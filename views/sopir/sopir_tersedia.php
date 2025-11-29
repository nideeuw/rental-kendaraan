<?php
$page_title = "Daftar Sopir Tersedia";
include __DIR__ . '/../../includes/header.php';

$searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'nama_sopir';
$sortOrder = isset($_GET['order']) ? $_GET['order'] : 'ASC';
?>

<div class="content-box">
    <div class="content-header">
        <h2>Daftar Sopir Tersedia</h2>
        <a href="index.php?action=sopir_list" class="btn btn-primary">Kembali</a>
    </div>

    <!-- Search dan Filter  -->
    <div class="filter-box-modern">
        <form method="GET" action="index.php" id="filterForm">
            <input type="hidden" name="action" value="sopir_tersedia">

            <div class="filter-grid-3">
                <!-- Search Box -->
                <div class="filter-col filter-col-search">
                    <label class="filter-label">
                        <span class="label-icon">🔍</span>
                        <span>Pencarian</span>
                    </label>
                    <input
                        type="text"
                        name="search"
                        id="searchInput"
                        class="form-input"
                        placeholder="Cari Nama Sopir atau No SIM..."
                        value="<?= htmlspecialchars($searchKeyword) ?>">
                </div>

                <!-- Sort By -->
                <div class="filter-col">
                    <label class="filter-label">
                        <span class="label-icon">📈</span>
                        <span>Urutkan</span>
                    </label>
                    <select name="sort" class="form-select auto-submit">
                        <option value="nama_sopir" <?= $sortBy == 'nama_sopir' ? 'selected' : '' ?>>Nama Sopir</option>
                        <option value="no_sim" <?= $sortBy == 'no_sim' ? 'selected' : '' ?>>No SIM</option>
                        <option value="tarif_harian" <?= $sortBy == 'tarif_harian' ? 'selected' : '' ?>>Tarif Harian</option>
                    </select>
                </div>

                <!-- Sort Order -->
                <div class="filter-col">
                    <label class="filter-label">
                        <span class="label-icon">⬆️⬇️</span>
                        <span>Urutan</span>
                    </label>
                    <select name="order" class="form-select auto-submit">
                        <option value="ASC" <?= $sortOrder == 'ASC' ? 'selected' : '' ?>>A-Z / Kecil-Besar</option>
                        <option value="DESC" <?= $sortOrder == 'DESC' ? 'selected' : '' ?>>Z-A / Besar-Kecil</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Active Filters Info -->
    <?php if ($searchKeyword): ?>
        <div class="filter-info">
            <div class="filter-info-text">
                <strong>Filter Aktif:</strong>
                <span class="info-tag">🔍 "<?= htmlspecialchars($searchKeyword) ?>"</span>
            </div>
        </div>
    <?php endif; ?>

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Sopir</th>
                    <th>No SIM</th>
                    <th>Tarif Harian</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($sopir)): ?>
                    <tr>
                        <td colspan="4" style="text-align:center">
                            <div class="empty-state">
                                <div class="empty-icon">👨‍✈️</div>
                                <h3>Tidak ada sopir tersedia</h3>
                                <p>Tidak ada sopir yang sesuai dengan filter</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php
                    $no = $pagination['from'];
                    foreach ($sopir as $row):
                    ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['nama_sopir']); ?></td>
                            <td><?= htmlspecialchars($row['no_sim']); ?></td>
                            <td>Rp <?= number_format($row['tarif_harian'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php
    $queryParams = http_build_query([
        'action' => 'sopir_tersedia',
        'search' => $searchKeyword,
        'sort' => $sortBy,
        'order' => $sortOrder
    ]);
    showPagination($pagination, 'index.php?' . $queryParams);
    ?>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>