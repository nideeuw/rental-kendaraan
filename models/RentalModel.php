<?php
class RentalModel
{
    private $conn;
    private $table_name = "rental";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function createRentalWithTransaction($data): bool|string
    {
        try {
            // BEGIN TRANSACTION
            $this->conn->beginTransaction();

            // Cek Status Kendaraan dan lock
            $checkKendaraan = "SELECT id_kendaraan, status 
                              FROM kendaraan 
                              WHERE id_kendaraan = :id_kendaraan 
                              FOR UPDATE";

            $stmtCheck = $this->conn->prepare($checkKendaraan);
            $stmtCheck->bindParam(':id_kendaraan', $data['id_kendaraan'], PDO::PARAM_INT);
            $stmtCheck->execute();
            $kendaraan = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if (!$kendaraan || $kendaraan['status'] !== 'Tersedia') {
                if ($kendaraan['status'] === 'Disewa') {
                    throw new Exception("Kendaraan sedang disewa");
                } else if ($kendaraan['status'] === 'Perawatan') {
                    throw new Exception("Kendaraan sedang masa perawatan");
                }
                throw new Exception("Kendaraan tidak tersedia");
            }

            // Cek Status Sopir dan lock
            $checkSopir = "SELECT id_sopir, status_sopir 
                          FROM sopir 
                          WHERE id_sopir = :id_sopir 
                          FOR UPDATE";

            $stmtCheck = $this->conn->prepare($checkSopir);
            $stmtCheck->bindParam(':id_sopir', $data['id_sopir'], PDO::PARAM_INT);
            $stmtCheck->execute();
            $sopir = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if (!$sopir || $sopir['status_sopir'] !== 'Tersedia') {
                throw new Exception("Sopir tidak tersedia");
            }

            // Savepoint setelah validasi status berhasil
            $this->conn->exec("SAVEPOINT sp_after_validation");

            // Update status sopir
            $updateSopir = "UPDATE sopir 
                           SET status_sopir = 'Tidak Tersedia' 
                           WHERE id_sopir = :id_sopir";

            $stmtUpdate = $this->conn->prepare($updateSopir);
            $stmtUpdate->bindParam(':id_sopir', $data['id_sopir'], PDO::PARAM_INT);
            $stmtUpdate->execute();

            // Insert data rental
            $insertRental = "INSERT INTO rental 
                            (id_kendaraan, id_sopir, id_pelanggan, 
                             tanggal_sewa, tanggal_kembali) 
                            VALUES 
                            (:id_kendaraan, :id_sopir, :id_pelanggan, 
                             :tanggal_sewa, :tanggal_kembali)";

            $stmtRental = $this->conn->prepare($insertRental);
            $stmtRental->bindParam(':id_kendaraan', $data['id_kendaraan'], PDO::PARAM_INT);
            $stmtRental->bindParam(':id_sopir', $data['id_sopir'], PDO::PARAM_INT);
            $stmtRental->bindParam(':id_pelanggan', $data['id_pelanggan'], PDO::PARAM_INT);
            $stmtRental->bindParam(':tanggal_sewa', $data['tanggal_sewa']);
            $stmtRental->bindParam(':tanggal_kembali', $data['tanggal_kembali']);
            $stmtRental->execute();

            // COMMIT
            $this->conn->commit();

            return true;
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                try {
                    $this->conn->exec("ROLLBACK TO SAVEPOINT sp_after_validation");
                } catch (Exception $savepointError) {
                    // Jika savepoint belum dibuat, rollback total
                    $this->conn->rollback();
                }
            }

            return $e->getMessage();
        }
    }

    // GET ALL DATA
    public function getAllRental($limit, $offset)
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
                  ORDER BY r.id_rental DESC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
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
    // public function searchRental($keyword)
    // {
    //     $query = "SELECT 
    //             r.id_rental,
    //             r.tanggal_sewa,
    //             r.tanggal_kembali,
    //             r.total_biaya,
    //             r.status_rental,
    //             k.plat_nomor,
    //             k.merk,
    //             k.warna,
    //             t.nama_tipe,
    //             s.nama_sopir,
    //             p.nama_pelanggan
    //           FROM " . $this->table_name . " r
    //           LEFT JOIN kendaraan k ON r.id_kendaraan = k.id_kendaraan
    //           LEFT JOIN tipe_kendaraan t ON k.id_tipe = t.id_tipe
    //           LEFT JOIN sopir s ON r.id_sopir = s.id_sopir
    //           LEFT JOIN pelanggan p ON r.id_pelanggan = p.id_pelanggan
    //           WHERE k.plat_nomor ILIKE :wild
    //              OR k.merk ILIKE :wild
    //              OR k.warna ILIKE :wild
    //              OR t.nama_tipe ILIKE :wild
    //              OR s.nama_sopir ILIKE :wild
    //              OR p.searchable @@ plainto_tsquery('simple', :tsquery)
    //           ORDER BY r.id_rental DESC";

    //     $stmt = $this->conn->prepare($query);

    //     $wild = "%{$keyword}%";
    //     $tsquery = $keyword; // tanpa wildcard untuk tsquery

    //     $stmt->bindParam(":wild", $wild);
    //     $stmt->bindParam(":tsquery", $tsquery);

    //     $stmt->execute();
    //     return $stmt;
    // }

    // SEARCH
    public function searchRental($keyword, $limit, $offset)
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
             OR r.status_rental ILIKE :keyword
          ORDER BY r.id_rental DESC
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
                    OR r.status_rental ILIKE :keyword";

        $stmt = $this->conn->prepare($query);
        $kw = "%{$keyword}%";
        $stmt->bindParam(":keyword", $kw);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)$result['total'];
    }

    public function getTotal()
    {
        $query = "SELECT COUNT(*) as total FROM rental";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)$result['total'];
    }

    public function demoRollbackForceError($data)
    {
        $log = [];

        try {
            $log[] = "STEP 1: BEGIN TRANSACTION";
            $this->conn->beginTransaction();

            // Step 2: Update kendaraan
            $log[] = "STEP 2: UPDATE kendaraan (id={$data['id_kendaraan']}) → status='Disewa'";
            $query = "UPDATE kendaraan SET status = 'Disewa' WHERE id_kendaraan = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $data['id_kendaraan']);
            $stmt->execute();
            $log[] = "Kendaraan updated successfully";

            // Step 3: Update sopir
            $log[] = "STEP 3: UPDATE sopir (id={$data['id_sopir']}) → status='Tidak Tersedia'";
            $query = "UPDATE sopir SET status_sopir = 'Tidak Tersedia' WHERE id_sopir = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $data['id_sopir']);
            $stmt->execute();
            $log[] = "Sopir updated successfully";

            // Step 4: FORCE ERROR (untuk demo)
            $log[] = "STEP 4: Simulating error...";
            throw new Exception("DEMO: Forced error untuk demonstrasi ROLLBACK!");

            // Code di bawah ini tidak akan pernah dijalankan
            $this->conn->commit();
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $log[] = "ERROR DETECTED: " . $e->getMessage();
                $log[] = "EXECUTING ROLLBACK...";
                $this->conn->rollback();
                $log[] = "ROLLBACK SUCCESS - All changes REVERTED!";
                $log[] = "Kendaraan dan Sopir kembali ke status semula";
            }

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'log' => $log
            ];
        }
    }

    // Rollback menggunakan Savepoint
    public function demoRollbackSavepoint($data)
    {
        $log = [];

        try {
            $log[] = "STEP 1: BEGIN TRANSACTION";
            $this->conn->beginTransaction();

            // Step 2: Update kendaraan
            $log[] = "STEP 2: UPDATE kendaraan";
            $query = "UPDATE kendaraan SET status = 'Disewa' WHERE id_kendaraan = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $data['id_kendaraan']);
            $stmt->execute();
            $log[] = "Kendaraan updated";

            // Step 3: CREATE SAVEPOINT
            $log[] = "STEP 3: CREATE SAVEPOINT 'sp_after_kendaraan'";
            $this->conn->exec("SAVEPOINT sp_after_kendaraan");
            $log[] = "Savepoint created";

            // Step 4: Update sopir
            $log[] = "STEP 4: UPDATE sopir";
            $query = "UPDATE sopir SET status_sopir = 'Tidak Tersedia' WHERE id_sopir = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $data['id_sopir']);
            $stmt->execute();
            $log[] = "Sopir updated";

            // Step 5: FORCE ERROR
            $log[] = "STEP 5: Simulating error after sopir update...";
            throw new Exception("Error setelah update sopir!");
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $log[] = "ERROR: " . $e->getMessage();

                // Rollback to savepoint (hanya batalkan update sopir)
                try {
                    $log[] = "ROLLBACK TO SAVEPOINT 'sp_after_kendaraan'";
                    $this->conn->exec("ROLLBACK TO SAVEPOINT sp_after_kendaraan");
                    $log[] = "Partial rollback: Sopir update dibatalkan";
                    $log[] = "Kendaraan update MASIH ADA (belum di-rollback)";

                    // Full rollback
                    $log[] = "ROLLBACK FULL TRANSACTION";
                    $this->conn->rollback();
                    $log[] = "Full rollback: SEMUA changes dibatalkan";
                } catch (Exception $rollbackError) {
                    $log[] = "Savepoint error, doing full rollback";
                    $this->conn->rollback();
                }
            }

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'log' => $log
            ];
        }
    }

    // Rollback secara manual
    public function demoRollbackManual($data, $shouldRollback = false)
    {
        $log = [];

        try {
            $log[] = "BEGIN TRANSACTION";
            $this->conn->beginTransaction();

            // Update kendaraan
            $log[] = "UPDATE kendaraan → status='Disewa'";
            $query = "UPDATE kendaraan SET status = 'Disewa' WHERE id_kendaraan = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $data['id_kendaraan']);
            $stmt->execute();
            $log[] = "Kendaraan updated";

            // Update sopir
            $log[] = "UPDATE sopir → status='Tidak Tersedia'";
            $query = "UPDATE sopir SET status_sopir = 'Tidak Tersedia' WHERE id_sopir = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $data['id_sopir']);
            $stmt->execute();
            $log[] = "Sopir updated";

            // Decision point: Commit or Rollback?
            if ($shouldRollback) {
                $log[] = "MANUAL ROLLBACK TRIGGERED by user";
                $log[] = "ROLLBACK...";
                $this->conn->rollback();
                $log[] = "ROLLBACK SUCCESS - Changes reverted";

                return [
                    'success' => false,
                    'message' => 'Manual rollback executed (demo purpose)',
                    'log' => $log,
                    'action' => 'ROLLBACK'
                ];
            } else {
                $log[] = "COMMIT TRANSACTION";
                $this->conn->commit();
                $log[] = "COMMIT SUCCESS - Changes permanent";

                return [
                    'success' => true,
                    'message' => 'Transaction committed successfully',
                    'log' => $log,
                    'action' => 'COMMIT'
                ];
            }
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $log[] = "ERROR: " . $e->getMessage();
                $log[] = "AUTO ROLLBACK";
                $this->conn->rollback();
                $log[] = "Rollback complete";
            }

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'log' => $log,
                'action' => 'AUTO_ROLLBACK'
            ];
        }
    }

    // Rollback dengan proof
    public function demoRollbackWithProof($data)
    {
        $log = [];

        try {
            // BEFORE: Ambil status awal
            $queryBefore = "SELECT status FROM kendaraan WHERE id_kendaraan = :id";
            $stmt = $this->conn->prepare($queryBefore);
            $stmt->bindParam(':id', $data['id_kendaraan']);
            $stmt->execute();
            $statusBefore = $stmt->fetch(PDO::FETCH_ASSOC)['status'];
            $log[] = "BEFORE: Kendaraan status = '{$statusBefore}'";

            // BEGIN TRANSACTION
            $log[] = "BEGIN TRANSACTION";
            $this->conn->beginTransaction();

            // UPDATE
            $log[] = "UPDATE kendaraan → status='Disewa'";
            $query = "UPDATE kendaraan SET status = 'Disewa' WHERE id_kendaraan = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $data['id_kendaraan']);
            $stmt->execute();

            // Check status DURING transaction
            $stmt->execute();
            $queryDuring = "SELECT status FROM kendaraan WHERE id_kendaraan = :id";
            $stmt = $this->conn->prepare($queryDuring);
            $stmt->bindParam(':id', $data['id_kendaraan']);
            $stmt->execute();
            $statusDuring = $stmt->fetch(PDO::FETCH_ASSOC)['status'];
            $log[] = "DURING: Kendaraan status = '{$statusDuring}'";

            // FORCE ERROR
            $log[] = "Simulating error...";
            throw new Exception("Forced error for rollback demo");
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $log[] = "ERROR: " . $e->getMessage();
                $log[] = "ROLLBACK";
                $this->conn->rollback();
            }

            // AFTER: Check status setelah rollback
            $queryAfter = "SELECT status FROM kendaraan WHERE id_kendaraan = :id";
            $stmt = $this->conn->prepare($queryAfter);
            $stmt->bindParam(':id', $data['id_kendaraan']);
            $stmt->execute();
            $statusAfter = $stmt->fetch(PDO::FETCH_ASSOC)['status'];
            $log[] = "AFTER ROLLBACK: Kendaraan status = '{$statusAfter}'";

            // PROOF
            if ($statusBefore === $statusAfter) {
                $log[] = "PROOF: Status kembali ke kondisi awal!";
                $log[] = "ROLLBACK WORKS CORRECTLY!";
            } else {
                $log[] = "WARNING: Status tidak kembali ke kondisi awal!";
            }

            return [
                'success' => false,
                'message' => 'Rollback demo with proof',
                'log' => $log,
                'proof' => [
                    'before' => $statusBefore,
                    'during' => $statusDuring ?? 'N/A',
                    'after' => $statusAfter,
                    'rollback_works' => $statusBefore === $statusAfter
                ]
            ];
        }
    }
}
