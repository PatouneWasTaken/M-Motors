<?php

// si une connexion est déjà fournie (par les tests), on ne se reconnecte pas
if (isset($GLOBALS['pdo']) && $GLOBALS['pdo'] instanceof PDO) {
    return;
}

$host     = getenv('DB_HOST') ?: 'localhost';
$dbname   = getenv('DB_NAME') ?: 'MotorsDB';
$user     = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';

// true en prod -> on masque le détail des erreurs
$isProduction = getenv('APP_ENV') === 'production';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // remonter les erreurs en exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // tableaux associatifs par défaut
    PDO::ATTR_EMULATE_PREPARES   => false,                 // vraies requêtes préparées (sécurité)
];

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $password,
        $options
    );
} catch (PDOException $e) {
    // Le détail technique est journalisé côté serveur, jamais affiché à l'utilisateur.
    error_log('Erreur de connexion BDD : ' . $e->getMessage());

    http_response_code(500);

    if ($isProduction) {
        // Message générique en production : on ne divulgue aucune information sensible.
        die('Le service est momentanément indisponible. Veuillez réessayer plus tard.');
    }

    // En développement uniquement : message détaillé pour faciliter le débogage.
    die('Erreur de connexion à la base : ' . $e->getMessage());
}