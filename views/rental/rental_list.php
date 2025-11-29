<?php
$page_title = "Data Rental";
include __DIR__ . '/../../includes/header.php';
?>

<?php if (isset($_GET['message'])): ?>
    <?php if ($_GET['message'] == 'created'): ?>
        <div class="alert alert-success">Data rental berhasil ditambahkan!</div>
    <?php elseif ($_GET['message'] == 'updated'): ?>
        <div class="alert alert-success">Data rental berhasil diupdate!</div>
    <?php elseif ($_GET['message'] == 'deleted'): ?>
        <div class="alert alert-success">Data rental berhasil dihapus!</div>
    <?php elseif ($_GET['message'] == 'delete_error'): ?>
        <div class="alert alert-error">Gagal menghapus data rental!</div>
    <?php endif; ?>
<?php endif; ?>

<div class="content-box">
    <div class="content-header">
        <h2>Daftar Rental</h2>
        <a href="index.php?action=rental_create" class="btn btn-primary">Tambah Rental</a>
    </div>

    <div class="search-box">
        <form method="GET" action="index.php">
            <input type="hidden" name="action" value="rental_search">
            <input
                type="text"
                name="keyword"
                class="search-input"
                placeholder="Cari rental..."
                value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
        </form>
    </div>

    <?php if ($rental->rowCount() > 0): ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pelanggan</th>
                        <th>Sopir</th>
                        <th>Kendaraan</th>
                        <th>Tanggal Sewa</th>
                        <th>Tanggal Kembali</th>
                        <th>Total Biaya</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = $pagination['from']; ?>
                    <?php while ($row = $rental->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['nama_pelanggan']); ?></td>
                            <td><?= htmlspecialchars($row['nama_sopir']); ?></td>
                            <td><?= htmlspecialchars($row['plat_nomor']); ?></td>
                            <td><?= htmlspecialchars($row['tanggal_sewa']); ?></td>
                            <td><?= htmlspecialchars($row['tanggal_kembali']); ?></td>
                            <td>Rp <?= htmlspecialchars($row['total_biaya']); ?></td>
                            <td><?= htmlspecialchars($row['status_rental']); ?></td>
                            <td>
                                <div class="btn-group">
                                    <a
                                        href="index.php?action=rental_edit&id=<?= $row['id_rental']; ?>"
                                        class="btn btn-edit">Edit</a>

                                    <a
                                        href="index.php?action=rental_delete&id=<?= $row['id_rental']; ?>"
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
            <h3>Tidak ada data rental</h3>
            <p>Silakan tambahkan rental baru</p>
        </div>
    <?php endif; ?>

    <!-- Pagination -->
    <?php if (isset($pagination) && $pagination['total'] > 0): ?>
        <?php
        // Build URL berdasarkan context (search atau list)
        if (isset($searchKeyword) && !empty($searchKeyword)) {
            // Kalau dari search
            $url = 'index.php?action=rental_search&keyword=' . urlencode($searchKeyword);
        } else {
            // Kalau dari list biasa
            $url = 'index.php?action=rental_list';
        }
        
        showPagination($pagination, $url);
        ?>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>