<?php
class SopirModel
{
    private $conn;
    private $table_name = "sopir";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Ambil semua data
    public function getAllTipe()
    {
        $query = "SELECT id_sopir, nama_sopir, no_sim, tarif_harian, status_sopir  FROM " . $this->table_name . " ORDER BY nama_sopir ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // ======================================================
    // GET ALL DATA
    // ======================================================
    public function getAllSopir()
    {
        $query = "SELECT 
                    id_sopir,
                    nama_sopir,
                    no_sim,
                    tarif_harian,
                    status_sopir
                  FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // ======================================================
    // CREATE DATA
    // ======================================================
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

    // ======================================================
    // UPDATE DATA
    // ======================================================
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

    // ======================================================
    // DELETE DATA
    // ======================================================
    public function deleteSopir($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_sopir = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // ======================================================
    // GET SINGLE RECORD
    // ======================================================
    public function getSopirById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_sopir = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ======================================================
    // SEARCH
    // ======================================================
    public function searchSopir($keyword)
    {

        $query = "SELECT id_Sopir, nama_sopir, no_sim, tarif_harian, status_sopir FROM " . $this->table_name . "
                  WHERE nama_sopir LIKE :keyword
                  ORDER BY nama_sopir ASC";

        $stmt = $this->conn->prepare($query);
        $kw = "%{$keyword}%";
        $stmt->bindParam(":keyword", $kw);
        $stmt->execute();
        return $stmt;
    }
}
