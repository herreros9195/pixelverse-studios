<?php require __DIR__ . '/../layouts/header.php'; ?>

<h1>Contactez-nous</h1>
<p class="lead">Une question ? Un problème ? Remplissez le formulaire ci-dessous.</p>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form method="POST" action="/index.php?action=contact" class="mt-4">
    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required
               value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label for="pseudo" class="form-label">Pseudo</label>
        <input type="text" class="form-control" id="pseudo" name="pseudo" required
               value="<?= htmlspecialchars($_SESSION['pseudo'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label for="message" class="form-label">Détail de la demande</label>
        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Envoyer</button>
</form>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
