<?php
// 1. Panggil controller
require __DIR__ . '/../controllers/admin/data_petani.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Petani | AgriData</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/sidebar_admin.css">
    <link rel="stylesheet" href="../../assets/css/topbar_admin.css">
    <link rel="stylesheet" href="../../assets/css/data_petani.css">

</head>

<body>
    <?php include 'sidebar_admin.php'; ?>

    <main class="main-content">
        <?php include 'topbar_admin.php'; ?>

        <div class="page-header">
            <div class="page-info">
                <h1>Daftar Petani</h1>
                <p>Kelola dan pantau seluruh data akun petani AgriData.</p>
            </div>
            <a href="tambah_petani_admin.php" class="btn-add-new">
                <i class="fa-solid fa-user-plus"></i>
                <span>Petani Baru</span>
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
                        <th style="text-align: center;">Kelola</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td>
                                <div class="profile-cell">
                                    <div class="avatar-initial"><?php echo strtoupper(substr($row['name'], 0, 1)); ?></div>
                                    <div>
                                        <span class="user-name"><?php echo $row['name']; ?></span>
                                        <span class="user-sub">ID: #<?php echo $row['id_user']; ?></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 500;"><?php echo $row['phone']; ?></div>
                                <div class="user-sub">@<?php echo $row['username']; ?></div>
                            </td>
                            <td>
                                <div style="max-width: 200px; line-height: 1.4; font-size: 13px;">
                                    <?php echo $row['address']; ?>
                                </div>
                            </td>
                            <td>
                                <?php
                                $status = $row['status'] ?? 'Active';
                                $class = ($status == 'Active') ? 'status-active' : 'status-inactive';
                                ?>
                                <span class="badge-status <?php echo $class; ?>">
                                    <?php echo $status; ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-group">
                                    <a href="edit_petani_admin.php?id=<?= $row['id_user'] ?>"
                                     class="btn-icon btn-edit" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="../controllers/admin/hapus_petani.php?id=<?= $row['id_user']; ?>" class="btn-icon btn-delete"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus petani ini?')" title="Hapus">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>