<?php
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../config/koneksi.php';

// Memastikan hanya user yang bisa akses
$user = requireAuth('user');
$page = 'data_lahan';

// Ambil data lahan (Bisa difilter berdasarkan user jika ada id_user di tabel lahan)
$data = mysqli_query($conn, "SELECT * FROM lahan_petani ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Lahan Pertanian | AgriData</title>

    <link rel="stylesheet" href="/assets/css/style.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="/assets/css/sidebar_user.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="/assets/css/topbar_user.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="/assets/css/admin_lahan.css?v=<?= time(); ?>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Penyesuaian khusus untuk halaman User (Tanpa Form) */
        .page-lahan .lahan-layout {
            display: block;
            /* Mengubah grid menjadi block agar tabel melebar penuh */
        }

        .user-info-banner {
            background: #eef7f2;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            border-left: 5px solid #2d6a4f;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-info-banner i {
            color: #2d6a4f;
            font-size: 20px;
        }

        .user-info-banner p {
            font-size: 14px;
            color: #334155;
            margin: 0;
        }
    </style>
</head>

<body class="page-lahan">

    <?php include __DIR__ . '/partials/sidebar_user.php'; ?>

    <main class="main-content">

        <?php include __DIR__ . '/partials/topbar_user.php'; ?>

        <div class="content-area">

            <div class="user-info-banner">
                <i class="fa-solid fa-circle-info"></i>
                <p>Berikut adalah daftar lahan pertanian yang terdaftar dalam sistem. Data ini dikelola oleh Admin AgriData.</p>
            </div>

            <div class="lahan-layout">
                <div class="lahan-table-box">
                    <div class="table-header">
                        <h3><i class="fa fa-table"></i> Informasi Lahan Pertanian</h3>
                    </div>

                    <div class="table-container">
                        <div class="table-scroll">
                            <table class="table-modern">
                                <thead>
                                    <tr>
                                        <th>Pemilik Lahan</th>
                                        <th>Provinsi</th>
                                        <th>Desa</th>
                                        <th>Luas Lahan</th>
                                        <th>Komoditas Utama</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($data) > 0): ?>
                                        <?php while ($d = mysqli_fetch_assoc($data)): ?>
                                            <tr>
                                                <td><strong><?= htmlspecialchars($d['nama_pemilik']) ?></strong></td>
                                                <td><?= htmlspecialchars($d['provinsi']) ?></td>
                                                <td><?= htmlspecialchars($d['desa']) ?></td>
                                                <td><?= htmlspecialchars($d['luas']) ?> ha</td>
                                                <td><?= htmlspecialchars($d['komoditas']) ?></td>
                                                <td>
                                                    <span class="badge <?= $d['status'] == 'aktif' ? 'aktif' : 'nonaktif' ?>">
                                                        <?= htmlspecialchars($d['status']) ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" style="text-align:center; padding: 40px; color: #94a3b8;">
                                                <i class="fa fa-folder-open" style="font-size: 24px; display: block; margin-bottom: 10px;"></i>
                                                Belum ada data lahan yang tersedia saat ini.
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
    </main>

</body>

</html>