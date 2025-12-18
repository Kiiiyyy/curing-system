<?php
header('Content-Type: application/json');
require_once '../config.php';

$sql = "SELECT COUNT(*) AS runtime FROM kondisi_mesin 
        WHERE status_mesin = 'ON' 
        AND waktu >= NOW() - INTERVAL 8 HOUR";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

echo json_encode([
    "metrik" => "Total Runtime (8 Jam Terakhir)",
    "total_point_logs" => $row['runtime'],
    "unit" => "Log Points",
    "keterangan" => "Setiap 1 log mewakili interval pengiriman data mesin"
]);
?>