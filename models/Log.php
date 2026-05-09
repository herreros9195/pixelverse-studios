<?php
$mongoConfig = __DIR__ . '/../config/mongodb.php';
if (file_exists($mongoConfig)) {
    require_once $mongoConfig;
}

class Log {
    public static function add($action, $details = []) {
        try {
            if (!function_exists('getMongoDB')) return;
            $db = getMongoDB();
            $collection = $db->selectCollection('activity_logs');
            $collection->insertOne([
                'action' => $action,
                'details' => $details,
                'user_id' => $_SESSION['user_id'] ?? null,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
                'created_at' => new MongoDB\BSON\UTCDateTime()
            ]);
        } catch (Exception $e) {
            // Silencieux pour ne pas bloquer l'application si MongoDB est indisponible
            error_log("MongoDB Log error: " . $e->getMessage());
        }
    }

    public static function getAll($limit = 100) {
        try {
            if (!function_exists('getMongoDB')) return [];
            $db = getMongoDB();
            $collection = $db->selectCollection('activity_logs');
            return $collection->find([], ['limit' => $limit, 'sort' => ['created_at' => -1]])->toArray();
        } catch (Exception $e) {
            return [];
        }
    }
}
