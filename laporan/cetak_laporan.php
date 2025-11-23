<?php
include_once '../koneksi/koneksi.php';

// Ambil tanggal dari URL
$tgl_mulai   = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : date('Y-m-01');
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan Periode <?= $tgl_mulai ?> - <?= $tgl_selesai ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
            color: #333;
        }
        .header h3 {
            margin: 5px 0;
            font-weight: normal;
            font-size: 14px;
        }
        .header hr {
            border: 0;
            border-bottom: 2px solid #333;
            margin-top: 15px;
        }
        .periode {
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        /* Table Style */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #333;
        }
        th {
            background-color: #f2f2f2;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }
        td {
            padding: 8px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        
        /* Total Row Style */
        .grand-total {
            background-color: #e8e8e8;
            font-size: 14px;
        }

        /* Signature Section */
        .signature {
            margin-top: 50px;
            text-align: right;
            margin-right: 50px;
        }
        .signature div {
            margin-bottom: 80px;
        }

        /* Print Settings */
        @media print {
            @page { margin: 1cm; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h1>GearZone</h1>
        <h3>Jl. Raya Otomotif No. 1, Jakarta | Telp: 0812-3456-7890</h3>
        <hr>
        <h2 style="margin-top: 20px;">LAPORAN PENJUALAN</h2>
    </div>

    <div class="periode">
        <strong>Periode Laporan:</strong> 
        <?= date('d F Y', strtotime($tgl_mulai)); ?> s/d <?= date('d F Y', strtotime($tgl_selesai)); ?>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="20%">No Invoice</th>
                <th width="35%">Pelanggan</th>
                <th width="25%">Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Query yang sama persis dengan index, hanya status 'paid'
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
                <td class="text-center"><?= $no++; ?></td>
                <td class="text-center"><?= date('d/m/Y', strtotime($row['tanggal_datetime'])); ?></td>
                <td class="text-center">#INV-<?= $row['transaksi_id']; ?></td>
                <td><?= htmlspecialchars($row['nama']); ?></td>
                <td class="text-right">Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
            </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>Tidak ada data penjualan pada periode ini.</td></tr>";
            }
            ?>
        </tbody>
        <tfoot>
            <tr class="grand-total">
                <td colspan="4" class="text-right text-bold">GRAND TOTAL PENDAPATAN</td>
                <td class="text-right text-bold">Rp <?= number_format($grand_total, 0, ',', '.'); ?></td>
            </tr>
        </tfoot>
    </table>

    <div class="signature">
        <div>Jakarta, <?= date('d F Y'); ?></div>
        <br><br>
        <strong>( Admin GearZone )</strong>
    </div>

</body>
</html>