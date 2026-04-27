<?php
ob_start();

require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../config/koneksi.php';

$user = requireAuth('admin');
$page = 'data_petani';
$action = $_GET['action'] ?? 'list';
if ($action === 'panen') $page = 'data_panen';

// =============================================
// HANDLE POST
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ================= TAMBAH PETANI =================
    if (isset($_POST['simpan'])) {

        $name     = $_POST['name'] ?? '';
        $address  = $_POST['address'] ?? '';
        $dob      = $_POST['dob'] ?? '';
        $gender   = $_POST['gender'] ?? '';
        $phone    = $_POST['phone'] ?? '';
        $username = $_POST['username'] ?? '';
        $email    = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!$name || !$username || !$email || !$password) {
            $error_msg = "Data wajib belum lengkap!";
        } else {

            $password_hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users 
                (name, username, email, password, phone, address, gender, dob, role, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'user', 'Active')");

            $stmt->bind_param(
                "ssssssss",
                $name,
                $username,
                $email,
                $password_hashed,
                $phone,
                $address,
                $gender,
                $dob
            );

            if ($stmt->execute()) {
                if (ob_get_length()) ob_end_clean();
                header("Location: /admin/petani?success=tambah");
                exit();
            } else {
                $error_msg = "DB Error: " . $stmt->error;
            }
        }
    }

    // ================= EDIT PETANI =================
    if (isset($_POST['update'])) {

        $id_user  = (int) $_POST['id_user'];
        $name     = $_POST['name'];
        $username = $_POST['username'];
        $email    = $_POST['email'];
        $address  = $_POST['address'];
        $dob      = $_POST['dob'];
        $gender   = $_POST['gender'];
        $phone    = $_POST['phone'];
        $status   = $_POST['status'];
        $password = $_POST['password'];

        if (!empty($password)) {
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("UPDATE users SET 
                name=?, username=?, email=?, address=?, dob=?, gender=?, phone=?, status=?, password=?
                WHERE id_user=?");

            $stmt->bind_param(
                "sssssssssi",
                $name,
                $username,
                $email,
                $address,
                $dob,
                $gender,
                $phone,
                $status,
                $password_hashed,
                $id_user
            );
        } else {

            $stmt = $conn->prepare("UPDATE users SET 
                name=?, username=?, email=?, address=?, dob=?, gender=?, phone=?, status=?
                WHERE id_user=?");

            $stmt->bind_param(
                "ssssssssi",
                $name,
                $username,
                $email,
                $address,
                $dob,
                $gender,
                $phone,
                $status,
                $id_user
            );
        }

        if ($stmt->execute()) {
            ob_end_clean();
            header("Location: /admin/petani?success=edit");
            exit();
        }
    }

    // ================= TAMBAH PANEN =================
    if (isset($_POST['tambah_panen'])) {

        $provinsi      = strtoupper($_POST['provinsi']);
        $luas_panen    = $_POST['luas_panen'];
        $produktivitas = $_POST['produktivitas'];
        $produksi      = $_POST['produksi'];
        $tahun         = date('Y');

        $stmt = $conn->prepare("INSERT INTO data_panen 
            (provinsi, luas_panen, produktivitas, produksi, tahun)
            VALUES (?, ?, ?, ?, ?)");

        $stmt->bind_param("sdddi", $provinsi, $luas_panen, $produktivitas, $produksi, $tahun);
        $stmt->execute();

        ob_end_clean();
        header("Location: /admin/petani?action=panen&success=tambah");
        exit();
    }
}

// ================= HAPUS =================
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id_user=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    ob_end_clean();
    header("Location: /admin/petani?success=hapus");
    exit();
}

// ================= DATA =================
if ($action === 'edit') {
    $id = (int) ($_GET['id'] ?? 0);

    $stmt = $conn->prepare("SELECT * FROM users WHERE id_user=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();

    if (!$data) {
        header("Location: /admin/petani");
        exit();
    }
}

$query_petani = mysqli_query($conn, "SELECT * FROM users WHERE role='user' ORDER BY id_user DESC");
$q_panen = mysqli_query($conn, "SELECT * FROM data_panen ORDER BY provinsi ASC");

$data_panen_list = [];
if ($q_panen) {
    while ($row = mysqli_fetch_assoc($q_panen)) {
        $data_panen_list[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Petani | AgriData</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/sidebar_admin.css">
    <link rel="stylesheet" href="/assets/css/topbar_admin.css">
    <link rel="stylesheet" href="/assets/css/data_petani.css">
    <?php if ($action === 'tambah'): ?>
        <link rel="stylesheet" href="/assets/css/forms.css">
        <link rel="stylesheet" href="/assets/css/tambah_petani_admin.css">
    <?php elseif ($action === 'edit'): ?>
        <link rel="stylesheet" href="/assets/css/forms.css">
        <link rel="stylesheet" href="/assets/css/edit_petani_admin.css">
    <?php elseif ($action === 'panen'): ?>
        <link rel="stylesheet" href="/assets/css/edit_data_panen.css">
    <?php endif; ?>
</head>

<body>
    <?php include __DIR__ . '/partials/sidebar_admin.php'; ?>
    <main class="main-content">
        <?php include __DIR__ . '/partials/topbar_admin.php'; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert-banner success" style="margin:16px;padding:14px 20px;background:#f0fdf4;border:1px solid #86efac;border-radius:10px;color:#166534;font-weight:600;">
                <i class="fa-solid fa-circle-check"></i>
                <?= ($_GET['success'] == 'tambah' ? 'Data berhasil ditambahkan!' : ($_GET['success'] == 'edit' ? 'Data berhasil diperbarui!' : 'Data berhasil dihapus!')) ?>
            </div>
        <?php endif; ?>

        <?php if ($action === 'list' || !$action): ?>
            <div class="page-header">
                <div class="page-info">
                    <h1>Daftar Petani</h1>
                    <p>Kelola dan pantau seluruh data akun petani AgriData.</p>
                </div>
                <a href="?action=tambah" class="btn-add-new"><i class="fa-solid fa-user-plus"></i><span>Petani Baru</span></a>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Profil Petani</th>
                            <th>Kontak & Akun</th>
                            <th>Alamat Lokasi</th>
                            <th>Status</th>
                            <th style="text-align:center;">Kelola</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($query_petani)): ?>
                            <tr>
                                <td>
                                    <div class="profile-cell">
                                        <div class="avatar-initial"><?= strtoupper(substr($row['name'], 0, 1)) ?></div>
                                        <div>
                                            <span class="user-name"><?= htmlspecialchars($row['name']) ?></span>
                                            <span class="user-sub">ID: #<?= $row['id_user'] ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight:500;"><?= htmlspecialchars($row['phone']) ?></div>
                                    <div class="user-sub">@<?= htmlspecialchars($row['username']) ?></div>
                                </td>
                                <td>
                                    <div style="max-width:200px;line-height:1.4;font-size:13px;"><?= htmlspecialchars($row['address']) ?></div>
                                </td>
                                <td>
                                    <span class="badge-status <?= ($row['status'] == 'Active' ? 'status-active' : 'status-inactive') ?>"><?= $row['status'] ?? 'Active' ?></span>
                                </td>
                                <td>
                                    <div class="action-group">
                                        <a href="?action=edit&id=<?= $row['id_user'] ?>" class="btn-icon btn-edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <a href="?hapus=<?= $row['id_user'] ?>" class="btn-icon btn-delete" onclick="return confirm('Hapus petani ini?')"><i class="fa-solid fa-trash-can"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($action === 'tambah'): ?>
            <a href="/admin/petani" class="back-link"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
            <div class="add-hero">
                <div class="add-hero-icon"><i class="fa-solid fa-user-plus"></i></div>
                <div class="add-hero-info">
                    <h2>Tambah Petani Baru</h2>
                </div>
            </div>
            <form action="" method="POST">
                <div class="two-col-grid">
                    <div class="form-card">
                        <div class="section-head">
                            <div class="section-head-icon"><i class="fa-solid fa-user"></i></div>
                            <div>
                                <h3>Informasi Pribadi</h3>
                                <p>Data diri petani</p>
                            </div>
                        </div>
                        <div class="form-group"><label>Nama Lengkap</label><input type="text" name="name" class="form-control" required></div>
                        <div class="form-group"><label>Tanggal Lahir</label><input type="date" name="dob" class="form-control" required></div>
                        <div class="form-group"><label>Jenis Kelamin</label>
                            <select name="gender" class="form-control" required>
                                <option value="Male">Laki-laki</option>
                                <option value="Female">Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group"><label>Telepon</label><input type="text" name="phone" class="form-control" required></div>
                        <div class="form-group"><label>Alamat</label><textarea name="address" class="form-control" rows="3" required></textarea></div>
                    </div>
                    <div class="form-card">
                        <div class="section-head">
                            <div class="section-head-icon"><i class="fa-solid fa-key"></i></div>
                            <div>
                                <h3>Akun</h3>
                                <p>Login petani</p>
                            </div>
                        </div>
                        <div class="form-group"><label>Username</label><input type="text" name="username" class="form-control" required></div>
                        <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                        <div class="form-group"><label>Password</label><input type="password" name="password" class="form-control" required></div>
                        <button type="submit" name="simpan" class="btn-save" style="width:100%; margin-top:20px; background:#2D6A4F; color:white; border:none; padding:15px; border-radius:10px; font-weight:700; cursor:pointer;">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Petani
                        </button>
                    </div>
                </div>
            </form>

        <?php elseif ($action === 'edit'): ?>
            <a href="/admin/petani" class="back-link">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>

            <form action="" method="POST">
                <input type="hidden" name="id_user" value="<?= $data['id_user'] ?>">

                <div class="two-col-grid">

                    <!-- KIRI -->
                    <div class="form-card">
                        <div class="section-head">
                            <div class="section-head-icon">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div>
                                <h3>Informasi Petani</h3>
                                <p>Edit data petani</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="name" class="form-control"
                                value="<?= htmlspecialchars($data['name']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Telepon</label>
                            <input type="text" name="phone" class="form-control"
                                value="<?= htmlspecialchars($data['phone']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea name="address" class="form-control" required><?= htmlspecialchars($data['address']) ?></textarea>
                        </div>
                    </div>

                    <!-- KANAN -->
                    <div class="form-card">
                        <div class="section-head">
                            <div class="section-head-icon">
                                <i class="fa-solid fa-key"></i>
                            </div>
                            <div>
                                <h3>Akun</h3>
                                <p>Pengaturan login</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="Active" <?= ($data['status'] == 'Active') ? 'selected' : '' ?>>Aktif</option>
                                <option value="Inactive" <?= ($data['status'] == 'Inactive') ? 'selected' : '' ?>>Tidak Aktif</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control"
                                value="<?= htmlspecialchars($data['username']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control"
                                value="<?= htmlspecialchars($data['email']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Ganti Password <small>(Kosongkan jika tidak diubah)</small></label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <button type="submit" name="update"
                            class="btn-save"
                            style="width:100%; margin-top:20px; background:#2D6A4F; color:white; border:none; padding:15px; border-radius:10px; font-weight:700; cursor:pointer;">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                        </button>
                    </div>

                </div>
            </form>

        <?php elseif ($action === 'panen'): ?>

            <div class="page-header">
                <div class="page-info">
                    <h1>Data Panen</h1>
                    <p>Kelola data hasil panen seluruh provinsi</p>
                </div>
                <button class="btn-add-new" onclick="openAddModal()">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah Data</span>
                </button>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Provinsi</th>
                            <th style="text-align:right;">Luas Panen (Ha)</th>
                            <th style="text-align:right;">Produksi (Ton)</th>
                            <th style="text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_panen_list as $row): ?>
                            <tr>
                                <td style="font-weight:600;">
                                    <?= htmlspecialchars($row['provinsi']) ?>
                                </td>

                                <td style="text-align:right;">
                                    <?= number_format($row['luas_panen'], 2) ?>
                                </td>

                                <td style="text-align:right; font-weight:600; color:#2D6A4F;">
                                    <?= number_format($row['produksi'], 2) ?>
                                </td>

                                <td style="text-align:center;">
                                    <a href="?action=panen&hapus_panen=<?= $row['id'] ?>"
                                        class="btn-icon btn-delete"
                                        onclick="return confirm('Hapus data ini?')">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- MODAL TAMBAH -->
            <div id="addModal" class="modal-overlay"
                style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">

                <div style="background:white; padding:30px; border-radius:20px; width:100%; max-width:500px;">

                    <h3 style="margin-bottom:10px;">Tambah Data Panen</h3>
                    <p style="font-size:13px; color:#64748b; margin-bottom:20px;">
                        Masukkan data panen terbaru
                    </p>

                    <form action="" method="POST">

                        <div class="form-group">
                            <label>Provinsi</label>
                            <input type="text" name="provinsi" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Luas Panen (Ha)</label>
                            <input type="number" step="any" name="luas_panen" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Produktivitas</label>
                            <input type="number" step="any" name="produktivitas" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Produksi (Ton)</label>
                            <input type="number" step="any" name="produksi" class="form-control" required>
                        </div>

                        <div style="margin-top:20px; display:flex; gap:10px;">
                            <button type="button" onclick="closeAddModal()"
                                style="flex:1; padding:12px; border-radius:10px; border:1px solid #ccc;">
                                Batal
                            </button>

                            <button type="submit" name="tambah_panen"
                                style="flex:1; padding:12px; border-radius:10px; background:#2D6A4F; color:white; border:none; font-weight:600;">
                                Simpan
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <script>
                function openAddModal() {
                    document.getElementById('addModal').style.display = 'flex';
                }

                function closeAddModal() {
                    document.getElementById('addModal').style.display = 'none';
                }
            </script>

        <?php endif; ?>
    </main>
</body>

</html>