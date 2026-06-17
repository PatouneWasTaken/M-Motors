<?php
session_start();
require __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../toolbox/validators.php';

// En cas d'erreur : on mémorise le message + l'email saisi, et on revient au formulaire
function loginFail($message, $email = '') {
    $_SESSION['login_error'] = $message;
    $_SESSION['login_old'] = ['email' => $email];
    header("Location: /M-Motors/public/index.php?page=login");
    exit;
}

$errors = validateLogin($_POST);
if ($errors) {
    loginFail($errors[0], trim($_POST['email'] ?? ''));
}

$email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
$password = $_POST['password'];

try {

    $stmt = $pdo->prepare("SELECT id, name, password, is_admin FROM users WHERE email = ?");
    $stmt->execute([$email]);

    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
		$_SESSION['admin'] = $user['is_admin'];

        session_regenerate_id(true);

        header("Location: /M-Motors/public/index.php?page=dashboard");
        exit;

    } else {
        loginFail("Email ou mot de passe incorrect", $email);
    }

} catch (PDOException $e) {
    loginFail("Erreur lors de la connexion");
}
