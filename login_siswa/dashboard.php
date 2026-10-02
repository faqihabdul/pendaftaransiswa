<?php
session_start();

// Kalau belum login, tendang balik ke halaman login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; height: 100vh; display: flex;
        align-items: center; justify-content: center; background-color: #f4f4f9; }
        .container { max-width: 400px; width: 100%; background: white; padding: 25px;
        border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); text-align: center; }
        .btn { padding: 10px 15px; border: none; border-radius: 4px; color: white;
        cursor: pointer; text-decoration: none; display: inline-block; background-color: #dc3545; }
    </style>
</head>
<body>
<div class="container">
    <h2>Selamat Datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <p>Kamu berhasil login.</p>
    <a href="logout.php" class="btn">Logout</a>
</div>
</body>
</html>
