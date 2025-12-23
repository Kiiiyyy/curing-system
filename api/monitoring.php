<?php
require_once '../config/database.php';
header('Content-Type: application/json');

$view       = $_GET['view'] ?? 'overview'; // overview, shift-report, latest, plant-total
$machine_id = $_GET['machine_id'] ?? null;
$tanggal    = $_GET['tanggal'] ?? date('Y-m-d');
$shift      = $_GET['shift'] ?? 'SHIFT_1';

try {
    switch ($view) {
        // 1. DATA OVERVIEW (Untuk Widget Card di Atas)
        case 'overview':
            if (!$machine_id) throw new Exception("Machine ID dibutuhkan untuk overview");

            // Ambil Runtime & Downtime
            $stmt = $pdo->prepare("SELECT 
                SUM(CASE WHEN status_mesin = 'ON' THEN durasi ELSE 0 END) as runtime,
                SUM(CASE WHEN status_mesin = 'OFF' THEN durasi ELSE 0 END) as downtime
                FROM kondisi_mesin WHERE machine_id = ? AND tanggal = ? AND shift = ?");
            $stmt->execute([$machine_id, $tanggal, $shift]);
            $stats = $stmt->fetch();

            // Ambil Total Produksi
            $stmtProd = $pdo->prepare("SELECT SUM(jumlah_ban) as total FROM produksi_curing WHERE machine_id = ? AND tanggal = ? AND shift = ?");
            $stmtProd->execute([$machine_id, $tanggal, $shift]);
            $prod = $stmtProd->fetch();

            // Ambil Quality dari View
            $stmtQual = $pdo->prepare("SELECT quality_percentage FROM v_quality_per_shift WHERE machine_id = ? AND tanggal = ? AND shift = ?");
            $stmtQual->execute([$machine_id, $tanggal, $shift]);
            $qual = $stmtQual->fetch();

            $res = [
                'runtime' => (int)($stats['runtime'] ?? 0),
                'downtime' => (int)($stats['downtime'] ?? 0),
                'production' => (int)($prod['total'] ?? 0),
                'quality' => (int)($qual['quality_percentage'] ?? 100)
            ];
            break;

        // 2. SHIFT REPORT (Untuk Tabel Performa Semua Mesin)
        case 'shift-report':
            $sql = "SELECT k.machine_id, 
                    SUM(CASE WHEN k.status_mesin = 'ON' THEN k.durasi ELSE 0 END) as runtime,
                    SUM(CASE WHEN k.status_mesin = 'OFF' THEN k.durasi ELSE 0 END) as downtime,
                    (SELECT SUM(jumlah_ban) FROM produksi_curing p WHERE p.machine_id = k.machine_id AND p.tanggal = k.tanggal AND p.shift = k.shift) as production,
                    q.quality_percentage
                    FROM kondisi_mesin k
                    LEFT JOIN v_quality_per_shift q ON k.machine_id = q.machine_id AND k.tanggal = q.tanggal AND k.shift = q.shift
                    WHERE k.tanggal = ? AND k.shift = ?
                    GROUP BY k.machine_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$tanggal, $shift]);
            $res = $stmt->fetchAll();
            break;

        // 3. PLANT TOTAL (Sesuai requestmu: Total seluruh pabrik tanpa filter mesin)
        case 'plant-total':
            $sql = "SELECT 
                    SUM(jumlah_ban) as global_production,
                    COUNT(DISTINCT machine_id) as active_machines
                    FROM produksi_curing WHERE tanggal = ? AND shift = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$tanggal, $shift]);
            $res = $stmt->fetch();
            break;

        // 4. LATEST LOGS (Untuk Live Feed/Tabel History)
        case 'latest':
            $limit = $_GET['limit'] ?? 10;
            $res = $pdo->query("SELECT * FROM kondisi_mesin ORDER BY waktu DESC LIMIT " . (int)$limit)->fetchAll();
            break;

        default:
            throw new Exception("View type tidak dikenali");
    }

    echo json_encode(['status' => 'success', 'view' => $view, 'data' => $res]);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}