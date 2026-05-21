<?php
require_once __DIR__ . '/../../config/avatar_options.php';

$traitOptions = [
    'body_style' => array_map(static function ($value, $definition) {
        return ['value' => $value, 'label' => $definition['label']];
    }, array_keys(pixelverseBodyStyleCatalog()), pixelverseBodyStyleCatalog()),
    'ear_shape' => pixelverseAppearanceOptionsByField()['ear_shape'],
    'eye_shape' => pixelverseAppearanceOptionsByField()['eye_shape'],
    'nose_shape' => pixelverseAppearanceOptionsByField()['nose_shape'],
    'mouth_shape' => pixelverseAppearanceOptionsByField()['mouth_shape'],
    'skin_color' => array_map(static function ($label) {
        return ['value' => $label, 'label' => $label];
    }, array_keys(pixelverseSkinColorMap())),
    'hair_style' => pixelverseAppearanceOptionsByField()['hair_style'],
    'hair_color' => array_map(static function ($label) {
        return ['value' => $label, 'label' => $label];
    }, array_keys(pixelverseHairColorMap())),
    'eye_color' => array_map(static function ($label) {
        return ['value' => $label, 'label' => $label];
    }, array_keys(pixelverseEyeColorMap())),
    'character_type' => array_map(static function ($label) {
        return ['value' => $label, 'label' => $label];
    }, pixelversePlayableClassOptions()),
];

function traitOptionKey($value) {
    return pixelverseAvatarSlug((string) $value);
}

function normalizeSkinTraitValue($value) {
    if (!is_string($value) || $value === '') {
        return 'Claire';
    }

    if (substr($value, 0, 1) === '#') {
        $map = array_flip(array_map('strtolower', pixelverseSkinColorMap()));
        $hex = strtolower($value);
        return $map[$hex] ?? 'Claire';
    }

    $labelMap = [];
    foreach (array_keys(pixelverseSkinColorMap()) as $label) {
        $labelMap[traitOptionKey($label)] = $label;
    }

    return $labelMap[traitOptionKey($value)] ?? 'Claire';
}

function renderTraitSelect($name, $label, $options, $selected = '') {
    $nameAttr = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $labelAttr = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
    $selectedKey = traitOptionKey($selected);

    echo "<div class=\"mb-3\">\n";
    echo "    <label class=\"form-label\" for=\"{$nameAttr}\">{$labelAttr}</label>\n";
    echo "    <select class=\"form-select\" id=\"{$nameAttr}\" name=\"{$nameAttr}\">\n";
    echo "        <option value=\"\">-- Choisir --</option>\n";

    foreach ($options as $option) {
        $value = is_array($option) ? ($option['value'] ?? '') : $option;
        $display = is_array($option) ? ($option['label'] ?? $value) : $option;
        $valueAttr = htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
        $displayAttr = htmlspecialchars((string) $display, ENT_QUOTES, 'UTF-8');
        $selectedAttr = $selectedKey !== '' && $selectedKey === traitOptionKey($value) ? ' selected' : '';

        echo "        <option value=\"{$valueAttr}\"{$selectedAttr}>{$displayAttr}</option>\n";
    }

    echo "    </select>\n";
    echo "</div>\n";
}

function renderColorPicker($name, $label, $selected = '') {
    $nameAttr = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $labelAttr = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
    $value = is_string($selected) && $selected !== '' ? $selected : '#f5d0a9';

    echo "<div class=\"mb-3\">\n";
    echo "    <label class=\"form-label\" for=\"{$nameAttr}\">{$labelAttr}</label>\n";
    echo "    <input type=\"color\" class=\"form-control form-control-color\" id=\"{$nameAttr}\" name=\"{$nameAttr}\" value=\"" . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . "\" title=\"Choisir une couleur\">\n";
    echo "</div>\n";
}
