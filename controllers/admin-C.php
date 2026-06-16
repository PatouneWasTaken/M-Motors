<?php

require_once __DIR__ . '/../models/vehicles-M.php';
require_once __DIR__ . '/../toolbox/validators.php';

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

    // Ajout véhicule
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

            if ($file['error'] !== 0) {
                echo "Erreur upload";
                return;
            }

            if (!isAllowedImage($file['name'], $file['size'])) {
                echo "Image invalide (format accepté : jpg, jpeg, png, webp ; 2 Mo max)";
                return;
            }

            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $imageName = uniqid('veh_', true) . '.' . $ext;

            $target = __DIR__ . '/../uploads/' . $imageName;

            if (!move_uploaded_file($file['tmp_name'], $target)) {
                echo "Erreur upload";
                return;
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

        $errors = validateVehicle($_POST);
        if ($errors) {
            echo $errors[0];
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

    // Modification véhicule
    public function editVehicle() {
        $this->checkAdmin();

        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            header("Location: /M-Motors/public/index.php?page=dashboard");
            exit;
        }

        // GET : on affiche le formulaire pré-rempli
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            $vehicle = getVehicleById($id);

            if (!$vehicle) {
                header("Location: /M-Motors/public/index.php?page=dashboard");
                exit;
            }

            require __DIR__ . '/../views/admin/edit-vehicle-V.php';
            return;
        }

        // POST : on traite la modification

        // Validation
        $brand = trim($_POST['brand'] ?? '');
        $model = trim($_POST['model'] ?? '');
        $type = $_POST['type'] ?? '';
        $price = $_POST['price'] ?? 0;
        $description = trim($_POST['description'] ?? '');

        $errors = validateVehicle($_POST);
        if ($errors) {
            echo $errors[0];
            return;
        }

        // Image optionnelle : si rien n'est envoyé, on garde la photo actuelle
        $imageName = null;

        if (!empty($_FILES['image']['name'])) {

            $file = $_FILES['image'];

            if ($file['error'] !== 0) {
                echo "Erreur upload";
                return;
            }

            if (!isAllowedImage($file['name'], $file['size'])) {
                echo "Image invalide (format accepté : jpg, jpeg, png, webp ; 2 Mo max)";
                return;
            }

            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $imageName = uniqid('veh_', true) . '.' . $ext;

            $target = __DIR__ . '/../uploads/' . $imageName;

            if (!move_uploaded_file($file['tmp_name'], $target)) {
                echo "Erreur upload";
                return;
            }
        }

        // Si une nouvelle photo a été uploadée, on supprime l'ancienne du disque
        if ($imageName !== null) {
            $old = getVehicleById($id);
            if ($old && !empty($old['photo'])) {
                $oldPath = __DIR__ . '/../uploads/' . $old['photo'];
                if (is_file($oldPath)) {
                    unlink($oldPath);
                }
            }
        }

        updateVehicle($id, $brand, $model, $type, $price, $description, $imageName);

        header("Location: /M-Motors/public/index.php?page=dashboard");
        exit;
    }

    // Suppression véhicule
    public function deleteVehicle() {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Méthode non autorisée";
            return;
        }

        global $pdo;

        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            header("Location: /M-Motors/public/index.php?page=dashboard");
            exit;
        }

        // On récupère le véhicule (pour sa photo) et les dossiers liés (pour leurs PDF)
        $vehicle = getVehicleById($id);

        $stmt = $pdo->prepare("SELECT document FROM applications WHERE vehicle_id = ?");
        $stmt->execute([$id]);
        $docs = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Suppression en base (ON DELETE CASCADE retire aussi les dossiers liés)
        deleteVehicle($id);

        // Nettoyage des fichiers orphelins sur le disque
        if ($vehicle && !empty($vehicle['photo'])) {
            $photoPath = __DIR__ . '/../uploads/' . $vehicle['photo'];
            if (is_file($photoPath)) {
                unlink($photoPath);
            }
        }

        foreach ($docs as $doc) {
            $docPath = __DIR__ . '/../storage/dossiers/' . $doc;
            if (is_file($docPath)) {
                unlink($docPath);
            }
        }

        header("Location: /M-Motors/public/index.php?page=dashboard");
        exit;
    }

    // Liste des dossiers déposés
    public function applications() {
        $this->checkAdmin();

        require_once __DIR__ . '/../models/applications-M.php';

        $apps = getAllApplications();

        require __DIR__ . '/../views/admin/applications-V.php';
    }

    // Accepter / refuser un dossier
    public function updateApplication() {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Méthode non autorisée";
            return;
        }

        require_once __DIR__ . '/../models/applications-M.php';

        $id = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';

        if ($id > 0 && in_array($status, ['accepted', 'refused'])) {
            updateApplicationStatus($id, $status);
        }

        header("Location: /M-Motors/public/index.php?page=admin_applications");
        exit;
    }

    // Téléchargement sécurisé du dossier PDF (réservé admin)
    public function downloadDossier() {
        $this->checkAdmin();

        require_once __DIR__ . '/../models/applications-M.php';

        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            http_response_code(400);
            die("Requête invalide");
        }

        $app = getApplicationById($id);

        if (!$app || empty($app['document'])) {
            http_response_code(404);
            die("Dossier introuvable");
        }

        // Le nom est généré côté serveur, mais on neutralise tout chemin par sécurité
        $filename = basename($app['document']);
        $path = __DIR__ . '/../storage/dossiers/' . $filename;

        if (!is_file($path)) {
            http_response_code(404);
            die("Fichier introuvable");
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }
}
