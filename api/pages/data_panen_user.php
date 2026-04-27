<?php
require_once __DIR__ . '/../controllers/user/data_panen_controller.php';
$page = 'data_panen';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Panen - AgriData</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/data_panen.css">
</head>

<body>
    <?php include 'sidebar_user.php'; ?>

    <main class="main-content">
        <?php include 'topbar_user.php'; ?>

        <div class="content-wrapper" style="padding-top: 20px;">
            <div class="page-header" style="margin-bottom: 30px;">
                <h2 style="font-weight: 700;">Data Produksi Padi Nasional 2023</h2>
                <p style="color: #64748b;">Sumber: Badan Pusat Statistik</p>
            </div>

            <div class="table-container" style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8fafc; text-align: left;">
                            <th style="padding: 15px;">Provinsi</th>
                            <th style="padding: 15px;">Luas Panen (ha)</th>
                            <th style="padding: 15px;">Produksi (ton)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_panen as $row): ?>
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 15px;"><?= $row['provinsi'] ?></td>
                                <td style="padding: 15px;"><?= number_format($row['luas_panen'], 0, ',', '.') ?></td>
                                <td style="padding: 15px;"><?= number_format($row['produksi'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>

</html>