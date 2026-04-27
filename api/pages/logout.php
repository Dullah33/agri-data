<?php
require __DIR__ . '/../helpers/auth_cookie.php';

clearAuthCookie();

header("Location: /pages/login.php");
exit();
