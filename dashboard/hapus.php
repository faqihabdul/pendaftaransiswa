<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    // FIX: escape id agar tidak rentan SQL Injection
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    $q = mysqli_query($koneksi, "SELECT foto FROM tb_siswa WHERE id='$id'");
    $data = mysqli_fetch_assoc($q);

    // FIX: cek data ditemukan dulu sebelum akses index 'foto' (mencegah PHP warning kalau id tidak ada)
    if ($data) {
        $foto_lama = $data['foto'];

        if (file_exists("../uploads/" . $foto_lama)) {
            unlink("../uploads/" . $foto_lama);
        }

        $sql = "DELETE FROM tb_siswa WHERE id='$id'";
        if (mysqli_query($koneksi, $sql)) {
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    } else {
        echo "Data tidak ditemukan.";
    }
}
?>