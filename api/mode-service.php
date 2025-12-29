<?php

header('Content-Type: application/json');
require_once '../config/database.php';

try {
    $pdo->beginTransaction();

    $sqlInsert = "INSERT INTO mode_service (waktu) VALUES (?)";
    $stmtInsert = $pdo->prepare($sqlInsert);
    $stmtInsert->execute([date('Y-m-d H:i:s')]);
    $pdo->commit();

    echo json_encode([
        "status" => "success",
        "data_tercatat" => ["waktu" => date('Y-m-d H:i:s')]
    ]);
} catch (mysqli_sql_exception $th) {
    echo json_encode(["status" => "error", "message" => $th->getMessage()]);
}
