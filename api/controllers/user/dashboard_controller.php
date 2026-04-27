<?php
require __DIR__ . '/../../middleware/auth.php';
require __DIR__ . '/../../config/koneksi.php';

// 🔐 cukup 1 baris
$user = requireAuth('user');

$page = 'dashboard';
$id_user = $user['id_user'];

$query_user = mysqli_query($conn, "SELECT * FROM users WHERE id_user='$id_user'");
$u = mysqli_fetch_assoc($query_user);

// === BPS DATA ===
$data_petani_2023 = [];
$url_bps = "https://sensus.bps.go.id/topik/tabular/st2023/242/98808/3";

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
        $temp = [];

        foreach ($response['data'] as $row) {
            $prov = $row['nama_wilayah'];
            $indikator = strtolower($row['nama_indikator']);
            $nilai = (int)$row['nilai'];

            if (!isset($temp[$prov])) {
                $temp[$prov] = [
                    'provinsi' => $prov,
                    'rt_petani' => 0,
                    'jml_petani' => 0
                ];
            }

            if (strpos($indikator, 'rumah tangga') !== false) {
                $temp[$prov]['rt_petani'] = $nilai;
            } else {
                $temp[$prov]['jml_petani'] = $nilai;
            }
        }

        $data_petani_2023 = array_values($temp);
    }
}
