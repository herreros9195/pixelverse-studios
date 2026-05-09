<?php
require_once __DIR__ . '/../models/Contact.php';

class HomeController {
    public static function index() {
        $pageTitle = 'Accueil - PixelVerse Studios';
        require __DIR__ . '/../views/home/index.php';
    }

    public static function contact() {
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
                    $error = 'Le pseudo mentionné n\'existe pas dans notre base.';
                } else {
                    Contact::create($email, $pseudo, $message);
                    $to = 'contact@pixelverse.com';
                    $subject = 'Nouvelle demande de contact de ' . $pseudo;
                    $headers = "From: " . $email . "\r\nContent-Type: text/plain; charset=utf-8";
                    require_once __DIR__ . '/../helpers/MailHelper.php';
                    MailHelper::send($to, $subject, $message);
                    $success = 'Votre message a bien été envoyé.';
                }
            }
        }

        $pageTitle = 'Contact - PixelVerse Studios';
        require __DIR__ . '/../views/home/contact.php';
    }

    public static function legal() {
        $pageTitle = 'Mentions Légales - PixelVerse Studios';
        require __DIR__ . '/../views/home/legal.php';
    }

    public static function cgv() {
        $pageTitle = 'CGV - PixelVerse Studios';
        require __DIR__ . '/../views/home/cgv.php';
    }
}
