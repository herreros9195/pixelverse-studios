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
        $pageTitle = 'Espace Employé';
        require __DIR__ . '/../views/employee/dashboard.php';
    }

    public static function validateCharacter() {
        requireAuth();
        requireRole('employee');
        $id = intval($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';
        $reason = htmlspecialchars(trim($_POST['reason'] ?? ''));
        $character = Character::getById($id);

        if ($character && in_array($status, ['approved', 'rejected'])) {
            if ($status === 'rejected' && empty($reason)) {
                $_SESSION['flash_error'] = 'Un motif est obligatoire en cas de rejet.';
            } else {
                Character::updateStatus($id, $status, $reason);
                $subject = $status === 'approved' ? 'Votre personnage a été approuvé' : 'Votre personnage a été rejeté';
                $message = "Bonjour,\n\nVotre personnage '{$character['name']}' a été $subject.";
                if ($status === 'rejected') {
                    $message .= "\nMotif : $reason\n\nLe personnage a été supprimé.";
                    Character::delete($id);
                }
                require_once __DIR__ . '/../helpers/MailHelper.php';
                MailHelper::send($character['creator_email'], $subject, $message);
                Log::add('character_validated', ['character_id' => $id, 'status' => $status]);
                $_SESSION['flash_success'] = 'Action effectuée avec succès.';
            }
        }
        header('Location: /index.php?action=employee-dashboard');
        exit;
    }

    public static function validateReview() {
        requireAuth();
        requireRole('employee');
        $id = intval($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';
        $review = Review::getPending();
        if (in_array($status, ['approved', 'rejected'])) {
            Review::updateStatus($id, $status);
            $rev = getDB()->prepare("SELECT r.*, c.name as character_name, u.email as owner_email FROM reviews r JOIN characters c ON r.character_id = c.id JOIN users u ON c.user_id = u.id WHERE r.id = ?");
            $rev->execute([$id]);
            $data = $rev->fetch();
            if ($data && $status === 'approved') {
                require_once __DIR__ . '/../helpers/MailHelper.php';
                MailHelper::send($data['owner_email'], 'Nouvel avis sur ' . $data['character_name'], "Un nouvel avis a été déposé sur votre personnage.");
            }
            Log::add('review_validated', ['review_id' => $id, 'status' => $status]);
            $_SESSION['flash_success'] = 'Avis traité.';
        }
        header('Location: /index.php?action=employee-dashboard');
        exit;
    }

    public static function addAccessory() {
        requireAuth();
        requireRole('employee');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = htmlspecialchars(trim($_POST['name'] ?? ''));
            $type = $_POST['type'] ?? 'accessory';
            $description = htmlspecialchars(trim($_POST['description'] ?? ''));
            Accessory::create($name, $type, $description);
            Log::add('accessory_created', ['name' => $name]);
            $_SESSION['flash_success'] = 'Accessoire ajouté.';
        }
        header('Location: /index.php?action=employee-dashboard');
        exit;
    }

    public static function disableAccessory() {
        requireAuth();
        requireRole('employee');
        $id = intval($_GET['id'] ?? 0);
        Accessory::updateStatus($id, 'disabled');
        Log::add('accessory_disabled', ['accessory_id' => $id]);
        header('Location: /index.php?action=employee-dashboard');
        exit;
    }

    public static function deleteCharacter() {
        requireAuth();
        requireRole('employee');
        $id = intval($_GET['id'] ?? 0);
        Character::delete($id);
        Log::add('character_deleted_by_employee', ['character_id' => $id]);
        header('Location: /index.php?action=employee-dashboard');
        exit;
    }

    public static function suspendUser() {
        requireAuth();
        requireRole('employee');
        $id = intval($_GET['id'] ?? 0);
        User::updateStatus($id, 'suspended');
        Log::add('user_suspended', ['user_id' => $id]);
        header('Location: /index.php?action=employee-dashboard');
        exit;
    }
}
