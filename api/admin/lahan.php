<?php
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../config/koneksi.php';

$page = 'data_lahan';

// SIMPAN
if (isset($_POST['simpan'])) {

    // PHP sudah aman, jika input di form HTML dihapus, otomatis terisi default
    $nama = mysqli_real_escape_string($conn, $_POST['nama_pemilik'] ?? '');
    $provinsi = mysqli_real_escape_string($conn, $_POST['provinsi'] ?? '');
    $desa = mysqli_real_escape_string($conn, $_POST['desa'] ?? '');
    $luas = !empty($_POST['luas']) ? $_POST['luas'] : 0;
    $komoditas = mysqli_real_escape_string($conn, $_POST['komoditas'] ?? '');
    $status = $_POST['status'] ?? 'aktif';
    $masa = mysqli_real_escape_string($conn, $_POST['masa_tanam'] ?? '');
    $hasil = !empty($_POST['hasil_per_ha']) ? $_POST['hasil_per_ha'] : 0;
    $total = !empty($_POST['total_panen']) ? $_POST['total_panen'] : 0;

    mysqli_query($conn, "INSERT INTO lahan_petani 
    (nama_pemilik, luas, desa, provinsi, komoditas, status, masa_tanam, hasil_per_ha, total_panen)
    VALUES ('$nama','$luas','$desa','$provinsi','$komoditas','$status','$masa','$hasil','$total')");

    header("Location: /admin/lahan");
    exit;
}

// HAPUS
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM lahan_petani WHERE id=$id");

    header("Location: /admin/lahan");
    exit;
}

$data = mysqli_query($conn, "SELECT * FROM lahan_petani ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Lahan | AgriData</title>

    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/sidebar_admin.css">
    <link rel="stylesheet" href="/assets/css/topbar_admin.css">
    <link rel="stylesheet" href="/assets/css/admin_lahan.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="page-lahan">

    <div class="layout-wrapper">

        <?php include __DIR__ . '/partials/sidebar_admin.php'; ?>

        <div class="main-content">

            <?php include __DIR__ . '/partials/topbar_admin.php'; ?>

            <div class="content-area">

                <div class="lahan-layout">

                    <div class="lahan-form-box">
                        <h3><i class="fa fa-plus"></i> Tambah Data</h3>
                        <form method="POST">
                            <div class="form-group">
                                <label>Nama Pemilik</label>
                                <input type="text" name="nama_pemilik" required placeholder="Nama lengkap...">
                            </div>
                            <div class="form-group">
                                <label>Provinsi</label>
                                <input type="text" name="provinsi" required placeholder="Contoh: Jawa Barat">
                            </div>
                            <div class="form-group">
                                <label>Desa</label>
                                <input type="text" name="desa" required placeholder="Contoh: Sukamaju">
                            </div>
                            <div class="form-group">
                                <label>Luas (ha)</label>
                                <input type="number" step="0.01" name="luas">
                            </div>
                            <div class="form-group">
                                <label>Komoditas</label>
                                <input type="text" name="komoditas">
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <select name="status">
                                    <option value="aktif">Aktif</option>
                                    <option value="tidak aktif">Nonaktif</option>
                                </select>
                            </div>
                            <button name="simpan" class="btn-primary">
                                <i class="fa fa-save"></i> Simpan Data
                            </button>
                        </form>
                    </div>

                    <div class="lahan-table-box">
                        <div class="table-header">
                            <h3><i class="fa fa-table"></i> Daftar Lahan Pertanian</h3>
                        </div>

                        <div class="table-container">
                            <div class="table-scroll">
                                <table class="table-modern">
                                    <thead>
                                        <tr>
                                            <th>Pemilik</th>
                                            <th>Provinsi</th>
                                            <th>Desa</th>
                                            <th>Luas</th>
                                            <th>Komoditas</th>
                                            <th>Status</th>
                                            <th style="text-align: center;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (mysqli_num_rows($data) > 0): ?>
                                            <?php while ($d = mysqli_fetch_assoc($data)): ?>
                                                <tr>
                                                    <td><strong><?= $d['nama_pemilik'] ?></strong></td>
                                                    <td><?= $d['provinsi'] ?></td>
                                                    <td><?= $d['desa'] ?></td>
                                                    <td><?= $d['luas'] ?> ha</td>
                                                    <td><?= $d['komoditas'] ?></td>
                                                    <td>
                                                        <span class="badge <?= $d['status'] == 'aktif' ? 'aktif' : 'nonaktif' ?>">
                                                            <?= $d['status'] ?>
                                                        </span>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <a href="?hapus=<?= $d['id'] ?>" class="btn-delete" onclick="return confirm('Hapus data ini?')">
                                                            <i class="fa fa-trash"></i> Hapus
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7" style="text-align:center; padding: 40px; color: #94a3b8;">
                                                    <i class="fa fa-folder-open" style="font-size: 24px; display: block; margin-bottom: 10px;"></i>
                                                    Belum ada data lahan tersedia.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>

</html>