<?php
class PelangganController
{
  private $model;

  public function __construct($model)
  {
    $this->model = $model;
  }


  public function list(): void
  {
    require_once __DIR__ . '/../includes/pagination.php';

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

    $total = $this->model->getTotal();
    $pagination = paginate($page, $total, $perPage);

    $pelanggan = $this->model->getAllPelanggan($pagination['limit'], $pagination['offset']);
    include 'views/pelanggan/pelanggan_list.php';
  }


  public function create(): void
  {
    if ($_POST) {

      $data = [
        'nama_pelanggan' => $_POST['nama_pelanggan'],
        'alamat'         => $_POST['alamat'],
        'no_telepon'     => $_POST['no_telepon'],
        'email'          => $_POST['email']
      ];


      if ($this->model->createPelanggan($data)) {
        header("Location: index.php?action=pelanggan_list&message=created");
        exit();
      } else {
        $error = "Gagal menambah data pelanggan";
      }
    }

    include 'views/pelanggan/pelanggan_form.php';
  }

  public function edit(): void
  {
    $id = $_GET['id'];
    $pelanggan = $this->model->getPelangganById($id);

    if ($_POST) {

      $data = [
        'nama_pelanggan' => $_POST['nama_pelanggan'],
        'alamat'         => $_POST['alamat'],
        'no_telepon'     => $_POST['no_telepon'],
        'email'          => $_POST['email']
      ];

      if ($this->model->updatePelanggan($id, $data)) {
        header("Location: index.php?action=pelanggan_list&message=updated");
        exit();
      } else {
        $error = "Gagal mengupdate data pelanggan";
      }
    }

    include 'views/pelanggan/pelanggan_form.php';
  }


  public function delete(): void
  {
    $id = $_GET['id'];

    if ($this->model->deletePelanggan($id)) {
      header("Location: index.php?action=pelanggan_list&message=deleted");
    } else {
      header("Location: index.php?action=pelanggan_list&message=delete_error");
    }
    exit();
  }


  public function search(): void
  {
    require_once __DIR__ . '/../includes/pagination.php';

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

    if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
      // Ada keyword search
      $searchKeyword = $_GET['keyword'];
      $total = $this->model->getTotalSearch($searchKeyword);
      $pagination = paginate($page, $total, $perPage);

      $pelanggan = $this->model->searchPelanggan($searchKeyword, $pagination['limit'], $pagination['offset']);
    } else {
      // Tidak ada keyword, redirect ke list
      header("Location: index.php?action=pelanggan_list");
      exit();
    }

    include 'views/pelanggan/pelanggan_list.php';
  }

  // FUNCTION: Total Denda Pelanggan
  public function totalDenda(): void
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      $id = $_POST['id_pelanggan'];

      // Ambil hasil dari function PostgreSQL
      $result = $this->model->getTotalDenda($id);

      include 'views/pelanggan/total_denda_result.php';
    } else {
      include 'views/pelanggan/total_denda.php';
    }
  }
}
