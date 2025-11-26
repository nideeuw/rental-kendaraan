<?php 
$page_title = "Cek Total Denda";
include __DIR__ . '/../../includes/header.php';
?>

<div class="content-box">
    <div class="content-header">
        <h2>Cek Total Denda Pelanggan</h2>
        <a href="index.php?action=pelanggan_list" class="btn btn-secondary">Kembali</a>
    </div>

    <form method="POST" action="index.php?action=pelanggan_total_denda">
        <div class="form-group">
            <label class="form-label">ID Pelanggan</label>
            <input type="number" name="id_pelanggan" class="form-input" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Cek Denda</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
