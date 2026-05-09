<?php
$traitOptions = [
    'eye_shape' => ['Amande', 'Rond', 'En amande', 'Bridé', 'Enfoncé', 'Proéminent'],
    'nose_shape' => ['Droit', 'Aquilin', 'Camus', 'Rond', 'Plat', 'Retroussé'],
    'mouth_shape' => ['Fine', 'Moyenne', 'Large', 'En cœur'],
    'hair_color' => ['Brun', 'Blond', 'Roux', 'Noir', 'Blanc', 'Gris', 'Châtain'],
    'eye_color' => ['Bleu', 'Vert', 'Marron', 'Noisette', 'Gris', 'Noir', 'Violet'],
    'character_type' => ['Guerrier', 'Mage', 'Archer', 'Voleur', 'Barbare', 'Sorcier', 'Paladin', 'Druide', 'Nécromancien'],
    'build' => ['Maigre', 'Musclé', 'Gros', 'Athlétique', 'Élancé', 'Trapu'],
    'age_group' => ['Jeune', 'Adulte', 'Mûr', 'Vieux'],
];

function renderTraitSelect($name, $label, $options, $selected = '') {
    if ($options === 'colorpicker') {
        renderColorPicker($name, $label, $selected);
        return;
    }
    $nameAttr = htmlspecialchars($name);
    $labelAttr = htmlspecialchars($label);
    echo "<div class=\"mb-3\">\n";
    echo "    <label class=\"form-label\">{$labelAttr}</label>\n";
    echo "    <select class=\"form-select\" name=\"{$nameAttr}\">\n";
    echo "        <option value=\"\">-- Choisir --</option>\n";
    foreach ($options as $opt) {
        $optAttr = htmlspecialchars($opt);
        $sel = ($selected === $opt) ? ' selected' : '';
        echo "        <option value=\"{$optAttr}\"{$sel}>{$optAttr}</option>\n";
    }
    echo "    </select>\n";
    echo "</div>\n";
}

function renderColorPicker($name, $label, $selected = '') {
    $nameAttr = htmlspecialchars($name);
    $labelAttr = htmlspecialchars($label);
    $val = $selected ?: '#f5d0a9';
    echo "<div class=\"mb-3\">\n";
    echo "    <label class=\"form-label\">{$labelAttr}</label>\n";
    echo "    <input type=\"color\" class=\"form-control form-control-color\" name=\"{$nameAttr}\" value=\"{$val}\" title=\"Choisir une couleur de peau\">\n";
    echo "</div>\n";
}
