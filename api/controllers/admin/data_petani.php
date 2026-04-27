<?php
require __DIR__ . '/../../middleware/auth.php';
require __DIR__ . '/../../config/koneksi.php';

$user = requireAuth('admin');

$page = 'data_petani';

// Ambil semua data petani
$query = mysqli_query($conn, "SELECT * FROM users WHERE role='user' ORDER BY id_user DESC");
$page = 'data_petani';

// Ambil semua data petani
$query = mysqli_query($conn, "SELECT * FROM users WHERE role='user' ORDER BY id_user DESC");
