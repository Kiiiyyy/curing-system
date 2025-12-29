<?php
header('Content-Type: application/json');
require_once '../config/database.php';

$tanggal = $_GET['tanggal'] ?? date('Y-m-d');

try {
    /** * Logika Shift:
     * Shift 1: 07:00 - 15:00
     * Shift 2: 15:00 - 23:00
     * Shift 3: 23:00 - 07:00 (Besok pagi)
     */
    
    $query = "SELECT 
        SUM(CASE WHEN TIME(waktu) >= '07:00:00' AND TIME(waktu) < '15:00:00' AND DATE(waktu) = :tgl THEN 1 ELSE 0 END) as shift_1,
        SUM(CASE WHEN TIME(waktu) >= '15:00:00' AND TIME(waktu) < '23:00:00' AND DATE(waktu) = :tgl THEN 1 ELSE 0 END) as shift_2,
        SUM(CASE WHEN 
            (TIME(waktu) >= '23:00:00' AND DATE(waktu) = :tgl) OR 
            (TIME(waktu) < '07:00:00' AND DATE(waktu) = DATE_ADD(:tgl, INTERVAL 1 DAY)) 
            THEN 1 ELSE 0 END) as shift_3
        FROM mode_service";

    $stmt = $pdo->prepare($query);
    $stmt->execute(['tgl' => $tanggal]);
    $result = $stmt->fetch();

    echo json_encode([
        "status" => "success",
        "tanggal_monitoring" => $tanggal,
        "data" => [
            "shift_1" => (int)($result['shift_1'] ?? 0),
            "shift_2" => (int)($result['shift_2'] ?? 0),
            "shift_3" => (int)($result['shift_3'] ?? 0),
            "total_harian" => (int)($result['shift_1'] + $result['shift_2'] + $result['shift_3'])
        ]
    ]);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}