<?php
class PengembalianModel
{
    private $conn;
    private $table_name = "pengembalian";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // GET ALL DATA
    public function getAllPengembalian()
    {
        $query = "SELECT 
                    p.id_pengembalian,
                    p.tanggal_pengembalian,
                    p.kondisi_kendaraan,
                    p.denda,
                    r.id_rental,
                    k.plat_nomor,
                    s.nama_sopir,
                    pl.nama_pelanggan
                  FROM " . $this->table_name . " p
                  LEFT JOIN rental r ON p.id_rental = r.id_rental
                  LEFT JOIN kendaraan k ON r.id_kendaraan = k.id_kendaraan
                  LEFT JOIN sopir s ON r.id_sopir = s.id_sopir
                  LEFT JOIN pelanggan pl ON r.id_pelanggan = pl.id_pelanggan
                  ORDER BY p.id_pengembalian DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // GET ALL DATA RENTAL
    public function getAllRental()
    {
        $query = "SELECT 
                    r.id_rental,
                    r.tanggal_sewa,
                    r.tanggal_kembali,
                    r.total_biaya,
                    r.status_rental,
                    k.plat_nomor,
                    s.nama_sopir,
                    p.nama_pelanggan    
                  FROM rental r
                  LEFT JOIN kendaraan k ON r.id_kendaraan = k.id_kendaraan
                  LEFT JOIN sopir s ON r.id_sopir = s.id_sopir
                  LEFT JOIN pelanggan p ON r.id_pelanggan = p.id_pelanggan
                  ORDER BY r.id_rental DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // CREATE DATA
    public function createPengembalian($data)
    {
        $query = "INSERT INTO " . $this->table_name . "
                  (tanggal_pengembalian, kondisi_kendaraan, denda, id_rental)
                  VALUES 
                  (:tanggal_pengembalian, :kondisi_kendaraan, :denda, :id_rental)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":tanggal_pengembalian", $data['tanggal_pengembalian']);
        $stmt->bindParam(":kondisi_kendaraan", $data['kondisi_kendaraan']);
        $stmt->bindParam(":denda", $data['denda']);
        $stmt->bindParam(":id_rental", $data['id_rental']);
        return $stmt->execute();
    }

    // UPDATE DATA
    public function updatePengembalian($id, $data)
    {
        $query = "UPDATE " . $this->table_name . "
                  SET tanggal_pengembalian = :tanggal_pengembalian,
                      kondisi_kendaraan = :kondisi_kendaraan,
                      denda = :denda,
                      id_rental = :id_rental
                  WHERE id_pengembalian = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":tanggal_pengembalian", $data['tanggal_pengembalian']);
        $stmt->bindParam(":kondisi_kendaraan", $data['kondisi_kendaraan']);
        $stmt->bindParam(":denda", $data['denda']);
        $stmt->bindParam(":id_rental", $data['id_rental']);
        return $stmt->execute();
    }

    // DELETE DATA
    public function deletePengembalian($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_pengembalian = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // GET SINGLE RECORD
    public function getPengembalianById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_pengembalian = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // SEARCH DATA
    public function searchPengembalian($keyword)
    {
        $query = "SELECT 
                    p.id_pengembalian,
                    p.tanggal_pengembalian,
                    p.kondisi_kendaraan,
                    p.denda,
                    r.id_rental,
                    k.plat_nomor,
                    s.nama_sopir,
                    pl.nama_pelanggan
                  FROM " . $this->table_name . " p
                  LEFT JOIN rental r ON p.id_rental = r.id_rental
                  LEFT JOIN kendaraan k ON r.id_kendaraan = k.id_kendaraan
                  LEFT JOIN sopir s ON r.id_sopir = s.id_sopir
                  LEFT JOIN pelanggan pl ON r.id_pelanggan = pl.id_pelanggan
                  WHERE k.plat_nomor ILIKE :keyword
                     OR s.nama_sopir ILIKE :keyword
                     OR pl.nama_pelanggan ILIKE :keyword
                  ORDER BY p.id_pengembalian DESC";

        $stmt = $this->conn->prepare($query);
        $kw = "%{$keyword}%";
        $stmt->bindParam(":keyword", $kw);
        $stmt->execute();
        return $stmt;
    }
}
