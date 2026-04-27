<?php
// Mencegah output sebelum header
ob_start();
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../config/koneksi.php';

$user = requireAuth('admin');
$page = 'data_petani';
$action = $_GET['action'] ?? 'list';
if ($action === 'panen') $page = 'data_panen';

// =============================================
// HANDLE POST ACTIONS (Proses Simpan/Update)
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- TAMBAH PETANI ---
    if (isset($_POST['simpan'])) {
        $id_user         = $_POST['id_user'];
        $name            = mysqli_real_escape_string($conn, $_POST['name']);
        $address         = mysqli_real_escape_string($conn, $_POST['address']);
        $dob             = $_POST['dob'];
        $gender          = $_POST['gender'];
        $phone           = mysqli_real_escape_string($conn, $_POST['phone']);
        $username        = mysqli_real_escape_string($conn, $_POST['username']);
        $email           = mysqli_real_escape_string($conn, $_POST['email']);
        $password_hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role            = "user";

        $sql = "INSERT INTO users (id_user, name, username, email, password, phone, address, gender, dob, role)
                VALUES ('$id_user', '$name', '$username', '$email', '$password_hashed', '$phone', '$address', '$gender', '$dob', '$role')";

        if (mysqli_query($conn, $sql)) {
            header("Location: /admin/petani?success=tambah");
            exit();
        } else {
            $error_msg = "Error: " . mysqli_error($conn);
        }
    }

    // --- EDIT PETANI ---
    if (isset($_POST['update'])) {
        $id_user  = $_POST['id_user'];
        $name     = mysqli_real_escape_string($conn, $_POST['name']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email    = mysqli_real_escape_string($conn, $_POST['email']);
        $address  = mysqli_real_escape_string($conn, $_POST['address']);
        $dob      = $_POST['dob'];
        $gender   = $_POST['gender'];
        $phone    = mysqli_real_escape_string($conn, $_POST['phone']);
        $status   = $_POST['status'];
        $password = $_POST['password'];

        if (!empty($password)) {
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET name='$name', username='$username', email='$email',
                    address='$address', dob='$dob', gender='$gender', phone='$phone',
                    status='$status', password='$password_hashed' WHERE id_user='$id_user'";
        } else {
            $sql = "UPDATE users SET name='$name', username='$username', email='$email',
                    address='$address', dob='$dob', gender='$gender', phone='$phone',
                    status='$status' WHERE id_user='$id_user'";
        }

        if (mysqli_query($conn, $sql)) {
            header("Location: /admin/petani?success=edit");
            exit();
        } else {
            $error_msg = "Error: " . mysqli_error($conn);
        }
    }

    // --- TAMBAH DATA PANEN ---
    if (isset($_POST['tambah_panen'])) {
        $provinsi      = strtoupper(mysqli_real_escape_string($conn, $_POST['provinsi']));
        $luas_panen    = mysqli_real_escape_string($conn, $_POST['luas_panen']);
        $produktivitas = mysqli_real_escape_string($conn, $_POST['produktivitas']);
        $produksi      = mysqli_real_escape_string($conn, $_POST['produksi']);
        $tahun         = date('Y');

        $sql = "INSERT INTO data_panen (provinsi, luas_panen, produktivitas, produksi, tahun) VALUES ('$provinsi', '$luas_panen', '$produktivitas', '$produksi', '$tahun')";
        if (mysqli_query($conn, $sql)) {
            header("Location: /admin/petani?action=panen&success=tambah");
            exit();
        }
    }
}

// =============================================
// HANDLE GET: HAPUS
// =============================================
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($conn, $_GET['hapus']);
    mysqli_query($conn, "DELETE FROM users WHERE id_user = '$id'");
    header("Location: /admin/petani?success=hapus");
    exit();
}

// =============================================
// DATA PREPARATION
// =============================================
if ($action === 'tambah') {
    $query_id = mysqli_query($conn, "SELECT MAX(id_user) as last_id FROM users WHERE role='user'");
    $data_id  = mysqli_fetch_assoc($query_id);
    $new_id   = "USR-" . sprintf("%03s", (int)substr($data_id['last_id'] ?? 'USR-000', 4) + 1);
}

if ($action === 'edit') {
    $id = $_GET['id'] ?? null;
    $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id_user = '$id'"));
    if (!$data) {
        header("Location: /admin/petani");
        exit();
    }
}

$query_petani = mysqli_query($conn, "SELECT * FROM users WHERE role='user' ORDER BY id_user DESC");
$q_panen = mysqli_query($conn, "SELECT * FROM data_panen ORDER BY provinsi ASC");
$data_panen_list = [];
if ($q_panen) {
    while ($row = mysqli_fetch_assoc($q_panen)) $data_panen_list[] = $row;
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
                    <div class="add-hero-badge">ID Otomatis: <?= $new_id ?></div>
                </div>
            </div>
            <form action="" method="POST">
                <input type="hidden" name="id_user" value="<?= $new_id ?>">
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
            <a href="/admin/petani" class="back-link"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
            <form action="" method="POST">
                <input type="hidden" name="id_user" value="<?= $data['id_user'] ?>">
                <div class="two-col-grid">
                    <div class="form-card">
                        <div class="section-head">
                            <h3>Edit Data</h3>
                        </div>
                        <div class="form-group"><label>Nama</label><input type="text" name="name" class="form-control" value="<?= $data['name'] ?>" required></div>
                        <div class="form-group"><label>Telepon</label><input type="text" name="phone" class="form-control" value="<?= $data['phone'] ?>" required></div>
                        <div class="form-group"><label>Alamat</label><textarea name="address" class="form-control" required><?= $data['address'] ?></textarea></div>
                    </div>
                    <div class="form-card">
                        <div class="form-group"><label>Status</label>
                            <select name="status" class="form-control">
                                <option value="Active" <?= $data['status'] == 'Active' ? 'selected' : '' ?>>Aktif</option>
                                <option value="Inactive" <?= $data['status'] == 'Inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="form-group"><label>Username</label><input type="text" name="username" class="form-control" value="<?= $data['username'] ?>" required></div>
                        <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" value="<?= $data['email'] ?>" required></div>
                        <div class="form-group"><label>Ganti Password <small>(Kosongkan jika tetap)</small></label><input type="password" name="password" class="form-control"></div>
                        <button type="submit" name="update" class="btn-save" style="width:100%; margin-top:20px; background:#2D6A4F; color:white; border:none; padding:15px; border-radius:10px; font-weight:700; cursor:pointer;">Simpan Perubahan</button>
                    </div>
                </div>
            </form>

        <?php elseif ($action === 'panen'): ?>
            <div class="dashboard-header">
                <h1>Manajemen Data Panen 🌾</h1>
                <button class="btn-primary" onclick="openAddModal()"><i class="fa-solid fa-plus"></i> Tambah Data Baru</button>
            </div>
            <div class="table-section" style="background:white; border-radius:15px; padding:20px; border:1px solid #f1f5f9;">
                <table class="admin-data-table" style="width:100%;">
                    <thead>
                        <tr style="background:#f8fafc;">
                            <th style="padding:15px; text-align:left;">PROVINSI</th>
                            <th style="padding:15px; text-align:right;">Luas (ha)</th>
                            <th style="padding:15px; text-align:right;">Produksi (ton)</th>
                            <th style="padding:15px; text-align:center;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_panen_list as $row): ?>
                            <tr style="border-bottom:1px solid #f1f5f9;">
                                <td style="padding:15px; font-weight:700;"><?= $row['provinsi'] ?></td>
                                <td style="padding:15px; text-align:right;"><?= number_format($row['luas_panen'], 2) ?></td>
                                <td style="padding:15px; text-align:right; font-weight:700; color:#2D6A4F;"><?= number_format($row['produksi'], 2) ?></td>
                                <td style="padding:15px; text-align:center;">
                                    <a href="?action=panen&hapus_panen=<?= $row['id'] ?>" style="color:#ef4444;" onclick="return confirm('Hapus data?')"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div id="addModal" class="modal-overlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
                <div style="background:white; padding:30px; border-radius:20px; width:100%; max-width:500px;">
                    <h3>Tambah Data Panen</h3>
                    <form action="" method="POST">
                        <div class="form-group" style="margin-top:15px;"><label>Provinsi</label><input type="text" name="provinsi" class="form-control" required></div>
                        <div class="form-group"><label>Luas Panen</label><input type="number" step="any" name="luas_panen" class="form-control" required></div>
                        <div class="form-group"><label>Produktivitas</label><input type="number" step="any" name="produktivitas" class="form-control" required></div>
                        <div class="form-group"><label>Produksi</label><input type="number" step="any" name="produksi" class="form-control" required></div>
                        <div style="margin-top:20px; display:flex; gap:10px;">
                            <button type="button" onclick="closeAddModal()" style="flex:1; padding:12px; border-radius:8px; border:1px solid #ccc;">Batal</button>
                            <button type="submit" name="tambah_panen" style="flex:1; padding:12px; border-radius:8px; background:#2D6A4F; color:white; border:none;">Simpan</button>
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