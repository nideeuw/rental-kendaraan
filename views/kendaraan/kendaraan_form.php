<?php
$page_title = isset($kendaraan) ? "Edit Kendaraan" : "Tambah Kendaraan";
include __DIR__ . '/../../includes/header.php';
?>

<div class="content-box">
    <div class="content-header">
        <h2><?php echo isset($kendaraan) ? 'Edit Data Kendaraan' : 'Tambah Data Kendaraan'; ?></h2>
        <a href="index.php?action=kendaraan_list" class="btn btn-secondary">Kembali</a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        
        <div class="form-grid">

            <div class="form-group">
                <label class="form-label">Plat Nomor</label>
                <input type="text" name="plat_nomor" class="form-input"
                    value="<?= htmlspecialchars($kendaraan['plat_nomor'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Merk</label>
                <input type="text" name="merk" class="form-input"
                    value="<?= htmlspecialchars($kendaraan['merk'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Warna</label>
                <input type="text" name="warna" class="form-input"
                    value="<?= htmlspecialchars($kendaraan['warna'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-input" required>
                    <option value="Tersedia" <?= isset($kendaraan) && $kendaraan['status'] == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                    <option value="Disewa" <?= isset($kendaraan) && $kendaraan['status'] == 'Disewa' ? 'selected' : '' ?>>Disewa</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Kapasitas</label>
                <input type="number" name="kapasitas" class="form-input"
                    value="<?= htmlspecialchars($kendaraan['kapasitas'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Tarif Harian</label>
                <input type="number" name="tarif_harian" class="form-input"
                    value="<?= htmlspecialchars($kendaraan['tarif_harian'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Tipe Kendaraan</label>
                <select name="id_tipe" class="form-input" required>
                    <option value="">-- Pilih Tipe --</option>
                    <?php while ($row = $tipe_list->fetch(PDO::FETCH_ASSOC)): ?>
                        <option 
                            value="<?= $row['id_tipe']; ?>" 
                            <?= (isset($kendaraan) && $kendaraan['id_tipe'] == $row['id_tipe']) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($row['nama_tipe']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <?= isset($kendaraan) ? 'Update Data' : 'Simpan Data' ?>
            </button>
            <a href="index.php?action=kendaraan_list" class="btn btn-secondary">Batal</a>
        </div>

    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
