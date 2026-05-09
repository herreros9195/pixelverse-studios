<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Log.php';

class AdminController {
    public static function dashboard() {
        requireAuth();
        requireRole('admin');
        $users = User::getAll();
        $pageTitle = 'Espace Administrateur';
        require __DIR__ . '/../views/admin/dashboard.php';
    }

    public static function createEmployee() {
        requireAuth();
        requireRole('admin');
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
                $error = 'Token CSRF invalide.';
            } else {
                $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
                $password = $_POST['password'] ?? '';

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = 'Email invalide.';
                } elseif (User::findByEmail($email)) {
                    $error = 'Cet email est déjà utilisé.';
                } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{8,}$/', $password)) {
                    $error = 'Le mot de passe doit respecter les règles de sécurité.';
                } else {
                    User::createEmployee($email, $password);
                    Log::add('employee_created', ['email' => $email]);
                    $success = 'Compte employé créé avec succès.';
                }
            }
        }

        $pageTitle = 'Créer un employé';
        require __DIR__ . '/../views/admin/create_employee.php';
    }

    public static function logs() {
        requireAuth();
        requireRole('admin');
        $logs = Log::getAll();
        $pageTitle = 'Logs d\'activité';
        require __DIR__ . '/../views/admin/logs.php';
    }

    public static function manageEmployee() {
        requireAuth();
        requireRole('admin');
        $id = intval($_GET['id'] ?? 0);
        $actionType = $_GET['type'] ?? '';

        $employee = User::findById($id);
        if ($employee && $employee['role'] === 'employee') {
            if ($actionType === 'suspend') {
                User::updateStatus($id, 'suspended');
                Log::add('employee_suspended', ['employee_id' => $id]);
            } elseif ($actionType === 'activate') {
                User::updateStatus($id, 'active');
                Log::add('employee_activated', ['employee_id' => $id]);
            } elseif ($actionType === 'delete') {
                User::delete($id);
                Log::add('employee_deleted', ['employee_id' => $id]);
            } elseif ($actionType === 'password') {
                $newPassword = bin2hex(random_bytes(8)) . 'A1!';
                User::updatePassword($employee['email'], $newPassword);
                require_once __DIR__ . '/../helpers/MailHelper.php';
                MailHelper::send($employee['email'], 'Nouveau mot de passe', "Votre nouveau mot de passe est : $newPassword");
                Log::add('employee_password_reset', ['employee_id' => $id]);
            }
        }
        header('Location: /index.php?action=admin-dashboard');
        exit;
    }
}
