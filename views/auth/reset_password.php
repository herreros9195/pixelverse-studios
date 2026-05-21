<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-lg-5 col-md-7">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <p class="eyebrow">Securite</p>
                <h1 class="h3 mb-3">Renouveler le mot de passe</h1>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <?php if ($user): ?>
                    <p class="text-muted">Definition d'un nouveau mot de passe securise pour le compte <strong><?= htmlspecialchars($user['pseudo'], ENT_QUOTES, 'UTF-8') ?></strong>.</p>
                    <form method="POST" action="/index.php?action=reset-password">
                        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                        <input type="hidden" name="id" value="<?= (int) $user['id'] ?>">
                        <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">
                        <div class="mb-3">
                            <label for="password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" required pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{8,}" title="8 caracteres minimum avec majuscule, minuscule, chiffre et caractere special">
                        </div>
                        <div class="mb-3">
                            <label for="password_confirm" class="form-label">Confirmation</label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Enregistrer le mot de passe</button>
                    </form>
                <?php else: ?>
                    <div class="text-center small mt-3">
                        <a href="/index.php?action=forgot-password">Demander un nouveau lien</a>
                        |
                        <a href="/index.php?action=login">Retour a la connexion</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
