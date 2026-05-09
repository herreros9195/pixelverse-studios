<?php
require_once __DIR__ . '/../config/database.php';

class User {
    public static function getAll() {
        $db = getDB();
        $stmt = $db->query("SELECT id, email, pseudo, role, status, created_at FROM users");
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
        $stmt = $db->prepare("SELECT id, email, pseudo, role, status, created_at FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($email, $pseudo, $password) {
        $db = getDB();
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $db->prepare("INSERT INTO users (email, pseudo, password_hash) VALUES (?, ?, ?)");
        return $stmt->execute([$email, $pseudo, $hash]);
    }

    public static function updatePassword($email, $newPassword) {
        $db = getDB();
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $db->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
        return $stmt->execute([$hash, $email]);
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
        $stmt = $db->prepare("INSERT INTO users (email, pseudo, password_hash, role) VALUES (?, ?, ?, 'employee')");
        return $stmt->execute([$email, $pseudo, $hash]);
    }
}
