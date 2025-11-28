//<?php 
$page_title = "Hasil Total Denda";
include __DIR__ . '/../../includes/header.php';
?>

<div class="content-box">
    <div class="content-header">
        <h2>Hasil Perhitungan Total Denda</h2>
        <a href="index.php?action=pelanggan_total_denda" class="btn btn-secondary">Kembali</a>
    </div>

    <p><strong>ID Pelanggan:</strong> <?= htmlspecialchars($_POST['id_pelanggan']); ?></p>
    <p><strong>Total Denda:</strong> 
        Rp <?= isset($result['total_denda']) ? number_format($result['total_denda'], 0, ',', '.') : '0'; ?>
    </p>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
