<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    // FIX: escape id agar tidak rentan SQL Injection
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    // 1. Ambil nama file foto dari database
    $q = mysqli_query($koneksi, "SELECT foto FROM tb_siswa WHERE id='$id'");
    $data = mysqli_fetch_assoc($q);

    if ($data) {
        $foto_lama = $data['foto'];

        // 2. Hapus file fisik foto jika ada
        if (file_exists("uploads/" . $foto_lama)) {
            unlink("uploads/" . $foto_lama);
        }

        // 3. Hapus data dari database db_siswa
        $sql = "DELETE FROM tb_siswa WHERE id='$id'";
        if (mysqli_query($koneksi, $sql)) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    } else {
        echo "Data tidak ditemukan.";
    }
}
?>
