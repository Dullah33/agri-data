<?php
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../config/koneksi.php';

$user = requireAuth('user');
$page = 'data_panen';

// Ambil Data Panen
$stmt = $conn->prepare("SELECT * FROM data_panen ORDER BY id ASC");
$stmt->execute();
$data_panen = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Panen - AgriData</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/assets/css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="/assets/css/sidebar_user.css?v=<?= time() ?>">
    <link rel="stylesheet" href="/assets/css/topbar_user.css?v=<?= time() ?>">
</head>

<body style="background-color: #f1f5f9;">
    <?php include __DIR__ . '/partials/sidebar_user.php'; ?>
    <main class="main-content">
        <?php include __DIR__ . '/partials/topbar_user.php'; ?>

        <div class="content-wrapper" style="padding-top:20px;">
            <div class="page-header" style="margin-bottom:30px;">
                <h2 style="font-weight:700; color: #0f172a;">Data Produksi Padi Nasional 2023</h2>
                <p style="color:#64748b;">Sumber: Badan Pusat Statistik</p>
            </div>

            <div class="table-container" style="background:white; border-radius:15px; overflow:hidden; box-shadow:0 4px 6px rgba(0,0,0,0.05);">
                <table style="width:100%; border-collapse:collapse; font-size: 14px;">
                    <thead>
                        <tr style="background:#2d6a4f; color:#ffffff; text-align:left;">
                            <th style="padding:16px 20px; font-weight: 600;">Provinsi</th>
                            <th style="padding:16px 20px; font-weight: 600;">Luas Panen (ha)</th>
                            <th style="padding:16px 20px; font-weight: 600;">Produksi (ton)</th>
                        </tr>
                    </thead>
                    <tbody id="panen-container">
                        <?php if (!empty($data_panen)): ?>
                            <?php foreach ($data_panen as $row): ?>
                                <tr style="border-bottom:1px solid #f1f5f9; transition: background 0.2s;">
                                    <td style="padding:16px 20px; color:#334155; font-weight:500;">
                                        <?= htmlspecialchars($row['provinsi']) ?>
                                    </td>
                                    <td style="padding:16px 20px; color:#475569;">
                                        <?= number_format($row['luas_panen'], 0, ',', '.') ?>
                                    </td>
                                    <td style="padding:16px 20px; color:#475569;">
                                        <strong><?= number_format($row['produksi'], 0, ',', '.') ?></strong>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>

</html>

<script>
function loadDataPanen() {
    fetch('/api/get_panen.php')
        .then(res => res.text())
        .then(html => {
            document.getElementById('panen-container').innerHTML = html;
        })
        .catch(err => console.log('Error:', err));
}

// load pertama
loadDataPanen();

// auto refresh tiap 5 detik
setInterval(loadDataPanen, 5000);
</script>