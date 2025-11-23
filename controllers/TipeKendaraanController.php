<?php
class TipeKendaraanController
{
    private $model;

    // ==========
    // CONSTRUCTOR
    // ==========
    public function __construct($model)
    {
        $this->model = $model;
    }

    // ============================
    // LIST DATA
    // ============================
    public function list(): void
    {
        $tipe = $this->model->getAllTipe();
        include 'views/tipe_kendaraan/tipe_kendaraan_list.php';
    }

    // ============================
    // CREATE DATA
    // ============================
    public function create(): void
    {
        if ($_POST) {

            $data = [
                'nama_tipe' => $_POST['nama_tipe'],
            ];

            if ($this->model->createTipe($data)) {
                header("Location: index.php?action=tipe_kendaraan_list&message=created");
                exit();
            } else {
                $error = "Gagal menambah tipe kendaraan";
            }
        }

        $tipe_list = $this->model->getAllTipe();
        include 'views/tipe_kendaraan/tipe_kendaraan_form.php';
    }

    // ============================
    // EDIT DATA
    // ============================
    public function edit(): void
    {
        $id = $_GET['id'];

        if ($_POST) {
            $data = [
                'nama_tipe' => $_POST['nama_tipe'],
            ];

            if ($this->model->updateTipe($id, $data)) {
                header("Location: index.php?action=tipe_kendaraan_list&message=updated");
                exit();
            } else {
                $error = "Gagal mengupdate tipe kendaraan";
            }
        }

        $tipe = $this->model->getTipeById($id);
        $tipe_list = $this->model->getAllTipe();
        include 'views/tipe_kendaraan/tipe_kendaraan_form.php';
    }

    // ============================
    // DELETE DATA
    // ============================
    public function delete(): void
    {
        $id = $_GET['id'];

        if ($this->model->deleteTipe($id)) {
            header("Location: index.php?action=tipe_kendaraan_list&message=deleted");
        } else {
            header("Location: index.php?action=tipe_kendaraan_list&message=delete_error");
        }
        exit();
    }

    // ============================
    // SEARCH DATA
    // ============================
    public function search(): void
    {
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            $tipe = $this->model->searchTipe($_GET['keyword']);
        } else {
            $tipe = $this->model->getAllTipe();
        }

        include 'views/tipe_kendaraan/tipe_kendaraan_list.php';
    }
}
?>
