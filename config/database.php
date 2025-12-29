<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

// config/database.php
date_default_timezone_set('Asia/Jakarta');

$host = "bagipanen.my.id";
$db   = "kmiprodm_aioa_curing";
$user = "kmiprodm_aioa";
$pass = "aioa-curing";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed']));
}