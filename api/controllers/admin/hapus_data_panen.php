<?php
require __DIR__ . '/../../middleware/auth.php';
require __DIR__ . '/../../config/koneksi.php';

$user = requireAuth('admin');

// Gunakan path koneksi yang benar (mundur 2 folder)
// Ambil ID dari URL
$id = $_GET['id'] ?? null;

if ($id) {
    // Jalankan query hapus berdasarkan ID
    $query = mysqli_query($conn, "DELETE FROM data_panen WHERE id = '$id'");

    if ($query) {
        echo "<script>
                alert('Data panen provinsi berhasil dihapus!');
                // Kembali ke halaman tabel menggunakan Absolute Path
                window.location.href = '../../pages/edit_data_panen_admin.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus data: " . mysqli_error($conn) . "');
                window.location.href = '../../pages/edit_data_panen_admin.php';
              </script>";
    }
} else {
    // Jika tidak ada ID, langsung kembalikan ke halaman tabel
    header("Location: ../../pages/edit_data_panen_admin.php");
    exit();
}
