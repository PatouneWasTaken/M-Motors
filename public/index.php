<?php

session_start();
require_once '../controllers/vehicles-C.php';
require_once '../toolbox/tools.php';

$page = $_GET['page'] ?? 'home';

switch ($page) {

	case 'vehicle':
    	$controller = new VehicleController();
    	$controller->show();
    	break;

	case 'apply':
    if (!isset($_SESSION['user_id'])) {
        header("Location: /index.php?page=login");
        exit;
    }
    require __DIR__ . '/../views/apply-V.php';
    break;

	case 'submit_app':
    	require_once '../controllers/apply-C.php';
    	$controller = new ApplicationController();
    	$controller->store();
    	break;

    case 'register':
        require __DIR__ . '/../views/register-V.php';
        break;

    case 'login':
        require __DIR__ . '/../views/login-V.php';
        break;

	case 'vehicles':
   		$controller = new VehicleController();
    	$controller->asyncList();
    	break;

	case 'admin':
    	require_once '../controllers/admin-C.php';
    	$controller = new AdminController();
    	$controller->dashboard();
    	break;

	case 'admin_vehicles':
    	$controller = new AdminController();
    	$controller->adminVehicles();
    	break;

	case 'admin_add_vehicles':
    	$controller = new AdminController();
    	$controller->addVehicle();
    	break;

    case 'home':
    default:
        $controller = new VehicleController();
        $controller->index();
        break;
}