<?php
include_once '../koneksi/koneksi.php';
include_once '../template/auth_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Pesanan - GearZone</title>
    <link href="../css/styles.css" rel="stylesheet">
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
                    <h1 class="mt-4">Data Pesanan Masuk</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Pesanan</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-shopping-cart me-1"></i>
                            Daftar Transaksi Pelanggan
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No Invoice</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Tanggal</th>
                                        <th>Total Bayar</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // KITA GUNAKAN JOIN
                                    // Mengambil data transaksi + nama user dari tabel users
                                    $query = "SELECT transaksi.*, users.nama 
                                              FROM transaksi 
                                              JOIN users ON transaksi.user_id = users.user_id 
                                              ORDER BY transaksi.transaksi_id DESC";
                                              
                                    $result = mysqli_query($conn, $query);

                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            // Logika warna badge status (Sesuai Enum: pending, paid, cancelled)
                                            $status = $row['status'];
                                            $badge = 'bg-secondary'; 
                                            $status_text = $status;

                                            if ($status == 'pending') {
                                                $badge = 'bg-warning text-dark';
                                                $status_text = 'Menunggu';
                                            } elseif ($status == 'paid') {
                                                $badge = 'bg-success';
                                                $status_text = 'Lunas';
                                            } elseif ($status == 'cancelled') {
                                                $badge = 'bg-danger';
                                                $status_text = 'Dibatalkan';
                                            }
                                    ?>
                                    <tr>
                                        <td><strong>#INV-<?= $row['transaksi_id']; ?></strong></td>
                                        
                                        <td><?= htmlspecialchars($row['nama']); ?></td>
                                        
                                        <td><?= date('d-m-Y H:i', strtotime($row['tanggal_datetime'])); ?></td>
                                        
                                        <td>Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                        
                                        <td>
                                            <span class="badge <?= $badge; ?>"><?= $status_text; ?></span>
                                        </td>
                                        
                                        <td>
                                            <a href="detail.php?id=<?= $row['transaksi_id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='6' class='text-center text-muted'>Belum ada pesanan masuk.</td></tr>";
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
    <script src="../js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    <script src="../js/datatables-simple-demo.js"></script>
</body>
</html>