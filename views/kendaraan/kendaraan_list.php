<?php
$page_title = "Data Kendaraan";
include __DIR__ . '/../../includes/header.php';
?>

<!-- NOTIFIKASI (SUCCESS / ERROR) -->
<?php if (!empty($_SESSION['notif'])): ?>
    <div class="alert alert-<?= $_SESSION['notif']['type']; ?>">
        <?= $_SESSION['notif']['message']; ?>
    </div>
    <?php unset($_SESSION['notif']); ?>
<?php endif; ?>

<div class="content-box">
    <div class="content-header">
        <h2>Daftar Kendaraan</h2>
        <a href="index.php?action=kendaraan_create" class="btn btn-primary">Tambah Kendaraan</a>
    </div>

    <!-- SEARCH BOX -->
    <div class="search-box">
        <form method="GET" action="index.php">
            <input type="hidden" name="action" value="kendaraan_search">
            <input
                type="text"
                name="keyword"
                class="search-input"
                placeholder="Cari kendaraan..."
                value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
        </form>
    </div>

    <!-- TABEL DATA -->
    <?php if ($kendaraan->rowCount() > 0): ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Plat Nomor</th>
                        <th>Merk</th>
                        <th>Warna</th>
                        <th>Status</th>
                        <th>Kapasitas</th>
                        <th>Tarif Harian</th>
                        <th>Tipe</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $no = 1; ?>
                    <?php while ($row = $kendaraan->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['plat_nomor']); ?></td>
                            <td><?= htmlspecialchars($row['merk']); ?></td>
                            <td><?= htmlspecialchars($row['warna']); ?></td>
                            <td><?= htmlspecialchars($row['status']); ?></td>
                            <td><?= htmlspecialchars($row['kapasitas']); ?> orang</td>
                            <td>Rp <?= htmlspecialchars($row['tarif_harian']); ?> / hari</td>
                            <td><?= htmlspecialchars($row['nama_tipe']); ?></td>

                            <td>
                                <div class="btn-group">
                                    <!-- Edit -->
                                    <a
                                        href="index.php?action=kendaraan_edit&id=<?= $row['id_kendaraan']; ?>"
                                        class="btn btn-edit">Edit</a>

                                    <!-- Hapus -->
                                    <a
                                        href="index.php?action=kendaraan_delete&id=<?= $row['id_kendaraan']; ?>"
                                        class="btn btn-delete"
                                        onclick="return confirm('Yakin hapus data ini?')">
                                        Hapus
                                    </a>

                                    <!-- Set Disewa -->
                                    <a
                                        href="index.php?action=kendaraan_ubah_status&id=<?= $row['id_kendaraan'] ?>&status=Disewa"
                                        class="btn btn-warning btn-sm">
                                        Set Disewa
                                    </a>

                                    <!-- Set Tersedia -->
                                    <a
                                        href="index.php?action=kendaraan_ubah_status&id=<?= $row['id_kendaraan'] ?>&status=Tersedia"
                                        class="btn btn-success btn-sm">
                                        Set Tersedia
                                    </a>
                                </div>
                            </td>

                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    <?php else: ?>
        <div class="empty-state">
            <h3>Tidak ada data kendaraan</h3>
            <p>Silakan tambahkan kendaraan baru</p>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>