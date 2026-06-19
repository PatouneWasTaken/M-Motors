<?php

use PHPUnit\Framework\TestCase;

/*
 * Classe de base pour les tests de modèles.
 *
 * Avant CHAQUE test, on crée une base SQLite en mémoire neuve (isolation totale :
 * aucun test ne dépend d'un autre, et la vraie base MySQL n'est jamais touchée),
 * on y crée le schéma, et on l'expose via $GLOBALS['pdo'] — c'est cette connexion
 * qu'utilisent les fonctions des modèles (`global $pdo`).
 */
abstract class DatabaseTestCase extends TestCase
{
    protected PDO $pdo;

    /** Vrai si SQLite peut émuler la fonction CONCAT (pour getAllApplications). */
    protected bool $hasConcat = false;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        // CONCAT n'existe pas nativement en SQLite : on l'émule si possible.
        if (method_exists($this->pdo, 'sqliteCreateFunction')) {
            $this->pdo->sqliteCreateFunction('CONCAT', function (...$args) {
                return implode('', $args);
            });
            $this->hasConcat = true;
        }

        // Les modèles utilisent `global $pdo` : on pointe le global sur notre base de test.
        $GLOBALS['pdo'] = $this->pdo;

        $this->createSchema();
    }

    /** Crée un schéma SQLite équivalent à celui de MotorsDB. */
    private function createSchema(): void
    {
        $this->pdo->exec("
            CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT UNIQUE,
                email TEXT UNIQUE,
                password TEXT,
                is_admin INTEGER DEFAULT NULL,
                entry_date TEXT DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $this->pdo->exec("
            CREATE TABLE vehicles (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                brand TEXT,
                model TEXT,
                price INTEGER,
                description TEXT NOT NULL,
                photo TEXT NOT NULL,
                entry_date TEXT DEFAULT CURRENT_TIMESTAMP,
                entry_by INTEGER NOT NULL,
                kms INTEGER NOT NULL DEFAULT 0,
                type TEXT
            )
        ");

        $this->pdo->exec("
            CREATE TABLE applications (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER,
                vehicle_id INTEGER,
                name TEXT,
                email TEXT,
                document TEXT,
                status TEXT DEFAULT 'pending',
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    /* ----------------------- Helpers de seed ----------------------- */

    protected function seedUser(
        string $name = 'Jean',
        string $email = 'jean@example.com',
        string $password = 'hash',
        $isAdmin = null
    ): int {
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (name, email, password, is_admin)
             VALUES (:name, :email, :password, :is_admin)"
        );
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'is_admin' => $isAdmin,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    protected function seedVehicle(
        int $entryBy,
        string $brand = 'Renault',
        string $model = 'Clio',
        int $price = 10000,
        string $type = 'sale',
        int $kms = 50000,
        string $description = 'Bon état',
        string $photo = 'photo.png'
    ): int {
        $stmt = $this->pdo->prepare(
            "INSERT INTO vehicles (brand, model, price, description, photo, entry_by, kms, type)
             VALUES (:brand, :model, :price, :description, :photo, :entry_by, :kms, :type)"
        );
        $stmt->execute([
            'brand' => $brand,
            'model' => $model,
            'price' => $price,
            'description' => $description,
            'photo' => $photo,
            'entry_by' => $entryBy,
            'kms' => $kms,
            'type' => $type,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    protected function seedApplication(
        int $userId,
        int $vehicleId,
        string $name = 'Jean',
        string $email = 'jean@example.com',
        string $document = 'doc.pdf',
        string $status = 'pending',
        string $createdAt = '2024-01-01 10:00:00'
    ): int {
        $stmt = $this->pdo->prepare(
            "INSERT INTO applications (user_id, vehicle_id, name, email, document, status, created_at)
             VALUES (:user_id, :vehicle_id, :name, :email, :document, :status, :created_at)"
        );
        $stmt->execute([
            'user_id' => $userId,
            'vehicle_id' => $vehicleId,
            'name' => $name,
            'email' => $email,
            'document' => $document,
            'status' => $status,
            'created_at' => $createdAt,
        ]);

        return (int) $this->pdo->lastInsertId();
    }
}
