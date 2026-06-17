<?php

require_once __DIR__ . '/../models/vehicles-M.php';

class VehicleController {

    public function index() {

		$vehicles = [];
		$totalPages = 0;

        // Récupérer le filtre
		$type = $_GET['type'] ?? null;
		$min = $_GET['min'] ?? null;
		$max = $_GET['max'] ?? null;
		$brand = $_GET['brand'] ?? null;
		$page = 1;
		$limit = 10;

        if (!in_array($type, ['sale', 'rent'])) {
            $type = null;
        }

		$min = is_numeric($min) ? (int)$min : null;
		$max = is_numeric($max) ? (int)$max : null;

        // Récupérer les données
        $vehicles = getVehicles($type, $min, $max, $brand, $page, $limit);
    	$total = countVehicles($type, $min, $max, $brand);
    	$totalPages = ceil($total / $limit);
    	$brands = getBrands();

        // Charger la vue
        require __DIR__ . '/../views/vehicles-V.php';
    }

	public function asyncList() {

    	$type = $_GET['type'] ?? null;
    	$min = $_GET['min'] ?? null;
    	$max = $_GET['max'] ?? null;
    	$brand = $_GET['brand'] ?? null;
		$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
		$limit = 10;

    	if (!in_array($type, ['sale', 'rent'])) {
        	$type = null;
    	}

    	$min = is_numeric($min) ? (int)$min : null;
    	$max = is_numeric($max) ? (int)$max : null;

		// Récupérer les données
    	$vehicles = getVehicles($type, $min, $max, $brand, $page, $limit);
    	$total = countVehicles($type, $min, $max, $brand);
    	$totalPages = ceil($total / $limit);

    	// retourner HTML
    	require __DIR__ . '/../views/components/vehicles-list.php';
	}

	public function show() {

    	// vérifier que l'id existe
    	if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        	die("Véhicule invalide");
    	}

    	$id = (int) $_GET['id'];

    	// récupérer le véhicule
    	$vehicle = getVehicleById($id);

    	if (!$vehicle) {
        	die("Véhicule introuvable");
    	}

    	// charger la vue
    	require __DIR__ . '/../views/vehicles-detail-V.php';
	}

}
