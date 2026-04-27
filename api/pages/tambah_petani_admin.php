<?php
require __DIR__ . '/../controllers/admin/tambah_petani.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Petani - AgriData</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/sidebar_admin.css">
    <link rel="stylesheet" href="../../assets/css/topbar_admin.css">
    <link rel="stylesheet" href="../../assets/css/forms.css">
    <link rel="stylesheet" href="../../assets/css/data_petani.css">
    <link rel="stylesheet" href="../../assets/css/tambah_petani_admin.css">
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
        <div class="add-hero">
            <div class="add-hero-icon">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <div class="add-hero-info">
                <h2>Tambah Petani Baru</h2>
                <span>Daftarkan akun petani baru ke sistem AgriData</span>
                <div>
                    <div class="add-hero-badge">
                        <i class="fa-solid fa-id-badge" style="font-size:10px;"></i>
                        ID Otomatis: <?= htmlspecialchars($new_id) ?>
                    </div>
                </div>
            </div>
        </div>

        <form action="../controllers/admin/proses_tambah_petani.php" method="POST">
            <input type="hidden" name="id_user" value="<?= htmlspecialchars($new_id) ?>">

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
                            placeholder="Masukkan nama lengkap..." required>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Lahir <span class="req">*</span></label>
                        <input type="date" name="dob" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Jenis Kelamin <span class="req">*</span></label>
                        <select name="gender" class="form-control" required>
                            <option value="">-- Pilih Gender --</option>
                            <option value="Male">Laki-laki</option>
                            <option value="Female">Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>No. Telepon <span class="req">*</span></label>
                        <input type="text" name="phone" class="form-control"
                            placeholder="08..." required>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label>Alamat Lengkap <span class="req">*</span></label>
                        <textarea name="address" class="form-control" rows="4"
                            placeholder="Masukkan alamat lengkap..." required
                            style="resize: vertical;"></textarea>
                    </div>
                </div>

                <!-- KOLOM KANAN: Akun & Keamanan -->
                <div style="display: flex; flex-direction: column; gap: 24px;">

                    <div class="form-card">
                        <div class="section-head">
                            <div class="section-head-icon"><i class="fa-solid fa-at"></i></div>
                            <div>
                                <h3>Data Akun</h3>
                                <p>Kredensial login petani</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Username <span class="req">*</span></label>
                            <input type="text" name="username" class="form-control"
                                placeholder="Contoh: budisudaryo" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label>Email <span class="req">*</span></label>
                            <input type="email" name="email" class="form-control"
                                placeholder="budi@email.com" required>
                        </div>
                    </div>

                    <div class="form-card">
                        <div class="section-head">
                            <div class="section-head-icon"><i class="fa-solid fa-lock"></i></div>
                            <div>
                                <h3>Keamanan</h3>
                                <p>Password awal akun petani</p>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom:0;">
                            <label>Password <span class="req">*</span></label>
                            <div class="pass-wrap">
                                <input type="password" name="password" id="passField" class="form-control"
                                    placeholder="Minimal 6 karakter..." required>
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
                <button type="reset" class="btn-reset">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </button>
                <button type="submit" name="simpan" class="btn-save">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Data Petani
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