<?php
include_once '../koneksi/koneksi.php';
include_once '../template/auth_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Kategori - GearZone</title>
    <link href="../css/styles.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="sb-nav-fixed">
<?php include '../template/topbar.php'; ?>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include '../template/sidebar.php'; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Tambah Kategori</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="index.php">Kategori</a></li>
                    <li class="breadcrumb-item active">Tambah Kategori</li>
                </ol>

                <div class="card mb-4">
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="mb-3">
                                <label class="form-label">Nama Kategori</label>
                                <input type="text" name="nama_kategori" class="form-control" required>
                            </div>

                            <button type="submit" name="submit" class="btn btn-success">Simpan</button>
                            <a href="index.php" class="btn btn-secondary">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>

<?php 
// Proses form tambah kategori
if (isset($_POST['submit'])) {
    include_once '../koneksi/koneksi.php';

    $nama_kategori = trim($_POST['nama_kategori']);

    if ($nama_kategori == "") {
        echo "
        <script>
            Swal.fire({
                title: 'Peringatan!',
                text: 'Nama kategori tidak boleh kosong!',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        </script>
        ";
        exit;
    }

    $query = "INSERT INTO kategori (nama_kategori) VALUES ('$nama_kategori')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "
        <script>
            Swal.fire({
                title: 'Berhasil!',
                text: 'Kategori berhasil ditambahkan!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'index.php';
            });
        </script>
        ";
    } else {
        echo "
        <script>
            Swal.fire({
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat menyimpan data.',
                icon: 'error',
                confirmButtonText: 'Coba Lagi'
            });
        </script>
        ";
    }
}
?>
