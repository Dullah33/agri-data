<?php
require __DIR__ . '/../controllers/admin/edit_petani.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Petani - AgriData</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/sidebar_admin.css">
    <link rel="stylesheet" href="../../assets/css/topbar_admin.css">
    <link rel="stylesheet" href="../../assets/css/forms.css">
    <link rel="stylesheet" href="../../assets/css/data_petani.css">
    <link rel="stylesheet" href="../../assets/css/edit_petani_admin.css">
</head>

<body>
    <?php include 'sidebar_admin.php'; ?>
    <main class="main-content">
        <?php include 'topbar_admin.php'; ?>

        <!-- BACK -->
        <a href="data_petani_admin.php" class="back-link">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Petani
        </a>

        <!-- HERO -->
        <div class="edit-hero">
            <div class="edit-hero-avatar">
                <?= strtoupper(substr($data['name'] ?? 'P', 0, 1)) ?>
            </div>
            <div class="edit-hero-info">
                <h2>Edit Profil: <?= htmlspecialchars($data['name'] ?? '') ?></h2>
                <span><i class="fa-solid fa-id-badge" style="margin-right:5px;"></i>ID: <?= htmlspecialchars($data['id_user'] ?? '') ?></span>
            </div>
        </div>

        <form action="../controllers/admin/proses_edit_petani.php" method="POST">
            <input type="hidden" name="id_user" value="<?= htmlspecialchars($data['id_user']) ?>">

            <div class="two-col-grid">

                <!-- KOLOM KIRI: Info Pribadi -->
                <div class="form-card">
                    <div class="section-head">
                        <div class="section-head-icon"><i class="fa-solid fa-user"></i></div>
                        <div>
                            <h3>Informasi Pribadi</h3>
                            <p>Data diri dan kontak petani</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Nama Lengkap <span class="req">*</span></label>
                        <input type="text" name="name" class="form-control"
                            value="<?= htmlspecialchars($data['name'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Lahir <span class="req">*</span></label>
                        <input type="date" name="dob" class="form-control"
                            value="<?= htmlspecialchars($data['dob'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Jenis Kelamin <span class="req">*</span></label>
                        <select name="gender" class="form-control" required>
                            <option value="Male" <?= ($data['gender'] ?? '') == 'Male'   ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="Female" <?= ($data['gender'] ?? '') == 'Female' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>No. Telepon <span class="req">*</span></label>
                        <input type="text" name="phone" class="form-control"
                            value="<?= htmlspecialchars($data['phone'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Alamat Lengkap <span class="req">*</span></label>
                        <textarea name="address" class="form-control" rows="4" required><?= htmlspecialchars($data['address'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- KOLOM KANAN: Akun & Keamanan -->
                <div style="display: flex; flex-direction: column; gap: 24px;">

                    <div class="form-card">
                        <div class="section-head">
                            <div class="section-head-icon"><i class="fa-solid fa-at"></i></div>
                            <div>
                                <h3>Data Akun</h3>
                                <p>Username, email, dan status akun</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Username <span class="req">*</span></label>
                            <input type="text" name="username" class="form-control"
                                value="<?= htmlspecialchars($data['username'] ?? '') ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Email <span class="req">*</span></label>
                            <input type="email" name="email" class="form-control"
                                value="<?= htmlspecialchars($data['email'] ?? '') ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Status Petani <span class="req">*</span></label>
                            <select name="status" class="form-control" required>
                                <option value="Active" <?= ($data['status'] ?? '') == 'Active'   ? 'selected' : '' ?>>✅ Active (Aktif)</option>
                                <option value="Inactive" <?= ($data['status'] ?? '') == 'Inactive' ? 'selected' : '' ?>>🚫 Inactive (Tidak Aktif)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-card">
                        <div class="section-head">
                            <div class="section-head-icon"><i class="fa-solid fa-lock"></i></div>
                            <div>
                                <h3>Keamanan</h3>
                                <p>Kosongkan jika tidak ganti password</p>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom:0;">
                            <label>Password Baru</label>
                            <div class="pass-wrap">
                                <input type="password" name="password" id="passField" class="form-control"
                                    placeholder="••••••••">
                                <span class="pass-eye" onclick="togglePass()">
                                    <i class="fa-regular fa-eye" id="eyeIcon"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- ACTION BAR -->
            <div class="form-actions-bar">
                <a href="data_petani_admin.php" class="btn-cancel">
                    <i class="fa-solid fa-xmark"></i> Batal
                </a>
                <button type="submit" name="update" class="btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Petani
                </button>
            </div>

        </form>
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
    </script>
</body>

</html>