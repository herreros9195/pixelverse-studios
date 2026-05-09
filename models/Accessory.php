<?php
require_once __DIR__ . '/../config/database.php';

class Accessory {
    public static function getAll($status = null) {
        $db = getDB();
        if ($status) {
            $stmt = $db->prepare("SELECT * FROM accessories WHERE status = ? ORDER BY type, name");
            $stmt->execute([$status]);
        } else {
            $stmt = $db->query("SELECT * FROM accessories ORDER BY type, name");
        }
        return $stmt->fetchAll();
    }

    public static function getById($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM accessories WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function getByCharacterId($characterId) {
        $db = getDB();
        $stmt = $db->prepare("SELECT a.* FROM accessories a 
            JOIN character_accessories ca ON a.id = ca.accessory_id 
            WHERE ca.character_id = ?");
        $stmt->execute([$characterId]);
        return $stmt->fetchAll();
    }

    public static function create($name, $type, $description, $imageUrl = null) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO accessories (name, type, description, image_url) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$name, $type, $description, $imageUrl]);
    }

    public static function updateStatus($id, $status) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE accessories SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public static function delete($id) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM accessories WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function addToCharacter($characterId, $accessoryId) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO character_accessories (character_id, accessory_id) VALUES (?, ?)");
        return $stmt->execute([$characterId, $accessoryId]);
    }

    public static function removeFromCharacter($characterId, $accessoryId) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM character_accessories WHERE character_id = ? AND accessory_id = ?");
        return $stmt->execute([$characterId, $accessoryId]);
    }
}
