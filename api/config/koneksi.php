<?php
$host = "gateway01.ap-southeast-1.prod.alicloud.tidbcloud.com";
$port = 4000;
$user = "2PYtsc69VVAWGrB.root";
$pass = "yEfXagDr03g7t2EL";
$db   = "drive-agridata";

$conn = mysqli_init();

// WAJIB untuk TiDB Cloud
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

mysqli_real_connect(
    $conn,
    $host,
    $user,
    $pass,
    $db,
    $port,
    NULL,
    MYSQLI_CLIENT_SSL
);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>