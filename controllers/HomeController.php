<?php
require_once __DIR__ . '/../models/Contact.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Log.php';

class HomeController {
    public static function index() {
        $pageTitle = 'Accueil - PixelVerse Studios';
        require __DIR__ . '/../views/home/index.php';
    }

    public static function contact() {
        $error = '';
        $success = '';

        if (isPostRequest()) {
            if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
                $error = 'Token CSRF invalide.';
            } else {
                $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
                $pseudo = htmlspecialchars(trim($_POST['pseudo'] ?? ''));
                $message = htmlspecialchars(trim($_POST['message'] ?? ''));

                if (empty($email) || empty($pseudo) || empty($message)) {
                    $error = 'Tous les champs sont obligatoires.';
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = 'Adresse email invalide.';
                } elseif (!User::findByPseudo($pseudo)) {
                    $error = 'Le pseudo indique ne correspond a aucun compte.';
                } else {
                    Contact::create($email, $pseudo, $message);
                    require_once __DIR__ . '/../helpers/MailHelper.php';
                    MailHelper::send('contact@pixelverse.com', 'Nouvelle demande de contact de ' . $pseudo, $message, $email);
                    Log::add('contact_request', ['email' => $email, 'pseudo' => $pseudo]);
                    $success = 'Message envoye avec succes.';
                }
            }
        }

        $pageTitle = 'Contact - PixelVerse Studios';
        require __DIR__ . '/../views/home/contact.php';
    }

    public static function legal() {
        $pageTitle = 'Mentions Legales - PixelVerse Studios';
        require __DIR__ . '/../views/home/legal.php';
    }

    public static function privacy() {
        $pageTitle = 'Confidentialite - PixelVerse Studios';
        require __DIR__ . '/../views/home/privacy.php';
    }

    public static function cgv() {
        $pageTitle = 'CGV - PixelVerse Studios';
        require __DIR__ . '/../views/home/cgv.php';
    }
}
