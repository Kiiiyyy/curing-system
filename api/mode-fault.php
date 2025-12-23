<?php
require_once '../config/database.php';
header('Content-Type: application/json');

// Mengambil parameter filter
$machine_id = $_GET['machine_id'] ?? null;
$tanggal    = $_GET['tanggal'] ?? date('Y-m-d');
$shift      = $_GET['shift'] ?? null;

try {
    // Jika ada machine_id dan shift, ambil data spesifik
    if ($machine_id && $shift) {
        $sql = "SELECT * FROM v_quality_per_shift WHERE machine_id = ? AND tanggal = ? AND shift = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$machine_id, $tanggal, $shift]);
        $data = $stmt->fetch(); // Ambil satu baris saja
    } 
    // Jika hanya machine_id, ambil semua shift di tanggal tsb
    else if ($machine_id) {
        $sql = "SELECT * FROM v_quality_per_shift WHERE machine_id = ? AND tanggal = ? ORDER BY shift ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$machine_id, $tanggal]);
        $data = $stmt->fetchAll();
    }
    // Jika tidak ada parameter, tampilkan semua rangkuman kualitas hari ini
    else {
        $sql = "SELECT * FROM v_quality_per_shift WHERE tanggal = ? ORDER BY machine_id ASC, shift ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$tanggal]);
        $data = $stmt->fetchAll();
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Data Quality OEE berhasil diambil dari View',
        'data' => $data ? $data : [] // Kembalikan array kosong jika data tidak ditemukan
    ]);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}