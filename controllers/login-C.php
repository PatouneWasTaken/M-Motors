<?php
session_start();
require __DIR__ . '/../config/database.php';

if (!isset($_POST['email'], $_POST['password'])) {
    die("Tous les champs sont obligatoires");
}

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
if (!$email) {
    die("Email invalide");
}

$password = $_POST['password'];

if (empty($password)) {
    die("Tous les champs sont obligatoires");
}

try {

    $stmt = $pdo->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->execute([$email]);

    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

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