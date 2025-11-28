<?php
class RentalController
{
    private $model;

    public function __construct($model)
    {
        $this->model = $model;
    }


    public function list(): void
    {
        $rental = $this->model->getAllRental();
        include 'views/rental/rental_list.php';
    }


    public function create(): void
    {
        if ($_POST) {

            $data = [
                'tanggal_sewa'    => $_POST['tanggal_sewa'],
                'tanggal_kembali' => $_POST['tanggal_kembali'],
                'total_biaya'     => $_POST['total_biaya'],
                'status_rental'   => $_POST['status_rental'],
                'id_kendaraan'    => $_POST['id_kendaraan'],
                'id_sopir'        => $_POST['id_sopir'],
                'id_pelanggan'    => $_POST['id_pelanggan']
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
            $data = [
                'tanggal_sewa'    => $_POST['tanggal_sewa'],
                'tanggal_kembali' => $_POST['tanggal_kembali'],
                'total_biaya'     => $_POST['total_biaya'],
                'status_rental'   => $_POST['status_rental'],
                'id_kendaraan'    => $_POST['id_kendaraan'],
                'id_sopir'        => $_POST['id_sopir'],
                'id_pelanggan'    => $_POST['id_pelanggan']
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

    // =====================================================
    // SEARCH DATA
    // =====================================================
    public function search(): void
    {
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            $rental = $this->model->searchRental($_GET['keyword']);
        } else {
            $rental = $this->model->getAllRental();
        }

        include 'views/rental/rental_list.php';
    }
}
