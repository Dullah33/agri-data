<?php
require __DIR__ . '/../../middleware/auth.php';
require __DIR__ . '/../../config/koneksi.php';

$user = requireAuth('user');

// ================= DATA PANEN =================
function getSemuaDataPanen($tahun = 2024)
{
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM data_panen WHERE tahun=? ORDER BY id ASC");
    $stmt->bind_param("i", $tahun);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

$data_panen = getSemuaDataPanen(2024);
