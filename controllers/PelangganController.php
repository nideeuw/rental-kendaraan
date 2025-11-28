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
    $pelanggan = $this->model->getAllPelanggan();
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
    if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
      $pelanggan = $this->model->searchPelanggan($_GET['keyword']);
    } else {
      $pelanggan = $this->model->getAllPelanggan();
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

