<?php

/*
 * Connexion à la base de données (PDO).
 *
 * --- PRODUCTION ---
 * Ne jamais laisser d'identifiants en clair dans le code versionné.
 * Définissez plutôt ces variables d'environnement sur le serveur :
 *     DB_HOST, DB_NAME, DB_USER, DB_PASS
 *     APP_ENV=production
 *
 * Selon l'hébergeur, on les définit via le panneau d'administration,
 * un fichier .env, ou dans la configuration Apache (ex. SetEnv DB_USER ...).
 *
 * --- DÉVELOPPEMENT LOCAL (XAMPP) ---
 * Si ces variables ne sont pas définies, on retombe sur les valeurs
 * par défaut ci-dessous. En local, ne pas définir APP_ENV (ou la mettre
 * à "development") pour voir le détail des erreurs.
 */

$host     = getenv('DB_HOST') ?: 'localhost';
$dbname   = getenv('DB_NAME') ?: 'MotorsDB';
$user     = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';

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