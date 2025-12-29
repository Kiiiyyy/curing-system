<?php
require_once '../config/database.php';
header('Content-Type: application/json');

$machine_id  = $_GET['machine_id'] ?? null;
$status_baru = $_GET['perintah'] ?? null;    // ON / OFF
$mode_baru   = $_GET['mode_target'] ?? null; // AUTO / MANUAL / FAULT
$shift       = $_GET['shift'] ?? 'SHIFT_1';

if (!$machine_id || !$status_baru || !$mode_baru) {
    echo json_encode(['status' => 'error', 'message' => 'Parameter tidak lengkap']);
    exit;
}

try {
    $pdo->beginTransaction();

    // 1. Ambil data terakhir untuk mesin ini
    $stmtLast = $pdo->prepare("SELECT id, status_mesin, waktu FROM kondisi_mesin WHERE machine_id = ? ORDER BY id DESC LIMIT 1");
    $stmtLast->execute([$machine_id]);
    $lastRecord = $stmtLast->fetch();

    $durasi_kalkulasi = 0;

    if ($lastRecord) {
        // 2. CEK: Apakah Status berubah? (ON ke OFF atau OFF ke ON)
        if ($lastRecord['status_mesin'] !== $status_baru) {
            // Jika status berubah, hitung selisih waktu
            $waktu_awal = strtotime($lastRecord['waktu']);
            $waktu_sekarang = time();
            $durasi_kalkulasi = $waktu_sekarang - $waktu_awal;

            // Update durasi di baris terakhir tersebut
            $stmtUpdate = $pdo->prepare("UPDATE kondisi_mesin SET durasi = ? WHERE id = ?");
            $stmtUpdate->execute([$durasi_kalkulasi, $lastRecord['id']]);
        }
    }

    // 3. Selalu Insert baris baru untuk mencatat state saat ini
    $sqlInsert = "INSERT INTO kondisi_mesin (machine_id, status_mesin, mode_mesin, tanggal, shift, durasi) 
                  VALUES (?, ?, ?, ?, ?, 0)";
    $stmtInsert = $pdo->prepare($sqlInsert);
    $stmtInsert->execute([$machine_id, $status_baru, $mode_baru, date('Y-m-d'), $shift]);

    // 4. Catat history di tabel write
    $sqlWrite = "INSERT INTO kondisi_mesin_write (machine_id, perintah, mode_target) VALUES (?, ?, ?)";
    $stmtWrite = $pdo->prepare($sqlWrite);
    $stmtWrite->execute([$machine_id, $status_baru, $mode_baru]);

    $pdo->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Log diperbarui',
        'update_duration' => ($durasi_kalkulasi > 0 ? "Status berubah, durasi diupdate: $durasi_kalkulasi detik" : "Status tetap, durasi tidak diupdate")
    ]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
