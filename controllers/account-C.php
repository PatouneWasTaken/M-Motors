<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/users-M.php';
require_once __DIR__ . '/../models/my-applications-M.php';
require_once __DIR__ . '/../toolbox/validators.php';

class AccountController {

    private function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /M-Motors/public/index.php?page=login");
            exit;
        }
    }

    private function back() {
        header("Location: /M-Motors/public/index.php?page=account");
        exit;
    }

    // Affichage de la page "Mon compte"
    public function show() {
        $this->requireLogin();

        $user = getUserById($_SESSION['user_id']);
        $applications = getUserApplications($_SESSION['user_id']);

        require __DIR__ . '/../views/account-V.php';
    }

    // Mise à jour du nom et de l'email
    public function updateProfile() {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->back();
        }

        $id    = (int) $_SESSION['user_id'];
        $name  = trim($_POST['name'] ?? '');
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);

        if ($name === '' || !$email) {
            $_SESSION['account_error'] = "Un nom et un email valides sont requis";
            $this->back();
        }

        if (emailTakenByOther($email, $id)) {
            $_SESSION['account_error'] = "Cet email est déjà utilisé";
            $this->back();
        }

        try {
            updateUserProfile($id, $name, $email);
            $_SESSION['user_name'] = $name;
            $_SESSION['account_success'] = "Informations mises à jour";
        } catch (PDOException $e) {
            $_SESSION['account_error'] = "Mise à jour impossible (nom déjà utilisé ?)";
        }

        $this->back();
    }

    // Changement de mot de passe
    public function updatePassword() {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->back();
        }

        $id      = (int) $_SESSION['user_id'];
        $current = $_POST['current_password'] ?? '';
        $new     = $_POST['new_password'] ?? '';

        $hash = getUserPasswordHash($id);

        if (!$hash || !password_verify($current, $hash)) {
            $_SESSION['account_error'] = "Mot de passe actuel incorrect";
            $this->back();
        }

        if (!isStrongPassword($new)) {
            $_SESSION['account_error'] = "Le nouveau mot de passe doit contenir au moins 8 caractères, une lettre et un chiffre.";
            $this->back();
        }

        updateUserPassword($id, password_hash($new, PASSWORD_DEFAULT));
        $_SESSION['account_success'] = "Mot de passe modifié";

        $this->back();
    }
}
