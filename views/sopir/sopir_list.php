<?php
$page_title = "Data Sopir";
include __DIR__ . '/../../includes/header.php';
?>

<?php if (isset($_GET['message'])): ?>
    <?php if ($_GET['message'] == 'created'): ?>
        <div class="alert alert-success">Data Sopir berhasil ditambahkan!</div>
    <?php elseif ($_GET['message'] == 'updated'): ?>
        <div class="alert alert-success">Data Sopir berhasil diupdate!</div>
    <?php elseif ($_GET['message'] == 'deleted'): ?>
        <div class="alert alert-success">Data Sopir berhasil dihapus!</div>
    <?php elseif ($_GET['message'] == 'delete_error'): ?>
        <div class="alert alert-error">Gagal menghapus data Sopir!</div>
    <?php endif; ?>
<?php endif; ?>

<div class="content-box">
    <div class="content-header">
        <h2>Daftar Sopir</h2>
        <a href="index.php?action=sopir_create" class="btn btn-primary">Tambah Sopir</a>
    </div>

    <div class="search-box">
        <form method="GET" action="index.php">
            <input type="hidden" name="action" value="sopir_search">
            <input 
                type="text" 
                name="keyword" 
                class="search-input" 
                placeholder="Cari Sopir..."
                value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>"
            >
        </form>
    </div>

    <?php if ($sopir->rowCount() > 0): ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Sopir</th>
                        <th>No Sim</th>
                        <th>Tarif Harian</th>
                        <th>Status Sopir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php while ($row = $sopir->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['nama_sopir']); ?></td>
                            <td><?= htmlspecialchars($row['no_sim']); ?></td>
                            <td><?= htmlspecialchars($row['tarif_harian']); ?></td>
                            <td><?= htmlspecialchars($row['status_sopir']); ?></td>
                            <td>
                                <div class="btn-group">
                                    <a 
                                        href="index.php?action=sopir_edit&id=<?= $row['id_sopir']; ?>" 
                                        class="btn btn-edit">Edit</a>

                                    <a 
                                        href="index.php?action=sopir_delete&id=<?= $row['id_sopir']; ?>" 
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
            <h3>Tidak ada data Sopir</h3>
            <p>Silakan tambahkan Sopir baru</p>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
