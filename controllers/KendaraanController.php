<?php
session_start();
class KendaraanController
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

        // Ambil filter dari URL
        $filters = [
            'search' => isset($_GET['search']) ? trim($_GET['search']) : '',
            'status' => isset($_GET['status']) ? $_GET['status'] : '',
            'sort' => isset($_GET['sort']) ? $_GET['sort'] : 'id_kendaraan',
            'order' => isset($_GET['order']) ? $_GET['order'] : 'DESC'
        ];

        $total = $this->model->getTotal($filters);
        $pagination = paginate($page, $total, $perPage);

        $kendaraan = $this->model->getAllKendaraan($pagination['limit'], $pagination['offset']);
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
        require_once __DIR__ . '/../includes/pagination.php';

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            // Ada keyword search
            $searchKeyword = $_GET['keyword'];

            $total = $this->model->getTotalSearch($searchKeyword);
            $pagination = paginate($page, $total, $perPage);

            $kendaraan = $this->model->searchKendaraan($searchKeyword, $pagination['limit'], $pagination['offset']);
        } else {
            // Tidak ada keyword, redirect ke list
            header("Location: index.php?action=kendaraan_list");
            exit();
        }

        include 'views/kendaraan/kendaraan_list.php';
    }

    public function kendaraanTersedia(): void
    {
        require_once __DIR__ . '/../includes/pagination.php';

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

        // Ambil filter dari URL
        $filters = [
            'search' => isset($_GET['search']) ? trim($_GET['search']) : '',
            'status' => isset($_GET['status']) ? $_GET['status'] : '',
            'sort' => isset($_GET['sort']) ? $_GET['sort'] : 'id_kendaraan',
            'order' => isset($_GET['order']) ? $_GET['order'] : 'DESC'
        ];

        // Hitung pagination
        $total = $this->model->getTotalKendaraanTersedia($filters);
        $pagination = paginate($page, $total, $perPage);

        $kendaraan = $this->model->getKendaraanTersedia($pagination['limit'], $pagination['offset'], $filters);
        include 'views/kendaraan/kendaraan_tersedia.php';
    }

    public function ubah_status()
    {
        $id = $_GET['id'];
        $status_baru = $_GET['status'];

        if ($this->model->ubahStatusKendaraan($id, $status_baru)) {
            $_SESSION['notif'] = [
                "type" => "success",
                "message" => "Status kendaraan berhasil diubah menjadi <b>$status_baru</b>!"
            ];
        } else {
            $_SESSION['notif'] = [
                "type" => "error",
                "message" => "Gagal mengubah status kendaraan."
            ];
        }

        header("Location: index.php?action=kendaraan_list");
        exit();
    }
}
