<?php

require_once __DIR__ . '/../models/vehicles-M.php';

class AdminController {

    private function checkAdmin() {
        if (!isset($_SESSION['user_id']) || empty($_SESSION['admin']) || $_SESSION['admin'] != 1) {
            header("Location: /M-Motors/public/index.php");
            exit;
        }
    }

    // Dashboard
    public function dashboard() {
        $this->checkAdmin();

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
        require __DIR__ . '/../views/admin/dashboard-V.php';
    }


    public function adminVehicles() {
        $this->checkAdmin();

        $page = $_GET['page_num'] ?? 1;
        $page = is_numeric($page) ? (int)$page : 1;

        $limit = 10;

        $vehicles = getVehicles(null, null, null, null, $page, $limit);
        $total = countVehicles();
        $totalPages = ceil($total / $limit);

        require __DIR__ . '/../views/admin/vehicles-V.php';
    }

    // AJAX liste (optionnel mais recommandé)
    public function ajaxVehicles() {
        $this->checkAdmin();

        $page = $_GET['page_num'] ?? 1;
        $page = is_numeric($page) ? (int)$page : 1;

        $limit = 10;

        $vehicles = getVehicles(null, null, null, null, $page, $limit);
        $total = countVehicles();
        $totalPages = ceil($total / $limit);

        require __DIR__ . '/../views/admin/components/vehicles-list.php';
    }

    // Ajout véhicule (amélioré)
    public function addVehicle() {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Méthode non autorisée";
            return;
        }

        global $pdo;

        $imageName = null;

        // Upload sécurisé
        if (!empty($_FILES['image']['name'])) {

            $file = $_FILES['image'];

            // Vérifier erreurs
            if ($file['error'] === 0) {

                // Taille max 2MB
                if ($file['size'] <= 2 * 1024 * 1024) {

                    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

                    if (in_array($ext, $allowed)) {

                        $imageName = uniqid('veh_', true) . '.' . $ext;

                        $target = __DIR__ . '/../public/uploads/' . $imageName;

                        if (!move_uploaded_file($file['tmp_name'], $target)) {
                            echo "Erreur upload";
                            return;
                        }
                    }
                } else {
                    echo "Fichier trop volumineux";
                    return;
                }
            }
        } else {
			echo "Fichier manquant";
            return;
		}

        // Validation
        $brand = trim($_POST['brand'] ?? '');
        $model = trim($_POST['model'] ?? '');
        $type = $_POST['type'] ?? '';
        $price = $_POST['price'] ?? 0;
        $description = trim($_POST['description'] ?? '');

        if (!in_array($type, ['sale', 'rent'])) {
            echo "Type invalide";
            return;
        }

        if (!is_numeric($price)) {
            echo "Prix invalide";
            return;
        }

		// La colonne photo est NOT NULL : on refuse si l'upload n'a pas abouti
        if ($imageName === null) {
            echo "Image invalide (format accepté : jpg, jpeg, png, webp)";
            return;
        }

		// SQL — entry_by = id de l'admin connecté (colonne NOT NULL + clé étrangère)
        $sql = "INSERT INTO vehicles (brand, model, type, price, photo, description, entry_by)
                VALUES (:brand, :model, :type, :price, :photo, :description, :entry_by)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'brand' => $brand,
            'model' => $model,
            'type' => $type,
            'price' => (int)$price,
            'photo' => $imageName,
            'description' => $description,
            'entry_by' => $_SESSION['user_id'],
        ]);

        echo "OK";
    }
}