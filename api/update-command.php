<?php
header('Content-Type: application/json');
require_once '../config/database.php';

// Menangkap parameter perintah (ON/OFF)
$perintah = $_GET['perintah'] ?? null;

// Validasi input
if (!$perintah || !in_array(strtoupper($perintah), ['ON', 'OFF'])) {
    echo json_encode([
        "status" => "error", 
        "message" => "Parameter perintah wajib diisi (ON/OFF)"
    ]);
    exit;
}

try {
    $pdo->beginTransaction();

    // 1. Cek dulu apakah ID 1 sudah ada
    $check = $pdo->query("SELECT id FROM kondisi_mesin_write WHERE id = 1")->fetch();

    if ($check) {
        // 2. Jika ada, lakukan UPDATE
        $sql = "UPDATE kondisi_mesin_write SET perintah = ?, waktu = ? WHERE id = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([strtoupper($perintah), date('Y-m-d H:i:s')]);
        $action = "updated";
    } else {
        // 3. Jika belum ada (tabel kosong), lakukan INSERT dengan ID 1
        $sql = "INSERT INTO kondisi_mesin_write (id, machine_id, perintah, waktu) VALUES (1, 'MC-01', ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([strtoupper($perintah), date('Y-m-d H:i:s')]);
        $action = "created";
    }

    $pdo->commit();

    echo json_encode([
        "status" => "success",
        "message" => "Master Command ID 1 berhasil di-$action",
        "data" => [
            "id" => 1,
            "status_mesin" => strtoupper($perintah),
            "last_update" => date('Y-m-d H:i:s')
        ]
    ]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo json_encode([
        "status" => "error", 
        "message" => $e->getMessage()
    ]);
}