<?php
require_once __DIR__ . '/../helpers/auth_cookie.php';

function requireAuth(?string $role = null): array
{
    $user = getAuthUser();

    if (!$user) {
        header("Location: /api/login.php");
        exit();
    }

    if ($role && $user['role'] !== $role) {
        header("Location: /api/login.php");
        exit();
    }

    return $user;
}
