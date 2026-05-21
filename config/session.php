<?php
/**
 * Gestion des sessions securisees
 */

$appTimezone = getenv('PHP_TIMEZONE') ?: 'Europe/Paris';
if (@date_default_timezone_get() !== $appTimezone) {
    date_default_timezone_set($appTimezone);
}

if (session_status() === PHP_SESSION_NONE) {
    $sessionDirectories = [
        dirname(__DIR__) . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'sessions',
        sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'pixelverse-sessions',
    ];

    $sessionPath = null;

    foreach ($sessionDirectories as $candidate) {
        if (!is_dir($candidate) && !@mkdir($candidate, 0775, true) && !is_dir($candidate)) {
            continue;
        }

        if (is_writable($candidate)) {
            $sessionPath = $candidate;
            break;
        }
    }

    if ($sessionPath !== null) {
        session_save_path($sessionPath);
    }

    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');

    $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

    session_name('PIXELVERSESESSID');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isUser() {
    return isLoggedIn() && ($_SESSION['role'] ?? '') === 'user';
}

function isEmployee() {
    return isLoggedIn() && ($_SESSION['role'] ?? '') === 'employee';
}

function isAdmin() {
    return isLoggedIn() && ($_SESSION['role'] ?? '') === 'admin';
}

function requireAuth() {
    if (!isLoggedIn()) {
        header('Location: /index.php?action=login');
        exit;
    }
}

function requireRole($role) {
    requireAuth();
    $currentRole = $_SESSION['role'] ?? '';
    if ($currentRole !== $role && $currentRole !== 'admin') {
        header('Location: /index.php?action=home');
        exit;
    }
}

function csrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrf($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function isPostRequest() {
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}
