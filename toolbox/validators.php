<?php

// Fonctions de validation. On reçoit les données et on renvoie la liste des erreurs.
// Si le tableau renvoyé est vide, c'est que tout est bon.

function validateRegistration(array $data): array {
    $errors = [];

    $firstname = trim($data['firstname'] ?? '');
    $lastname  = trim($data['lastname'] ?? '');
    $email     = trim($data['email'] ?? '');
    $password  = $data['password'] ?? '';

    if ($firstname === '' || $lastname === '' || $password === '') {
        $errors[] = "Tous les champs sont obligatoires";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email invalide";
    }

    // mot de passe : 8 caractères min, au moins une lettre et un chiffre
    if (
        strlen($password) < 8 ||
        !preg_match('/[A-Za-z]/', $password) ||
        !preg_match('/[0-9]/', $password)
    ) {
        $errors[] = "Le mot de passe doit contenir au moins 8 caractères, une lettre et un chiffre.";
    }

    return $errors;
}

function validateLogin(array $data): array {
    $errors = [];

    $email    = trim($data['email'] ?? '');
    $password = $data['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email invalide";
    }

    if ($password === '') {
        $errors[] = "Tous les champs sont obligatoires";
    }

    return $errors;
}

function validateVehicle(array $data): array {
    $errors = [];

    $brand = trim($data['brand'] ?? '');
    $model = trim($data['model'] ?? '');
    $type  = $data['type'] ?? '';
    $price = $data['price'] ?? '';

    if ($brand === '' || $model === '') {
        $errors[] = "Marque et modèle obligatoires";
    }

    // seulement deux types possibles
    if (!in_array($type, ['sale', 'rent'], true)) {
        $errors[] = "Type invalide";
    }

    if (!is_numeric($price) || (int)$price < 0) {
        $errors[] = "Prix invalide";
    }

    $kms = $data['kms'] ?? '';
    if (!is_numeric($kms) || (int)$kms < 0) {
        $errors[] = "Kilométrage invalide";
    }

    return $errors;
}

function validateApplication(array $data): array {
    $errors = [];

    $vehicleId = (int) ($data['vehicle_id'] ?? 0);
    $name      = trim($data['name'] ?? '');
    $email     = trim($data['email'] ?? '');

    if ($vehicleId <= 0) {
        $errors[] = "Véhicule invalide";
    }

    if ($name === '') {
        $errors[] = "Nom obligatoire";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email invalide";
    }

    return $errors;
}

// vérifie une image : extension autorisée + taille (2 Mo max par défaut)
function isAllowedImage(string $filename, int $size, int $maxSize = 2097152): bool {
    if ($size <= 0 || $size > $maxSize) {
        return false;
    }

    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    return in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true);
}

// vrai si la taille est entre 1 octet et $max
function isWithinMaxSize(int $size, int $max): bool {
    return $size > 0 && $size <= $max;
}

// vrai si le fichier est bien un PDF
function isPdfMimeType(string $mimeType): bool {
    return $mimeType === 'application/pdf';
}

// vrai si le mot de passe fait 8+ caractères avec au moins une lettre et un chiffre
function isStrongPassword(string $password): bool {
    return strlen($password) >= 8
        && preg_match('/[A-Za-z]/', $password)
        && preg_match('/[0-9]/', $password);
}
