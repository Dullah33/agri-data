<?php
session_start();

// Perhatikan path koneksi mundur dua kali (../../) karena file ini ada di folder controllers/admin/
require __DIR__ . '/../../config/koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../../pages/login.php");
    exit();
}

// Logika buat ID baru otomatis
$query_id = mysqli_query($conn, "SELECT MAX(id_user) as last_id FROM users WHERE role='user'");
$data_id = mysqli_fetch_assoc($query_id);
$new_id = "USR-" . sprintf("%03s", (int)substr($data_id['last_id'], 4) + 1);

$page = 'data_petani';
