<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/_trait_options.php'; ?>

<h1>Modifier <?= htmlspecialchars($character['name']) ?></h1>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-6">
        <h3>Configuration</h3>
        <form method="POST" action="/index.php?action=character-edit&id=<?= $character['id'] ?>">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <input type="hidden" name="update_traits" value="1">
            <h5>Profil</h5>
            <?php renderTraitSelect('character_type', 'Type de personnage', $traitOptions['character_type'], $character['character_type'] ?? ''); ?>
            <?php renderTraitSelect('build', 'Corpulence', $traitOptions['build'], $character['build'] ?? ''); ?>
            <?php renderTraitSelect('age_group', 'Tranche d\'âge', $traitOptions['age_group'], $character['age_group'] ?? ''); ?>
            <h5 class="mt-3">Traits du visage</h5>
            <?php renderTraitSelect('eye_shape', 'Forme des yeux', $traitOptions['eye_shape'], $character['eye_shape'] ?? ''); ?>
            <?php renderTraitSelect('nose_shape', 'Forme du nez', $traitOptions['nose_shape'], $character['nose_shape'] ?? ''); ?>
            <?php renderTraitSelect('mouth_shape', 'Forme de la bouche', $traitOptions['mouth_shape'], $character['mouth_shape'] ?? ''); ?>
            <?php renderColorPicker('skin_color', 'Couleur de peau', $character['skin_color'] ?? ''); ?>
            <?php renderTraitSelect('hair_color', 'Couleur des cheveux', $traitOptions['hair_color'], $character['hair_color'] ?? ''); ?>
            <?php renderTraitSelect('eye_color', 'Couleur des yeux', $traitOptions['eye_color'], $character['eye_color'] ?? ''); ?>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>

    <div class="col-md-6">
        <h3>Accessoires équipés</h3>
        <?php if (empty($characterAccessories)): ?>
            <p class="text-muted">Aucun accessoire.</p>
        <?php else: ?>
            <ul class="list-group mb-3">
                <?php foreach ($characterAccessories as $acc): ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <?= htmlspecialchars($acc['name']) ?>
                        <span class="badge bg-secondary"><?= ucfirst($acc['type']) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <h4>Ajouter un accessoire</h4>
        <form method="POST" action="/index.php?action=character-add-accessory">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <input type="hidden" name="character_id" value="<?= $character['id'] ?>">
            <div class="input-group">
                <select name="accessory_id" class="form-select" required>
                    <option value="">Choisir...</option>
                    <?php foreach ($allAccessories as $acc): ?>
                        <?php if (!in_array($acc['id'], $characterAccessoryIds)): ?>
                            <option value="<?= $acc['id'] ?>"><?= htmlspecialchars($acc['name']) ?> (<?= ucfirst($acc['type']) ?>)</option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-success">+</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
