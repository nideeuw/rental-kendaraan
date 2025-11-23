<?php
require_once 'config/database.php';
require_once 'models/TipeKendaraanModel.php';
require_once 'controllers/TipeKendaraanController.php';
require_once 'models/KendaraanModel.php';
require_once 'controllers/KendaraanController.php';
require_once 'models/SopirModel.php';
require_once 'controllers/SopirController.php';
require_once 'models/RentalModel.php';
require_once 'controllers/RentalController.php';



// db
$database = new Database();
$db = $database->getConnection();
$tipeKendaraanModel = new TipeKendaraanModel($db);
$TipeKendaraanController = new TipeKendaraanController($tipeKendaraanModel);
$kendaraanModel = new KendaraanModel($db);
$KendaraanController = new KendaraanController($kendaraanModel);
$sopirModel = new SopirModel($db);
$sopirController = new SopirController($sopirModel);
$rentalModel = new RentalModel($db);
$rentalController = new RentalController($rentalModel);

$action = isset($_GET['action']) ? $_GET['action'] : 'tipe_kendaraan_list';

switch ($action) {
    // tipeKedaraan routes
    case 'tipe_kendaraan_list':
        $TipeKendaraanController->list();
        break;
    case 'tipe_kendaraan_create':
        $TipeKendaraanController->create();
        break;
    case 'tipe_kendaraan_edit':
        $TipeKendaraanController->edit();
        break;
    case 'tipe_kendaraan_delete':
        $TipeKendaraanController->delete();
        break;
    case 'tipe_kendaraan_search':
        $TipeKendaraanController->search();
        break;

    case 'kendaraan_list':
        $KendaraanController->list();
        break;
    case 'kendaraan_create':
        $KendaraanController->create();
        break;
    case 'kendaraan_edit':
        $KendaraanController->edit();
        break;
    case 'kendaraan_delete':
        $KendaraanController->delete();
        break;
    case 'kendaraan_search':
        $KendaraanController->search();
        break;

    case 'sopir_list':
        $sopirController->list();
        break;
    case 'sopir_create':
        $sopirController->create();
        break;
    case 'sopir_edit':
        $sopirController->edit();
        break;
    case 'sopir_delete':
        $sopirController->delete();
        break;
    case 'sopir_search':
        $sopirController->search();
        break;

    case 'rental_list':
        $rentalController->list();
        break;
    case 'rental_create':
        $rentalController->create();
        break;
    case 'rental_edit':
        $rentalController->edit();
        break;
    case 'rental_delete':
        $rentalController->delete();
        break;
    case 'rental_search':
        $rentalController->search();
        break;


    default:
        $TipeKendaraanController->list();
        break;
}
