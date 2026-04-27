<?php
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../config/koneksi.php';

$user = requireAuth('admin');
$page = 'data_petani';
$action = $_GET['action'] ?? 'list';
if ($action === 'panen') $page = 'data_panen';

// =============================================
// HANDLE POST ACTIONS (Proses Form)
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
            exit(); // ✅ Kunci agar redirect sukses di Vercel
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
            exit(); // ✅ Pastikan berhenti setelah header
        } else {
            $error_msg = "Error: " . mysqli_error($conn);
        }
    }

    // --- EDIT DATA PANEN ---
    if (isset($_POST['edit_panen'])) {
        $id            = mysqli_real_escape_string($conn, $_POST['id']);
        $luas_panen    = mysqli_real_escape_string($conn, $_POST['luas_panen']);
        $produktivitas = mysqli_real_escape_string($conn, $_POST['produktivitas']);
        $produksi      = mysqli_real_escape_string($conn, $_POST['produksi']);

        $sql = "UPDATE data_panen SET luas_panen='$luas_panen', produktivitas='$produktivitas', produksi='$produksi' WHERE id='$id'";
        if (mysqli_query($conn, $sql)) {
            header("Location: /admin/petani?action=panen&success=edit");
            exit();
        } else {
            $error_msg = mysqli_error($conn);
        }
    }

    // --- TAMBAH DATA PANEN ---
    if (isset($_POST['tambah_panen'])) {
        $provinsi      = strtoupper(mysqli_real_escape_string($conn, $_POST['provinsi']));
        $luas_panen    = mysqli_real_escape_string($conn, $_POST['luas_panen']);
        $produktivitas = mysqli_real_escape_string($conn, $_POST['produktivitas']);
        $produksi      = mysqli_real_escape_string($conn, $_POST['produksi']);
        $tahun         = date('Y');

        $sql = "INSERT INTO data_panen (provinsi, luas_panen, produktivitas, produksi, tahun) 
                VALUES ('$provinsi', '$luas_panen', '$produktivitas', '$produksi', '$tahun')";
        if (mysqli_query($conn, $sql)) {
            header("Location: /admin/petani?action=panen&success=tambah");
            exit();
        } else {
            $error_msg = mysqli_error($conn);
        }
    }
}

// =============================================
// HANDLE GET ACTIONS (Hapus)
// =============================================
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($conn, $_GET['hapus']);
    mysqli_query($conn, "DELETE FROM users WHERE id_user = '$id'");
    header("Location: /admin/petani?success=hapus");
    exit();
}
if (isset($_GET['hapus_panen'])) {
    $id = mysqli_real_escape_string($conn, $_GET['hapus_panen']);
    mysqli_query($conn, "DELETE FROM data_panen WHERE id = '$id'");
    header("Location: /admin/petani?action=panen&success=hapus");
    exit();
}

// =============================================
// DATA LOADING
// =============================================
if ($action === 'tambah') {
    $query_id = mysqli_query($conn, "SELECT MAX(id_user) as last_id FROM users WHERE role='user'");
    $data_id  = mysqli_fetch_assoc($query_id);
    $num = (int)substr($data_id['last_id'] ?? 'USR-000', 4) + 1;
    $new_id   = "USR-" . sprintf("%03s", $num);
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
    
    <?php if ($action === 'tambah' || $action === 'edit'): ?>
        <link rel="stylesheet" href="/assets/css/forms.css">
        <link rel="stylesheet" href="/assets/css/<?= $action ?>_petani_admin.css">
    
        <?php elseif ($action === 'panen'): ?>
        <link rel="stylesheet" href="/assets/css/edit_data_panen.css">
    
        <?php endif; ?>
</head>

<body>
    <?php include __DIR__ . '/partials/sidebar_admin.php'; ?>
    <main class="main-content">
        <?php include __DIR__ . '/partials/topbar_admin.php'; ?>

        <div style="padding: 20px;">
            <?php if (isset($_GET['success'])): ?>
                <div class="alert-banner success" style="margin-bottom:20px; padding:15px; background:#dcfce7; color:#15803d; border-radius:10px; border:1px solid #bbf7d0;">
                    <i class="fa-solid fa-circle-check"></i>
                    <?= ($_GET['success'] == 'tambah' ? 'Data berhasil ditambahkan!' : ($_GET['success'] == 'edit' ? 'Data berhasil diperbarui!' : 'Data berhasil dihapus!')) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error_msg)): ?>
                <div class="alert-banner error" style="margin-bottom:20px; padding:15px; background:#fee2e2; color:#b91c1c; border-radius:10px; border:1px solid #fecaca;">
                    <i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error_msg) ?>
                </div>
            <?php endif; ?>

            <?php if ($action === 'list' || !$action): ?>
                <div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
                    <div>
                        <h1 style="font-size:24px; font-weight:800;">Daftar Petani</h1>
                        <p style="color:#64748b; font-size:14px;">Kelola seluruh akun petani dalam satu panel.</p>
                    </div>
                    <a href="?action=tambah" class="btn-add-new" style="background:#2D6A4F; color:white; padding:12px 20px; border-radius:10px; text-decoration:none; font-weight:600; display:flex; align-items:center; gap:8px;">
                        <i class="fa-solid fa-user-plus"></i> Petani Baru
                    </a>
                </div>

                <div class="table-container" style="background:white; border-radius:15px; overflow:hidden; border:1px solid #f1f5f9; box-shadow:0 4px 15px rgba(0,0,0,0.03);">
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr style="background:#f8fafc; border-bottom:2px solid #f1f5f9;">
                                <th style="padding:15px; text-align:left; font-size:12px; color:#64748b; text-transform:uppercase;">Profil Petani</th>
                                <th style="padding:15px; text-align:left; font-size:12px; color:#64748b; text-transform:uppercase;">Kontak</th>
                                <th style="padding:15px; text-align:left; font-size:12px; color:#64748b; text-transform:uppercase;">Alamat</th>
                                <th style="padding:15px; text-align:left; font-size:12px; color:#64748b; text-transform:uppercase;">Status</th>
                                <th style="padding:15px; text-align:center; font-size:12px; color:#64748b; text-transform:uppercase;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($query_petani)): ?>
                                <tr style="border-bottom:1px solid #f1f5f9;">
                                    <td style="padding:15px;">
                                        <div style="display:flex; align-items:center; gap:12px;">
                                            <div style="width:40px; height:40px; border-radius:50%; background:#2D6A4F; color:white; display:flex; align-items:center; justify-content:center; font-weight:700;"><?= strtoupper(substr($row['name'], 0, 1)) ?></div>
                                            <div>
                                                <div style="font-weight:700; color:#0f172a;"><?= htmlspecialchars($row['name']) ?></div>
                                                <div style="font-size:12px; color:#94a3b8;">#<?= $row['id_user'] ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding:15px; font-size:14px;"><?= htmlspecialchars($row['phone']) ?><br><span style="color:#94a3b8;">@<?= htmlspecialchars($row['username']) ?></span></td>
                                    <td style="padding:15px; font-size:13px; color:#475569; max-width:200px;"><?= htmlspecialchars($row['address']) ?></td>
                                    <td style="padding:15px;">
                                        <span style="padding:4px 10px; border-radius:20px; font-size:11px; font-weight:700; background:<?= $row['status'] == 'Active' ? '#dcfce7; color:#166534;' : '#fee2e2; color:#991b1b;' ?>">
                                            <?= $row['status'] ?? 'Active' ?>
                                        </span>
                                    </td>
                                    <td style="padding:15px; text-align:center;">
                                        <div style="display:flex; justify-content:center; gap:8px;">
                                            <a href="?action=edit&id=<?= $row['id_user'] ?>" style="color:#3b82f6;"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <a href="?hapus=<?= $row['id_user'] ?>" style="color:#ef4444;" onclick="return confirm('Hapus petani ini?')"><i class="fa-solid fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($action === 'tambah'): ?>
                <div style="max-width:800px; margin:0 auto;">
                    <a href="/admin/petani" style="display:inline-block; margin-bottom:20px; text-decoration:none; color:#64748b; font-weight:600;"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
                    <form action="/admin/petani" method="POST">
                        <input type="hidden" name="id_user" value="<?= $new_id ?>">
                        <div style="background:white; border-radius:20px; padding:30px; border:1px solid #f1f5f9; box-shadow:0 10px 30px rgba(0,0,0,0.05);">
                            <h2 style="margin-bottom:25px;">Tambah Petani Baru <span style="font-size:14px; color:#94a3b8;">(ID: <?= $new_id ?>)</span></h2>
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                                <div class="form-group"><label>Nama Lengkap</label><input type="text" name="name" class="form-control" required></div>
                                <div class="form-group"><label>Username</label><input type="text" name="username" class="form-control" required></div>
                                <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                                <div class="form-group"><label>Password</label><input type="password" name="password" class="form-control" required></div>
                                <div class="form-group"><label>Telepon</label><input type="text" name="phone" class="form-control" required></div>
                                <div class="form-group"><label>Gender</label>
                                    <select name="gender" class="form-control" required>
                                        <option value="Male">Laki-laki</option>
                                        <option value="Female">Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group"><label>Tanggal Lahir</label><input type="date" name="dob" class="form-control" required></div>
                                <div class="form-group"><label>Alamat</label><textarea name="address" class="form-control" required></textarea></div>
                            </div>
                            <button type="submit" name="simpan" style="margin-top:25px; width:100%; background:#2D6A4F; color:white; border:none; padding:15px; border-radius:12px; font-weight:700; cursor:pointer;">Simpan Data Petani</button>
                        </div>
                    </form>
                </div>

            <?php elseif ($action === 'edit'): ?>
                <div style="max-width:800px; margin:0 auto;">
                    <a href="/admin/petani" style="display:inline-block; margin-bottom:20px; text-decoration:none; color:#64748b; font-weight:600;"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
                    <form action="/admin/petani" method="POST">
                        <input type="hidden" name="id_user" value="<?= $data['id_user'] ?>">
                        <div style="background:white; border-radius:20px; padding:30px; border:1px solid #f1f5f9; box-shadow:0 10px 30px rgba(0,0,0,0.05);">
                            <h2 style="margin-bottom:25px;">Edit Petani: <?= htmlspecialchars($data['name']) ?></h2>
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                                <div class="form-group"><label>Nama Lengkap</label><input type="text" name="name" class="form-control" value="<?= $data['name'] ?>" required></div>
                                <div class="form-group"><label>Username</label><input type="text" name="username" class="form-control" value="<?= $data['username'] ?>" required></div>
                                <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" value="<?= $data['email'] ?>" required></div>
                                <div class="form-group"><label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="Active" <?= $data['status'] == 'Active' ? 'selected' : '' ?>>Aktif</option>
                                        <option value="Inactive" <?= $data['status'] == 'Inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
                                    </select>
                                </div>
                                <div class="form-group"><label>Password <small>(Kosongkan jika tidak ganti)</small></label><input type="password" name="password" class="form-control"></div>
                                <div class="form-group"><label>Telepon</label><input type="text" name="phone" class="form-control" value="<?= $data['phone'] ?>" required></div>
                                <div class="form-group"><label>Gender</label>
                                    <select name="gender" class="form-control">
                                        <option value="Male" <?= $data['gender'] == 'Male' ? 'selected' : '' ?>>Laki-laki</option>
                                        <option value="Female" <?= $data['gender'] == 'Female' ? 'selected' : '' ?>>Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group"><label>Tanggal Lahir</label><input type="date" name="dob" class="form-control" value="<?= $data['dob'] ?>" required></div>
                                <div class="form-group" style="grid-column: span 2;"><label>Alamat</label><textarea name="address" class="form-control" required><?= $data['address'] ?></textarea></div>
                            </div>
                            <button type="submit" name="update" style="margin-top:25px; width:100%; background:#2D6A4F; color:white; border:none; padding:15px; border-radius:12px; font-weight:700; cursor:pointer;">Update Data Petani</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>