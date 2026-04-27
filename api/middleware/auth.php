<?php
// ==========================
// REQUIRE LOGIN (SESSION)
// ==========================
function requireAuth($role = null)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['id_user'])) {
        header("Location: /pages/login.php");
        exit();
    }

    // Cek role jika diperlukan
    if ($role && $_SESSION['role'] !== $role) {
        header("Location: /pages/login.php");
        exit();
    }

    return [
        'id_user'  => $_SESSION['id_user'],
        'name'     => $_SESSION['name'],
        'username' => $_SESSION['username'],
        'email'    => $_SESSION['email'],
        'role'     => $_SESSION['role'],
    ];
}
