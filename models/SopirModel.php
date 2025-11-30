<?php
class SopirModel
{
    private $conn;
    private $table_name = "sopir";

    public function __construct($db)
    {
        $this->conn = $db;
    }


    // GET ALL DATA
    public function getAllSopir($limit, $offset)
    {
        $query = "SELECT 
                    id_sopir,
                    nama_sopir,
                    no_sim,
                    tarif_harian,
                    status_sopir
                  FROM " . $this->table_name . "                 
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    // CREATE DATA
    public function createSopir($data)
    {
        $query = "INSERT INTO " . $this->table_name . "
                  (nama_sopir, no_sim, tarif_harian, status_sopir)
                  VALUES 
                  (:nama_sopir, :no_sim, :tarif_harian, :status_sopir)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nama_sopir", $data['nama_sopir']);
        $stmt->bindParam(":no_sim", $data['no_sim']);
        $stmt->bindParam(":tarif_harian", $data['tarif_harian']);
        $stmt->bindParam(":status_sopir", $data['status_sopir']);;
        return $stmt->execute();
    }

    // UPDATE DATA
    public function updateSopir($id, $data)
    {
        $query = "UPDATE " . $this->table_name . "
                  SET nama_sopir = :nama_sopir,
                      no_sim = :no_sim,
                      tarif_harian = :tarif_harian,
                      status_sopir = :status_sopir
                  WHERE id_sopir = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":nama_sopir", $data['nama_sopir']);
        $stmt->bindParam(":no_sim", $data['no_sim']);
        $stmt->bindParam(":tarif_harian", $data['tarif_harian']);
        $stmt->bindParam(":status_sopir", $data['status_sopir']);
        return $stmt->execute();
    }

    // DELETE DATA
    public function deleteSopir($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_sopir = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // GET SINGLE RECORD
    public function getSopirById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_sopir = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // SEARCH
    public function searchSopir($keyword, $limit, $offset)
    {

        $query = "SELECT id_Sopir, nama_sopir, no_sim, tarif_harian, status_sopir FROM " . $this->table_name . "
                  WHERE nama_sopir LIKE :keyword
                  OR no_sim LIKE :keyword
                  OR status_sopir LIKE :keyword
                  ORDER BY nama_sopir ASC
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
              FROM " . $this->table_name . "
              WHERE nama_sopir LIKE :keyword
              OR no_sim LIKE :keyword
              OR status_sopir LIKE :keyword";

        $stmt = $this->conn->prepare($query);
        $kw = "%{$keyword}%";
        $stmt->bindParam(":keyword", $kw);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)$result['total'];
    }

    public function getSopirTersedia($limit, $offset, $filters = [])
    {
        // subquery untuk filtering
        $query = "SELECT * FROM (
                    SELECT * FROM vw_sopirtersedia
                  ) AS hasil";

        $params = [];
        $conditions = [];

        // SEARCH
        if (!empty($filters['search'])) {
            $conditions[] = "(
                nama_sopir ILIKE :search OR 
                no_sim ILIKE :search
            )";
            $searchTerm = '%' . $filters['search'] . '%';
            $params['search'] = $searchTerm;
        }

        // WHERE clause
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }

        // SORTING
        $allowedSort = [
            'nama_sopir',
            'no_sim',
            'tarif_harian'
        ];

        $sortBy = !empty($filters['sort']) && in_array($filters['sort'], $allowedSort)
            ? $filters['sort']
            : 'nama_sopir';

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

    public function getTotal()
    {
        $query = "SELECT COUNT(*) as total FROM sopir";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)$result['total'];
    }

    public function getTotalSopirTersedia($filters = [])
    {
        $query = "SELECT COUNT(*) as total FROM (
                    SELECT * FROM vw_sopirtersedia
                  ) AS hasil";

        $params = [];
        $conditions = [];

        // SEARCH
        if (!empty($filters['search'])) {
            $conditions[] = "(
                nama_sopir ILIKE :search OR 
                no_sim ILIKE :search
            )";
            $searchTerm = '%' . $filters['search'] . '%';
            $params['search'] = $searchTerm;
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
