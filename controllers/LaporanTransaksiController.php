<?php

class LaporanTransaksiController
{
    private $model;

    public function __construct($model)
    {
        $this->model = $model;
    }
    public function laporanTransaksi(): void
    {
        $laporanTransaksi = $this->model->getLaporanTransaksi();
        include 'views/laporan/laporan_transaksi.php';
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
?>