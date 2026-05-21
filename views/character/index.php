<?php
$pageCss = [ASSETS_URL . 'css/pixelverse-ui-upgrade.css?v=2'];
require_once __DIR__ . '/../../config/avatar_options.php';
require __DIR__ . '/../layouts/header.php';
?>

<div class="pv-page-shell">
    <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-end mb-4">
        <div>
            <div class="pv-kicker">Galerie publique</div>
            <h1 class="pv-page-title">Personnages partages</h1>
            <p class="pv-page-subtitle mb-0">
                Explorez les creations validees de la communaute FantasyRealm Online et filtrez les heros par profil,
                date de creation ou createur.
            </p>
        </div>

        <?php if (!empty($_SESSION['user'])): ?>
            <a href="/index.php?action=character-create" class="btn btn-primary btn-lg">Creer un personnage</a>
        <?php endif; ?>
    </div>

    <form method="get" action="/index.php" class="pv-panel pv-filter-panel mb-4">
        <input type="hidden" name="action" value="characters">

        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="filterGender" class="form-label">Genre</label>
                <select id="filterGender" name="gender" class="form-select">
                    <option value="">Tous</option>
                    <option value="male" <?= (($filters['gender'] ?? '') === 'male') ? 'selected' : '' ?>>Masculin</option>
                    <option value="female" <?= (($filters['gender'] ?? '') === 'female') ? 'selected' : '' ?>>Feminin</option>
                </select>
            </div>

            <div class="col-md-2">
                <label for="filterStart" class="form-label">Date debut</label>
                <input id="filterStart" name="date_from" type="date" class="form-control" value="<?= htmlspecialchars($filters['date_from'] ?? '') ?>">
            </div>

            <div class="col-md-2">
                <label for="filterEnd" class="form-label">Date fin</label>
                <input id="filterEnd" name="date_to" type="date" class="form-control" value="<?= htmlspecialchars($filters['date_to'] ?? '') ?>">
            </div>

            <div class="col-md-3">
                <label for="filterPseudo" class="form-label">Pseudo createur</label>
                <input id="filterPseudo" name="pseudo" type="text" class="form-control" placeholder="Ex : player1" value="<?= htmlspecialchars($filters['pseudo'] ?? '') ?>">
            </div>

            <div class="col-md-2 d-grid gap-2 d-md-flex">
                <button class="btn btn-primary" type="submit">Filtrer</button>
                <a class="btn btn-outline-secondary" href="/index.php?action=characters">Reinitialiser</a>
            </div>
        </div>
    </form>

    <?php if (empty($characters)): ?>
        <div class="pv-panel pv-empty-state">
            <div class="display-6 mb-3">🧙</div>
            <h2 class="h4">Aucun personnage partage</h2>
            <p class="mb-0">Essayez de modifier les filtres ou revenez plus tard.</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($characters as $char): ?>
                <?php
                    $id = (int) ($char['id'] ?? 0);
                    $name = $char['name'] ?? 'Personnage';
                    $pseudo = $char['creator_pseudo'] ?? 'Createur';
                    $selectedGender = pixelverseNormalizeGender($char['gender'] ?? 'male');
                    $selectedBodyStyle = pixelverseNormalizeBodyStyle($selectedGender, $char['body_style'] ?? '');
                    $selectedEarShape = pixelverseNormalizeAppearanceChoice('ear_shape', $selectedGender, $char['ear_shape'] ?? '');
                    $selectedEyeShape = pixelverseNormalizeAppearanceChoice('eye_shape', $selectedGender, $char['eye_shape'] ?? '');
                    $selectedNoseShape = pixelverseNormalizeAppearanceChoice('nose_shape', $selectedGender, $char['nose_shape'] ?? '');
                    $selectedMouthShape = pixelverseNormalizeAppearanceChoice('mouth_shape', $selectedGender, $char['mouth_shape'] ?? '');
                    $selectedHairStyle = pixelverseNormalizeAppearanceChoice('hair_style', $selectedGender, $char['hair_style'] ?? '');
                    $selectedClass = pixelverseNormalizeCharacterType($char['character_type'] ?? 'Guerrier');
                    $selectedVariant = pixelverseNormalizeOutfitVariant($selectedClass, $char['outfit_variant'] ?? '');
                    $createdAt = $char['created_at'] ?? '';
                ?>

                <div class="col-sm-6 col-xl-4">
                    <article class="pv-panel pv-character-card">
                        <div class="pv-character-visual">
                            <div
                                id="gallery-three-viewer-<?= $id ?>"
                                data-synty-viewer
                                data-character-type="<?= htmlspecialchars($selectedClass) ?>"
                                data-skin-color="<?= htmlspecialchars($char['skin_color'] ?? 'Claire') ?>"
                                data-gender="<?= htmlspecialchars($selectedGender) ?>"
                                data-body-style="<?= htmlspecialchars($selectedBodyStyle) ?>"
                                data-ear-shape="<?= htmlspecialchars($selectedEarShape) ?>"
                                    data-eye-shape="<?= htmlspecialchars($selectedEyeShape) ?>"
                                data-nose-shape="<?= htmlspecialchars($selectedNoseShape) ?>"
                                data-mouth-shape="<?= htmlspecialchars($selectedMouthShape) ?>"
                                data-hair-style="<?= htmlspecialchars($selectedHairStyle) ?>"
                                data-hair-color="<?= htmlspecialchars($char['hair_color'] ?? 'Brun') ?>"
                                data-eye-color="<?= htmlspecialchars($char['eye_color'] ?? 'Bleu') ?>"
                                data-outfit-variant="<?= htmlspecialchars($selectedVariant) ?>"
                                data-preview-mode="class"
                                data-scale="0.9"
                                style="width:100%;height:220px;border-radius:0.75rem;background:rgba(0,0,0,0.3);"></div>
                        </div>

                        <div class="px-3 pb-3">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                <div>
                                    <h2 class="h5 fw-bold mb-1"><?= htmlspecialchars($name) ?></h2>
                                    <div class="small text-muted">Par <strong><?= htmlspecialchars($pseudo) ?></strong></div>
                                </div>
                                <span class="pv-tag">Valide</span>
                            </div>

                            <div class="d-flex flex-wrap gap-2 my-3">
                                <span class="pv-tag"><?= htmlspecialchars($selectedClass ?: 'Classe a definir') ?></span>
                                <span class="pv-tag"><?= htmlspecialchars(pixelverseDisplayGender($selectedGender)) ?></span>
                                <span class="pv-tag"><?= htmlspecialchars(pixelverseOutfitVariantLabel($selectedVariant)) ?></span>
                            </div>

                            <?php if ($createdAt): ?>
                                <div class="small text-muted mb-3">Cree le <?= htmlspecialchars(date('d/m/Y', strtotime($createdAt))) ?></div>
                            <?php endif; ?>

                            <a href="/index.php?action=character-detail&id=<?= $id ?>" class="btn btn-outline-primary w-100">
                                Voir la fiche
                            </a>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($pages > 1): ?>
        <nav class="mt-4" aria-label="Pagination des personnages">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $pages; $i++): ?>
                    <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                        <a class="page-link" href="/index.php?action=characters&page=<?= $i ?>&gender=<?= urlencode($filters['gender']) ?>&date_from=<?= urlencode($filters['date_from']) ?>&date_to=<?= urlencode($filters['date_to']) ?>&pseudo=<?= urlencode($filters['pseudo']) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<script type="module" src="<?= ASSETS_URL ?>js/synty-character-viewer.js?v=73"></script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
