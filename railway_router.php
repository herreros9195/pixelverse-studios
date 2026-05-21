<?php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$uri = urldecode($uri);

if ($uri !== '/' && strpos($uri, '..') === false) {
    $publicFile = __DIR__ . '/public' . $uri;

    if (is_file($publicFile)) {
        $ext = strtolower(pathinfo($publicFile, PATHINFO_EXTENSION));

        $mimeTypes = [
            'css'  => 'text/css; charset=UTF-8',
            'js'   => 'application/javascript; charset=UTF-8',
            'mjs'  => 'application/javascript; charset=UTF-8',
            'json' => 'application/json; charset=UTF-8',

            'png'  => 'image/png',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'webp' => 'image/webp',
            'gif'  => 'image/gif',
            'svg'  => 'image/svg+xml',
            'ico'  => 'image/x-icon',

            'glb'  => 'model/gltf-binary',
            'gltf' => 'model/gltf+json',
            'bin'  => 'application/octet-stream',

            'mp3'  => 'audio/mpeg',
            'mp4'  => 'video/mp4',

            'woff'  => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf'   => 'font/ttf',
            'otf'   => 'font/otf',
        ];

        header('Content-Type: ' . ($mimeTypes[$ext] ?? 'application/octet-stream'));
        header('Content-Length: ' . filesize($publicFile));
        readfile($publicFile);
        return true;
    }
}

require __DIR__ . '/public/index.php';
