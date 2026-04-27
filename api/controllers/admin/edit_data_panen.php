<?php
session_start();
// Gunakan path koneksi yang benar
require __DIR__ . '/../../config/koneksi.php';

// 1. Proteksi Halaman: Pastikan hanya peran Admin
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'super_admin')) {
    header("Location: ../../pages/login.php");
    exit();
}

$page = 'data_panen';

// 2. Ambil Data Pribadi Admin (Untuk Topbar)
$id_user = $_SESSION['id_user'];
$query_user = mysqli_query($conn, "SELECT * FROM users WHERE id_user = '$id_user'");
$u = mysqli_fetch_assoc($query_user);

// 3. MENGAMBIL DATA DARI DATABASE LOKAL HASIL SINKRONISASI BPS
$data_panen = [];

// Menggunakan nama tabel 'data_panen' sesuai dengan yang ada di file sync_api_data_panen_bps.php
$query_panen = mysqli_query($conn, "SELECT * FROM data_panen ORDER BY provinsi ASC");

if ($query_panen) {
    while ($row = mysqli_fetch_assoc($query_panen)) {
        $data_panen[] = $row;
    }
}
