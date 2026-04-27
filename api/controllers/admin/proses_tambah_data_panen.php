<?php
require __DIR__ . '/../../middleware/auth.php';
require __DIR__ . '/../../config/koneksi.php';

$user = requireAuth('admin');

// Gunakan path koneksi mundur 2 folder
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form dan pastikan provinsi diubah jadi huruf besar (uppercase) agar seragam
    $provinsi = strtoupper(mysqli_real_escape_string($conn, $_POST['provinsi']));
    $luas_panen = mysqli_real_escape_string($conn, $_POST['luas_panen']);
    $produktivitas = mysqli_real_escape_string($conn, $_POST['produktivitas']);
    $produksi = mysqli_real_escape_string($conn, $_POST['produksi']);
    $tahun = date('Y'); // Set otomatis tahun saat ini (2024)

    // Query Insert ke database
    $sql = "INSERT INTO data_panen (provinsi, luas_panen, produktivitas, produksi, tahun) 
            VALUES ('$provinsi', '$luas_panen', '$produktivitas', '$produksi', '$tahun')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Data Panen Provinsi Baru berhasil ditambahkan!');
                window.location.href = '../../pages/edit_data_panen_admin.php';
              </script>";
    } else {
        echo "Gagal menambah data: " . mysqli_error($conn);
    }
} else {
    header("Location: ../../pages/edit_data_panen_admin.php");
}
