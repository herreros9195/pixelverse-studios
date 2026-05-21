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
                    if (($user['status'] ?? 'active') !== 'active') {
                        $error = 'Le compte est suspendu ou supprime.';
                    } else {
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['pseudo'] = $user['pseudo'];
                        $_SESSION['role'] = $user['role'];

                        Log::add('login', ['email' => $email, 'role' => $user['role']]);
                        header('Location: /index.php?action=' . self::dashboardActionForRole($user['role']));
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
                    $error = 'Le pseudo doit contenir au moins 3 caracteres.';
                } elseif (!self::isPasswordSecure($password)) {
                    $error = 'Le mot de passe doit contenir au moins 8 caracteres, une majuscule, une minuscule, un chiffre et un caractere special.';
                } elseif (User::findByEmail($email)) {
                    $error = 'Cet email est deja utilise.';
                } elseif (User::findByPseudo($pseudo)) {
                    $error = 'Ce pseudo est deja utilise.';
                } else {
                    User::create($email, $pseudo, $password);
                    Log::add('register', ['email' => $email, 'pseudo' => $pseudo]);
                    $success = 'Compte cree avec succes. La connexion est maintenant disponible.';
                }
            }
        }

        $pageTitle = 'Creer un compte';
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
                    $token = bin2hex(random_bytes(32));
                    User::setPasswordResetToken($user['id'], $token);

                    $resetUrl = self::buildAbsoluteUrl('index.php?action=reset-password&id=' . $user['id'] . '&token=' . urlencode($token));
                    $subject = 'Renouvellement du mot de passe PixelVerse';
                    $message = "Bonjour,\n\nUtiliser ce lien pour definir un nouveau mot de passe :\n{$resetUrl}\n\nCe lien expire dans 1 heure.\n\nPixelVerse Studios";

                    require_once __DIR__ . '/../helpers/MailHelper.php';
                    $mailSent = MailHelper::send($email, $subject, $message);
                    Log::add('password_reset_requested', ['email' => $email, 'mail_sent' => $mailSent]);

                    if ($mailSent) {
                        $success = 'Un lien de renouvellement a ete envoye par email.';
                    } else {
                        $error = 'Le lien de renouvellement a ete genere, mais l\'envoi du mail a echoue sur cette machine.';
                    }
                }
            }
        }

        $pageTitle = 'Mot de passe oublie';
        require __DIR__ . '/../views/auth/forgot_password.php';
    }

    public static function resetPassword() {
        $error = '';
        $success = '';
        $userId = intval($_GET['id'] ?? $_POST['id'] ?? 0);
        $token = trim($_GET['token'] ?? $_POST['token'] ?? '');
        $user = $userId > 0 && $token !== '' ? User::findByResetToken($userId, $token) : false;

        if (!$user) {
            $error = 'Le lien de renouvellement est invalide ou expire.';
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
                $error = 'Token CSRF invalide.';
            } else {
                $password = $_POST['password'] ?? '';
                $passwordConfirm = $_POST['password_confirm'] ?? '';

                if (!self::isPasswordSecure($password)) {
                    $error = 'Le mot de passe doit contenir au moins 8 caracteres, une majuscule, une minuscule, un chiffre et un caractere special.';
                } elseif ($password !== $passwordConfirm) {
                    $error = 'La confirmation du mot de passe ne correspond pas.';
                } else {
                    User::updatePasswordById($user['id'], $password);
                    User::clearPasswordResetToken($user['id']);
                    Log::add('password_reset_completed', ['user_id' => $user['id']]);
                    $success = 'Mot de passe renouvele avec succes. La connexion est de nouveau disponible.';
                    $user = false;
                }
            }
        }

        $pageTitle = 'Renouveler le mot de passe';
        require __DIR__ . '/../views/auth/reset_password.php';
    }

    private static function dashboardActionForRole($role) {
        if ($role === 'admin') {
            return 'admin-dashboard';
        }
        if ($role === 'employee') {
            return 'employee-dashboard';
        }
        return 'dashboard';
    }

    private static function buildAbsoluteUrl($path) {
        $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
        $scheme = $https ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php')), '/');
        $basePath = $basePath === '' ? '' : $basePath;
        return $scheme . '://' . $host . $basePath . '/' . ltrim($path, '/');
    }

    private static function isPasswordSecure($password) {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{8,}$/', $password) === 1;
    }
}
