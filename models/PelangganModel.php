<?php
class PelangganModel
{
    private $conn;
    private $table_name = "pelanggan";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // GET ALL DATA
    public function getAllPelanggan()
    {
        $query = "SELECT 
                    id_pelanggan,
                    nama_pelanggan,
                    alamat,
                    no_telepon,
                    email
                  FROM " . $this->table_name . "
                  ORDER BY id_pelanggan ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // CREATE DATA
    public function createPelanggan($data)
    {
        $query = "INSERT INTO " . $this->table_name . "
                  (nama_pelanggan, alamat, no_telepon, email)
                  VALUES 
                  (:nama_pelanggan, :alamat, :no_telepon, :email)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nama_pelanggan", $data['nama_pelanggan']);
        $stmt->bindParam(":alamat", $data['alamat']);
        $stmt->bindParam(":no_telepon", $data['no_telepon']);
        $stmt->bindParam(":email", $data['email']);
        return $stmt->execute();
    }


    // UPDATE DATA
    public function updatePelanggan($id, $data)
    {
        $query = "UPDATE " . $this->table_name . "
                  SET 
                    nama_pelanggan = :nama_pelanggan,
                    alamat = :alamat,
                    no_telepon = :no_telepon,
                    email = :email
                  WHERE id_pelanggan = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":nama_pelanggan", $data['nama_pelanggan']);
        $stmt->bindParam(":alamat", $data['alamat']);
        $stmt->bindParam(":no_telepon", $data['no_telepon']);
        $stmt->bindParam(":email", $data['email']);
        return $stmt->execute();
    }

    // DELETE DATA
    public function deletePelanggan($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_pelanggan = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // GET SINGLE RECORD
    public function getPelangganById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_pelanggan = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // SEARCH DATA
    public function searchPelanggan($keyword)
    {
        $query = "SELECT 
                    id_pelanggan,
                    nama_pelanggan,
                    alamat,
                    no_telepon,
                    email
                  FROM " . $this->table_name . "
                  WHERE nama_pelanggan LIKE :keyword
                     OR alamat LIKE :keyword
                     OR email LIKE :keyword
                  ORDER BY nama_pelanggan ASC";

        $stmt = $this->conn->prepare($query);
        $kw = "%{$keyword}%";
        $stmt->bindParam(":keyword", $kw);
        $stmt->execute();
        return $stmt;
    }
}
