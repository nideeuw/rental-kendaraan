<?php
class RentalModel
{
    private $conn;
    private $table_name = "rental";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // GET ALL DATA
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
                  FROM " . $this->table_name . " r
                  LEFT JOIN kendaraan k ON r.id_kendaraan = k.id_kendaraan
                  LEFT JOIN sopir s ON r.id_sopir = s.id_sopir
                  LEFT JOIN pelanggan p ON r.id_pelanggan = p.id_pelanggan
                  ORDER BY r.id_rental DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // GET ALL DATA KENDARAAN UNTUK DROP DOWN
    public function getAllKendaraan()
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
                  ORDER BY k.id_kendaraan DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // GET ALL DATA SOPIR UNTUK DROP DOWN
    public function getAllSopir()
    {
        $query = "SELECT 
                    id_sopir,
                    nama_sopir,
                    no_sim,
                    tarif_harian,
                    status_sopir
                  FROM sopir
                  ORDER BY id_sopir ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // GET ALL DATA PELANGGAN UNTUK DROP DOWN
    public function getAllPelanggan()
    {
        $query = "SELECT 
                    id_pelanggan,
                    nama_pelanggan,
                    alamat,
                    no_telepon,
                    email
                  FROM pelanggan
                  ORDER BY id_pelanggan ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // CREATE DATA
    public function createRental($data)
    {
        $query = "INSERT INTO " . $this->table_name . "
                  (tanggal_sewa, tanggal_kembali, total_biaya, status_rental, id_kendaraan, id_sopir, id_pelanggan)
                  VALUES 
                  (:tanggal_sewa, :tanggal_kembali, :total_biaya, :status_rental, :id_kendaraan, :id_sopir, :id_pelanggan)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":tanggal_sewa", $data['tanggal_sewa']);
        $stmt->bindParam(":tanggal_kembali", $data['tanggal_kembali']);
        $stmt->bindParam(":total_biaya", $data['total_biaya']);
        $stmt->bindParam(":status_rental", $data['status_rental']);
        $stmt->bindParam(":id_kendaraan", $data['id_kendaraan']);
        $stmt->bindParam(":id_sopir", $data['id_sopir']);
        $stmt->bindParam(":id_pelanggan", $data['id_pelanggan']);
        return $stmt->execute();
    }

    // UPDATE DATA
    public function updateRental($id, $data)
    {
        $query = "UPDATE " . $this->table_name . "
                  SET tanggal_sewa = :tanggal_sewa,
                      tanggal_kembali = :tanggal_kembali,
                      total_biaya = :total_biaya,
                      status_rental = :status_rental,
                      id_kendaraan = :id_kendaraan,
                      id_sopir = :id_sopir,
                      id_pelanggan = :id_pelanggan
                  WHERE id_rental = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":tanggal_sewa", $data['tanggal_sewa']);
        $stmt->bindParam(":tanggal_kembali", $data['tanggal_kembali']);
        $stmt->bindParam(":total_biaya", $data['total_biaya']);
        $stmt->bindParam(":status_rental", $data['status_rental']);
        $stmt->bindParam(":id_kendaraan", $data['id_kendaraan']);
        $stmt->bindParam(":id_sopir", $data['id_sopir']);
        $stmt->bindParam(":id_pelanggan", $data['id_pelanggan']);
        return $stmt->execute();
    }

    // DELETE DATA
    public function deleteRental($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_rental = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // GET SINGLE RECORD
    public function getRentalById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_rental = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // SEARCH
    public function searchRental($keyword)
    {
        $query = "SELECT 
                    r.id_rental,
                    r.tanggal_sewa,
                    r.tanggal_kembali,
                    r.total_biaya,
                    r.status_rental,
                    k.plat_nomor,
                    k.merk,
                    k.warna,
                    t.nama_tipe,
                    s.nama_sopir,
                    p.nama_pelanggan
                  FROM " . $this->table_name . " r
                  LEFT JOIN kendaraan k ON r.id_kendaraan = k.id_kendaraan
                  LEFT JOIN tipe_kendaraan t ON k.id_tipe = t.id_tipe
                  LEFT JOIN sopir s ON r.id_sopir = s.id_sopir
                  LEFT JOIN pelanggan p ON r.id_pelanggan = p.id_pelanggan
                  WHERE k.plat_nomor ILIKE :keyword
                     OR k.merk ILIKE :keyword
                     OR k.warna ILIKE :keyword
                     OR t.nama_tipe ILIKE :keyword
                     OR s.nama_sopir ILIKE :keyword
                     OR p.nama_pelanggan ILIKE :keyword
                  ORDER BY r.id_rental DESC";

        $stmt = $this->conn->prepare($query);
        $kw = "%{$keyword}%";
        $stmt->bindParam(":keyword", $kw);
        $stmt->execute();
        return $stmt;
    }
}
