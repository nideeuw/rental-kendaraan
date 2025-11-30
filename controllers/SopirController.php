<?php
require_once __DIR__ . '/../includes/validation_helper.php';

class SopirController
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

        $sopir = $this->model->getAllSopir($pagination['limit'], $pagination['offset']);
        include 'views/sopir/sopir_list.php';
    }

    public function create(): void
    {
        if ($_POST) {

            $allErrors = [];

            $allErrors[] = ValidationHelper::validateName($_POST['nama_sopir'] ?? '', 'Nama sopir', 100);
            $allErrors[] = ValidationHelper::validateNoSim($_POST['no_sim'] ?? '');
            $allErrors[] = ValidationHelper::validatePositiveNumber($_POST['tarif_harian'] ?? '', 'Tarif harian',  0);
            $allErrors[] = ValidationHelper::validateEnum($_POST['status_sopir'] ?? '', ['Tersedia', 'Tidak tersedia'], 'Status sopir');

            $error = ValidationHelper::formatErrors($allErrors);

            if (!empty($error)) {
                include 'views/sopir/sopir_form.php';
                return;
            }

            $data = [
                'nama_sopir'     => ValidationHelper::sanitizeString($_POST['nama_sopir']),
                'no_sim'         => strtoupper(trim($_POST['no_sim'])), // menggunakan uppercase untuk konsistensi
                'tarif_harian'   => (float)$_POST['tarif_harian'],
                'status_sopir'   => $_POST['status_sopir'],
            ];

            if ($this->model->createSopir($data)) {
                header("Location: index.php?action=sopir_list&message=created");
                exit();
            } else {
                $error = "Gagal menambah data sopir";
            }
        }

        include 'views/sopir/sopir_form.php';
    }

    public function edit(): void
    {
        $id = $_GET['id'];

        if ($_POST) {

            $allErrors = [];

            $allErrors[] = ValidationHelper::validateName($_POST['nama_sopir'] ?? '', 'Nama sopir', 100);
            $allErrors[] = ValidationHelper::validateNoSim($_POST['no_sim'] ?? '');
            $allErrors[] = ValidationHelper::validatePositiveNumber($_POST['tarif_harian'] ?? '', 'Tarif harian', 0);
            $allErrors[] = ValidationHelper::validateEnum($_POST['status_sopir'] ?? '', ['Tersedia', 'Tidak tersedia'], 'Status sopir');

            $error = ValidationHelper::formatErrors($allErrors);

            if (!empty($error)) {
                $sopir = $this->model->getSopirById($id);
                include 'views/sopir/sopir_form.php';
                return;
            }

            $data = [
                'nama_sopir'     => ValidationHelper::sanitizeString($_POST['nama_sopir']),
                'no_sim'         => strtoupper(trim($_POST['no_sim'])),
                'tarif_harian'   => (float)$_POST['tarif_harian'],
                'status_sopir'   => $_POST['status_sopir'],
            ];

            if ($this->model->updateSopir($id, $data)) {
                header("Location: index.php?action=sopir_list&message=updated");
                exit();
            } else {
                $error = "Gagal mengupdate data sopir";
            }
        }

        $sopir = $this->model->getSopirById($id);
        include 'views/sopir/sopir_form.php';
    }

    public function delete(): void
    {
        $id = $_GET['id'];

        if ($this->model->deleteSopir($id)) {
            header("Location: index.php?action=sopir_list&message=deleted");
        } else {
            header("Location: index.php?action=sopir_list&message=delete_error");
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

            $sopir = $this->model->searchSopir($searchKeyword, $pagination['limit'], $pagination['offset']);
        } else {
            // Tidak ada keyword, redirect ke list
            header("Location: index.php?action=sopir_list");
            exit();
        }

        include 'views/sopir/sopir_list.php';
    }

    public function sopirTersedia(): void
    {
        require_once __DIR__ . '/../includes/pagination.php';

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

        $allowed_sort = ['id_sopir', 'nama_sopir', 'tarif_harian'];
        $sort = isset($_GET['sort']) && in_array($_GET['sort'], $allowed_sort)
            ? $_GET['sort']
            : 'nama_sopir';

        $order = isset($_GET['order']) && strtoupper($_GET['order']) === 'ASC'
            ? 'ASC'
            : 'DESC';

        $filters = [
            'search' => isset($_GET['search']) ? ValidationHelper::sanitizeString($_GET['search']) : '',
            'sort' => $sort,
            'order' => $order
        ];

        // Hitung pagination dengan filter
        $total = $this->model->getTotalSopirTersedia($filters);
        $pagination = paginate($page, $total, $perPage);

        $sopir = $this->model->getSopirTersedia($pagination['limit'], $pagination['offset'], $filters);

        include 'views/sopir/sopir_tersedia.php';
    }
}
