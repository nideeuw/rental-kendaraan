<?php
$page_title = isset($tipe) ? "Edit Tipe Kendaraan" : "Tambah Tipe Kendaraan";
include __DIR__ . '/../../includes/header.php';
?>

<div class="content-box">
    <div class="content-header">
        <h2><?php echo isset($tipe) ? 'Edit Data Tipe Kendaraan' : 'Tambah Data Tipe Kendaraan'; ?></h2>
        <a href="index.php?action=tipe_list" class="btn btn-secondary">Kembali</a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-grid">
            <div class="form-group">
                <label for="nama_tipe" class="form-label">Nama Tipe *</label>
                <input type="text" id="nama_tipe" name="nama_tipe" class="form-input"
                    placeholder="Contoh: SUV, MPV, Sedan"
                    value="<?php echo isset($tipe) ? htmlspecialchars($tipe['nama_tipe']) : ''; ?>"
                    required>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <?php echo isset($tipe) ? 'Update Data' : 'Simpan Data'; ?>
            </button>
            <a href="index.php?action=tipe_list" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>