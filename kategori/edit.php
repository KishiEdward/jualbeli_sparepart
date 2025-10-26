<?php
include_once '../koneksi/koneksi.php';
include_once '../template/auth_check.php';

// Pastikan ada parameter id
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM kategori WHERE kategori_id = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Data tidak ditemukan!',
        }).then(() => {
            window.location = 'index.php';
        });
    </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Kategori - GearZone</title>
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
                <h1 class="mt-4">Edit Kategori</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="index.php">Kategori</a></li>
                    <li class="breadcrumb-item active">Edit Kategori</li>
                </ol>

                <div class="card mb-4">
                    <div class="card-body">
                        <form action="" method="post">
                            <input type="hidden" name="kategori_id" value="<?= htmlspecialchars($data['kategori_id']); ?>">

                            <div class="mb-3">
                                <label class="form-label">Nama Kategori</label>
                                <input type="text" name="nama_kategori" class="form-control"
                                       value="<?= htmlspecialchars($data['nama_kategori']); ?>" required>
                            </div>

                            <button type="submit" name="update" class="btn btn-primary">Update</button>
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
// Proses update kategori
if (isset($_POST['update'])) {
    $id = $_POST['kategori_id'];
    $nama_kategori = $_POST['nama_kategori'];

    $query = "UPDATE kategori SET nama_kategori = '$nama_kategori' WHERE kategori_id = '$id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "
        <script>
            Swal.fire({
                title: 'Berhasil!',
                text: 'Data kategori berhasil diperbarui!',
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
                text: 'Terjadi kesalahan saat mengupdate data.',
                icon: 'error',
                confirmButtonText: 'Coba Lagi'
            });
        </script>
        ";
    }
}
?>
