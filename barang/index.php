<?php
    include_once '../koneksi/koneksi.php';
    include_once '../template/auth_check.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Data Produk - GearZone</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="../css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

        <style>
            /* Biar kolom deskripsi tidak terlalu lebar */
            .col-deskripsi {
                max-width: 250px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
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
                        <h1 class="mt-4">Data Produk</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Produk</li>
                        </ol>

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Data Produk
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Gambar</th>
                                            <th>Nama Produk</th>
                                            <th>Kategori</th>
                                            <th class="col-deskripsi">Deskripsi</th>
                                            <th>Harga</th>
                                            <th>Stok</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Gambar</th>
                                            <th>Nama Produk</th>
                                            <th>Kategori</th>
                                            <th class="col-deskripsi">Deskripsi</th>
                                            <th>Harga</th>
                                            <th>Stok</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                            $query = "SELECT * FROM produk";
                                            $result = mysqli_query($conn, $query);

                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo "<tr>";

                                                    // Gambar
                                                    if (!empty($row['gambar'])) {
                                                        echo "<td><img src='../assets/img/" . htmlspecialchars($row['gambar']) . "' width='70' height='70' class='rounded'></td>";
                                                    } else {
                                                        echo "<td><span class='text-muted fst-italic'>Tidak ada gambar</span></td>";
                                                    }
                                                    // Nama produk
                                                    echo "<td>" . htmlspecialchars($row['nama_produk']) . "</td>";
                                                    // Kategori
                                                    echo "<td>" . htmlspecialchars($row['kategori_id']) . "</td>";
                                                    // Deskripsi (pakai title biar saat hover muncul teks lengkap)
                                                    echo "<td class='col-deskripsi' title='" . htmlspecialchars($row['deskripsi']) . "'>" . htmlspecialchars($row['deskripsi']) . "</td>";
                                                    // Harga
                                                    echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                                                    // Stok
                                                    echo "<td>" . htmlspecialchars($row['stok']) . "</td>";
                                                    // Aksi
                                                    echo "<td>
                                                            <a href='edit_produk.php?id=" . $row['produk_id'] . "' class='btn btn-sm btn-primary'>Edit</a>
                                                            <a href='hapus_produk.php?id=" . $row['produk_id'] . "' class='btn btn-sm btn-danger' onclick=\"return confirm('Yakin ingin hapus produk ini?')\">Hapus</a>
                                                          </td>";

                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='7' class='text-center text-muted'>Belum ada data produk</td></tr>";
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>

                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; GearZone 2025</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="../js/scripts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="../js/datatables-simple-demo.js"></script>
    </body>
</html>