<?php
if (!headers_sent()) {
    header('Content-Type: text/html; charset=UTF-8');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'PixelVerse Studios', ENT_QUOTES, 'UTF-8') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>css/style.css?v=4">
    <?php if (!empty($pageCss)): ?>
        <?php foreach ((array) $pageCss as $css): ?>
            <link rel="stylesheet" href="<?= htmlspecialchars($css, ENT_QUOTES, 'UTF-8') ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <script>window.ASSETS_URL = '<?= ASSETS_URL ?>';</script>
    <script type="importmap">
    {
      "imports": {
        "three": "https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js",
        "three/addons/": "https://cdn.jsdelivr.net/npm/three@0.160.0/examples/jsm/"
      }
    }
    </script>
    <style>
        :root { --app-bg:#0B0E17; --app-surface:#111827; --app-text:#F5F0E6; --app-muted:#A39B8B; --app-border:rgba(212,175,55,0.15); --app-primary:#D4AF37; --app-primary-soft:rgba(212,175,55,0.1); }
        body { background: var(--app-bg) !important; color: var(--app-text) !important; font-family: 'Inter', system-ui, sans-serif; }
        .card { background: var(--app-surface) !important; color: var(--app-text) !important; border-color: var(--app-border) !important; }
        .site-header { position: fixed; top: 0; left: 0; right: 0; z-index: 50; background: rgba(11,14,23,0.9) !important; backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid rgba(212,175,55,0.1); }
        .site-header .container { max-width: 80rem; }
        .btn-primary { background: var(--app-primary) !important; border-color: var(--app-primary) !important; color: #0B0E17 !important; font-weight: 600; }
        .btn-primary:hover { background: #E8D589 !important; border-color: #E8D589 !important; }
        .eyebrow { color: var(--app-primary); letter-spacing: 0.2em; font-size: 0.85rem; text-transform: uppercase; }
        .page-title { color: var(--app-text); font-weight: 700; font-family: 'Cinzel', serif; }
    </style>
</head>
<body>
<a class="visually-hidden-focusable" href="#main-content" style="position:absolute;top:1rem;left:1rem;z-index:100;background:#D4AF37;color:#0B0E17;padding:0.5rem 1rem;border-radius:0.5rem;font-weight:bold;">Aller au contenu principal</a>

<header class="site-header">
    <div class="container h-100">
        <nav class="navbar navbar-expand-lg" style="padding: 0; height: 4rem;">
            <div class="container-fluid" style="padding: 0;">
                <a class="navbar-brand d-flex align-items-center gap-2" href="/index.php?action=home" style="color: #D4AF37; font-family: 'Cinzel', serif; font-weight: 700; font-size: 1.125rem; letter-spacing: 0.05em; padding: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #D4AF37;"><polyline points="14.5 17.5 3 6 3 3 6 3 17.5 14.5"></polyline><line x1="13" y1="19" x2="19" y2="13"></line><line x1="16" y1="16" x2="20" y2="20"></line><line x1="19" y1="21" x2="21" y2="19"></line></svg>
                    <span>FantasyRealm</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Ouvrir le menu" style="border-color: rgba(212,175,55,0.3);">
                    <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto align-items-center gap-1">
                        <?php
                        $navLinks = [
                            ['url' => '/index.php?action=home', 'label' => 'Accueil'],
                            ['url' => '/index.php?action=characters', 'label' => 'Personnages'],
                            ['url' => '/index.php?action=contact', 'label' => 'Contact'],
                        ];
                        foreach ($navLinks as $link):
                            $isActive = ($_SERVER['REQUEST_URI'] ?? '') === $link['url'];
                        ?>
                            <li class="nav-item">
                                <a class="nav-link px-3 py-2 rounded" href="<?= $link['url'] ?>" style="font-size: 0.875rem; font-weight: 500; transition: all 0.2s; <?= $isActive ? 'color: #D4AF37; background: rgba(212,175,55,0.1);' : 'color: #A39B8B;' ?>" onmouseover="this.style.color='#F5F0E6';this.style.background='rgba(255,255,255,0.05)';" onmouseout="this.style.color='<?= $isActive ? '#D4AF37' : '#A39B8B' ?>';this.style.background='<?= $isActive ? 'rgba(212,175,55,0.1)' : 'transparent' ?>';">
                                    <?= htmlspecialchars($link['label'], ENT_QUOTES, 'UTF-8') ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <ul class="navbar-nav align-items-center gap-2">
                        <?php if (isLoggedIn()): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #F5F0E6; font-size: 0.875rem;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    <span class="d-none d-lg-inline" style="max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?= htmlspecialchars($_SESSION['pseudo'] ?? 'Compte', ENT_QUOTES, 'UTF-8') ?></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" style="background: #1F2937; border-color: rgba(212,175,55,0.2); min-width: 14rem;">
                                    <li class="px-3 py-2" style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                                        <p class="mb-0" style="font-size: 0.875rem; font-weight: 500; color: #F5F0E6;"><?= htmlspecialchars($_SESSION['pseudo'] ?? 'Compte', ENT_QUOTES, 'UTF-8') ?></p>
                                        <p class="mb-0" style="font-size: 0.75rem; color: #A39B8B;"><?= htmlspecialchars($_SESSION['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                                        <?php if (!empty($_SESSION['role'])): ?>
                                            <p class="mb-0 mt-1" style="font-size: 0.75rem; color: #D4AF37; text-transform: capitalize;"><?= htmlspecialchars($_SESSION['role'], ENT_QUOTES, 'UTF-8') ?></p>
                                        <?php endif; ?>
                                    </li>
                                    <li><a class="dropdown-item" href="/index.php?action=dashboard" style="color: #F5F0E6; font-size: 0.875rem; padding: 0.5rem 1rem;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem;"><rect width="7" height="9" x="3" y="3" rx="1"></rect><rect width="7" height="5" x="14" y="3" rx="1"></rect><rect width="7" height="9" x="14" y="12" rx="1"></rect><rect width="7" height="5" x="3" y="16" rx="1"></rect></svg>
                                        Tableau de bord
                                    </a></li>
                                    <?php if (!empty($_SESSION['role']) && in_array($_SESSION['role'], ['employee', 'admin'], true)): ?>
                                        <li><a class="dropdown-item" href="/index.php?action=employee-dashboard" style="color: #F5F0E6; font-size: 0.875rem; padding: 0.5rem 1rem;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem;"><rect width="20" height="14" x="2" y="7" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                                            Espace employe
                                        </a></li>
                                    <?php endif; ?>
                                    <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                        <li><a class="dropdown-item" href="/index.php?action=admin-dashboard" style="color: #F5F0E6; font-size: 0.875rem; padding: 0.5rem 1rem;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem;"><path d="M11.562 3.266a.5.5 0 0 1 .876 0L15.39 8.87a1 1 0 0 0 1.516.294L21.183 5.5a.5.5 0 0 1 .798.519l-2.834 10.246a1 1 0 0 1-.956.734H5.81a1 1 0 0 1-.957-.734L2.02 6.02a.5.5 0 0 1 .798-.519l4.276 3.664a1 1 0 0 0 1.516-.294z"></path><path d="M5 21h14"></path></svg>
                                            Espace admin
                                        </a></li>
                                    <?php endif; ?>
                                    <li><hr class="dropdown-divider" style="border-color: rgba(255,255,255,0.1);"></li>
                                    <li><a class="dropdown-item" href="/index.php?action=logout" style="color: #fca5a5; font-size: 0.875rem; padding: 0.5rem 1rem;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem;"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                        Deconnexion
                                    </a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="btn btn-primary btn-sm" href="/index.php?action=login" style="font-weight: 600; padding: 0.375rem 1rem;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.25rem;"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                                    Connexion
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>

<div style="height: 4rem;"></div>

<?php $mainClass = !empty($fluidMain) ? '' : 'container'; ?>
<main id="main-content" class="<?= $mainClass ?> <?= empty($fluidMain) ? 'py-4' : '' ?>">
<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
<?php endif; ?>
<?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
<?php endif; ?>
