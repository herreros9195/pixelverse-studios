<?php
require_once __DIR__ . '/../config/database.php';

class Character {
    public static function getAllShared($filters = [], $page = 1, $perPage = 9) {
        $db = getDB();
        $sql = "SELECT c.*, u.pseudo as creator_pseudo FROM characters c JOIN users u ON c.user_id = u.id WHERE c.shared = 1 AND c.status = 'approved'";
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

        // Count total
        $countSql = str_replace("SELECT c.*, u.pseudo as creator_pseudo", "SELECT COUNT(*) as total", $sql);
        $countStmt = $db->prepare($countSql);
        $countStmt->execute($params);
        $total = $countStmt->fetch()['total'];

        $sql .= " ORDER BY c.created_at DESC LIMIT " . (int)$perPage . " OFFSET " . (int)(($page - 1) * $perPage);
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return ['items' => $stmt->fetchAll(), 'total' => $total, 'pages' => ceil($total / $perPage), 'page' => $page];
    }

    public static function getById($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT c.*, u.pseudo as creator_pseudo FROM characters c JOIN users u ON c.user_id = u.id WHERE c.id = ? LIMIT 1");
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
        $stmt = $db->query("SELECT c.*, u.pseudo as creator_pseudo, u.email as creator_email FROM characters c JOIN users u ON c.user_id = u.id WHERE c.status = 'pending' ORDER BY c.created_at DESC");
        return $stmt->fetchAll();
    }

    public static function create($data) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO characters 
            (user_id, name, gender, eye_shape, nose_shape, mouth_shape, skin_color, hair_color, eye_color, character_type, build, age_group) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['user_id'], $data['name'], $data['gender'],
            $data['eye_shape'], $data['nose_shape'], $data['mouth_shape'],
            $data['skin_color'], $data['hair_color'], $data['eye_color'],
            $data['character_type'], $data['build'], $data['age_group']
        ]);
    }

    public static function update($id, $data) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE characters SET 
            eye_shape=?, nose_shape=?, mouth_shape=?, skin_color=?, hair_color=?, eye_color=?,
            character_type=?, build=?, age_group=?
            WHERE id=?");
        return $stmt->execute([
            $data['eye_shape'], $data['nose_shape'], $data['mouth_shape'],
            $data['skin_color'], $data['hair_color'], $data['eye_color'],
            $data['character_type'], $data['build'], $data['age_group'], $id
        ]);
    }

    public static function updateStatus($id, $status, $reason = null) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE characters SET status = ?, rejection_reason = ? WHERE id = ?");
        return $stmt->execute([$status, $reason, $id]);
    }

    public static function toggleShare($id, $shared) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE characters SET shared = ? WHERE id = ?");
        return $stmt->execute([$shared, $id]);
    }

    public static function delete($id) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM characters WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function duplicate($id, $newName) {
        $db = getDB();
        $original = self::getById($id);
        if (!$original) return false;

        $stmt = $db->prepare("INSERT INTO characters 
            (user_id, name, gender, eye_shape, nose_shape, mouth_shape, skin_color, hair_color, eye_color, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
        $ok = $stmt->execute([
            $original['user_id'], $newName, $original['gender'],
            $original['eye_shape'], $original['nose_shape'], $original['mouth_shape'],
            $original['skin_color'], $original['hair_color'], $original['eye_color'],
            $original['character_type'], $original['build'], $original['age_group']
        ]);
        if (!$ok) return false;
        $newId = $db->lastInsertId();

        // Copier les accessoires
        $accStmt = $db->prepare("INSERT INTO character_accessories (character_id, accessory_id) 
            SELECT ?, accessory_id FROM character_accessories WHERE character_id = ?");
        $accStmt->execute([$newId, $id]);
        return $newId;
    }
}
