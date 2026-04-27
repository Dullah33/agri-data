<?php
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../config/koneksi.php';

$user = requireAuth('admin');
$page = 'data_petani';
$action = $_GET['action'] ?? 'list';
if ($action === 'panen') $page = 'data_panen';

// =============================================
// HANDLE POST ACTIONS (Hanya perbaikan logika redirect)
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action_post = $_POST['action_type'] ?? '';

    // --- TAMBAH PETANI ---
    if ($action_post === 'tambah' && isset($_POST['simpan'])) {
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
    if ($action_post === 'edit' && isset($_POST['update'])) {
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
// LOAD DATA
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
    <?php endif; ?>
</head>

<body>
    <?php include __DIR__ . '/partials/sidebar_admin.php'; ?>
    <main class="main-content">
        <?php include __DIR__ . '/partials/topbar_admin.php'; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert-banner success" style="margin:16px;padding:14px 20px;background:#f0fdf4;border:1px solid #86efac;border-radius:10px;color:#166534;font-weight:600;">
                <i class="fa-solid fa-circle-check"></i>
                <?php
                $msgs = ['tambah' => 'Data berhasil ditambahkan!', 'edit' => 'Data berhasil diperbarui!', 'hapus' => 'Data berhasil dihapus!'];
                echo $msgs[$_GET['success']] ?? 'Operasi berhasil!';
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_msg)): ?>
            <div class="alert-banner error" style="margin:16px;padding:14px 20px;background:#fef2f2;border:1px solid #fca5a5;border-radius:10px;color:#991b1b;">
                <i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error_msg) ?>
            </div>
        <?php endif; ?>

        <?php if ($action === 'list' || !$action): ?>
            <div class="page-header">
                <div class="page-info">
                    <h1>Daftar Petani</h1>
                    <p>Kelola dan pantau seluruh data akun petani AgriData.</p>
                </div>
                <a href="?action=tambah" class="btn-add-new">
                    <i class="fa-solid fa-user-plus"></i><span>Petani Baru</span>
                </a>
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
                                    <?php $status = $row['status'] ?? 'Active';
                                    $class = ($status == 'Active') ? 'status-active' : 'status-inactive'; ?>
                                    <span class="badge-status <?= $class ?>"><?= $status ?></span>
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
            <a href="/admin/petani" class="back-link"><i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Petani</a>
            <div class="add-hero">
                <div class="add-hero-icon"><i class="fa-solid fa-user-plus"></i></div>
                <div class="add-hero-info">
                    <h2>Tambah Petani Baru</h2>
                    <span>Daftarkan akun petani baru ke sistem AgriData</span>
                    <div class="add-hero-badge">ID Otomatis: <?= htmlspecialchars($new_id) ?></div>
                </div>
            </div>
            <form action="/admin/petani" method="POST">
                <input type="hidden" name="action_type" value="tambah">
                <input type="hidden" name="id_user" value="<?= htmlspecialchars($new_id) ?>">
                <div class="two-col-grid">
                    <div class="form-card">
                        <div class="section-head">
                            <div class="section-head-icon"><i class="fa-solid fa-user"></i></div>
                            <div>
                                <h3>Informasi Pribadi</h3>
                                <p>Data diri dan kontak petani</p>
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
                        <div class="form-group"><label>No. Telepon</label><input type="text" name="phone" class="form-control" required></div>
                        <div class="form-group"><label>Alamat</label><textarea name="address" class="form-control" rows="3" required></textarea></div>
                    </div>
                    <div class="form-card">
                        <div class="section-head">
                            <div class="section-head-icon"><i class="fa-solid fa-key"></i></div>
                            <div>
                                <h3>Data Akun</h3>
                                <p>Username, email, dan password</p>
                            </div>
                        </div>
                        <div class="form-group"><label>Username</label><input type="text" name="username" class="form-control" required></div>
                        <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                        <div class="form-group"><label>Password</label><input type="password" name="password" class="form-control" required></div>
                        <button type="submit" name="simpan" class="btn-save" style="width:100%; margin-top:20px; background:#2D6A4F; color:white; border:none; padding:15px; border-radius:10px; font-weight:700; cursor:pointer;">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Petani Baru
                        </button>
                    </div>
                </div>
            </form>

        <?php elseif ($action === 'edit'): ?>
            <a href="/admin/petani" class="back-link"><i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Petani</a>
            <div class="edit-hero">
                <div class="edit-hero-avatar"><?= strtoupper(substr($data['name'] ?? 'P', 0, 1)) ?></div>
                <div class="edit-hero-info">
                    <h2>Edit Profil: <?= htmlspecialchars($data['name'] ?? '') ?></h2>
                    <span>ID: <?= htmlspecialchars($data['id_user'] ?? '') ?></span>
                </div>
            </div>
            <form action="/admin/petani" method="POST">
                <input type="hidden" name="action_type" value="edit">
                <input type="hidden" name="id_user" value="<?= htmlspecialchars($data['id_user']) ?>">
                <div class="two-col-grid">
                    <div class="form-card">
                        <div class="section-head">
                            <div class="section-head-icon"><i class="fa-solid fa-user"></i></div>
                            <div>
                                <h3>Informasi Pribadi</h3>
                                <p>Data diri dan kontak petani</p>
                            </div>
                        </div>
                        <div class="form-group"><label>Nama Lengkap</label><input type="text" name="name" class="form-control" value="<?= htmlspecialchars($data['name'] ?? '') ?>" required></div>
                        <div class="form-group"><label>No. Telepon</label><input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($data['phone'] ?? '') ?>" required></div>
                        <div class="form-group"><label>Alamat</label><textarea name="address" class="form-control" rows="3" required><?= htmlspecialchars($data['address'] ?? '') ?></textarea></div>
                    </div>
                    <div class="form-card">
                        <div class="section-head">
                            <div class="section-head-icon"><i class="fa-solid fa-at"></i></div>
                            <div>
                                <h3>Data Akun</h3>
                                <p>Status dan Password</p>
                            </div>
                        </div>
                        <div class="form-group"><label>Status Akun</label>
                            <select name="status" class="form-control">
                                <option value="Active" <?= ($data['status'] ?? '') == 'Active' ? 'selected' : '' ?>>Aktif</option>
                                <option value="Inactive" <?= ($data['status'] ?? '') == 'Inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="form-group"><label>Password Baru <small>(Kosongkan jika tetap)</small></label><input type="password" name="password" class="form-control"></div>
                        <button type="submit" name="update" class="btn-save" style="width:100%; margin-top:20px; background:#2D6A4F; color:white; border:none; padding:15px; border-radius:10px; font-weight:700; cursor:pointer;">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </main>
</body>

</html>