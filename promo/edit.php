<?php
include '../koneksi/koneksi.php';
include '../template/auth_check.php';

// 1. Cek apakah ada ID di URL
if (!isset($_GET['id'])) {
    echo "<script>alert('ID tidak ditemukan'); window.location='index.php';</script>";
    exit();
}

$id = $_GET['id'];

// 2. Ambil data promo berdasarkan ID
$query = mysqli_query($conn, "SELECT * FROM promo WHERE promo_id = '$id'");
$data = mysqli_fetch_assoc($query);

// Jika data tidak ditemukan
if (mysqli_num_rows($query) < 1) {
    echo "<script>alert('Data tidak ditemukan'); window.location='index.php';</script>";
    exit();
}

// 3. Proses Update Data
if (isset($_POST['update'])) {
    $nama_promo = $_POST['nama_promo'];
    $deskripsi  = $_POST['deskripsi'];
    $potongan   = $_POST['potongan'];
    $tgl_mulai  = $_POST['tanggal_mulai'];
    $tgl_selesai= $_POST['tanggal_selesai'];

    $update_query = "UPDATE promo SET 
                        nama_promo = '$nama_promo',
                        deskripsi = '$deskripsi',
                        potongan = '$potongan',
                        tanggal_mulai = '$tgl_mulai',
                        tanggal_selesai = '$tgl_selesai'
                     WHERE promo_id = '$id'";
    
    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Promo Berhasil Diupdate'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate data: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Edit Promo - GearZone</title>
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
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
                    <h1 class="mt-4">Edit Data Promo</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Promo</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                    
                    <div class="card mb-4 col-md-8">
                        <div class="card-header fw-bold">
                            <i class="fas fa-edit me-1"></i> Form Edit Promo
                        </div>
                        <div class="card-body">
                            <form action="" method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Kode Promo (Nama)</label>
                                    <input type="text" name="nama_promo" class="form-control" 
                                           value="<?= htmlspecialchars($data['nama_promo']) ?>" 
                                           required style="text-transform: uppercase;">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Deskripsi Singkat</label>
                                    <textarea name="deskripsi" class="form-control" rows="2"><?= htmlspecialchars($data['deskripsi']) ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Besar Potongan (Rp)</label>
                                    <input type="number" name="potongan" class="form-control" 
                                           value="<?= $data['potongan'] ?>" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal Mulai</label>
                                        <input type="date" name="tanggal_mulai" class="form-control" 
                                               value="<?= $data['tanggal_mulai'] ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal Selesai</label>
                                        <input type="date" name="tanggal_selesai" class="form-control" 
                                               value="<?= $data['tanggal_selesai'] ?>" required>
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <button type="submit" name="update" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                                    <a href="index.php" class="btn btn-secondary">Batal</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="text-muted small">Copyright &copy; GearZone 2025</div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/scripts.js"></script>
</body>
</html>