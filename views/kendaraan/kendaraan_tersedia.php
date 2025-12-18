<?php
$page_title = "Daftar Kendaraan Tersedia";
include __DIR__ . '/../../includes/header.php';

$searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';
$filterStatus = isset($_GET['status']) ? $_GET['status'] : '';
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'id_kendaraan';
$sortOrder = isset($_GET['order']) ? $_GET['order'] : 'DESC';
?>

<div class="content-box">
    <div class="content-header">
        <h2>Daftar Kendaraan Tersedia</h2>
        <a href="index.php?action=kendaraan_list" class="btn btn-primary">Kembali</a>
    </div>

    <!-- Search dan Filter -->
    <div class="search-filter-box">
        <form method="GET" action="index.php" class="filter-form" id="filterForm">
            <input type="hidden" name="action" value="kendaraan_tersedia">

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
                    placeholder="Search..."
                    value="<?= htmlspecialchars($searchKeyword) ?>">
            </div>

            <!-- Filter Status -->
            <!-- <div class="filter-col">
                <label class="filter-label">
                    <span class="label-icon">📊</span>
                    <span>Status</span>
                </label>
                <select name="status" class="form-select auto-submit">
                    <option value="">Semua Status</option>
                    <option value="Tersedia" <?= $filterStatus == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                    <option value="Disewa" <?= $filterStatus == 'Disewa' ? 'selected' : '' ?>>Disewa</option>
                </select>
            </div> -->

            <!-- Sort By -->
            <div class="filter-col">
                <label class="filter-label">
                    <span class="label-icon">📈</span>
                    <span>Urutkan</span>
                </label>
                <select name="sort" class="form-select auto-submit">
                    <option value="id_kendaraan" <?= $sortBy == 'id_kendaraan' ? 'selected' : '' ?>>ID Kendaraan</option>
                    <option value="plat_nomor" <?= $sortBy == 'plat_nomor' ? 'selected' : '' ?>>Plat Nomor</option>
                    <option value="merk" <?= $sortBy == 'merk' ? 'selected' : '' ?>>Merk Kendaraan</option>
                </select>
            </div>

            <!-- Sort Order -->
            <div class="filter-col">
                <label class="filter-label">
                    <span class="label-icon">⬆️⬇️</span>
                    <span>Urutan</span>
                </label>
                <select name="order" class="form-select auto-submit">
                    <option value="DESC" <?= $sortOrder == 'DESC' ? 'selected' : '' ?>>Z-A / Besar-Kecil</option>
                    <option value="ASC" <?= $sortOrder == 'ASC' ? 'selected' : '' ?>>A-Z / Kecil-Besar</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Active Filters Info -->
    <?php if ($searchKeyword): ?>
        <div class="filter-info">
            <div class="filter-info-text">
                <strong>Filter Aktif:</strong>
                <?php if ($searchKeyword): ?>
                    <span class="info-tag">🔍 "<?= htmlspecialchars($searchKeyword) ?>"</span>
                <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="table-container">
        <table class="data-table">
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
                <?php if (empty($kendaraan)): ?>
                    <tr>
                        <td colspan="5" style="text-align:center">
                            <div class="empty-state">
                                <div class="empty-icon">🚗</div>
                                <h3>Tidak ada kendaraan tersedia</h3>
                                <p>Tidak ada kendaraan yang sesuai dengan filter</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php
                    $no = $pagination['from'];
                    foreach ($kendaraan as $row):
                    ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['id_kendaraan']); ?></td>
                            <td><?= htmlspecialchars($row['plat_nomor']); ?></td>
                            <td><?= htmlspecialchars($row['merk']); ?></td>
                            <td>
                                <span class="badge badge-<?= $row['status'] == 'Tersedia' ? 'success' : 'warning'; ?>">
                                    <?= htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php
    $queryParams = http_build_query([
        'action' => 'kendaraan_tersedia',
        'search' => $searchKeyword,
        'sort' => $sortBy,
        'order' => $sortOrder
    ]);
    showPagination($pagination, 'index.php?' . $queryParams);
    ?>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>