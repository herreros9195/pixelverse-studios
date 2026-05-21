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
                        <span style="font-size: 1.5rem;">ID</span>
                    </div>
                    <p class="eyebrow">Inscription</p>
                    <h1 class="h3 mb-2" style="color: var(--app-text);">Creation d'un compte joueur</h1>
                    <p class="mb-0" style="color: var(--app-muted); font-size: 0.95rem;">Le compte donne acces a la creation des personnages, au suivi de moderation et au partage public.</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <form method="POST" action="/index.php?action=register">
                    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="compte@email.com">
                    </div>
                    <div class="mb-3">
                        <label for="pseudo" class="form-label">Pseudo</label>
                        <input type="text" class="form-control" id="pseudo" name="pseudo" required minlength="3" placeholder="Pseudo joueur">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            required
                            pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{8,}"
                            title="8 caracteres minimum avec majuscule, minuscule, chiffre et caractere special"
                            placeholder="********">
                        <div class="form-text">Minimum 8 caracteres avec majuscule, minuscule, chiffre et caractere special.</div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" style="font-size: 1rem;">Creer le compte</button>
                </form>

                <div class="mt-4 text-center small" style="color: var(--app-muted);">
                    <a href="/index.php?action=login" style="color: var(--app-primary);">Connexion</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
