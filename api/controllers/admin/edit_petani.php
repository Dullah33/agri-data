<?php
session_start();

// Path koneksi
require __DIR__ . '/../../config/koneksi.php';

// Perbaikan 1: Gunakan Absolute Path untuk redirect login
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../../pages/login.php");
    exit();
}

$page = 'data_petani';

// 1. Ambil ID dari URL
$id = $_GET['id'] ?? null;
if (!$id) {
    // Perbaikan 2: Gunakan Absolute Path untuk alert redirect
    echo "<script>alert('ID tidak ditemukan!'); window.location.href='../../pages/dashboard_admin.php';</script>";
    exit();
}

// 2. Ambil data lama dari database
$query = mysqli_query($conn, "SELECT * FROM users WHERE id_user = '$id'");
$data = mysqli_fetch_assoc($query);

// Jika ID salah atau data tidak ada
if (!$data) {
    // Perbaikan 3: Gunakan Absolute Path untuk alert redirect
    echo "<script>alert('Data petani tidak ditemukan!'); window.location.href='../../pages/dashboard_admin.php';</script>";
    exit();
}
