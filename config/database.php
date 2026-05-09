<?php
/**
 * Configuration de la base de donnees relationnelle (MySQL)
 * Supporte DATABASE_URL (Railway) et config locale (WAMP)
 */

// Priorite a la variable d'environnement Railway
if (!empty(getenv('DATABASE_URL'))) {
    $dbUrl = parse_url(getenv('DATABASE_URL'));
    define('DB_HOST', $dbUrl['host'] ?? 'localhost');
    define('DB_PORT', $dbUrl['port'] ?? 3306);
    define('DB_NAME', ltrim($dbUrl['path'] ?? '/pixelverse', '/'));
    define('DB_USER', $dbUrl['user'] ?? 'root');
    define('DB_PASS', $dbUrl['pass'] ?? '');
} else {
    // Configuration locale WAMP64
    define('DB_HOST', 'localhost');
    define('DB_PORT', 3307);
    define('DB_NAME', 'pixelverse');
    define('DB_USER', 'root');
    define('DB_PASS', '');
}

define('DB_CHARSET', 'utf8mb4');

function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die("Erreur de connexion a la base de donnees : " . $e->getMessage());
        }
    }
    return $pdo;
}
