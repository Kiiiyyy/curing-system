<?php
header('Content-Type: application/json');
require_once '../config.php';

$status = $_GET['status_mesin'] ?? null;
$mode   = $_GET['mode_mesin'] ?? null;
$waktu  = $_GET['waktu'] ?? date('Y-m-d H:i:s');

if ($status && $mode) {
    // Sanitasi input sederhana
    $status = mysqli_real_escape_string($conn, $status);
    $mode   = mysqli_real_escape_string($conn, $mode);
    
    $sql = "INSERT INTO kondisi_mesin (status_mesin, mode_mesin, waktu) VALUES ('$status', '$mode', '$waktu')";
    
    if (mysqli_query($conn, $sql)) {
        echo json_encode([
            "status" => "success",
            "data_tercatat" => ["status" => $status, "mode" => $mode, "waktu" => $waktu]
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Gunakan parameter: status_mesin, mode_mesin, & waktu"]);
}
?>