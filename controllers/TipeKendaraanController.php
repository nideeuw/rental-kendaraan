<?php
require_once __DIR__ . '/../includes/validation_helper.php';
class TipeKendaraanController
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

        $tipe = $this->model->getAllTipe($pagination['limit'], $pagination['offset']);
        include 'views/tipe_kendaraan/tipe_kendaraan_list.php';
    }

    public function create(): void
    {
        if ($_POST) {

            $allErrors = [];

            $allErrors[] = ValidationHelper::validateName($_POST['nama_tipe'] ?? '', 'Nama tipe', 50);

            $error = ValidationHelper::formatErrors($allErrors);

            if (!empty($error)) {
                include 'views/tipe_kendaraan/tipe_kendaraan_form.php';
                return;
            }

            $data = [
                'nama_tipe' => ValidationHelper::sanitizeString($_POST['nama_tipe']),
            ];

            if ($this->model->createTipe($data)) {
                header("Location: index.php?action=tipe_kendaraan_list&message=created");
                exit();
            } else {
                $error = "Gagal menambah tipe kendaraan";
            }
        }

        include 'views/tipe_kendaraan/tipe_kendaraan_form.php';
    }

    public function edit(): void
    {
        $id = $_GET['id'];

        if ($_POST) {
            $allErrors = [];

            $allErrors[] = ValidationHelper::validateName($_POST['nama_tipe'] ?? '', 'Nama tipe', 50);

            $error = ValidationHelper::formatErrors($allErrors);

            if (!empty($error)) {
                $tipe = $this->model->getTipeById($id);
                include 'views/tipe_kendaraan/tipe_kendaraan_form.php';
                return;
            }

            $data = [
                'nama_tipe' => ValidationHelper::sanitizeString($_POST['nama_tipe']),
            ];

            if ($this->model->updateTipe($id, $data)) {
                header("Location: index.php?action=tipe_kendaraan_list&message=updated");
                exit();
            } else {
                $error = "Gagal mengupdate tipe kendaraan";
            }
        }

        $tipe = $this->model->getTipeById($id);
        include 'views/tipe_kendaraan/tipe_kendaraan_form.php';
    }

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

    public function search(): void
    {
        require_once __DIR__ . '/../includes/pagination.php';

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            // Ada keyword search
            $searchKeyword = ValidationHelper::sanitizeString($_GET['keyword']);
            $tipe = $this->model->searchTipe($searchKeyword);

            // Hitung total hasil search
            $total = $tipe->rowCount();
            $pagination = paginate($page, $total, $perPage);
        } else {
            // Tidak ada keyword, redirect ke list
            header("Location: index.php?action=tipe_kendaraan_list");
            exit();
        }

        include 'views/tipe_kendaraan/tipe_kendaraan_list.php';
    }
}
