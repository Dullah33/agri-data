<?php
session_start();
// Path mundur dua kali ke folder config
require __DIR__ . '/../../config/koneksi.php';

// Perbaikan keamanan redirect agar tidak ERR_UNSAFE_REDIRECT
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../../pages/login.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id_user = '$id_user'"));
$page = 'profile'; // Agar sidebar tahu kita di halaman profil

// ============================================================
// LOGIKA MENGINGAT HALAMAN SEBELUMNYA
// ============================================================
// Jika ada data halaman asal, dan asalnya BUKAN dari halaman profil itu sendiri
if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'profile_admin.php') === false) {
    // Simpan URL tersebut ke dalam brankas Session
    $_SESSION['kembali_ke'] = $_SERVER['HTTP_REFERER'];
}
