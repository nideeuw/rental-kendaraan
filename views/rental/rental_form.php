<?php
$page_title = isset($rental) ? "Edit Rental" : "Tambah Rental";
include __DIR__ . '/../../includes/header.php';
?>

<div class="content-box">
    <div class="content-header">
        <h2><?= isset($rental) ? 'Edit Data Rental' : 'Tambah Data Rental'; ?></h2>
        <a href="index.php?action=rental_list" class="btn btn-secondary">Kembali</a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-grid">

            <div class="form-group">
                <label class="form-label">Tanggal Sewa</label>
                <input type="date" name="tanggal_sewa" class="form-input"
                    value="<?= htmlspecialchars($rental['tanggal_sewa'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Tanggal Kembali</label>
                <input type="date" name="tanggal_kembali" class="form-input"
                    value="<?= htmlspecialchars($rental['tanggal_kembali'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Total Biaya</label>
                <input type="number" name="total_biaya" class="form-input"
                    value="<?= htmlspecialchars($rental['total_biaya'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Status Rental</label>
                <select name="status_rental" class="form-input" required>
                    <option value="Belum Kembali" <?= isset($rental) && $rental['status_rental'] == 'Belum Kembali' ? 'selected' : '' ?>>Aktif</option>
                    <option value="Sudah Kembali" <?= isset($rental) && $rental['status_rental'] == 'Sudah Kembali' ? 'selected' : '' ?>>Selesai</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Kendaraan</label>
                <select name="id_kendaraan" class="form-input" required>
                    <option value="">-- Pilih Kendaraan --</option>
                    <?php while ($row = $kendaraan_list->fetch(PDO::FETCH_ASSOC)): ?>
                        <option 
                            value="<?= $row['id_kendaraan']; ?>" 
                            <?= (isset($rental) && $rental['id_kendaraan'] == $row['id_kendaraan']) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($row['plat_nomor'] . " - " . $row['merk']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Sopir</label>
                <select name="id_sopir" class="form-input" required>
                    <option value="">-- Pilih Sopir --</option>
                    <?php while ($row = $sopir_list->fetch(PDO::FETCH_ASSOC)): ?>
                        <option 
                            value="<?= $row['id_sopir']; ?>" 
                            <?= (isset($rental) && $rental['id_sopir'] == $row['id_sopir']) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($row['nama_sopir']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Pelanggan</label>
                <select name="id_pelanggan" class="form-input" required>
                    <option value="">-- Pilih Pelanggan --</option>
                    <?php while ($row = $pelanggan_list->fetch(PDO::FETCH_ASSOC)): ?>
                        <option 
                            value="<?= $row['id_pelanggan']; ?>" 
                            <?= (isset($rental) && $rental['id_pelanggan'] == $row['id_pelanggan']) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($row['nama_pelanggan']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <?= isset($rental) ? 'Update Data' : 'Simpan Data' ?>
            </button>
            <a href="index.php?action=rental_list" class="btn btn-secondary">Batal</a>
        </div>

    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
