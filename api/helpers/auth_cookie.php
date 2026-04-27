<?php
// ============================================================
// AUTH COOKIE HELPER
// Simpan data user di cookie terenkripsi (AES-256-CBC)
// Works di Vercel serverless (tidak butuh session PHP)
// ============================================================

define('COOKIE_SECRET', 'AGRIDATA_COOKIE_SECRET_2024_XYZ');
define('COOKIE_NAME',   'agri_auth');
define('COOKIE_TTL',    60 * 60 * 24); // 1 hari

// --- Enkripsi data ke string ---
function cookieEncrypt(array $data): string
{
    $json   = json_encode($data);
    $iv     = random_bytes(16);
    $cipher = openssl_encrypt($json, 'AES-256-CBC', COOKIE_SECRET, 0, $iv);
    return base64_encode($iv . '||' . $cipher);
}

// --- Dekripsi string ke array ---
function cookieDecrypt(string $value): ?array
{
    $raw   = base64_decode($value);
    $parts = explode('||', $raw, 2);
    if (count($parts) !== 2) return null;

    [$iv, $cipher] = $parts;
    if (strlen($iv) !== 16) return null;

    $json = openssl_decrypt($cipher, 'AES-256-CBC', COOKIE_SECRET, 0, $iv);
    if (!$json) return null;

    $data = json_decode($json, true);
    if (!$data || !isset($data['id_user'], $data['role'], $data['exp'])) return null;

    // Cek expiry
    if ($data['exp'] < time()) return null;

    return $data;
}

// --- Set cookie login ---
function setAuthCookie(array $user): void
{
    $payload = [
        'id_user'  => $user['id_user'],
        'name'     => $user['name'],
        'username' => $user['username'],
        'email'    => $user['email'],
        'role'     => $user['role'],
        'exp'      => time() + COOKIE_TTL,
    ];

    $value = cookieEncrypt($payload);

    setcookie(COOKIE_NAME, $value, [
        'expires'  => time() + COOKIE_TTL,
        'path'     => '/',
        'secure'   => true,   // HTTPS (Vercel selalu HTTPS)
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

// --- Hapus cookie (logout) ---
function clearAuthCookie(): void
{
    setcookie(COOKIE_NAME, '', [
        'expires'  => time() - 3600,
        'path'     => '/',
        'secure'   => true,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

// --- Baca user dari cookie ---
function getAuthUser(): ?array
{
    $value = $_COOKIE[COOKIE_NAME] ?? null;
    if (!$value) return null;
    return cookieDecrypt($value);
}
