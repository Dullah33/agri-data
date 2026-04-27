<?php
session_start();
// Path koneksi sudah benar (../../)
require __DIR__ . '/../../config/koneksi.php';

if (isset($_POST['simpan'])) {
    $id_user    = $_POST['id_user'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $address    = mysqli_real_escape_string($conn, $_POST['address']);
    $dob        = $_POST['dob'];
    $gender     = $_POST['gender'];
    $phone      = mysqli_real_escape_string($conn, $_POST['phone']);

    // MENANGKAP INPUT BARU
    $username   = mysqli_real_escape_string($conn, $_POST['username']);
    $email      = mysqli_real_escape_string($conn, $_POST['email']);
    $password_raw = $_POST['password'];

    // Enkripsi Password agar aman
    $password_hashed = password_hash($password_raw, PASSWORD_DEFAULT);

    $role = "user";

    // Query Insert (Sudah ditambahkan kolom dob agar tanggal lahir ikut tersimpan)
    $sql = "INSERT INTO users (id_user, name, username, email, password, phone, address, gender, dob, role) 
            VALUES ('$id_user', '$name', '$username', '$email', '$password_hashed', '$phone', '$address', '$gender', '$dob', '$role')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Akun Petani Berhasil Dibuat!');
                // PERBAIKAN: Gunakan Absolute Path agar tidak Not Found saat kembali ke halaman tabel
                window.location.href = '../../pages/data_petani_admin.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
