<?php
header('Content-Type: application/json');
require_once '../config.php';

$sql = "SELECT COUNT(*) AS downtime FROM kondisi_mesin 
        WHERE status_mesin = 'OFF' 
        AND waktu >= NOW() - INTERVAL 8 HOUR";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

echo json_encode([
    "metrik" => "Total Downtime (8 Jam Terakhir)",
    "total_point_logs" => $row['downtime'],
    "unit" => "Log Points"
]);
?>