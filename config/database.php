<?php

$host = "localhost";
$dbname = "M-Motors";
$user = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
} catch (PDOException $e) {
	die("Erreur : " . $e->getMessage());
}