<?php
session_start();
require __DIR__ . '/../../config/koneksi.php';

if (isset($_POST['update_profile'])) {
    $id_user  = $_SESSION['id_user'];
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $dob      = mysqli_real_escape_string($conn, $_POST['dob']);
    $address  = mysqli_real_escape_string($conn, $_POST['address']);
    $phone    = mysqli_real_escape_string($conn, $_POST['phone']);
    $gender   = mysqli_real_escape_string($conn, $_POST['gender']);
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET 
                name='$name', username='$username', email='$email',
                dob='$dob', address='$address', phone='$phone', gender='$gender',
                password='$hashed_pass'
                WHERE id_user='$id_user'";
    } else {
        $sql = "UPDATE users SET 
                name='$name', username='$username', email='$email',
                dob='$dob', address='$address', phone='$phone', gender='$gender'
                WHERE id_user='$id_user'";
    }

    if (mysqli_query($conn, $sql)) {
        $_SESSION['name']     = $name;
        header("Location: ../../pages/profile_user.php?success=1");
        exit();
    } else {
        header("Location: ../../pages/profile_user.php?error=1");
        exit();
    }
}
