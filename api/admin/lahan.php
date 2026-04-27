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

$data = mysqli_query($conn, "SELECT * FROM lahan_petani ORDER BY provinsi ASC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Lahan</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/sidebar_admin.css">
    <link rel="stylesheet" href="/assets/css/topbar_admin.css">

    <!-- pakai style petani -->
    <link rel="stylesheet" href="/assets/css/data_petani.css">

</head>

<body>

    <div class="layout-wrapper">

        <?php include __DIR__ . '/partials/sidebar_admin.php'; ?>

        <div class="main-content">

            <?php include __DIR__ . '/partials/topbar_admin.php'; ?>

            <div class="content-area">

                <!-- HEADER -->
                <div class="page-header">
                    <div class="page-info">
                        <h1>Data Lahan</h1>
                        <p>Kelola seluruh data lahan petani AgriData</p>
                    </div>
                </div>

                <!-- FORM FULL WIDTH -->
                <div class="content-box">
                    <form method="POST" class="form-grid-3">

                        <input name="nama_pemilik" placeholder="Nama Pemilik" required>
                        <input name="provinsi" placeholder="Provinsi" required>
                        <input name="desa" placeholder="Desa">

                        <input name="luas" type="number" placeholder="Luas (ha)">
                        <input name="komoditas" placeholder="Komoditas">
                        <input name="masa_tanam" placeholder="Masa Tanam">

                        <input name="hasil_per_ha" type="number" placeholder="Hasil/ha">
                        <input name="total_panen" type="number" placeholder="Total Panen">

                        <select name="status">
                            <option value="aktif">Aktif</option>
                            <option value="tidak aktif">Nonaktif</option>
                        </select>

                        <button name="simpan" class="btn-add-new">
                            <i class="fa fa-plus"></i> Tambah Data
                        </button>

                    </form>
                </div>

                <!-- TABLE -->
                <div class="table-container">
                    <table>

                        <thead>
                            <tr>
                                <th>PEMILIK</th>
                                <th>LOKASI</th>
                                <th>LUAS</th>
                                <th>KOMODITAS</th>
                                <th>STATUS</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (mysqli_num_rows($data) > 0): ?>
                                <?php while ($d = mysqli_fetch_assoc($data)): ?>

                                    <tr>

                                        <td>
                                            <div class="profile-cell">
                                                <div class="avatar-initial">
                                                    <?= strtoupper(substr($d['nama_pemilik'], 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <span class="user-name"><?= htmlspecialchars($d['nama_pemilik']) ?></span>
                                                    <span class="user-sub"><?= htmlspecialchars($d['desa']) ?></span>
                                                </div>
                                            </div>
                                        </td>

                                        <td><?= htmlspecialchars($d['provinsi']) ?></td>
                                        <td><?= $d['luas'] ?> ha</td>
                                        <td><?= htmlspecialchars($d['komoditas']) ?></td>

                                        <td>
                                            <span class="badge-status <?= $d['status'] == 'aktif' ? 'status-active' : 'status-inactive' ?>">
                                                <?= ucfirst($d['status']) ?>
                                            </span>
                                        </td>

                                        <td>
                                            <div class="action-group">
                                                <a href="?hapus=<?= $d['id'] ?>" class="btn-icon btn-delete" onclick="return confirm('Hapus data?')">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>

                                    </tr>

                                <?php endwhile; ?>
                            <?php else: ?>

                                <tr>
                                    <td colspan="6" style="text-align:center; padding:30px;">
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

</body>

</html>