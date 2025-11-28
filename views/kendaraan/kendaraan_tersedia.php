<?php
$page_title = "Daftar Kendaraan Tersedia";
include __DIR__ . '/../../includes/header.php';
?>

<div class="content-box">
    <div class="content-header">
        <h2>Daftar Kendaraan Tersedia</h2>
        <a href="index.php?action=kendaraan_list" class="btn btn-primary">Kembali</a>
    </div>

    <table class="data-table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>ID Kendaraan</th>
                <th>Plat Nomor</th>
                <th>Merk</th>
                <th>Status</th>

            </tr>
        </thead>

        <tbody>
            <?php
            $no = 1;
            foreach ($kendaraan as $row): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['id_kendaraan']; ?></td>
                    <td><?= $row['plat_nomor']; ?></td>
                    <td><?= $row['merk']; ?></td>
                    <td><?= $row['status']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>