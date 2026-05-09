<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'PixelVerse Studios') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/index.php?action=home">PixelVerse Studios</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="/index.php?action=home">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="/index.php?action=characters">Personnages</a></li>
                <li class="nav-item"><a class="nav-link" href="/index.php?action=contact">Contact</a></li>
            </ul>
            <ul class="navbar-nav">
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <li class="nav-item"><a class="nav-link" href="/index.php?action=admin-dashboard">Admin</a></li>
                    <?php elseif (isEmployee()): ?>
                        <li class="nav-item"><a class="nav-link" href="/index.php?action=employee-dashboard">Employé</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="/index.php?action=dashboard">Mon Espace</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="/index.php?action=logout">Déconnexion (<?= htmlspecialchars($_SESSION['pseudo'] ?? '') ?>)</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="/index.php?action=login">Connexion</a></li>
                    <li class="nav-item"><a class="nav-link" href="/index.php?action=register">Créer un compte</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<main class="container py-4">
<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= htmlspecialchars($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= htmlspecialchars($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
