<?php
require_once __DIR__ . '/../models/Character.php';
require_once __DIR__ . '/../models/Accessory.php';
require_once __DIR__ . '/../models/Log.php';

class UserController {
    public static function dashboard() {
        requireAuth();
        requireRole('user');
        $characters = Character::getByUserId($_SESSION['user_id']);
        $pageTitle = 'Mon Espace';
        require __DIR__ . '/../views/user/dashboard.php';
    }

    public static function createCharacter() {
        requireAuth();
        requireRole('user');
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
                $error = 'Token CSRF invalide.';
            } else {
                $name = htmlspecialchars(trim($_POST['name'] ?? ''));
                $db = getDB();
                $check = $db->prepare("SELECT id FROM characters WHERE name = ?");
                $check->execute([$name]);
                if ($check->fetch()) {
                    $error = 'Ce nom de personnage est déjà utilisé.';
                } else {
                    $data = [
                        'user_id' => $_SESSION['user_id'],
                        'name' => $name,
                        'gender' => $_POST['gender'] ?? 'other',
                        'eye_shape' => htmlspecialchars(trim($_POST['eye_shape'] ?? '')),
                        'nose_shape' => htmlspecialchars(trim($_POST['nose_shape'] ?? '')),
                        'mouth_shape' => htmlspecialchars(trim($_POST['mouth_shape'] ?? '')),
                        'skin_color' => htmlspecialchars(trim($_POST['skin_color'] ?? '')),
                        'hair_color' => htmlspecialchars(trim($_POST['hair_color'] ?? '')),
                        'eye_color' => htmlspecialchars(trim($_POST['eye_color'] ?? '')),
                        'character_type' => htmlspecialchars(trim($_POST['character_type'] ?? '')),
                        'build' => htmlspecialchars(trim($_POST['build'] ?? '')),
                        'age_group' => htmlspecialchars(trim($_POST['age_group'] ?? ''))
                    ];
                    Character::create($data);
                    Log::add('character_created', ['name' => $name]);
                    $success = 'Personnage créé avec succès ! Il doit être validé par un employé.';
                }
            }
        }

        $pageTitle = 'Créer un personnage';
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

        $error = '';
        $success = '';
        $allAccessories = Accessory::getAll('available');
        $characterAccessories = Accessory::getByCharacterId($id);
        $characterAccessoryIds = array_column($characterAccessories, 'id');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
                $error = 'Token CSRF invalide.';
            } else {
                if (isset($_POST['update_traits'])) {
                    $data = [
                        'eye_shape' => htmlspecialchars(trim($_POST['eye_shape'] ?? '')),
                        'nose_shape' => htmlspecialchars(trim($_POST['nose_shape'] ?? '')),
                        'mouth_shape' => htmlspecialchars(trim($_POST['mouth_shape'] ?? '')),
                        'skin_color' => htmlspecialchars(trim($_POST['skin_color'] ?? '')),
                        'hair_color' => htmlspecialchars(trim($_POST['hair_color'] ?? '')),
                        'eye_color' => htmlspecialchars(trim($_POST['eye_color'] ?? '')),
                        'character_type' => htmlspecialchars(trim($_POST['character_type'] ?? '')),
                        'build' => htmlspecialchars(trim($_POST['build'] ?? '')),
                        'age_group' => htmlspecialchars(trim($_POST['age_group'] ?? ''))
                    ];
                    Character::update($id, $data);
                    Log::add('character_updated', ['character_id' => $id]);
                    $success = 'Personnage mis à jour.';
                }
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
        Accessory::addToCharacter($characterId, $accessoryId);
        Log::add('accessory_added', ['character_id' => $characterId, 'accessory_id' => $accessoryId]);
        header('Location: /index.php?action=character-edit&id=' . $characterId);
        exit;
    }

    public static function deleteCharacter() {
        requireAuth();
        requireRole('user');
        $id = intval($_GET['id'] ?? 0);
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
        $newName = htmlspecialchars(trim($_POST['new_name'] ?? ''));
        $character = Character::getById($id);
        if ($character && $character['user_id'] == $_SESSION['user_id']) {
            $db = getDB();
            $check = $db->prepare("SELECT id FROM characters WHERE name = ?");
            $check->execute([$newName]);
            if (!$check->fetch() && !empty($newName)) {
                Character::duplicate($id, $newName);
                Log::add('character_duplicated', ['original_id' => $id, 'new_name' => $newName]);
                $_SESSION['flash_success'] = 'Personnage dupliqué avec succès.';
            } else {
                $_SESSION['flash_error'] = 'Ce nom est déjà utilisé ou invalide.';
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
        $character = Character::getById($id);
        if ($character && $character['user_id'] == $_SESSION['user_id'] && $character['status'] === 'approved') {
            Character::toggleShare($id, $share);
            Log::add('character_share_toggled', ['character_id' => $id, 'shared' => $share]);
        }
        header('Location: /index.php?action=dashboard');
        exit;
    }
}
