<?php
/**
 * Gestion des sessions sécurisées
 */
session_start();

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
