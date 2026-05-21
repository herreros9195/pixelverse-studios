<?php
require_once __DIR__ . '/../../config/avatar_options.php';
require __DIR__ . '/../layouts/header.php';

$selectedGender = pixelverseNormalizeGender($character['gender'] ?? 'male');
$selectedBodyStyle = pixelverseNormalizeBodyStyle($selectedGender, $character['body_style'] ?? '');
$selectedEarShape = pixelverseNormalizeAppearanceChoice('ear_shape', $selectedGender, $character['ear_shape'] ?? '');
$selectedEyeShape = pixelverseNormalizeAppearanceChoice('eye_shape', $selectedGender, $character['eye_shape'] ?? '');
$selectedNoseShape = pixelverseNormalizeAppearanceChoice('nose_shape', $selectedGender, $character['nose_shape'] ?? '');
$selectedMouthShape = pixelverseNormalizeAppearanceChoice('mouth_shape', $selectedGender, $character['mouth_shape'] ?? '');
$selectedHairStyle = pixelverseNormalizeAppearanceChoice('hair_style', $selectedGender, $character['hair_style'] ?? '');
$selectedClass = pixelverseNormalizeCharacterType($character['character_type'] ?? 'Guerrier');
$selectedVariant = pixelverseNormalizeOutfitVariant($selectedClass, $character['outfit_variant'] ?? '');
?>

<div class="row g-4 align-items-start">
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <p class="eyebrow mb-2">Apercu 3D modulaire</p>
                <div
                    id="detail-three-viewer-<?= (int) $character['id'] ?>"
                    data-synty-viewer
                    data-character-type="<?= htmlspecialchars($selectedClass, ENT_QUOTES, 'UTF-8') ?>"
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
                    data-outfit-variant="<?= htmlspecialchars($selectedVariant, ENT_QUOTES, 'UTF-8') ?>"
                    data-preview-mode="class"
                    data-scale="1.0"
                    style="width:100%;height:320px;border-radius:0.75rem;background:rgba(0,0,0,0.3);margin-bottom:0.75rem;"></div>
                <p class="text-muted small mt-3 mb-0">Rendu 3D Synty en temps reel.</p>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <section class="page-intro page-intro-compact">
            <div>
                <p class="eyebrow">Personnage partage</p>
                <h1 class="page-title"><?= htmlspecialchars($character['name'], ENT_QUOTES, 'UTF-8') ?></h1>
                <p class="page-copy">Fiche publique creee par <strong><?= htmlspecialchars($character['creator_pseudo'], ENT_QUOTES, 'UTF-8') ?></strong> le <?= htmlspecialchars(date('d/m/Y', strtotime($character['created_at'])), ENT_QUOTES, 'UTF-8') ?>.</p>
            </div>
        </section>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h2 class="h5 mb-3">Profil</h2>
                        <dl class="detail-list">
                            <dt>Classe</dt>
                            <dd><?= htmlspecialchars($selectedClass ?: 'Non definie', ENT_QUOTES, 'UTF-8') ?></dd>
                            <dt>Genre</dt>
                            <dd><?= htmlspecialchars(pixelverseDisplayGender($selectedGender), ENT_QUOTES, 'UTF-8') ?></dd>
                            <dt>Corps</dt>
                            <dd><?= htmlspecialchars(pixelverseBodyStyleLabel($selectedBodyStyle, $selectedGender), ENT_QUOTES, 'UTF-8') ?></dd>
                            <dt>Equipement</dt>
                            <dd><?= htmlspecialchars(pixelverseOutfitVariantLabel($selectedVariant), ENT_QUOTES, 'UTF-8') ?></dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h2 class="h5 mb-3">Traits</h2>
                        <dl class="detail-list">
                            <dt>Barbe / masque</dt>
                            <dd><?= htmlspecialchars(pixelverseAppearanceChoiceLabel('mouth_shape', $selectedMouthShape, $selectedGender), ENT_QUOTES, 'UTF-8') ?></dd>
                            <dt>Oreilles</dt>
                            <dd><?= htmlspecialchars(pixelverseAppearanceChoiceLabel('ear_shape', $selectedEarShape, $selectedGender), ENT_QUOTES, 'UTF-8') ?></dd>
                            <dt>Sourcils / regard</dt>
                            <dd><?= htmlspecialchars(pixelverseAppearanceChoiceLabel('eye_shape', $selectedEyeShape, $selectedGender), ENT_QUOTES, 'UTF-8') ?></dd>
                            <dt>Nez</dt>
                            <dd><?= htmlspecialchars(pixelverseAppearanceChoiceLabel('nose_shape', $selectedNoseShape, $selectedGender), ENT_QUOTES, 'UTF-8') ?></dd>
                            <dt>Peau</dt>
                            <dd><?= htmlspecialchars($character['skin_color'] ?: 'Non defini', ENT_QUOTES, 'UTF-8') ?></dd>
                            <dt>Cheveux</dt>
                            <dd><?= htmlspecialchars(pixelverseAppearanceChoiceLabel('hair_style', $selectedHairStyle, $selectedGender), ENT_QUOTES, 'UTF-8') ?></dd>
                            <dt>Couleur cheveux</dt>
                            <dd><?= htmlspecialchars($character['hair_color'] ?: 'Non defini', ENT_QUOTES, 'UTF-8') ?></dd>
                            <dt>Couleur yeux</dt>
                            <dd><?= htmlspecialchars($character['eye_color'] ?: 'Non defini', ENT_QUOTES, 'UTF-8') ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mt-4">
            <div class="card-body">
                <h2 class="h5 mb-3">Accessoires equipes</h2>
                <?php if (empty($accessories)): ?>
                    <p class="text-muted mb-0">Aucun accessoire equipe sur cette fiche.</p>
                <?php else: ?>
                    <div class="character-traits">
                        <?php foreach ($accessories as $acc): ?>
                            <span class="trait-pill"><?= htmlspecialchars($acc['name'], ENT_QUOTES, 'UTF-8') ?> - <?= htmlspecialchars(ucfirst($acc['type']), ENT_QUOTES, 'UTF-8') ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="mt-5">
    <h2 class="h4 mb-3">Avis et commentaires</h2>
    <?php if (empty($reviews)): ?>
        <div class="alert alert-info">Aucun avis valide pour le moment.</div>
    <?php else: ?>
        <div class="vstack gap-3">
            <?php foreach ($reviews as $rev): ?>
                <article class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                            <strong><?= htmlspecialchars($rev['reviewer_pseudo'], ENT_QUOTES, 'UTF-8') ?></strong>
                            <span class="rating-stars" aria-label="Note de <?= (int) $rev['rating'] ?> sur 5"><?= str_repeat('&#9733;', (int) $rev['rating']) ?></span>
                        </div>
                        <p class="mb-0"><?= nl2br(htmlspecialchars($rev['comment'], ENT_QUOTES, 'UTF-8')) ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (isLoggedIn() && isUser()): ?>
        <div class="card shadow-sm mt-4">
            <div class="card-body">
                <h3 class="h5 mb-3">Deposer un avis</h3>
                <form method="POST" action="/index.php?action=add-review">
                    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                    <input type="hidden" name="character_id" value="<?= (int) $character['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label" for="review-rating">Note</label>
                        <select id="review-rating" name="rating" class="form-select" required>
                            <option value="5">5 - Excellent</option>
                            <option value="4">4 - Tres bien</option>
                            <option value="3">3 - Bien</option>
                            <option value="2">2 - Moyen</option>
                            <option value="1">1 - Mauvais</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="review-comment">Commentaire</label>
                        <textarea id="review-comment" name="comment" class="form-control" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Envoyer l'avis</button>
                </form>
            </div>
        </div>
    <?php elseif (!isLoggedIn()): ?>
        <div class="alert alert-info mt-4"><a href="/index.php?action=login">Connexion</a> necessaire pour deposer un avis.</div>
    <?php endif; ?>
</div>

<script type="module" src="<?= ASSETS_URL ?>js/synty-character-viewer.js?v=73"></script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
