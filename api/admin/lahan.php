<?php
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../config/koneksi.php';

$page = 'data_lahan';

// ======================
// SIMPAN DATA (AMAN)
// ======================
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

    header("Location: lahan.php");
    exit;
}

// ======================
// HAPUS DATA
// ======================
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM lahan_petani WHERE id=$id");

    header("Location: lahan.php");
    exit;
}

// ======================
// AMBIL DATA
// ======================
$data = mysqli_query($conn, "SELECT * FROM lahan_petani ORDER BY provinsi ASC, nama_pemilik ASC");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Data Lahan</title>

    <style>
        body {
            font-family: 'Segoe UI';
            background: #f5f7fa;
            padding: 20px;
        }

        h2 {
            margin-bottom: 20px;
        }

        .card {
            background: white;
            padding: 16px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        input,
        select {
            padding: 8px;
            margin: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            padding: 8px 14px;
            background: #2D6A4F;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #1B4332;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        th {
            background: #2D6A4F;
            color: white;
            padding: 10px;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }

        td:first-child {
            text-align: left;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
        }

        .aktif {
            background: #bbf7d0;
            color: #166534;
        }

        .nonaktif {
            background: #fecaca;
            color: #991b1b;
        }

        .btn-hapus {
            color: red;
            text-decoration: none;
        }

        .form-grid {
            display: flex;
            flex-wrap: wrap;
        }
    </style>
</head>

<body>

    <h2>🌾 Kelola Data Lahan</h2>

    <div class="card">
        <form method="POST">
            <div class="form-grid">
                <input name="nama_pemilik" placeholder="Nama Pemilik" required>
                <input name="provinsi" placeholder="Provinsi" required>
                <input name="desa" placeholder="Desa">
                <input name="luas" placeholder="Luas (ha)" type="number" step="0.01">
                <input name="komoditas" placeholder="Komoditas">
                <input name="masa_tanam" placeholder="Masa Tanam">
                <input name="hasil_per_ha" placeholder="Hasil/ha" type="number" step="0.01">
                <input name="total_panen" placeholder="Total Panen" type="number" step="0.01">

                <select name="status">
                    <option value="aktif">Aktif</option>
                    <option value="tidak aktif">Tidak Aktif</option>
                </select>
            </div>

            <br>
            <button name="simpan">+ Tambah Data</button>
        </form>
    </div>

    <div class="card">
        <table>
            <tr>
                <th>Nama</th>
                <th>Provinsi</th>
                <th>Luas</th>
                <th>Komoditas</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>

            <?php while ($d = mysqli_fetch_assoc($data)): ?>
                <tr>
                    <td><?= htmlspecialchars($d['nama_pemilik']) ?></td>
                    <td><?= htmlspecialchars($d['provinsi']) ?></td>
                    <td><?= $d['luas'] ?></td>
                    <td><?= htmlspecialchars($d['komoditas']) ?></td>
                    <td>
                        <span class="badge <?= $d['status'] == 'aktif' ? 'aktif' : 'nonaktif' ?>">
                            <?= $d['status'] ?>
                        </span>
                    </td>
                    <td>
                        <a class="btn-hapus" href="?hapus=<?= $d['id'] ?>" onclick="return confirm('Yakin hapus data?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>

        </table>
    </div>

</body>

</html>