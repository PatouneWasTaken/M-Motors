<?php

class AdminController {

    private function checkAdmin() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: /index.php");
            exit;
        }
    }

    public function dashboard() {
        $this->checkAdmin();
        require __DIR__ . '/../views/admin/dashboard-V.php';
    }

	public function vehicles() {
    	$this->checkAdmin();
    	$vehicles = getVehicles();
    	require __DIR__ . '/../views/admin/vehicles-V.php';
	}

	public function addVehicle() {
    	$this->checkAdmin();

    	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        	global $pdo;
			$imageName = null;

			if (!empty($_FILES['image']['name'])) {

            	$file = $_FILES['image'];

            	// sécuriser extension
            	$allowed = ['jpg', 'jpeg', 'png', 'webp'];
            	$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            	if (in_array($ext, $allowed)) {

                	// nom unique
                	$imageName = uniqid() . '.' . $ext;

                	$target = __DIR__ . '/../public/uploads/' . $imageName;

                	move_uploaded_file($file['tmp_name'], $target);
            	}
        	}

        	$sql = "INSERT INTO vehicles (brand, model, price, type, description, photo)
                VALUES (:brand, :model, :price, :type, :description, :photo)";

        	$stmt = $pdo->prepare($sql);
        	$stmt->execute([
            	'brand' => $_POST['brand'],
            	'model' => $_POST['model'],
            	'price' => $_POST['price'],
            	'type' => $_POST['type'],
				'description' => $_POST['description'],
				'photo' => $imageName,
        	]);

        	header("Location: /index.php?page=admin_vehicles");
        	exit;
    	}

    	require __DIR__ . '/../views/admin/add-V.php';
	}

	// a upgrader:

	public function applications() {
    	$this->checkAdmin();

    	global $pdo;

    	$sql = "SELECT a.*, v.name AS vehicle_name
            FROM applications a
            JOIN vehicles v ON a.vehicle_id = v.id
            ORDER BY a.created_at DESC";

    	$apps = $pdo->query($sql)->fetchAll();

    	require __DIR__ . '/../views/admin/applications-V.php';
	}

	public function updateStatus($id, $status) {
    	$this->checkAdmin();

    	global $pdo;

    	$stmt = $pdo->prepare("UPDATE applications SET status = :status WHERE id = :id");
    	$stmt->execute([
        	'status' => $status,
        	'id' => $id
    	]);

    	header("Location: /index.php?page=admin_applications");
	}

}