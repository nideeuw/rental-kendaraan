<?php
$page_title = isset($sopir) ? "Edit Sopir" : "Tambah Sopir";
include __DIR__ . '/../../includes/header.php';
?>

<div class="content-box">
    <div class="content-header">
        <h2><?php echo isset($sopir) ? 'Edit Data sopir' : 'Tambah Data sopir'; ?></h2>
        <a href="index.php?action=sopir_list" class="btn btn-secondary">Kembali</a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        
        <div class="form-grid">

            <div class="form-group">
                <label class="form-label">Nama Sopir</label>
                <input type="text" name="nama_sopir" class="form-input"
                    value="<?= htmlspecialchars($sopir['nama_sopir'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">No Sim</label>
                <input type="text" name="no_sim" class="form-input"
                    value="<?= htmlspecialchars($sopir['no_sim'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Tarif Harian</label>
                <input type="text" name="tarif_harian" class="form-input"
                    value="<?= htmlspecialchars($sopir['tarif_harian'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Status Sopir</label>
                <select name="status_sopir" class="form-input" required>
                    <option value="Tersedia" <?= isset($sopir) && $sopir['status_sopir'] == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                    <option value="Disewa" <?= isset($sopir) && $sopir['status_sopir'] == 'Disewa' ? 'selected' : '' ?>>Disewa</option>
                </select>
            </div>

        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <?= isset($sopir) ? 'Update Data' : 'Simpan Data' ?>
            </button>
            <a href="index.php?action=sopir_list" class="btn btn-secondary">Batal</a>
        </div>

    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
