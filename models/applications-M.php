<?php

require_once __DIR__ . '/../config/database.php';

// tous les dossiers + le nom du véhicule et de l'utilisateur (pour le dashboard admin)
function getAllApplications() {
    global $pdo;

    $sql = "SELECT
            a.id,
            a.name,
            a.email,
            a.document,
            a.status,
            a.created_at,
            CONCAT(v.brand, ' ', v.model) AS vehicle_name,
            v.type,
            v.price,
            u.name AS user_name
        FROM applications a
        JOIN vehicles v ON a.vehicle_id = v.id
        JOIN users u ON a.user_id = u.id
        ORDER BY a.created_at DESC";

    $stmt = $pdo->query($sql);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// un dossier par son id
function getApplicationById($id) {
    global $pdo;

    $sql = "SELECT * FROM applications WHERE id = :id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => (int)$id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// change le statut d'un dossier (en attente / accepté / refusé)
function updateApplicationStatus($id, $status) {
    global $pdo;

    $sql = "UPDATE applications SET status = :status WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        'status' => $status,
        'id' => (int)$id,
    ]);
}

// supprime un dossier
function deleteApplication($id) {
    global $pdo;

    $sql = "DELETE FROM applications WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    return $stmt->execute(['id' => (int)$id]);
}
