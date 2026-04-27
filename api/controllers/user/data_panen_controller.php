<?php
// Sesuaikan path koneksi database Anda
require_once __DIR__ . '/../../config/koneksi.php';

function getSemuaDataPanen($tahun = 2024)
{
    global $conn;
    // Mengambil data berdasarkan tahun, diurutkan berdasarkan id
    $query = "SELECT * FROM data_panen WHERE tahun = ? ORDER BY id ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $tahun);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

$data_panen = getSemuaDataPanen(2024);
?>