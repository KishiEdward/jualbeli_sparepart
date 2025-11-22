<?php
include_once '../koneksi/koneksi.php';
include_once '../template/auth_check.php';

// Menangkap Data Filter Tanggal
// Jika tidak ada request tanggal, default-nya adalah Tanggal 1 bulan ini s/d Hari ini
$tgl_mulai   = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : date('Y-m-01');
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan - GearZone</title>
    <link href="../css/styles.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        /* CSS khusus print agar elemen filter & sidebar hilang saat dicetak */
        @media print {
            .sb-nav-fixed #layoutSidenav_nav, 
            .sb-nav-fixed .sb-topnav, 
            .filter-card, 
            .btn-print, 
            footer {
                display: none !important;
            }
            .sb-nav-fixed #layoutSidenav_content {
                margin-left: 0 !important;
                padding: 0;
            }
            .container-fluid {
                padding: 0;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
            }
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
                    <h1 class="mt-4">Laporan Keuangan</h1>
                    
                    <div class="card mb-4 filter-card">
                        <div class="card-body">
                            <form method="GET" action="">
                                <div class="row align-items-end">
                                    <div class="col-md-3">
                                        <label class="fw-bold">Dari Tanggal</label>
                                        <input type="date" name="tgl_mulai" class="form-control" value="<?= $tgl_mulai; ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="fw-bold">Sampai Tanggal</label>
                                        <input type="date" name="tgl_selesai" class="form-control" value="<?= $tgl_selesai; ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-filter"></i> Tampilkan
                                        </button>
                                    </div>
                                    <div class="col-md-2">
                                        <a href="index.php" class="btn btn-secondary w-100">Reset</a>
                                    </div>
                                    <div class="col-md-2">
                                        <button onclick="window.print()" class="btn btn-success w-100 btn-print">
                                            <i class="fas fa-print"></i> Cetak
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Laporan Penjualan (Status: Paid)
                        </div>
                        <div class="card-body">
                            <div class="d-none d-print-block mb-3">
                                <h3>Laporan Keuangan GearZone</h3>
                                <p>Periode: <?= date('d-m-Y', strtotime($tgl_mulai)); ?> s/d <?= date('d-m-Y', strtotime($tgl_selesai)); ?></p>
                            </div>

                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>No Invoice</th>
                                        <th>Pelanggan</th>
                                        <th class="text-end">Jumlah Pendapatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Query Filter Tanggal & Status Paid
                                    $query = "SELECT t.*, u.nama 
                                              FROM transaksi t 
                                              JOIN users u ON t.user_id = u.user_id
                                              WHERE t.status = 'paid' 
                                              AND DATE(t.tanggal_datetime) BETWEEN '$tgl_mulai' AND '$tgl_selesai'
                                              ORDER BY t.tanggal_datetime ASC";
                                    
                                    $result = mysqli_query($conn, $query);
                                    $no = 1;
                                    $grand_total = 0;

                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $grand_total += $row['total_harga'];
                                    ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= date('d/m/Y', strtotime($row['tanggal_datetime'])); ?></td>
                                        <td>#INV-<?= $row['transaksi_id']; ?></td>
                                        <td><?= htmlspecialchars($row['nama']); ?></td>
                                        <td class="text-end">Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                    </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='5' class='text-center'>Tidak ada data penjualan pada periode ini.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="4" class="text-center fw-bold fs-5">GRAND TOTAL PENDAPATAN</th>
                                        <th class="text-end fw-bold fs-5 text-success">
                                            Rp <?= number_format($grand_total, 0, ',', '.'); ?>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
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