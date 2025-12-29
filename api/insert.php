<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
require_once '../config/database.php';

$status = $_GET['status_mesin'] ?? null;
$mode   = $_GET['mode_mesin'] ?? null;
$waktu  = $_GET['waktu'] ?? date('Y-m-d H:i:s');

if ($status && $mode) {
    // Sanitasi input sederhana

    $pdo->beginTransaction();

    $sqlInsert = "INSERT INTO kondisi_mesin (machine_id, status_mesin, mode_mesin, tanggal, shift, waktu) VALUES (?, ?, ?, ?, ?, ?)";
    $stmtInsert = $pdo->prepare($sqlInsert);
    $stmtInsert->execute(['MC-01', $status, $mode, date('Y-m-d'), '', $waktu]);
    $pdo->commit();

    echo json_encode([
        "status" => "success",
        "data_tercatat" => ["status" => $status, "mode" => $mode, "waktu" => $waktu]
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Gunakan parameter: status_mesin, mode_mesin, & waktu"]);
}
