<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <h1 class="mb-4">Créer un compte</h1>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <form method="POST" action="/index.php?action=register">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <div class="mb-3">
                <label for="email" class="form-label">Email (unique)</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="pseudo" class="form-label">Pseudo (unique)</label>
                <input type="text" class="form-control" id="pseudo" name="pseudo" required minlength="3">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required
                       pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{8,}"
                       title="8 caractères min, 1 majuscule, 1 minuscule, 1 chiffre, 1 spécial">
                <div class="form-text">Min. 8 caractères, 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial.</div>
            </div>
            <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
        </form>
        <div class="mt-3 text-center">
            <a href="/index.php?action=login">Déjà un compte ? Se connecter</a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
