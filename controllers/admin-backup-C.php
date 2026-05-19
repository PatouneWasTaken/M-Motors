<?php

require_once __DIR__ . '/../models/vehicles-M.php';

class AdminController {

    private function checkAdmin() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: /index.php");
            exit;
        }
    }

    // Dashboard
    public function dashboard() {
        $this->checkAdmin();
        require __DIR__ . '/../views/admin/dashboard-V.php';
    }

    // Liste véhicules (réutilise MODEL)
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
        }

        // Validation minimale
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

        // INSERT
        $sql = "INSERT INTO vehicles (brand, model, type, price, photo, description)
                VALUES (:brand, :model, :type, :price, :photo, :description)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'brand' => $brand,
            'model' => $model,
            'type' => $type,
            'price' => (int)$price,
            'photo' => $imageName,
            'description' => $description,
        ]);

        echo "OK";
    }
}