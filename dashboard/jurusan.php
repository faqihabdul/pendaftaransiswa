<?php
include 'koneksi.php';

if (isset($_POST['tambah'])) {
    $nama_jurusan = mysqli_real_escape_string($koneksi, $_POST['nama_jurusan']);
    mysqli_query($koneksi, "INSERT INTO tb_jurusan (nama_jurusan) VALUES ('$nama_jurusan')");
    header("Location: jurusan.php");
    exit(); // FIX: hentikan eksekusi script setelah redirect
}

if (isset($_GET['hapus'])) {
    // FIX: escape id agar tidak rentan SQL Injection
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM tb_jurusan WHERE id_jurusan='$id'");
    header("Location: jurusan.php");
    exit(); // FIX: hentikan eksekusi script setelah redirect
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Master Data Jurusan - db_siswa</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f4f4f9; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #007bff; color: white; }
        .btn { padding: 6px 12px; border: none; border-radius: 4px; color: white; cursor: pointer; text-decoration: none; font-size: 12px; }
        .btn-green { background: #28a745; } .btn-danger { background: #dc3545; } .btn-nav { background: #6c757d; }
    </style>
</head>
<body>
<div class="container">
    <a href="../index.php" class="btn btn-nav">« Kembali ke Form Pendaftaran</a>
    <a href="dashboard.php" class="btn btn-green">« Kembali ke Dashboard</a>
    <h2>Kelola Master Jurusan (db_siswa)</h2>
    <form method="POST">
        <input type="text" name="nama_jurusan" placeholder="Nama Jurusan Baru" required style="width: 70%; padding: 8px;">
        <button type="submit" name="tambah" class="btn btn-green">Tambah Jurusan</button>
    </form>
    <table>
        <tr><th>ID</th><th>Nama Jurusan</th><th>Aksi</th></tr>
        <?php
        $q = mysqli_query($koneksi, "SELECT * FROM tb_jurusan");
        while ($r = mysqli_fetch_assoc($q)) {
            echo "<tr>
                <td>{$r['id_jurusan']}</td>
                <td>" . htmlspecialchars($r['nama_jurusan']) . "</td>
                <td><a href='jurusan.php?hapus={$r['id_jurusan']}' class='btn btn-danger'
                    onclick='return confirm(\"Hapus jurusan ini?\")'>Hapus</a></td>
            </tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
