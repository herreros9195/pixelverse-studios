<?php require __DIR__ . '/../layouts/header.php'; ?>

<h1>Espace Employé</h1>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Personnages en attente de validation</h5>
            </div>
            <div class="card-body">
                <?php if (empty($pendingCharacters)): ?>
                    <p class="text-muted mb-0">Aucun personnage en attente.</p>
                <?php else: ?>
                    <?php foreach ($pendingCharacters as $char): ?>
                        <div class="border rounded p-2 mb-2">
                            <strong><?= htmlspecialchars($char['name']) ?></strong> par <?= htmlspecialchars($char['creator_pseudo']) ?><br>
                            <small class="text-muted"><?= $char['gender'] ?> • <?= date('d/m/Y H:i', strtotime($char['created_at'])) ?></small>
                            <form method="POST" action="/index.php?action=employee-validate-character" class="mt-2">
                                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                                <input type="hidden" name="id" value="<?= $char['id'] ?>">
                                <div class="input-group input-group-sm">
                                    <button type="submit" name="status" value="approved" class="btn btn-success">Approuver</button>
                                    <input type="text" name="reason" class="form-control" placeholder="Motif en cas de rejet">
                                    <button type="submit" name="status" value="rejected" class="btn btn-danger">Rejeter</button>
                                </div>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Avis en attente</h5>
            </div>
            <div class="card-body">
                <?php if (empty($pendingReviews)): ?>
                    <p class="text-muted mb-0">Aucun avis en attente.</p>
                <?php else: ?>
                    <?php foreach ($pendingReviews as $rev): ?>
                        <div class="border rounded p-2 mb-2">
                            <strong><?= htmlspecialchars($rev['reviewer_pseudo']) ?></strong> sur <em><?= htmlspecialchars($rev['character_name']) ?></em><br>
                            <span class="text-warning"><?= str_repeat('⭐', $rev['rating']) ?></span><br>
                            <small><?= nl2br(htmlspecialchars($rev['comment'])) ?></small>
                            <form method="POST" action="/index.php?action=employee-validate-review" class="mt-2">
                                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                                <input type="hidden" name="id" value="<?= $rev['id'] ?>">
                                <button type="submit" name="status" value="approved" class="btn btn-sm btn-success">Approuver</button>
                                <button type="submit" name="status" value="rejected" class="btn btn-sm btn-danger">Refuser</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Gestion des accessoires</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="/index.php?action=employee-add-accessory">
                    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                    <div class="mb-2">
                        <input type="text" name="name" class="form-control" placeholder="Nom" required>
                    </div>
                    <div class="mb-2">
                        <select name="type" class="form-select">
                            <option value="clothing">Vêtement</option>
                            <option value="armor">Armure</option>
                            <option value="accessory">Accessoire</option>
                            <option value="weapon">Arme</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <textarea name="description" class="form-control" placeholder="Description"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Ajouter</button>
                </form>

                <hr>
                <h6>Accessoires existants</h6>
                <ul class="list-group list-group-flush">
                    <?php foreach ($accessories as $acc): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= htmlspecialchars($acc['name']) ?> (<?= ucfirst($acc['type']) ?>)
                            <?php if ($acc['status'] === 'available'): ?>
                                <a href="/index.php?action=employee-disable-accessory&id=<?= $acc['id'] ?>" class="btn btn-sm btn-warning">Désactiver</a>
                            <?php else: ?>
                                <span class="badge bg-secondary">Désactivé</span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
