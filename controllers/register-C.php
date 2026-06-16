<?php
require __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../toolbox/validators.php';

$errors = validateRegistration($_POST);
if ($errors) {
    die($errors[0]);
}

$firstname = trim($_POST['firstname']);
$lastname = trim($_POST['lastname']);
$name = $firstname . " " . $lastname;

$email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
$password = $_POST['password'];

try {

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        die("Email déjà utilisé");
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
    die("Erreur : " . $e->getMessage()); //debug
	//die("Erreur lors de l'inscription");
}
