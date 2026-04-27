<?php
session_start();
require __DIR__ . '/../../config/koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: ../../pages/login.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$page = 'profile';

$query = mysqli_query($conn, "SELECT * FROM users WHERE id_user = '$id_user'");
$raw = mysqli_fetch_assoc($query);

if (!$raw) {
    session_destroy();
    header("Location: ../../pages/login.php");
    exit();
}

// Map first_name -> 'name' agar form profile_user.php bisa pakai $data['name']
$data = $raw;
$data['name'] = $raw['name'];
