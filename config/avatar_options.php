<?php

function pixelverseAvatarSlug($value) {
    if (!is_string($value) || $value === '') {
        return '';
    }

    $ascii = iconv('UTF-8', 'ASCII//TRANSLIT', $value);
    if ($ascii === false) {
        $ascii = $value;
    }

    return strtolower(preg_replace('/[^a-z0-9]/i', '', $ascii));
}

function pixelverseNormalizeGender($gender) {
    $slug = pixelverseAvatarSlug((string) $gender);
    if (in_array($slug, ['female', 'feminin'], true)) {
        return 'female';
    }

    return 'male';
}

function pixelverseGenderOptions() {
    return [
        'male' => 'Masculin',
        'female' => 'Feminin',
    ];
}

function pixelverseBodyStyleCatalog() {
    return [
        'body_02' => [
            'label' => 'Corps 02',
            'title' => 'Corps 02',
            'text' => 'Silhouette feminine modulaire 02 exportee depuis le pack Synty.',
            'genders' => ['female'],
            'species' => 'female_muscular_02',
        ],
        'body_04' => [
            'label' => 'Corps 04',
            'title' => 'Corps 04',
            'text' => 'Silhouette masculine modulaire 04 exportee depuis le pack Synty.',
            'genders' => ['male'],
            'species' => 'humanspecies_04',
        ],
    ];
}

function pixelverseDefaultBodyStyleForGender($gender) {
    return pixelverseNormalizeGender($gender) === 'female' ? 'body_02' : 'body_04';
}

function pixelverseBodyStyleOptions($gender = null) {
    $catalog = pixelverseBodyStyleCatalog();
    if ($gender === null) {
        return $catalog;
    }

    $gender = pixelverseNormalizeGender($gender);

    return array_filter($catalog, static function ($option) use ($gender) {
        return in_array($gender, $option['genders'] ?? ['male', 'female'], true);
    });
}

function pixelverseNormalizeBodyStyle($gender, $value) {
    $catalog = pixelverseBodyStyleCatalog();
    $normalized = pixelverseAvatarSlug((string) $value);
    $gender = pixelverseNormalizeGender($gender);

    foreach ($catalog as $key => $definition) {
        if (($key === $value || pixelverseAvatarSlug($key) === $normalized)
            && in_array($gender, $definition['genders'] ?? ['male', 'female'], true)) {
            return $key;
        }
    }

    return pixelverseDefaultBodyStyleForGender($gender);
}

function pixelverseBodyStyleLabel($value, $gender = 'male') {
    $catalog = pixelverseBodyStyleCatalog();
    $normalized = pixelverseNormalizeBodyStyle($gender, $value);

    return $catalog[$normalized]['label'] ?? pixelverseDefaultBodyStyleForGender($gender);
}

function pixelverseSkinColorMap() {
    return [
        'Claire' => '#f5d0a9',
        'Mediterraneenne' => '#d4a373',
        'Mate' => '#c68642',
        'Foncee' => '#8d5524',
        'Pale' => '#fde4d0',
        'Rougeatre' => '#e6a08d',
    ];
}

function pixelverseHairColorMap() {
    return [
        'Brun' => '#4b2e20',
        'Blond' => '#d9b65b',
        'Roux' => '#b45309',
        'Noir' => '#171717',
        'Blanc' => '#e5e7eb',
        'Gris' => '#8a8a8a',
        'Chatain' => '#7a5533',
    ];
}

function pixelverseEyeColorMap() {
    return [
        'Marron' => '#6b4423',
        'Bleu' => '#2563eb',
        'Vert' => '#15803d',
        'Gris' => '#64748b',
        'Noisette' => '#d97706',
        'Noir' => '#1f1f1f',
        'Violet' => '#7c3aed',
    ];
}

function pixelverseDefaultAppearanceNumbers($gender) {
    $gender = pixelverseNormalizeGender($gender);

    if ($gender === 'female') {
        return [
            'face' => 1,
            'ear' => 1,
            'brow' => 1,
            'nose' => 1,
            'hair' => 10,
        ];
    }

    return [
        'face' => 1,
        'ear' => 1,
        'brow' => 3,
        'nose' => 6,
        'hair' => 2,
    ];
}

function pixelverseIndexedOption($prefix, $number, $labelPrefix, $genders = ['male', 'female']) {
    return [
        'value' => sprintf('%s_%02d', $prefix, $number),
        'label' => sprintf('%s %02d', $labelPrefix, $number),
        'genders' => $genders,
        'number' => $number,
    ];
}

function pixelverseAppearanceCatalog() {
    static $catalog = null;

    if ($catalog !== null) {
        return $catalog;
    }

    $catalog = [
        'face_styles' => [],
        'ear_styles' => [],
        'brow_styles' => [],
        'nose_styles' => [],
        'hair_styles' => [],
    ];

    $catalog['face_styles'][] = [
        'value' => 'face_none',
        'label' => 'Aucun',
        'genders' => ['male', 'female'],
        'number' => 0,
    ];

    $maleFaceLabels = [
        1 => 'Barbe royale',
        2 => 'Barbe pointue',
        3 => 'Barbe sombre',
        4 => 'Barbe courte',
        5 => 'Barbe bouclee',
    ];

    for ($index = 1; $index <= 5; $index++) {
        $catalog['face_styles'][] = [
            'value' => sprintf('face_%02d', $index),
            'label' => $maleFaceLabels[$index] ?? sprintf('Style %02d', $index),
            'genders' => ['male'],
            'number' => $index,
        ];
        $catalog['ear_styles'][] = pixelverseIndexedOption('ear', $index, 'Oreilles', $index === 1 ? ['male', 'female'] : ['male']);
        $catalog['brow_styles'][] = pixelverseIndexedOption('brow', $index, 'Sourcils', $index === 1 ? ['male', 'female'] : ['male']);
        $catalog['hair_styles'][] = pixelverseIndexedOption('hair', $index, 'Coupe', ['male']);
    }

    for ($index = 6; $index <= 10; $index++) {
        $catalog['ear_styles'][] = pixelverseIndexedOption('ear', $index, 'Oreilles', ['female']);
        $catalog['brow_styles'][] = pixelverseIndexedOption('brow', $index, 'Sourcils', ['female']);
        $catalog['hair_styles'][] = pixelverseIndexedOption('hair', $index, 'Coupe', ['female']);
    }

    for ($index = 1; $index <= 11; $index++) {
        $catalog['nose_styles'][] = pixelverseIndexedOption('nose', $index, 'Nez');
    }

    return $catalog;
}

function pixelverseAppearanceOptionsByField() {
    $catalog = pixelverseAppearanceCatalog();

    return [
        'ear_shape' => $catalog['ear_styles'],
        'eye_shape' => $catalog['brow_styles'],
        'nose_shape' => $catalog['nose_styles'],
        'mouth_shape' => $catalog['face_styles'],
        'hair_style' => $catalog['hair_styles'],
    ];
}

function pixelverseAppearanceFieldMeta() {
    return [
        'ear_shape' => ['prefix' => 'ear', 'label' => 'Oreilles'],
        'eye_shape' => ['prefix' => 'brow', 'label' => 'Sourcils'],
        'nose_shape' => ['prefix' => 'nose', 'label' => 'Nez'],
        'mouth_shape' => ['prefix' => 'face', 'label' => 'Barbe / masque'],
        'hair_style' => ['prefix' => 'hair', 'label' => 'Coupe'],
    ];
}

function pixelverseSelectableAppearanceOptions($field, $gender) {
    $gender = pixelverseNormalizeGender($gender);
    $options = pixelverseAppearanceOptionsByField()[$field] ?? [];

    return array_values(array_filter($options, static function ($option) use ($gender) {
        return in_array($gender, $option['genders'] ?? ['male', 'female'], true);
    }));
}

function pixelverseDefaultChoiceForField($field, $gender) {
    if ($field === 'mouth_shape') {
        return 'face_none';
    }

    $defaults = pixelverseDefaultAppearanceNumbers($gender);
    $meta = pixelverseAppearanceFieldMeta();
    $prefix = $meta[$field]['prefix'] ?? null;

    if ($prefix === null) {
        return '';
    }

    $number = $defaults[$prefix] ?? 1;
    return sprintf('%s_%02d', $prefix, $number);
}

function pixelverseLegacyAppearanceMap($field, $gender) {
    $gender = pixelverseNormalizeGender($gender);

    $shared = [
        'nose_shape' => [
            'droit' => 'nose_01',
            'aquilin' => 'nose_02',
            'camus' => 'nose_03',
            'rond' => 'nose_04',
            'plat' => 'nose_05',
            'retrousse' => 'nose_06',
        ],
    ];

    $male = [
        'eye_shape' => [
            'amande' => 'brow_01',
            'rond' => 'brow_02',
            'enamande' => 'brow_03',
            'bride' => 'brow_04',
            'enfonce' => 'brow_05',
            'proeminent' => 'brow_03',
        ],
        'mouth_shape' => [
            'fine' => 'face_none',
            'moyenne' => 'face_none',
            'large' => 'face_none',
            'encoeur' => 'face_none',
        ],
        'hair_style' => [
            'court' => 'hair_01',
            'degrade' => 'hair_02',
            'milong' => 'hair_03',
            'long' => 'hair_04',
            'attache' => 'hair_05',
            'volumineux' => 'hair_05',
        ],
    ];

    $female = [
        'eye_shape' => [
            'amande' => 'brow_06',
            'rond' => 'brow_07',
            'enamande' => 'brow_08',
            'bride' => 'brow_09',
            'enfonce' => 'brow_10',
            'proeminent' => 'brow_08',
        ],
        'mouth_shape' => [
            'fine' => 'face_none',
            'moyenne' => 'face_none',
            'large' => 'face_none',
            'encoeur' => 'face_none',
        ],
        'hair_style' => [
            'court' => 'hair_06',
            'degrade' => 'hair_06',
            'milong' => 'hair_07',
            'long' => 'hair_08',
            'attache' => 'hair_09',
            'volumineux' => 'hair_10',
        ],
    ];

    $map = $shared[$field] ?? [];
    if ($gender === 'female') {
        $map = array_merge($map, $female[$field] ?? []);
    } else {
        $map = array_merge($map, $male[$field] ?? []);
    }

    return $map;
}

function pixelverseNormalizeAppearanceChoice($field, $gender, $value) {
    $options = pixelverseSelectableAppearanceOptions($field, $gender);
    $normalized = pixelverseAvatarSlug((string) $value);

    foreach ($options as $option) {
        if ($option['value'] === $value || pixelverseAvatarSlug($option['value']) === $normalized) {
            return $option['value'];
        }
    }

    $legacyMap = pixelverseLegacyAppearanceMap($field, $gender);
    if (isset($legacyMap[$normalized])) {
        return $legacyMap[$normalized];
    }

    return pixelverseDefaultChoiceForField($field, $gender);
}

function pixelverseAppearanceChoiceLabel($field, $value, $gender = 'male') {
    $options = pixelverseAppearanceOptionsByField()[$field] ?? [];
    $normalized = pixelverseAvatarSlug((string) $value);

    foreach ($options as $option) {
        if ($option['value'] === $value || pixelverseAvatarSlug($option['value']) === $normalized) {
            return $option['label'];
        }
    }

    $fallback = pixelverseNormalizeAppearanceChoice($field, $gender, $value);
    foreach ($options as $option) {
        if ($option['value'] === $fallback) {
            return $option['label'];
        }
    }

    return $fallback;
}

function pixelversePlayableClassCatalog() {
    return [
        'Guerrier' => [
            'family' => 'guerrier',
            'icon' => '&#9876;&#65039;',
            'accent' => '#335c9f',
            'text' => 'Armure royale bleue et or, montee avec les pieces fantasy knight du pack Synty.',
            'default_variant' => 'warrior_full',
        ],
        'Sentinelle' => [
            'family' => 'sentinelle',
            'icon' => '&#128737;&#65039;',
            'accent' => '#37b7ff',
            'text' => 'Preset techno clair et lisible, base sur la silhouette sci-fi principale exportee.',
            'default_variant' => 'sentinel_full',
        ],
        'Ronin' => [
            'family' => 'ronin',
            'icon' => '&#129689;',
            'accent' => '#ef6b6b',
            'text' => 'Version plus agressive et masquee, construite avec les accessoires alternatifs du starter pack.',
            'default_variant' => 'ronin_fox',
        ],
        'Occultiste' => [
            'family' => 'occultiste',
            'icon' => '&#9789;',
            'accent' => '#8b5cf6',
            'text' => 'Silhouette sombre a capuche, assemblee avec l\'accessoire horror disponible localement.',
            'default_variant' => 'occult_hood',
        ],
    ];
}

function pixelversePlayableClassOptions() {
    return array_keys(pixelversePlayableClassCatalog());
}

function pixelverseAvatarClassFamilies() {
    return [
        'guerrier' => 'guerrier',
        'paladin' => 'guerrier',
        'barbare' => 'guerrier',
        'mage' => 'sentinelle',
        'sentinelle' => 'sentinelle',
        'archer' => 'ronin',
        'voleur' => 'ronin',
        'ronin' => 'ronin',
        'sorcier' => 'occultiste',
        'druide' => 'occultiste',
        'necromancien' => 'occultiste',
        'occultiste' => 'occultiste',
    ];
}

function pixelverseAvatarClassFamily($characterType) {
    $slug = pixelverseAvatarSlug((string) $characterType);
    $families = pixelverseAvatarClassFamilies();

    return $families[$slug] ?? 'guerrier';
}

function pixelverseNormalizeCharacterType($characterType) {
    $catalog = pixelversePlayableClassCatalog();
    $normalized = pixelverseAvatarSlug((string) $characterType);

    foreach ($catalog as $label => $definition) {
        if (pixelverseAvatarSlug($label) === $normalized || pixelverseAvatarSlug($definition['family']) === $normalized) {
            return $label;
        }
    }

    $family = pixelverseAvatarClassFamily($characterType);
    foreach ($catalog as $label => $definition) {
        if (($definition['family'] ?? '') === $family) {
            return $label;
        }
    }

    return 'Guerrier';
}

function pixelverseOutfitVariantCatalog() {
    return [
        'warrior_core' => [
            'label' => 'Armure de base',
            'title' => 'Armure de base',
            'icon' => '&#128737;',
            'text' => 'Silhouette chevalier epuree avec les plaques principales uniquement.',
            'families' => ['guerrier'],
        ],
        'warrior_full' => [
            'label' => 'Armure royale',
            'title' => 'Armure royale',
            'icon' => '&#9876;',
            'text' => 'Set fantasy complet avec trophées, renforts et accessoires du chevalier Synty.',
            'families' => ['guerrier'],
        ],
        'sentinel_core' => [
            'label' => 'Tenue standard',
            'title' => 'Tenue standard',
            'icon' => '&#128736;',
            'text' => 'Base sci-fi propre avec les pieces de corps principales et sans surcharge.',
            'families' => ['sentinelle'],
        ],
        'sentinel_full' => [
            'label' => 'Tenue modulee',
            'title' => 'Tenue modulee',
            'icon' => '&#9881;',
            'text' => 'Version equipee avec casque, epaules, hanches et renforts du preset sci-fi.',
            'families' => ['sentinelle'],
        ],
        'ronin_fox' => [
            'label' => 'Masque renard',
            'title' => 'Masque renard',
            'icon' => '&#129418;',
            'text' => 'Preset sculpte autour des accessoires alternatifs 10 du starter pack gratuit.',
            'families' => ['ronin'],
        ],
        'occult_hood' => [
            'label' => 'Capuche sombre',
            'title' => 'Capuche sombre',
            'icon' => '&#9789;',
            'text' => 'Lecture plus sombre avec la capuche horror et une silhouette volontairement cachee.',
            'families' => ['occultiste'],
        ],
    ];
}

function pixelverseDefaultOutfitVariantForClass($characterType) {
    $class = pixelverseNormalizeCharacterType($characterType);
    $catalog = pixelversePlayableClassCatalog();

    return $catalog[$class]['default_variant'] ?? 'warrior_full';
}

function pixelverseAllowedOutfitVariants($characterType) {
    $family = pixelverseAvatarClassFamily($characterType);
    $catalog = pixelverseOutfitVariantCatalog();
    $allowed = [];

    foreach ($catalog as $value => $definition) {
        if (in_array($family, $definition['families'] ?? [], true)) {
            $allowed[] = $value;
        }
    }

    if (empty($allowed)) {
        $allowed[] = pixelverseDefaultOutfitVariantForClass($characterType);
    }

    return array_values(array_unique($allowed));
}

function pixelverseNormalizeOutfitVariant($characterType, $variant) {
    $allowed = pixelverseAllowedOutfitVariants($characterType);
    $normalized = pixelverseAvatarSlug((string) $variant);

    foreach ($allowed as $value) {
        if ($value === $variant || pixelverseAvatarSlug($value) === $normalized) {
            return $value;
        }
    }

    return pixelverseDefaultOutfitVariantForClass($characterType);
}

function pixelverseOutfitVariantLabel($variant) {
    $catalog = pixelverseOutfitVariantCatalog();
    return $catalog[$variant]['label'] ?? 'Equipement Synty';
}

function pixelverseDisplayGender($gender) {
    $options = pixelverseGenderOptions();
    return $options[pixelverseNormalizeGender($gender)] ?? 'Masculin';
}
