# Tests unitaires — M-Motors

Les tests vérifient les fonctions utilitaires de `toolbox/tools.php`
(`e`, `preview`, `vehicleType`, `appStatus`) avec **PHPUnit**.

## Prérequis

- PHP **8.1+** (vérifie avec `php -v` ; XAMPP récent est en 8.2, c'est bon).
- **Composer** (gestionnaire de dépendances PHP).
  S'il n'est pas installé : https://getcomposer.org/download/

> Astuce Windows : ajoute le dossier de PHP de XAMPP au PATH
> (ex. `C:\xampp\php`) pour pouvoir lancer `php` et `composer` depuis n'importe où.

## Installation (une seule fois)

À la racine du projet :

```bash
composer install
```

Cela télécharge PHPUnit dans `vendor/`.

## Lancer les tests

```bash
composer exec phpunit
```

ou directement :

```bash
vendor/bin/phpunit
```

Sortie attendue : tous les tests au vert (`OK`).

## Détail

- `tests/ToolsTest.php` — un test par fonction utilitaire (`e`, `preview`,
  `vehicleType`, `appStatus`), avec quelques cas limites.
- `tests/ValidatorsTest.php` — tests des **formulaires** : inscription, connexion,
  ajout/édition de véhicule, dépôt de dossier, et helpers d'upload (image, PDF).
  Ces tests portent sur `toolbox/validators.php`, où la logique de validation des
  formulaires a été extraite pour être testable sans base de données.
- `phpunit.xml` — configuration (dossier `tests/`, bootstrap Composer).

## En cas de souci

- *PHP trop ancien* : si tu as PHP 8.0 ou moins, remplace dans `composer.json`
  `"phpunit/phpunit": "^10"` par `"^9"`, puis relance `composer install`.
- *Pas de Composer* : tu peux aussi télécharger `phpunit.phar` depuis
  https://phpunit.de/ et lancer `php phpunit.phar tests`.
