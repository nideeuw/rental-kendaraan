<?php
require_once __DIR__ . '/../includes/validation_helper.php';

class RentalController
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

        $rental = $this->model->getAllRental($pagination['limit'], $pagination['offset']);
        include 'views/rental/rental_list.php';
    }

    public function create(): void
    {
        if ($_POST) {

            $allErrors = [];

            $allErrors[] = ValidationHelper::validateDate($_POST['tanggal_sewa'] ?? '', 'Tanggal sewa');
            $allErrors[] = ValidationHelper::validateDate($_POST['tanggal_kembali'] ?? '', 'Tanggal kembali');

            if (!empty($_POST['tanggal_sewa']) && !empty($_POST['tanggal_kembali'])) {
                $allErrors[] = ValidationHelper::validateDateRange($_POST['tanggal_sewa'], $_POST['tanggal_kembali'], 'Tanggal sewa', 'Tanggal kembali');
            }

            $allErrors[] = ValidationHelper::validatePositiveNumber($_POST['total_biaya'] ?? '', 'Total biaya', 0);
            $allErrors[] = ValidationHelper::validateId($_POST['id_kendaraan'] ?? '', 'Kendaraan');
            $allErrors[] = ValidationHelper::validateId($_POST['id_pelanggan'] ?? '', 'Pelanggan');

            // Validasi ID sopir (optional)
            if (!empty($_POST['id_sopir'])) {
                $allErrors[] = ValidationHelper::validateId($_POST['id_sopir'], 'Sopir', false);
            }

            $error = ValidationHelper::formatErrors($allErrors);

            if (!empty($error)) {
                $kendaraan_list = $this->model->getAllKendaraan();
                $sopir_list      = $this->model->getAllSopir();
                $pelanggan_list  = $this->model->getAllPelanggan();
                include 'views/rental/rental_form.php';
                return;
            }

            $data = [
                'tanggal_sewa'    => $_POST['tanggal_sewa'],
                'tanggal_kembali' => $_POST['tanggal_kembali'],
                'total_biaya'     => (float)$_POST['total_biaya'],
                'id_kendaraan'    => (int)$_POST['id_kendaraan'],
                'id_sopir'        => !empty($_POST['id_sopir']) ? (int)$_POST['id_sopir'] : null,
                'id_pelanggan'    => (int)$_POST['id_pelanggan']
            ];

            $result = $this->model->createRentalWithTransaction($data);

            if ($result === true) {
                header('Location: index.php?action=rental_list&message=created');
                exit();
            } else {
                $error = $result;

                $kendaraan_list = $this->model->getAllKendaraan();
                $sopir_list      = $this->model->getAllSopir();
                $pelanggan_list  = $this->model->getAllPelanggan();

                include 'views/rental/rental_form.php';
                return;
            }

            // Code create lama
            // if ($this->model->createRental($data)) {
            //     header("Location: index.php?action=rental_list&message=created");
            //     exit();
            // } else {
            //     $error = "Gagal menambah data rental";
            // }
        }

        // Ambil list kendaraan, sopir, dan pelanggan untuk dropdown
        $kendaraan_list = $this->model->getAllKendaraan();
        $sopir_list      = $this->model->getAllSopir();
        $pelanggan_list  = $this->model->getAllPelanggan();
        include 'views/rental/rental_form.php';
    }

    public function edit(): void
    {
        $id = $_GET['id'];
        $rental = $this->model->getRentalById($id);

        if ($_POST) {
            $allErrors = [];

            $allErrors[] = ValidationHelper::validateDate($_POST['tanggal_sewa'] ?? '', 'Tanggal sewa');
            $allErrors[] = ValidationHelper::validateDate($_POST['tanggal_kembali'] ?? '', 'Tanggal kembali');

            if (!empty($_POST['tanggal_sewa']) && !empty($_POST['tanggal_kembali'])) {
                $allErrors[] = ValidationHelper::validateDateRange($_POST['tanggal_sewa'], $_POST['tanggal_kembali']);
            }

            $allErrors[] = ValidationHelper::validatePositiveNumber($_POST['total_biaya'] ?? '', 'Total biaya', 0);
            $allErrors[] = ValidationHelper::validateEnum($_POST['status_rental'] ?? '', ['Aktif', 'Selesai'], 'Status rental');
            $allErrors[] = ValidationHelper::validateId($_POST['id_kendaraan'] ?? '', 'Kendaraan');
            $allErrors[] = ValidationHelper::validateId($_POST['id_pelanggan'] ?? '', 'Pelanggan');

            if (!empty($_POST['id_sopir'])) {
                $allErrors[] = ValidationHelper::validateId($_POST['id_sopir'], 'Sopir', false);
            }

            $error = ValidationHelper::formatErrors($allErrors);

            if (!empty($error)) {
                $kendaraan_list = $this->model->getAllKendaraan();
                $sopir_list      = $this->model->getAllSopir();
                $pelanggan_list  = $this->model->getAllPelanggan();
                include 'views/rental/rental_form.php';
                return;
            }

            $data = [
                'tanggal_sewa'    => $_POST['tanggal_sewa'],
                'tanggal_kembali' => $_POST['tanggal_kembali'],
                'total_biaya'     => (float)$_POST['total_biaya'],
                'status_rental'   => $_POST['status_rental'],
                'id_kendaraan'    => (int)$_POST['id_kendaraan'],
                'id_sopir'        => !empty($_POST['id_sopir']) ? (int)$_POST['id_sopir'] : null,
                'id_pelanggan'    => (int)$_POST['id_pelanggan']
            ];

            if ($this->model->updateRental($id, $data)) {
                header("Location: index.php?action=rental_list&message=updated");
                exit();
            } else {
                $error = "Gagal mengupdate data rental";
            }
        }

        // Ambil list kendaraan, sopir, dan pelanggan untuk dropdown
        $kendaraan_list = $this->model->getAllKendaraan();
        $sopir_list      = $this->model->getAllSopir();
        $pelanggan_list  = $this->model->getAllPelanggan();
        include 'views/rental/rental_form.php';
    }

    public function delete(): void
    {
        $id = $_GET['id'];

        if ($this->model->deleteRental($id)) {
            header("Location: index.php?action=rental_list&message=deleted");
        } else {
            header("Location: index.php?action=rental_list&message=delete_error");
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
            $total = $this->model->getTotalSearch($searchKeyword);
            $pagination = paginate($page, $total, $perPage);

            $rental = $this->model->searchRental($searchKeyword, $pagination['limit'], $pagination['offset']);
        } else {
            // Tidak ada keyword, redirect ke list
            header("Location: index.php?action=rental_list");
            exit();
        }

        include 'views/rental/rental_list.php';
    }
}
