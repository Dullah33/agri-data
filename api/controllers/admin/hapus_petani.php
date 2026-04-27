<?php
// Hubungkan ke database
require_once __DIR__ . '/../../config/koneksi.php';

// Gunakan $conn karena di file koneksi.php kamu namanya $conn
if (!isset($conn)) {
    die("Error: Variabel koneksi ($conn) tidak ditemukan. Periksa file config/koneksi.php");
}

$id = $_GET['id'];

if (isset($id) && !empty($id)) {
    // Jalankan query menggunakan $conn
    $query = mysqli_query($conn, "DELETE FROM users WHERE id_user = '$id'");

    if ($query) {
        echo "<script>
                alert('Data petani berhasil dihapus!');
                window.location.href = '../../pages/data_petani_admin.php';
              </script>";
    } else {
        echo "Gagal menghapus: " . mysqli_error($conn);
    }
} else {
    header("Location: ../../pages/data_petani_admin.php");
}
