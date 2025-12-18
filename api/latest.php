<?php
header('Content-Type: application/json');
require_once '../config.php';

$sql = "SELECT status_mesin, mode_mesin, waktu FROM kondisi_mesin ORDER BY waktu DESC LIMIT 1";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);

if ($data) {
    echo json_encode([
        "metrik" => "Status & Mode Terakhir",
        "status_mesin" => $data['status_mesin'],
        "mode_mesin" => $data['mode_mesin'],
        "last_update" => $data['waktu']
    ]);
} else {
    echo json_encode(["message" => "Belum ada data"]);
}
?>