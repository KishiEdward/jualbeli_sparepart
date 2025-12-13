<?php
include_once '../koneksi/koneksi.php';
include_once '../template/auth_check.php';

if (isset($_POST['simpan'])) {
    $nama_model = htmlspecialchars($_POST['nama_model']);
    $merek      = $_POST['merek'];

    $query = "INSERT INTO model_motor (nama_model, merek) VALUES ('$nama_model', '$merek')";
    
    if (mysqli_query($conn, $query)) {
        $berhasil = true;
    } else {
        $berhasil = false;
        $error_msg = mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Model - GearZone</title>
    <link href="../css/styles.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"></script>
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
                    <h1 class="mt-4">Tambah Model Motor</h1>
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="" method="post">
                                <div class="mb-3">
                                    <label>Nama Model</label>
                                    <input type="text" name="nama_model" class="form-control" placeholder="Contoh: Supra X 125, NMax 155" required>
                                </div>
                                <div class="mb-3">
                                    <label>Merek</label>
                                    <input type="text" name="merek" class="form-control" placeholder="Contoh: Honda, Yamaha" required>
                                </div>
                                <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                                <a href="index.php" class="btn btn-secondary">Batal</a>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/scripts.js"></script>

    <?php if (isset($berhasil) && $berhasil === true) : ?>
        <script>
            Swal.fire({
                title: 'Berhasil!',
                text: 'Model motor berhasil ditambahkan.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'index.php';
            });
        </script>
    <?php elseif (isset($berhasil) && $berhasil === false) : ?>
        <script>
            Swal.fire({
                title: 'Gagal!',
                text: 'Error: <?= $error_msg; ?>',
                icon: 'error'
            });
        </script>
    <?php endif; ?>
</body>
</html>