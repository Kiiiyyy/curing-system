<?php
$host = "localhost";
$user = "root";
$pass = "password_baru";
$db   = "db_monitoring";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    header('Content-Type: application/json');
    die(json_encode(["error" => "Koneksi database gagal"]));
}
?>