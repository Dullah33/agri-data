<?php
require __DIR__ . '/../../middleware/auth.php';
require __DIR__ . '/../../config/koneksi.php';

$user = requireAuth('user');

// Ambil nama user dari JWT payload
$nama_user = $user['name'] ?? 'Pengguna';

// CATATAN: 
// Jika ke depannya kamu punya logika untuk mengambil data GeoJSON (titik koordinat) 
// dari database MySQL kamu, letakkan query-nya di sini!
// Contoh: $data_koordinat = mysqli_query($conn, "SELECT * FROM peta");
?>
