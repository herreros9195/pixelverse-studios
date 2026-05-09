<?php require __DIR__ . '/../layouts/header.php'; ?>

<h1>Créer un compte employé</h1>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <form method="POST" action="/index.php?action=admin-create-employee">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required
                       pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{8,}"
                       title="8 caractères min, 1 majuscule, 1 minuscule, 1 chiffre, 1 spécial">
                <div class="form-text">Min. 8 caractères, 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial.</div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Créer le compte employé</button>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
