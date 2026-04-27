<?php
require_once __DIR__ . '/../helpers/auth_cookie.php';

// ============================================================
// requireAuth($role)
// Cek login via cookie terenkripsi — works di Vercel serverless
// ============================================================
function requireAuth(?string $role = null): array
{
    $user = getAuthUser();

    if (!$user) {
        header("Location: /pages/login.php");
        exit();
    }

    if ($role && $user['role'] !== $role) {
        header("Location: /pages/login.php");
        exit();
    }

    return $user;
}
