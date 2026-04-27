<?php
require __DIR__ . '/../../middleware/auth.php';
require __DIR__ . '/../../config/koneksi.php';

$user = requireAuth('admin');

// Gunakan path koneksi yang benar (mundur 2 folder)
// Cek apakah ada data POST yang dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form pop-up
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $luas_panen = mysqli_real_escape_string($conn, $_POST['luas_panen']);
    $produktivitas = mysqli_real_escape_string($conn, $_POST['produktivitas']);
    $produksi = mysqli_real_escape_string($conn, $_POST['produksi']);

    // Query untuk update data
    $sql = "UPDATE data_panen SET 
            luas_panen = '$luas_panen', 
            produktivitas = '$produktivitas', 
            produksi = '$produksi' 
            WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Data Panen berhasil diperbarui!');
                // Kembali ke halaman tabel panen
                window.location.href = '../../pages/edit_data_panen_admin.php';
              </script>";
    } else {
        echo "Gagal mengupdate data: " . mysqli_error($conn);
    }
} else {
    // Jika diakses langsung tanpa lewat form, kembalikan ke halaman tabel
    header("Location: ../../pages/edit_data_panen_admin.php");
}
