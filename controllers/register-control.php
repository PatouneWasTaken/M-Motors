<?php
require __DIR__ . '/../config/database.php';

if (!isset($_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['password'])) {
    die("Formulaire incomplet");
}

$firstname = trim($_POST['firstname']);
$lastname = trim($_POST['lastname']);
$name = $firstname . " " . $lastname;

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
if (!$email) {
    die("Email invalide");
}

$password = trim($_POST['password']);

if (empty($firstname) || empty($lastname) || empty($password)) {
    die("Tous les champs sont obligatoires");
}

if (
    strlen($password) < 8 ||
    !preg_match('/[A-Za-z]/', $password) ||
    !preg_match('/[0-9]/', $password)
) {
    die("Le mot de passe doit contenir au moins 8 caractères, une lettre et un chiffre.");
}

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
	//die("Erreur lors de l’inscription");
}