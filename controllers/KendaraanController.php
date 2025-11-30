<?php
session_start();
require_once __DIR__ . '/../includes/validation_helper.php';
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

        $allowed_sort = ['id_kendaraan', 'plat_nomor', 'merk', 'status', 'tarif_harian'];
        $sort = isset($_GET['sort']) && in_array($_GET['sort'], $allowed_sort)
            ? $_GET['sort']
            : 'id_kendaraan';

        $order = isset($_GET['order']) && strtoupper($_GET['order']) === 'ASC'
            ? 'ASC'
            : 'DESC';

        $valid_status = ['tersedia', 'disewa', 'maintenance'];
        $status = isset($_GET['status']) && in_array($_GET['status'], $valid_status)
            ? $_GET['status']
            : '';

        $filters = [
            'search' => isset($_GET['search']) ? ValidationHelper::sanitizeString($_GET['search']) : '',
            'status' => $status,
            'sort' => $sort,
            'order' => $order
        ];

        $total = $this->model->getTotal($filters);
        $pagination = paginate($page, $total, $perPage);

        $kendaraan = $this->model->getAllKendaraan($pagination['limit'], $pagination['offset']);
        include 'views/kendaraan/kendaraan_list.php';
    }

    public function create(): void
    {
        if ($_POST) {

            $allErrors = [];

            $allErrors[] = ValidationHelper::validatePlatNomor($_POST['plat_nomor'] ?? '');
            $allErrors[] = ValidationHelper::validateName($_POST['merk'] ?? '', 'Merk', 50);

            $warna = $_POST['warna'] ?? '';
            if (empty($warna)) {
                $allErrors[] = ["Warna harus diisi"];
            } elseif (!preg_match('/^[a-zA-Z\s]+$/', $warna)) {
                $allErrors[] = ["Warna hanya boleh huruf dan spasi"];
            } elseif (strlen($warna) > 30) {
                $allErrors[] = ["Warna maksimal 30 karakter"];
            }

            $allErrors[] = ValidationHelper::validateEnum(
                $_POST['status'] ?? '',
                ['Tersedia', 'Disewa', 'Perawatan'],
                'Status'
            );

            $allErrors[] = ValidationHelper::validatePositiveNumber(
                $_POST['kapasitas'] ?? '',
                'Kapasitas',
                1,
                50
            );

            $allErrors[] = ValidationHelper::validatePositiveNumber(
                $_POST['tarif_harian'] ?? '',
                'Tarif harian',
                0
            );

            $allErrors[] = ValidationHelper::validateId($_POST['id_tipe'] ?? '', 'Tipe kendaraan');

            $error = ValidationHelper::formatErrors($allErrors);

            if (!empty($error)) {
                $tipe_list = $this->model->getAllTipe();
                include 'views/kendaraan/kendaraan_form.php';
                return;
            }

            $data = [
                'plat_nomor'     => strtoupper(ValidationHelper::sanitizeString($_POST['plat_nomor'])),
                'merk'           => ValidationHelper::sanitizeString($_POST['merk']),
                'warna'          => ValidationHelper::sanitizeString($_POST['warna']),
                'status'         => $_POST['status'],
                'kapasitas'      => (int)$_POST['kapasitas'],
                'tarif_harian'   => (float)$_POST['tarif_harian'],
                'id_tipe'        => (int)$_POST['id_tipe']
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

            $allErrors = [];

            $allErrors[] = ValidationHelper::validatePlatNomor($_POST['plat_nomor'] ?? '');
            $allErrors[] = ValidationHelper::validateName($_POST['merk'] ?? '', 'Merk', 50);

            $warna = $_POST['warna'] ?? '';
            if (empty($warna)) {
                $allErrors[] = ["Warna harus diisi"];
            } elseif (!preg_match('/^[a-zA-Z\s]+$/', $warna)) {
                $allErrors[] = ["Warna hanya boleh huruf dan spasi"];
            } elseif (strlen($warna) > 30) {
                $allErrors[] = ["Warna maksimal 30 karakter"];
            }

            $allErrors[] = ValidationHelper::validateEnum(
                $_POST['status'] ?? '',
                ['tersedia', 'disewa', 'maintenance'],
                'Status'
            );

            $allErrors[] = ValidationHelper::validatePositiveNumber($_POST['kapasitas'] ?? '', 'Kapasitas', 1, 50);
            $allErrors[] = ValidationHelper::validatePositiveNumber($_POST['tarif_harian'] ?? '', 'Tarif harian', 0);
            $allErrors[] = ValidationHelper::validateId($_POST['id_tipe'] ?? '', 'Tipe kendaraan');

            $error = ValidationHelper::formatErrors($allErrors);

            if (!empty($error)) {
                $kendaraan = $this->model->getKendaraanById($id);
                $tipe_list = $this->model->getAllTipe();
                include 'views/kendaraan/kendaraan_form.php';
                return;
            }

            $data = [
                'plat_nomor'     => strtoupper(ValidationHelper::sanitizeString($_POST['plat_nomor'])),
                'merk'           => ValidationHelper::sanitizeString($_POST['merk']),
                'warna'          => ValidationHelper::sanitizeString($_POST['warna']),
                'status'         => $_POST['status'],
                'kapasitas'      => (int)$_POST['kapasitas'],
                'tarif_harian'   => (float)$_POST['tarif_harian'],
                'id_tipe'        => (int)$_POST['id_tipe']
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
            $searchKeyword = ValidationHelper::sanitizeString($_GET['keyword']);
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

        $allowed_sort = ['id_kendaraan', 'plat_nomor', 'merk', 'tarif_harian'];
        $sort = isset($_GET['sort']) && in_array($_GET['sort'], $allowed_sort)
            ? $_GET['sort']
            : 'id_kendaraan';

        $order = isset($_GET['order']) && strtoupper($_GET['order']) === 'ASC'
            ? 'ASC'
            : 'DESC';

        $filters = [
            'search' => isset($_GET['search']) ? ValidationHelper::sanitizeString($_GET['search']) : '',
            'status' => 'Tersedia',
            'sort' => $sort,
            'order' => $order
        ];

        // Hitung pagination
        $total = $this->model->getTotalKendaraanTersedia($filters);
        $pagination = paginate($page, $total, $perPage);

        $kendaraan = $this->model->getKendaraanTersedia($pagination['limit'], $pagination['offset'], $filters);
        include 'views/kendaraan/kendaraan_tersedia.php';
    }

    public function ubah_status()
    {
        $allErrors = [];
        $allErrors[] = ValidationHelper::validateId($_GET['id'] ?? '', 'ID Kendaraan');
        $allErrors[] = ValidationHelper::validateEnum(
            $_GET['status'] ?? '', 
            ['Tersedia', 'Disewa', 'Perawatan'], 
            'Status'
        );

        $error = ValidationHelper::formatErrors($allErrors);
        
        if (!empty($error)) {
            $_SESSION['notif'] = [
                "type" => "error",
                "message" => $error
            ];
            header("Location: index.php?action=kendaraan_list");
            exit();
        }

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
