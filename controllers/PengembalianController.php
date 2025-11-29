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
        require_once __DIR__ . '/../includes/pagination.php';

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

        $total = $this->model->getTotal();
        $pagination = paginate($page, $total, $perPage);

        $pengembalian = $this->model->getAllPengembalian($pagination['limit'], $pagination['offset']);
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
        require_once __DIR__ . '/../includes/pagination.php';

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            // Ada keyword search
            $searchKeyword = $_GET['keyword'];
            $total = $this->model->getTotalSearch($searchKeyword);
            $pagination = paginate($page, $total, $perPage);

            $pengembalian = $this->model->searchPengembalian($searchKeyword, $pagination['limit'], $pagination['offset']);
        } else {
            // Tidak ada keyword, redirect ke list
            header("Location: index.php?action=pengembalian_list");
            exit();
        }

        include 'views/pengembalian/pengembalian_list.php';
    }

    // untuk trigger hitung denda otomatis
    public function getRentalDetail(): void
    {
        header('Content-Type: application/json');

        if (!isset($_GET['id_rental'])) {
            echo json_encode(['error' => 'ID Rental tidak ditemukan']);
            exit();
        }

        $id_rental = $_GET['id_rental'];
        $detail = $this->model->getRentalDetailForDenda($id_rental);

        if ($detail) {
            echo json_encode([
                'success' => true,
                'tanggal_kembali' => $detail['tanggal_kembali'],
                'tarif_harian' => $detail['tarif_harian']
            ]);
        } else {
            echo json_encode(['error' => 'Data rental tidak ditemukan']);
        }

        exit();
    }
}
