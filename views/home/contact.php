<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="page-intro">
    <div>
        <p class="eyebrow">Support</p>
        <h1 class="page-title">Contacter PixelVerse Studios</h1>
        <p class="page-copy">Le formulaire rattache chaque demande a un pseudo deja present sur la plateforme afin de faciliter le suivi et la moderation.</p>
    </div>
</section>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="/index.php?action=contact">
                    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required value="<?= htmlspecialchars($_SESSION['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="pseudo" class="form-label">Pseudo</label>
                            <input type="text" class="form-control" id="pseudo" name="pseudo" required value="<?= htmlspecialchars($_SESSION['pseudo'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                        <div class="col-12">
                            <label for="message" class="form-label">Detail de la demande</label>
                            <textarea class="form-control" id="message" name="message" rows="6" required></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-4">Envoyer la demande</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <p class="eyebrow">Traitement</p>
                <h2 class="h5 mb-3">Elements utiles</h2>
                <div class="character-traits">
                    <span class="trait-pill">Pseudo existant</span>
                    <span class="trait-pill">Email valide</span>
                    <span class="trait-pill">Contexte precis</span>
                </div>
                <p class="text-muted mt-3 mb-0">Les demandes sont journalisees et orientees vers la moderation ou l'administration selon le sujet declare.</p>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
