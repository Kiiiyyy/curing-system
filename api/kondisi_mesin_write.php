<?php
header('Content-Type: application/json');
require_once '../config/database.php';

try {
    $pdo->beginTransaction();

    // 1. Ambil data terakhir untuk mesin ini
    $stmtLast = $pdo->prepare("SELECT perintah FROM kondisi_mesin_write WHERE machine_id='MC-01' ORDER BY id ASC LIMIT 1");
    $stmtLast->execute();
    $lastRecord = $stmtLast->fetch();
    echo json_encode([
        "metrik" => "Status & Mode Terakhir",
        "status_mesin" => $lastRecord["perintah"]
    ]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
}
