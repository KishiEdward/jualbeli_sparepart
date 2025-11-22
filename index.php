<?php
    include_once 'koneksi/koneksi.php';
    include_once 'template/auth_check.php';
    
    // Menampilkan pesan selamat datang sekali setelah login
    $selamatdatang = "";
    if (isset($_SESSION['selamatdatang'])) {
        $selamatdatang = $_SESSION['selamatdatang'];
        unset($_SESSION['selamatdatang']);
    }

    // ==========================================================
    // LOGIKA DASHBOARD REAL-TIME (SESUAI INSTRUKSI GAMBAR 6)
    // ==========================================================

    // 1. Pendapatan Bulan Ini (Hanya yang status 'paid' dan Bulan ini)
    $query_income = "SELECT SUM(total_harga) AS total 
                     FROM transaksi 
                     WHERE status = 'paid' 
                     AND MONTH(tanggal_datetime) = MONTH(CURRENT_DATE())
                     AND YEAR(tanggal_datetime) = YEAR(CURRENT_DATE())";
    $res_income = mysqli_query($conn, $query_income);
    $row_income = mysqli_fetch_assoc($res_income);
    $pendapatan_bulan_ini = $row_income['total'] ?? 0; // Kalau null, anggap 0

    // 2. Pesanan Perlu Dikirim (Status 'paid' tapi belum selesai)
    // Asumsi: yang 'paid' adalah yang harus segera diproses kirim
    $query_paid = "SELECT COUNT(*) AS jumlah FROM transaksi WHERE status = 'paid'";
    $res_paid = mysqli_query($conn, $query_paid);
    $row_paid = mysqli_fetch_assoc($res_paid);
    $perlu_dikirim = $row_paid['jumlah'];

    // 3. Stok Menipis (Stok < 5)
    $query_stok = "SELECT COUNT(*) AS jumlah FROM produk WHERE stok < 5";
    $res_stok = mysqli_query($conn, $query_stok);
    $row_stok = mysqli_fetch_assoc($res_stok);
    $stok_menipis = $row_stok['jumlah'];

    // 4. Total Pelanggan (Role = 'cust')
    $query_cust = "SELECT COUNT(*) AS jumlah FROM users WHERE role = 'cust'";
    $res_cust = mysqli_query($conn, $query_cust);
    $row_cust = mysqli_fetch_assoc($res_cust);
    $total_pelanggan = $row_cust['jumlah'];

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Dashboard - GearZone Admin</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class="sb-nav-fixed">
        <?php if (!empty($selamatdatang)) : ?>
            <script>
                Swal.fire({
                    title: 'Berhasil Login 🎉',
                    text: '<?php echo $selamatdatang ?>',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            </script>
        <?php endif; ?>

        <?php include 'template/topbar.php'; ?>
        
        <div id="layoutSidenav">
            <?php include 'template/sidebar.php'; ?>
            
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Dashboard</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Ringkasan Bisnis Hari Ini</li>
                        </ol>

                        <div class="row">
                            
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="small text-white-50">Pendapatan Bulan Ini</div>
                                                <div class="h3 mb-0">Rp <?= number_format($pendapatan_bulan_ini, 0, ',', '.') ?></div>
                                            </div>
                                            <i class="fas fa-dollar-sign fa-2x text-white-50"></i>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">Lihat Detail</a> 
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="small text-white-50">Perlu Dikirim</div>
                                                <div class="h3 mb-0"><?= $perlu_dikirim ?> Pesanan</div>
                                            </div>
                                            <i class="fas fa-box fa-2x text-white-50"></i>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="pesanan/index.php">Kelola Pesanan</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="small text-white-50">Stok Menipis (< 5)</div>
                                                <div class="h3 mb-0"><?= $stok_menipis ?> Item</div>
                                            </div>
                                            <i class="fas fa-exclamation-triangle fa-2x text-white-50"></i>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="barang/index.php">Cek Stok</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="small text-white-50">Total Pelanggan</div>
                                                <div class="h3 mb-0"><?= $total_pelanggan ?> Orang</div>
                                            </div>
                                            <i class="fas fa-users fa-2x text-white-50"></i>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="users/index.php">Lihat User</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card mb-4">
                                    <div class="card-header justify-content-between d-flex align-items-center">
                                        <div>
                                            <i class="fas fa-chart-bar me-1"></i>
                                            Grafik Penjualan Tahun Ini
                                        </div>
                                        <a href="laporan/index.php" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-file-invoice-dollar me-1"></i> Lihat Laporan Keuangan
                                        </a>
                                    </div>
                                    <div class="card-body"><canvas id="myBarChart" width="100%" height="20"></canvas></div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Daftar Produk GearZone
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
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                            // Menampilkan tabel produk secara dinamis
                                            $query = "SELECT produk.*, kategori.nama_kategori 
                                                      FROM produk 
                                                      JOIN kategori ON produk.kategori_id = kategori.kategori_id";
                                            $result = mysqli_query($conn, $query);

                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo "<tr>";

                                                    if (!empty($row['gambar'])) {
                                                        echo "<td><img src='assets/img/" . htmlspecialchars($row['gambar']) . "' width='70' height='70' class='rounded'></td>";
                                                    } else {
                                                        echo "<td><span class='text-muted fst-italic'>Tidak ada gambar</span></td>";
                                                    }

                                                    echo "<td>" . htmlspecialchars($row['nama_produk']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['nama_kategori']) . "</td>";
                                                    echo "<td class='col-deskripsi' title='" . htmlspecialchars($row['deskripsi']) . "'>" . htmlspecialchars($row['deskripsi']) . "</td>";
                                                    echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                                                    
                                                    // Stok dikasih warna merah jika sedikit
                                                    if ($row['stok'] < 5) {
                                                        echo "<td class='text-danger fw-bold'>" . htmlspecialchars($row['stok']) . "</td>";
                                                    } else {
                                                        echo "<td>" . htmlspecialchars($row['stok']) . "</td>";
                                                    }
                                                    
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='6' class='text-center text-muted'>Belum ada data produk</td></tr>";
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
                            <div class="text-muted">Copyright &copy; Gearzone 2025</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>