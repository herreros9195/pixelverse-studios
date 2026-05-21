<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="page-intro">
    <div>
        <p class="eyebrow">Moderation</p>
        <h1 class="page-title">Espace employe</h1>
        <p class="page-copy">Validez les personnages et les avis, enrichissez la bibliotheque, puis gerez les comptes joueurs et les suppressions manuelles.</p>
    </div>
</section>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5 mb-3">Personnages en attente</h2>
                <?php if (empty($pendingCharacters)): ?>
                    <p class="text-muted mb-0">Aucun personnage n'attend de validation.</p>
                <?php else: ?>
                    <div class="vstack gap-3">
                        <?php foreach ($pendingCharacters as $char): ?>
                            <article class="border rounded-4 p-3">
                                <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                    <div>
                                        <h3 class="h6 mb-1"><?= htmlspecialchars($char['name']) ?></h3>
                                        <p class="mb-0">Par <strong><?= htmlspecialchars($char['creator_pseudo']) ?></strong></p>
                                    </div>
                                    <span class="trait-pill"><?= htmlspecialchars(ucfirst($char['gender'])) ?></span>
                                </div>
                                <p class="text-muted small mb-3">Soumis le <?= htmlspecialchars(date('d/m/Y H:i', strtotime($char['created_at']))) ?></p>
                                <form method="POST" action="/index.php?action=employee-validate-character" class="mb-2">
                                    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                                    <input type="hidden" name="id" value="<?= (int) $char['id'] ?>">
                                    <div class="input-group input-group-sm">
                                        <button type="submit" name="status" value="approved" class="btn btn-success">Approuver</button>
                                        <input type="text" name="reason" class="form-control" placeholder="Motif si rejet">
                                        <button type="submit" name="status" value="rejected" class="btn btn-outline-danger">Rejeter</button>
                                    </div>
                                </form>
                                <a href="/index.php?action=employee-delete-character&id=<?= (int) $char['id'] ?>&csrf=<?= urlencode(csrfToken()) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer definitivement ce personnage ?')">Supprimer le personnage</a>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5 mb-3">Avis en attente</h2>
                <?php if (empty($pendingReviews)): ?>
                    <p class="text-muted mb-0">Aucun avis n'attend de moderation.</p>
                <?php else: ?>
                    <div class="vstack gap-3">
                        <?php foreach ($pendingReviews as $rev): ?>
                            <article class="border rounded-4 p-3">
                                <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                    <div>
                                        <strong><?= htmlspecialchars($rev['reviewer_pseudo']) ?></strong>
                                        <p class="mb-0 text-muted small">Sur <?= htmlspecialchars($rev['character_name']) ?></p>
                                    </div>
                                    <span class="rating-stars"><?= str_repeat('&#9733;', (int) $rev['rating']) ?></span>
                                </div>
                                <p class="mb-3"><?= nl2br(htmlspecialchars($rev['comment'])) ?></p>
                                <form method="POST" action="/index.php?action=employee-validate-review" class="d-flex gap-2">
                                    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                                    <input type="hidden" name="id" value="<?= (int) $rev['id'] ?>">
                                    <button type="submit" name="status" value="approved" class="btn btn-sm btn-success">Approuver</button>
                                    <button type="submit" name="status" value="rejected" class="btn btn-sm btn-outline-danger">Refuser</button>
                                </form>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5 mb-3">Ajouter un accessoire</h2>
                <form method="POST" action="/index.php?action=employee-add-accessory">
                    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                    <div class="mb-3">
                        <label class="form-label" for="accessory-name">Nom</label>
                        <input id="accessory-name" type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="accessory-type">Type</label>
                        <select id="accessory-type" name="type" class="form-select">
                            <option value="clothing">Vetement</option>
                            <option value="armor">Armure</option>
                            <option value="accessory">Accessoire</option>
                            <option value="weapon">Arme</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="accessory-description">Description</label>
                        <textarea id="accessory-description" name="description" class="form-control" rows="4"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Ajouter l'accessoire</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5 mb-3">Catalogue existant</h2>
                <?php if (empty($accessories)): ?>
                    <p class="text-muted mb-0">Aucun accessoire n'est encore enregistre.</p>
                <?php else: ?>
                    <div class="vstack gap-2">
                        <?php foreach ($accessories as $acc): ?>
                            <div class="border rounded-4 p-3 d-flex justify-content-between align-items-center gap-3">
                                <div>
                                    <strong><?= htmlspecialchars($acc['name']) ?></strong>
                                    <p class="mb-0 text-muted small"><?= htmlspecialchars(ucfirst($acc['type'])) ?> - <?= htmlspecialchars($acc['status']) ?></p>
                                </div>
                                <?php if ($acc['status'] === 'available'): ?>
                                    <a href="/index.php?action=employee-disable-accessory&id=<?= (int) $acc['id'] ?>&csrf=<?= urlencode(csrfToken()) ?>" class="btn btn-sm btn-outline-warning">Desactiver</a>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Desactive</span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5 mb-3">Comptes joueurs</h2>
                <?php if (empty($managedUsers)): ?>
                    <p class="text-muted mb-0">Aucun compte joueur a gerer.</p>
                <?php else: ?>
                    <div class="vstack gap-2">
                        <?php foreach ($managedUsers as $user): ?>
                            <div class="border rounded-4 p-3 d-flex justify-content-between align-items-center gap-3">
                                <div>
                                    <strong><?= htmlspecialchars($user['pseudo']) ?></strong>
                                    <p class="mb-0 text-muted small"><?= htmlspecialchars($user['email']) ?> - <?= htmlspecialchars($user['status']) ?></p>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    <?php if ($user['status'] === 'active'): ?>
                                        <a href="/index.php?action=employee-suspend-user&id=<?= (int) $user['id'] ?>&csrf=<?= urlencode(csrfToken()) ?>" class="btn btn-sm btn-outline-warning">Suspendre</a>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Suspendu</span>
                                    <?php endif; ?>
                                    <a href="/index.php?action=employee-delete-user&id=<?= (int) $user['id'] ?>&csrf=<?= urlencode(csrfToken()) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer definitivement ce compte ?')">Supprimer</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5 mb-3">Suppression manuelle de personnages</h2>
                <?php if (empty($recentCharacters)): ?>
                    <p class="text-muted mb-0">Aucun personnage disponible.</p>
                <?php else: ?>
                    <div class="vstack gap-2">
                        <?php foreach ($recentCharacters as $char): ?>
                            <div class="border rounded-4 p-3 d-flex justify-content-between align-items-center gap-3">
                                <div>
                                    <strong><?= htmlspecialchars($char['name']) ?></strong>
                                    <p class="mb-0 text-muted small"><?= htmlspecialchars($char['creator_pseudo']) ?> - <?= htmlspecialchars($char['status']) ?></p>
                                </div>
                                <a href="/index.php?action=employee-delete-character&id=<?= (int) $char['id'] ?>&csrf=<?= urlencode(csrfToken()) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer definitivement ce personnage ?')">Supprimer</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
