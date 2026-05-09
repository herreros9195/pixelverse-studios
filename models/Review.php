<?php
require_once __DIR__ . '/../config/database.php';

class Review {
    public static function getByCharacterId($characterId, $status = 'approved') {
        $db = getDB();
        $stmt = $db->prepare("SELECT r.*, u.pseudo as reviewer_pseudo FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE r.character_id = ? AND r.status = ? ORDER BY r.created_at DESC");
        $stmt->execute([$characterId, $status]);
        return $stmt->fetchAll();
    }

    public static function getPending() {
        $db = getDB();
        $stmt = $db->query("SELECT r.*, u.pseudo as reviewer_pseudo, c.name as character_name, u2.email as owner_email 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            JOIN characters c ON r.character_id = c.id 
            JOIN users u2 ON c.user_id = u2.id 
            WHERE r.status = 'pending' ORDER BY r.created_at DESC");
        return $stmt->fetchAll();
    }

    public static function create($characterId, $userId, $rating, $comment) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO reviews (character_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$characterId, $userId, $rating, $comment]);
    }

    public static function updateStatus($id, $status) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE reviews SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public static function delete($id) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM reviews WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
