<?php
require_once __DIR__ . '/../models/Character.php';
require_once __DIR__ . '/../models/Review.php';
require_once __DIR__ . '/../models/Accessory.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Log.php';

class EmployeeController {
    public static function dashboard() {
        requireAuth();
        requireRole('employee');

        $pendingCharacters = Character::getPending();
        $pendingReviews = Review::getPending();
        $accessories = Accessory::getAll();
        $recentCharacters = Character::getAllForModeration();
        $managedUsers = User::getManageableUsers();
        $pageTitle = 'Espace Employe';

        require __DIR__ . '/../views/employee/dashboard.php';
    }

    public static function validateCharacter() {
        requireAuth();
        requireRole('employee');

        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Token CSRF invalide.';
            header('Location: /index.php?action=employee-dashboard');
            exit;
        }

        $id = intval($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';
        $reason = htmlspecialchars(trim($_POST['reason'] ?? ''));
        $character = Character::getById($id);

        if ($character && in_array($status, ['approved', 'rejected'], true)) {
            if ($status === 'rejected' && $reason === '') {
                $_SESSION['flash_error'] = 'Un motif est obligatoire en cas de rejet.';
            } else {
                Character::updateStatus($id, $status, $reason);

                $subject = $status === 'approved'
                    ? 'Personnage approuve'
                    : 'Personnage rejete';
                $message = "Bonjour,\n\nLe personnage '{$character['name']}' a ete traite.";

                if ($status === 'approved') {
                    $message .= "\nLe personnage est maintenant disponible pour la personnalisation complementaire.";
                } else {
                    $message .= "\nMotif : {$reason}\nLe personnage a ete supprime apres rejet.";
                    Character::delete($id);
                }

                require_once __DIR__ . '/../helpers/MailHelper.php';
                if (!empty($character['creator_email'])) {
                    MailHelper::send($character['creator_email'], $subject, $message);
                }

                Log::add('character_validated', ['character_id' => $id, 'status' => $status]);
                $_SESSION['flash_success'] = 'Demande de personnage traitee avec succes.';
            }
        }

        header('Location: /index.php?action=employee-dashboard');
        exit;
    }

    public static function validateReview() {
        requireAuth();
        requireRole('employee');

        if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Token CSRF invalide.';
            header('Location: /index.php?action=employee-dashboard');
            exit;
        }

        $id = intval($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';

        if (in_array($status, ['approved', 'rejected'], true)) {
            Review::updateStatus($id, $status);

            $statement = getDB()->prepare(
                "SELECT r.*, c.name AS character_name, u.email AS owner_email
                 FROM reviews r
                 JOIN characters c ON r.character_id = c.id
                 JOIN users u ON c.user_id = u.id
                 WHERE r.id = ?"
            );
            $statement->execute([$id]);
            $review = $statement->fetch();

            if ($review && $status === 'approved') {
                require_once __DIR__ . '/../helpers/MailHelper.php';
                MailHelper::send(
                    $review['owner_email'],
                    'Nouvel avis sur ' . $review['character_name'],
                    "Un nouvel avis valide a ete depose sur le personnage partage."
                );
            }

            Log::add('review_validated', ['review_id' => $id, 'status' => $status]);
            $_SESSION['flash_success'] = 'Avis traite.';
        }

        header('Location: /index.php?action=employee-dashboard');
        exit;
    }

    public static function addAccessory() {
        requireAuth();
        requireRole('employee');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
                $_SESSION['flash_error'] = 'Token CSRF invalide.';
            } else {
                $name = htmlspecialchars(trim($_POST['name'] ?? ''));
                $type = $_POST['type'] ?? 'accessory';
                $description = htmlspecialchars(trim($_POST['description'] ?? ''));

                Accessory::create($name, $type, $description);
                Log::add('accessory_created', ['name' => $name]);
                $_SESSION['flash_success'] = 'Accessoire ajoute.';
            }
        }

        header('Location: /index.php?action=employee-dashboard');
        exit;
    }

    public static function disableAccessory() {
        requireAuth();
        requireRole('employee');

        if (!verifyCsrf($_GET['csrf'] ?? '')) {
            $_SESSION['flash_error'] = 'Token CSRF invalide.';
            header('Location: /index.php?action=employee-dashboard');
            exit;
        }

        $id = intval($_GET['id'] ?? 0);
        Accessory::updateStatus($id, 'disabled');
        Log::add('accessory_disabled', ['accessory_id' => $id]);

        header('Location: /index.php?action=employee-dashboard');
        exit;
    }

    public static function deleteCharacter() {
        requireAuth();
        requireRole('employee');

        if (!verifyCsrf($_GET['csrf'] ?? '')) {
            $_SESSION['flash_error'] = 'Token CSRF invalide.';
            header('Location: /index.php?action=employee-dashboard');
            exit;
        }

        $id = intval($_GET['id'] ?? 0);
        Character::delete($id);
        Log::add('character_deleted_by_employee', ['character_id' => $id]);
        $_SESSION['flash_success'] = 'Personnage supprime.';

        header('Location: /index.php?action=employee-dashboard');
        exit;
    }

    public static function suspendUser() {
        requireAuth();
        requireRole('employee');

        if (!verifyCsrf($_GET['csrf'] ?? '')) {
            $_SESSION['flash_error'] = 'Token CSRF invalide.';
            header('Location: /index.php?action=employee-dashboard');
            exit;
        }

        $id = intval($_GET['id'] ?? 0);
        $user = User::findById($id);
        if ($user && $user['role'] === 'user') {
            User::updateStatus($id, 'suspended');
            Log::add('user_suspended', ['user_id' => $id]);
            $_SESSION['flash_success'] = 'Compte utilisateur suspendu.';
        }

        header('Location: /index.php?action=employee-dashboard');
        exit;
    }

    public static function deleteUser() {
        requireAuth();
        requireRole('employee');

        if (!verifyCsrf($_GET['csrf'] ?? '')) {
            $_SESSION['flash_error'] = 'Token CSRF invalide.';
            header('Location: /index.php?action=employee-dashboard');
            exit;
        }

        $id = intval($_GET['id'] ?? 0);
        $user = User::findById($id);
        if ($user && $user['role'] === 'user') {
            User::delete($id);
            Log::add('user_deleted_by_employee', ['user_id' => $id]);
            $_SESSION['flash_success'] = 'Compte utilisateur supprime.';
        }

        header('Location: /index.php?action=employee-dashboard');
        exit;
    }
}
