<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/_trait_options.php'; ?>

<h1>Créer un personnage</h1>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form method="POST" action="/index.php?action=character-create" class="mt-4">
    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
    <div class="row">
        <div class="col-md-5">
            <div class="mb-3">
                <label for="name" class="form-label">Nom du personnage</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="gender" class="form-label">Genre</label>
                <select class="form-select" id="gender" name="gender">
                    <option value="male">Masculin</option>
                    <option value="female">Féminin</option>
                    <option value="other">Autre</option>
                </select>
            </div>
            <h4>Profil</h4>
            <?php renderTraitSelect('character_type', 'Type de personnage', $traitOptions['character_type'], 'Guerrier'); ?>
            <?php renderTraitSelect('build', 'Corpulence', $traitOptions['build'], 'Musclé'); ?>
            <?php renderTraitSelect('age_group', 'Tranche d\'âge', $traitOptions['age_group'], 'Adulte'); ?>
            <h4 class="mt-3">Traits du visage</h4>
            <?php renderTraitSelect('eye_shape', 'Forme des yeux', $traitOptions['eye_shape'], 'Rond'); ?>
            <?php renderTraitSelect('nose_shape', 'Forme du nez', $traitOptions['nose_shape'], 'Droit'); ?>
            <?php renderTraitSelect('mouth_shape', 'Forme de la bouche', $traitOptions['mouth_shape'], 'Moyenne'); ?>
            <?php renderColorPicker('skin_color', 'Couleur de peau', '#f5d0a9'); ?>
            <?php renderTraitSelect('hair_color', 'Couleur des cheveux', $traitOptions['hair_color'], 'Brun'); ?>
            <?php renderTraitSelect('eye_color', 'Couleur des yeux', $traitOptions['eye_color'], 'Marron'); ?>
            <button type="submit" class="btn btn-primary">Créer le personnage</button>
        </div>
        <div class="col-md-7">
            <div class="sticky-top" style="top: 80px;">
                <h4 class="text-center">Aperçu en temps réel</h4>
                <div id="live-avatar-preview" class="avatar-builder-wrap">
                    <img class="avatar-layer-img" data-layer="body" src="/assets/images/avatar/body/maigre.png?v=2">
                    <div class="avatar-skin-overlay"></div>
                    <div class="avatar-hair-wrap">
                        <img class="avatar-layer-img" data-layer="hair" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" style="opacity:0">
                    </div>
                    <img class="avatar-layer-img layer-scale" data-layer="clothes" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" style="opacity:0">
                    <div class="avatar-eyes-wrap">
                        <img class="avatar-layer-img" data-layer="eyes" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" style="opacity:0">
                    </div>
                    <div class="avatar-nose" style="display:none"></div>
                    <div class="avatar-mouth" style="display:none"></div>
                </div>
                <p class="text-center text-muted mt-2"><small>Sélectionnez des traits pour construire votre avatar</small></p>
            </div>
        </div>
    </div>
</form>

<link rel="stylesheet" href="/assets/css/avatar-builder.css">
<script src="/assets/js/avatar-builder.js"></script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
