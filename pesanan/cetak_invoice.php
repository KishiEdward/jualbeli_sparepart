<?php
include_once '../koneksi/koneksi.php';

// Cek ID
if (!isset($_GET['id'])) {
    echo "Data tidak ditemukan";
    exit;
}
$id = $_GET['id'];

// Ambil Data Header (Transaksi, User, Pengiriman, Promo)
// Note: Ditambahkan LEFT JOIN promo untuk mengambil nama_promo
$query_header = "SELECT t.*, u.nama, u.no_hp, p.alamat_tujuan, p.jasa_kirim, p.no_resi, pr.nama_promo 
                 FROM transaksi t
                 JOIN users u ON t.user_id = u.user_id
                 LEFT JOIN pengiriman p ON t.transaksi_id = p.transaksi_id
                 LEFT JOIN promo pr ON t.promo_id = pr.promo_id
                 WHERE t.transaksi_id = '$id'";
$result_header = mysqli_query($conn, $query_header);
$header = mysqli_fetch_assoc($result_header);

// Ambil Data Barang
$query_items = "SELECT d.*, p.nama_produk 
                FROM transaksi_detail d
                JOIN produk p ON d.produk_id = p.produk_id
                WHERE d.transaksi_id = '$id'";
$items = mysqli_query($conn, $query_items);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #INV-<?= $id ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 10px;
        }
        .header-logo {
            font-size: 24px;
            font-weight: bold;
            color: #ee4d2d;
            display: flex;
            align-items: center;
        }
        .header-title {
            font-size: 18px;
            font-weight: bold;
        }
        
        .address-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .col-box {
            width: 48%;
        }
        .info-label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .info-text {
            line-height: 1.4;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .meta-table th, .meta-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .meta-table th {
            background-color: #f9f9f9;
            font-size: 11px;
            text-transform: uppercase;
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .product-table th {
            border-top: 1px solid #333;
            border-bottom: 1px solid #333;
            padding: 8px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }
        .product-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }

        /* Bagian Total (Bawah Kanan) */
        .total-section {
            width: 45%; /* Diperlebar sedikit agar muat teks diskon */
            margin-left: auto;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .total-final {
            border-top: 1px dashed #333;
            padding-top: 10px;
            font-size: 14px;
            font-weight: bold;
        }
        .text-success { color: #198754; } /* Warna hijau untuk diskon */

        .footer-note {
            margin-top: 30px;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 10px;
            text-align: center;
        }

        @media print {
            @page { margin: 0.5cm; }
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body onload="window.print()">

<div class="container">
    <div class="header">
        <div class="header-logo">
            <span style="margin-right:5px;"></span>GearZone
        </div>
        <div class="header-title">NOTA PESANAN</div>
    </div>

    <div class="address-section">
        <div class="col-box">
            <div class="info-label">Penerima (Pembeli):</div>
            <div class="info-text">
                <span class="bold"><?= htmlspecialchars($header['nama']) ?></span><br>
                <?= htmlspecialchars($header['no_hp']) ?><br>
                <div style="margin-top: 4px; color: #555;">
                    <?= nl2br(htmlspecialchars($header['alamat_tujuan'] ?? '-')) ?>
                </div>
            </div>
        </div>

        <div class="col-box text-right">
            <div class="info-label">Pengirim (Penjual):</div>
            <div class="info-text">
                <span class="bold">GearZone Official Store</span><br>
                Jl. Raya Otomotif No. 1, Jakarta<br>
                0812-3456-7890
            </div>
        </div>
    </div>

    <table class="meta-table">
        <thead>
            <tr>
                <th>No. Pesanan</th>
                <th>Tanggal Pesanan</th>
                <th>Metode Pembayaran</th>
                <th>Jasa Kirim</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="bold">#INV-<?= $header['transaksi_id'] ?></td>
                <td><?= date('d/m/Y H:i', strtotime($header['tanggal_datetime'])) ?></td>
                <td style="text-transform: uppercase;"><?= $header['metode_bayar'] ?></td>
                <td style="text-transform: uppercase;">
                    <?= $header['jasa_kirim'] ?? '-' ?> 
                    <?= !empty($header['no_resi']) ? "($header[no_resi])" : "" ?>
                </td>
            </tr>
        </tbody>
    </table>

    <div style="margin-bottom: 10px; font-weight: bold; font-size: 14px;">Rincian Pesanan</div>

    <table class="product-table">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="50%">Produk</th>
                <th width="15%" class="text-right">Harga Satuan</th>
                <th width="10%" class="text-center">Qty</th>
                <th width="20%" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $subtotal_produk = 0;
            while($row = mysqli_fetch_assoc($items)): 
                $subtotal_produk += $row['subtotal'];
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td>
                    <span class="bold"><?= htmlspecialchars($row['nama_produk']) ?></span>
                </td>
                <td class="text-right">Rp <?= number_format($row['harga_satuan'], 0, ',', '.') ?></td>
                <td class="text-center"><?= $row['jumlah'] ?></td>
                <td class="text-right">Rp <?= number_format($row['subtotal'], 0, ',', '.') ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <?php 
        $potongan = $header['potongan'];
        $total_bayar = $header['total_harga'];
        
        // Menghitung Ongkir (Reverse Engineering karena ada diskon)
        // Rumus: Total Bayar = (Subtotal + Ongkir) - Diskon
        // Maka : Ongkir = Total Bayar - Subtotal + Diskon
        $ongkir = $total_bayar - $subtotal_produk + $potongan;
        
        // Cegah ongkir minus (jika ada kesalahan data)
        if($ongkir < 0) $ongkir = 0;
    ?>

    <div class="total-section">
        <div class="total-row">
            <span>Subtotal Produk</span>
            <span>Rp <?= number_format($subtotal_produk, 0, ',', '.') ?></span>
        </div>
        
        <?php if($ongkir > 0): ?>
        <div class="total-row">
            <span>Total Ongkos Kirim</span>
            <span>Rp <?= number_format($ongkir, 0, ',', '.') ?></span>
        </div>
        <?php endif; ?>

        <?php if($potongan > 0): ?>
        <div class="total-row text-success">
            <span>Diskon (<?= htmlspecialchars($header['nama_promo'] ?? 'Promo') ?>)</span>
            <span>- Rp <?= number_format($potongan, 0, ',', '.') ?></span>
        </div>
        <?php endif; ?>

        <div class="total-row total-final">
            <span>Total Pembayaran</span>
            <span style="color: #ee4d2d;">Rp <?= number_format($total_bayar, 0, ',', '.') ?></span>
        </div>
    </div>

    <div class="footer-note">
        Terima kasih telah berbelanja di GearZone. <br>
        Invoice ini sah dan diproses oleh komputer.
    </div>
</div>

</body>
</html>