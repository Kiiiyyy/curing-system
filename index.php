<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Monitoring API</title>
</head>
<body>
    <h1>Industrial Machine Monitoring API</h1>
    <p>Status: <strong>Online</strong></p>
    <hr>
    <h3>Testing Endpoints:</h3>
    <ul>
        <li><a href="api/insert.php?status_mesin=ON&mode_mesin=AUTO">Test Insert Data (ON/AUTO)</a></li>
        <li><a href="api/insert.php?status_mesin=OFF&mode_mesin=AUTO">Test Insert Data (OFF/AUTO)</a></li>
        <li><a href="api/latest.php">Cek Status & Mode Terakhir</a></li>
        <li><a href="api/operasi.php">Cek Runtime (8 Jam)</a></li>
        <li><a href="api/downtime.php">Cek Downtime (8 Jam)</a></li>
    </ul>
</body>
</html>