<?php
require __DIR__ . '/../../middleware/auth.php';
require __DIR__ . '/../../config/koneksi.php';

$user = requireAuth('admin');

$page = 'data_panen';

// Ambil Data Admin
$id_user = $user['id_user'];
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
