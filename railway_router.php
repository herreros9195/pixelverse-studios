<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$uri = urldecode($uri);

if ($uri !== '/' && strpos($uri, '..') === false) {
    $publicFile = __DIR__ . '/public' . $uri;

    if (is_file($publicFile) && strtolower(pathinfo($publicFile, PATHINFO_EXTENSION)) !== 'php') {
        return false;
    }
}

require __DIR__ . '/public/index.php';