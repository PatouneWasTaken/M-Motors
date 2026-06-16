<?php
session_start();
require __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../toolbox/validators.php';

$errors = validateLogin($_POST);
if ($errors) {
    die($errors[0]);
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
        die("Email ou mot de passe incorrect");
    }

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage()); //debug
	//die("Erreur lors de la connexion");
}
