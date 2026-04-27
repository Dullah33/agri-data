<?php
require_once __DIR__ . '/../config/koneksi.php';

$api_key  = "b68f5cb385c55fa3d7e16cac48016cde";
$domain_id = "3519";
$var_id   = "123";
$url_bps  = "https://webapi.bps.go.id/v1/api/list/model/data/domain/$domain_id/var/$var_id/key/$api_key/";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_bps);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if ($err) die("cURL Error: " . $err);

$data_bps = json_decode($response, true);

if (isset($data_bps['data'][1]) && $data_bps['data'][1]['status'] == "OK") {
    $nilai_harga = $data_bps['data'][1]['data'][0]['nilai'];
    $tahun       = $data_bps['data'][1]['data'][0]['tahun'];
    $indikator   = "Harga Gabah Kering Panen Kab. Madiun";
    $satuan      = "Rp/Kg";

    $cek = mysqli_query($conn, "SELECT * FROM bps_statistik_makro WHERE indikator = '$indikator'");
    if (mysqli_num_rows($cek) > 0) {
        $query = "UPDATE bps_statistik_makro SET nilai = '$nilai_harga', tahun_periode = '$tahun' WHERE indikator = '$indikator'";
    } else {
        $query = "INSERT INTO bps_statistik_makro (indikator, nilai, satuan, tahun_periode) VALUES ('$indikator', '$nilai_harga', '$satuan', '$tahun')";
    }

    if (mysqli_query($conn, $query)) echo "Data BPS berhasil disinkronisasi ke database!";
    else echo "Error Database: " . mysqli_error($conn);
} else {
    echo "Gagal mengambil data dari BPS atau format JSON tidak sesuai.";
}
