<?php
session_start();
// Path koneksi sudah benar
require __DIR__ . '/../../config/koneksi.php';

if (isset($_POST['update'])) {
    $id_user    = $_POST['id_user'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username   = mysqli_real_escape_string($conn, $_POST['username']);
    $email      = mysqli_real_escape_string($conn, $_POST['email']);
    $address    = mysqli_real_escape_string($conn, $_POST['address']);
    $dob        = $_POST['dob'];
    $gender     = $_POST['gender'];
    $phone      = mysqli_real_escape_string($conn, $_POST['phone']);
    $status     = $_POST['status'];
    $password   = $_POST['password'];

    // 1. Logika Update (Cek apakah ganti password atau tidak)
    if (!empty($password)) {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET 
                name='$name', username='$username', email='$email', 
                address='$address', dob='$dob', gender='$gender', phone='$phone', 
                status='$status', password='$password_hashed' 
                WHERE id_user='$id_user'";
    } else {
        $sql = "UPDATE users SET 
                name='$name', username='$username', email='$email', 
                address='$address', dob='$dob', gender='$gender', phone='$phone', 
                status='$status' 
                WHERE id_user='$id_user'";
    }

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Data Petani Berhasil Diperbarui!'); 
                // PERBAIKAN: Arahkan kembali ke folder admin/
                window.location.href = '../../pages/data_petani_admin.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
