<?php
include_once '../koneksi/koneksi.php';
include_once '../template/auth_check.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}
$id = $_GET['id'];

// Ambil Data Produk Lama
$query = "SELECT * FROM produk WHERE produk_id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Ambil Data Model yang SUDAH TERPILIH (Masuk ke array)
$query_selected = "SELECT model_id FROM produk_kompatibel WHERE produk_id = '$id'";
$result_selected = mysqli_query($conn, $query_selected);
$selected_models = [];
while($row = mysqli_fetch_assoc($result_selected)){
    $selected_models[] = $row['model_id'];
}

// PROSES UPDATE
if (isset($_POST['update'])) {
    $nama_produk = $_POST['nama_produk'];
    $kategori_id = $_POST['kategori_id'];
    $deskripsi   = $_POST['deskripsi'];
    $harga       = $_POST['harga'];
    $stok        = $_POST['stok'];
    $model_ids   = isset($_POST['model_ids']) ? $_POST['model_ids'] : [];

    // Logic Gambar
    $gambar_nama = $data['gambar']; 
    if ($_FILES['gambar']['name'] != "") {
        $nama_file = $_FILES['gambar']['name'];
        $tmp_file = $_FILES['gambar']['tmp_name'];
        $gambar_nama = time() . "-" . $nama_file;
        move_uploaded_file($tmp_file, "../assets/img/" . $gambar_nama);
    }

    // 1. UPDATE TABEL PRODUK
    $update_produk = "UPDATE produk SET 
                      nama_produk = '$nama_produk',
                      kategori_id = '$kategori_id',
                      harga = '$harga',
                      stok = '$stok',
                      deskripsi = '$deskripsi',
                      gambar = '$gambar_nama'
                      WHERE produk_id = '$id'";
    
    if (mysqli_query($conn, $update_produk)) {
        
        // 2. UPDATE MODEL (HAPUS SEMUA DULU, BARU ISI ULANG)
        // Hapus relasi lama
        mysqli_query($conn, "DELETE FROM produk_kompatibel WHERE produk_id = '$id'");
        
        // Isi relasi baru
        if (!empty($model_ids)) {
            foreach ($model_ids as $mid) {
                mysqli_query($conn, "INSERT INTO produk_kompatibel (produk_id, model_id) VALUES ('$id', '$mid')");
            }
        }
        $berhasil_edit = true;
    } else {
        $error_msg = mysqli_error($conn);
        $berhasil_edit = false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Produk - GearZone</title>
    <link href="../css/styles.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"></script>
    <style>
        .checkbox-group {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ced4da;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
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
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label>Nama Produk</label>
                                    <input type="text" name="nama_produk" class="form-control" value="<?= htmlspecialchars($data['nama_produk']); ?>" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Kategori</label>
                                        <select name="kategori_id" class="form-select" required>
                                            <?php
                                            $kat = mysqli_query($conn, "SELECT * FROM kategori");
                                            while ($k = mysqli_fetch_assoc($kat)) {
                                                $selected = ($k['kategori_id'] == $data['kategori_id']) ? 'selected' : '';
                                                echo "<option value='{$k['kategori_id']}' $selected>{$k['nama_kategori']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Harga</label>
                                        <input type="number" name="harga" class="form-control" value="<?= $data['harga']; ?>" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="fw-bold mb-2">Kompatibel untuk Motor</label>
                                    <div class="checkbox-group bg-light">
                                        <div class="row">
                                            <?php
                                            $mod = mysqli_query($conn, "SELECT * FROM model_motor ORDER BY merek ASC");
                                            while ($m = mysqli_fetch_assoc($mod)) {
                                                // Cek apakah ID motor ini ada di array selected_models
                                                $isChecked = in_array($m['model_id'], $selected_models) ? 'checked' : '';
                                            ?>
                                            <div class="col-md-4 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="model_ids[]" value="<?= $m['model_id']; ?>" id="m<?= $m['model_id']; ?>" <?= $isChecked; ?>>
                                                    <label class="form-check-label" for="m<?= $m['model_id']; ?>">
                                                        <?= $m['nama_model']; ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label>Stok</label>
                                    <input type="number" name="stok" class="form-control" value="<?= $data['stok']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label>Deskripsi</label>
                                    <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($data['deskripsi']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Gambar (Biarkan kosong jika tidak ganti)</label>
                                    <br>
                                    <?php if($data['gambar']): ?>
                                        <img src="../assets/img/<?= $data['gambar']; ?>" width="100" class="mb-2 rounded">
                                    <?php endif; ?>
                                    <input type="file" name="gambar" class="form-control">
                                </div>

                                <button type="submit" name="update" class="btn btn-warning text-white">Update Produk</button>
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
    <?php if (isset($berhasil_edit) && $berhasil_edit === true) : ?>
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
    <?php elseif (isset($berhasil_edit) && $berhasil_edit === false) : ?>
    <script>
        Swal.fire({
            title: 'Gagal!',
            text: 'Terjadi kesalahan: <?= $error_msg; ?>',
            icon: 'error'
        });
    </script>
    <?php endif; ?>
</body>
</html>