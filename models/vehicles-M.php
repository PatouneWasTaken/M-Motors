<?php

require_once __DIR__ . '/../config/database.php';

function getVehicles($type = null, $min = null, $max = null, $brand = null, $page = 1, $limit = null) {
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

    if ($brand) {
        $sql .= " AND brand = :brand";
        $params['brand'] = $brand;
    }

    $sql .= " ORDER BY id DESC";

    if ($limit !== null) {
        $offset = ($page - 1) * $limit;
        $sql .= " LIMIT :limit OFFSET :offset";
    }

    $stmt = $pdo->prepare($sql);

    foreach ($params as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }

    if ($limit !== null) {
        $stmt->bindValue(":limit", (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(":offset", (int)$offset, PDO::PARAM_INT);
    }

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getVehicleById($id) {
    global $pdo;

    $sql = "SELECT * FROM vehicles WHERE id = :id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getBrands() {
    global $pdo;

    $sql = "SELECT DISTINCT brand FROM vehicles ORDER BY brand ASC";
    $stmt = $pdo->query($sql);

    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function countVehicles($type = null, $min = null, $max = null, $brand = null) {
    global $pdo;

    $sql = "SELECT COUNT(*) FROM vehicles WHERE 1=1";
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

    if ($brand) {
        $sql .= " AND brand = :brand";
        $params['brand'] = $brand;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchColumn();
}