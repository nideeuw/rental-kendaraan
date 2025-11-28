<?php
$page_title = "Laporan Transaksi";
include __DIR__ . '/../../includes/header.php';
?>

<div class="content-box">
    <div class="content-header">
        <h2>Laporan Transaksi</h2>
        <a href="index.php?action=refresh_laporan" class="btn btn-primary">Refresh</a>
    </div>

    <table class="data-table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pelanggan</th>
                <th>Nama Sopir</th>
                <th>Tipe Kendaraan</th>
                <th>Plat Nomor</th>
                <th>Merk</th>
                <th>Tarif Harian</th>
                <th>Kondisi Kendaraan</th>
                <th>Tanggal Sewa</th>
                <th>Tanggal Kembali</th>
                <th>Durasi Sewa</th>
                <th>Total Biaya</th>
                <th>Tanggal Pengembalian</th>
                <th>Denda</th>
                <th>Status Pengembalian</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $no = 1;
            foreach ($laporanTransaksi as $row): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['nama_pelanggan']; ?></td>
                    <td><?= $row['nama_sopir']; ?></td>
                    <td><?= $row['nama_tipe']; ?></td>
                    <td><?= $row['plat_nomor']; ?></td>
                    <td><?= $row['merk']; ?></td>
                    <td><?= $row['tarif_harian']; ?></td>
                    <td><?= $row['kondisi_kendaraan']; ?></td>
                    <td><?= $row['tanggal_sewa']; ?></td>
                    <td><?= $row['tanggal_kembali']; ?></td>
                    <td><?= $row['durasi_sewa_hari']; ?></td>
                    <td><?= $row['total_biaya']; ?></td>
                    <td><?= $row['tanggal_pengembalian']; ?></td>
                    <td><?= $row['denda']; ?></td>
                    <td><?= $row['status_pengembalian']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>