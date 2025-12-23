<?php
require_once '../config/database.php';
header('Content-Type: application/json');

$limit = $_GET['limit'] ?? 10;

try {
    $stmt = $pdo->query("SELECT * FROM kondisi_mesin ORDER BY waktu DESC LIMIT " . (int)$limit);
    $data = $stmt->fetchAll();
    echo json_encode(['status' => 'success', 'data' => $data]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}