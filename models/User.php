<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private static array $columnSupport = [];

    private static function hasColumn($column) {
        if (array_key_exists($column, self::$columnSupport)) {
            return self::$columnSupport[$column];
        }

        $db = getDB();
        $stmt = $db->prepare(
            "SELECT 1
             FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = 'users'
               AND COLUMN_NAME = ?
             LIMIT 1"
        );
        $stmt->execute([$column]);
        self::$columnSupport[$column] = (bool) $stmt->fetchColumn();

        return self::$columnSupport[$column];
    }

    private static function ensurePasswordResetColumns() {
        $missingColumns = [];

        if (!self::hasColumn('password_reset_token')) {
            $missingColumns[] = "ADD COLUMN password_reset_token VARCHAR(128) DEFAULT NULL AFTER password_hash";
        }

        if (!self::hasColumn('password_reset_expires_at')) {
            $afterColumn = self::hasColumn('password_reset_token') || in_array("ADD COLUMN password_reset_token VARCHAR(128) DEFAULT NULL AFTER password_hash", $missingColumns, true)
                ? 'password_reset_token'
                : 'password_hash';
            $missingColumns[] = "ADD COLUMN password_reset_expires_at DATETIME DEFAULT NULL AFTER {$afterColumn}";
        }

        if ($missingColumns === []) {
            return;
        }

        $db = getDB();
        $db->exec("ALTER TABLE users " . implode(', ', $missingColumns));

        self::$columnSupport['password_reset_token'] = true;
        self::$columnSupport['password_reset_expires_at'] = true;
    }

    public static function getAll() {
        $db = getDB();
        $stmt = $db->query(
            "SELECT id, email, pseudo, role, status, created_at
             FROM users
             ORDER BY created_at DESC"
        );
        return $stmt->fetchAll();
    }

    public static function getManageableUsers() {
        $db = getDB();
        $stmt = $db->query(
            "SELECT id, email, pseudo, role, status, created_at
             FROM users
             WHERE role = 'user'
             ORDER BY created_at DESC"
        );
        return $stmt->fetchAll();
    }

    public static function findByEmail($email) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public static function findByPseudo($pseudo) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE pseudo = ? LIMIT 1");
        $stmt->execute([$pseudo]);
        return $stmt->fetch();
    }

    public static function findById($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function findByResetToken($userId, $token) {
        self::ensurePasswordResetColumns();

        $db = getDB();
        $stmt = $db->prepare(
            "SELECT *
             FROM users
             WHERE id = ?
               AND password_reset_token = ?
               AND password_reset_expires_at IS NOT NULL
               AND password_reset_expires_at >= NOW()
             LIMIT 1"
        );
        $stmt->execute([$userId, $token]);
        return $stmt->fetch();
    }

    public static function create($email, $pseudo, $password) {
        $db = getDB();
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $db->prepare(
            "INSERT INTO users (email, pseudo, password_hash)
             VALUES (?, ?, ?)"
        );
        return $stmt->execute([$email, $pseudo, $hash]);
    }

    public static function updatePassword($email, $newPassword) {
        $db = getDB();
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $db->prepare(
            "UPDATE users
             SET password_hash = ?
             WHERE email = ?"
        );
        return $stmt->execute([$hash, $email]);
    }

    public static function updatePasswordById($id, $newPassword) {
        $db = getDB();
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $db->prepare(
            "UPDATE users
             SET password_hash = ?
             WHERE id = ?"
        );
        return $stmt->execute([$hash, $id]);
    }

    public static function setPasswordResetToken($id, $token, $expiresAt = null) {
        self::ensurePasswordResetColumns();

        $db = getDB();
        if ($expiresAt === null) {
            $stmt = $db->prepare(
                "UPDATE users
                 SET password_reset_token = ?,
                     password_reset_expires_at = DATE_ADD(NOW(), INTERVAL 1 HOUR)
                 WHERE id = ?"
            );
            return $stmt->execute([$token, $id]);
        }

        $stmt = $db->prepare(
            "UPDATE users
             SET password_reset_token = ?, password_reset_expires_at = ?
             WHERE id = ?"
        );
        return $stmt->execute([$token, $expiresAt, $id]);
    }

    public static function clearPasswordResetToken($id) {
        self::ensurePasswordResetColumns();

        $db = getDB();
        $stmt = $db->prepare(
            "UPDATE users
             SET password_reset_token = NULL, password_reset_expires_at = NULL
             WHERE id = ?"
        );
        return $stmt->execute([$id]);
    }

    public static function updateStatus($id, $status) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE users SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public static function delete($id) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function createEmployee($email, $password) {
        $db = getDB();
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $pseudo = 'emp_' . bin2hex(random_bytes(4));
        $stmt = $db->prepare(
            "INSERT INTO users (email, pseudo, password_hash, role)
             VALUES (?, ?, ?, 'employee')"
        );
        return $stmt->execute([$email, $pseudo, $hash]);
    }
}
