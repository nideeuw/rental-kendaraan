<?php
class DashboardModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // GET STATISTICS
    public function getStatistics()
    {
        $stats = [];

        try {
            // Total Kendaraan
            $stmt = $this->conn->query("SELECT COUNT(*) as total FROM kendaraan");
            $stats['total_kendaraan'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Kendaraan Tersedia
            $stmt = $this->conn->query("SELECT COUNT(*) as total FROM kendaraan WHERE LOWER(status) = 'Tersedia'");
            $stats['kendaraan_tersedia'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Kendaraan Disewa
            $stmt = $this->conn->query("SELECT COUNT(*) as total FROM kendaraan WHERE LOWER(status) = 'Disewa'");
            $stats['kendaraan_disewa'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Total Sopir
            $stmt = $this->conn->query("SELECT COUNT(*) as total FROM sopir");
            $stats['total_sopir'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Sopir Tersedia
            $stmt = $this->conn->query("SELECT COUNT(*) as total FROM sopir WHERE LOWER(status_sopir) = 'Tersedia'");
            $stats['sopir_tersedia'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Total Pelanggan
            $stmt = $this->conn->query("SELECT COUNT(*) as total FROM pelanggan");
            $stats['total_pelanggan'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Rental Aktif
            $stmt = $this->conn->query("SELECT COUNT(*) as total FROM rental WHERE LOWER(status_rental) = 'aktif'");
            $stats['rental_aktif'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Total Pendapatan Bulan Ini
            $stmt = $this->conn->query("
                SELECT COALESCE(SUM(total_biaya), 0) as total 
                FROM rental 
                WHERE EXTRACT(MONTH FROM tanggal_sewa) = EXTRACT(MONTH FROM CURRENT_DATE) 
                AND EXTRACT(YEAR FROM tanggal_sewa) = EXTRACT(YEAR FROM CURRENT_DATE)
            ");
            $stats['total_pendapatan'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            return $stats;
        } catch (Exception $e) {
            // Return default values jika error
            return [
                'total_kendaraan' => 0,
                'kendaraan_tersedia' => 0,
                'kendaraan_disewa' => 0,
                'total_sopir' => 0,
                'sopir_tersedia' => 0,
                'total_pelanggan' => 0,
                'rental_aktif' => 0,
                'total_pendapatan' => 0,
                'error' => $e->getMessage()
            ];
        }
    }

    // GET STATISTIK PENDAPATAN PER TAHUN
    public function getStatistikPendapatan()
    {
        try {
            $query = "SELECT * FROM vw_statistikpertahun ORDER BY tahun DESC";
            $stmt = $this->conn->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // GET RECENT RENTALS
    public function getRecentRentals($limit = 5)
    {
        try {
            $query = "
                SELECT 
                    r.id_rental,
                    r.tanggal_sewa,
                    r.tanggal_kembali,
                    r.total_biaya,
                    p.nama_pelanggan,
                    k.plat_nomor,
                    k.merk
                FROM rental r
                JOIN pelanggan p ON r.id_pelanggan = p.id_pelanggan
                JOIN kendaraan k ON r.id_kendaraan = k.id_kendaraan
                ORDER BY r.tanggal_sewa DESC
                LIMIT :limit
            ";

            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // GET CHART DATA (untuk future feature)
    public function getChartData($year = null)
    {
        if ($year === null) {
            $year = date('Y');
        }

        try {
            $query = "
                SELECT 
                    EXTRACT(MONTH FROM tanggal_sewa) as bulan,
                    COUNT(*) as total_rental,
                    SUM(total_biaya) as total_pendapatan
                FROM rental
                WHERE EXTRACT(YEAR FROM tanggal_sewa) = :year
                GROUP BY EXTRACT(MONTH FROM tanggal_sewa)
                ORDER BY bulan
            ";

            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':year', $year, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
}
