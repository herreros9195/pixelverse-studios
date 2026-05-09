<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-img-top bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 250px;">
                <span class="fs-1">🧙‍♂️</span>
            </div>
        </div>
        
        <!-- Avatar Builder Visuel -->
        <div class="card mt-3">
            <div class="card-body text-center">
                <h5>Visualisation</h5>
                <?php require __DIR__ . '/_avatar_builder.php'; ?>
                <small class="text-muted">Aperçu selon les traits et accessoires équipés</small>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <h1><?= htmlspecialchars($character['name']) ?></h1>
        <p class="lead">Créé par <strong><?= htmlspecialchars($character['creator_pseudo']) ?></strong> le <?= date('d/m/Y', strtotime($character['created_at'])) ?></p>
        
        <h3 class="mt-4">Profil</h3>
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Type :</strong> <?= htmlspecialchars($character['character_type'] ?: 'Non défini') ?></li>
            <li class="list-group-item"><strong>Corpulence :</strong> <?= htmlspecialchars($character['build'] ?: 'Non défini') ?></li>
            <li class="list-group-item"><strong>Tranche d'âge :</strong> <?= htmlspecialchars($character['age_group'] ?: 'Non défini') ?></li>
            <li class="list-group-item"><strong>Genre :</strong> <?= ucfirst($character['gender']) ?></li>
        </ul>

        <h3 class="mt-4">Traits du visage</h3>
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Forme des yeux :</strong> <?= htmlspecialchars($character['eye_shape'] ?: 'Non défini') ?></li>
            <li class="list-group-item"><strong>Forme du nez :</strong> <?= htmlspecialchars($character['nose_shape'] ?: 'Non défini') ?></li>
            <li class="list-group-item"><strong>Forme de la bouche :</strong> <?= htmlspecialchars($character['mouth_shape'] ?: 'Non défini') ?></li>
            <li class="list-group-item"><strong>Couleur de peau :</strong> <?= htmlspecialchars($character['skin_color'] ?: 'Non défini') ?></li>
            <li class="list-group-item"><strong>Couleur des cheveux :</strong> <?= htmlspecialchars($character['hair_color'] ?: 'Non défini') ?></li>
            <li class="list-group-item"><strong>Couleur des yeux :</strong> <?= htmlspecialchars($character['eye_color'] ?: 'Non défini') ?></li>
        </ul>

        <h3 class="mt-4">Accessoires équipés</h3>
        <?php if (empty($accessories)): ?>
            <p class="text-muted">Aucun accessoire équipé.</p>
        <?php else: ?>
            <ul class="list-group list-group-flush">
                <?php foreach ($accessories as $acc): ?>
                    <li class="list-group-item"><?= htmlspecialchars($acc['name']) ?> <span class="badge bg-info"><?= ucfirst($acc['type']) ?></span></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>

<div class="mt-5">
    <h2>Avis et commentaires</h2>
    <?php if (empty($reviews)): ?>
        <p class="text-muted">Aucun avis pour le moment.</p>
    <?php else: ?>
        <?php foreach ($reviews as $rev): ?>
            <div class="card mb-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <strong><?= htmlspecialchars($rev['reviewer_pseudo']) ?></strong>
                        <span class="text-warning"><?= str_repeat('⭐', $rev['rating']) ?></span>
                    </div>
                    <p class="mb-0"><?= nl2br(htmlspecialchars($rev['comment'])) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (isLoggedIn() && isUser()): ?>
        <div class="card mt-3">
            <div class="card-body">
                <h5>Déposer un avis</h5>
                <form method="POST" action="/index.php?action=add-review">
                    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                    <input type="hidden" name="character_id" value="<?= $character['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label">Note</label>
                        <select name="rating" class="form-select" required>
                            <option value="5">5 - Excellent</option>
                            <option value="4">4 - Très bien</option>
                            <option value="3">3 - Bien</option>
                            <option value="2">2 - Moyen</option>
                            <option value="1">1 - Mauvais</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Commentaire</label>
                        <textarea name="comment" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </form>
            </div>
        </div>
    <?php elseif (!isLoggedIn()): ?>
        <div class="alert alert-info mt-3"><a href="/index.php?action=login">Connectez-vous</a> pour déposer un avis.</div>
    <?php endif; ?>
</div>

<link rel="stylesheet" href="/assets/css/avatar-builder.css">

<?php require __DIR__ . '/../layouts/footer.php'; ?>
