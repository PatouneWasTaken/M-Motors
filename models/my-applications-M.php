<?php

require_once __DIR__ . '/../config/database.php';

function getUserApplications($user_id) {
    global $pdo;

    $sql = "SELECT 
    		a.id,
    		a.status,
    		a.created_at,
    		v.name AS vehicle_name,
    		v.price,
    		v.type
		FROM applications a
		JOIN vehicles v ON a.vehicle_id = v.id
		WHERE a.user_id = :user_id
		ORDER BY a.created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}