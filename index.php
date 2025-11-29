<?php
require_once 'config/database.php';
require_once 'models/DashboardModel.php';
require_once 'controllers/DashboardController.php';
require_once 'models/TipeKendaraanModel.php';
require_once 'controllers/TipeKendaraanController.php';
require_once 'models/KendaraanModel.php';
require_once 'controllers/KendaraanController.php';
require_once 'models/SopirModel.php';
require_once 'controllers/SopirController.php';
require_once 'models/PelangganModel.php';
require_once 'controllers/PelangganController.php';
require_once 'models/RentalModel.php';
require_once 'controllers/RentalController.php';
require_once 'models/pengembalianModel.php';
require_once 'controllers/PengembalianController.php';
require_once 'models/LaporanModel.php';
require_once 'controllers/LaporanController.php';

// db
$database = new Database();
$db = $database->getConnection();
// Dashboard
$dashboardModel = new DashboardModel($db);
$dashboardController = new DashboardController($dashboardModel);
//Tabel Tipe Kendaraan
$tipeKendaraanModel = new TipeKendaraanModel($db);
$TipeKendaraanController = new TipeKendaraanController($tipeKendaraanModel);
//Tabel Kendaraan
$kendaraanModel = new KendaraanModel($db);
$KendaraanController = new KendaraanController($kendaraanModel);
//Tabel Sopir
$sopirModel = new SopirModel($db);
$sopirController = new SopirController($sopirModel);
//Tabel Pelanggan
$pelangganModel = new PelangganModel($db);
$pelangganController = new PelangganController($pelangganModel);
//Tabel Rental
$rentalModel = new RentalModel($db);
$rentalController = new RentalController($rentalModel);
//Tabel Pengembalian
$pengambalianModel = new pengembalianModel($db);
$pengembalianController = new PengembalianController($pengambalianModel);
// Laporan Transaksi
$laporanModel = new LaporanModel($db);
$laporanController = new LaporanController($laporanModel);

$action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';

switch ($action) {
  case 'dashboard':
    $dashboardController->index();
    break;

  // routes tipe kendaraan
  case 'tipe_list':
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

  // routes kendaraan
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

  // routes sopir
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

  // routes pelanggan
  case 'pelanggan_list':
    $pelangganController->list();
    break;
  case 'pelanggan_create':
    $pelangganController->create();
    break;
  case 'pelanggan_edit':
    $pelangganController->edit();
    break;
  case 'pelanggan_delete':
    $pelangganController->delete();
    break;
  case 'pelanggan_search':
    $pelangganController->search();
    break;

  // routes rental
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

  // routes pengembalian
  case 'pengembalian_list':
    $pengembalianController->list();
    break;
  case 'pengembalian_create':
    $pengembalianController->create();
    break;
  case 'pengembalian_edit':
    $pengembalianController->edit();
    break;
  case 'pengembalian_delete':
    $pengembalianController->delete();
    break;
  case 'pengembalian_search':
    $pengembalianController->search();
    break;

  // routes function
  case 'pelanggan_total_denda':
    $controller = new PelangganController($pelangganModel);
    $controller->totalDenda();
    break;
  case 'kendaraan_tersedia':
    $controller = new KendaraanController($kendaraanModel);
    $controller->kendaraanTersedia();
    break;
  case 'kendaraan_ubah_status':
    $controller = new KendaraanController($kendaraanModel);
    $controller->ubah_status();
    break;

  // routes view
  case 'sopir_tersedia':
    $controller = new SopirController($sopirModel);
    $controller->sopirTersedia();
    break;
  case 'laporan_transaksi':
    $controller = new LaporanController($laporanModel);
    $controller->laporanTransaksi();
    break;
  case 'refresh_laporan':
    $controller = new LaporanController($laporanModel);
    $controller->refreshTransaksi();
  case 'laporan_pendapatan':
    $controller = new LaporanController($laporanModel);
    $controller->laporanPendapatan();
    break;
  
  // routes demo
  case 'demo_transaction':
    include 'views/demo_transaction.php';
    break;

  default:
    $dashboardController->index();
    break;
}