# M-Motors

Site de vente et de location de véhicules d'occasion. Projet d'examen (Studi / HETIC — Bloc 3, spécialité Web).

Le site permet de parcourir un catalogue de véhicules (à la vente ou à la location), de filtrer la recherche, de se créer un compte et de déposer un dossier (achat ou location) accompagné d'un document PDF. Un espace d'administration permet de gérer les véhicules et de suivre / valider les dossiers.

## Fonctionnalités

- Catalogue avec filtres (type, marque, prix) et chargement dynamique (AJAX).
- Fiche détaillée par véhicule.
- Comptes utilisateurs : inscription, connexion, page « Mon compte ».
- Dépôt de dossier (achat ou location) avec upload d'un PDF, stocké **hors du dossier public**.
- Espace admin : ajout / édition / suppression de véhicules (dont le type vente / location), visualisation et validation des dossiers.

## Stack technique

- **PHP 8.2**, architecture MVC maison (sans framework).
- **MySQL** via **PDO**.
- **JavaScript natif** (fetch / AJAX) pour les filtres et le back-office.
- **CSS** maison.
- **PHPUnit** pour les tests.

## Structure du projet

```
config/       connexion à la base
controllers/  contrôleurs (logique des pages)
models/       accès à la base (requêtes)
views/        templates HTML/PHP
toolbox/      fonctions utilitaires + validation
public/       racine web (index.php = routeur, assets, uploads)
storage/      dossiers PDF déposés (hors racine web)
tests/        tests unitaires
```

## Installation en local

### 1. Récupérer le projet et la base

- Place le projet dans ton serveur local (ex. `C:\xampp\htdocs\M-Motors`).
- Importe `motorsdb.sql` dans phpMyAdmin (crée la base `motorsdb`).

### 2. Configurer la connexion

`config/database.php` lit des variables d'environnement, avec des valeurs par défaut pour le local (`localhost`, base `motorsdb`, user `root`, mot de passe vide). Adapte-les si besoin.

### 3. Servir le site à la racine

La racine web du projet est le dossier `public/`. Le plus simple est de créer un virtual host pour que le site soit servi à la racine, comme en production.

`C:\xampp\apache\conf\extra\httpd-vhosts.conf` :
```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/M-Motors/public"
    ServerName m-motors.local
    <Directory "C:/xampp/htdocs/M-Motors/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

`C:\Windows\System32\drivers\etc\hosts` (à éditer en administrateur) :
```
127.0.0.1 m-motors.local
```

Redémarre Apache, puis ouvre **http://m-motors.local/**.

### 4. Créer un compte admin

Inscris-toi via le site, puis passe ton compte en admin dans la base :
```sql
UPDATE users SET is_admin = 1 WHERE email = 'ton@email.com';
```

## Tests

Les tests couvrent la **couche logique** du projet avec **PHPUnit** :

- les fonctions utilitaires de `toolbox/tools.php` (`e`, `preview`, `vehicleType`, `appStatus`) ;
- la validation des formulaires dans `toolbox/validators.php` (inscription, connexion, véhicule, dépôt de dossier, helpers d'upload image / PDF) ;
- les modèles `models/` (véhicules, utilisateurs, dossiers), testés sur une base **SQLite en mémoire** : aucune config, et la vraie base MySQL n'est jamais touchée.

### Prérequis

- PHP **8.1+** (`php -v` ; XAMPP récent est en 8.2, c'est bon).
- **Composer** — sinon : https://getcomposer.org/download/

> Astuce Windows : ajoute `C:\xampp\php` au PATH pour lancer `php` et `composer` depuis n'importe où.

### Installation (une seule fois)

À la racine du projet :

```bash
composer install
```

Cela télécharge PHPUnit dans `vendor/`.

### Lancer les tests

```bash
vendor/bin/phpunit
```

Sortie attendue : tous les tests au vert (`OK`).

### Mesurer la couverture

La couverture se mesure avec **Xdebug** en mode `coverage`. Active-le dans le `php.ini` :

```ini
xdebug.mode=coverage
```

puis :

```bash
vendor/bin/phpunit --coverage-text
```

Pour un rapport HTML détaillé : `vendor/bin/phpunit --coverage-html coverage` (puis ouvre `coverage/index.html`).

> La couverture est calculée sur `models/` et `toolbox/`, c'est-à-dire la logique testable unitairement. Les vues sont des templates et les contrôleurs relèvent de tests d'intégration. Couverture actuelle : **~92 % des lignes**.

### Détail des fichiers de test

- `tests/ToolsTest.php` — un test par fonction utilitaire, avec quelques cas limites.
- `tests/ValidatorsTest.php` — tests des formulaires et des helpers d'upload.
- `tests/VehiclesModelTest.php`, `UsersModelTest.php`, `ApplicationsModelTest.php` — tests des modèles sur SQLite en mémoire.
- `tests/DatabaseTestCase.php` — base commune : crée une base de test neuve avant chaque test.
- `phpunit.xml` — configuration (dossier `tests/`, bootstrap, périmètre de couverture).

### En cas de souci

- *PHP trop ancien* : si tu as PHP 8.0 ou moins, remplace dans `composer.json` `"phpunit/phpunit": "^10"` par `"^9"`, puis relance `composer install`.
- *« No code coverage driver available »* : Xdebug n'est pas en mode coverage (voir plus haut).

## Déploiement

Le site est déployé sur OVHcloud. La branche `main` sert au développement et la branche `prod` correspond à la version déployée. En production, la racine web pointe sur `public/` et les identifiants de base de données viennent des variables d'environnement (jamais dans le code).