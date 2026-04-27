<?php
require_once __DIR__ . '/config/koneksi.php';

$query = mysqli_query($conn, "SELECT * FROM data_panen ORDER BY id ASC");

if (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {
        echo '<tr style="border-bottom:1px solid #f1f5f9; transition: background 0.2s;">
            <td style="padding:16px 20px; color:#334155; font-weight:500;">' . htmlspecialchars($row['provinsi']) . '</td>
            <td style="padding:16px 20px; color:#475569;">' . number_format($row['luas_panen'], 0, ',', '.') . '</td>
            <td style="padding:16px 20px; color:#475569;"><strong>' . number_format($row['produksi'], 0, ',', '.') . '</strong></td>
        </tr>';
    }
} else {
    echo '<tr>
        <td colspan="3" style="text-align:center; padding:30px; color:#94a3b8;">
            Belum ada data panen.
        </td>
    </tr>';
}
