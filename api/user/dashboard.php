<?php
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../config/koneksi.php';

$user = requireAuth('user');
$page = 'dashboard';
$id_user = $user['id_user'];

$query_user = mysqli_query($conn, "SELECT * FROM users WHERE id_user='$id_user'");
$u = mysqli_fetch_assoc($query_user);

// === BPS DATA ===
$data_petani_2023 = [];
$url_bps = "https://sensus.bps.go.id/topik/tabular/st2023/242/98808/3";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_bps);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$json_data = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code === 200 && $json_data !== false) {
    $response = json_decode($json_data, true);
    if (isset($response['data'])) {
        $temp = [];
        foreach ($response['data'] as $row) {
            $prov = $row['nama_wilayah'];
            $indikator = strtolower($row['nama_indikator']);
            $nilai = (int)$row['nilai'];
            if (!isset($temp[$prov])) $temp[$prov] = ['provinsi' => $prov, 'rt_petani' => 0, 'jml_petani' => 0];
            if (strpos($indikator, 'rumah tangga') !== false) $temp[$prov]['rt_petani'] = $nilai;
            else $temp[$prov]['jml_petani'] = $nilai;
        }
        $data_petani_2023 = array_values($temp);
    }
}

// === DATA PANEN (untuk view=panen) ===
$view = $_GET['view'] ?? 'dashboard';
if ($view === 'panen') {
    $stmt = $conn->prepare("SELECT * FROM data_panen WHERE tahun=? ORDER BY id ASC");
    $tahun = 2024;
    $stmt->bind_param("i", $tahun);
    $stmt->execute();
    $data_panen = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $page = 'data_panen';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $view === 'panen' ? 'Data Panen' : 'Dashboard Saya' ?> - AgriData</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/sidebar_user.css">
    <link rel="stylesheet" href="/assets/css/topbar_user.css">
    <link rel="stylesheet" href="/assets/css/dashboard_user.css">
    <?php if ($view === 'panen'): ?>
    <link rel="stylesheet" href="/assets/css/data_panen.css">
    <?php endif; ?>
</head>
<body>
    <?php include __DIR__ . '/partials/sidebar_user.php'; ?>
    <main class="main-content">
        <?php include __DIR__ . '/partials/topbar_user.php'; ?>

        <?php if ($view === 'panen'): ?>
        <!-- ========== DATA PANEN VIEW ========== -->
        <div class="content-wrapper" style="padding-top:20px;">
            <div class="page-header" style="margin-bottom:30px;">
                <h2 style="font-weight:700;">Data Produksi Padi Nasional 2024</h2>
                <p style="color:#64748b;">Sumber: Badan Pusat Statistik</p>
            </div>
            <div class="table-container" style="background:white;border-radius:15px;padding:20px;box-shadow:0 4px 6px rgba(0,0,0,0.05);">
                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="background:#f8fafc;text-align:left;">
                            <th style="padding:15px;">Provinsi</th>
                            <th style="padding:15px;">Luas Panen (ha)</th>
                            <th style="padding:15px;">Produksi (ton)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_panen as $row): ?>
                        <tr style="border-bottom:1px solid #f1f5f9;">
                            <td style="padding:15px;"><?= htmlspecialchars($row['provinsi']) ?></td>
                            <td style="padding:15px;"><?= number_format($row['luas_panen'], 0, ',', '.') ?></td>
                            <td style="padding:15px;"><?= number_format($row['produksi'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php else: ?>
        <!-- ========== DASHBOARD VIEW ========== -->
        <div class="welcome-banner">
            <div class="welcome-text">
                <h1>Selamat datang, <?= htmlspecialchars($u['username'] ?? $user['name'] ?? 'Petani') ?>! 🌾</h1>
                <p>Panel pribadi AgriData — pantau data pertanian dan informasi Anda di sini.</p>
            </div>
            <div class="welcome-date-badge">
                <i class="fa-regular fa-calendar"></i>
                <span id="currentDate">Memuat...</span>
            </div>
        </div>

        <div class="stat-grid">
            <?php $status_akun = $u['status'] ?? 'Active'; $is_active = (strtolower($status_akun) == 'active'); ?>
            <div class="stat-card-new">
                <div class="card-accent-line" style="background:<?= $is_active ? '#10b981' : '#ef4444' ?>;"></div>
                <div class="card-icon" style="background:<?= $is_active ? '#dcfce7' : '#fee2e2' ?>;color:<?= $is_active ? '#16a34a' : '#dc2626' ?>;"><i class="fa-solid fa-user-shield"></i></div>
                <div class="card-label">Status Akun</div>
                <div class="card-value" style="color:<?= $is_active ? '#16a34a' : '#dc2626' ?>;"><?= $is_active ? 'Aktif' : 'Tidak Aktif' ?></div>
                <div class="card-sub"><?= $is_active ? 'Terverifikasi sistem ✓' : 'Menunggu verifikasi' ?></div>
            </div>
            <div class="stat-card-new">
                <div class="card-accent-line" style="background:#3b82f6;"></div>
                <div class="card-icon" style="background:#eff6ff;color:#3b82f6;"><i class="fa-solid fa-phone"></i></div>
                <div class="card-label">Nomor Telepon</div>
                <div class="card-value" style="font-size:17px;"><?= htmlspecialchars($u['phone'] ?? '-') ?></div>
                <div class="card-sub">@<?= htmlspecialchars($u['username'] ?? '-') ?></div>
            </div>
            <div class="stat-card-new">
                <div class="card-accent-line" style="background:#f59e0b;"></div>
                <div class="card-icon" style="background:#fffbeb;color:#d97706;"><i class="fa-solid fa-map-location-dot"></i></div>
                <div class="card-label">Alamat Domisili</div>
                <div class="card-value" style="font-size:13px;font-weight:600;line-height:1.4;"><?= htmlspecialchars(mb_strimwidth($u['address'] ?? 'Belum diisi', 0, 40, '...')) ?></div>
                <div class="card-sub">Lokasi terdaftar</div>
            </div>
            <div class="stat-card-new">
                <div class="card-accent-line" style="background:#8b5cf6;"></div>
                <div class="card-icon" style="background:#f5f3ff;color:#7c3aed;"><i class="fa-solid fa-seedling"></i></div>
                <div class="card-label">Data Panen</div>
                <div class="card-value">Lihat Riwayat</div>
                <div class="card-sub"><a href="/user/dashboard?view=panen" style="color:#7c3aed;font-weight:700;font-size:12px;">Ke halaman Data Panen →</a></div>
            </div>
        </div>

        <div class="two-col">
            <div class="section-card">
                <div class="section-title">Profil Saya</div>
                <div class="section-subtitle">Informasi akun yang terdaftar di sistem</div>
                <div class="profile-summary">
                    <div class="profile-avatar-big"><?= strtoupper(substr($u['name'] ?? $user['name'] ?? 'P', 0, 1)) ?></div>
                    <div>
                        <div class="profile-info-name"><?= htmlspecialchars($u['name'] ?? '') ?></div>
                        <div class="profile-info-role">Petani Terdaftar — AgriData</div>
                        <span class="status-pill <?= $is_active ? 'status-active' : 'status-inactive' ?>">
                            <i class="fa-solid fa-circle" style="font-size:7px;"></i>
                            <?= $is_active ? 'Akun Aktif' : 'Akun Tidak Aktif' ?>
                        </span>
                    </div>
                </div>
                <div class="info-row"><span class="info-key"><i class="fa-regular fa-envelope"></i> Email</span><span class="info-val"><?= htmlspecialchars($u['email'] ?? '-') ?></span></div>
                <div class="info-row"><span class="info-key"><i class="fa-solid fa-user"></i> Username</span><span class="info-val">@<?= htmlspecialchars($u['username'] ?? '-') ?></span></div>
                <div class="info-row"><span class="info-key"><i class="fa-solid fa-phone"></i> Telepon</span><span class="info-val"><?= htmlspecialchars($u['phone'] ?? '-') ?></span></div>
                <div class="info-row"><span class="info-key"><i class="fa-solid fa-location-dot"></i> Alamat</span><span class="info-val" style="max-width:180px;text-align:right;line-height:1.4;"><?= htmlspecialchars($u['address'] ?? '-') ?></span></div>
                <div class="quick-actions">
                    <a href="/user/profile" class="qa-btn"><i class="fa-solid fa-user-pen"></i> Edit Profil</a>
                    <a href="/user/dashboard?view=panen" class="qa-btn"><i class="fa-solid fa-leaf"></i> Data Panen</a>
                </div>
            </div>

            <div class="section-card">
                <div class="section-title">Panduan Penggunaan</div>
                <div class="section-subtitle">Langkah-langkah memanfaatkan sistem AgriData</div>
                <div class="tips-list">
                    <div class="tip-item"><div class="tip-icon" style="background:#dcfce7;color:#16a34a;"><i class="fa-solid fa-circle-check"></i></div><div class="tip-text"><h5>Lengkapi Profil Anda</h5><p>Pastikan data diri seperti alamat dan nomor telepon sudah terisi agar verifikasi berjalan lancar.</p></div></div>
                    <div class="tip-item"><div class="tip-icon" style="background:#fffbeb;color:#d97706;"><i class="fa-solid fa-wheat-awn"></i></div><div class="tip-text"><h5>Input Data Panen Rutin</h5><p>Catat hasil panen secara berkala melalui menu Data Panen untuk membantu analisis pertanian.</p></div></div>
                    <div class="tip-item"><div class="tip-icon" style="background:#eff6ff;color:#3b82f6;"><i class="fa-solid fa-chart-bar"></i></div><div class="tip-text"><h5>Pantau Data Statistik BPS</h5><p>Dashboard ini menampilkan data resmi BPS tentang jumlah petani per provinsi di Indonesia.</p></div></div>
                    <div class="tip-item"><div class="tip-icon" style="background:#fdf4ff;color:#9333ea;"><i class="fa-solid fa-lock"></i></div><div class="tip-text"><h5>Jaga Keamanan Akun</h5><p>Jangan bagikan password kepada siapapun. Admin tidak pernah meminta password Anda.</p></div></div>
                </div>
            </div>
        </div>

        <div class="chart-full">
            <div class="chart-header">
                <div>
                    <div class="section-title">Grafik Sebaran Petani per Wilayah</div>
                    <div class="section-subtitle" style="margin-bottom:0;">Perbandingan jumlah petani di berbagai wilayah Indonesia (ribuan)</div>
                </div>
                <div class="chart-legend-pills">
                    <div class="legend-pill" style="background:#dcfce7;color:#16a34a;"><i class="fa-solid fa-circle" style="font-size:8px;"></i> Jumlah Petani</div>
                    <div class="legend-pill" style="background:#dbeafe;color:#1d4ed8;">BPS 2023</div>
                </div>
            </div>
            <div style="position:relative;height:300px;width:100%;"><canvas id="farmerChartViz"></canvas></div>
        </div>

        <div class="bps-table-card">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:20px;">
                <div>
                    <div class="section-title">Data Rumah Tangga Petani</div>
                    <div class="section-subtitle" style="margin-bottom:0;">Jumlah petani menurut wilayah di Indonesia — Sensus Pertanian 2023</div>
                </div>
                <div class="bps-badge"><i class="fa-solid fa-database"></i> Sumber: BPS ST2023</div>
            </div>
            <div style="overflow-x:auto;border-radius:14px;border:1px solid #f1f5f9;">
                <table class="data-table">
                    <thead><tr><th>#</th><th>Provinsi</th><th class="text-right">Rumah Tangga Petani</th><th class="text-right">Jumlah Petani</th></tr></thead>
                    <tbody>
                        <?php if (!empty($data_petani_2023)): ?>
                        <?php $no = 1; foreach ($data_petani_2023 as $row): ?>
                        <tr>
                            <td style="color:#94a3b8;font-size:12px;font-weight:600;"><?= $no++ ?></td>
                            <td><div class="td-province"><div class="prov-dot"></div><?= htmlspecialchars($row['provinsi']) ?></div></td>
                            <td class="text-right"><span class="number-chip"><?= number_format($row['rt_petani'], 0, ',', '.') ?></span></td>
                            <td class="text-right font-bold-green"><?= number_format($row['jml_petani'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr><td colspan="4" style="text-align:center;padding:40px;color:#94a3b8;"><i class="fa-solid fa-database" style="font-size:28px;display:block;margin-bottom:10px;color:#cbd5e1;"></i>Data BPS belum tersedia atau gagal disinkronisasi.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <script>
        document.getElementById('currentDate').textContent = new Date().toLocaleDateString('id-ID', {weekday:'long',year:'numeric',month:'long',day:'numeric'});
        new Chart(document.getElementById('farmerChartViz').getContext('2d'), {
            type:'bar',
            data:{
                labels:['Jawa Timur','Jawa Tengah','Jawa Barat','Sumatera Utara','Sulawesi Sel.','NTT','Lampung','Aceh'],
                datasets:[{label:'Jumlah Petani (ribu)',data:[4800,4200,3900,1800,1500,1200,1100,980],backgroundColor:['#2D6A4F','#40916C','#52B788','#74C69D','#95D5B2','#B7E4C7','#D8F3DC','#a3d9a5'],borderRadius:8,borderSkipped:false}]
            },
            options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false},tooltip:{backgroundColor:'#1e293b',titleColor:'#f8fafc',bodyColor:'#94a3b8',padding:12,borderRadius:10}},scales:{x:{grid:{display:false},ticks:{color:'#64748b'}},y:{grid:{color:'#f1f5f9'},ticks:{color:'#94a3b8'}}}}
        });
        </script>
        <?php endif; ?>
    </main>
</body>
</html>
