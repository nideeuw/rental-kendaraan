<?php
$page_title = "Dashboard";
include __DIR__ . '/../includes/header.php';
?>

<?php if (isset($stats['error'])): ?>
    <div class="alert alert-error">
        ⚠️ Error: <?= htmlspecialchars($stats['error']) ?>
    </div>
<?php endif; ?>

<!-- Stats Cards -->
<div class="dashboard-stats">
    <div class="stats-grid">
        <div class="stat-card stat-primary">
            <div class="stat-icon">🚗</div>
            <div class="stat-content">
                <h3><?= number_format($stats['total_kendaraan'] ?? 0); ?></h3>
                <p>Total Kendaraan</p>
                <small><?= $stats['kendaraan_tersedia'] ?? 0; ?> tersedia | <?= $stats['kendaraan_disewa'] ?? 0; ?> disewa</small>
            </div>
        </div>

        <div class="stat-card stat-success">
            <div class="stat-icon">👨‍✈️</div>
            <div class="stat-content">
                <h3><?= number_format($stats['total_sopir'] ?? 0); ?></h3>
                <p>Total Sopir</p>
                <small><?= $stats['sopir_tersedia'] ?? 0; ?> sopir tersedia</small>
            </div>
        </div>

        <div class="stat-card stat-info">
            <div class="stat-icon">👥</div>
            <div class="stat-content">
                <h3><?= number_format($stats['total_pelanggan'] ?? 0); ?></h3>
                <p>Total Pelanggan</p>
                <small>Pelanggan terdaftar</small>
            </div>
        </div>

        <div class="stat-card stat-warning">
            <div class="stat-icon">📋</div>
            <div class="stat-content">
                <h3><?= number_format($stats['rental_aktif'] ?? 0); ?></h3>
                <p>Rental Aktif</p>
                <small>Sedang berlangsung</small>
            </div>
        </div>

        <div class="stat-card stat-revenue">
            <div class="stat-icon">💰</div>
            <div class="stat-content">
                <h3>Rp <?= number_format($stats['total_pendapatan'] ?? 0, 0, ',', '.'); ?></h3>
                <p>Pendapatan Bulan Ini</p>
                <small><?= date('F Y'); ?></small>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Pendapatan Per Tahun -->
<div class="content-box">
    <div class="content-header">
        <h2>📊 Statistik Pendapatan Per Tahun</h2>
        <a href="index.php?action=laporan_pendapatan" class="btn btn-primary">Lihat Detail →</a>
    </div>

    <?php if (!empty($statistikPendapatan)): ?>
        <div class="stats-table">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tahun</th>
                        <th>Total Rental</th>
                        <th>Total Denda</th>
                        <th>Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($statistikPendapatan as $stat): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($stat['tahun']); ?></strong></td>
                            <td>Rp <?= number_format($stat['total_rental'], 0, ',', '.'); ?></td>
                            <td>
                                <span style="color: <?= $stat['total_denda'] > 0 ? 'red' : 'green'; ?>">
                                    Rp <?= number_format($stat['total_denda'], 0, ',', '.'); ?>
                                </span>
                            </td>
                            <td>
                                <strong style="color: #1d3557;">
                                    Rp <?= number_format($stat['total_rental_denda'], 0, ',', '.'); ?>
                                </strong>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">📊</div>
            <h3>Belum Ada Data Statistik</h3>
            <p>Belum ada transaksi yang tercatat</p>
        </div>
    <?php endif; ?>
</div>

<!-- Quick Actions -->
<div class="content-box">
    <div class="content-header">
        <h2>⚡ Quick Actions</h2>
    </div>

    <div class="quick-actions">
        <a href="index.php?action=rental_create" class="action-card">
            <div class="action-icon">➕</div>
            <h3>Rental Baru</h3>
            <p>Tambah transaksi rental</p>
        </a>

        <a href="index.php?action=kendaraan_tersedia" class="action-card">
            <div class="action-icon">🔍</div>
            <h3>Cek Ketersediaan</h3>
            <p>Lihat kendaraan tersedia</p>
        </a>

        <a href="index.php?action=pengembalian_list" class="action-card">
            <div class="action-icon">↩️</div>
            <h3>Pengembalian</h3>
            <p>Proses pengembalian</p>
        </a>

        <a href="index.php?action=laporan_transaksi" class="action-card">
            <div class="action-icon">📊</div>
            <h3>Laporan</h3>
            <p>Lihat laporan transaksi</p>
        </a>
    </div>
</div>

<!-- Recent Rentals -->
<div class="content-box">
    <div class="content-header">
        <h2>📋 Rental Terbaru</h2>
        <a href="index.php?action=rental_list" class="btn btn-secondary">Lihat Semua →</a>
    </div>

    <?php if (!empty($recentRentals)): ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Pelanggan</th>
                        <th>Kendaraan</th>
                        <th>Tanggal Sewa</th>
                        <th>Tanggal Kembali</th>
                        <th>Total Biaya</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentRentals as $rental): ?>
                        <tr>
                            <td><?= htmlspecialchars($rental['nama_pelanggan']); ?></td>
                            <td><?= htmlspecialchars($rental['merk'] . ' - ' . $rental['plat_nomor']); ?></td>
                            <td><?= date('d/m/Y', strtotime($rental['tanggal_sewa'])); ?></td>
                            <td><?= date('d/m/Y', strtotime($rental['tanggal_kembali'])); ?></td>
                            <td><strong>Rp <?= number_format($rental['total_biaya'], 0, ',', '.'); ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">🔭</div>
            <h3>Belum Ada Rental</h3>
            <p>Belum ada transaksi rental yang tercatat</p>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>