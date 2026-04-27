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

$query_recent = mysqli_query($conn, "SELECT * FROM users WHERE role='user' ORDER BY id_user DESC LIMIT 6");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin — AgriData</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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

        <!-- HEADER -->
        <div class="admin-header">
            <div>
                <h1 class="admin-title">Dashboard Admin 🌾</h1>
                <p class="admin-subtitle">Pantau statistik dan aktivitas petani secara real-time</p>
            </div>
            <div class="header-date-badge">
                <i class="fa-regular fa-clock"></i>
                <span id="adminDate"></span>
            </div>
        </div>

        <!-- KPI -->
        <div class="kpi-grid">

            <div class="kpi-card">
                <div class="kpi-icon" style="background:#f0fdf4; color:#2D6A4F;">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <div class="kpi-label">Total Petani</div>
                    <div class="kpi-value"><?= number_format($total_petani) ?></div>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon" style="background:#dcfce7; color:#10b981;">
                    <i class="fa-solid fa-user-check"></i>
                </div>
                <div>
                    <div class="kpi-label">Petani Aktif</div>
                    <div class="kpi-value" style="color:#10b981;"><?= number_format($total_aktif) ?></div>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon" style="background:#fee2e2; color:#ef4444;">
                    <i class="fa-solid fa-user-slash"></i>
                </div>
                <div>
                    <div class="kpi-label">Petani Inaktif</div>
                    <div class="kpi-value" style="color:#ef4444;"><?= number_format($total_nonaktif) ?></div>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon" style="background:#fffbeb; color:#d97706;">
                    <i class="fa-solid fa-percent"></i>
                </div>
                <div>
                    <div class="kpi-label">Aktivasi</div>
                    <div class="kpi-value" style="color:#d97706;">
                        <?= ($total_petani > 0) ? number_format(($total_aktif / $total_petani) * 100, 1) : 0 ?>%
                    </div>
                </div>
            </div>

        </div>

        <!-- MAIN GRID -->
        <div class="dashboard-grid">

            <!-- CHART -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <div>
                        <div class="admin-card-title">Status Keanggotaan</div>
                        <div class="admin-card-sub">Distribusi akun petani</div>
                    </div>
                </div>

                <div class="chart-container">
                    <canvas id="statusChart"></canvas>

                    <div class="chart-legend-list">
                        <div class="chart-legend-item">
                            <span class="legend-dot" style="background:#10b981;"></span>
                            Aktif (<?= $total_aktif ?>)
                        </div>
                        <div class="chart-legend-item">
                            <span class="legend-dot" style="background:#ef4444;"></span>
                            Inaktif (<?= $total_nonaktif ?>)
                        </div>
                    </div>
                </div>
            </div>

            <!-- RECENT -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <div>
                        <div class="admin-card-title">Pendaftar Terbaru</div>
                        <div class="admin-card-sub">User terbaru masuk sistem</div>
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
                            <div class="recent-avatar">
                                <?= strtoupper(substr($u['name'] ?? 'U', 0, 1)) ?>
                            </div>

                            <div class="recent-info">
                                <div class="recent-name"><?= htmlspecialchars($u['name']) ?></div>
                                <div class="recent-detail"><?= htmlspecialchars($u['phone'] ?? '-') ?></div>
                            </div>

                            <span class="recent-badge <?= $is_act ? 'badge-act' : 'badge-inact' ?>">
                                <?= $is_act ? 'Aktif' : 'Inaktif' ?>
                            </span>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

        </div>

        <!-- QUICK NAV -->
        <div class="admin-card quick-nav">
            <div class="admin-card-header">
                <div>
                    <div class="admin-card-title">Navigasi Cepat</div>
                    <div class="admin-card-sub">Akses fitur utama</div>
                </div>
            </div>

            <div class="quick-nav-grid">

                <a href="/admin/petani" class="quick-nav-item">
                    <div class="qn-icon" style="background:#f0fdf4; color:#2D6A4F;">
                        <i class="fa-solid fa-user-group"></i>
                    </div>
                    <div class="qn-label">Manajemen Petani</div>
                    <div class="qn-sub">Kelola data anggota</div>
                </a>

                <a href="/admin/petani?action=panen" class="quick-nav-item">
                    <div class="qn-icon" style="background:#fffbeb; color:#d97706;">
                        <i class="fa-solid fa-wheat-awn"></i>
                    </div>
                    <div class="qn-label">Data Panen</div>
                    <div class="qn-sub">Kelola hasil panen</div>
                </a>

                <a href="/admin/petani?action=tambah" class="quick-nav-item">
                    <div class="qn-icon" style="background:#eff6ff; color:#3b82f6;">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <div class="qn-label">Tambah Petani</div>
                    <div class="qn-sub">Daftarkan user</div>
                </a>

                <a href="/admin/profile" class="quick-nav-item">
                    <div class="qn-icon" style="background:#fdf4ff; color:#9333ea;">
                        <i class="fa-solid fa-user-gear"></i>
                    </div>
                    <div class="qn-label">Profil Admin</div>
                    <div class="qn-sub">Pengaturan akun</div>
                </a>

            </div>
        </div>

    </main>

    <script>
        document.getElementById('adminDate').textContent =
            new Date().toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Aktif', 'Inaktif'],
                datasets: [{
                    data: [<?= (int)$total_aktif ?>, <?= (int)$total_nonaktif ?>],
                    backgroundColor: ['#10b981', '#ef4444'],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>

</body>

</html>