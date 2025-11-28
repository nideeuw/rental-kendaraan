<?php
$page_title = "Daftar Sopir Tersedia";
include __DIR__ . '/../../includes/header.php';
?>

<div class="content-box">
    <div class="content-header">
        <h2>Daftar Sopir Tersedia</h2>
        <a href="index.php?action=sopir_list" class="btn btn-primary">Kembali</a>
    </div>

    <table class="data-table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Sopir</th>
                <th>No SIM</th>
                <th>Tarif Harian</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $no = 1;
            foreach ($sopir as $row): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['nama_sopir']; ?></td>
                    <td><?= $row['no_sim']; ?></td>
                    <td><?= $row['tarif_harian']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>