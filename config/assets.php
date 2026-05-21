<?php
/**
 * Détecte le chemin correct des assets selon la configuration du serveur.
 * - Serveur pointant vers la racine du projet : public/assets/
 * - Serveur pointant déjà vers public/ : /assets/
 */

$publicDir = realpath(__DIR__ . '/../public');
$documentRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');

$normalizePath = static function ($path) {
    return rtrim(str_replace('\\', '/', (string) $path), '/');
};

$isPublicDocumentRoot = $publicDir !== false
    && $documentRoot !== false
    && $normalizePath($publicDir) === $normalizePath($documentRoot);

if (php_sapi_name() === 'cli-server') {
    define('ASSETS_URL', $isPublicDocumentRoot ? '/assets/' : 'public/assets/');
} else {
    define('ASSETS_URL', '/assets/');
}
