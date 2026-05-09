<?php require __DIR__ . '/../layouts/header.php'; ?>

<h1>Tous les personnages</h1>
<p class="lead">Découvrez les créations de la communauté FantasyRealm Online.</p>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/index.php" class="row g-3">
            <input type="hidden" name="action" value="characters">
            <div class="col-md-3">
                <label class="form-label">Genre</label>
                <select name="gender" class="form-select">
                    <option value="">Tous</option>
                    <option value="male" <?= ($_GET['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Masculin</option>
                    <option value="female" <?= ($_GET['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Féminin</option>
                    <option value="other" <?= ($_GET['gender'] ?? '') === 'other' ? 'selected' : '' ?>>Autre</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Date début</label>
                <input type="date" name="date_from" class="form-control" value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Date fin</label>
                <input type="date" name="date_to" class="form-control" value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Pseudo créateur</label>
                <input type="text" name="pseudo" class="form-control" value="<?= htmlspecialchars($_GET['pseudo'] ?? '') ?>">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Filtrer</button>
                <a href="/index.php?action=characters" class="btn btn-secondary">Réinitialiser</a>
            </div>
        </form>
    </div>
</div>

<div class="row g-4">
    <?php if (empty($characters)): ?>
        <div class="col-12"><div class="alert alert-info">Aucun personnage trouvé.</div></div>
    <?php else: ?>
        <?php foreach ($characters as $char): ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-img-top bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 180px;">
                        <span class="fs-1">🧙‍♂️</span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($char['name']) ?></h5>
                        <p class="card-text">Créé par <strong><?= htmlspecialchars($char['creator_pseudo']) ?></strong></p>
                        <p class="card-text"><small class="text-muted"><?= ucfirst($char['gender']) ?> • <?= date('d/m/Y', strtotime($char['created_at'])) ?></small></p>
                        <a href="/index.php?action=character-detail&id=<?= $char['id'] ?>" class="btn btn-outline-primary">Voir le détail</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php if ($pages > 1): ?>
<nav aria-label="Pagination">
    <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                <a class="page-link" href="/index.php?action=characters&page=<?= $i ?>&gender=<?= urlencode($filters['gender']) ?>&date_from=<?= urlencode($filters['date_from']) ?>&date_to=<?= urlencode($filters['date_to']) ?>&pseudo=<?= urlencode($filters['pseudo']) ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
