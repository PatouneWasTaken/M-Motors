<?php

require_once __DIR__ . '/../config/database.php';

// récupère un utilisateur (sans le mot de passe)
function getUserById($id) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT id, name, email, is_admin FROM users WHERE id = ?");
    $stmt->execute([(int)$id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// récupère le hash du mot de passe (pour le vérifier)
function getUserPasswordHash($id) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([(int)$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? $row['password'] : null;
}

// vrai si l'email est déjà utilisé par un autre utilisateur
function emailTakenByOther($email, $userId) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->execute([$email, (int)$userId]);

    return (bool) $stmt->fetch();
}

// met à jour le nom et l'email
function updateUserProfile($id, $name, $email) {
    global $pdo;

    $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");

    return $stmt->execute([
        'name'  => $name,
        'email' => $email,
        'id'    => (int)$id,
    ]);
}

// met à jour le mot de passe
function updateUserPassword($id, $hash) {
    global $pdo;

    $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");

    return $stmt->execute([
        'password' => $hash,
        'id'       => (int)$id,
    ]);
}
