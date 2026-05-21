<?php
require_once __DIR__ . '/../config/database.php';

class Character {
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
               AND TABLE_NAME = 'characters'
               AND COLUMN_NAME = ?
             LIMIT 1"
        );
        $stmt->execute([$column]);
        self::$columnSupport[$column] = (bool) $stmt->fetchColumn();

        return self::$columnSupport[$column];
    }

    public static function getAllShared($filters = [], $page = 1, $perPage = 9) {
        $db = getDB();
        $sql = "SELECT c.*, u.pseudo AS creator_pseudo
                FROM characters c
                JOIN users u ON c.user_id = u.id
                WHERE c.shared = 1 AND c.status = 'approved'";
        $params = [];

        if (!empty($filters['gender'])) {
            $sql .= " AND c.gender = ?";
            $params[] = $filters['gender'];
        }
        if (!empty($filters['date_from'])) {
            $sql .= " AND c.created_at >= ?";
            $params[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND c.created_at <= ?";
            $params[] = $filters['date_to'];
        }
        if (!empty($filters['pseudo'])) {
            $sql .= " AND u.pseudo LIKE ?";
            $params[] = '%' . $filters['pseudo'] . '%';
        }

        $countSql = str_replace("SELECT c.*, u.pseudo AS creator_pseudo", "SELECT COUNT(*) AS total", $sql);
        $countStmt = $db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int) ($countStmt->fetch()['total'] ?? 0);

        $sql .= " ORDER BY c.created_at DESC LIMIT " . (int) $perPage . " OFFSET " . (int) (($page - 1) * $perPage);
        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        return [
            'items' => $stmt->fetchAll(),
            'total' => $total,
            'pages' => max(1, (int) ceil($total / $perPage)),
            'page' => $page,
        ];
    }

    public static function getById($id) {
        $db = getDB();
        $stmt = $db->prepare(
            "SELECT c.*, u.pseudo AS creator_pseudo, u.email AS creator_email
             FROM characters c
             JOIN users u ON c.user_id = u.id
             WHERE c.id = ?
             LIMIT 1"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function getByUserId($userId) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM characters WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function getPending() {
        $db = getDB();
        $stmt = $db->query(
            "SELECT c.*, u.pseudo AS creator_pseudo, u.email AS creator_email
             FROM characters c
             JOIN users u ON c.user_id = u.id
             WHERE c.status = 'pending'
             ORDER BY c.created_at DESC"
        );
        return $stmt->fetchAll();
    }

    public static function getAllForModeration($limit = 20) {
        $db = getDB();
        $stmt = $db->query(
            "SELECT c.*, u.pseudo AS creator_pseudo
             FROM characters c
             JOIN users u ON c.user_id = u.id
             ORDER BY c.created_at DESC
             LIMIT " . (int) $limit
        );
        return $stmt->fetchAll();
    }

    public static function create($data) {
        $db = getDB();
        $columns = ['user_id', 'name', 'gender'];
        $values = [$data['user_id'], $data['name'], $data['gender']];

        if (self::hasColumn('body_style')) {
            $columns[] = 'body_style';
            $values[] = $data['body_style'] ?? null;
        }

        if (self::hasColumn('ear_shape')) {
            $columns[] = 'ear_shape';
            $values[] = $data['ear_shape'] ?? null;
        }

        $columns = array_merge($columns, [
            'eye_shape',
            'nose_shape',
            'mouth_shape',
            'skin_color',
            'hair_style',
            'hair_color',
            'eye_color',
            'character_type',
        ]);
        $values = array_merge($values, [
            $data['eye_shape'],
            $data['nose_shape'],
            $data['mouth_shape'],
            $data['skin_color'],
            $data['hair_style'],
            $data['hair_color'],
            $data['eye_color'],
            $data['character_type'],
        ]);

        if (self::hasColumn('outfit_variant')) {
            $columns[] = 'outfit_variant';
            $values[] = $data['outfit_variant'] ?? 'auto';
        }

        $placeholders = implode(', ', array_fill(0, count($columns), '?'));
        $stmt = $db->prepare(
            "INSERT INTO characters (" . implode(', ', $columns) . ")
            VALUES (" . $placeholders . ")"
        );

        $ok = $stmt->execute($values);

        if (!$ok) {
            return false;
        }

        return (int) $db->lastInsertId();
    }

    public static function update($id, $data) {
        $db = getDB();
        $assignments = [];
        $values = [];

        if (self::hasColumn('body_style')) {
            $assignments[] = 'body_style = ?';
            $values[] = $data['body_style'] ?? null;
        }

        if (self::hasColumn('ear_shape')) {
            $assignments[] = 'ear_shape = ?';
            $values[] = $data['ear_shape'] ?? null;
        }

        $assignments = array_merge($assignments, [
            'eye_shape = ?',
            'nose_shape = ?',
            'mouth_shape = ?',
            'skin_color = ?',
            'hair_style = ?',
            'hair_color = ?',
            'eye_color = ?',
            'character_type = ?',
        ]);
        $values = array_merge($values, [
            $data['eye_shape'],
            $data['nose_shape'],
            $data['mouth_shape'],
            $data['skin_color'],
            $data['hair_style'],
            $data['hair_color'],
            $data['eye_color'],
            $data['character_type'],
        ]);

        if (self::hasColumn('outfit_variant')) {
            $assignments[] = 'outfit_variant = ?';
            $values[] = $data['outfit_variant'] ?? 'auto';
        }

        $values[] = $id;

        $stmt = $db->prepare(
            "UPDATE characters
             SET " . implode(",\n                 ", $assignments) . "
             WHERE id = ?"
        );

        return $stmt->execute($values);
    }

    public static function updateStatus($id, $status, $reason = null) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE characters SET status = ?, rejection_reason = ? WHERE id = ?");
        return $stmt->execute([$status, $reason, $id]);
    }

    public static function toggleShare($id, $shared) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE characters SET shared = ? WHERE id = ?");
        return $stmt->execute([(int) $shared, $id]);
    }

    public static function delete($id) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM characters WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function duplicate($id, $newName) {
        $db = getDB();
        $original = self::getById($id);
        if (!$original) {
            return false;
        }

        $columns = ['user_id', 'name', 'gender'];
        $values = [$original['user_id'], $newName, $original['gender']];

        if (self::hasColumn('body_style')) {
            $columns[] = 'body_style';
            $values[] = $original['body_style'] ?? null;
        }

        if (self::hasColumn('ear_shape')) {
            $columns[] = 'ear_shape';
            $values[] = $original['ear_shape'] ?? null;
        }

        $columns = array_merge($columns, [
            'eye_shape',
            'nose_shape',
            'mouth_shape',
            'skin_color',
            'hair_style',
            'hair_color',
            'eye_color',
            'character_type',
            'status',
            'shared',
        ]);
        $values = array_merge($values, [
            $original['eye_shape'],
            $original['nose_shape'],
            $original['mouth_shape'],
            $original['skin_color'],
            $original['hair_style'] ?? (($original['gender'] ?? 'male') === 'female' ? 'hair_07' : 'hair_02'),
            $original['hair_color'],
            $original['eye_color'],
            $original['character_type'],
            'pending',
            0,
        ]);

        if (self::hasColumn('outfit_variant')) {
            $columns[] = 'outfit_variant';
            $values[] = $original['outfit_variant'] ?? 'auto';
        }

        $placeholders = implode(', ', array_fill(0, count($columns), '?'));
        $stmt = $db->prepare(
            "INSERT INTO characters (" . implode(', ', $columns) . ")
            VALUES (" . $placeholders . ")"
        );
        $ok = $stmt->execute($values);

        if (!$ok) {
            return false;
        }

        $newId = (int) $db->lastInsertId();
        $accessoryStmt = $db->prepare(
            "INSERT INTO character_accessories (character_id, accessory_id)
             SELECT ?, accessory_id
             FROM character_accessories
             WHERE character_id = ?"
        );
        $accessoryStmt->execute([$newId, $id]);

        return $newId;
    }

    /**
     * Compatibilite avec les anciennes routes.
     * En v29 Full Synty, l'avatar 3D est affiche dynamiquement par Three.js :
     * on ne stocke plus portrait_path ni avatar_3d_url dans characters.
     */
    public static function updatePortraitPath($id, $path) {
        return false;
    }

    public static function updateAvatar3dUrl($id, $url) {
        return false;
    }
}
