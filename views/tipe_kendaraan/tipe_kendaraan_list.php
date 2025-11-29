<?php
$page_title = "Data Tipe Kendaraan";
include __DIR__ . '/../../includes/header.php';
?>

<?php if (isset($_GET['message'])): ?>
    <?php if ($_GET['message'] == 'created'): ?>
        <div class="alert alert-success">Data tipe kendaraan berhasil ditambahkan!</div>
    <?php elseif ($_GET['message'] == 'updated'): ?>
        <div class="alert alert-success">Data tipe kendaraan berhasil diupdate!</div>
    <?php elseif ($_GET['message'] == 'deleted'): ?>
        <div class="alert alert-success">Data tipe kendaraan berhasil dihapus!</div>
    <?php elseif ($_GET['message'] == 'delete_error'): ?>
        <div class="alert alert-error">Gagal menghapus data tipe kendaraan!</div>
    <?php elseif ($_GET['message'] == 'search_error'): ?>
        <div class="alert alert-error">Tipe kendaraan tidak ditemukan!</div>
    <?php endif; ?>
<?php endif; ?>

<div class="content-box">
    <div class="content-header">
        <h2>Daftar Tipe Kendaraan</h2>
        <a href="index.php?action=tipe_kendaraan_create" class="btn btn-primary">Tambah Tipe Kendaraan</a>
    </div>

    <div class="search-box">
        <form method="GET" action="index.php">
            <input type="hidden" name="action" value="tipe_kendaraan_search">
            <input
                type="text"
                name="keyword"
                class="search-input"
                placeholder="Cari Nama Tipe Kendaraan..."
                value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
        </form>
    </div>

    <?php if ($tipe->rowCount() > 0): ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Tipe Kendaraan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = $pagination['from'];
                    while ($row = $tipe->fetch(PDO::FETCH_ASSOC)):
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['nama_tipe']); ?></strong></td>
                            <td>
                                <div class="btn-group">
                                    <a
                                        href="index.php?action=tipe_kendaraan_edit&id=<?php echo $row['id_tipe']; ?>"
                                        class="btn btn-edit">Edit</a>

                                    <a
                                        href="index.php?action=tipe_kendaraan_delete&id=<?php echo $row['id_tipe']; ?>"
                                        class="btn btn-delete"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <h3>Tidak ada data tipe kendaraan</h3>
            <p>Silakan tambahkan tipe kendaraan baru</p>
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
            $url = 'index.php?action=tipe_list';
        }
        
        showPagination($pagination, $url);
        ?>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>