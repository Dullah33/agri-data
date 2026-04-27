<?php
// 1. Perbaikan Path Koneksi (Sesuaikan dengan nama file koneksi Anda, database.php atau koneksi.php)
require_once __DIR__ . '/../../config/koneksi.php';

echo "<h2>Proses Sinkronisasi API BPS (Tabel 1498)...</h2>";

$api_key = "b68f5cb385c55fa3d7e16cac48016cde";
// Endpoint View untuk Static Table
$url_api_bps = "https://webapi.bps.go.id/v1/api/list/model/data/lang/ind/domain/0000/var/1498/th/123/key/$api_key/";

// Tarik Data
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_api_bps);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
curl_close($ch);

$dataBps = json_decode($response, true);

// Cek apakah brankas 'datacontent' ada
if (!isset($dataBps['datacontent'])) {
    echo "<h3 style='color:red;'>Struktur BPS berubah, gagal menemukan datacontent.</h3>";
    echo "<pre>";
    print_r($dataBps);
    echo "</pre>";
    die();
}

// 2. Kosongkan tabel lokal sebelum diisi baru
$conn->query("TRUNCATE TABLE data_panen");

$jumlah_sukses = 0;
$tahun_data = 2024;

// 3. Ekstrak ID Pintar dari BPS
$var_id = $dataBps['var'][0]['val'];
$turvar_luas = $dataBps['turvar'][0]['val'];     // ID Luas Panen (1191)
$turvar_prodtiv = $dataBps['turvar'][1]['val'];  // ID Produktivitas (1192)
$turvar_prodksi = $dataBps['turvar'][2]['val'];  // ID Produksi (1193)

$tahun_id = isset($dataBps['tahun'][0]['val']) ? $dataBps['tahun'][0]['val'] : '';
$turtahun_id = isset($dataBps['turtahun'][0]['val']) ? $dataBps['turtahun'][0]['val'] : '';

$datacontent = $dataBps['datacontent'];

// 4. Looping Ekstraksi per Provinsi
foreach ($dataBps['vervar'] as $prov) {
    $prov_id = $prov['val'];
    $nama_provinsi = $prov['label'];

    // Abaikan rekap total Nasional "INDONESIA"
    if (strtoupper(trim($nama_provinsi)) == "INDONESIA" || $prov_id == 0) continue;

    // BPS merakit kunci dengan rumus gabungan ID: var+turvar+prov+tahun+turtahun
    $key_luas    = $var_id . $turvar_luas . $prov_id . $tahun_id . $turtahun_id;
    $key_prodtiv = $var_id . $turvar_prodtiv . $prov_id . $tahun_id . $turtahun_id;
    $key_prodksi = $var_id . $turvar_prodksi . $prov_id . $tahun_id . $turtahun_id;

    $luas = 0;
    $prodtiv = 0;
    $prodksi = 0;

    // Ambil nilai utama
    if (isset($datacontent[$key_luas])) $luas = (float)$datacontent[$key_luas];
    if (isset($datacontent[$key_prodtiv])) $prodtiv = (float)$datacontent[$key_prodtiv];
    if (isset($datacontent[$key_prodksi])) $prodksi = (float)$datacontent[$key_prodksi];

    // JARING PENGAMAN: Jika BPS mengacak urutan kuncinya, kita cari manual di datacontent
    if ($luas == 0 && $prodtiv == 0) {
        foreach ($datacontent as $kunci_acak => $nilai) {
            if (strpos($kunci_acak, (string)$turvar_luas) !== false && strpos($kunci_acak, (string)$prov_id) !== false) $luas = (float)$nilai;
            if (strpos($kunci_acak, (string)$turvar_prodtiv) !== false && strpos($kunci_acak, (string)$prov_id) !== false) $prodtiv = (float)$nilai;
            if (strpos($kunci_acak, (string)$turvar_prodksi) !== false && strpos($kunci_acak, (string)$prov_id) !== false) $prodksi = (float)$nilai;
        }
    }

    $sql = "INSERT INTO data_panen (provinsi, luas_panen, produktivitas, produksi, tahun) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdddi", $nama_provinsi, $luas, $prodtiv, $prodksi, $tahun_data);

    if ($stmt->execute()) {
        $jumlah_sukses++;
    }
}

echo "<div style='font-family: sans-serif; text-align: center; margin-top: 50px;'>";
echo "<h1 style='color: #28a745;'>✅ Sinkronisasi Sukses!</h1>";
echo "<p>Berhasil menarik <b>$jumlah_sukses</b> data provinsi langsung dari server BPS.</p>";
echo "<br><a href='../../user/data_panen.php' style='padding: 12px 24px; background-color: #0b457f; color: white; text-decoration: none; border-radius: 6px; font-weight: bold;'>Kembali ke Halaman Peta & Tabel</a>";
echo "</div>";
