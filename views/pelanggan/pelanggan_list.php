<?php
$page_title = "Data Pelanggan";
include __DIR__ . '/../../includes/header.php';
?>

<?php if (isset($_GET['message'])): ?>
    <?php if ($_GET['message'] == 'created'): ?>
        <div class="alert alert-success">Data Pelanggan berhasil ditambahkan!</div>
    <?php elseif ($_GET['message'] == 'updated'): ?>
        <div class="alert alert-success">Data Pelanggan berhasil diupdate!</div>
    <?php elseif ($_GET['message'] == 'deleted'): ?>
        <div class="alert alert-success">Data Pelanggan berhasil dihapus!</div>
    <?php elseif ($_GET['message'] == 'delete_error'): ?>
        <div class="alert alert-error">Gagal menghapus data pelanggan!</div>
    <?php endif; ?>
<?php endif; ?>

<div class="content-box">
    <div class="content-header">
        <h2>Daftar Pelanggan</h2>
        <a href="index.php?action=pelanggan_create" class="btn btn-primary">Tambah Pelanggan</a>
    </div>

    <div class="search-box">
        <form method="GET" action="index.php">
            <input type="hidden" name="action" value="pelanggan_search">
            <input
                type="text"
                name="keyword"
                class="search-input"
                placeholder="Cari pelanggan..."
                value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
        </form>
    </div>

    <?php if (!empty($pelanggan)): ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pelanggan</th>
                        <th>Alamat</th>
                        <th>No Telepon</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = $pagination['from']; ?>
                    <?php foreach ($pelanggan as $row): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['nama_pelanggan']); ?></td>
                            <td><?= htmlspecialchars($row['alamat']); ?></td>
                            <td><?= htmlspecialchars($row['no_telepon']); ?></td>
                            <td><?= htmlspecialchars($row['email']); ?></td>
                            <td>
                                <div class="btn-group">
                                    <a
                                        href="index.php?action=pelanggan_edit&id=<?= $row['id_pelanggan']; ?>"
                                        class="btn btn-edit">Edit</a>

                                    <a
                                        href="index.php?action=pelanggan_delete&id=<?= $row['id_pelanggan']; ?>"
                                        class="btn btn-delete"
                                        onclick="return confirm('Yakin hapus data ini?')">Hapus</a>


                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php else: ?>
        <div class="empty-state">
            <h3>Belum ada data pelanggan</h3>
            <p>Silakan tambahkan pelanggan baru</p>
        </div>
    <?php endif; ?>

    <!-- Pagination -->
    <?php if (isset($pagination) && $pagination['total'] > 0): ?>
        <?php
        // Build URL berdasarkan context (search atau list)
        if (isset($searchKeyword) && !empty($searchKeyword)) {
            // Kalau dari search
            $url = 'index.php?action=pelanggan_search&keyword=' . urlencode($searchKeyword);
        } else {
            // Kalau dari list biasa
            $url = 'index.php?action=pelanggan_list';
        }
        
        showPagination($pagination, $url);
        ?>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>