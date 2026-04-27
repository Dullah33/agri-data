<?php
session_start();
// Path koneksi sudah disesuaikan
require __DIR__ . '/../../config/koneksi.php';

if (isset($_POST['save_admin'])) {
    $id_user    = $_SESSION['id_user'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username   = mysqli_real_escape_string($conn, $_POST['username']);
    $email      = mysqli_real_escape_string($conn, $_POST['email']);
    $password   = $_POST['password'];

    // Update session nama agar otomatis berubah di Topbar
    $_SESSION['name'] = $name;

    if (!empty($password)) {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET 
                name='$name', 
                username='$username', email='$email', password='$password_hashed' 
                WHERE id_user='$id_user'";
    } else {
        $sql = "UPDATE users SET 
                name='$name', 
                username='$username', email='$email' 
                WHERE id_user='$id_user'";
    }

    if (mysqli_query($conn, $sql)) {
        // Ambil URL kembalian dari session, jika kosong default kembali ke dashboard
        $url_kembali = isset($_SESSION['kembali_ke']) ? $_SESSION['kembali_ke'] : '/api/pages/dashboard_admin.php';

        echo "<script>
                alert('Profil Admin Berhasil Diperbarui!'); 
                // Arahkan dinamis sesuai halaman terakhir
                window.location.href = '$url_kembali';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
