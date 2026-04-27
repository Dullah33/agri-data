<?php
require __DIR__ . '/../controllers/user/profile_user_controller.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya | AgriData</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/sidebar_user.css">
    <link rel="stylesheet" href="../../assets/css/topbar_user.css">
    <link rel="stylesheet" href="../../assets/css/profile_user.css">
</head>

<body>
    <?php include 'sidebar_user.php'; ?>
    <main class="main-content">
        <?php include 'topbar_user.php'; ?>

        <div class="profile-wrapper">

            <?php if (isset($_GET['success'])): ?>
                <div class="alert-banner success">
                    <i class="fa-solid fa-circle-check"></i> Profil berhasil diperbarui!
                </div>
            <?php elseif (isset($_GET['error'])): ?>
                <div class="alert-banner error">
                    <i class="fa-solid fa-circle-exclamation"></i> Gagal memperbarui profil. Coba lagi.
                </div>
            <?php endif; ?>

            <!-- HERO -->
            <div class="hero-card">
                <div class="hero-avatar">
                    <?= strtoupper(substr($data['name'] ?? 'U', 0, 1)) ?>
                </div>
                <div class="hero-meta">
                    <h2><?= htmlspecialchars($data['name'] ?? '-') ?></h2>
                    <div class="hero-username">@<?= htmlspecialchars($data['username'] ?? '-') ?></div>
                    <div class="hero-badge">
                        <i class="fa-solid fa-seedling" style="font-size:11px;"></i>
                        Petani Terdaftar
                    </div>
                </div>
            </div>

            <form action="../controllers/user/proses_update_profile.php" method="POST">

                <div class="profile-grid">

                    <!-- KOLOM KIRI: Info Pribadi -->
                    <div class="form-card">
                        <div class="card-header">
                            <div class="card-header-icon"><i class="fa-solid fa-user"></i></div>
                            <div>
                                <h3>Informasi Pribadi</h3>
                                <p>Data diri dan kontak</p>
                            </div>
                        </div>

                        <div class="fg">
                            <label>Nama Lengkap</label>
                            <input type="text" name="name"
                                value="<?= htmlspecialchars($data['name'] ?? '') ?>" required>
                        </div>
                        <div class="fg">
                            <label>Tanggal Lahir</label>
                            <input type="date" name="dob"
                                value="<?= htmlspecialchars($data['dob'] ?? '') ?>">
                        </div>
                        <div class="fg">
                            <label>Jenis Kelamin</label>
                            <select name="gender">
                                <option value="">-- Pilih --</option>
                                <option value="Male" <?= ($data['gender'] ?? '') == 'Male'   ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="Female" <?= ($data['gender'] ?? '') == 'Female' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                        <div class="fg">
                            <label>No HP</label>
                            <input type="text" name="phone"
                                value="<?= htmlspecialchars($data['phone'] ?? '') ?>">
                        </div>
                        <div class="fg">
                            <label>Alamat</label>
                            <textarea name="address"><?= htmlspecialchars($data['address'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- KOLOM KANAN: Akun & Keamanan -->
                    <div style="display: flex; flex-direction: column; gap: 20px;">

                        <div class="form-card">
                            <div class="card-header">
                                <div class="card-header-icon"><i class="fa-solid fa-at"></i></div>
                                <div>
                                    <h3>Data Akun</h3>
                                    <p>Username dan email login</p>
                                </div>
                            </div>

                            <div class="fg">
                                <label>Username</label>
                                <input type="text" name="username"
                                    value="<?= htmlspecialchars($data['username'] ?? '') ?>" required>
                            </div>
                            <div class="fg">
                                <label>Email</label>
                                <input type="email" name="email"
                                    value="<?= htmlspecialchars($data['email'] ?? '') ?>" required>
                            </div>

                            <!-- Info readonly -->
                            <div style="margin-top: 14px; display: flex; flex-direction: column; gap: 8px;">
                                <div class="info-chip">
                                    <i class="fa-solid fa-id-badge"></i>
                                    <span>ID: <strong><?= htmlspecialchars($data['id_user'] ?? '-') ?></strong></span>
                                </div>
                                <div class="info-chip">
                                    <i class="fa-solid fa-circle-dot" style="color: <?= strtolower($data['status'] ?? '') == 'active' ? '#22c55e' : '#ef4444' ?>;"></i>
                                    <span>Status: <strong><?= htmlspecialchars($data['status'] ?? '-') ?></strong></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-card">
                            <div class="card-header">
                                <div class="card-header-icon"><i class="fa-solid fa-lock"></i></div>
                                <div>
                                    <h3>Keamanan</h3>
                                    <p>Kosongkan jika tidak ganti</p>
                                </div>
                            </div>

                            <div class="fg">
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

                    </div>

                    <!-- TOMBOL SIMPAN - full width -->
                    <div class="form-card full-width" style="padding: 20px 28px;">
                        <button type="submit" name="update_profile" class="btn-save">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Semua Perubahan
                        </button>
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

        // Auto-hide alert after 4s
        const alert = document.querySelector('.alert-banner');
        if (alert) setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = '.4s';
            setTimeout(() => alert.remove(), 400);
        }, 4000);
    </script>
</body>

</html>