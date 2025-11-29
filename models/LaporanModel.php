<?php

class LaporanModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function getLaporanTransaksi($limit, $offset, $filters = [])
    {
        $query = "SELECT * FROM mv_laporantransaksi";

        $params = [];
        $conditions = [];

        // SEARCH: Case insensitive dengan ILIKE (PostgreSQL) atau LOWER+LIKE
        if (!empty($filters['search'])) {
            // Untuk PostgreSQL gunakan ILIKE, untuk MySQL gunakan LOWER
            $conditions[] = "(
                nama_pelanggan ILIKE :search OR 
                nama_sopir ILIKE :search OR 
                plat_nomor ILIKE :search OR 
                merk ILIKE :search OR
                nama_tipe ILIKE :search
            )";
            $searchTerm = '%' . $filters['search'] . '%';
            $params['search'] = $searchTerm;
        }

        // FILTER: Status Pengembalian
        if (!empty($filters['status']) && $filters['status'] !== '') {
            $conditions[] = "status_pengembalian = :status";
            $params['status'] = $filters['status'];
        }

        // FILTER: Kondisi Kendaraan
        if (!empty($filters['kondisi']) && $filters['kondisi'] !== '') {
            $conditions[] = "kondisi_kendaraan = :kondisi";
            $params['kondisi'] = $filters['kondisi'];
        }

        // WHERE clause
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }

        // SORTING
        $allowedSort = [
            'id_rental',
            'nama_pelanggan',
            'nama_sopir',
            'tanggal_sewa',
            'total_biaya',
            'denda',
            'tarif_harian',
            'plat_nomor',
            'merk'
        ];

        $sortBy = !empty($filters['sort']) && in_array($filters['sort'], $allowedSort)
            ? $filters['sort']
            : 'tanggal_sewa';

        $sortOrder = !empty($filters['order']) && strtoupper($filters['order']) === 'ASC'
            ? 'ASC'
            : 'DESC';

        $query .= " ORDER BY {$sortBy} {$sortOrder}";
        $query .= " LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getTotal($filters = [])
    {
        $query = "SELECT COUNT(*) as total FROM mv_laporantransaksi";

        $params = [];
        $conditions = [];

        // SEARCH - SAMA SEPERTI DI getLaporanTransaksi
        if (!empty($filters['search'])) {
            $conditions[] = "(
                nama_pelanggan ILIKE :search OR 
                nama_sopir ILIKE :search OR 
                plat_nomor ILIKE :search OR 
                merk ILIKE :search OR
                nama_tipe ILIKE :search
            )";
            $searchTerm = '%' . $filters['search'] . '%';
            $params['search'] = $searchTerm;
        }

        // FILTER: Status
        if (!empty($filters['status']) && $filters['status'] !== '') {
            $conditions[] = "status_pengembalian = :status";
            $params['status'] = $filters['status'];
        }

        // FILTER: Kondisi
        if (!empty($filters['kondisi']) && $filters['kondisi'] !== '') {
            $conditions[] = "kondisi_kendaraan = :kondisi";
            $params['kondisi'] = $filters['kondisi'];
        }

        // WHERE clause
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)$result['total'];
    }

    public function refreshLaporanTransaksi()
    {
        $query = "REFRESH MATERIALIZED VIEW mv_laporantransaksi";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }

    public function getLaporanPendapatan($filters = [])
    {
        $query = "SELECT * FROM vw_statistikpertahun";

        $params = [];
        $conditions = [];

        // FILTER: Tahun tertentu (jika ada)
        if (!empty($filters['tahun']) && $filters['tahun'] !== '') {
            $conditions[] = "tahun = :tahun";
            $params['tahun'] = $filters['tahun'];
        }

        // FILTER: Rentang tahun
        if (!empty($filters['tahun_dari'])) {
            $conditions[] = "tahun >= :tahun_dari";
            $params['tahun_dari'] = $filters['tahun_dari'];
        }

        if (!empty($filters['tahun_sampai'])) {
            $conditions[] = "tahun <= :tahun_sampai";
            $params['tahun_sampai'] = $filters['tahun_sampai'];
        }

        // WHERE clause
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }

        // SORTING untuk statistik pendapatan
        $allowedSort = [
            'tahun',
            'total_rental',
            'total_denda',
            'total_rental_denda'
        ];

        $sortBy = !empty($filters['sort']) && in_array($filters['sort'], $allowedSort)
            ? $filters['sort']
            : 'tahun';

        $sortOrder = !empty($filters['order']) && strtoupper($filters['order']) === 'ASC'
            ? 'ASC'
            : 'DESC';

        $query .= " ORDER BY {$sortBy} {$sortOrder}";

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTahunList()
    {
        $query = "SELECT DISTINCT tahun FROM vw_statistikpertahun ORDER BY tahun DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
