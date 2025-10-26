
<?php
include_once '../koneksi/koneksi.php';
include_once '../template/auth_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk - GearZone</title>
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
                <h1 class="mt-4">Tambah Produk</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="index.php">Produk</a></li>
                    <li class="breadcrumb-item active">Tambah Produk</li>
                </ol>

                <div class="card mb-4">
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Nama Produk</label>
                                <input type="text" name="nama_produk" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="kategori_id" class="form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php
                                    $kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
                                    while ($row = mysqli_fetch_assoc($kategori)) {
                                        echo "<option value='{$row['kategori_id']}'>{$row['nama_kategori']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Harga</label>
                                <input type="number" name="harga" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Stok</label>
                                <input type="number" name="stok" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Gambar Produk</label>
                                <input type="file" name="gambar" class="form-control" accept="image/*">
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
// Proses form tambah produk
if (isset($_POST['submit'])) {
    include_once '../koneksi/koneksi.php';

    $nama_produk = $_POST['nama_produk'];
    $kategori_id = $_POST['kategori_id'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    // Upload gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp_name = $_FILES['gambar']['tmp_name'];
    $upload_dir = "../assets/img/";

    if (!empty($gambar)) {
        move_uploaded_file($tmp_name, $upload_dir . $gambar);
    }

    // Simpan ke database
    $query = "INSERT INTO produk (nama_produk, kategori_id, deskripsi, harga, stok, gambar)
              VALUES ('$nama_produk', '$kategori_id', '$deskripsi', '$harga', '$stok', '$gambar')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "
        <script>
            Swal.fire({
                title: 'Berhasil!',
                text: 'Produk berhasil ditambahkan!',
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
