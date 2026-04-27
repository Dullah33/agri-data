<?php
require __DIR__ . '/../controllers/admin/profile_admin.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya | Admin AgriData</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/sidebar_admin.css">
    <link rel="stylesheet" href="../../assets/css/topbar_admin.css">
    <link rel="stylesheet" href="../../assets/css/profile_admin.css">
</head>

<body>
    <?php include 'sidebar_admin.php'; ?>
    <main class="main-content">
        <?php include 'topbar_admin.php'; ?>

        <div class="profile-wrapper">

            <!-- ALERT -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert-banner success">
                    <i class="fa-solid fa-circle-check"></i> Profil admin berhasil diperbarui!
                </div>
            <?php elseif (isset($_GET['error'])): ?>
                <div class="alert-banner error">
                    <i class="fa-solid fa-circle-exclamation"></i> Gagal memperbarui profil. Coba lagi.
                </div>
            <?php endif; ?>

            <!-- BACK -->
            <a href="<?= isset($_SESSION['kembali_ke']) ? htmlspecialchars($_SESSION['kembali_ke']) : 'dashboard_admin.php' ?>" class="back-link">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
            </a>

            <!-- HERO CARD -->
            <div class="profile-header-card">
                <div class="profile-avatar">
                    <?= strtoupper(substr($data['name'] ?? 'A', 0, 1)) ?>
                </div>
                <div class="profile-meta">
                    <h2><?= htmlspecialchars($data['name'] ?? '-') ?></h2>
                    <p>@<?= htmlspecialchars($data['username'] ?? '-') ?></p>
                    <div class="profile-badge">
                        <i class="fa-solid fa-user-shield" style="font-size:11px;"></i>
                        Super Admin
                    </div>
                </div>
            </div>

            <form action="../controllers/admin/proses_update_profile_admin.php" method="POST">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">

                    <!-- KOLOM KIRI: Data Akun -->
                    <div class="profile-info-card">
                        <div class="section-head">
                            <div class="section-head-icon"><i class="fa-solid fa-at"></i></div>
                            <div>
                                <h3>Data Akun</h3>
                                <p>Nama, username, dan email</p>
                            </div>
                        </div>

                        <div class="fg">
                            <label>Nama Lengkap</label>
                            <input type="text" name="name"
                                value="<?= htmlspecialchars($data['name'] ?? '') ?>" required>
                        </div>
                        <div class="fg">
                            <label>Username Admin</label>
                            <input type="text" name="username"
                                value="<?= htmlspecialchars($data['username'] ?? '') ?>" required>
                        </div>
                        <div class="fg">
                            <label>Email</label>
                            <input type="email" name="email"
                                value="<?= htmlspecialchars($data['email'] ?? '') ?>" required>
                        </div>

                        <!-- Info readonly -->
                        <div style="margin-top: 18px;">
                            <div class="info-chip">
                                <i class="fa-solid fa-id-badge"></i>
                                <span>ID: <strong><?= htmlspecialchars($data['id_user'] ?? '-') ?></strong></span>
                            </div>
                            <div class="info-chip" style="margin-bottom:0;">
                                <i class="fa-solid fa-circle-dot" style="color:#22c55e;"></i>
                                <span>Role: <strong>Admin</strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- KOLOM KANAN: Keamanan + Simpan -->
                    <div style="display: flex; flex-direction: column; gap: 20px;">

                        <div class="profile-info-card">
                            <div class="section-head">
                                <div class="section-head-icon"><i class="fa-solid fa-lock"></i></div>
                                <div>
                                    <h3>Keamanan</h3>
                                    <p>Kosongkan jika tidak ganti password</p>
                                </div>
                            </div>

                            <div class="fg" style="margin-bottom:0;">
                                <label>Password Baru</label>
                                <div class="pass-wrap">
                                    <input type="password" name="password" id="passField"
                                        placeholder="••••••••">
                                    <span class="pass-eye" onclick="togglePass()">
                                        <i class="fa-regular fa-eye" id="eyeIcon"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- TOMBOL SIMPAN -->
                        <div class="profile-info-card" style="padding: 20px 28px;">
                            <button type="submit" name="save_admin" class="btn-save">
                                <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                            </button>
                        </div>

                    </div>
                </div>
            </form>

        </div>
    </main>

    <script>
        function togglePass() {
            const f = document.getElementById('passField');
            const i = document.getElementById('eyeIcon');
            if (f.type === 'password') {
                f.type = 'text';
                i.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                f.type = 'password';
                i.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // Auto-hide alert setelah 4 detik
        const alert = document.querySelector('.alert-banner');
        if (alert) setTimeout(() => {
            alert.style.transition = '.4s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 400);
        }, 4000);
    </script>
</body>

</html>