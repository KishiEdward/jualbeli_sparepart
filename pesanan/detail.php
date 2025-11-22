<?php
include_once '../koneksi/koneksi.php';
include_once '../template/auth_check.php';

// 1. Cek ID di URL
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

// 2. Proses Update Status & Resi (Jika tombol Simpan ditekan)
if (isset($_POST['update_status'])) {
    $status_transaksi = $_POST['status'];
    $status_kirim     = $_POST['status_kirim'];
    $no_resi          = $_POST['no_resi'];

    // Update Tabel Transaksi
    $query1 = "UPDATE transaksi SET status = '$status_transaksi' WHERE transaksi_id = '$id'";
    mysqli_query($conn, $query1);

    // Update Tabel Pengiriman (Resi & Status Kirim)
    // Kita cek dulu apakah data pengiriman ada? Jika tidak, insert, jika ada, update.
    // Tapi agar simpel, kita asumsikan data pengiriman sudah dibuat saat checkout.
    $query2 = "UPDATE pengiriman SET 
               status_kirim = '$status_kirim',
               no_resi = '$no_resi'
               WHERE transaksi_id = '$id'";
    mysqli_query($conn, $query2);

    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Status pesanan diperbarui.',
                icon: 'success'
            }).then(() => {
                window.location.href = 'detail.php?id=$id';
            });
        });
    </script>";
}

// 3. Ambil Data Utama (Header: Transaksi + User + Pengiriman)
$query_header = "SELECT t.*, u.nama, u.email, u.no_hp, 
                 p.alamat_tujuan, p.jasa_kirim, p.no_resi, p.status_kirim
                 FROM transaksi t
                 JOIN users u ON t.user_id = u.user_id
                 LEFT JOIN pengiriman p ON t.transaksi_id = p.transaksi_id
                 WHERE t.transaksi_id = '$id'";
$result_header = mysqli_query($conn, $query_header);
$data = mysqli_fetch_assoc($result_header);

// Jika data tidak ditemukan
if (!$data) {
    echo "<script>alert('Data transaksi tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detail Pesanan #<?= $id; ?> - GearZone</title>
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
                    <h1 class="mt-4">Detail Pesanan</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Pesanan</a></li>
                        <li class="breadcrumb-item active">Detail #INV-<?= $id; ?></li>
                    </ol>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <i class="fas fa-user me-1"></i> Info Pelanggan
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th>Nama</th>
                                            <td>: <?= htmlspecialchars($data['nama']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>: <?= htmlspecialchars($data['email']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>No HP</th>
                                            <td>: <?= htmlspecialchars($data['no_hp']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal</th>
                                            <td>: <?= date('d M Y, H:i', strtotime($data['tanggal_datetime'])); ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-info text-white">
                                    <i class="fas fa-truck me-1"></i> Info Pengiriman
                                </div>
                                <div class="card-body">
                                    <p><strong>Alamat Tujuan:</strong><br>
                                    <?= nl2br(htmlspecialchars($data['alamat_tujuan'] ?? '-')); ?>
                                    </p>
                                    <p><strong>Kurir:</strong> <?= htmlspecialchars($data['jasa_kirim'] ?? '-'); ?></p>
                                    <hr>
                                    <form method="POST">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Status Pembayaran</label>
                                            <select name="status" class="form-select">
                                                <option value="pending" <?= $data['status'] == 'pending' ? 'selected' : '' ?>>Pending (Menunggu)</option>
                                                <option value="paid" <?= $data['status'] == 'paid' ? 'selected' : '' ?>>Paid (Sudah Bayar)</option>
                                                <option value="cancelled" <?= $data['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled (Batal)</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Status Pengiriman</label>
                                            <select name="status_kirim" class="form-select">
                                                <option value="diproses" <?= ($data['status_kirim'] ?? '') == 'diproses' ? 'selected' : '' ?>>Diproses</option>
                                                <option value="dikirim" <?= ($data['status_kirim'] ?? '') == 'dikirim' ? 'selected' : '' ?>>Dikirim</option>
                                                <option value="diterima" <?= ($data['status_kirim'] ?? '') == 'diterima' ? 'selected' : '' ?>>Diterima</option>
                                                <option value="dibatalkan" <?= ($data['status_kirim'] ?? '') == 'dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">No. Resi</label>
                                            <input type="text" name="no_resi" class="form-control" placeholder="Input nomor resi disini" value="<?= htmlspecialchars($data['no_resi'] ?? ''); ?>">
                                        </div>

                                        <button type="submit" name="update_status" class="btn btn-success w-100">
                                            <i class="fas fa-save"></i> Simpan Perubahan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-box-open me-1"></i> Detail Item Pesanan
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Produk</th>
                                                <th class="text-center">Harga</th>
                                                <th class="text-center">Qty</th>
                                                <th class="text-end">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // 4. Ambil Data Item (Detail Transaksi JOIN Produk)
                                            $query_items = "SELECT d.*, p.nama_produk, p.gambar 
                                                            FROM transaksi_detail d
                                                            JOIN produk p ON d.produk_id = p.produk_id
                                                            WHERE d.transaksi_id = '$id'";
                                            $result_items = mysqli_query($conn, $query_items);
                                            $grand_total_check = 0;

                                            if (mysqli_num_rows($result_items) > 0) {
                                                while ($item = mysqli_fetch_assoc($result_items)) {
                                                    $grand_total_check += $item['subtotal'];
                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <?php if(!empty($item['gambar'])): ?>
                                                            <img src="../assets/img/<?= $item['gambar']; ?>" alt="img" width="50" class="me-2 rounded border">
                                                        <?php endif; ?>
                                                        <div>
                                                            <strong><?= htmlspecialchars($item['nama_produk']); ?></strong>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">Rp <?= number_format($item['harga_satuan'], 0, ',', '.'); ?></td>
                                                <td class="text-center"><?= $item['jumlah']; ?></td>
                                                <td class="text-end">Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?></td>
                                            </tr>
                                            <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='4' class='text-center'>Tidak ada item (Data error)</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <td colspan="3" class="text-end fw-bold">TOTAL BAYAR</td>
                                                <td class="text-end fw-bold fs-5">Rp <?= number_format($data['total_harga'], 0, ',', '.'); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-end fw-bold">Metode Pembayaran</td>
                                                <td class="text-end text-uppercase"><?= htmlspecialchars($data['metode_bayar'] ?? '-'); ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    
                                    <div class="mt-3">
                                        <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                                        <a href="cetak_invoice.php?id=<?= $id; ?>" target="_blank" class="btn btn-outline-dark float-end"><i class="fas fa-print"></i> Cetak Invoice</a>
                                    </div>
                                </div>
                            </div>
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