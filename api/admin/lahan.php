<?php
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../config/koneksi.php';

$page = 'data_lahan';

// ======================
// SIMPAN DATA
// ======================
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

// ======================
// HAPUS DATA
// ======================
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM lahan_petani WHERE id=$id");

    header("Location: /admin/lahan");
    exit;
}

// ======================
// AMBIL DATA
// ======================
$data = mysqli_query($conn, "SELECT * FROM lahan_petani ORDER BY provinsi ASC, nama_pemilik ASC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Lahan | AgriData</title>

    <!-- ICON & FONT -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS GLOBAL -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/sidebar_admin.css">
    <link rel="stylesheet" href="/assets/css/topbar_admin.css">

    <!-- CSS HALAMAN -->
    <link rel="stylesheet" href="/assets/css/admin_lahan.css">
</head>

<body>

    <div class="layout-wrapper">

        <!-- SIDEBAR -->
        <?php include __DIR__ . '/partials/sidebar_admin.php'; ?>

        <div class="main-content">

            <!-- TOPBAR -->
            <?php include __DIR__ . '/partials/topbar_admin.php'; ?>

            <!-- CONTENT -->
            <div class="content-area">

                <div class="container">

                    <h2 class="page-title">
                        <i class="fa-solid fa-seedling"></i> Kelola Data Lahan
                    </h2>

                    <!-- FORM -->
                    <div class="card">
                        <form method="POST" class="form-grid">

                            <input name="nama_pemilik" placeholder="Nama Pemilik" required>
                            <input name="provinsi" placeholder="Provinsi" required>
                            <input name="desa" placeholder="Desa">

                            <input name="luas" type="number" step="0.01" placeholder="Luas (ha)">
                            <input name="komoditas" placeholder="Komoditas">
                            <input name="masa_tanam" placeholder="Masa Tanam">

                            <input name="hasil_per_ha" type="number" step="0.01" placeholder="Hasil/ha">
                            <input name="total_panen" type="number" step="0.01" placeholder="Total Panen">

                            <select name="status">
                                <option value="aktif">Aktif</option>
                                <option value="tidak aktif">Tidak Aktif</option>
                            </select>

                            <button name="simpan" class="btn-primary">
                                <i class="fa-solid fa-plus"></i> Tambah Data
                            </button>

                        </form>
                    </div>

                    <!-- TABLE -->
                    <div class="card">
                        <table class="table-modern">

                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Provinsi</th>
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
                                            <td><?= htmlspecialchars($d['nama_pemilik']) ?></td>
                                            <td><?= htmlspecialchars($d['provinsi']) ?></td>
                                            <td><?= $d['luas'] ?> ha</td>
                                            <td><?= htmlspecialchars($d['komoditas']) ?></td>
                                            <td>
                                                <span class="badge <?= $d['status'] == 'aktif' ? 'aktif' : 'nonaktif' ?>">
                                                    <?= ucfirst($d['status']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a class="btn-delete"
                                                    href="?hapus=<?= $d['id'] ?>"
                                                    onclick="return confirm('Yakin hapus data ini?')">
                                                    <i class="fa-solid fa-trash"></i> Hapus
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" style="text-align:center; padding:20px;">
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