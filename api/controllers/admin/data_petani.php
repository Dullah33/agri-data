<?php
session_start();

// Path mundur dua kali ke folder config
require __DIR__ . '/../../config/koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}

$page = 'data_petani';

// Ambil semua data petani
$query = mysqli_query($conn, "SELECT * FROM users WHERE role='user' ORDER BY id_user DESC");
