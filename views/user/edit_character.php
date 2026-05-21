<?php
$error = $error ?? '';
$success = $success ?? '';
require __DIR__ . '/_trait_options.php';
require_once __DIR__ . '/../../config/avatar_options.php';
require __DIR__ . '/../layouts/header.php';

$selectedGender = pixelverseNormalizeGender($character['gender'] ?? 'male');
$selectedBodyStyle = pixelverseNormalizeBodyStyle($selectedGender, $character['body_style'] ?? '');
$selectedSkinTone = normalizeSkinTraitValue($character['skin_color'] ?? '');
$selectedEarShape = pixelverseNormalizeAppearanceChoice('ear_shape', $selectedGender, $character['ear_shape'] ?? '');
$selectedEyeShape = pixelverseNormalizeAppearanceChoice('eye_shape', $selectedGender, $character['eye_shape'] ?? '');
$selectedNoseShape = pixelverseNormalizeAppearanceChoice('nose_shape', $selectedGender, $character['nose_shape'] ?? '');
$selectedMouthShape = pixelverseNormalizeAppearanceChoice('mouth_shape', $selectedGender, $character['mouth_shape'] ?? '');
$selectedHairStyle = pixelverseNormalizeAppearanceChoice('hair_style', $selectedGender, $character['hair_style'] ?? '');
$selectedOutfitVariant = pixelverseNormalizeOutfitVariant($character['character_type'] ?? 'Guerrier', $character['outfit_variant'] ?? '');
$appearanceOptions = pixelverseAppearanceOptionsByField();
$outfitVariantOptions = [];
foreach (pixelverseOutfitVariantCatalog() as $value => $definition) {
    $outfitVariantOptions[] = [
        'value' => $value,
        'label' => $definition['label'],
    ];
}
?>

<section class="page-intro">
    <div>
        <p class="eyebrow">Personnalisation</p>
        <h1 class="page-title">Modifier <?= htmlspecialchars($character['name'], ENT_QUOTES, 'UTF-8') ?></h1>
        <p class="page-copy">Edition reservee aux personnages deja approuves, avec rendu 3D Synty synchronise sur les traits et la tenue.</p>
    </div>
</section>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<div class="row g-4 align-items-start">
    <div class="col-lg-7">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h2 class="h5 mb-3">Traits et apparence</h2>
                <form method="POST" action="/index.php?action=character-edit&id=<?= (int) $character['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                    <input type="hidden" name="update_traits" value="1">

                    <div class="row g-3">
                        <div class="col-md-6"><?php renderTraitSelect('body_style', 'Corps de base', $traitOptions['body_style'], $selectedBodyStyle); ?></div>
                        <div class="col-md-6"><?php renderTraitSelect('character_type', 'Classe', $traitOptions['character_type'], $character['character_type'] ?? ''); ?></div>
                        <div class="col-md-6"><?php renderTraitSelect('outfit_variant', 'Style de tenue', $outfitVariantOptions, $selectedOutfitVariant); ?></div>
                        <div class="col-md-6"><?php renderTraitSelect('ear_shape', 'Oreilles', pixelverseSelectableAppearanceOptions('ear_shape', $selectedGender), $selectedEarShape); ?></div>
                        <div class="col-md-6"><?php renderTraitSelect('eye_shape', 'Sourcils / regard', pixelverseSelectableAppearanceOptions('eye_shape', $selectedGender), $selectedEyeShape); ?></div>
                        <div class="col-md-6"><?php renderTraitSelect('nose_shape', 'Style du nez', $appearanceOptions['nose_shape'], $selectedNoseShape); ?></div>
                        <div class="col-md-6"><?php renderTraitSelect('mouth_shape', 'Barbe / masque', pixelverseSelectableAppearanceOptions('mouth_shape', $selectedGender), $selectedMouthShape); ?></div>
                        <div class="col-md-6"><?php renderTraitSelect('skin_color', 'Teinte de peau', $traitOptions['skin_color'], $selectedSkinTone); ?></div>
                        <div class="col-md-6"><?php renderTraitSelect('hair_style', 'Coupe de cheveux', pixelverseSelectableAppearanceOptions('hair_style', $selectedGender), $selectedHairStyle); ?></div>
                        <div class="col-md-6"><?php renderTraitSelect('hair_color', 'Couleur des cheveux', $traitOptions['hair_color'], $character['hair_color'] ?? ''); ?></div>
                        <div class="col-md-6"><?php renderTraitSelect('eye_color', 'Couleur des yeux', $traitOptions['eye_color'], $character['eye_color'] ?? ''); ?></div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4">Enregistrer les modifications</button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="h5 mb-3">Accessoires equipes</h2>
                <?php if (empty($characterAccessories)): ?>
                    <p class="text-muted">Aucun accessoire equipe pour le moment.</p>
                <?php else: ?>
                    <div class="character-traits mb-4">
                        <?php foreach ($characterAccessories as $acc): ?>
                            <span class="trait-pill"><?= htmlspecialchars($acc['name'], ENT_QUOTES, 'UTF-8') ?> - <?= htmlspecialchars(ucfirst($acc['type']), ENT_QUOTES, 'UTF-8') ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <h3 class="h6 mb-3">Ajouter un accessoire</h3>
                <form method="POST" action="/index.php?action=character-add-accessory">
                    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                    <input type="hidden" name="character_id" value="<?= (int) $character['id'] ?>">
                    <div class="input-group">
                        <select name="accessory_id" class="form-select" required>
                            <option value="">Choisir un accessoire</option>
                            <?php foreach ($allAccessories as $acc): ?>
                                <?php if (!in_array($acc['id'], $characterAccessoryIds, true)): ?>
                                    <option value="<?= (int) $acc['id'] ?>"><?= htmlspecialchars($acc['name'], ENT_QUOTES, 'UTF-8') ?> (<?= htmlspecialchars(ucfirst($acc['type']), ENT_QUOTES, 'UTF-8') ?>)</option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-outline-success">Equiper</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm sticky-top" style="top: 96px;">
            <div class="card-body">
                <div class="avatar-preview-card text-center mb-3">
                    <p class="eyebrow mb-2">Apercu 3D Synty</p>
                    <div
                        id="edit-three-viewer"
                        data-synty-viewer
                        data-character-type="<?= htmlspecialchars(pixelverseNormalizeCharacterType($character['character_type'] ?? 'Guerrier'), ENT_QUOTES, 'UTF-8') ?>"
                        data-skin-color="<?= htmlspecialchars($character['skin_color'] ?? 'Claire', ENT_QUOTES, 'UTF-8') ?>"
                        data-gender="<?= htmlspecialchars($selectedGender, ENT_QUOTES, 'UTF-8') ?>"
                        data-body-style="<?= htmlspecialchars($selectedBodyStyle, ENT_QUOTES, 'UTF-8') ?>"
                        data-ear-shape="<?= htmlspecialchars($selectedEarShape, ENT_QUOTES, 'UTF-8') ?>"
                        data-eye-shape="<?= htmlspecialchars($selectedEyeShape, ENT_QUOTES, 'UTF-8') ?>"
                        data-nose-shape="<?= htmlspecialchars($selectedNoseShape, ENT_QUOTES, 'UTF-8') ?>"
                        data-mouth-shape="<?= htmlspecialchars($selectedMouthShape, ENT_QUOTES, 'UTF-8') ?>"
                        data-hair-style="<?= htmlspecialchars($selectedHairStyle, ENT_QUOTES, 'UTF-8') ?>"
                        data-hair-color="<?= htmlspecialchars($character['hair_color'] ?? 'Brun', ENT_QUOTES, 'UTF-8') ?>"
                        data-eye-color="<?= htmlspecialchars($character['eye_color'] ?? 'Bleu', ENT_QUOTES, 'UTF-8') ?>"
                        data-outfit-variant="<?= htmlspecialchars($selectedOutfitVariant, ENT_QUOTES, 'UTF-8') ?>"
                        data-preview-mode="class"
                        data-camera-mode="full"
                        style="width:100%;height:320px;border-radius:0.75rem;background:rgba(0,0,0,0.3);"></div>
                </div>
                <p class="text-muted small text-center mt-3 mb-0">Le rendu 3D se met a jour selon les traits, la classe et les accessoires equipes.</p>
            </div>
        </div>
    </div>
</div>

<script type="module" src="<?= ASSETS_URL ?>js/synty-character-viewer.js?v=73"></script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
