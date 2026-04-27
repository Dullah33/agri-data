<?php
session_start();
// 1. Panggil sistem autentikasi (sesuaikan path-nya jika AuthController ada di root controllers)
require_once __DIR__ . '/../../config/koneksi.php';

// 2. Proteksi Halaman (Pastikan hanya 'user' yang bisa akses)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit();
}

// 3. Logika Bisnis: Ambil data nama user
$nama_user = 'Pengguna';
if (isset($_SESSION['nama'])) {
    $nama_user = $_SESSION['nama'];
} elseif (isset($_SESSION['username'])) {
    $nama_user = $_SESSION['username'];
}

// CATATAN: 
// Jika ke depannya kamu punya logika untuk mengambil data GeoJSON (titik koordinat) 
// dari database MySQL kamu, letakkan query-nya di sini!
// Contoh: $data_koordinat = mysqli_query($koneksi, "SELECT * FROM peta");
?>