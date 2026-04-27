<?php
require_once __DIR__ . '/helpers/auth_cookie.php';
clearAuthCookie();
header("Location: /api/login.php");
exit();
