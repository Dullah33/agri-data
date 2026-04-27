<?php
// File: controllers/user/dashboard_user_controller.php

session_start();
require __DIR__ . '/../../config/koneksi.php';

// 1. Proteksi: Cek Session
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: ../../pages/login.php");
    exit();
}

// 2. Inisialisasi Penanda Halaman
$page = 'dashboard';
$search_placeholder = "Cari info harga pasar...";

// 3. Ambil Data Pribadi User
$id_user = $_SESSION['id_user'];
$query_user = mysqli_query($conn, "SELECT * FROM users WHERE id_user = '$id_user'");
$u = mysqli_fetch_assoc($query_user);

// 4. Integrasi Data API BPS Langsung (Pendekatan Dinamis)
$data_petani_2023 = [];
$url_bps = "https://sensus.bps.go.id/topik/tabular/st2023/242/98808/3";

// Menggunakan cURL untuk stabilitas koneksi HTTP ke server BPS
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_bps);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$json_data = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code === 200 && $json_data !== false) {
    $response = json_decode($json_data, true);

    if (isset($response['data'])) {
        $temp_data = [];

        // Memproses struktur JSON mentah menjadi format yang siap disajikan
        foreach ($response['data'] as $row) {
            $provinsi = $row['nama_wilayah'];
            $indikator = $row['nama_indikator'];
            $nilai = (int)$row['nilai'];

            // Inisialisasi array untuk setiap provinsi yang baru terdeteksi
            if (!isset($temp_data[$provinsi])) {
                $temp_data[$provinsi] = [
                    'provinsi'   => $provinsi,
                    'rt_petani'  => 0,
                    'jml_petani' => 0
                ];
            }

            // Memilah nilai berdasarkan nama indikator yang dikirimkan BPS
            if (strpos(strtolower($indikator), 'rumah tangga') !== false) {
                $temp_data[$provinsi]['rt_petani'] = $nilai;
            } else {
                // Jika URL JSON nantinya memuat indikator Jumlah Petani juga
                $temp_data[$provinsi]['jml_petani'] = $nilai;
            }
        }

        // Mengonversi array asosiatif (key provinsi) kembali menjadi array indeks numerik
        $data_petani_2023 = array_values($temp_data);
    }
} else {
    // Penanganan error jika server BPS mengalami gangguan (Timeout/500)
    error_log("Koneksi API BPS Gagal. HTTP Code: " . $http_code);
}
