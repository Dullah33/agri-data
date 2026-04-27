<?php
// Panggil controller admin
require __DIR__ . '/../controllers/admin/edit_data_panen.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Data Panen - Admin AgriData</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/sidebar_admin.css">
    <link rel="stylesheet" href="../../assets/css/topbar_admin.css">
    <link rel="stylesheet" href="../../assets/css/edit_data_panen.css">

</head>

<body>

    <?php include 'sidebar_admin.php'; ?>

    <main class="main-content">
        <?php include 'topbar_admin.php'; ?>

        <div class="dashboard-header">
            <div class="header-title-group">
                <h1>Manajemen Data Panen 🌾</h1>
                <p>Kelola entri luas panen, produktivitas, dan produksi padi tingkat provinsi.</p>
            </div>
            <div class="header-action">
                <button class="btn-primary" onclick="openAddModal()">
                    <i class="fa-solid fa-plus"></i> Tambah Data Baru
                </button>
            </div>
        </div>

        <div class="table-section">
            <div class="table-header-admin">
                <h3>Luas Panen, Produksi, dan Produktivitas Padi (2024)</h3>
                <div class="table-controls">
                    <input type="text" id="searchInput" class="search-input" placeholder="Cari provinsi..." onkeyup="searchTable()">

                    <button class="btn-outline" id="btnFilter" onclick="sortProduction()">
                        <i class="fa-solid fa-sort"></i> Urut Produksi
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="admin-data-table">
                    <thead>
                        <tr>
                            <th rowspan="2" class="align-left">38 PROVINSI</th>
                            <th colspan="3" class="text-center bg-blue-header">Rincian Data Panen Padi</th>
                            <th rowspan="2" class="text-center">AKSI</th>
                        </tr>
                        <tr>
                            <th class="align-right bg-blue-sub">Luas Panen (ha)</th>
                            <th class="align-right bg-blue-sub">Produktivitas (ku/ha)</th>
                            <th class="align-right bg-blue-sub">Produksi (ton)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data_panen)): ?>
                            <?php foreach ($data_panen as $row): ?>
                                <tr>
                                    <td class="font-bold"><?php echo htmlspecialchars($row['provinsi']); ?></td>
                                    <td class="align-right font-number"><?php echo number_format($row['luas_panen'], 2, ',', '.'); ?></td>
                                    <td class="align-right font-number"><?php echo number_format($row['produktivitas'], 2, ',', '.'); ?></td>
                                    <td class="align-right font-number font-bold-number"><?php echo number_format($row['produksi'], 2, ',', '.'); ?></td>

                                    <td class="action-cells">
                                        <button type="button" class="btn-icon btn-edit" title="Edit Data"
                                            data-id="<?php echo $row['id']; ?>"
                                            data-prov="<?php echo htmlspecialchars($row['provinsi']); ?>"
                                            data-luas="<?php echo $row['luas_panen']; ?>"
                                            data-prod="<?php echo $row['produktivitas']; ?>"
                                            data-hasil="<?php echo $row['produksi']; ?>"
                                            onclick="openEditModal(this)">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>

                                        <a href="../controllers/admin/hapus_data_panen.php?id=<?php echo $row['id']; ?>" class="btn-icon btn-delete" title="Hapus Data" style="display: flex; text-decoration: none; align-items: center; justify-content: center;" onclick="return confirm('Yakin ingin menghapus data <?php echo htmlspecialchars($row['provinsi']); ?>?');">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div class="modal-overlay" id="editModal">
        <div class="modal-box">
            <div class="modal-header">
                <h3><i class="fa-solid fa-pen-to-square" style="color: #3b82f6;"></i> Edit Data Panen</h3>
                <button type="button" class="btn-close-modal" onclick="closeEditModal()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="../controllers/admin/proses_edit_data_panen.php" method="POST">
                <input type="hidden" name="id" id="edit_id">

                <div class="form-group">
                    <label class="form-label">Nama Provinsi</label>
                    <input type="text" id="edit_provinsi" class="form-control" readonly style="background-color: #f1f5f9; color: #64748b;">
                </div>

                <div class="form-group">
                    <label class="form-label">Luas Panen (Hektar)</label>
                    <input type="number" step="any" name="luas_panen" id="edit_luas" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Produktivitas (Kuintal/Hektar)</label>
                    <input type="number" step="any" name="produktivitas" id="edit_produktivitas" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Produksi Padi (Ton)</label>
                    <input type="number" step="any" name="produksi" id="edit_produksi" class="form-control" required>
                </div>

                <div class="modal-actions" style="display: flex; justify-content: flex-end; gap: 15px; margin-top: 30px;">
                    <button type="button" onclick="closeEditModal()" style="padding: 12px 24px; border-radius: 8px; border: 1px solid #cbd5e1; background: #ffffff; color: #475569; font-weight: 600; cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif; transition: 0.2s;">
                        Batal
                    </button>
                    <button type="submit" style="padding: 12px 24px; border-radius: 8px; border: none; background: #3b82f6; color: white; font-weight: 600; cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif; box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2); transition: 0.2s;">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="addModal">
        <div class="modal-box">
            <div class="modal-header">
                <h3><i class="fa-solid fa-plus" style="color: #10b981;"></i> Tambah Data Panen</h3>
                <button type="button" class="btn-close-modal" onclick="closeAddModal()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="../controllers/admin/proses_tambah_data_panen.php" method="POST">
                <div class="form-group">
                    <label class="form-label">Nama Provinsi</label>
                    <input type="text" name="provinsi" class="form-control" placeholder="Contoh: JAWA TENGAH" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Luas Panen (Hektar)</label>
                    <input type="number" step="any" name="luas_panen" class="form-control" placeholder="Contoh: 150000.5" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Produktivitas (Kuintal/Hektar)</label>
                    <input type="number" step="any" name="produktivitas" class="form-control" placeholder="Contoh: 55.2" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Produksi Padi (Ton)</label>
                    <input type="number" step="any" name="produksi" class="form-control" placeholder="Contoh: 2000000" required>
                </div>

                <div class="modal-actions" style="display: flex; justify-content: flex-end; gap: 15px; margin-top: 30px;">
                    <button type="button" onclick="closeAddModal()" style="padding: 12px 24px; border-radius: 8px; border: 1px solid #cbd5e1; background: #ffffff; color: #475569; font-weight: 600; cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif; transition: 0.2s;">
                        Batal
                    </button>
                    <button type="submit" style="padding: 12px 24px; border-radius: 8px; border: none; background: #10b981; color: white; font-weight: 600; cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif; box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2); transition: 0.2s;">
                        Simpan Data Baru
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="../../assets/js/edit_data_panen_admin.js"></script>

</body>

</html>