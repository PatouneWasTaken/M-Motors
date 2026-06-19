<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../toolbox/validators.php';

// Valeurs saisies, conservées pour ré-afficher le formulaire en cas d'erreur
$old = [
    'firstname' => trim($_POST['firstname'] ?? ''),
    'lastname'  => trim($_POST['lastname'] ?? ''),
    'email'     => trim($_POST['email'] ?? ''),
];

function registerFail($message, $old) {
    $_SESSION['register_error'] = $message;
    $_SESSION['register_old'] = $old;
    header("Location: /index.php?page=login");
    exit;
}

$errors = validateRegistration($_POST);
if ($errors) {
    registerFail($errors[0], $old);
}

$name = $old['firstname'] . " " . $old['lastname'];
$email = filter_var($old['email'], FILTER_VALIDATE_EMAIL);
$password = $_POST['password'];

try {

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        registerFail("Email déjà utilisé", $old);
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("
        INSERT INTO users (name, email, password)
        VALUES (?, ?, ?)
    ");

    $stmt->execute([$name, $email, $hash]);

    header("Location: ../public/index.php?page=login");
    exit;

} catch (PDOException $e) {
    registerFail("Erreur lors de l'inscription", $old);
}
