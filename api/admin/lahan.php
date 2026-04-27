<?php
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../config/koneksi.php';

$page = 'data_lahan';

// SIMPAN
if (isset($_POST['simpan'])) {

    $nama = mysqli_real_escape_string($conn, $_POST['nama_pemilik']);
    $provinsi = mysqli_real_escape_string($conn, $_POST['provinsi']);
    $desa = mysqli_real_escape_string($conn, $_POST['desa']);
    $luas = $_POST['luas'] ?: 0;
    $komoditas = mysqli_real_escape_string($conn, $_POST['komoditas']);
    $status = $_POST['status'];
    $masa = mysqli_real_escape_string($conn, $_POST['masa_tanam']);
    $hasil = $_POST['hasil_per_ha'] ?: 0;
    $total = $_POST['total_panen'] ?: 0;

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

$data = mysqli_query($conn, "SELECT * FROM lahan_petani ORDER BY provinsi ASC, nama_pemilik ASC");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Data Lahan</title>
    <link rel="stylesheet" href="/assets/css/admin_lahan.css">
</head>

<body>

    <div class="layout-wrapper">

        <?php include __DIR__ . '/partials/sidebar_admin.php'; ?>

        <div class="main-content">

            <?php include __DIR__ . '/partials/topbar_admin.php'; ?>

            <div class="content-area">

                <h2 class="page-title">🌾 Kelola Data Lahan</h2>

                <!-- FORM -->
                <div class="card">
                    <form method="POST" class="form-grid">
                        <input name="nama_pemilik" placeholder="Nama Pemilik" required>
                        <input name="provinsi" placeholder="Provinsi" required>
                        <input name="desa" placeholder="Desa">
                        <input name="luas" placeholder="Luas (ha)" type="number" step="0.01">
                        <input name="komoditas" placeholder="Komoditas">
                        <input name="masa_tanam" placeholder="Masa Tanam">
                        <input name="hasil_per_ha" placeholder="Hasil/ha" type="number">
                        <input name="total_panen" placeholder="Total Panen" type="number">

                        <select name="status">
                            <option value="aktif">Aktif</option>
                            <option value="tidak aktif">Tidak Aktif</option>
                        </select>

                        <button name="simpan" class="btn-primary">+ Tambah Data</button>
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
                            <?php while ($d = mysqli_fetch_assoc($data)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($d['nama_pemilik']) ?></td>
                                    <td><?= htmlspecialchars($d['provinsi']) ?></td>
                                    <td><?= $d['luas'] ?> ha</td>
                                    <td><?= htmlspecialchars($d['komoditas']) ?></td>
                                    <td>
                                        <span class="badge <?= $d['status'] == 'aktif' ? 'aktif' : 'nonaktif' ?>">
                                            <?= $d['status'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a class="btn-delete" href="?hapus=<?= $d['id'] ?>" onclick="return confirm('Hapus data?')">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </div>

</body>

</html>