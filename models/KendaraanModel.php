<?php
class KendaraanModel
{
    private $conn;
    private $table_name = "kendaraan";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // GET TIPE KENDARAAN (UNTUK DROPDOWN)
    public function getAllTipe()
    {
        $query = "SELECT id_tipe, nama_tipe FROM tipe_kendaraan ORDER BY nama_tipe";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // GET ALL
    public function getAllKendaraan($limit, $offset)
    {
        $query = "SELECT 
                    k.id_kendaraan,
                    k.plat_nomor,
                    k.merk,
                    k.warna,
                    k.id_tipe,
                    k.status,
                    k.kapasitas,
                    k.tarif_harian,
                    t.nama_tipe
                  FROM kendaraan k
                  LEFT JOIN tipe_kendaraan t ON k.id_tipe = t.id_tipe
                  ORDER BY k.id_kendaraan DESC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    // CREATE
    public function createKendaraan($data)
    {
        $query = "INSERT INTO kendaraan 
                  (plat_nomor, merk, warna, status, kapasitas, tarif_harian, id_tipe)
                  VALUES (:plat_nomor, :merk, :warna, :status, :kapasitas, :tarif_harian, :id_tipe)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":plat_nomor", $data['plat_nomor']);
        $stmt->bindParam(":merk", $data['merk']);
        $stmt->bindParam(":warna", $data['warna']);
        $stmt->bindParam(":status", $data['status']);
        $stmt->bindParam(":kapasitas", $data['kapasitas']);
        $stmt->bindParam(":tarif_harian", $data['tarif_harian']);
        $stmt->bindParam(":id_tipe", $data['id_tipe']);
        return $stmt->execute();
    }

    // UPDATE
    public function updateKendaraan($id, $data)
    {
        $query = "UPDATE kendaraan
                  SET plat_nomor = :plat_nomor,
                      merk = :merk,
                      warna = :warna,
                      status = :status,
                      kapasitas = :kapasitas,
                      tarif_harian = :tarif_harian,
                      id_tipe = :id_tipe
                  WHERE id_kendaraan = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":plat_nomor", $data['plat_nomor']);
        $stmt->bindParam(":merk", $data['merk']);
        $stmt->bindParam(":warna", $data['warna']);
        $stmt->bindParam(":status", $data['status']);
        $stmt->bindParam(":kapasitas", $data['kapasitas']);
        $stmt->bindParam(":tarif_harian", $data['tarif_harian']);
        $stmt->bindParam(":id_tipe", $data['id_tipe']);
        return $stmt->execute();
    }

    // DELETE
    public function deleteKendaraan($id)
    {
        $query = "DELETE FROM kendaraan WHERE id_kendaraan = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // GET ONE
    public function getKendaraanById($id)
    {
        $query = "SELECT * FROM kendaraan WHERE id_kendaraan = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // SEARCH
    public function searchKendaraan($keyword, $limit, $offset)
    {
        $query = "SELECT 
                    k.id_kendaraan,
                    k.plat_nomor,
                    k.merk,
                    k.warna,
                    k.status,
                    k.kapasitas,
                    k.tarif_harian,
                    t.nama_tipe
                  FROM kendaraan k
                  LEFT JOIN tipe_kendaraan t ON k.id_tipe = t.id_tipe
                  WHERE k.plat_nomor ILIKE :keyword
                     OR k.merk ILIKE :keyword
                     OR k.warna ILIKE :keyword
                     OR k.status ILIKE :keyword
                     OR t.nama_tipe ILIKE :keyword
                  ORDER BY k.id_kendaraan DESC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $kw = "%{$keyword}%";
        $stmt->bindParam(":keyword", $kw);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    public function getTotalSearch($keyword)
    {
        $query = "SELECT COUNT(*) as total
              FROM kendaraan k
              LEFT JOIN tipe_kendaraan t ON k.id_tipe = t.id_tipe
              WHERE k.plat_nomor ILIKE :keyword
                 OR k.merk ILIKE :keyword
                 OR k.warna ILIKE :keyword
                 OR k.status ILIKE :keyword
                 OR t.nama_tipe ILIKE :keyword";

        $stmt = $this->conn->prepare($query);
        $kw = "%{$keyword}%";
        $stmt->bindParam(":keyword", $kw);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)$result['total'];
    }

    public function getKendaraanTersedia($limit, $offset, $filters = [])
    {
        // Gunakan subquery untuk menghindari masalah dengan function
        $query = "SELECT * FROM (
                    SELECT * FROM daftar_kendaraan_tersedia()
                  ) AS hasil";

        $params = [];
        $conditions = [];

        // SEARCH
        if (!empty($filters['search'])) {
            $conditions[] = "(
                CAST(id_kendaraan AS TEXT) ILIKE :search OR
                plat_nomor ILIKE :search OR 
                merk ILIKE :search OR
                status ILIKE :search
            )";
            $searchTerm = '%' . $filters['search'] . '%';
            $params['search'] = $searchTerm;
        }

        // FILTER: Status
        if (!empty($filters['status']) && $filters['status'] !== '') {
            $conditions[] = "status = :status";
            $params['status'] = $filters['status'];
        }

        // WHERE clause
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }

        // SORTING
        $allowedSort = [
            'id_kendaraan',
            'plat_nomor',
            'merk',
            'status'
        ];

        $sortBy = !empty($filters['sort']) && in_array($filters['sort'], $allowedSort)
            ? $filters['sort']
            : 'id_kendaraan';

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

    public function ubahStatusKendaraan($id_kendaraan, $status_baru)
    {
        $query = "CALL ubah_status_kendaraan(:id_kendaraan, :status_baru)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_kendaraan', $id_kendaraan);
        $stmt->bindParam(':status_baru', $status_baru);
        return $stmt->execute();
    }

    public function getTotal()
    {
        $query = "SELECT COUNT(*) as total FROM kendaraan";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)$result['total'];
    }

    public function getTotalKendaraanTersedia($filters = [])
    {
        $query = "SELECT COUNT(*) as total FROM (
                    SELECT * FROM daftar_kendaraan_tersedia()
                  ) AS hasil";

        $params = [];
        $conditions = [];

        // SEARCH
        if (!empty($filters['search'])) {
            $conditions[] = "(
                CAST(id_kendaraan AS TEXT) ILIKE :search OR
                plat_nomor ILIKE :search OR 
                merk ILIKE :search OR
                status ILIKE :search
            )";
            $searchTerm = '%' . $filters['search'] . '%';
            $params['search'] = $searchTerm;
        }

        if (!empty($filters['status']) && $filters['status'] !== '') {
            $conditions[] = "status = :status";
            $params['status'] = $filters['status'];
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
}
