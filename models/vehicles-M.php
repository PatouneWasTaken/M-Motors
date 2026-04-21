<?php

require_once __DIR__ . '/../config/database.php';

function getVehicles($type = null) {
    global $pdo;

    if (in_array($type, ['vente', 'location'])) {

        $isForSale = ($type === 'vente') ? 1 : 0;

        $sql = "SELECT * FROM vehicles 
                WHERE is_for_sale = :is_for_sale 
                ORDER BY id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['is_for_sale' => $isForSale]);

    } else {

        $sql = "SELECT * FROM vehicles ORDER BY id DESC";
        $stmt = $pdo->query($sql);
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getVehicleById($id) {
    global $pdo;

    $sql = "SELECT * FROM vehicles WHERE id = :id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}