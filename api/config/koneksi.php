<?php
$host = "gateway01.ap-southeast-1.prod.alicloud.tidbcloud.com";
$port = 4000;
$user = "2PYtsc69VVAWGrB.root";
$pass = "yEfXagDr03g7t2EL";
$db   = "drive-agridata";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
