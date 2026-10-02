<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pendaftaran Siswa Baru - db_siswa</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f4f4f9; }
        .container { max-width: 950px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 12px; }
        label { display: block; font-weight: bold; margin-bottom: 4px; }
        input[type="text"], textarea, select { width: 100%; padding: 8px; box-sizing: border-box; }
        .btn { padding: 8px 12px; border: none; border-radius: 4px; color: white; cursor: pointer; text-decoration: none; font-size: 13px; display: inline-block; }
        .btn-green { background-color: #28a745; }
        .btn-blue { background-color: #007bff; }
        .btn-danger { background-color: #dc3545; }
        .btn-purple { background-color: #6f42c1; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #1e293b; color: white; }
        .img-thumb { width: 50px; height: 60px; object-fit: cover; border-radius: 4px; }
        .filter-box { background: #e2e8f0; padding: 12px; border-radius: 6px; margin-bottom: 15px; display: flex; gap: 10px; }
    </style>
</head>
<body>
<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
    <h2>Form Pendaftaran Siswa Baru</h2>
    <div style="display: flex; gap: 10px;">
        <a href="dashboard/dashboard.php" class="btn btn-blue">Kembali ke Dashboard</a>
        <a href="jurusan.php" class="btn btn-purple">Kelola Master Jurusan »</a>
    </div>
</div>

    <!-- FORM PENDAFTARAN DENGAN UPLOAD FILE -->
    <form action="simpan.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>NIS:</label>
            <input type="text" name="nis" required>
        </div>
        <div class="form-group">
            <label>Nama Lengkap:</label>
            <input type="text" name="nama" required>
        </div>
        <div class="form-group">
            <label>Pilih Jurusan (Relasi Data):</label>
            <select name="id_jurusan" required>
                <option value="">-- Pilih Jurusan --</option>
                <?php
                $jur = mysqli_query($koneksi, "SELECT * FROM tb_jurusan");
                while ($j = mysqli_fetch_assoc($jur)) {
                    echo "<option value='{$j['id_jurusan']}'>" . htmlspecialchars($j['nama_jurusan']) . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>Upload Pasfoto (JPG/PNG, Max 2MB):</label>
            <input type="file" name="foto" accept="image/*" required>
        </div>
        <div class="form-group">
            <label>Alasan Masuk:</label>
            <textarea name="alasan" rows="2" required></textarea>
        </div>
        <button type="submit" class="btn btn-green">Daftar Sekarang</button>
    </form>

    <hr style="margin: 25px 0;">
    <h3>Daftar Siswa Terdaftar</h3>

    <!-- FORM PENCARIAN & FILTER -->
    <form method="GET" class="filter-box">
        <input type="text" name="keyword" placeholder="Cari NIS / Nama..."
            value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>" style="flex: 2;">
        <select name="filter_jurusan" style="flex: 1;">
            <option value="">Semua Jurusan</option>
            <?php
            $jur2 = mysqli_query($koneksi, "SELECT * FROM tb_jurusan");
            while ($j2 = mysqli_fetch_assoc($jur2)) {
                $selected = (isset($_GET['filter_jurusan']) && $_GET['filter_jurusan'] == $j2['id_jurusan']) ? 'selected' : '';
                echo "<option value='{$j2['id_jurusan']}' $selected>" . htmlspecialchars($j2['nama_jurusan']) . "</option>";
            }
            ?>
        </select>
        <button type="submit" class="btn btn-blue">Cari & Filter</button>
        <a href="index.php" class="btn btn-danger">Reset</a>
    </form>

    <table>
        <thead>
            <tr>
                <th>Pasfoto</th>
                <th>NIS</th>
                <th>Nama</th>
                <th>Jurusan</th>
                <th>Alasan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // QUERY DENGAN JOIN & FILTER LIKE
            $sql = "SELECT tb_siswa.*, tb_jurusan.nama_jurusan
                    FROM tb_siswa
                    JOIN tb_jurusan ON tb_siswa.id_jurusan = tb_jurusan.id_jurusan
                    WHERE 1=1";

            if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
                $kw = mysqli_real_escape_string($koneksi, $_GET['keyword']);
                $sql .= " AND (tb_siswa.nama LIKE '%$kw%' OR tb_siswa.nis LIKE '%$kw%')";
            }
            if (isset($_GET['filter_jurusan']) && $_GET['filter_jurusan'] != '') {
                $fj = mysqli_real_escape_string($koneksi, $_GET['filter_jurusan']);
                $sql .= " AND tb_siswa.id_jurusan = '$fj'";
            }
            $sql .= " ORDER BY tb_siswa.id DESC";

            $query = mysqli_query($koneksi, $sql);
            if (mysqli_num_rows($query) > 0) {
                while ($row = mysqli_fetch_assoc($query)) {
                    echo "<tr>
                        <td><img src='uploads/{$row['foto']}' class='img-thumb'></td>
                        <td>" . htmlspecialchars($row['nis']) . "</td>
                        <td>" . htmlspecialchars($row['nama']) . "</td>
                        <td>" . htmlspecialchars($row['nama_jurusan']) . "</td>
                        <td>" . htmlspecialchars($row['alasan']) . "</td>
                        <td>
                            <a href='cetak.php?id={$row['id']}' target='_blank' class='btn btn-blue'>Cetak Kartu</a>
                            <a href='hapus.php?id={$row['id']}' class='btn btn-danger'
                                onclick='return confirm(\"Hapus data ini?\")'>Hapus</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='6' style='text-align:center;'>Data tidak ditemukan.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>
