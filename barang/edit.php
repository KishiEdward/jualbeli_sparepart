<?php
include_once '../koneksi/koneksi.php';
include_once '../template/auth_check.php';

// Pastikan ada parameter id
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM produk WHERE produk_id = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan'); window.location='index.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk - GearZone</title>
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
                <h1 class="mt-4">Edit Produk</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="index.php">Produk</a></li>
                    <li class="breadcrumb-item active">Edit Produk</li>
                </ol>

                <div class="card mb-4">
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="produk_id" value="<?= $data['produk_id']; ?>">

                            <div class="mb-3">
                                <label class="form-label">Nama Produk</label>
                                <input type="text" name="nama_produk" class="form-control" 
                                       value="<?= htmlspecialchars($data['nama_produk']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="kategori_id" class="form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php
                                    $kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
                                    while ($row = mysqli_fetch_assoc($kategori)) {
                                        $selected = ($row['kategori_id'] == $data['kategori_id']) ? 'selected' : '';
                                        echo "<option value='{$row['kategori_id']}' $selected>{$row['nama_kategori']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="3" required><?= htmlspecialchars($data['deskripsi']); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Harga</label>
                                <input type="number" name="harga" class="form-control" 
                                       value="<?= htmlspecialchars($data['harga']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Stok</label>
                                <input type="number" name="stok" class="form-control" 
                                       value="<?= htmlspecialchars($data['stok']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Gambar Produk</label><br>
                                <?php if (!empty($data['gambar'])): ?>
                                    <img src="../assets/img/<?= htmlspecialchars($data['gambar']); ?>" width="100" class="rounded mb-2">
                                    <br>
                                <?php endif; ?>
                                <input type="file" name="gambar" class="form-control" accept="image/*">
                                <small class="text-muted fst-italic">Kosongkan jika tidak ingin mengganti gambar.</small>
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
// Proses update produk
if (isset($_POST['update'])) {
    $id = $_POST['produk_id'];
    $nama_produk = $_POST['nama_produk'];
    $kategori_id = $_POST['kategori_id'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    // Cek upload gambar baru
    $gambar_baru = $_FILES['gambar']['name'];
    $tmp_name = $_FILES['gambar']['tmp_name'];
    $upload_dir = "../assets/img/";

    if (!empty($gambar_baru)) {
        move_uploaded_file($tmp_name, $upload_dir . $gambar_baru);
        $gambar_update = ", gambar = '$gambar_baru'";
    } else {
        $gambar_update = "";
    }

    $query = "UPDATE produk 
              SET nama_produk = '$nama_produk', 
                  kategori_id = '$kategori_id',
                  deskripsi = '$deskripsi',
                  harga = '$harga',
                  stok = '$stok'
                  $gambar_update
              WHERE produk_id = '$id'";

    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "
        <script>
            Swal.fire({
                title: 'Berhasil!',
                text: 'Data produk berhasil diperbarui!',
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
