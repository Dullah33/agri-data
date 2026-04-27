<?php
// 1. Panggil koneksi database Anda
require_once __DIR__ . '/../../config/koneksi.php';

// 2. Konfigurasi API BPS
$api_key = "b68f5cb385c55fa3d7e16cac48016cde";
$domain_id = "3519"; // Contoh Kode Kabupaten Madiun (Bisa dicari di webapi BPS)
$var_id = "123"; // Contoh ID Variabel untuk "Harga Produsen"

// Struktur URL BPS biasanya seperti ini (cek dokumentasi resmi untuk format pastinya)
$url_bps = "https://webapi.bps.go.id/v1/api/list/model/data/domain/$domain_id/var/$var_id/key/$api_key/";

// 3. Inisialisasi cURL untuk request ke BPS
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_bps);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// Opsional: nonaktifkan verifikasi SSL jika di localhost terjadi error sertifikat
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if ($err) {
    die("cURL Error: " . $err);
}

// 4. Ubah JSON dari BPS menjadi Array PHP
$data_bps = json_decode($response, true);

// 5. Cek apakah status request sukses ("OK")
if (isset($data_bps['data'][1]) && $data_bps['data'][1]['status'] == "OK") {

    // Asumsi: data nilai ada di dalam array data_bps (Bentuk array BPS cukup kompleks, Anda harus dump/print_r dulu untuk melihat strukturnya)
    // Ini contoh penyederhanaan ekstraksi data:
    $nilai_harga = $data_bps['data'][1]['data'][0]['nilai'];
    $tahun = $data_bps['data'][1]['data'][0]['tahun'];

    $indikator = "Harga Gabah Kering Panen Kab. Madiun";
    $satuan = "Rp/Kg";

    // 6. Simpan atau Update ke Database Anda
    // Cek apakah data indikator ini sudah ada
    $cek = mysqli_query($conn, "SELECT * FROM bps_statistik_makro WHERE indikator = '$indikator'");

    if (mysqli_num_rows($cek) > 0) {
        // Update data jika sudah ada
        $query = "UPDATE bps_statistik_makro SET nilai = '$nilai_harga', tahun_periode = '$tahun' WHERE indikator = '$indikator'";
    } else {
        // Insert data baru
        $query = "INSERT INTO bps_statistik_makro (indikator, nilai, satuan, tahun_periode) VALUES ('$indikator', '$nilai_harga', '$satuan', '$tahun')";
    }

    if (mysqli_query($conn, $query)) {
        echo "Data BPS berhasil disinkronisasi ke database!";
    } else {
        echo "Error Database: " . mysqli_error($conn);
    }
} else {
    echo "Gagal mengambil data dari BPS atau format JSON tidak sesuai.";
    // Uncomment baris di bawah untuk melihat struktur asli dari BPS jika error
    // echo "<pre>"; print_r($data_bps); echo "</pre>";
}
