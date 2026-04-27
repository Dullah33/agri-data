<?php
require __DIR__ . '/../../middleware/auth.php';
require __DIR__ . '/../../config/koneksi.php';

$user = requireAuth('admin');

$id_user = $user['id_user'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id_user = '$id_user'"));
$page = 'profile';


