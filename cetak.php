<?php
include 'koneksi.php';

// FIX: escape id agar tidak rentan SQL Injection
$id = mysqli_real_escape_string($koneksi, $_GET['id']);
$query = mysqli_query($koneksi, "SELECT tb_siswa.*, tb_jurusan.nama_jurusan
    FROM tb_siswa
    JOIN tb_jurusan ON tb_siswa.id_jurusan = tb_jurusan.id_jurusan
    WHERE tb_siswa.id='$id'");
$data = mysqli_fetch_assoc($query);
if (!$data) { die("Data tidak ditemukan."); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Pendaftaran - <?php echo htmlspecialchars($data['nama']); ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f9; padding: 20px; }
        .card { width: 450px; margin: auto; background: white; border: 2px solid #1e293b; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .card-header { text-align: center; border-bottom: 2px solid #1e293b; padding-bottom: 10px; margin-bottom: 15px; }
        .card-header h3 { margin: 0; text-transform: uppercase; }
        .card-body { display: flex; gap: 15px; }
        .pasfoto { width: 110px; height: 140px; border: 1px solid #ccc; object-fit: cover; }
        .info table { font-size: 13px; line-height: 1.6; }
        .btn-print { margin-top: 15px; width: 100%; padding: 10px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
        /* CSS KHUSUS MEDIA PRINT */
        @media print {
            .btn-print { display: none; }
            body { background: white; padding: 0; }
            .card { box-shadow: none; border: 2px solid #000; }
        }
    </style>
</head>
<body>
<div class="card">
    <div class="card-header">
        <h3>BUKTI PENDAFTARAN SISWA</h3>
        <small>SMK NEGERI KEBANGSAAN</small>
    </div>
    <div class="card-body">
        <img src="uploads/<?php echo htmlspecialchars($data['foto']); ?>" class="pasfoto">
        <div class="info">
            <table>
                <tr><td><strong>NIS</strong></td><td>: <?php echo htmlspecialchars($data['nis']); ?></td></tr>
                <tr><td><strong>Nama</strong></td><td>: <?php echo htmlspecialchars($data['nama']); ?></td></tr>
                <tr><td><strong>Jurusan</strong></td><td>: <?php echo htmlspecialchars($data['nama_jurusan']); ?></td></tr>
                <tr><td><strong>Status</strong></td><td>: TERDAFTAR</td></tr>
            </table>
        </div>
    </div>
    <button class="btn-print" onclick="window.print()">Cetak Kartu (Print)</button>
</div>
<script>
    window.onload = function() { window.print(); }
</script>
</body>
</html>
