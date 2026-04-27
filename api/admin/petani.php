<?php
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../config/koneksi.php';

$user = requireAuth('admin');
$page = 'data_petani';
$action = $_GET['action'] ?? 'list';
if ($action === 'panen') $page = 'data_panen';

// =============================================
// HANDLE POST ACTIONS (proses form)
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
        } else {
            $error_msg = "Error: " . mysqli_error($conn);
        }
        if (!isset($error_msg)) exit();
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
        } else {
            $error_msg = "Error: " . mysqli_error($conn);
        }
        if (!isset($error_msg)) exit();
    }

    // --- EDIT DATA PANEN ---
    if (isset($_POST['edit_panen'])) {
        $id           = mysqli_real_escape_string($conn, $_POST['id']);
        $luas_panen   = mysqli_real_escape_string($conn, $_POST['luas_panen']);
        $produktivitas = mysqli_real_escape_string($conn, $_POST['produktivitas']);
        $produksi     = mysqli_real_escape_string($conn, $_POST['produksi']);

        $sql = "UPDATE data_panen SET luas_panen='$luas_panen', produktivitas='$produktivitas', produksi='$produksi' WHERE id='$id'";
        if (mysqli_query($conn, $sql)) {
            header("Location: /admin/petani?action=panen&success=edit");
        } else {
            $error_msg = mysqli_error($conn);
        }
        if (!isset($error_msg)) exit();
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
        } else {
            $error_msg = mysqli_error($conn);
        }
        if (!isset($error_msg)) exit();
    }
}

// =============================================
// HANDLE GET: HAPUS
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM users WHERE id_user = '$id'");
    header("Location: /admin/petani?success=hapus");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['hapus_panen'])) {
    $id = $_GET['hapus_panen'];
    mysqli_query($conn, "DELETE FROM data_panen WHERE id = '$id'");
    header("Location: /admin/petani?action=panen&success=hapus");
    exit();
}

// =============================================
// LOAD DATA SESUAI ACTION
// =============================================
if ($action === 'tambah') {
    $query_id = mysqli_query($conn, "SELECT MAX(id_user) as last_id FROM users WHERE role='user'");
    $data_id  = mysqli_fetch_assoc($query_id);
    $new_id   = "USR-" . sprintf("%03s", (int)substr($data_id['last_id'] ?? 'USR-000', 4) + 1);
}

if ($action === 'edit') {
    $id    = $_GET['id'] ?? null;
    $data  = null;
    if ($id) {
        $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id_user = '$id'"));
    }
    if (!$data) {
        header("Location: /admin/petani");
        exit();
    }
}

if ($action === 'panen' || $action === 'list' || !$action) {
    $query_petani = mysqli_query($conn, "SELECT * FROM users WHERE role='user' ORDER BY id_user DESC");
    $data_panen_list = [];
    $q_panen = mysqli_query($conn, "SELECT * FROM data_panen ORDER BY provinsi ASC");
    if ($q_panen) {
        while ($row = mysqli_fetch_assoc($q_panen)) $data_panen_list[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $action === 'tambah' ? 'Tambah Petani' : ($action === 'edit' ? 'Edit Petani' : ($action === 'panen' ? 'Data Panen' : 'Manajemen Petani')) ?> | AgriData</title>
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

        <!-- ========== LIST PETANI ========== -->
        <?php if ($action === 'list' || !$action || $action === ''): ?>
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
                                        <a href="?action=edit&id=<?= $row['id_user'] ?>" class="btn-icon btn-edit" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <a href="?hapus=<?= $row['id_user'] ?>" class="btn-icon btn-delete" onclick="return confirm('Hapus petani ini?')" title="Hapus"><i class="fa-solid fa-trash-can"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- ========== TAMBAH PETANI ========== -->
        <?php elseif ($action === 'tambah'): ?>
            <a href="/admin/petani" class="back-link"><i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Petani</a>
            <div class="add-hero">
                <div class="add-hero-icon"><i class="fa-solid fa-user-plus"></i></div>
                <div class="add-hero-info">
                    <h2>Tambah Petani Baru</h2>
                    <span>Daftarkan akun petani baru ke sistem AgriData</span>
                    <div>
                        <div class="add-hero-badge"><i class="fa-solid fa-id-badge" style="font-size:10px;"></i> ID Otomatis: <?= htmlspecialchars($new_id) ?></div>
                    </div>
                </div>
            </div>
            <form action="/admin/petani?action=tambah" method="POST">
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
                        <div class="form-group"><label>Nama Lengkap <span class="req">*</span></label><input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap..." required></div>
                        <div class="form-group"><label>Tanggal Lahir <span class="req">*</span></label><input type="date" name="dob" class="form-control" required></div>
                        <div class="form-group"><label>Jenis Kelamin <span class="req">*</span></label>
                            <select name="gender" class="form-control" required>
                                <option value="">-- Pilih Gender --</option>
                                <option value="Male">Laki-laki</option>
                                <option value="Female">Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group"><label>No. Telepon <span class="req">*</span></label><input type="text" name="phone" class="form-control" placeholder="08xxxxxxxxxx" required></div>
                        <div class="form-group"><label>Alamat <span class="req">*</span></label><textarea name="address" class="form-control" rows="3" placeholder="Masukkan alamat lengkap..." required></textarea></div>
                    </div>
                    <div class="form-card">
                        <div class="section-head">
                            <div class="section-head-icon"><i class="fa-solid fa-key"></i></div>
                            <div>
                                <h3>Data Akun & Login</h3>
                                <p>Username, email, dan password</p>
                            </div>
                        </div>
                        <div class="form-group"><label>Username <span class="req">*</span></label><input type="text" name="username" class="form-control" placeholder="username_petani" required></div>
                        <div class="form-group"><label>Email <span class="req">*</span></label><input type="email" name="email" class="form-control" placeholder="email@domain.com" required></div>
                        <div class="form-group"><label>Password <span class="req">*</span></label><input type="password" name="password" class="form-control" placeholder="••••••••" required></div>
                        <div class="form-group" style="margin-top:30px;">
                            <button type="submit" name="simpan" class="btn-save" style="width:100%;padding:14px;background:#2D6A4F;color:white;border:none;border-radius:10px;font-weight:700;font-size:15px;cursor:pointer;">
                                <i class="fa-solid fa-floppy-disk"></i> Simpan Petani Baru
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- ========== EDIT PETANI ========== -->
        <?php elseif ($action === 'edit'): ?>
            <a href="/admin/petani" class="back-link"><i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Petani</a>
            <div class="edit-hero">
                <div class="edit-hero-avatar"><?= strtoupper(substr($data['name'] ?? 'P', 0, 1)) ?></div>
                <div class="edit-hero-info">
                    <h2>Edit Profil: <?= htmlspecialchars($data['name'] ?? '') ?></h2>
                    <span><i class="fa-solid fa-id-badge" style="margin-right:5px;"></i>ID: <?= htmlspecialchars($data['id_user'] ?? '') ?></span>
                </div>
            </div>
            <form action="/admin/petani?action=edit" method="POST">
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
                        <div class="form-group"><label>Nama Lengkap <span class="req">*</span></label><input type="text" name="name" class="form-control" value="<?= htmlspecialchars($data['name'] ?? '') ?>" required></div>
                        <div class="form-group"><label>Tanggal Lahir <span class="req">*</span></label><input type="date" name="dob" class="form-control" value="<?= htmlspecialchars($data['dob'] ?? '') ?>" required></div>
                        <div class="form-group"><label>Jenis Kelamin <span class="req">*</span></label>
                            <select name="gender" class="form-control" required>
                                <option value="Male" <?= ($data['gender'] ?? '') == 'Male' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="Female" <?= ($data['gender'] ?? '') == 'Female' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group"><label>No. Telepon <span class="req">*</span></label><input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($data['phone'] ?? '') ?>" required></div>
                        <div class="form-group"><label>Alamat <span class="req">*</span></label><textarea name="address" class="form-control" rows="3" required><?= htmlspecialchars($data['address'] ?? '') ?></textarea></div>
                    </div>
                    <div class="form-card">
                        <div class="section-head">
                            <div class="section-head-icon"><i class="fa-solid fa-at"></i></div>
                            <div>
                                <h3>Data Akun</h3>
                                <p>Username, email, status</p>
                            </div>
                        </div>
                        <div class="form-group"><label>Username <span class="req">*</span></label><input type="text" name="username" class="form-control" value="<?= htmlspecialchars($data['username'] ?? '') ?>" required></div>
                        <div class="form-group"><label>Email <span class="req">*</span></label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($data['email'] ?? '') ?>" required></div>
                        <div class="form-group"><label>Status Akun</label>
                            <select name="status" class="form-control">
                                <option value="Active" <?= ($data['status'] ?? '') == 'Active' ? 'selected' : '' ?>>Aktif</option>
                                <option value="Inactive" <?= ($data['status'] ?? '') == 'Inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="form-group"><label>Password Baru <small>(kosongkan jika tidak ganti)</small></label><input type="password" name="password" class="form-control" placeholder="••••••••"></div>
                        <div class="form-group" style="margin-top:20px;">
                            <button type="submit" name="update" class="btn-save" style="width:100%;padding:14px;background:#2D6A4F;color:white;border:none;border-radius:10px;font-weight:700;font-size:15px;cursor:pointer;">
                                <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- ========== DATA PANEN ========== -->
        <?php elseif ($action === 'panen'): ?>
            <div class="dashboard-header">
                <div class="header-title-group">
                    <h1>Manajemen Data Panen 🌾</h1>
                    <p>Kelola entri luas panen, produktivitas, dan produksi padi tingkat provinsi.</p>
                </div>
                <div class="header-action">
                    <button class="btn-primary" onclick="openAddModal()">
                        <i class="fa-solid fa-plus"></i> Tambah Data Baru
                    </button>
                </div>
            </div>
            <div class="table-section">
                <div class="table-header-admin">
                    <h3>Luas Panen, Produksi, dan Produktivitas Padi (2024)</h3>
                    <div class="table-controls">
                        <input type="text" id="searchInput" class="search-input" placeholder="Cari provinsi..." onkeyup="searchTable()">
                        <button class="btn-outline" id="btnFilter" onclick="sortProduction()">
                            <i class="fa-solid fa-sort"></i> Urut Produksi
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="admin-data-table">
                        <thead>
                            <tr>
                                <th rowspan="2" class="align-left">38 PROVINSI</th>
                                <th colspan="3" class="text-center bg-blue-header">Rincian Data Panen Padi</th>
                                <th rowspan="2" class="text-center">AKSI</th>
                            </tr>
                            <tr>
                                <th class="align-right bg-blue-sub">Luas Panen (ha)</th>
                                <th class="align-right bg-blue-sub">Produktivitas (ku/ha)</th>
                                <th class="align-right bg-blue-sub">Produksi (ton)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data_panen_list as $row): ?>
                                <tr>
                                    <td class="font-bold"><?= htmlspecialchars($row['provinsi']) ?></td>
                                    <td class="align-right font-number"><?= number_format($row['luas_panen'], 2, ',', '.') ?></td>
                                    <td class="align-right font-number"><?= number_format($row['produktivitas'], 2, ',', '.') ?></td>
                                    <td class="align-right font-number font-bold-number"><?= number_format($row['produksi'], 2, ',', '.') ?></td>
                                    <td class="action-cells">
                                        <button type="button" class="btn-icon btn-edit" title="Edit Data"
                                            data-id="<?= $row['id'] ?>"
                                            data-prov="<?= htmlspecialchars($row['provinsi']) ?>"
                                            data-luas="<?= $row['luas_panen'] ?>"
                                            data-prod="<?= $row['produktivitas'] ?>"
                                            data-hasil="<?= $row['produksi'] ?>"
                                            onclick="openEditModal(this)">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <a href="?action=panen&hapus_panen=<?= $row['id'] ?>" class="btn-icon btn-delete" style="display:flex;text-decoration:none;align-items:center;justify-content:center;" onclick="return confirm('Hapus data <?= htmlspecialchars($row['provinsi']) ?>?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal Edit Panen -->
            <div class="modal-overlay" id="editModal">
                <div class="modal-box">
                    <div class="modal-header">
                        <h3><i class="fa-solid fa-pen-to-square" style="color:#3b82f6;"></i> Edit Data Panen</h3>
                        <button type="button" class="btn-close-modal" onclick="closeEditModal()"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <form action="/admin/petani?action=panen" method="POST">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="form-group"><label class="form-label">Nama Provinsi</label><input type="text" id="edit_provinsi" class="form-control" readonly style="background:#f1f5f9;color:#64748b;"></div>
                        <div class="form-group"><label class="form-label">Luas Panen (Hektar)</label><input type="number" step="any" name="luas_panen" id="edit_luas" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Produktivitas (Kuintal/Hektar)</label><input type="number" step="any" name="produktivitas" id="edit_produktivitas" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Produksi Padi (Ton)</label><input type="number" step="any" name="produksi" id="edit_produksi" class="form-control" required></div>
                        <div class="modal-actions" style="display:flex;justify-content:flex-end;gap:15px;margin-top:30px;">
                            <button type="button" onclick="closeEditModal()" style="padding:12px 24px;border-radius:8px;border:1px solid #cbd5e1;background:#fff;color:#475569;font-weight:600;cursor:pointer;">Batal</button>
                            <button type="submit" name="edit_panen" style="padding:12px 24px;border-radius:8px;border:none;background:#3b82f6;color:white;font-weight:600;cursor:pointer;">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Tambah Panen -->
            <div class="modal-overlay" id="addModal">
                <div class="modal-box">
                    <div class="modal-header">
                        <h3><i class="fa-solid fa-plus" style="color:#10b981;"></i> Tambah Data Panen</h3>
                        <button type="button" class="btn-close-modal" onclick="closeAddModal()"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <form action="/admin/petani?action=panen" method="POST">
                        <div class="form-group"><label class="form-label">Nama Provinsi</label><input type="text" name="provinsi" class="form-control" placeholder="Contoh: JAWA TENGAH" required></div>
                        <div class="form-group"><label class="form-label">Luas Panen (Hektar)</label><input type="number" step="any" name="luas_panen" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Produktivitas (Kuintal/Hektar)</label><input type="number" step="any" name="produktivitas" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Produksi Padi (Ton)</label><input type="number" step="any" name="produksi" class="form-control" required></div>
                        <div class="modal-actions" style="display:flex;justify-content:flex-end;gap:15px;margin-top:30px;">
                            <button type="button" onclick="closeAddModal()" style="padding:12px 24px;border-radius:8px;border:1px solid #cbd5e1;background:#fff;color:#475569;font-weight:600;cursor:pointer;">Batal</button>
                            <button type="submit" name="tambah_panen" style="padding:12px 24px;border-radius:8px;border:none;background:#10b981;color:white;font-weight:600;cursor:pointer;">Simpan Data Baru</button>
                        </div>
                    </form>
                </div>
            </div>
            <script src="/assets/js/edit_data_panen_admin.js"></script>
        <?php endif; ?>
    </main>
</body>

</html>