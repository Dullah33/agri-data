<?php
require __DIR__ . '/../../middleware/auth.php';
require __DIR__ . '/../../config/koneksi.php';

$user = requireAuth('admin');

if (isset($_POST['save_admin'])) {
    $id_user    = $user['id_user'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username   = mysqli_real_escape_string($conn, $_POST['username']);
    $email      = mysqli_real_escape_string($conn, $_POST['email']);
    $password   = $_POST['password'];

    if (!empty($password)) {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET 
                first_name='$name', 
                username='$username', email='$email', password='$password_hashed' 
                WHERE id_user='$id_user'";
    } else {
        $sql = "UPDATE users SET 
                first_name='$name', 
                username='$username', email='$email' 
                WHERE id_user='$id_user'";
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: /pages/profile_admin.php?success=1");
        exit();
    } else {
        header("Location: /pages/profile_admin.php?error=1");
        exit();
    }
}

header("Location: /pages/profile_admin.php");
exit();
