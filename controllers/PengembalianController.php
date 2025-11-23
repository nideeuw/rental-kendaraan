<?php
class PengembalianController
{
    private $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function list(): void
    {
        $pengembalian = $this->model->getAllPengembalian();
        include 'views/pengembalian/pengembalian_list.php';
    }

    public function create(): void
    {
        if ($_POST) {
            $data = [
                'tanggal_pengembalian' => $_POST['tanggal_pengembalian'],
                'kondisi_kendaraan'   => $_POST['kondisi_kendaraan'],
                'denda'               => $_POST['denda'],
                'id_rental'           => $_POST['id_rental']
            ];

            if ($this->model->createPengembalian($data)) {
                header("Location: index.php?action=pengembalian_list&message=created");
                exit();
            } else {
                $error = "Gagal menambahkan data pengembalian";
            }
        }

        // Ambil daftar rental untuk dropdown
        $rental_list = $this->model->getAllRental();
        include 'views/pengembalian/pengembalian_form.php';
    }

    public function edit(): void
    {
        $id = $_GET['id'];
        if ($_POST) {
            $data = [
                'tanggal_pengembalian' => $_POST['tanggal_pengembalian'],
                'kondisi_kendaraan'   => $_POST['kondisi_kendaraan'],
                'denda'               => $_POST['denda'],
                'id_rental'           => $_POST['id_rental']
            ];

            if ($this->model->updatePengembalian($id, $data)) {
                header("Location: index.php?action=pengembalian_list&message=updated");
                exit();
            } else {
                $error = "Gagal mengupdate data pengembalian";
            }
        }

        $pengembalian = $this->model->getPengembalianById($id);
        $rental_list = $this->model->getAllRental();
        include 'views/pengembalian/pengembalian_form.php';
    }


    public function delete(): void
    {
        $id = $_GET['id'];

        if ($this->model->deletePengembalian($id)) {
            header("Location: index.php?action=pengembalian_list&message=deleted");
        } else {
            header("Location: index.php?action=pengembalian_list&message=delete_error");
        }
        exit();
    }

    
    public function search(): void
    {
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            $pengembalian = $this->model->searchPengembalian($_GET['keyword']);
        } else {
            $pengembalian = $this->model->getAllPengembalian();
        }

        include 'views/pengembalian/pengembalian_list.php';
    }
}
?>
