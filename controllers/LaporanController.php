<?php

class LaporanController
{
    private $model;

    public function __construct($model)
    {
        $this->model = $model;
    }
    public function laporanTransaksi(): void
    {
        require_once __DIR__ . '/../includes/pagination.php';

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

        // Ambil filter dari URL
        $filters = [
            'search' => isset($_GET['search']) ? trim($_GET['search']) : '',
            'status' => isset($_GET['status']) ? $_GET['status'] : '',
            'kondisi' => isset($_GET['kondisi']) ? $_GET['kondisi'] : '',
            'sort' => isset($_GET['sort']) ? $_GET['sort'] : 'id_rental',
            'order' => isset($_GET['order']) ? $_GET['order'] : 'DESC'
        ];

        // Hitung pagination
        $total = $this->model->getTotal($filters);
        $pagination = paginate($page, $total, $perPage);

        $laporanTransaksi = $this->model->getLaporanTransaksi($pagination['limit'], $pagination['offset'], $filters);
        include 'views/laporan/laporan_transaksi.php';
    }

    public function laporanPendapatan(): void
    {
        try {
            // Ambil filter dari URL
            $filters = [
                'tahun' => isset($_GET['tahun']) ? $_GET['tahun'] : '',
                'tahun_dari' => isset($_GET['tahun_dari']) ? $_GET['tahun_dari'] : '',
                'tahun_sampai' => isset($_GET['tahun_sampai']) ? $_GET['tahun_sampai'] : '',
                'sort' => isset($_GET['sort']) ? $_GET['sort'] : 'tahun',
                'order' => isset($_GET['order']) ? $_GET['order'] : 'DESC'
            ];

            // Ambil data statistik pendapatan
            $statistikPendapatan = $this->model->getLaporanPendapatan($filters);

            // Ambil list tahun untuk dropdown filter
            $tahunList = $this->model->getTahunList();
        } catch (Exception $e) {
            // Jika terjadi error, set default values
            $statistikPendapatan = [];
            $tahunList = [];
            $error = $e->getMessage();
        }

        include 'views/laporan/laporan_pendapatan.php';
    }

    public function refreshTransaksi(): void
    {
        if ($this->model->refreshLaporanTransaksi()) {
            header('Location: index.php?action=laporan_transaksi&success=refresh');
        } else {
            header('Location: index.php?action=laporan_transaksi&error=refresh');
        }
        exit();
    }
}