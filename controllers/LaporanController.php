<?php
require_once __DIR__ . '/../includes/validation_helper.php';

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

        $allowed_sort = ['tanggal_sewa', 'tanggal_kembali', 'total_biaya', 'status_rental'];
        $sort = isset($_GET['sort']) && in_array($_GET['sort'], $allowed_sort)
            ? $_GET['sort']
            : 'tanggal_sewa';

        $order = isset($_GET['order']) && strtoupper($_GET['order']) === 'ASC'
            ? 'ASC'
            : 'DESC';

        $valid_status = ['Tepat waktu', 'Terlambat'];
        $status = isset($_GET['status']) && in_array($_GET['status'], $valid_status)
            ? $_GET['status']
            : '';

        $valid_kondisi = ['Baik', 'Rusak', 'rusak berat'];
        $kondisi = isset($_GET['kondisi']) && in_array($_GET['kondisi'], $valid_kondisi)
            ? $_GET['kondisi']
            : '';

        $filters = [
            'search' => isset($_GET['search']) ? ValidationHelper::sanitizeString($_GET['search']) : '',
            'status' => $status,
            'kondisi' => $kondisi,
            'sort' => $sort,
            'order' => $order
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
            $allowed_sort = ['tahun', 'bulan', 'total_pendapatan'];
            $sort = isset($_GET['sort']) && in_array($_GET['sort'], $allowed_sort)
                ? $_GET['sort']
                : 'tahun';

            $order = isset($_GET['order']) && strtoupper($_GET['order']) === 'ASC'
                ? 'ASC'
                : 'DESC';

            // Validasi tahun (harus 4 digit)
            $tahun = '';
            if (isset($_GET['tahun']) && preg_match('/^\d{4}$/', $_GET['tahun'])) {
                $tahun = $_GET['tahun'];
            }

            $tahun_dari = '';
            if (isset($_GET['tahun_dari']) && preg_match('/^\d{4}$/', $_GET['tahun_dari'])) {
                $tahun_dari = $_GET['tahun_dari'];
            }

            $tahun_sampai = '';
            if (isset($_GET['tahun_sampai']) && preg_match('/^\d{4}$/', $_GET['tahun_sampai'])) {
                $tahun_sampai = $_GET['tahun_sampai'];
            }

            $filters = [
                'tahun' => $tahun,
                'tahun_dari' => $tahun_dari,
                'tahun_sampai' => $tahun_sampai,
                'sort' => $sort,
                'order' => $order
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
