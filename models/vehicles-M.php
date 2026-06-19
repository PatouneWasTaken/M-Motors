<?php

require_once __DIR__ . '/../config/database.php';

// récupère les véhicules, avec filtres optionnels (type/prix/marque) et pagination
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

// récupère un véhicule par son id
function getVehicleById($id) {
    global $pdo;

    $sql = "SELECT * FROM vehicles WHERE id = :id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// liste des marques (pour le menu des filtres)
function getBrands() {
    global $pdo;

    $sql = "SELECT DISTINCT brand FROM vehicles ORDER BY brand ASC";
    $stmt = $pdo->query($sql);

    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// compte les véhicules (pour calculer le nombre de pages)
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

// met à jour un véhicule
function updateVehicle($id, $brand, $model, $type, $price, $description, $kms, $photo = null) {
    global $pdo;

    // si une nouvelle photo est fournie on la met à jour, sinon on garde l'ancienne
    if ($photo !== null) {
        $sql = "UPDATE vehicles
                SET brand = :brand, model = :model, type = :type,
                    price = :price, description = :description, kms = :kms, photo = :photo
                WHERE id = :id";
        $params = [
            'brand' => $brand,
            'model' => $model,
            'type' => $type,
            'price' => (int)$price,
            'description' => $description,
            'kms' => (int)$kms,
            'photo' => $photo,
            'id' => (int)$id,
        ];
    } else {
        $sql = "UPDATE vehicles
                SET brand = :brand, model = :model, type = :type,
                    price = :price, description = :description, kms = :kms
                WHERE id = :id";
        $params = [
            'brand' => $brand,
            'model' => $model,
            'type' => $type,
            'price' => (int)$price,
            'description' => $description,
            'kms' => (int)$kms,
            'id' => (int)$id,
        ];
    }

    $stmt = $pdo->prepare($sql);
    return $stmt->execute($params);
}

// supprime un véhicule
function deleteVehicle($id) {
    global $pdo;

    $sql = "DELETE FROM vehicles WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute(['id' => (int)$id]);
}
