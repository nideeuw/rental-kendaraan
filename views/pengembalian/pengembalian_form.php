<?php
$page_title = isset($pengembalian) ? "Edit Pengembalian" : "Tambah Pengembalian";
include __DIR__ . '/../../includes/header.php';
?>

<div class="content-box">
    <div class="content-header">
        <h2><?= isset($pengembalian) ? 'Edit Data Pengembalian' : 'Tambah Data Pengembalian'; ?></h2>
        <a href="index.php?action=pengembalian_list" class="btn btn-secondary">Kembali</a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-grid">

            <div class="form-group">
                <label class="form-label">Tanggal Pengembalian</label>
                <input type="date" name="tanggal_pengembalian" id="tanggal_pengembalian" class="form-input"
                    value="<?= htmlspecialchars($pengembalian['tanggal_pengembalian'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Kondisi Kendaraan</label>
                <select name="kondisi_kendaraan" class="form-input" required>
                    <option value="">-- Pilih Kondisi --</option>
                    <option value="Baik"
                        <?= (isset($pengembalian) && $pengembalian['kondisi_kendaraan'] == 'Baik') ? 'selected' : '' ?>>
                        Baik
                    </option>
                    <option value="Rusak"
                        <?= (isset($pengembalian) && $pengembalian['kondisi_kendaraan'] == 'Rusak') ? 'selected' : '' ?>>
                        Rusak
                    </option>
                </select>
            </div>


            <div class="form-group">
                <label class="form-label">Denda</label>
                <input type="number" name="denda" id="denda" class="form-input"
                    value="<?= htmlspecialchars($pengembalian['denda'] ?? 0) ?>" <?= !isset($pengembalian) ? 'readonly' : '' ?>>
            </div>

            <div class="form-group">
                <label class="form-label">Rental</label>
                <select name="id_rental" id="id_rental" class="form-input" required>
                    <option value="">-- Pilih Rental --</option>
                    <?php while ($row = $rental_list->fetch(PDO::FETCH_ASSOC)): ?>
                        <option value="<?= $row['id_rental'] ?>"
                            data-tanggal-kembali="<?= $row['tanggal_kembali'] ?>"
                            data-tarif="<?= $row['tarif_harian'] ?>"
                            <?= (isset($pengembalian) && $pengembalian['id_rental'] == $row['id_rental']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars("Rental ID {$row['id_rental']} - {$row['plat_nomor']} ({$row['nama_pelanggan']})"); ?>
                        </option>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <?= isset($pengembalian) ? 'Update Data' : 'Simpan Data' ?>
            </button>
            <a href="index.php?action=pengembalian_list" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>