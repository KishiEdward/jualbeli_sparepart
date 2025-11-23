<?php
include_once '../koneksi/koneksi.php';
include_once '../template/auth_check.php';

if (isset($_POST['simpan'])) {
    $nama_produk = $_POST['nama_produk'];
    $kategori_id = $_POST['kategori_id'];
    $deskripsi   = $_POST['deskripsi'];
    $harga       = $_POST['harga'];
    $stok        = $_POST['stok'];
    $model_ids   = isset($_POST['model_ids']) ? $_POST['model_ids'] : []; 

    // Upload Gambar
    $gambar = $_FILES['gambar']['name'];
    $nama_gambar_baru = null;

    if ($gambar != "") {
        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg');
        $x = explode('.', $gambar);
        $ekstensi = strtolower(end($x));
        $file_tmp = $_FILES['gambar']['tmp_name'];
        $nama_gambar_baru = time() . '-' . $gambar;

        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            move_uploaded_file($file_tmp, '../assets/img/' . $nama_gambar_baru);
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Ekstensi gambar tidak valid (harus jpg, jpeg, png)',
                    icon: 'error'
                });
            </script>";
            exit; // Stop eksekusi
        }
    }

    // INSERT DATA
    $query_produk = "INSERT INTO produk (nama_produk, kategori_id, deskripsi, harga, stok, gambar) 
                     VALUES ('$nama_produk', '$kategori_id', '$deskripsi', '$harga', '$stok', '$nama_gambar_baru')";
    
    if (mysqli_query($conn, $query_produk)) {
        $produk_id_baru = mysqli_insert_id($conn);

        // INSERT RELASI
        if (!empty($model_ids)) {
            foreach ($model_ids as $model_id) {
                $query_relasi = "INSERT INTO produk_kompatibel (produk_id, model_id) VALUES ('$produk_id_baru', '$model_id')";
                mysqli_query($conn, $query_relasi);
            }
        }

        // SWEETALERT SUKSES (Echo Script di sini)
        // Kita echo script setelah HTML dirender agar library SWAL terbaca, 
        // tapi karena logic ini di atas, kita perlu trik sedikit atau pastikan SWAL ada di head.
        // Cara paling aman: Simpan status di variabel, lalu cetak di bawah.
        $berhasil = true;
    } else {
        $error_msg = mysqli_error($conn);
        $berhasil = false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk - GearZone</title>
    <link href="../css/styles.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    <h1 class="mt-4">Tambah Produk Baru</h1>
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label>Nama Produk</label>
                                    <input type="text" name="nama_produk" class="form-control" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Kategori</label>
                                        <select name="kategori_id" class="form-select" required>
                                            <option value="">-- Pilih Kategori --</option>
                                            <?php
                                            $kat = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
                                            while ($k = mysqli_fetch_assoc($kat)) {
                                                echo "<option value='{$k['kategori_id']}'>{$k['nama_kategori']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Harga (Rp)</label>
                                        <input type="number" name="harga" class="form-control" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="mb-2 fw-bold">Kompatibel untuk Motor (Boleh pilih banyak)</label>
                                    <div class="checkbox-group bg-light">
                                        <div class="row">
                                            <?php
                                            $mod = mysqli_query($conn, "SELECT * FROM model_motor ORDER BY merek ASC, nama_model ASC");
                                            while ($m = mysqli_fetch_assoc($mod)) {
                                            ?>
                                            <div class="col-md-4 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="model_ids[]" value="<?= $m['model_id']; ?>" id="mod<?= $m['model_id']; ?>">
                                                    <label class="form-check-label" for="mod<?= $m['model_id']; ?>">
                                                        <?= $m['nama_model']; ?> <small class="text-muted">(<?= $m['merek']; ?>)</small>
                                                    </label>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <small class="text-muted">*Jika universal, tidak perlu dicentang.</small>
                                </div>

                                <div class="mb-3">
                                    <label>Stok</label>
                                    <input type="number" name="stok" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Deskripsi</label>
                                    <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Gambar Produk</label>
                                    <input type="file" name="gambar" class="form-control">
                                </div>

                                <button type="submit" name="simpan" class="btn btn-primary">Simpan Produk</button>
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
                text: 'Produk berhasil ditambahkan!',
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
                text: 'Terjadi kesalahan: <?= $error_msg; ?>',
                icon: 'error',
                confirmButtonText: 'Coba Lagi'
            });
        </script>
    <?php endif; ?>
</body>
</html>