<?php
require __DIR__ . '/../../middleware/auth.php';
require __DIR__ . '/../../config/koneksi.php';

$user = requireAuth('admin');

$page = 'data_petani';

// 1. Ambil ID dari URL
$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href='/pages/dashboard_admin.php';</script>";
    exit();
}

$query = mysqli_query($conn, "SELECT * FROM users WHERE id_user = '$id'");
$data = mysqli_fetch_assoc($query);

// Jika ID salah atau data tidak ada
if (!$data) {
    // Perbaikan 3: Gunakan Absolute Path untuk alert redirect
    echo "<script>alert('Data petani tidak ditemukan!'); window.location.href='../../pages/dashboard_admin.php';</script>";
    exit();
}