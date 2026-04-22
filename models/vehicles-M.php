<?php

require_once __DIR__ . '/../config/database.php';

function getVehicles($type = null, $min = null, $max = null) {
    global $pdo;

    $sql = "SELECT * FROM vehicles WHERE 1=1";
    $params = [];

    if ($type) {
        $sql .= " AND type = :type";
        $params['type'] = $type;
    }

    if ($min !== null) {
        $sql .= " AND price >= :min";
        $params['min'] = $min;
    }

    if ($max !== null) {
        $sql .= " AND price <= :max";
        $params['max'] = $max;
    }

    $sql .= " ORDER BY id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getVehicleById($id) {
    global $pdo;

    $sql = "SELECT * FROM vehicles WHERE id = :id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}