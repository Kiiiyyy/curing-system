<?php
require_once '../config/database.php';
header('Content-Type: application/json');

$machine_id = $_GET['machine_id'] ?? null;
$tanggal    = $_GET['tanggal'] ?? date('Y-m-d');
$shift      = $_GET['shift'] ?? 'SHIFT_1';

if (!$machine_id) {
    echo json_encode(['status' => 'error', 'message' => 'Machine ID wajib diisi']);
    exit;
}

try {
    $sql = "SELECT machine_id, tanggal, shift, SUM(durasi) as total_runtime_seconds 
            FROM kondisi_mesin 
            WHERE machine_id = ? AND status_mesin = 'ON' AND tanggal = ? AND shift = ?
            GROUP BY machine_id, tanggal, shift";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$machine_id, $tanggal, $shift]);
    $result = $stmt->fetch();

    echo json_encode([
        'status' => 'success',
        'data' => [
            'machine_id' => $machine_id,
            'tanggal' => $tanggal,
            'shift' => $shift,
            'total_runtime' => $result ? (int)$result['total_runtime_seconds'] : 0,
            'unit' => 'seconds'
        ]
    ]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}