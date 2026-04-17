<?php

$host = "localhost";
$dbname = "MotorsDB";
$user = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
	die("Erreur : " . $e->getMessage()); //debug
    //die("Erreur de connexion à la base");
}