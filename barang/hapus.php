<?php
include_once '../koneksi/koneksi.php';
include_once '../template/auth_check.php';

// Pastikan ID ada
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 1. Hapus Gambar Fisik
    $q_gambar = mysqli_query($conn, "SELECT gambar FROM produk WHERE produk_id='$id'");
    $dt_gambar = mysqli_fetch_assoc($q_gambar);
    
    if ($dt_gambar['gambar'] != "" && file_exists("../assets/img/" . $dt_gambar['gambar'])) {
        unlink("../assets/img/" . $dt_gambar['gambar']);
    }

    // 2. Bersihkan Tabel Relasi (Manual)
    mysqli_query($conn, "DELETE FROM produk_kompatibel WHERE produk_id = '$id'");

    // 3. Hapus Produk Utama
    $hapus = mysqli_query($conn, "DELETE FROM produk WHERE produk_id = '$id'");

    // Cetak Struktur HTML + SweetAlert
    echo "<!DOCTYPE html>";
    echo "<head>";
    echo "<title>Menghapus...</title>";
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "</head>";
    echo "<body>";

    if ($hapus) {
        echo "<script>
            Swal.fire({
                title: 'Berhasil!',
                text: 'Produk berhasil dihapus.',
                icon: 'success',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.href = 'index.php';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'Gagal!',
                text: 'Gagal menghapus data: " . mysqli_error($conn) . "',
                icon: 'error'
            }).then(() => {
                window.location.href = 'index.php';
            });
        </script>";
    }
    echo "</body></html>";

} else {
    header("Location: index.php");
}
?>