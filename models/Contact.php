<?php
require_once __DIR__ . '/../config/database.php';

class Contact {
    public static function create($email, $pseudo, $message) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO contact_requests (email, pseudo, message) VALUES (?, ?, ?)");
        return $stmt->execute([$email, $pseudo, $message]);
    }
}
