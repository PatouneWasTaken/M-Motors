<?php

require_once __DIR__ . '/../models/vehicles-M.php';

class VehicleController {

    public function index() {

        // Récupérer le filtre
        $type = $_GET['type'] ?? null;

        // Sécuriser
        if (!in_array($type, ['vente', 'location'])) {
            $type = null;
        }

        // Récupérer les données
        $vehicles = getVehicles($type);

        // Charger la vue
        require __DIR__ . '/../views/vehicles-V.php';
    }
}