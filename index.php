<?php
ob_start();
/**
 * Routeur racine de PixelVerse Studios
 * Permet de lancer le projet depuis la racine du dossier comme eval1
 * tout en conservant le front controller principal dans public/index.php
 */

require_once __DIR__ . '/public/index.php';
