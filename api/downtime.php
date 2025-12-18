<?php
header('Content-Type: application/json');
require_once '../config.php';

$sql = "SELECT SUM(durasi_detik) as total_detik FROM (
            SELECT 
                status_mesin,
                TIMESTAMPDIFF(SECOND, waktu, 
                    LEAD(waktu) OVER (ORDER BY waktu ASC)
                ) as durasi_detik
            FROM kondisi_mesin
            WHERE waktu >= NOW() - INTERVAL 8 HOUR
        ) AS tabel_durasi 
        WHERE status_mesin = 'OFF'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$total_detik = $row['total_detik'] ?? 0;

$jam = floor($total_detik / 3600);
$menit = floor(($total_detik / 60) % 60);
$detik = $total_detik % 60;

$format_waktu = sprintf('%02d:%02d:%02d', $jam, $menit, $detik);

echo json_encode([
    "metrik" => "Total Downtime (8 Jam Terakhir)",
    "durasi_detik" => (int)$total_detik,
    "format_hms" => $format_waktu
]);