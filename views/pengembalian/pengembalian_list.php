<?php
$page_title = "Data Pengembalian";
include __DIR__ . '/../../includes/header.php';
?>

<?php if (isset($_GET['message'])): ?>
    <?php if ($_GET['message'] == 'created'): ?>
        <div class="alert alert-success">Data pengembalian berhasil ditambahkan!</div>
    <?php elseif ($_GET['message'] == 'updated'): ?>
        <div class="alert alert-success">Data pengembalian berhasil diupdate!</div>
    <?php elseif ($_GET['message'] == 'deleted'): ?>
        <div class="alert alert-success">Data pengembalian berhasil dihapus!</div>
    <?php elseif ($_GET['message'] == 'delete_error'): ?>
        <div class="alert alert-error">Gagal menghapus data pengembalian!</div>
    <?php endif; ?>
<?php endif; ?>

<div class="content-box">
    <div class="content-header">
        <h2>Daftar Pengembalian</h2>
        <a href="index.php?action=pengembalian_create" class="btn btn-primary">Tambah Pengembalian</a>
    </div>

    <div class="search-box">
        <form method="GET" action="index.php">
            <input type="hidden" name="action" value="pengembalian_search">
            <input type="text" name="keyword" class="search-input" placeholder="Cari pengembalian..."
                value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
        </form>
    </div>

    <?php if ($pengembalian->rowCount() > 0): ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Rental ID</th>
                        <th>Pelanggan</th>
                        <th>Sopir</th>
                        <th>Plat Kendaraan</th>
                        <th>Tanggal Pengembalian</th>
                        <th>Kondisi Kendaraan</th>
                        <th>Denda</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = $pagination['from']; ?>
                    <?php while ($row = $pengembalian->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['id_rental']); ?></td>
                            <td><?= htmlspecialchars($row['nama_pelanggan']); ?></td>
                            <td><?= htmlspecialchars($row['nama_sopir']); ?></td>
                            <td><?= htmlspecialchars($row['plat_nomor']); ?></td>
                            <td><?= htmlspecialchars($row['tanggal_pengembalian']); ?></td>
                            <td><?= htmlspecialchars($row['kondisi_kendaraan']); ?></td>
                            <td>Rp <?= htmlspecialchars($row['denda']); ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="index.php?action=pengembalian_edit&id=<?= $row['id_pengembalian']; ?>"
                                        class="btn btn-edit">Edit</a>
                                    <a href="index.php?action=pengembalian_delete&id=<?= $row['id_pengembalian']; ?>"
                                        class="btn btn-delete"
                                        onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <h3>Tidak ada data pengembalian</h3>
            <p>Silakan tambahkan data pengembalian baru</p>
        </div>
    <?php endif; ?>

    <!-- Pagination -->
    <?php if (isset($pagination) && $pagination['total'] > 0): ?>
        <?php
        // Build URL berdasarkan context (search atau list)
        if (isset($searchKeyword) && !empty($searchKeyword)) {
            // Kalau dari search
            $url = 'index.php?action=kendaraan_search&keyword=' . urlencode($searchKeyword);
        } else {
            // Kalau dari list biasa
            $url = 'index.php?action=pengembalian_list';
        }
        
        showPagination($pagination, $url);
        ?>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>