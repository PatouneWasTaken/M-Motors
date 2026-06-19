<?php

/*
 * Bootstrap des tests.
 *
 * On définit une connexion PDO AVANT de charger les modèles : grâce à la garde
 * placée en tête de config/database.php, cela empêche toute connexion à MySQL
 * pendant les tests. Chaque test remplacera ensuite $GLOBALS['pdo'] par sa
 * propre base SQLite en mémoire (voir DatabaseTestCase).
 */

require_once __DIR__ . '/../vendor/autoload.php';

$GLOBALS['pdo'] = new PDO('sqlite::memory:');

require_once __DIR__ . '/../models/vehicles-M.php';
require_once __DIR__ . '/../models/users-M.php';
require_once __DIR__ . '/../models/applications-M.php';
