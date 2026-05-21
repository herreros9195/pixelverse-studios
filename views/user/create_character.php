<?php
$error = $error ?? '';
$success = $success ?? '';
$pageCss = [ASSETS_URL . 'css/pixelverse-ui-upgrade.css?v=21'];
require_once __DIR__ . '/../../config/avatar_options.php';
require __DIR__ . '/../layouts/header.php';

$genders = pixelverseGenderOptions();
$classes = pixelversePlayableClassCatalog();
$outfitVariants = pixelverseOutfitVariantCatalog();
$bodyStyles = pixelverseBodyStyleCatalog();
$appearanceFields = pixelverseAppearanceOptionsByField();
$skinColorMap = pixelverseSkinColorMap();
$hairColorMap = pixelverseHairColorMap();
$eyeColorMap = pixelverseEyeColorMap();
$defaultEyeColor = 'Bleu';

$defaultGender = 'male';
$defaultClass = 'Guerrier';
$defaultSelections = [
    'body_style' => pixelverseDefaultBodyStyleForGender($defaultGender),
    'ear_shape' => pixelverseDefaultChoiceForField('ear_shape', $defaultGender),
    'eye_shape' => pixelverseDefaultChoiceForField('eye_shape', $defaultGender),
    'nose_shape' => pixelverseDefaultChoiceForField('nose_shape', $defaultGender),
    'mouth_shape' => pixelverseDefaultChoiceForField('mouth_shape', $defaultGender),
    'hair_style' => pixelverseDefaultChoiceForField('hair_style', $defaultGender),
    'outfit_variant' => pixelverseDefaultOutfitVariantForClass($defaultClass),
];

function esc($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>

<style>
.wizard-step { display: none; }
.wizard-step.active { display: block; animation: fadeIn 0.3s ease; }
.wizard-nav { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.5rem; border-bottom: 1px solid rgba(212,175,55,0.15); padding-bottom: 1rem; }
.wizard-nav-item { display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; color: #A39B8B; background: transparent; border: none; cursor: pointer; }
.wizard-nav-item.active { color: #D4AF37; background: rgba(212,175,55,0.1); }
.wizard-nav-item.completed { color: #22c55e; }
.wizard-nav-number { width: 1.5rem; height: 1.5rem; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: rgba(255,255,255,0.1); font-size: 0.75rem; font-weight: 700; }
.wizard-nav-item.active .wizard-nav-number { background: #D4AF37; color: #0B0E17; }
.wizard-nav-item.completed .wizard-nav-number { background: #22c55e; color: #fff; }
.wizard-actions { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid rgba(212,175,55,0.15); }
.wizard-summary { background: rgba(17,24,39,0.6); border: 1px solid rgba(212,175,55,0.1); border-radius: 0.75rem; padding: 1.25rem; }
.wizard-summary-item { display: flex; justify-content: space-between; gap: 1rem; padding: 0.5rem 0; border-bottom: 1px solid rgba(255,255,255,0.05); font-size: 0.9rem; }
.wizard-summary-item:last-child { border-bottom: none; }
.wizard-summary-label { color: #A39B8B; }
.wizard-summary-value { color: #F5F0E6; font-weight: 600; text-align: right; }
.preset-notice { background: rgba(212,175,55,0.08); border: 1px solid rgba(212,175,55,0.15); border-radius: 0.5rem; padding: 0.75rem 1rem; margin-bottom: 1rem; font-size: 0.85rem; color: #A39B8B; }
.outfit-variant-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: .75rem; }
.outfit-variant-card .pv-choice-text { min-height: 3.2em; }
.pv-preview-hint { color: var(--pv-muted); font-size: .78rem; margin-top: .5rem; }
.outfit-variant-option.is-disabled { opacity: .45; }
.outfit-variant-option.is-disabled .pv-choice-card { pointer-events: none; }
.pv-choice-accent { display: inline-flex; width: .7rem; height: .7rem; border-radius: 999px; margin-left: .35rem; box-shadow: 0 0 0 2px rgba(255,255,255,0.06); }
.pv-asset-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: .9rem; }
.pv-body-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; }
.pv-asset-option { min-width: 0; }
.pv-asset-card { display: flex; flex-direction: column; gap: .7rem; min-height: 100%; padding: .85rem; border-radius: 1rem; border: 1px solid rgba(212,175,55,.14); background: rgba(17,24,39,.7); cursor: pointer; transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease, background .2s ease; }
.pv-asset-card:hover { transform: translateY(-2px); border-color: rgba(212,175,55,.35); box-shadow: 0 12px 28px rgba(0,0,0,.22); }
.pv-choice-input:checked + .pv-asset-card { border-color: rgba(212,175,55,.9); box-shadow: 0 0 0 1px rgba(212,175,55,.5), 0 16px 36px rgba(0,0,0,.26); background: linear-gradient(180deg, rgba(212,175,55,.09), rgba(17,24,39,.78)); }
.pv-asset-card.is-hidden { display: none; }
.pv-asset-viewer { width: 100%; height: 108px; border-radius: .8rem; background: rgba(0,0,0,.28); overflow: hidden; border: 1px solid rgba(255,255,255,.04); }
.pv-body-card .pv-asset-viewer { height: 150px; }
.pv-asset-title { color: #F5F0E6; font-size: .92rem; font-weight: 700; line-height: 1.2; }
.pv-asset-text { color: #A39B8B; font-size: .76rem; line-height: 1.35; }
.pv-field-block + .pv-field-block { margin-top: 1.35rem; }
.pv-field-label { display: block; margin-bottom: .75rem; font-weight: 600; color: #F5F0E6; }
.pv-subnav { display: flex; flex-wrap: wrap; gap: .55rem; margin-bottom: 1.1rem; }
.pv-subnav-item { border: 1px solid rgba(212,175,55,.14); background: rgba(17,24,39,.68); color: #A39B8B; border-radius: 999px; padding: .5rem .85rem; font-size: .82rem; font-weight: 600; cursor: pointer; transition: all .2s ease; }
.pv-subnav-item.active { color: #0B0E17; background: #D4AF37; border-color: #D4AF37; }
.pv-subnav-item.completed { color: #D4AF37; border-color: rgba(212,175,55,.45); }
.pv-substep { display: none; }
.pv-substep.active { display: block; animation: fadeIn 0.25s ease; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
@media (max-width: 575.98px) {
    .wizard-nav-item { flex: 1 1 calc(50% - .5rem); justify-content: center; padding: .55rem .6rem; }
    .wizard-actions { flex-direction: column-reverse; align-items: stretch; }
    .wizard-actions .btn { width: 100%; }
    .pv-asset-grid, .pv-body-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}
</style>

<div class="pv-page-shell">
    <div class="mb-4">
        <div class="pv-kicker">Creation</div>
        <h1 class="pv-page-title">Creer un personnage</h1>
        <p class="pv-page-subtitle">
            Assemblage d'un heros a partir des vraies pieces modulaires Synty exportees du pack gratuit.
            Le flux reste en 4 etapes, mais il suit maintenant les assets reels du projet.
        </p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger pv-panel mb-4" role="alert">
            <strong>Impossible de creer le personnage.</strong>
            <ul class="mb-0 mt-2"><li><?= esc($error) ?></li></ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success pv-panel mb-4" role="alert">
            <?= esc($success) ?>
        </div>
    <?php endif; ?>

    <div class="row g-4 align-items-start">
        <div class="col-lg-7">
            <form method="post" action="/index.php?action=character-create" class="pv-panel" data-character-form id="character-create-form">
                <input type="hidden" name="csrf_token" value="<?= esc(csrfToken()) ?>">

                <div class="wizard-nav" role="tablist">
                    <button type="button" class="wizard-nav-item active" data-wizard-nav="1">
                        <span class="wizard-nav-number">1</span>
                        <span>Identite</span>
                    </button>
                    <button type="button" class="wizard-nav-item" data-wizard-nav="2">
                        <span class="wizard-nav-number">2</span>
                        <span>Apparence</span>
                    </button>
                    <button type="button" class="wizard-nav-item" data-wizard-nav="3">
                        <span class="wizard-nav-number">3</span>
                        <span>Classe</span>
                    </button>
                    <button type="button" class="wizard-nav-item" data-wizard-nav="4">
                        <span class="wizard-nav-number">4</span>
                        <span>Recapitulatif</span>
                    </button>
                </div>

                <div class="wizard-step active" data-wizard-step="1">
                    <div class="pv-panel-header">
                        <h2 class="h5 mb-1">Etape 1 - Identite</h2>
                        <p class="text-muted mb-0 small">Choisissez le nom, le sexe et un corps de base Synty. L'apercu reste sans armure a cette etape.</p>
                    </div>
                    <div class="pv-panel-body">
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="characterName" class="form-label">Nom du personnage *</label>
                                <input id="characterName" name="name" type="text" class="form-control form-control-lg" required maxlength="80" placeholder="Ex : Thalor">
                            </div>
                            <div class="col-md-6">
                                <label for="gender" class="form-label">Sexe *</label>
                                <select id="gender" name="gender" class="form-select form-select-lg" required>
                                    <?php foreach ($genders as $value => $label): ?>
                                        <option value="<?= esc($value) ?>" <?= $value === $defaultGender ? 'selected' : '' ?>><?= esc($label) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="pv-field-block mb-4">
                            <span class="pv-field-label">Corps de base *</span>
                            <div class="pv-body-grid" data-visual-radio-group="body_style" data-default-male="<?= esc(pixelverseDefaultBodyStyleForGender('male')) ?>" data-default-female="<?= esc(pixelverseDefaultBodyStyleForGender('female')) ?>">
                                <?php foreach ($bodyStyles as $value => $definition): ?>
                                    <?php $bodyId = 'body-style-' . strtolower(preg_replace('/[^a-z0-9]/i', '', $value)); ?>
                                    <div class="pv-asset-option" data-genders="<?= esc(implode(' ', $definition['genders'])) ?>">
                                        <input
                                            class="pv-choice-input"
                                            type="radio"
                                            name="body_style"
                                            value="<?= esc($value) ?>"
                                            id="<?= esc($bodyId) ?>"
                                            data-genders="<?= esc(implode(' ', $definition['genders'])) ?>"
                                            <?= $value === $defaultSelections['body_style'] ? 'checked' : '' ?>
                                            required>
                                        <label class="pv-asset-card pv-body-card" for="<?= esc($bodyId) ?>">
                                            <div
                                                id="<?= esc($bodyId) ?>-viewer"
                                                class="pv-asset-viewer"
                                                data-synty-viewer
                                                data-character-type="<?= esc($defaultClass) ?>"
                                                data-skin-color="Claire"
                                                data-gender="<?= esc($defaultGender) ?>"
                                                data-body-style="<?= esc($value) ?>"
                                                data-ear-shape="<?= esc($defaultSelections['ear_shape']) ?>"
                                                data-eye-shape="<?= esc($defaultSelections['eye_shape']) ?>"
                                                data-nose-shape="<?= esc($defaultSelections['nose_shape']) ?>"
                                                data-mouth-shape="face_none"
                                                data-hair-style="<?= esc($defaultSelections['hair_style']) ?>"
                                                data-hair-color="Brun"
                                                data-eye-color="<?= esc($defaultEyeColor) ?>"
                                                data-outfit-variant="<?= esc($defaultSelections['outfit_variant']) ?>"
                                                data-preview-mode="identity"
                                                data-camera-mode="full"
                                                data-choice-field="body_style"
                                                data-choice-value="<?= esc($value) ?>"></div>
                                            <span class="pv-asset-title"><?= esc($definition['title']) ?></span>
                                            <span class="pv-asset-text"><?= esc($definition['text']) ?></span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="wizard-actions">
                            <span></span>
                            <button type="button" class="btn btn-primary btn-lg px-4" data-wizard-next>Suivant - Apparence</button>
                        </div>
                    </div>
                </div>

                <div class="wizard-step" data-wizard-step="2">
                    <div class="pv-panel-header">
                        <h2 class="h5 mb-1">Etape 2 - Apparence</h2>
                        <p class="text-muted mb-0 small">La tete, les oreilles, le nez, les sourcils et les cheveux viennent directement des pieces humaines exportees. Les overlays de barbe ou de masque restent optionnels.</p>
                    </div>
                    <div class="pv-panel-body">
                        <div class="preset-notice">
                            <strong>Apercu 3D en temps reel.</strong> La camera se cale sur la tete, mais l'assemblage repose toujours sur le vrai corps modulaire Synty pour eviter les tetes flottantes.
                        </div>
                        <p class="pv-preview-hint mb-4">Le mode <strong>Aucun</strong> garde un visage neutre, sans barbe ni masque peint. Les styles actives chargent les overlays Synty reellement disponibles pour le sexe selectionne.</p>

                        <div class="row g-4 mb-4">
                            <div class="col-12">
                                <label class="form-label d-block">Teint de peau *</label>
                                <div class="pv-swatch-row">
                                    <?php foreach ($skinColorMap as $value => $color): ?>
                                        <?php $slug = strtolower(preg_replace('/[^a-z0-9]/i', '', iconv('UTF-8', 'ASCII//TRANSLIT', $value))); ?>
                                        <input class="pv-choice-input" type="radio" name="skin_color" value="<?= esc($value) ?>" id="skin-<?= esc($slug) ?>" <?= $value === 'Claire' ? 'checked' : '' ?> required>
                                        <label class="pv-swatch-label" for="skin-<?= esc($slug) ?>" style="background: <?= esc($color) ?>" title="<?= esc($value) ?>"></label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <div class="pv-subnav" aria-label="Sous-etapes apparence">
                            <button type="button" class="pv-subnav-item active" data-appearance-nav="1">Barbe et sourcils</button>
                            <button type="button" class="pv-subnav-item" data-appearance-nav="2">Oreilles</button>
                            <button type="button" class="pv-subnav-item" data-appearance-nav="3">Nez</button>
                            <button type="button" class="pv-subnav-item" data-appearance-nav="4">Cheveux</button>
                        </div>

                        <div class="pv-substep active" data-appearance-panel="1">
                            <div class="pv-field-block mb-4">
                                <span class="pv-field-label">Barbe / masque *</span>
                                <div class="pv-asset-grid" data-visual-radio-group="mouth_shape" data-default-male="<?= esc(pixelverseDefaultChoiceForField('mouth_shape', 'male')) ?>" data-default-female="<?= esc(pixelverseDefaultChoiceForField('mouth_shape', 'female')) ?>">
                                    <?php foreach ($appearanceFields['mouth_shape'] as $option): ?>
                                        <?php $mouthId = 'mouth-option-' . strtolower(preg_replace('/[^a-z0-9]/i', '', $option['value'])); ?>
                                        <div class="pv-asset-option" data-genders="<?= esc(implode(' ', $option['genders'])) ?>">
                                            <input
                                                class="pv-choice-input"
                                                type="radio"
                                                name="mouth_shape"
                                                value="<?= esc($option['value']) ?>"
                                                id="<?= esc($mouthId) ?>"
                                                data-genders="<?= esc(implode(' ', $option['genders'])) ?>"
                                                <?= $option['value'] === $defaultSelections['mouth_shape'] ? 'checked' : '' ?>
                                                required>
                                            <label class="pv-asset-card" for="<?= esc($mouthId) ?>">
                                                <div
                                                    id="<?= esc($mouthId) ?>-viewer"
                                                    class="pv-asset-viewer"
                                                    data-synty-viewer
                                                    data-character-type="<?= esc($defaultClass) ?>"
                                                    data-skin-color="Claire"
                                                    data-gender="<?= esc($defaultGender) ?>"
                                                    data-body-style="<?= esc($defaultSelections['body_style']) ?>"
                                                    data-ear-shape="<?= esc($defaultSelections['ear_shape']) ?>"
                                                    data-eye-shape="<?= esc($defaultSelections['eye_shape']) ?>"
                                                    data-nose-shape="<?= esc($defaultSelections['nose_shape']) ?>"
                                                    data-mouth-shape="<?= esc($option['value']) ?>"
                                                    data-hair-style="<?= esc($defaultSelections['hair_style']) ?>"
                                                    data-hair-color="Brun"
                                                    data-eye-color="<?= esc($defaultEyeColor) ?>"
                                                    data-outfit-variant="<?= esc($defaultSelections['outfit_variant']) ?>"
                                                    data-preview-mode="appearance"
                                                    data-camera-mode="portrait"
                                                    data-choice-field="mouth_shape"
                                                    data-choice-value="<?= esc($option['value']) ?>"></div>
                                                <span class="pv-asset-title"><?= esc($option['label']) ?></span>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="pv-preview-hint">Le style <strong>Aucun</strong> garde un visage neutre. Les cartes visibles s'adaptent au sexe choisi.</div>
                            </div>

                            <div class="pv-field-block mb-4">
                                <span class="pv-field-label">Sourcils / regard *</span>
                                <div class="pv-asset-grid" data-visual-radio-group="eye_shape" data-default-male="<?= esc(pixelverseDefaultChoiceForField('eye_shape', 'male')) ?>" data-default-female="<?= esc(pixelverseDefaultChoiceForField('eye_shape', 'female')) ?>">
                                    <?php foreach ($appearanceFields['eye_shape'] as $option): ?>
                                        <?php $eyeShapeId = 'eye-shape-' . strtolower(preg_replace('/[^a-z0-9]/i', '', $option['value'])); ?>
                                        <div class="pv-asset-option" data-genders="<?= esc(implode(' ', $option['genders'])) ?>">
                                            <input
                                                class="pv-choice-input"
                                                type="radio"
                                                name="eye_shape"
                                                value="<?= esc($option['value']) ?>"
                                                id="<?= esc($eyeShapeId) ?>"
                                                data-genders="<?= esc(implode(' ', $option['genders'])) ?>"
                                                <?= $option['value'] === $defaultSelections['eye_shape'] ? 'checked' : '' ?>
                                                required>
                                            <label class="pv-asset-card" for="<?= esc($eyeShapeId) ?>">
                                                <div
                                                    id="<?= esc($eyeShapeId) ?>-viewer"
                                                    class="pv-asset-viewer"
                                                    data-synty-viewer
                                                    data-character-type="<?= esc($defaultClass) ?>"
                                                    data-skin-color="Claire"
                                                    data-gender="<?= esc($defaultGender) ?>"
                                                    data-body-style="<?= esc($defaultSelections['body_style']) ?>"
                                                    data-ear-shape="<?= esc($defaultSelections['ear_shape']) ?>"
                                                    data-eye-shape="<?= esc($option['value']) ?>"
                                                    data-nose-shape="<?= esc($defaultSelections['nose_shape']) ?>"
                                                    data-mouth-shape="<?= esc($defaultSelections['mouth_shape']) ?>"
                                                    data-hair-style="<?= esc($defaultSelections['hair_style']) ?>"
                                                    data-hair-color="Brun"
                                                    data-eye-color="<?= esc($defaultEyeColor) ?>"
                                                    data-outfit-variant="<?= esc($defaultSelections['outfit_variant']) ?>"
                                                    data-preview-mode="appearance"
                                                    data-camera-mode="portrait"
                                                    data-choice-field="eye_shape"
                                                    data-choice-value="<?= esc($option['value']) ?>"></div>
                                                <span class="pv-asset-title"><?= esc($option['label']) ?></span>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label d-block">Couleur des yeux *</label>
                                    <div class="pv-swatch-row">
                                        <?php foreach ($eyeColorMap as $value => $color): ?>
                                            <?php $slug = strtolower(preg_replace('/[^a-z0-9]/i', '', iconv('UTF-8', 'ASCII//TRANSLIT', $value))); ?>
                                            <input class="pv-choice-input" type="radio" name="eye_color" value="<?= esc($value) ?>" id="eye-<?= esc($slug) ?>" <?= $value === $defaultEyeColor ? 'checked' : '' ?> required>
                                            <label class="pv-swatch-label" for="eye-<?= esc($slug) ?>" style="background: <?= esc($color) ?>" title="<?= esc($value) ?>"></label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pv-substep" data-appearance-panel="2">
                            <div class="pv-field-block mb-4">
                                <span class="pv-field-label">Oreilles *</span>
                                <div class="pv-asset-grid" data-visual-radio-group="ear_shape" data-default-male="<?= esc(pixelverseDefaultChoiceForField('ear_shape', 'male')) ?>" data-default-female="<?= esc(pixelverseDefaultChoiceForField('ear_shape', 'female')) ?>">
                                    <?php foreach ($appearanceFields['ear_shape'] as $option): ?>
                                        <?php $earId = 'ear-option-' . strtolower(preg_replace('/[^a-z0-9]/i', '', $option['value'])); ?>
                                        <div class="pv-asset-option" data-genders="<?= esc(implode(' ', $option['genders'])) ?>">
                                            <input
                                                class="pv-choice-input"
                                                type="radio"
                                                name="ear_shape"
                                                value="<?= esc($option['value']) ?>"
                                                id="<?= esc($earId) ?>"
                                                data-genders="<?= esc(implode(' ', $option['genders'])) ?>"
                                                <?= $option['value'] === $defaultSelections['ear_shape'] ? 'checked' : '' ?>
                                                required>
                                            <label class="pv-asset-card" for="<?= esc($earId) ?>">
                                                <div
                                                    id="<?= esc($earId) ?>-viewer"
                                                    class="pv-asset-viewer"
                                                    data-synty-viewer
                                                    data-character-type="<?= esc($defaultClass) ?>"
                                                    data-skin-color="Claire"
                                                    data-gender="<?= esc($defaultGender) ?>"
                                                    data-body-style="<?= esc($defaultSelections['body_style']) ?>"
                                                    data-ear-shape="<?= esc($option['value']) ?>"
                                                    data-eye-shape="<?= esc($defaultSelections['eye_shape']) ?>"
                                                    data-nose-shape="<?= esc($defaultSelections['nose_shape']) ?>"
                                                    data-mouth-shape="<?= esc($defaultSelections['mouth_shape']) ?>"
                                                    data-hair-style="<?= esc($defaultSelections['hair_style']) ?>"
                                                    data-hair-color="Brun"
                                                    data-eye-color="<?= esc($defaultEyeColor) ?>"
                                                    data-outfit-variant="<?= esc($defaultSelections['outfit_variant']) ?>"
                                                    data-preview-mode="appearance"
                                                    data-camera-mode="portrait"
                                                    data-choice-field="ear_shape"
                                                    data-choice-value="<?= esc($option['value']) ?>"></div>
                                                <span class="pv-asset-title"><?= esc($option['label']) ?></span>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="pv-preview-hint">Les oreilles suivent la meme teinte de peau que la tete et le corps.</div>
                            </div>
                        </div>

                        <div class="pv-substep" data-appearance-panel="3">
                            <div class="pv-field-block mb-4">
                                <span class="pv-field-label">Style du nez *</span>
                                <div class="pv-asset-grid" data-visual-radio-group="nose_shape" data-default-male="<?= esc(pixelverseDefaultChoiceForField('nose_shape', 'male')) ?>" data-default-female="<?= esc(pixelverseDefaultChoiceForField('nose_shape', 'female')) ?>">
                                    <?php foreach ($appearanceFields['nose_shape'] as $option): ?>
                                        <?php $noseId = 'nose-option-' . strtolower(preg_replace('/[^a-z0-9]/i', '', $option['value'])); ?>
                                        <div class="pv-asset-option" data-genders="<?= esc(implode(' ', $option['genders'])) ?>">
                                            <input
                                                class="pv-choice-input"
                                                type="radio"
                                                name="nose_shape"
                                                value="<?= esc($option['value']) ?>"
                                                id="<?= esc($noseId) ?>"
                                                data-genders="<?= esc(implode(' ', $option['genders'])) ?>"
                                                <?= $option['value'] === $defaultSelections['nose_shape'] ? 'checked' : '' ?>
                                                required>
                                            <label class="pv-asset-card" for="<?= esc($noseId) ?>">
                                                <div
                                                    id="<?= esc($noseId) ?>-viewer"
                                                    class="pv-asset-viewer"
                                                    data-synty-viewer
                                                    data-character-type="<?= esc($defaultClass) ?>"
                                                    data-skin-color="Claire"
                                                    data-gender="<?= esc($defaultGender) ?>"
                                                    data-body-style="<?= esc($defaultSelections['body_style']) ?>"
                                                    data-ear-shape="<?= esc($defaultSelections['ear_shape']) ?>"
                                                    data-eye-shape="<?= esc($defaultSelections['eye_shape']) ?>"
                                                    data-nose-shape="<?= esc($option['value']) ?>"
                                                    data-mouth-shape="<?= esc($defaultSelections['mouth_shape']) ?>"
                                                    data-hair-style="<?= esc($defaultSelections['hair_style']) ?>"
                                                    data-hair-color="Brun"
                                                    data-eye-color="<?= esc($defaultEyeColor) ?>"
                                                    data-outfit-variant="<?= esc($defaultSelections['outfit_variant']) ?>"
                                                    data-preview-mode="appearance"
                                                    data-camera-mode="portrait"
                                                    data-choice-field="nose_shape"
                                                    data-choice-value="<?= esc($option['value']) ?>"></div>
                                                <span class="pv-asset-title"><?= esc($option['label']) ?></span>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <div class="pv-substep" data-appearance-panel="4">
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <label class="form-label d-block">Couleur des cheveux *</label>
                                    <div class="pv-swatch-row">
                                        <?php foreach ($hairColorMap as $value => $color): ?>
                                            <?php $slug = strtolower(preg_replace('/[^a-z0-9]/i', '', iconv('UTF-8', 'ASCII//TRANSLIT', $value))); ?>
                                            <input class="pv-choice-input" type="radio" name="hair_color" value="<?= esc($value) ?>" id="hair-<?= esc($slug) ?>" <?= $value === 'Brun' ? 'checked' : '' ?> required>
                                            <label class="pv-swatch-label" for="hair-<?= esc($slug) ?>" style="background: <?= esc($color) ?>" title="<?= esc($value) ?>"></label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="pv-field-block mb-4">
                                <span class="pv-field-label">Coupe de cheveux *</span>
                                <div class="pv-asset-grid" data-visual-radio-group="hair_style" data-default-male="<?= esc(pixelverseDefaultChoiceForField('hair_style', 'male')) ?>" data-default-female="<?= esc(pixelverseDefaultChoiceForField('hair_style', 'female')) ?>">
                                    <?php foreach ($appearanceFields['hair_style'] as $option): ?>
                                        <?php $hairId = 'hair-style-' . strtolower(preg_replace('/[^a-z0-9]/i', '', $option['value'])); ?>
                                        <div class="pv-asset-option" data-genders="<?= esc(implode(' ', $option['genders'])) ?>">
                                            <input
                                                class="pv-choice-input"
                                                type="radio"
                                                name="hair_style"
                                                value="<?= esc($option['value']) ?>"
                                                id="<?= esc($hairId) ?>"
                                                data-genders="<?= esc(implode(' ', $option['genders'])) ?>"
                                                <?= $option['value'] === $defaultSelections['hair_style'] ? 'checked' : '' ?>
                                                required>
                                            <label class="pv-asset-card" for="<?= esc($hairId) ?>">
                                                <div
                                                    id="<?= esc($hairId) ?>-viewer"
                                                    class="pv-asset-viewer"
                                                    data-synty-viewer
                                                    data-character-type="<?= esc($defaultClass) ?>"
                                                    data-skin-color="Claire"
                                                    data-gender="<?= esc($defaultGender) ?>"
                                                    data-body-style="<?= esc($defaultSelections['body_style']) ?>"
                                                    data-ear-shape="<?= esc($defaultSelections['ear_shape']) ?>"
                                                    data-eye-shape="<?= esc($defaultSelections['eye_shape']) ?>"
                                                    data-nose-shape="<?= esc($defaultSelections['nose_shape']) ?>"
                                                    data-mouth-shape="<?= esc($defaultSelections['mouth_shape']) ?>"
                                                    data-hair-style="<?= esc($option['value']) ?>"
                                                    data-hair-color="Brun"
                                                    data-eye-color="<?= esc($defaultEyeColor) ?>"
                                                    data-outfit-variant="<?= esc($defaultSelections['outfit_variant']) ?>"
                                                    data-preview-mode="appearance"
                                                    data-camera-mode="portrait"
                                                    data-choice-field="hair_style"
                                                    data-choice-value="<?= esc($option['value']) ?>"></div>
                                                <span class="pv-asset-title"><?= esc($option['label']) ?></span>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="pv-preview-hint">Les mini-cartes reprennent la couleur de cheveux courante pour comparer directement le rendu.</div>
                            </div>
                        </div>

                        <div class="wizard-actions">
                            <button type="button" class="btn btn-outline-secondary" data-appearance-prev>&larr; Retour - Identite</button>
                            <button type="button" class="btn btn-primary btn-lg px-4" data-appearance-next>Suivant</button>
                        </div>
                    </div>
                </div>

                <div class="wizard-step" data-wizard-step="3">
                    <div class="pv-panel-header">
                        <h2 class="h5 mb-1">Etape 3 - Classe et equipement</h2>
                        <p class="text-muted mb-0 small">Les classes et les variations ci-dessous correspondent enfin aux vrais morceaux fantasy, sci-fi et horror presents dans les assets locaux.</p>
                    </div>
                    <div class="pv-panel-body">
                        <div class="pv-section-title">Classe du personnage *</div>
                        <div class="pv-choice-grid mb-4">
                            <?php foreach ($classes as $value => $definition): ?>
                                <?php $slug = strtolower(preg_replace('/[^a-z0-9]/i', '', iconv('UTF-8', 'ASCII//TRANSLIT', $value))); ?>
                                <div>
                                    <input
                                        class="pv-choice-input"
                                        type="radio"
                                        name="character_type"
                                        value="<?= esc($value) ?>"
                                        id="type-<?= esc($slug) ?>"
                                        data-class-family="<?= esc($definition['family']) ?>"
                                        <?= $value === $defaultClass ? 'checked' : '' ?>
                                        required>
                                    <label class="pv-choice-card" for="type-<?= esc($slug) ?>">
                                        <span class="pv-choice-icon"><?= $definition['icon'] ?></span>
                                        <span class="pv-choice-title">
                                            <?= esc($value) ?>
                                            <span class="pv-choice-accent" style="background: <?= esc($definition['accent']) ?>"></span>
                                        </span>
                                        <span class="pv-choice-text"><?= esc($definition['text']) ?></span>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="pv-section-title">Variation d'equipement</div>
                        <div class="outfit-variant-grid mb-4">
                            <?php foreach ($outfitVariants as $value => $variant): ?>
                                <?php $variantId = 'outfit-' . strtolower(preg_replace('/[^a-z0-9]/i', '', $value)); ?>
                                <?php $families = implode(' ', $variant['families'] ?? []); ?>
                                <div class="outfit-variant-option" data-outfit-option data-families="<?= esc($families) ?>">
                                    <input
                                        class="pv-choice-input"
                                        type="radio"
                                        name="outfit_variant"
                                        value="<?= esc($value) ?>"
                                        id="<?= esc($variantId) ?>"
                                        <?= $value === $defaultSelections['outfit_variant'] ? 'checked' : '' ?>>
                                    <label class="pv-choice-card outfit-variant-card" for="<?= esc($variantId) ?>">
                                        <span class="pv-choice-icon"><?= $variant['icon'] ?></span>
                                        <span class="pv-choice-title"><?= esc($variant['title']) ?></span>
                                        <span class="pv-choice-text"><?= esc($variant['text']) ?></span>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="pv-preview-hint">Chaque carte de variation active ou retire de vrais blocs d'armure, de casque, d'epaules ou d'accessoires du pack Synty, au lieu d'un simple preset blanc.</div>

                        <div class="wizard-actions">
                            <button type="button" class="btn btn-outline-secondary" data-wizard-prev>&larr; Retour</button>
                            <button type="button" class="btn btn-primary btn-lg px-4" data-wizard-next>Suivant - Recapitulatif</button>
                        </div>
                    </div>
                </div>

                <div class="wizard-step" data-wizard-step="4">
                    <div class="pv-panel-header">
                        <h2 class="h5 mb-1">Etape 4 - Recapitulatif</h2>
                        <p class="text-muted mb-0 small">Verifiez la selection avant l'envoi. Le rendu en galerie utilisera la meme composition modulaire.</p>
                    </div>
                    <div class="pv-panel-body">
                        <div class="wizard-summary mb-4" id="wizard-summary">
                            <div class="wizard-summary-item"><span class="wizard-summary-label">Nom</span><span class="wizard-summary-value" data-summary="name">-</span></div>
                            <div class="wizard-summary-item"><span class="wizard-summary-label">Sexe</span><span class="wizard-summary-value" data-summary="gender">-</span></div>
                            <div class="wizard-summary-item"><span class="wizard-summary-label">Corps</span><span class="wizard-summary-value" data-summary="body_style">-</span></div>
                            <div class="wizard-summary-item"><span class="wizard-summary-label">Peau</span><span class="wizard-summary-value" data-summary="skin_color">-</span></div>
                            <div class="wizard-summary-item"><span class="wizard-summary-label">Barbe / masque</span><span class="wizard-summary-value" data-summary="mouth_shape">-</span></div>
                            <div class="wizard-summary-item"><span class="wizard-summary-label">Oreilles</span><span class="wizard-summary-value" data-summary="ear_shape">-</span></div>
                            <div class="wizard-summary-item"><span class="wizard-summary-label">Sourcils / regard</span><span class="wizard-summary-value" data-summary="eye_shape">-</span></div>
                            <div class="wizard-summary-item"><span class="wizard-summary-label">Nez</span><span class="wizard-summary-value" data-summary="nose_shape">-</span></div>
                            <div class="wizard-summary-item"><span class="wizard-summary-label">Cheveux</span><span class="wizard-summary-value" data-summary="hair">-</span></div>
                            <div class="wizard-summary-item"><span class="wizard-summary-label">Yeux</span><span class="wizard-summary-value" data-summary="eye_color">-</span></div>
                            <div class="wizard-summary-item"><span class="wizard-summary-label">Classe</span><span class="wizard-summary-value" data-summary="character_type">-</span></div>
                            <div class="wizard-summary-item"><span class="wizard-summary-label">Equipement</span><span class="wizard-summary-value" data-summary="outfit_variant">-</span></div>
                        </div>

                        <p class="text-muted small mb-4">
                            Le personnage sera soumis a la moderation. Une fois approuve, il gardera la meme logique modulaire dans le tableau de bord, la fiche detail et la galerie.
                        </p>

                        <div class="wizard-actions">
                            <button type="button" class="btn btn-outline-secondary" data-wizard-prev>&larr; Modifier</button>
                            <button type="submit" class="btn btn-primary btn-lg px-4">Soumettre le personnage</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-5">
            <aside class="pv-preview-sticky">
                <div class="pv-panel pv-preview-card">
                    <div class="pv-panel-header">
                        <div class="pv-kicker">Apercu de reference</div>
                        <h2 class="h5 mb-0">Portrait du heros</h2>
                    </div>
                    <div class="pv-panel-body">
                        <div class="pv-preview-top mb-3">
                            <div
                                id="create-three-viewer"
                                data-synty-viewer
                                data-character-type="<?= esc($defaultClass) ?>"
                                data-skin-color="Claire"
                                data-gender="<?= esc($defaultGender) ?>"
                                data-body-style="<?= esc($defaultSelections['body_style']) ?>"
                                data-ear-shape="<?= esc($defaultSelections['ear_shape']) ?>"
                                data-eye-shape="<?= esc($defaultSelections['eye_shape']) ?>"
                                data-nose-shape="<?= esc($defaultSelections['nose_shape']) ?>"
                                data-mouth-shape="<?= esc($defaultSelections['mouth_shape']) ?>"
                                data-hair-style="<?= esc($defaultSelections['hair_style']) ?>"
                                data-hair-color="Brun"
                                data-eye-color="<?= esc($defaultEyeColor) ?>"
                                data-outfit-variant="<?= esc($defaultSelections['outfit_variant']) ?>"
                                data-preview-mode="identity"
                                data-camera-mode="full"
                                style="width:100%;height:320px;border-radius:0.75rem;background:rgba(0,0,0,0.3);"></div>
                        </div>
                        <div class="pv-preview-name" data-preview-name>Nom du personnage</div>
                        <div class="pv-preview-meta mb-3" data-preview-meta>Masculin - Guerrier</div>
                        <div class="d-flex flex-wrap gap-2" data-preview-tags></div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>

<script type="module" src="<?= ASSETS_URL ?>js/synty-character-viewer.js?v=73"></script>
<script src="<?= ASSETS_URL ?>js/synty-character-builder.js?v=72"></script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
