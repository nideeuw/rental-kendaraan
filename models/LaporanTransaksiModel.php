<?php

class LaporanTransaksiModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function getLaporanTransaksi()
    {
        $query = "SELECT * FROM mv_laporantransaksi";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function refreshLaporanTransaksi() {
        $query = "REFRESH MATERIALIZED VIEW mv_laporantransaksi";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }
}
?>