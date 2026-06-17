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
			header("Location: /M-Motors/public/index.php?page=login");
			exit;
		}
		require __DIR__ . '/../views/apply-V.php';
		break;

	case 'submit_app':
    	require_once '../controllers/apply-C.php';
    	$controller = new ApplicationController();
    	$controller->store();
    	break;

	case 'login':
		require __DIR__ . '/../views/login-V.php';
		break;

	case 'logout':
		require_once '../controllers/logout-C.php';
		break;

	case 'vehicles':
   		$controller = new VehicleController();
    	$controller->asyncList();
    	break;

	// Espace utilisateur : page "Mon compte"
	case 'account':
		require_once '../controllers/account-C.php';
		$controller = new AccountController();
		$controller->show();
		break;

	case 'account_update_profile':
		require_once '../controllers/account-C.php';
		$controller = new AccountController();
		$controller->updateProfile();
		break;

	case 'account_update_password':
		require_once '../controllers/account-C.php';
		$controller = new AccountController();
		$controller->updatePassword();
		break;

	// Dashboard administrateur (alias : admin)
	case 'dashboard':
	case 'admin':
    	require_once '../controllers/admin-C.php';
    	$controller = new AdminController();
    	$controller->dashboard();
    	break;

	case 'admin_vehicles':
    	require_once '../controllers/admin-C.php';
    	$controller = new AdminController();
    	$controller->adminVehicles();
    	break;

	case 'admin_add_vehicles':
    	require_once '../controllers/admin-C.php';
    	$controller = new AdminController();
    	$controller->addVehicle();
    	break;

	case 'admin_edit_vehicle':
    	require_once '../controllers/admin-C.php';
    	$controller = new AdminController();
    	$controller->editVehicle();
    	break;

	case 'admin_delete_vehicle':
    	require_once '../controllers/admin-C.php';
    	$controller = new AdminController();
    	$controller->deleteVehicle();
    	break;

	case 'admin_applications':
    	require_once '../controllers/admin-C.php';
    	$controller = new AdminController();
    	$controller->applications();
    	break;

	case 'admin_update_application':
    	require_once '../controllers/admin-C.php';
    	$controller = new AdminController();
    	$controller->updateApplication();
    	break;

	case 'admin_delete_application':
    	require_once '../controllers/admin-C.php';
    	$controller = new AdminController();
    	$controller->deleteApplication();
    	break;

	case 'admin_download_dossier':
    	require_once '../controllers/admin-C.php';
    	$controller = new AdminController();
    	$controller->downloadDossier();
    	break;

    case 'home':
    default:
        $controller = new VehicleController();
        $controller->index();
        break;
}
