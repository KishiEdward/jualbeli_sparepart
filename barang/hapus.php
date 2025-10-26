<?php
session_start();
include_once '../koneksi/koneksi.php';

// Pastikan ada ID yang dikirim
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil dulu data produk (untuk hapus gambar kalau ada)
    $querySelect = "SELECT gambar FROM produk WHERE produk_id = '$id'";
    $resultSelect = mysqli_query($conn, $querySelect);

    if ($resultSelect && mysqli_num_rows($resultSelect) > 0) {
        $data = mysqli_fetch_assoc($resultSelect);

        // Hapus gambar fisik jika ada
        if (!empty($data['gambar'])) {
            $filePath = '../assets/img/' . $data['gambar'];
            if (file_exists($filePath)) {
                unlink($filePath); // hapus file gambar
            }
        }

        // Hapus data produk di database
        $queryDelete = "DELETE FROM produk WHERE produk_id = '$id'";
        $resultDelete = mysqli_query($conn, $queryDelete);

        if ($resultDelete) {
            $_SESSION['success_delete'] = true;
        } else {
            $_SESSION['error_delete'] = true;
        }
    } else {
        $_SESSION['error_delete'] = true;
    }
}

// Setelah semua proses selesai, balik ke index
header('Location: index.php');
exit;
?>
