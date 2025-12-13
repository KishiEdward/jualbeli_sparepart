<?php
include_once '../koneksi/koneksi.php';
include_once '../template/auth_check.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query Hapus Promo
    // Pastikan nama kolom primary key di tabel promo adalah 'promo_id'
    $query = "DELETE FROM promo WHERE promo_id = '$id'";

    if (mysqli_query($conn, $query)) {
        // Jika sukses
        $sukses = true;
    } else {
        // Jika gagal (Misalnya karena promo sudah pernah dipakai di transaksi)
        $sukses = false;
        $error_msg = mysqli_error($conn);
    }
} else {
    // Jika akses langsung tanpa ID, kembalikan ke index
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menghapus Data Promo...</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    
    <?php if (isset($sukses) && $sukses === true) : ?>
        <script>
            Swal.fire({
                title: 'Terhapus!',
                text: 'Data Promo berhasil dihapus.',
                icon: 'success',
                timer: 1500, // Otomatis hilang dalam 1.5 detik
                showConfirmButton: false
            }).then(() => {
                // Redirect setelah alert selesai
                window.location.href = 'index.php';
            });
        </script>

    <?php elseif (isset($sukses) && $sukses === false) : ?>
        <script>
            Swal.fire({
                title: 'Gagal Menghapus!',
                text: 'Data ini tidak bisa dihapus (Mungkin sudah tercatat dalam riwayat transaksi).\nError: <?= $error_msg; ?>',
                icon: 'error',
                confirmButtonText: 'Kembali'
            }).then(() => {
                // Redirect setelah user klik tombol kembali
                window.location.href = 'index.php';
            });
        </script>
    <?php endif; ?>

</body>
</html>