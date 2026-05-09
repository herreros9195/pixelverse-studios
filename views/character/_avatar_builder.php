<?php
function avatarImagePath($folder, $value) {
    if (empty($value)) return '';
    $file = strtolower(preg_replace('/[^a-z0-9]/i', '', iconv('UTF-8', 'ASCII//TRANSLIT', $value))) . '.png';
    $path = "/assets/images/avatar/{$folder}/{$file}?v=2";
    $fullPath = __DIR__ . '/../../public' . str_replace('?v=2', '', $path);
    return file_exists($fullPath) ? $path : '';
}

function slugClass($value) {
    return strtolower(preg_replace('/[^a-z0-9]/i', '', iconv('UTF-8', 'ASCII//TRANSLIT', $value)));
}

function skinColorToHex($value) {
    if (empty($value)) return '#f5d0a9';
    if (str_starts_with($value, '#')) return $value;
    $map = [
        'claire' => '#f5d0a9',
        'mediterranenne' => '#d4a373',
        'foncee' => '#8d5524',
        'mate' => '#c68642',
        'pale' => '#fde4d0',
        'rougeatre' => '#e6a08d',
    ];
    return $map[slugClass($value)] ?? '#f5d0a9';
}

$bodyImg = avatarImagePath('body', $character['build'] ?? '');
$hairImg = avatarImagePath('hair', $character['hair_color'] ?? '');
$clothesImg = avatarImagePath('clothes', $character['character_type'] ?? '');
$eyesImg = avatarImagePath('eyes', $character['eye_color'] ?? '');
$skinHex = skinColorToHex($character['skin_color'] ?? '');

$buildScale = 'avatar-builder-scale-' . slugClass($character['build'] ?? 'muscle');
$noseClass = 'avatar-nose-' . slugClass($character['nose_shape'] ?? 'droit');
$mouthClass = 'avatar-mouth-' . slugClass($character['mouth_shape'] ?? 'fine');

// Armes
$weaponImg = '';
foreach ($accessories as $acc) {
    if ($acc['type'] === 'weapon') {
        $w = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $acc['name']));
        if (str_contains($w, 'epee') || str_contains($w, 'ep')) $weaponImg = '/assets/images/avatar/weapons/sword.png?v=2';
        elseif (str_contains($w, 'hache')) $weaponImg = '/assets/images/avatar/weapons/axe.png?v=2';
        elseif (str_contains($w, 'arc')) $weaponImg = '/assets/images/avatar/weapons/bow.png?v=2';
        elseif (str_contains($w, 'dague')) $weaponImg = '/assets/images/avatar/weapons/dagger.png?v=2';
        elseif (str_contains($w, 'baton')) $weaponImg = '/assets/images/avatar/weapons/staff.png?v=2';
        break;
    }
}

// Armures
$armorImg = '';
foreach ($accessories as $acc) {
    if ($acc['type'] === 'armor') {
        $armorImg = '/assets/images/avatar/armor/heavy.png?v=2';
        break;
    }
}
?>

<div id="avatar-builder" class="avatar-builder-wrap <?= htmlspecialchars($buildScale) ?>">
    <!-- Corps -->
    <img class="avatar-layer-img" data-layer="body" src="<?= $bodyImg ?: '/assets/images/avatar/body/maigre.png?v=2' ?>">
    
    <!-- Peau (CSS color overlay) -->
    <div class="avatar-skin-overlay" style="background-color: <?= htmlspecialchars($skinHex) ?>"></div>
    
    <!-- Cheveux -->
    <div class="avatar-hair-wrap">
        <img class="avatar-layer-img" data-layer="hair" src="<?= $hairImg ?: 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' ?>" style="opacity:<?= $hairImg ? 1 : 0 ?>">
    </div>
    
    <!-- Vêtements (scale selon corpulence) -->
    <img class="avatar-layer-img layer-scale" data-layer="clothes" src="<?= $clothesImg ?: 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' ?>" style="opacity:<?= $clothesImg ? 1 : 0 ?>">
    
    <!-- Armure (scale selon corpulence) -->
    <img class="avatar-layer-img layer-scale" data-layer="armor" src="<?= $armorImg ?: 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' ?>" style="opacity:<?= $armorImg ? 1 : 0 ?>">
    
    <!-- Yeux -->
    <div class="avatar-eyes-wrap">
        <img class="avatar-layer-img" data-layer="eyes" src="<?= $eyesImg ?: 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' ?>" style="opacity:<?= $eyesImg ? 1 : 0 ?>">
    </div>
    
    <!-- Nez -->
    <div class="avatar-nose <?= htmlspecialchars($noseClass) ?>"></div>
    
    <!-- Bouche -->
    <div class="avatar-mouth <?= htmlspecialchars($mouthClass) ?>"></div>
    
    <!-- Arme (scale selon corpulence) -->
    <img class="avatar-layer-img layer-scale" data-layer="weapon" src="<?= $weaponImg ?: 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' ?>" style="opacity:<?= $weaponImg ? 1 : 0 ?>">
    
    <!-- Icône type -->
    <div class="avatar-icon">
        <?= match($character['character_type'] ?? '') {
            'Guerrier' => '⚔️', 'Mage' => '🔮', 'Archer' => '🏹', 'Voleur' => '🗡️',
            'Barbare' => '🪓', 'Sorcier' => '🔥', 'Paladin' => '🛡️', 'Druide' => '🌿',
            'Nécromancien' => '💀', default => '👤'
        } ?>
    </div>
</div>
