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

            <div class="lahan-layout">

                <!-- FORM -->
                <div class="lahan-form-box">
                    <h3><i class="fa fa-plus"></i> Tambah Data</h3>

                    <form method="POST">

                        <div class="form-group">
                            <label>Nama Pemilik</label>
                            <input type="text" name="nama_pemilik" required>
                        </div>

                        <div class="form-group">
                            <label>Provinsi</label>
                            <input type="text" name="provinsi" required>
                        </div>

                        <div class="form-group">
                            <label>Desa</label>
                            <input type="text" name="desa">
                        </div>

                        <div class="form-group">
                            <label>Luas (ha)</label>
                            <input type="number" name="luas">
                        </div>

                        <div class="form-group">
                            <label>Komoditas</label>
                            <input type="text" name="komoditas">
                        </div>

                        <div class="form-group">
                            <label>Masa Tanam</label>
                            <input type="text" name="masa_tanam">
                        </div>

                        <div class="form-group">
                            <label>Hasil / ha</label>
                            <input type="number" name="hasil_per_ha">
                        </div>

                        <div class="form-group">
                            <label>Total Panen</label>
                            <input type="number" name="total_panen">
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


                <!-- TABLE -->
                <div class="lahan-table-box">

                    <div class="table-header">
                        <h3><i class="fa fa-table"></i> Daftar Lahan</h3>
                    </div>

                    <div class="table-container">
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
                                            <td><?= $d['nama_pemilik'] ?></td>
                                            <td><?= $d['provinsi'] ?></td>
                                            <td><?= $d['luas'] ?> ha</td>
                                            <td><?= $d['komoditas'] ?></td>
                                            <td>
                                                <span class="badge <?= $d['status'] == 'aktif' ? 'aktif' : 'nonaktif' ?>">
                                                    <?= $d['status'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="?hapus=<?= $d['id'] ?>" class="btn-delete" onclick="return confirm('Hapus?')">
                                                    Hapus
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" style="text-align:center;">Data belum tersedia</td>
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