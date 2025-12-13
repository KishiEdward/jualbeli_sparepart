<?php
include_once '../koneksi/koneksi.php';
include_once '../template/auth_check.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query Hapus
    $query = "DELETE FROM model_motor WHERE model_id = '$id'";

    if (mysqli_query($conn, $query)) {
        // Jika sukses
        $sukses = true;
    } else {
        // Jika gagal (biasanya karena constraint Foreign Key / dipakai di tabel produk_kompatibel)
        $sukses = false;
        $error_msg = mysqli_error($conn);
    }
} else {
    // Jika akses langsung tanpa ID
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menghapus...</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php if (isset($sukses) && $sukses === true) : ?>
        <script>
            Swal.fire({
                title: 'Terhapus!',
                text: 'Model motor berhasil dihapus.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                window.location.href = 'index.php';
            });
        </script>
    <?php elseif (isset($sukses) && $sukses === false) : ?>
        <script>
            Swal.fire({
                title: 'Gagal Menghapus!',
                text: 'Data ini mungkin sedang digunakan pada Produk Kompatibel. Hapus relasinya terlebih dahulu.\nError: <?= $error_msg; ?>',
                icon: 'error',
                confirmButtonText: 'Kembali'
            }).then(() => {
                window.location.href = 'index.php';
            });
        </script>
    <?php endif; ?>
</body>
</html>