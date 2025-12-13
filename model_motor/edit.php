<?php
include_once '../koneksi/koneksi.php';
include_once '../template/auth_check.php';

// Cek apakah ada ID di URL
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$query_data = mysqli_query($conn, "SELECT * FROM model_motor WHERE model_id = '$id'");
$data = mysqli_fetch_assoc($query_data);

// Jika data tidak ditemukan
if (!$data) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['update'])) {
    $nama_model = htmlspecialchars($_POST['nama_model']);
    $merek      = $_POST['merek'];

    $query = "UPDATE model_motor SET nama_model = '$nama_model', merek = '$merek' WHERE model_id = '$id'";
    
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
    <title>Edit Model - GearZone</title>
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
                    <h1 class="mt-4">Edit Model Motor</h1>
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="" method="post">
                                <div class="mb-3">
                                    <label>Nama Model</label>
                                    <input type="text" name="nama_model" class="form-control" value="<?= $data['nama_model']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label>Merek</label>
                                    <select name="merek" class="form-select" required>
                                        <option value="Honda" <?= ($data['merek'] == 'Honda') ? 'selected' : ''; ?>>Honda</option>
                                        <option value="Yamaha" <?= ($data['merek'] == 'Yamaha') ? 'selected' : ''; ?>>Yamaha</option>
                                        <option value="Suzuki" <?= ($data['merek'] == 'Suzuki') ? 'selected' : ''; ?>>Suzuki</option>
                                        <option value="Kawasaki" <?= ($data['merek'] == 'Kawasaki') ? 'selected' : ''; ?>>Kawasaki</option>
                                        <option value="Vespa" <?= ($data['merek'] == 'Vespa') ? 'selected' : ''; ?>>Vespa</option>
                                        <option value="Lainnya" <?= ($data['merek'] == 'Lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                                    </select>
                                </div>
                                <button type="submit" name="update" class="btn btn-primary">Update Data</button>
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
                text: 'Data berhasil diperbarui.',
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