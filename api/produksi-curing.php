<?php
require_once '../config/database.php';
header('Content-Type: application/json');

// Menangkap parameter
$machine_id = $_GET['machine_id'] ?? null;
$shift      = $_GET['shift'] ?? null;
$jumlah     = $_GET['jumlah_ban'] ?? null; // Nullable: trigger deteksi mode
$tanggal    = $_GET['tanggal'] ?? date('Y-m-d');

// Validasi minimal: machine_id dan shift wajib ada untuk kedua mode
if (!$machine_id || !$shift) {
    echo json_encode(['status' => 'error', 'message' => 'Machine ID dan Shift wajib diisi']);
    exit;
}

try {
    // --- MODE 1: WRITE (Jika jumlah_ban dikirim) ---
    if ($jumlah !== null && $jumlah !== '') {
        $stmt = $pdo->prepare("INSERT INTO produksi_curing (machine_id, tanggal, shift, jumlah_ban) VALUES (?, ?, ?, ?)");
        $stmt->execute([$machine_id, $tanggal, $shift, (int)$jumlah]);

        echo json_encode([
            'status' => 'success',
            'message' => 'Data produksi berhasil dicatat',
            'details' => [
                'machine' => $machine_id,
                'added_count' => $jumlah
            ]
        ]);
    } 
    // --- MODE 2: READ (Jika jumlah_ban TIDAK dikirim) ---
    else {
        // 1. Ambil List History
        $sql = "SELECT id, machine_id, jumlah_ban, waktu FROM produksi_curing 
                WHERE machine_id = ? AND tanggal = ? AND shift = ? ORDER BY waktu DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$machine_id, $tanggal, $shift]);
        $rows = $stmt->fetchAll();

        // 2. Hitung Total Akumulasi (untuk memudahkan Dashboard)
        $total_shift = 0;
        foreach ($rows as $row) {
            $total_shift += (int)$row['jumlah_ban'];
        }

        echo json_encode([
            'status' => 'success',
            'total_akumulasi' => $total_shift,
            'data_list' => $rows
        ]);
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}