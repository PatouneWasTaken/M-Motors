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

        // Sécuriser
        if (!in_array($type, ['sale', 'rent'])) {
            $type = null;
        }

		$min = is_numeric($min) ? (int)$min : null;
		$max = is_numeric($max) ? (int)$max : null;

		$page = 1;
    	$limit = 10;

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

    	if (!in_array($type, ['sale', 'rent'])) {
        	$type = null;
    	}

    	$min = is_numeric($min) ? (int)$min : null;
    	$max = is_numeric($max) ? (int)$max : null;

    	$limit = 10;

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

	public function myApplications() {

    	if (!isset($_SESSION['user_id'])) {
        	header("Location: /index.php?page=login");
        	exit;
    	}

    	$user_id = $_SESSION['user_id'];

    	$applications = getUserApplications($user_id);

    	require __DIR__ . '/../views/my-applications-V.php';
	}
}