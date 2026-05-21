<?php
require_once __DIR__ . '/../models/Character.php';
require_once __DIR__ . '/../models/Accessory.php';
require_once __DIR__ . '/../models/Log.php';
require_once __DIR__ . '/../config/avatar_options.php';

class UserController {
    private static function clean($value) {
        return htmlspecialchars(trim((string) $value), ENT_QUOTES, 'UTF-8');
    }

    public static function dashboard() {
        requireAuth();
        requireRole('user');

        $characters = Character::getByUserId($_SESSION['user_id']);
        $pageTitle = 'Tableau de bord';

        require __DIR__ . '/../views/user/dashboard.php';
    }

    public static function createCharacter() {
        requireAuth();
        requireRole('user');

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
                $error = 'Token CSRF invalide.';
            } else {
                $name = self::clean($_POST['name'] ?? '');

                if ($name === '') {
                    $error = 'Le nom du personnage est obligatoire.';
                } else {
                    $db = getDB();
                    $check = $db->prepare("SELECT id FROM characters WHERE name = ?");
                    $check->execute([$name]);

                    if ($check->fetch()) {
                        $error = 'Ce nom de personnage est deja utilise.';
                    } else {
                        $data = [
                            'user_id' => $_SESSION['user_id'],
                            'name' => $name,
                            'gender' => pixelverseNormalizeGender($_POST['gender'] ?? 'male'),
                            'body_style' => pixelverseNormalizeBodyStyle($_POST['gender'] ?? 'male', self::clean($_POST['body_style'] ?? '')),
                            'ear_shape' => pixelverseNormalizeAppearanceChoice('ear_shape', $_POST['gender'] ?? 'male', self::clean($_POST['ear_shape'] ?? '')),
                            'eye_shape' => pixelverseNormalizeAppearanceChoice('eye_shape', $_POST['gender'] ?? 'male', self::clean($_POST['eye_shape'] ?? '')),
                            'nose_shape' => pixelverseNormalizeAppearanceChoice('nose_shape', $_POST['gender'] ?? 'male', self::clean($_POST['nose_shape'] ?? '')),
                            'mouth_shape' => pixelverseNormalizeAppearanceChoice('mouth_shape', $_POST['gender'] ?? 'male', self::clean($_POST['mouth_shape'] ?? '')),
                            'skin_color' => self::clean($_POST['skin_color'] ?? 'Claire'),
                            'hair_style' => pixelverseNormalizeAppearanceChoice('hair_style', $_POST['gender'] ?? 'male', self::clean($_POST['hair_style'] ?? '')),
                            'hair_color' => self::clean($_POST['hair_color'] ?? 'Brun'),
                            'eye_color' => self::clean($_POST['eye_color'] ?? 'Bleu'),
                            'character_type' => pixelverseNormalizeCharacterType(self::clean($_POST['character_type'] ?? 'Guerrier')),
                            'outfit_variant' => pixelverseNormalizeOutfitVariant(
                                self::clean($_POST['character_type'] ?? 'Guerrier'),
                                self::clean($_POST['outfit_variant'] ?? '')
                            ),
                        ];

                        $characterId = Character::create($data);

                        if ($characterId) {
                            Log::add('character_created', ['name' => $name, 'character_id' => $characterId]);
                            $_SESSION['flash_success'] = 'Personnage cree avec succes. Le rendu 3D Synty est disponible dans le tableau de bord.';
                            header('Location: /index.php?action=dashboard');
                            exit;
                        }

                        $error = 'Impossible de creer le personnage.';
                    }
                }
            }
        }

        $pageTitle = 'Creer un personnage';
        require __DIR__ . '/../views/user/create_character.php';
    }

    public static function editCharacter() {
        requireAuth();
        requireRole('user');

        $id = intval($_GET['id'] ?? 0);
        $character = Character::getById($id);

        if (!$character || $character['user_id'] != $_SESSION['user_id']) {
            header('Location: /index.php?action=dashboard');
            exit;
        }

        if ($character['status'] !== 'approved') {
            $_SESSION['flash_error'] = 'Le personnage doit etre approuve avant la personnalisation complementaire.';
            header('Location: /index.php?action=dashboard');
            exit;
        }

        $error = '';
        $success = '';
        $allAccessories = Accessory::getAll('available');
        $characterAccessories = Accessory::getByCharacterId($id);
        $characterAccessoryIds = array_column($characterAccessories, 'id');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
                $error = 'Token CSRF invalide.';
            } elseif (isset($_POST['update_traits'])) {
                $data = [
                    'body_style' => pixelverseNormalizeBodyStyle($character['gender'] ?? 'male', self::clean($_POST['body_style'] ?? '')),
                    'ear_shape' => pixelverseNormalizeAppearanceChoice('ear_shape', $character['gender'] ?? 'male', self::clean($_POST['ear_shape'] ?? '')),
                    'eye_shape' => pixelverseNormalizeAppearanceChoice('eye_shape', $character['gender'] ?? 'male', self::clean($_POST['eye_shape'] ?? '')),
                    'nose_shape' => pixelverseNormalizeAppearanceChoice('nose_shape', $character['gender'] ?? 'male', self::clean($_POST['nose_shape'] ?? '')),
                    'mouth_shape' => pixelverseNormalizeAppearanceChoice('mouth_shape', $character['gender'] ?? 'male', self::clean($_POST['mouth_shape'] ?? '')),
                    'skin_color' => self::clean($_POST['skin_color'] ?? 'Claire'),
                    'hair_style' => pixelverseNormalizeAppearanceChoice('hair_style', $character['gender'] ?? 'male', self::clean($_POST['hair_style'] ?? '')),
                    'hair_color' => self::clean($_POST['hair_color'] ?? 'Brun'),
                    'eye_color' => self::clean($_POST['eye_color'] ?? 'Bleu'),
                    'character_type' => pixelverseNormalizeCharacterType(self::clean($_POST['character_type'] ?? 'Guerrier')),
                    'outfit_variant' => pixelverseNormalizeOutfitVariant(
                        self::clean($_POST['character_type'] ?? 'Guerrier'),
                        self::clean($_POST['outfit_variant'] ?? '')
                    ),
                ];

                Character::update($id, $data);
                Log::add('character_updated', ['character_id' => $id]);
                $success = 'Personnage mis a jour.';

                $character = Character::getById($id);
            }
        }

        $pageTitle = 'Modifier ' . $character['name'];
        require __DIR__ . '/../views/user/edit_character.php';
    }

    public static function addAccessory() {
        requireAuth();
        requireRole('user');

        $characterId = intval($_POST['character_id'] ?? 0);
        $accessoryId = intval($_POST['accessory_id'] ?? 0);
        $character = Character::getById($characterId);

        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Token CSRF invalide.';
        } elseif (!$character || $character['user_id'] != $_SESSION['user_id']) {
            $_SESSION['flash_error'] = 'Personnage introuvable.';
        } elseif ($character['status'] !== 'approved') {
            $_SESSION['flash_error'] = 'Le personnage doit etre approuve avant l\'equipement des accessoires.';
        } else {
            Accessory::addToCharacter($characterId, $accessoryId);
            Log::add('accessory_added', ['character_id' => $characterId, 'accessory_id' => $accessoryId]);
            $_SESSION['flash_success'] = 'Accessoire ajoute au personnage.';
        }

        header('Location: /index.php?action=character-edit&id=' . $characterId);
        exit;
    }

    public static function deleteCharacter() {
        requireAuth();
        requireRole('user');

        $id = intval($_GET['id'] ?? 0);

        if (!verifyCsrf($_GET['csrf'] ?? '')) {
            $_SESSION['flash_error'] = 'Token CSRF invalide.';
            header('Location: /index.php?action=dashboard');
            exit;
        }

        $character = Character::getById($id);

        if ($character && $character['user_id'] == $_SESSION['user_id']) {
            Character::delete($id);
            Log::add('character_deleted', ['character_id' => $id]);
        }

        header('Location: /index.php?action=dashboard');
        exit;
    }

    public static function duplicateCharacter() {
        requireAuth();
        requireRole('user');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?action=dashboard');
            exit;
        }

        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Token CSRF invalide.';
            header('Location: /index.php?action=dashboard');
            exit;
        }

        $id = intval($_POST['id'] ?? 0);
        $newName = self::clean($_POST['new_name'] ?? '');
        $character = Character::getById($id);

        if ($character && $character['user_id'] == $_SESSION['user_id']) {
            $db = getDB();
            $check = $db->prepare("SELECT id FROM characters WHERE name = ?");
            $check->execute([$newName]);

            if (!$check->fetch() && $newName !== '') {
                $newId = Character::duplicate($id, $newName);
                Log::add('character_duplicated', ['original_id' => $id, 'new_name' => $newName]);
                $_SESSION['flash_success'] = 'Personnage duplique avec succes.';
            } else {
                $_SESSION['flash_error'] = 'Ce nom est deja utilise ou invalide.';
            }
        }

        header('Location: /index.php?action=dashboard');
        exit;
    }

    public static function shareCharacter() {
        requireAuth();
        requireRole('user');

        $id = intval($_GET['id'] ?? 0);
        $share = intval($_GET['share'] ?? 0);

        if (!verifyCsrf($_GET['csrf'] ?? '')) {
            $_SESSION['flash_error'] = 'Token CSRF invalide.';
            header('Location: /index.php?action=dashboard');
            exit;
        }

        $character = Character::getById($id);

        if ($character && $character['user_id'] == $_SESSION['user_id'] && $character['status'] === 'approved') {
            Character::toggleShare($id, $share);
            Log::add('character_share_toggled', ['character_id' => $id, 'shared' => $share]);
        }

        header('Location: /index.php?action=dashboard');
        exit;
    }

    public static function previewCharacterPortrait() {
        requireAuth();
        requireRole('user');

        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => 'La generation de portrait 2D est desactivee. Le rendu 3D est genere directement dans la page.'
        ]);
        exit;
    }

    public static function avatarCreator() {
        requireAuth();
        requireRole('user');

        $_SESSION['flash_success'] = 'Le createur 3D Synty est integre dans la page de creation, le tableau de bord et la page de modification.';
        header('Location: /index.php?action=dashboard');
        exit;
    }

    public static function avatarSave() {
        requireAuth();
        requireRole('user');

        $_SESSION['flash_success'] = 'Aucune sauvegarde avatar separee necessaire : le rendu 3D Synty est calcule depuis les traits du personnage.';
        header('Location: /index.php?action=dashboard');
        exit;
    }
}
