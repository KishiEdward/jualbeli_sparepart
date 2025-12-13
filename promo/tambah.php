<?php
include '../koneksi/koneksi.php';
include '../template/auth_check.php'; // Opsional, sesuaikan kebutuhan

if (isset($_POST['simpan'])) {
    $nama_promo = $_POST['nama_promo'];
    $deskripsi  = $_POST['deskripsi'];
    $potongan   = $_POST['potongan'];
    $tgl_mulai  = $_POST['tanggal_mulai'];
    $tgl_selesai= $_POST['tanggal_selesai'];

    $query = "INSERT INTO promo (nama_promo, deskripsi, potongan, tanggal_mulai, tanggal_selesai) 
              VALUES ('$nama_promo', '$deskripsi', '$potongan', '$tgl_mulai', '$tgl_selesai')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Promo Berhasil Ditambahkan'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Tambah Promo - GearZone</title>
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
                    <h1 class="mt-4">Tambah Promo Baru</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Promo</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
                    
                    <div class="card mb-4 col-md-8">
                        <div class="card-header fw-bold">
                            <i class="fas fa-plus me-1"></i> Form Input Promo
                        </div>
                        <div class="card-body">
                            <form action="" method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Kode Promo (Nama)</label>
                                    <input type="text" name="nama_promo" class="form-control" placeholder="Contoh: MERDEKA17" required style="text-transform: uppercase;">
                                    <div class="form-text">Gunakan huruf kapital tanpa spasi untuk kode unik.</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Deskripsi Singkat</label>
                                    <textarea name="deskripsi" class="form-control" rows="2" placeholder="Contoh: Promo khusus hari kemerdekaan..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Besar Potongan (Rp)</label>
                                    <input type="number" name="potongan" class="form-control" placeholder="Contoh: 15000" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal Mulai</label>
                                        <input type="date" name="tanggal_mulai" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal Selesai</label>
                                        <input type="date" name="tanggal_selesai" class="form-control" required>
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <button type="submit" name="simpan" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Promo</button>
                                    <a href="index.php" class="btn btn-secondary">Kembali</a>
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