<?php

require_once '../controllers/vehicles-C.php';
require_once '../toolbox/tools.php';

$page = $_GET['page'] ?? 'home';

switch ($page) {

    case 'register':
        require __DIR__ . '/../views/register-V.php';
        break;

    case 'login':
        require __DIR__ . '/../views/login-V.php';
        break;

    case 'home':
    default:
        $controller = new VehicleController();
        $controller->index();
        break;
}