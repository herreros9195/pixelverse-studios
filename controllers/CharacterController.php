<?php
require_once __DIR__ . '/../models/Character.php';
require_once __DIR__ . '/../models/Review.php';
require_once __DIR__ . '/../models/Accessory.php';
require_once __DIR__ . '/../models/Log.php';

class CharacterController {
    public static function index() {
        $filters = [
            'gender' => $_GET['gender'] ?? '',
            'date_from' => $_GET['date_from'] ?? '',
            'date_to' => $_GET['date_to'] ?? '',
            'pseudo' => $_GET['pseudo'] ?? ''
        ];
        $page = max(1, intval($_GET['page'] ?? 1));
        $result = Character::getAllShared($filters, $page);
        $characters = $result['items'];
        $total = $result['total'];
        $pages = $result['pages'];
        $currentPage = $result['page'];
        $pageTitle = 'Personnages - FantasyRealm Online';
        require __DIR__ . '/../views/character/index.php';
    }

    public static function detail() {
        $id = intval($_GET['id'] ?? 0);
        $character = Character::getById($id);
        if (!$character || $character['status'] !== 'approved' || !$character['shared']) {
            http_response_code(404);
            echo "Personnage non trouvé.";
            return;
        }

        $accessories = Accessory::getByCharacterId($id);
        $reviews = Review::getByCharacterId($id, 'approved');
        $pageTitle = $character['name'] . ' - Détails';
        require __DIR__ . '/../views/character/detail.php';
    }

    public static function addReview() {
        requireAuth();
        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Token CSRF invalide.';
            header('Location: /index.php?action=character-detail&id=' . intval($_POST['character_id'] ?? 0));
            exit;
        }
        $characterId = intval($_POST['character_id'] ?? 0);
        $rating = intval($_POST['rating'] ?? 0);
        $comment = htmlspecialchars(trim($_POST['comment'] ?? ''));

        if ($rating < 1 || $rating > 5 || empty($comment)) {
            $_SESSION['flash_error'] = 'Veuillez fournir une note et un commentaire valides.';
        } else {
            Review::create($characterId, $_SESSION['user_id'], $rating, $comment);
            Log::add('review_submitted', ['character_id' => $characterId, 'user_id' => $_SESSION['user_id']]);
            $_SESSION['flash_success'] = 'Avis soumis. Publication apres validation.';
        }
        header('Location: /index.php?action=character-detail&id=' . $characterId);
        exit;
    }
}
