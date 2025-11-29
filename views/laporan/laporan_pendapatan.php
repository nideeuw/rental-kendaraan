<?php
$page_title = "Laporan Pendapatan Per Tahun";
include __DIR__ . '/../../includes/header.php';

// Set default values jika belum ada
$statistikPendapatan = isset($statistikPendapatan) ? $statistikPendapatan : [];
$tahunList = isset($tahunList) ? $tahunList : [];

// Ambil filter dari URL
$filterTahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';
$filterTahunDari = isset($_GET['tahun_dari']) ? $_GET['tahun_dari'] : '';
$filterTahunSampai = isset($_GET['tahun_sampai']) ? $_GET['tahun_sampai'] : '';
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'tahun';
$sortOrder = isset($_GET['order']) ? $_GET['order'] : 'DESC';
?>

<div class="content-box">
    <div class="content-header">
        <h2>📊 Laporan Pendapatan Per Tahun</h2>
        <a href="index.php?action=dashboard" class="btn btn-secondary">← Kembali ke Dashboard</a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">✅ <?= htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error">⚠️ <?= htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-error">⚠️ Error: <?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Filter -->
    <div class="search-filter-box">
        <form method="GET" action="index.php" class="filter-form" id="filterForm">
            <input type="hidden" name="action" value="laporan_pendapatan">

            <!-- Filter Tahun Spesifik -->
            <div class="filter-col">
                <label class="filter-label">
                    <span class="label-icon">📅</span>
                    <span>Tahun</span>
                </label>
                <select name="tahun" class="form-select auto-submit">
                    <option value="">Semua Tahun</option>
                    <?php foreach ($tahunList as $tahun): ?>
                        <option value="<?= $tahun ?>" <?= $filterTahun == $tahun ? 'selected' : '' ?>>
                            <?= $tahun ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Filter Rentang Tahun -->
            <div class="filter-col">
                <label class="filter-label">
                    <span class="label-icon">📆</span>
                    <span>Dari Tahun</span>
                </label>
                <input 
                    type="number" 
                    name="tahun_dari" 
                    class="form-input"
                    placeholder="0000"
                    min="2000"
                    max="2100"
                    value="<?= htmlspecialchars($filterTahunDari) ?>">
            </div>

            <div class="filter-col">
                <label class="filter-label">
                    <span class="label-icon">📆</span>
                    <span>Sampai Tahun</span>
                </label>
                <input 
                    type="number" 
                    name="tahun_sampai" 
                    class="form-input"
                    placeholder="0000"
                    min="2000"
                    max="2100"
                    value="<?= htmlspecialchars($filterTahunSampai) ?>">
            </div>

            <!-- Sort By -->
            <div class="filter-col">
                <label class="filter-label">
                    <span class="label-icon">📈</span>
                    <span>Urutkan</span>
                </label>
                <select name="sort" class="form-select auto-submit">
                    <option value="tahun" <?= $sortBy == 'tahun' ? 'selected' : '' ?>>Tahun</option>
                    <option value="total_rental" <?= $sortBy == 'total_rental' ? 'selected' : '' ?>>Pendapatan Rental</option>
                    <option value="total_denda" <?= $sortBy == 'total_denda' ? 'selected' : '' ?>>Total Denda</option>
                    <option value="total_rental_denda" <?= $sortBy == 'total_rental_denda' ? 'selected' : '' ?>>Total Keseluruhan</option>
                </select>
            </div>

            <!-- Sort Order -->
            <div class="filter-col">
                <label class="filter-label">
                    <span class="label-icon">⬆️⬇️</span>
                    <span>Urutan</span>
                </label>
                <select name="order" class="form-select auto-submit">
                    <option value="DESC" <?= $sortOrder == 'DESC' ? 'selected' : '' ?>>Besar ke Kecil</option>
                    <option value="ASC" <?= $sortOrder == 'ASC' ? 'selected' : '' ?>>Kecil ke Besar</option>
                </select>
            </div>

            <!-- Tombol Reset -->
            <div class="filter-col">
                <label class="filter-label" style="visibility: hidden;">Reset</label>
                <a href="index.php?action=laporan_pendapatan" class="btn btn-secondary" style="width: 100%;">
                    🔄 Reset Filter
                </a>
            </div>
        </form>
    </div>

    <!-- Active Filters Info -->
    <?php if ($filterTahun || $filterTahunDari || $filterTahunSampai): ?>
        <div class="filter-info">
            <div class="filter-info-text">
                <strong>Filter Aktif:</strong>
                <?php if ($filterTahun): ?>
                    <span class="info-tag">📅 Tahun: <?= htmlspecialchars($filterTahun) ?></span>
                <?php endif; ?>
                <?php if ($filterTahunDari): ?>
                    <span class="info-tag">📆 Dari: <?= htmlspecialchars($filterTahunDari) ?></span>
                <?php endif; ?>
                <?php if ($filterTahunSampai): ?>
                    <span class="info-tag">📆 Sampai: <?= htmlspecialchars($filterTahunSampai) ?></span>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (count($statistikPendapatan) > 0): ?>
        <!-- Summary Cards -->
        <div class="revenue-summary">
            <?php 
            $totalSemuaRental = array_sum(array_column($statistikPendapatan, 'total_rental'));
            $totalSemuaDenda = array_sum(array_column($statistikPendapatan, 'total_denda'));
            $totalSemuaPendapatan = array_sum(array_column($statistikPendapatan, 'total_rental_denda'));
            ?>
            
            <div class="summary-card">
                <div class="summary-content">
                    <h4>Total Pendapatan Rental</h4>
                    <h2>Rp <?= number_format($totalSemuaRental, 0, ',', '.'); ?></h2>
                    <small><?= count($statistikPendapatan) ?> tahun</small>
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-content">
                    <h4>Total Denda</h4>
                    <h2 style="color: #ef4444;">Rp <?= number_format($totalSemuaDenda, 0, ',', '.'); ?></h2>
                    <small><?= number_format(($totalSemuaDenda/$totalSemuaPendapatan)*100, 1) ?>% dari total</small>
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-content">
                    <h4>Total Keseluruhan</h4>
                    <h2 style="color: #10b981;">Rp <?= number_format($totalSemuaPendapatan, 0, ',', '.'); ?></h2>
                    <small>Rental + Denda</small>
                </div>
            </div>
        </div>

        <!-- Detailed Table -->
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tahun</th>
                        <th>Total Pendapatan Rental</th>
                        <th>Total Denda</th>
                        <th>Total Keseluruhan</th>
                        <th>Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($statistikPendapatan as $stat): ?>
                        <?php 
                        $persentase = $totalSemuaPendapatan > 0 
                            ? ($stat['total_rental_denda'] / $totalSemuaPendapatan) * 100 
                            : 0;
                        ?>
                        <tr>
                            <td><strong style="font-size: 16px;"><?= htmlspecialchars($stat['tahun']); ?></strong></td>
                            <td>
                                <span style="color: #1d3557; font-weight: 600;">
                                    Rp <?= number_format($stat['total_rental'], 0, ',', '.'); ?>
                                </span>
                            </td>
                            <td>
                                <span style="color: <?= $stat['total_denda'] > 0 ? '#ef4444' : '#10b981'; ?>; font-weight: 600;">
                                    Rp <?= number_format($stat['total_denda'], 0, ',', '.'); ?>
                                </span>
                            </td>
                            <td>
                                <strong style="color: #10b981; font-size: 16px;">
                                    Rp <?= number_format($stat['total_rental_denda'], 0, ',', '.'); ?>
                                </strong>
                            </td>
                            <td>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?= number_format($persentase, 2); ?>%"></div>
                                    <span class="progress-text"><?= number_format($persentase, 1); ?>%</span>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr style="background: #f8f9fa; font-weight: bold;">
                        <td>TOTAL</td>
                        <td>Rp <?= number_format($totalSemuaRental, 0, ',', '.'); ?></td>
                        <td>Rp <?= number_format($totalSemuaDenda, 0, ',', '.'); ?></td>
                        <td>Rp <?= number_format($totalSemuaPendapatan, 0, ',', '.'); ?></td>
                        <td>100%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">📊</div>
            <h3>Belum Ada Data Statistik</h3>
            <p>Belum ada transaksi yang tercatat<?= $filterTahun ? ' untuk tahun ' . $filterTahun : '' ?></p>
            <?php if ($filterTahun || $filterTahunDari || $filterTahunSampai): ?>
                <a href="index.php?action=laporan_pendapatan" class="btn btn-primary" style="margin-top: 20px;">
                    Lihat Semua Data
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>