<?php

require_once __DIR__ . '/../config/database.php';

class ApplicationController {

    public function store() {

    	session_start();

    	if (!isset($_SESSION['user_id'])) {
        	die("Accès refusé");
    	}

    	global $pdo;

    	$user_id = $_SESSION['user_id'];
    	$vehicle_id = (int) $_POST['vehicle_id'];
    	$name = $_POST['name'];
    	$email = $_POST['email'];

    	$file = $_FILES['document'];

    	$filename = time() . '_' . $file['name'];
    	$target = __DIR__ . '/../public/uploads/' . $filename;

    	move_uploaded_file($file['tmp_name'], $target);

    	$sql = "INSERT INTO applications 
            (user_id, vehicle_id, name, email, document, status)
            VALUES (:user_id, :vehicle_id, :name, :email, :document, 'pending')";

    	$stmt = $pdo->prepare($sql);
    	$stmt->execute([
        	'user_id' => $user_id,
        	'vehicle_id' => $vehicle_id,
        	'name' => $name,
        	'email' => $email,
        	'document' => $filename
    	]);

    	header("Location: /index.php?success=1");
    	exit;
	}
}