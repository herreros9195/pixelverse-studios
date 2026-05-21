<?php
require_once __DIR__ . '/../../config/avatar_options.php';
require __DIR__ . '/../layouts/header.php';
?>

<section class="page-intro">
    <div>
        <p class="eyebrow">Espace joueur</p>
        <h1 class="page-title">Tableau de bord des personnages</h1>
        <p class="page-copy">Suivi des validations, modifications des personnages approuves et partage public des fiches valides.</p>
    </div>
    <a href="/index.php?action=character-create" class="btn btn-primary btn-lg px-4 d-inline-flex align-items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="14.5 17.5 3 6 3 3 6 3 17.5 14.5"></polyline><line x1="13" y1="19" x2="19" y2="13"></line><line x1="16" y1="16" x2="20" y2="20"></line><line x1="19" y1="21" x2="21" y2="19"></line></svg>
        Creer un personnage
    </a>
</section>

<div class="row g-4">
    <?php if (empty($characters)): ?>
        <div class="col-12">
            <div class="alert alert-info" style="background: rgba(59, 130, 246, 0.1); border-color: rgba(59, 130, 246, 0.2); color: #93c5fd;">
                Aucun personnage enregistre pour ce compte. La premiere creation declenche la demande de validation employee.
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($characters as $char): ?>
            <?php
            $selectedGender = pixelverseNormalizeGender($char['gender'] ?? 'male');
            $selectedBodyStyle = pixelverseNormalizeBodyStyle($selectedGender, $char['body_style'] ?? '');
            $selectedEarShape = pixelverseNormalizeAppearanceChoice('ear_shape', $selectedGender, $char['ear_shape'] ?? '');
            $selectedEyeShape = pixelverseNormalizeAppearanceChoice('eye_shape', $selectedGender, $char['eye_shape'] ?? '');
            $selectedNoseShape = pixelverseNormalizeAppearanceChoice('nose_shape', $selectedGender, $char['nose_shape'] ?? '');
            $selectedMouthShape = pixelverseNormalizeAppearanceChoice('mouth_shape', $selectedGender, $char['mouth_shape'] ?? '');
            $selectedHairStyle = pixelverseNormalizeAppearanceChoice('hair_style', $selectedGender, $char['hair_style'] ?? '');
            $selectedClass = pixelverseNormalizeCharacterType($char['character_type'] ?? 'Guerrier');
            $selectedVariant = pixelverseNormalizeOutfitVariant($selectedClass, $char['outfit_variant'] ?? '');
            $statusBadge = match ($char['status']) {
                'approved' => ['label' => 'Approuve', 'style' => 'background:rgba(34,197,94,0.1);color:#86efac;border:1px solid rgba(34,197,94,0.2);'],
                'rejected' => ['label' => 'Rejete', 'style' => 'background:rgba(239,68,68,0.1);color:#fca5a5;border:1px solid rgba(239,68,68,0.2);'],
                default => ['label' => 'En attente', 'style' => 'background:rgba(245,158,11,0.1);color:#fcd34d;border:1px solid rgba(245,158,11,0.2);'],
            };
            ?>
            <div class="col-lg-4 col-md-6">
                <article class="card card-character h-100" style="background: var(--app-surface); border-color: var(--app-border);">
                    <div class="card-body">
                        <div class="character-preview-panel mb-3">
                            <div
                                id="dashboard-three-viewer-<?= (int) $char['id'] ?>"
                                data-synty-viewer
                                data-character-type="<?= htmlspecialchars($selectedClass, ENT_QUOTES, 'UTF-8') ?>"
                                data-skin-color="<?= htmlspecialchars($char['skin_color'] ?? 'Claire', ENT_QUOTES, 'UTF-8') ?>"
                                data-gender="<?= htmlspecialchars($selectedGender, ENT_QUOTES, 'UTF-8') ?>"
                                data-body-style="<?= htmlspecialchars($selectedBodyStyle, ENT_QUOTES, 'UTF-8') ?>"
                                data-ear-shape="<?= htmlspecialchars($selectedEarShape, ENT_QUOTES, 'UTF-8') ?>"
                                data-eye-shape="<?= htmlspecialchars($selectedEyeShape, ENT_QUOTES, 'UTF-8') ?>"
                                data-nose-shape="<?= htmlspecialchars($selectedNoseShape, ENT_QUOTES, 'UTF-8') ?>"
                                data-mouth-shape="<?= htmlspecialchars($selectedMouthShape, ENT_QUOTES, 'UTF-8') ?>"
                                data-hair-style="<?= htmlspecialchars($selectedHairStyle, ENT_QUOTES, 'UTF-8') ?>"
                                data-hair-color="<?= htmlspecialchars($char['hair_color'] ?? 'Brun', ENT_QUOTES, 'UTF-8') ?>"
                                data-eye-color="<?= htmlspecialchars($char['eye_color'] ?? 'Bleu', ENT_QUOTES, 'UTF-8') ?>"
                                data-outfit-variant="<?= htmlspecialchars($selectedVariant, ENT_QUOTES, 'UTF-8') ?>"
                                data-preview-mode="class"
                                data-camera-mode="full"
                                data-scale="0.85"
                                style="width:100%;height:200px;border-radius:0.75rem;background:rgba(0,0,0,0.3);"></div>
                        </div>

                        <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                            <div>
                                <h2 class="h5 mb-1" style="color: var(--app-text);"><?= htmlspecialchars($char['name'], ENT_QUOTES, 'UTF-8') ?></h2>
                                <p class="mb-0" style="color: var(--app-muted);"><?= htmlspecialchars(pixelverseDisplayGender($selectedGender), ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                            <span class="badge" style="<?= htmlspecialchars($statusBadge['style'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($statusBadge['label'], ENT_QUOTES, 'UTF-8') ?></span>
                        </div>

                        <div class="character-traits mb-3">
                            <span class="trait-pill"><?= htmlspecialchars($selectedClass ?: 'Classe a definir', ENT_QUOTES, 'UTF-8') ?></span>
                            <span class="trait-pill"><?= htmlspecialchars(pixelverseOutfitVariantLabel($selectedVariant), ENT_QUOTES, 'UTF-8') ?></span>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <a href="/index.php?action=character-detail&id=<?= (int) $char['id'] ?>" class="btn btn-sm btn-outline-primary">Voir</a>
                            <a href="/index.php?action=character-edit&id=<?= (int) $char['id'] ?>" class="btn btn-sm btn-outline-secondary">Modifier</a>
                            <?php if ($char['status'] === 'approved'): ?>
                                <a href="/index.php?action=character-share&id=<?= (int) $char['id'] ?>&share=<?= $char['shared'] ? 0 : 1 ?>&csrf=<?= urlencode(csrfToken()) ?>" class="btn btn-sm btn-outline-<?= $char['shared'] ? 'danger' : 'success' ?>">
                                    <?= $char['shared'] ? 'Arreter le partage' : 'Partager' ?>
                                </a>
                            <?php endif; ?>
                            <a href="/index.php?action=character-delete&id=<?= (int) $char['id'] ?>&csrf=<?= urlencode(csrfToken()) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer definitivement ce personnage ?')">Supprimer</a>
                        </div>

                        <form method="POST" action="/index.php?action=character-duplicate" class="mt-3">
                            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                            <input type="hidden" name="id" value="<?= (int) $char['id'] ?>">
                            <label class="form-label small" for="duplicate-name-<?= (int) $char['id'] ?>" style="color: var(--app-muted);">Duplication</label>
                            <div class="input-group input-group-sm">
                                <input type="text" id="duplicate-name-<?= (int) $char['id'] ?>" name="new_name" class="form-control" placeholder="Nom de la copie" required>
                                <button type="submit" class="btn btn-outline-primary" onclick="return confirm('Dupliquer ce personnage ?')">Dupliquer</button>
                            </div>
                        </form>
                    </div>
                </article>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script type="module" src="<?= ASSETS_URL ?>js/synty-character-viewer.js?v=73"></script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
