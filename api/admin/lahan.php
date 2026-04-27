<?php
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../config/koneksi.php';

$page = 'data_lahan';

// SIMPAN
if (isset($_POST['simpan'])) {

    $nama = mysqli_real_escape_string($conn, $_POST['nama_pemilik']);
    $provinsi = mysqli_real_escape_string($conn, $_POST['provinsi']);
    $desa = mysqli_real_escape_string($conn, $_POST['desa']);
    $luas = !empty($_POST['luas']) ? $_POST['luas'] : 0;
    $komoditas = mysqli_real_escape_string($conn, $_POST['komoditas']);
    $status = $_POST['status'];
    $masa = mysqli_real_escape_string($conn, $_POST['masa_tanam']);
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

<body>

    <div class="layout-wrapper">

        <?php include __DIR__ . '/partials/sidebar_admin.php'; ?>

        <div class="main-content">

            <?php include __DIR__ . '/partials/topbar_admin.php'; ?>

            <div class="content-area">

                <!-- HEADER -->
                <div class="page-header">
                    <div>
                        <h1>Data Lahan</h1>
                        <p>Kelola seluruh data lahan petani AgriData</p>
                    </div>
                </div>

                <!-- GRID -->
                <div class="lahan-grid">

                    <!-- FORM -->
                    <div class="card form-card">
                        <h3 class="section-title">
                            <i class="fa fa-plus"></i> Tambah Data
                        </h3>

                        <form method="POST">

                            <div class="form-grid">

                                <input type="text" name="nama_pemilik" placeholder="Nama Pemilik" required>
                                <input type="text" name="provinsi" placeholder="Provinsi" required>

                                <input type="text" name="desa" placeholder="Desa">
                                <input type="number" name="luas" placeholder="Luas (ha)">

                                <input type="text" name="komoditas" placeholder="Komoditas">
                                <input type="text" name="masa_tanam" placeholder="Masa Tanam">

                                <input type="number" name="hasil_per_ha" placeholder="Hasil / ha">
                                <input type="number" name="total_panen" placeholder="Total Panen">

                                <select name="status">
                                    <option value="aktif">Aktif</option>
                                    <option value="tidak aktif">Nonaktif</option>
                                </select>

                            </div>

                            <button name="simpan" class="btn-primary full">
                                <i class="fa fa-save"></i> Simpan Data
                            </button>

                        </form>
                    </div>

                    <!-- TABLE -->
                    <div class="card table-card">

                        <h3 class="section-title">
                            <i class="fa fa-table"></i> Daftar Lahan
                        </h3>

                        <table class="table-modern">

                            <thead>
                                <tr>
                                    <th>Pemilik</th>
                                    <th>Lokasi</th>
                                    <th>Luas</th>
                                    <th>Komoditas</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (mysqli_num_rows($data) > 0): ?>
                                    <?php while ($d = mysqli_fetch_assoc($data)): ?>

                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($d['nama_pemilik']) ?></strong><br>
                                                <small><?= htmlspecialchars($d['desa']) ?></small>
                                            </td>

                                            <td><?= htmlspecialchars($d['provinsi']) ?></td>
                                            <td><?= $d['luas'] ?> ha</td>
                                            <td><?= htmlspecialchars($d['komoditas']) ?></td>

                                            <td>
                                                <span class="badge <?= $d['status'] == 'aktif' ? 'aktif' : 'nonaktif' ?>">
                                                    <?= ucfirst($d['status']) ?>
                                                </span>
                                            </td>

                                            <td>
                                                <a href="?hapus=<?= $d['id'] ?>" class="btn-delete" onclick="return confirm('Hapus data?')">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>

                                    <?php endwhile; ?>
                                <?php else: ?>

                                    <tr>
                                        <td colspan="6" class="empty">
                                            Data belum tersedia
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

</body>

</html>