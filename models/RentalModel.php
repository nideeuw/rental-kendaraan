<?php
class RentalModelModel {
    private $conn;
    private $table_name = "rental";

    public function __construct($db) {
        $this->conn = $db;
    }
    
    // ======================================================
    // GET ALL DATA
    // ======================================================
    public function getAllKendaraan() {
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
                  FROM " . $this->table_name . " k
                  LEFT JOIN tipe_kendaraan t ON k.id_tipe = t.id_tipe
                  ORDER BY k.id_kendaraan DESC";
    
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // ======================================================
    // CREATE DATA
    // ======================================================
    public function createKendaraan($data) {
        $query = "INSERT INTO " . $this->table_name . "
                  (plat_nomor, merk, warna, status, kapasitas, tarif_harian, id_tipe)
                  VALUES 
                  (:plat_nomor, :merk, :warna, :status, :kapasitas, :tarif_harian, :id_tipe)";
        
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

    // ======================================================
    // UPDATE DATA
    // ======================================================
    public function updateKendaraan($id, $data) {
        $query = "UPDATE " . $this->table_name . "
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

    // ======================================================
    // DELETE DATA
    // ======================================================
    public function deleteKendaraan($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_kendaraan = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // ======================================================
    // GET SINGLE RECORD
    // ======================================================
    public function getKendaraanById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_kendaraan = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ======================================================
    // SEARCH
    // ======================================================
    public function searchKendaraan($keyword) {
        $query = "SELECT 
                    k.id_kendaraan,
                    k.plat_nomor,
                    k.merk,
                    k.warna,
                    k.status,
                    k.kapasitas,
                    k.tarif_harian,
                    t.nama_tipe
                  FROM " . $this->table_name . " k
                  LEFT JOIN tipe_kendaraan t ON k.id_tipe = t.id_tipe
                  WHERE k.plat_nomor ILIKE :keyword
                     OR k.merk ILIKE :keyword
                     OR k.warna ILIKE :keyword
                  ORDER BY k.id_kendaraan DESC";

        $stmt = $this->conn->prepare($query);
        $kw = "%{$keyword}%";
        $stmt->bindParam(":keyword", $kw);
        $stmt->execute();
        return $stmt;
    }

}
?>
