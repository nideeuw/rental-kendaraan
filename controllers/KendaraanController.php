<?php
class KendaraanController
{
    private $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function list(): void
    {
        $kendaraan = $this->model->getAllKendaraan();
        include 'views/kendaraan/kendaraan_list.php';
    }

    public function create(): void
    {
        if ($_POST) {

            $data = [
                'plat_nomor'     => $_POST['plat_nomor'],
                'merk'           => $_POST['merk'],
                'warna'          => $_POST['warna'],
                'status'         => $_POST['status'],
                'kapasitas'      => $_POST['kapasitas'],
                'tarif_harian'   => $_POST['tarif_harian'],
                'id_tipe'        => $_POST['id_tipe']
            ];

            if ($this->model->createKendaraan($data)) {
                header("Location: index.php?action=kendaraan_list&message=created");
                exit();
            } else {
                $error = "Gagal menambah data kendaraan";
            }
        }

        $tipe_list = $this->model->getAllTipe();
        include 'views/kendaraan/kendaraan_form.php';
    }

    public function edit(): void
    {
        $id = $_GET['id'];

        if ($_POST) {

            $data = [
                'plat_nomor'     => $_POST['plat_nomor'],
                'merk'           => $_POST['merk'],
                'warna'          => $_POST['warna'],
                'status'         => $_POST['status'],
                'kapasitas'      => $_POST['kapasitas'],
                'tarif_harian'   => $_POST['tarif_harian'],
                'id_tipe'        => $_POST['id_tipe']
            ];

            if ($this->model->updateKendaraan($id, $data)) {
                header("Location: index.php?action=kendaraan_list&message=updated");
                exit();
            } else {
                $error = "Gagal mengupdate data kendaraan";
            }
        }

        $kendaraan = $this->model->getKendaraanById($id);
        $tipe_list = $this->model->getAllTipe();
        include 'views/kendaraan/kendaraan_form.php';
    }

    public function delete(): void
    {
        $id = $_GET['id'];

        if ($this->model->deleteKendaraan($id)) {
            header("Location: index.php?action=kendaraan_list&message=deleted");
        } else {
            header("Location: index.php?action=kendaraan_list&message=delete_error");
        }
        exit();
    }

    public function search(): void
    {
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            $kendaraan = $this->model->searchKendaraan($_GET['keyword']);
        } else {
            $kendaraan = $this->model->getAllKendaraan();
        }

        include 'views/kendaraan/kendaraan_list.php';
    }
}
