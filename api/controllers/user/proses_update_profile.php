<?php
require __DIR__ . '/../../middleware/auth.php';
require __DIR__ . '/../../config/koneksi.php';

$user = requireAuth('user');

$id_user = $user['id_user'];

if (isset($_POST['update_profile'])) {

    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    mysqli_query($conn, "UPDATE users SET 
        name='$name',
        username='$username',
        email='$email'
        WHERE id_user='$id_user'");

    header("Location: /pages/profile_user.php?success=1");
}
