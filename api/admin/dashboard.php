<?php
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../config/koneksi.php';

$user = requireAuth('admin');
$page = 'dashboard';

$q_total    = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='user'");
$total_petani = mysqli_fetch_assoc($q_total)['total'] ?? 0;

$q_aktif    = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='user' AND status='Active'");
$total_aktif = mysqli_fetch_assoc($q_aktif)['total'] ?? 0;

$q_nonaktif = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='user' AND status='Inactive'");
$total_nonaktif = mysqli_fetch_assoc($q_nonaktif)['total'] ?? 0;

$pct_aktif = $total_petani > 0 ? round(($total_aktif / $total_petani) * 100, 1) : 0;

$query_recent = mysqli_query($conn, "SELECT * FROM users WHERE role='user' ORDER BY id_user DESC LIMIT 6");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin — AgriData</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/sidebar_admin.css">
    <link rel="stylesheet" href="/assets/css/topbar_admin.css">
    <link rel="stylesheet" href="/assets/css/dashboard_admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <?php include __DIR__ . '/partials/sidebar_admin.php'; ?>

    <main class="main-content">
        <?php include __DIR__ . '/partials/topbar_admin.php'; ?>

        <!-- ── HEADER ── -->
        <div class="admin-header">
            <div>
                <h1 class="admin-title">Dashboard <span class="admin-title-accent">Admin</span> 🌾</h1>
                <p class="admin-subtitle">Pantau statistik dan aktivitas petani secara real-time</p>
            </div>
            <div class="header-right">
                <div class="header-date-badge">
                    <i class="fa-regular fa-clock"></i>
                    <span id="adminDate"></span>
                </div>
            </div>
        </div>

        <!-- ── KPI ── -->
        <div class="kpi-grid">

            <div class="kpi-card" style="--kpi-color:#2D6A4F;">
                <div class="kpi-top">
                    <div class="kpi-icon" style="background:#f0fdf4;color:#2D6A4F;">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <span class="kpi-trend trend-neu"><i class="fa-solid fa-seedling"></i> Terdaftar</span>
                </div>
                <div class="kpi-value"><?= number_format($total_petani) ?></div>
                <div class="kpi-label">Total Petani</div>
            </div>

            <div class="kpi-card" style="--kpi-color:#10b981;">
                <div class="kpi-top">
                    <div class="kpi-icon" style="background:#dcfce7;color:#10b981;">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                    <span class="kpi-trend trend-up"><i class="fa-solid fa-arrow-up"></i> Aktif</span>
                </div>
                <div class="kpi-value" style="color:#10b981;"><?= number_format($total_aktif) ?></div>
                <div class="kpi-label">Petani Aktif</div>
            </div>

            <div class="kpi-card" style="--kpi-color:#ef4444;">
                <div class="kpi-top">
                    <div class="kpi-icon" style="background:#fee2e2;color:#ef4444;">
                        <i class="fa-solid fa-user-slash"></i>
                    </div>
                    <span class="kpi-trend trend-down"><i class="fa-solid fa-circle-pause"></i> Pending</span>
                </div>
                <div class="kpi-value" style="color:#ef4444;"><?= number_format($total_nonaktif) ?></div>
                <div class="kpi-label">Petani Inaktif</div>
            </div>

            <div class="kpi-card" style="--kpi-color:#d97706;">
                <div class="kpi-top">
                    <div class="kpi-icon" style="background:#fffbeb;color:#d97706;">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <span class="kpi-trend trend-neu"><i class="fa-solid fa-percent"></i> Rate</span>
                </div>
                <div class="kpi-value" style="color:#d97706;"><?= $pct_aktif ?>%</div>
                <div class="kpi-label">Tingkat Aktivasi</div>
            </div>

        </div>

        <!-- ── MAIN GRID ── -->
        <div class="main-grid">

            <!-- Donut chart -->
            <div class="dash-card">
                <div class="card-header">
                    <div>
                        <div class="card-title">Status Keanggotaan</div>
                        <div class="card-sub">Distribusi status akun seluruh petani</div>
                    </div>
                </div>

                <div class="chart-wrap">
                    <div class="chart-canvas-wrap">
                        <canvas id="statusChart" width="140" height="140"></canvas>
                        <div class="chart-center-label">
                            <div class="chart-center-pct"><?= $pct_aktif ?>%</div>
                            <div class="chart-center-txt">Aktif</div>
                        </div>
                    </div>

                    <div class="chart-legend">
                        <div class="legend-row">
                            <span class="legend-dot" style="background:#10b981;"></span>
                            <div class="legend-info">
                                <div class="legend-name">Petani Aktif</div>
                                <div class="legend-count"><?= $total_aktif ?> dari <?= $total_petani ?> petani</div>
                            </div>
                            <div class="legend-pct"><?= $pct_aktif ?>%</div>
                        </div>
                        <div class="legend-row">
                            <span class="legend-dot" style="background:#ef4444;"></span>
                            <div class="legend-info">
                                <div class="legend-name">Petani Inaktif</div>
                                <div class="legend-count"><?= $total_nonaktif ?> dari <?= $total_petani ?> petani</div>
                            </div>
                            <div class="legend-pct"><?= $total_petani > 0 ? round((1 - $pct_aktif / 100) * 100, 1) : 0 ?>%</div>
                        </div>
                    </div>
                </div>

                <div class="stat-pills">
                    <div class="stat-pill"><i class="fa-solid fa-circle-check" style="color:#10b981;"></i> <?= $total_aktif ?> Akun terverifikasi</div>
                    <div class="stat-pill"><i class="fa-solid fa-clock" style="color:#d97706;"></i> <?= $total_nonaktif ?> Menunggu review</div>
                </div>
            </div>

            <!-- Recent users -->
            <div class="dash-card">
                <div class="card-header">
                    <div>
                        <div class="card-title">Pendaftar Terbaru</div>
                        <div class="card-sub">User terbaru masuk sistem</div>
                    </div>
                    <a href="/admin/petani" class="view-all-link">
                        Lihat Semua <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                <div class="recent-list">
                    <?php while ($u = mysqli_fetch_assoc($query_recent)):
                        $is_act = strtolower($u['status'] ?? '') === 'active';
                    ?>
                        <div class="recent-item">
                            <div class="recent-avatar"><?= strtoupper(substr($u['name'] ?? 'U', 0, 1)) ?></div>
                            <div class="recent-info">
                                <div class="recent-name"><?= htmlspecialchars($u['name']) ?></div>
                                <div class="recent-detail"><?= htmlspecialchars($u['phone'] ?? '-') ?> &mdash; <?= htmlspecialchars(mb_strimwidth($u['address'] ?? 'Alamat belum diisi', 0, 22, '...')) ?></div>
                            </div>
                            <span class="recent-badge <?= $is_act ? 'badge-act' : 'badge-inact' ?>">
                                <?= $is_act ? 'Aktif' : 'Inaktif' ?>
                            </span>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

        </div>

        <!-- ── BOTTOM ROW ── -->
        <div class="bottom-grid" style="grid-template-columns:1fr;">
            <!-- Quick Nav -->
            <div class="dash-card">
                <div class="card-header">
                    <div>
                        <div class="card-title">Navigasi Cepat</div>
                        <div class="card-sub">Akses fitur utama sistem</div>
                    </div>
                </div>

                <div class="quick-nav-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;">

                    <a href="/admin/petani" class="quick-nav-item">
                        <div class="qn-icon" style="background:#f0fdf4;color:#2D6A4F;">
                            <i class="fa-solid fa-user-group"></i>
                        </div>
                        <div class="qn-label">Manajemen Petani</div>
                        <div class="qn-sub">Kelola data anggota</div>
                    </a>

                    <a href="/admin/petani?action=panen" class="quick-nav-item">
                        <div class="qn-icon" style="background:#fffbeb;color:#d97706;">
                            <i class="fa-solid fa-wheat-awn"></i>
                        </div>
                        <div class="qn-label">Data Panen</div>
                        <div class="qn-sub">Kelola hasil panen</div>
                    </a>

                    <a href="/admin/petani?action=tambah" class="quick-nav-item">
                        <div class="qn-icon" style="background:#eff6ff;color:#3b82f6;">
                            <i class="fa-solid fa-user-plus"></i>
                        </div>
                        <div class="qn-label">Tambah Petani</div>
                        <div class="qn-sub">Daftarkan user baru</div>
                    </a>

                    <a href="/admin/profile" class="quick-nav-item">
                        <div class="qn-icon" style="background:#fdf4ff;color:#9333ea;">
                            <i class="fa-solid fa-user-gear"></i>
                        </div>
                        <div class="qn-label">Profil Admin</div>
                        <div class="qn-sub">Pengaturan akun</div>
                    </a>

                </div>
            </div>

        </div>

    </main>

    <script>
        document.getElementById('adminDate').textContent = new Date().toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        const aktif = <?= (int)$total_aktif ?>;
        const inaktif = <?= (int)$total_nonaktif ?>;
        const total = aktif + inaktif;

        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Aktif', 'Inaktif'],
                datasets: [{
                    data: total === 0 ? [1, 0] : [aktif, inaktif === 0 ? 0.001 : inaktif],
                    backgroundColor: ['#10b981', '#ef4444'],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                cutout: '74%',
                animation: {
                    animateRotate: true,
                    duration: 900
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#0d1f18',
                        titleColor: '#e2e8f0',
                        bodyColor: '#94a3b8',
                        padding: 12,
                        borderRadius: 10,
                        callbacks: {
                            label: ctx => ` ${ctx.label}: ${ctx.parsed} petani`
                        }
                    }
                }
            }
        });
    </script>

</body>

</html>