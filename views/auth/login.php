<?php
$pageCss = [];
require __DIR__ . '/../layouts/header.php';
?>

<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-lg-5 col-md-7">
        <div class="card shadow-lg" style="background: var(--app-surface); border-color: var(--app-border);">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 3.5rem; height: 3.5rem; background: var(--app-primary-soft);">
                        <span style="font-size: 1.5rem;">Login</span>
                    </div>
                    <p class="eyebrow">Connexion</p>
                    <h1 class="h3 mb-2" style="color: var(--app-text);">Acces a l'espace compte</h1>
                    <p class="mb-0" style="color: var(--app-muted); font-size: 0.95rem;">Acces a la creation, a la moderation personnelle et au partage des personnages.</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <form method="POST" action="/index.php?action=login">
                    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="compte@email.com">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="********">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" style="font-size: 1rem;">Se connecter</button>
                </form>

                <div class="mt-4 text-center small" style="color: var(--app-muted);">
                    <a href="/index.php?action=forgot-password" style="color: var(--app-muted);">Mot de passe oublie</a>
                    <span class="mx-2">|</span>
                    <a href="/index.php?action=register" style="color: var(--app-primary);">Creer un compte</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
