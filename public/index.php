<?php

require '../config/database.php';

$query = $pdo->query("SELECT * FROM cars");
$vehicles = $query->fetchAll();

print_r($vehicles);