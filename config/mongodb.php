<?php
/**
 * Configuration de la base de donnees NoSQL (MongoDB)
 * Utilisee pour les logs d'activite des personnages
 * Supporte MONGODB_URI (Railway/Atlas) et localhost par defaut
 */

$autoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
}

function getMongoDB() {
    static $db = null;
    if ($db === null) {
        if (!class_exists('MongoDB\Client')) {
            throw new Exception("Extension MongoDB non disponible. Installez-la via 'composer require mongodb/mongodb' ou activez l'extension PHP mongodb.");
        }
        try {
            // Priorite a la variable d'environnement Railway/Atlas
            $uri = getenv('MONGODB_URI') ?: "mongodb://localhost:27017";
            $client = new MongoDB\Client($uri);
            $db = $client->selectDatabase('pixelverse_logs');
        } catch (Exception $e) {
            throw new Exception("Erreur de connexion a MongoDB : " . $e->getMessage());
        }
    }
    return $db;
}
