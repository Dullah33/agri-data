<?php
setcookie("token", "", time() - 3600, "/");
setcookie("refresh_token", "", time() - 3600, "/");

header("Location: /pages/login.php");
exit();
