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
    // LOGIKA DASHBOARD REAL-TIME
    // ==========================================================

    // 1. KARTU: Pendapatan Bulan Ini
    $query_income = "SELECT SUM(total_harga) AS total FROM transaksi 
                     WHERE status = 'paid' 
                     AND MONTH(tanggal_datetime) = MONTH(CURRENT_DATE())
                     AND YEAR(tanggal_datetime) = YEAR(CURRENT_DATE())";
    $res_income = mysqli_query($conn, $query_income);
    $pendapatan_bulan_ini = mysqli_fetch_assoc($res_income)['total'] ?? 0;

    // 2. KARTU: Pesanan Perlu Dikirim
    $query_paid = "SELECT COUNT(*) AS jumlah FROM transaksi WHERE status = 'paid'";
    $res_paid = mysqli_query($conn, $query_paid);
    $perlu_dikirim = mysqli_fetch_assoc($res_paid)['jumlah'];

    // 3. KARTU: Stok Menipis
    $query_stok = "SELECT COUNT(*) AS jumlah FROM produk WHERE stok < 5";
    $res_stok = mysqli_query($conn, $query_stok);
    $stok_menipis = mysqli_fetch_assoc($res_stok)['jumlah'];

    // 4. KARTU: Total Pelanggan
    $query_cust = "SELECT COUNT(*) AS jumlah FROM users WHERE role = 'cust'";
    $res_cust = mysqli_query($conn, $query_cust);
    $total_pelanggan = mysqli_fetch_assoc($res_cust)['jumlah'];

    // ==========================================================
    // LOGIKA GRAFIK (CHART) REAL-TIME
    // ==========================================================
    // Siapkan array kosong untuk 12 bulan (Jan-Des), isi default 0
    $penjualan_per_bulan = array_fill(0, 12, 0); 

    // Ambil data penjualan per bulan di tahun ini
    $tahun_ini = date('Y');
    $query_chart = "SELECT MONTH(tanggal_datetime) as bulan, SUM(total_harga) as total 
                    FROM transaksi 
                    WHERE status = 'paid' AND YEAR(tanggal_datetime) = '$tahun_ini' 
                    GROUP BY MONTH(tanggal_datetime)";
    $res_chart = mysqli_query($conn, $query_chart);

    while($row_chart = mysqli_fetch_assoc($res_chart)){
        // Bulan MySQL 1-12, Array PHP 0-11. Jadi indeks = bulan - 1
        $index = $row_chart['bulan'] - 1; 
        $penjualan_per_bulan[$index] = $row_chart['total'];
    }
    
    // Ubah array PHP ke format JSON biar bisa dibaca Javascript
    $data_grafik_json = json_encode(array_values($penjualan_per_bulan));
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
                                        <a class="small text-white stretched-link" href="laporan/index.php">Lihat Laporan</a>
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
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-chart-bar me-1"></i>
                                            Grafik Pendapatan Tahun <?= $tahun_ini ?>
                                        </div>
                                        <a href="laporan/index.php" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-file-invoice-dollar me-1"></i> Laporan Lengkap
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="myBarChart" width="100%" height="30"></canvas>
                                    </div>
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
                                    <tbody>
                                        <?php
                                            $query = "SELECT produk.*, kategori.nama_kategori 
                                                      FROM produk 
                                                      JOIN kategori ON produk.kategori_id = kategori.kategori_id";
                                            $result = mysqli_query($conn, $query);

                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo "<tr>";
                                                    if (!empty($row['gambar'])) {
                                                        echo "<td><img src='assets/img/" . htmlspecialchars($row['gambar']) . "' width='50' class='rounded'></td>";
                                                    } else {
                                                        echo "<td>-</td>";
                                                    }
                                                    echo "<td>" . htmlspecialchars($row['nama_produk']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['nama_kategori']) . "</td>";
                                                    echo "<td class='col-deskripsi text-truncate' style='max-width: 200px;'>" . htmlspecialchars($row['deskripsi']) . "</td>";
                                                    echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                                                    
                                                    if ($row['stok'] < 5) {
                                                        echo "<td class='text-danger fw-bold'>" . htmlspecialchars($row['stok']) . "</td>";
                                                    } else {
                                                        echo "<td>" . htmlspecialchars($row['stok']) . "</td>";
                                                    }
                                                    echo "</tr>";
                                                }
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
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
        <script src="js/datatables-simple-demo.js"></script>

        <script>
        // Mengatur font family default
        Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#292b2c';

        // Mengambil data dari PHP Variable
        var dataPenjualan = <?php echo $data_grafik_json; ?>; 

        var ctx = document.getElementById("myBarChart");
        var myBarChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"],
                datasets: [{
                    label: "Pendapatan",
                    backgroundColor: "rgba(2,117,216,1)",
                    borderColor: "rgba(2,117,216,1)",
                    data: dataPenjualan, // Ini data dinamis dari database
                }],
            },
            options: {
                scales: {
                    xAxes: [{
                        time: { unit: 'month' },
                        gridLines: { display: false },
                        ticks: { maxTicksLimit: 12 }
                    }],
                    yAxes: [{
                        ticks: {
                            min: 0,
                            maxTicksLimit: 5,
                            // Format mata uang di sumbu Y (Rp)
                            callback: function(value, index, values) {
                                return 'Rp ' + value.toLocaleString("id-ID");
                            }
                        },
                        gridLines: { display: true }
                    }],
                },
                legend: { display: false },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': Rp ' + tooltipItem.yLabel.toLocaleString("id-ID");
                        }
                    }
                }
            }
        });
        </script>
    </body>
</html>