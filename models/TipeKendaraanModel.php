<?php
class TipeKendaraanModel
{
    private $conn;
    private $table_name = "tipe_kendaraan";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Ambil semua data
    public function getAllTipe()
    {
        $query = "SELECT id_tipe, nama_tipe FROM " . $this->table_name . " ORDER BY nama_tipe ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Tambah data
    public function createTipe($data)
    {
        $query = "INSERT INTO tipe_kendaraan (nama_tipe) VALUES (:nama_tipe)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nama_tipe", $data['nama_tipe']);
        return $stmt->execute();
    }

    // Update data
    public function updateTipe($id, $data)
    {
        $query = "UPDATE " . $this->table_name . "
                  SET nama_tipe = :nama_tipe
                  WHERE id_tipe = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":nama_tipe", $data['nama_tipe']);
        return $stmt->execute();
    }

    // Hapus data
    public function deleteTipe($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_tipe = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Jika ada foreign key error
            if ($e->getCode() == '23000') {
                return 'fk_error';
            } else {
                return false;
            }
        }
    }

    // Ambil detail berdasarkan ID
    public function getTipeById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_tipe = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Pencarian
    public function searchTipe($keyword)
    {

        $query = "SELECT id_tipe, nama_tipe FROM " . $this->table_name . "
                  WHERE nama_tipe LIKE :keyword
                  ORDER BY nama_tipe ASC";

        $stmt = $this->conn->prepare($query);
        $kw = "%{$keyword}%";
        $stmt->bindParam(":keyword", $kw);
        $stmt->execute();
        return $stmt;
    }
}
