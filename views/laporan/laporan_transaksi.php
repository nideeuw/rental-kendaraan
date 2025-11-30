<?php
$page_title = "Laporan Transaksi";
include __DIR__ . '/../../includes/header.php';

$searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';
$filterStatus = isset($_GET['status']) ? $_GET['status'] : '';
$filterKondisi = isset($_GET['kondisi']) ? $_GET['kondisi'] : '';
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'tanggal_sewa';
$sortOrder = isset($_GET['order']) ? $_GET['order'] : 'DESC';
?>

<div class="content-box">
    <div class="content-header">
        <h2>Laporan Transaksi</h2>
        <a href="index.php?action=refresh_laporan" class="btn btn-primary">Refresh</a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">✅ <?= htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>

    <!-- Search dan Filter -->
    <div class="search-filter-box">
        <form method="GET" action="index.php" class="filter-form" id="filterForm">
            <input type="hidden" name="action" value="laporan_transaksi">

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
            <div class="filter-col">
                <label class="filter-label">
                    <span class="label-icon">📊</span>
                    <span>Status</span>
                </label>
                <select name="status" class="form-select auto-submit">
                    <option value="">Semua Status</option>
                    <option value="Tepat Waktu" <?= $filterStatus == 'Tepat Waktu' ? 'selected' : '' ?>>Tepat Waktu</option>
                    <option value="Terlambat" <?= $filterStatus == 'Terlambat' ? 'selected' : '' ?>>Terlambat</option>
                </select>
            </div>

            <!-- Filter Kondisi -->
            <div class="filter-col">
                <label class="filter-label">
                    <span class="label-icon">🚗</span>
                    <span>Kondisi</span>
                </label>
                <select name="kondisi" class="form-select auto-submit">
                    <option value="">Semua Kondisi</option>
                    <option value="Baik" <?= $filterKondisi == 'Baik' ? 'selected' : '' ?>>Baik</option>
                    <option value="Rusak" <?= $filterKondisi == 'Rusak' ? 'selected' : '' ?>>Rusak</option>
                </select>
            </div>

            <!-- Sort By -->
            <div class="filter-col">
                <label class="filter-label">
                    <span class="label-icon">📈</span>
                    <span>Urutkan</span>
                </label>
                <select name="sort" class="form-select auto-submit">
                    <option value="tarif_harian" <?= $sortBy == 'tarif_harian' ? 'selected' : '' ?>>Tarif Harian</option>
                    <option value="tanggal_sewa" <?= $sortBy == 'tanggal_sewa' ? 'selected' : '' ?>>Tanggal Sewa</option>
                    <option value="tanggal_kembali" <?= $sortBy == 'tanggal_kembali' ? 'selected' : '' ?>>Tanggal Kembali</option>
                    <option value="total_biaya" <?= $sortBy == 'total_biaya' ? 'selected' : '' ?>>Total Biaya</option>
                    <option value="tanggal_pengembalian" <?= $sortBy == 'tanggal_pengembalian' ? 'selected' : '' ?>>Tanggal Pengembalian</option>
                    <option value="denda" <?= $sortBy == 'denda' ? 'selected' : '' ?>>Denda</option>
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
    <?php if ($searchKeyword || $filterStatus || $filterKondisi): ?>
        <div class="filter-info">
            <div class="filter-info-text">
                <strong>Filter Aktif:</strong>
                <?php if ($searchKeyword): ?>
                    <span class="info-tag">🔍 "<?= htmlspecialchars($searchKeyword) ?>"</span>
                <?php endif; ?>
                <?php if ($filterStatus): ?>
                    <span class="info-tag">📊 <?= htmlspecialchars($filterStatus) ?></span>
                <?php endif; ?>
                <?php if ($filterKondisi): ?>
                    <span class="info-tag">🚗 <?= htmlspecialchars($filterKondisi) ?></span>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- TABLE -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pelanggan</th>
                    <th>Sopir</th>
                    <th>Tipe Kendaraan</th>
                    <th>Plat Nomor</th>
                    <th>Merk</th>
                    <th>Tarif Harian</th>
                    <th>Kondisi Kendaraan</th>
                    <th>Tanggal Sewa</th>
                    <th>Tanggal Kembali</th>
                    <th>Durasi Sewa</th>
                    <th>Total Biaya Rental</th>
                    <th>Tanggal Pengembalian</th>
                    <th>Denda</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($laporanTransaksi)): ?>
                    <tr>
                        <td colspan="15" style="text-align:center">Tidak ada data</td>
                    </tr>
                <?php else: ?>
                    <?php
                    $no = $pagination['from'];
                    foreach ($laporanTransaksi as $row):
                    ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['nama_pelanggan'] ?? '-'); ?></td>
                            <td><?= htmlspecialchars($row['nama_sopir'] ?? '-'); ?></td>
                            <td><?= htmlspecialchars($row['nama_tipe'] ?? '-'); ?></td>
                            <td><?= htmlspecialchars($row['plat_nomor'] ?? '-'); ?></td>
                            <td><?= htmlspecialchars($row['merk'] ?? '-'); ?></td>
                            <td>Rp <?= number_format($row['tarif_harian'] ?? 0, 0, ',', '.'); ?></td>
                            <td>
                                <span class="badge badge-<?= ($row['kondisi_kendaraan'] ?? '') == 'Baik' ? 'success' : 'danger'; ?>">
                                    <?= htmlspecialchars($row['kondisi_kendaraan'] ?? '-'); ?>
                                </span>
                            </td>
                            <td><?= isset($row['tanggal_sewa']) ? date('d/m/Y', strtotime($row['tanggal_sewa'])) : '-'; ?></td>
                            <td><?= isset($row['tanggal_kembali']) ? date('d/m/Y', strtotime($row['tanggal_kembali'])) : '-'; ?></td>
                            <td><?= ($row['durasi_sewa_hari'] ?? 0) . ' hari'; ?></td>
                            <td>Rp <?= number_format($row['total_biaya'] ?? 0, 0, ',', '.'); ?></td>
                            <td><?= isset($row['tanggal_pengembalian']) && $row['tanggal_pengembalian'] ? date('d/m/Y', strtotime($row['tanggal_pengembalian'])) : '-'; ?></td>
                            <td>
                                <?php $denda = $row['denda'] ?? 0; ?>
                                <strong style="color: <?= $denda > 0 ? 'red' : 'green'; ?>">
                                    Rp <?= number_format($denda, 0, ',', '.'); ?>
                                </strong>
                            </td>
                            <td>
                                <?php
                                $status = $row['status_pengembalian'] ?? '-';
                                if ($status == 'Tepat Waktu') {
                                    $statusClass = 'success';
                                } elseif ($status == 'Terlambat') {
                                    $statusClass = 'danger';
                                } else {
                                    $statusClass = 'secondary';
                                }
                                ?>
                                <span class="badge badge-<?= $statusClass; ?>"><?= htmlspecialchars($status); ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php
    // Buat base URL dengan semua filter params
    $paginationParams = [
        'search' => $searchKeyword,
        'status' => $filterStatus,
        'kondisi' => $filterKondisi,
        'sort' => $sortBy,
        'order' => $sortOrder
    ];

    // Hapus params kosong
    $paginationParams = array_filter($paginationParams);

    // Buat query string
    $queryString = http_build_query($paginationParams);
    $url = 'index.php?action=laporan_transaksi' . ($queryString ? '&' . $queryString : '');

    showPagination($pagination, $url);
    ?>

</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>