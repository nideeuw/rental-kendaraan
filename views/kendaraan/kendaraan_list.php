<?php
$page_title = "Data Kendaraan";
include __DIR__ . '/../../includes/header.php';
?>

<?php if (isset($_GET['message'])): ?>
    <?php if ($_GET['message'] == 'created'): ?>
        <div class="alert alert-success">Data kendaraan berhasil ditambahkan!</div>
    <?php elseif ($_GET['message'] == 'updated'): ?>
        <div class="alert alert-success">Data kendaraan berhasil diupdate!</div>
    <?php elseif ($_GET['message'] == 'deleted'): ?>
        <div class="alert alert-success">Data kendaraan berhasil dihapus!</div>
    <?php elseif ($_GET['message'] == 'delete_error'): ?>
        <div class="alert alert-error">Gagal menghapus data kendaraan!</div>
    <?php endif; ?>
<?php endif; ?>

<div class="content-box">
    <div class="content-header">
        <h2>Daftar Kendaraan</h2>
        <a href="index.php?action=kendaraan_create" class="btn btn-primary">Tambah Kendaraan</a>
    </div>

    <div class="search-box">
        <form method="GET" action="index.php">
            <input type="hidden" name="action" value="kendaraan_search">
            <input 
                type="text" 
                name="keyword" 
                class="search-input" 
                placeholder="Cari kendaraan..."
                value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>"
            >
        </form>
    </div>

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
                                    <a 
                                        href="index.php?action=kendaraan_edit&id=<?= $row['id_kendaraan']; ?>" 
                                        class="btn btn-edit"
                                    >Edit</a>

                                    <a 
                                        href="index.php?action=kendaraan_delete&id=<?= $row['id_kendaraan']; ?>" 
                                        class="btn btn-delete"
                                        onclick="return confirm('Yakin hapus data ini?')"
                                    >Hapus</a>
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
