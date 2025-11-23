<?php
$page_title = isset($pelanggan) ? "Edit Pelanggan" : "Tambah Pelanggan";
include __DIR__ . '/../../includes/header.php';
?>

<div class="content-box">
    <div class="content-header">
        <h2><?php echo isset($pelanggan) ? 'Edit Data Pelanggan' : 'Tambah Data Pelanggan'; ?></h2>
        <a href="index.php?action=pelanggan_list" class="btn btn-secondary">Kembali</a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        
        <div class="form-grid">

            <div class="form-group">
                <label class="form-label">Nama Pelanggan</label>
                <input type="text" name="nama_pelanggan" class="form-input"
                    value="<?= htmlspecialchars($pelanggan['nama_pelanggan'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Alamat</label>
                <input type="text" name="alamat" class="form-input"
                    value="<?= htmlspecialchars($pelanggan['alamat'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">No Telepon</label>
                <input type="text" name="no_telepon" class="form-input"
                    value="<?= htmlspecialchars($pelanggan['no_telepon'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input"
                    value="<?= htmlspecialchars($pelanggan['email'] ?? '') ?>" required>
            </div>

        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <?= isset($pelanggan) ? 'Update Data' : 'Simpan Data' ?>
            </button>
            <a href="index.php?action=pelanggan_list" class="btn btn-secondary">Batal</a>
        </div>

    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
