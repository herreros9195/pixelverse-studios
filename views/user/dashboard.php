<?php require __DIR__ . '/../layouts/header.php'; ?>

<h1>Mon Espace</h1>
<p class="lead">Gérez vos personnages et accédez à leurs options de personnalisation.</p>

<a href="/index.php?action=character-create" class="btn btn-primary mb-4">+ Créer un nouveau personnage</a>

<div class="row g-4">
    <?php if (empty($characters)): ?>
        <div class="col-12"><div class="alert alert-info">Vous n'avez encore aucun personnage.</div></div>
    <?php else: ?>
        <?php foreach ($characters as $char): ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-img-top bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 150px;">
                        <span class="fs-1">🧙‍♂️</span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($char['name']) ?></h5>
                        <p class="mb-1"><span class="badge bg-<?= $char['status'] === 'approved' ? 'success' : ($char['status'] === 'rejected' ? 'danger' : 'warning') ?>"><?= ucfirst($char['status']) ?></span></p>
                        <p class="text-muted mb-2"><?= ucfirst($char['gender']) ?></p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="/index.php?action=character-edit&id=<?= $char['id'] ?>" class="btn btn-sm btn-outline-primary">Modifier</a>
                            <?php if ($char['status'] === 'approved'): ?>
                                <a href="/index.php?action=character-share&id=<?= $char['id'] ?>&share=<?= $char['shared'] ? 0 : 1 ?>" class="btn btn-sm btn-outline-<?= $char['shared'] ? 'danger' : 'success' ?>">
                                    <?= $char['shared'] ? 'Arrêter le partage' : 'Partager' ?>
                                </a>
                            <?php endif; ?>
                            <a href="/index.php?action=character-detail&id=<?= $char['id'] ?>" class="btn btn-sm btn-outline-info">👁 Voir</a>
                            <a href="/index.php?action=character-delete&id=<?= $char['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer définitivement ?')">Supprimer</a>
                        </div>
                        <form method="POST" action="/index.php?action=character-duplicate" class="mt-2">
                            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                            <input type="hidden" name="id" value="<?= $char['id'] ?>">
                            <div class="input-group input-group-sm">
                                <input type="text" name="new_name" class="form-control" placeholder="Nouveau nom..." required>
                                <button type="submit" class="btn btn-outline-secondary" onclick="return confirm('Dupliquer ce personnage ?')">Dupliquer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
