<?php

function base64url_encode($data)
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data)
{
    return base64_decode(strtr($data, '-_', '+/'));
}

// ==========================
// GENERATE ACCESS TOKEN (1 JAM)
// ==========================
function generateJWT($payload, $secret = "AGRIDATA_SECRET_KEY")
{
    $header = json_encode([
        'typ' => 'JWT',
        'alg' => 'HS256'
    ]);

    $payload['exp'] = time() + 3600;

    $base64Header = base64url_encode($header);
    $base64Payload = base64url_encode(json_encode($payload));

    $signature = hash_hmac('sha256', "$base64Header.$base64Payload", $secret, true);
    $base64Signature = base64url_encode($signature);

    return "$base64Header.$base64Payload.$base64Signature";
}

// ==========================
// GENERATE REFRESH TOKEN (7 HARI)
// ==========================
function generateRefreshToken($payload)
{
    $payload['exp'] = time() + (7 * 24 * 60 * 60);
    return generateJWT($payload);
}

// ==========================
// VERIFY TOKEN
// ==========================
function verifyJWT($jwt, $secret = "AGRIDATA_SECRET_KEY")
{
    $parts = explode('.', $jwt);
    if (count($parts) !== 3) return false;

    [$header, $payload, $signature] = $parts;

    $validSignature = base64url_encode(
        hash_hmac('sha256', "$header.$payload", $secret, true)
    );

    if (!hash_equals($validSignature, $signature)) return false;

    $decoded = json_decode(base64url_decode($payload), true);

    if (isset($decoded['exp']) && $decoded['exp'] < time()) {
        return false;
    }

    return $decoded;
}

// ==========================
// AUTO AUTH + REFRESH
// ==========================
function checkAuth()
{
    $token = $_COOKIE['token'] ?? null;
    $refresh = $_COOKIE['refresh_token'] ?? null;

    // 1. Access token valid
    if ($token) {
        $user = verifyJWT($token);
        if ($user) return $user;
    }

    // 2. Refresh token
    if ($refresh) {
        $user = verifyJWT($refresh);
        if ($user) {
            $newToken = generateJWT($user);

            setcookie("token", $newToken, [
                'expires' => time() + 3600,
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Strict'
            ]);

            return $user;
        }
    }

    return false;
}