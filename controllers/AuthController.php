<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Log.php';

class AuthController {
    public static function login() {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
                $error = 'Token CSRF invalide.';
            } else {
                $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
                $password = $_POST['password'] ?? '';
                $user = User::findByEmail($email);

                if ($user && password_verify($password, $user['password_hash'])) {
                    if ($user['status'] !== 'active') {
                        $error = 'Votre compte est suspendu ou supprimé.';
                    } else {
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['pseudo'] = $user['pseudo'];
                        $_SESSION['role'] = $user['role'];
                        Log::add('login', ['email' => $email]);
                        header('Location: /index.php?action=dashboard');
                        exit;
                    }
                } else {
                    $error = 'Email ou mot de passe incorrect.';
                }
            }
        }

        $pageTitle = 'Connexion';
        require __DIR__ . '/../views/auth/login.php';
    }

    public static function register() {
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
                $error = 'Token CSRF invalide.';
            } else {
                $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
                $pseudo = htmlspecialchars(trim($_POST['pseudo'] ?? ''));
                $password = $_POST['password'] ?? '';

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = 'Adresse email invalide.';
                } elseif (strlen($pseudo) < 3) {
                    $error = 'Le pseudo doit contenir au moins 3 caractères.';
                } elseif (!self::isPasswordSecure($password)) {
                    $error = 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.';
                } elseif (User::findByEmail($email)) {
                    $error = 'Cet email est déjà utilisé.';
                } elseif (User::findByPseudo($pseudo)) {
                    $error = 'Ce pseudo est déjà utilisé.';
                } else {
                    User::create($email, $pseudo, $password);
                    Log::add('register', ['email' => $email, 'pseudo' => $pseudo]);
                    $success = 'Compte créé avec succès ! Vous pouvez maintenant vous connecter.';
                }
            }
        }

        $pageTitle = 'Créer un compte';
        require __DIR__ . '/../views/auth/register.php';
    }

    public static function logout() {
        Log::add('logout', ['user_id' => $_SESSION['user_id'] ?? null]);
        session_destroy();
        header('Location: /index.php?action=home');
        exit;
    }

    public static function forgotPassword() {
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
                $error = 'Token CSRF invalide.';
            } else {
                $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
                $pseudo = htmlspecialchars(trim($_POST['pseudo'] ?? ''));
                $user = User::findByEmail($email);

                if (!$user || $user['pseudo'] !== $pseudo) {
                    $error = 'Email ou pseudo incorrect.';
                } else {
                    $newPassword = self::generateSecurePassword();
                    User::updatePassword($email, $newPassword);
                    $subject = 'Réinitialisation de votre mot de passe PixelVerse';
                    $message = "Bonjour,\n\nVotre nouveau mot de passe temporaire est : $newPassword\n\nVous devrez le modifier à votre prochaine connexion.\n\nPixelVerse Studios";
                    require_once __DIR__ . '/../helpers/MailHelper.php';
                    MailHelper::send($email, $subject, $message);
                    Log::add('password_reset', ['email' => $email]);
                    $success = 'Un nouveau mot de passe a été envoyé à votre adresse email.';
                }
            }
        }

        $pageTitle = 'Mot de passe oublié';
        require __DIR__ . '/../views/auth/forgot_password.php';
    }

    private static function isPasswordSecure($password) {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{8,}$/', $password);
    }

    private static function generateSecurePassword() {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        $password = '';
        for ($i = 0; $i < 12; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $password;
    }
}
