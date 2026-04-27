<?php
require_once __DIR__ . '/../helpers/jwt_helper.php';

// ==========================
// GET TOKEN DARI COOKIE
// ==========================
function getToken()
{
    return $_COOKIE['token'] ?? null;
}

// ==========================
// SET COOKIE TOKEN
// ==========================
function setToken($token)
{
    setcookie("token", $token, [
        'expires' => time() + (60 * 60 * 24), // 1 hari
        'path' => '/',
        'secure' => false, // true kalau https
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}

// ==========================
// REQUIRE LOGIN + AUTO REFRESH
// ==========================
function requireAuth($role = null)
{
    $token = getToken();

    if (!$token) {
        header("Location: /pages/login.php");
        exit();
    }

    $decoded = verifyJWT($token);

    // ❌ Token invalid
    if (!$decoded) {
        header("Location: /pages/login.php");
        exit();
    }

    // 🔐 Cek role
    if ($role && $decoded['role'] !== $role) {
        header("Location: /pages/login.php");
        exit();
    }

    // ==========================
    // 🔁 AUTO REFRESH TOKEN
    // ==========================
    $timeLeft = $decoded['exp'] - time();

    // Kalau sisa < 10 menit → refresh
    if ($timeLeft < 600) {
        unset($decoded['exp']); // hapus exp lama
        $newToken = generateJWT($decoded);
        setToken($newToken);
    }

    return $decoded;
}
