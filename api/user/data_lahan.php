<?php
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../config/koneksi.php';

// GROUP DATA
$dataProvinsi = [];
$q = mysqli_query($conn, "SELECT * FROM lahan_petani ORDER BY provinsi, nama_pemilik");

while ($row = mysqli_fetch_assoc($q)) {
    $dataProvinsi[$row['provinsi']][] = $row;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Data Lahan Petani</title>

    <style>
        body {
            font-family: Arial;
            background: #f5f7fa;
            padding: 20px;
        }

        h2 {
            margin-bottom: 20px;
        }

        .lahan-box {
            background: white;
            border-radius: 12px;
            margin-bottom: 25px;
            padding: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .lahan-box h3 {
            margin-bottom: 10px;
            color: #1B4332;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        th {
            background: #2D6A4F;
            color: white;
            padding: 8px;
        }

        td {
            padding: 6px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }

        td:first-child {
            text-align: left;
        }

        .total-row {
            background: #ecfdf5;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <h2>🌾 Data Lahan Petani</h2>

    <?php foreach ($dataProvinsi as $provinsi => $rows): ?>

        <div class="lahan-box">
            <h3><?= htmlspecialchars($provinsi) ?></h3>

            <table>
                <tr>
                    <th>Nama</th>
                    <th>Luas</th>
                    <th>Desa</th>
                    <th>Komoditas</th>
                    <th>Status</th>
                    <th>Masa Tanam</th>
                    <th>Hasil/ha</th>
                    <th>Total</th>
                </tr>

                <?php
                $total = 0;
                foreach ($rows as $r):
                    $total += $r['total_panen'];
                ?>

                    <tr>
                        <td><?= htmlspecialchars($r['nama_pemilik']) ?></td>
                        <td><?= $r['luas'] ?></td>
                        <td><?= $r['desa'] ?></td>
                        <td><?= $r['komoditas'] ?></td>
                        <td><?= $r['status'] ?></td>
                        <td><?= $r['masa_tanam'] ?: '-' ?></td>
                        <td><?= $r['hasil_per_ha'] ?: '-' ?></td>
                        <td><?= $r['total_panen'] ?></td>
                    </tr>

                <?php endforeach; ?>

                <tr class="total-row">
                    <td colspan="7">Total Panen</td>
                    <td><?= number_format($total, 2) ?></td>
                </tr>

            </table>
        </div>

    <?php endforeach; ?>

</body>

</html>