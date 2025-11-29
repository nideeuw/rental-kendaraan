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
    public function getAllPelanggan($limit, $offset)
    {
        $query = "SELECT 
                    id_pelanggan,
                    nama_pelanggan,
                    alamat,
                    no_telepon,
                    email
                  FROM " . $this->table_name . "
                  ORDER BY id_pelanggan ASC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
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
    public function searchPelanggan($keyword, $limit, $offset)
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
                  ORDER BY nama_pelanggan ASC
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
              WHERE nama_pelanggan LIKE :keyword
                     OR alamat LIKE :keyword
                     OR email LIKE :keyword";

        $stmt = $this->conn->prepare($query);
        $kw = "%{$keyword}%";
        $stmt->bindParam(":keyword", $kw);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)$result['total'];
    }
    // FUNCTION: Total Denda via PostgreSQL Function
    public function getTotalDenda($id_pelanggan)
    {
        $query = "SELECT total_denda_pelanggan(:id_pelanggan) AS total_denda;";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_pelanggan", $id_pelanggan);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTotal()
    {
        $query = "SELECT COUNT(*) as total FROM pelanggan";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)$result['total'];
    }
}
