<?php
session_start();

session_destroy(); // Menghapus semua session

// Mengarahkan kembali ke halaman login menggunakan jalur absolut browser
header("Location: ./login.php");
exit();
