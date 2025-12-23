<?php
require_once '../config/database.php';
header('Content-Type: application/json');

// Menangkap parameter
$tanggal = $_GET['tanggal'] ?? date('Y-m-d');
$shift   = $_GET['shift'] ?? null;

// Validasi: Shift wajib ada karena perhitungan dilakukan per shift
if (!$shift) {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Parameter Shift (SHIFT_1/SHIFT_2/SHIFT_3) wajib diisi untuk melihat total produksi'
    ]);
    exit;
}

try {
    // SQL: Menghitung total jumlah_ban dari SEMUA mesin pada tanggal dan shift tertentu
    $sql = "SELECT 
                SUM(jumlah_ban) as total_produksi_global,
                COUNT(DISTINCT machine_id) as total_mesin_berkontribusi
            FROM produksi_curing 
            WHERE tanggal = ? AND shift = ?";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$tanggal, $shift]);
    $result = $stmt->fetch();

    $total = (int)($result['total_produksi_global'] ?? 0);
    $mesin_count = (int)($result['total_mesin_berkontribusi'] ?? 0);

    echo json_encode([
        'status' => 'success',
        'message' => 'Total produksi global berhasil dikalkulasi',
        'context' => [
            'tanggal' => $tanggal,
            'shift' => $shift
        ],
        'data' => [
            'total_produksi' => $total,
            'jumlah_mesin_aktif' => $mesin_count,
            'unit' => 'pcs'
        ]
    ]);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}