<?php
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../config/koneksi.php';

$user = requireAuth('admin');
$page = 'dashboard';
$id_user = $user['id_user'];

$q_total    = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='user'");
$total_petani = mysqli_fetch_assoc($q_total)['total'] ?? 0;

$q_aktif    = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='user' AND status='Active'");
$total_aktif = mysqli_fetch_assoc($q_aktif)['total'] ?? 0;

$q_nonaktif = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='user' AND status='Inactive'");
$total_nonaktif = mysqli_fetch_assoc($q_nonaktif)['total'] ?? 0;

$query_recent = mysqli_query($conn, "SELECT * FROM users WHERE role='user' ORDER BY id_user DESC LIMIT 8");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin — AgriData</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/assets/css/style.css">
    <link rel="stylesheet" href="/public/assets/css/sidebar_admin.css">
    <link rel="stylesheet" href="/public/assets/css/topbar_admin.css">
    <link rel="stylesheet" href="/public/assets/css/dashboard_admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include __DIR__ . '/partials/sidebar_admin.php'; ?>
    <main class="main-content">
        <?php include __DIR__ . '/partials/topbar_admin.php'; ?>

        <div class="admin-header">
            <div>
                <h1 class="admin-title">Dashboard Admin <span>🌾</span></h1>
                <p class="admin-subtitle">Selamat datang kembali. Pantau statistik dan data petani di sini.</p>
            </div>
            <div class="header-date-badge">
                <i class="fa-regular fa-clock"></i>
                <span id="adminDate">Memuat...</span>
            </div>
        </div>

        <div class="kpi-grid">
            <div class="kpi-card" style="--accent: #2D6A4F;">
                <div class="kpi-icon" style="background:#f0fdf4; color:#2D6A4F;"><i class="fa-solid fa-users"></i></div>
                <div class="kpi-body">
                    <div class="kpi-label">Total Petani</div>
                    <div class="kpi-value"><?= number_format($total_petani) ?></div>
                    <div class="kpi-note">Terdaftar di sistem</div>
                </div>
                <div class="kpi-bar" style="background:#2D6A4F;"></div>
            </div>
            <div class="kpi-card" style="--accent: #10b981;">
                <div class="kpi-icon" style="background:#dcfce7; color:#10b981;"><i class="fa-solid fa-user-check"></i></div>
                <div class="kpi-body">
                    <div class="kpi-label">Petani Aktif</div>
                    <div class="kpi-value" style="color:#10b981;"><?= number_format($total_aktif) ?></div>
                    <div class="kpi-note">Akun terverifikasi</div>
                </div>
                <div class="kpi-bar" style="background:#10b981;"></div>
            </div>
            <div class="kpi-card" style="--accent: #ef4444;">
                <div class="kpi-icon" style="background:#fee2e2; color:#ef4444;"><i class="fa-solid fa-user-slash"></i></div>
                <div class="kpi-body">
                    <div class="kpi-label">Petani Inaktif</div>
                    <div class="kpi-value" style="color:#ef4444;"><?= number_format($total_nonaktif) ?></div>
                    <div class="kpi-note">Menunggu verifikasi</div>
                </div>
                <div class="kpi-bar" style="background:#ef4444;"></div>
            </div>
            <div class="kpi-card" style="--accent: #f59e0b;">
                <div class="kpi-icon" style="background:#fffbeb; color:#d97706;"><i class="fa-solid fa-percent"></i></div>
                <div class="kpi-body">
                    <div class="kpi-label">Tingkat Aktivasi</div>
                    <div class="kpi-value" style="color:#d97706;">
                        <?= ($total_petani > 0) ? number_format(($total_aktif / $total_petani) * 100, 1) : 0 ?>%
                    </div>
                    <div class="kpi-note">Dari total petani</div>
                </div>
                <div class="kpi-bar" style="background:#f59e0b;"></div>
            </div>
        </div>

        <div class="admin-two-col">
            <div class="admin-card">
                <div class="admin-card-header">
                    <div>
                        <h3 class="admin-card-title">Status Keanggotaan</h3>
                        <p class="admin-card-sub">Distribusi status akun petani</p>
                    </div>
                </div>
                <div style="display:flex; align-items:center; justify-content:center; flex-direction:column;">
                    <div style="max-width:200px; width:100%; margin:0 auto;">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="chart-legend-list">
                        <div class="chart-legend-item">
                            <div class="legend-dot" style="background:#10b981;"></div>
                            <span>Aktif</span><strong><?= number_format($total_aktif) ?></strong>
                        </div>
                        <div class="chart-legend-item">
                            <div class="legend-dot" style="background:#ef4444;"></div>
                            <span>Inaktif</span><strong><?= number_format($total_nonaktif) ?></strong>
                        </div>
                    </div>
                </div>
                <div class="admin-card-footer">
                    <i class="fa-solid fa-circle-info"></i>
                    Gunakan halaman <a href="/admin/petani" style="color:#2D6A4F; font-weight:700;">Data Petani</a> untuk mengelola status akun.
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-header">
                    <div>
                        <h3 class="admin-card-title">Pendaftar Terbaru</h3>
                        <p class="admin-card-sub">Petani yang baru bergabung ke sistem</p>
                    </div>
                    <a href="/admin/petani" class="view-all-link">Lihat Semua <i class="fa-solid fa-arrow-right"></i></a>
                </div>
                <div class="recent-list">
                    <?php
                    $count = 0;
                    while ($u = mysqli_fetch_assoc($query_recent)):
                        $count++;
                        if ($count > 6) break;
                        $st = $u['status'] ?? 'Active';
                        $is_act = (strtolower($st) == 'active');
                    ?>
                        <div class="recent-item">
                            <div class="recent-avatar"><?= strtoupper(substr($u['name'] ?? 'U', 0, 1)) ?></div>
                            <div class="recent-info">
                                <div class="recent-name"><?= htmlspecialchars($u['name']) ?></div>
                                <div class="recent-detail"><?= htmlspecialchars($u['phone'] ?? '') ?>
                                    <?= htmlspecialchars(mb_strimwidth($u['address'] ?? '', 0, 28, '...')) ?>
                                </div>
                                <span class="recent-badge <?= $is_act ? 'badge-act' : 'badge-inact' ?>">
                                    <?= $is_act ? 'Aktif' : 'Inaktif' ?>
                                </span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <div class="admin-card" style="margin-bottom:0;">
                <div class="admin-card-header" style="margin-bottom:20px;">
                    <div>
                        <h3 class="admin-card-title">Navigasi Cepat</h3>
                        <p class="admin-card-sub">Akses halaman manajemen dengan cepat</p>
                    </div>
                </div>
                <div class="quick-nav-grid">
                    <a href="/admin/petani" class="quick-nav-item">
                        <div class="qn-icon" style="background:#f0fdf4; color:#2D6A4F;"><i class="fa-solid fa-user-group"></i></div>
                        <div class="qn-label">Manajemen Petani</div>
                        <div class="qn-sub">Kelola data anggota</div>
                    </a>
                    <a href="/admin/petani?action=panen" class="quick-nav-item">
                        <div class="qn-icon" style="background:#fffbeb; color:#d97706;"><i class="fa-solid fa-wheat-awn"></i></div>
                        <div class="qn-label">Data Panen</div>
                        <div class="qn-sub">Kelola hasil panen</div>
                    </a>
                    <a href="/admin/petani?action=tambah" class="quick-nav-item">
                        <div class="qn-icon" style="background:#eff6ff; color:#3b82f6;"><i class="fa-solid fa-user-plus"></i></div>
                        <div class="qn-label">Tambah Petani</div>
                        <div class="qn-sub">Daftarkan anggota baru</div>
                    </a>
                    <a href="/admin/profile" class="quick-nav-item">
                        <div class="qn-icon" style="background:#fdf4ff; color:#9333ea;"><i class="fa-solid fa-user-gear"></i></div>
                        <div class="qn-label">Profil Admin</div>
                        <div class="qn-sub">Pengaturan akun admin</div>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('adminDate').textContent = new Date().toLocaleDateString('id-ID', {
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
        });
        new Chart(document.getElementById('statusChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Aktif', 'Inaktif'],
                datasets: [{
                    data: [<?= json_encode((int)$total_aktif) ?>, <?= json_encode((int)$total_nonaktif) ?>],
                    backgroundColor: ['#10b981', '#ef4444'],
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor: '#1e293b', borderRadius: 10 }
                }
            }
        });
    </script>
</body>
</html>
