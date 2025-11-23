<?php
    include_once '../koneksi/koneksi.php';
    include_once '../template/auth_check.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Data Produk - GearZone</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="../css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <style>
            /* Style agar deskripsi tidak membuat tabel terlalu lebar */
            .col-deskripsi {
                max-width: 250px; /* Batas lebar kolom deskripsi */
                font-size: 0.9em;
                color: #555;
            }
            .desc-short {
                display: block;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .badge-motor {
                font-size: 12px;
                margin-right: 2px;
                margin-bottom: 2px;
                display: inline-block;
                font-weight: normal;
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
                                <a href="tambah.php" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i> Tambah Produk
                                </a>
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple" class="table table-striped table-bordered table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th width="5%">No</th> <th width="10%">Gambar</th>
                                            <th width="15%">Nama Produk</th>
                                            <th width="10%">Kategori</th>
                                            <th width="15%">Motor (Model)</th>
                                            <th width="20%">Deskripsi</th> <th width="10%">Harga</th>
                                            <th width="5%">Stok</th>
                                            <th width="10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Query Lengkap dengan Many-to-Many
                                        $query = "SELECT p.*, k.nama_kategori, 
                                                  GROUP_CONCAT(m.nama_model SEPARATOR ', ') as motor_list
                                                  FROM produk p
                                                  JOIN kategori k ON p.kategori_id = k.kategori_id
                                                  LEFT JOIN produk_kompatibel pk ON p.produk_id = pk.produk_id
                                                  LEFT JOIN model_motor m ON pk.model_id = m.model_id
                                                  GROUP BY p.produk_id
                                                  ORDER BY p.produk_id DESC"; // Urutkan dari yang terbaru
                                        
                                        $result = mysqli_query($conn, $query);
                                        $no = 1; // Untuk penomoran 1, 2, 3...

                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<tr>";

                                                // 1. No
                                                echo "<td>" . $no++ . "</td>";

                                                // 2. Gambar
                                                if (!empty($row['gambar'])) {
                                                    echo "<td><img src='../assets/img/" . htmlspecialchars($row['gambar']) . "' width='60' height='60' class='rounded border shadow-sm'></td>";
                                                } else {
                                                    echo "<td><span class='badge bg-light text-dark border'>No Img</span></td>";
                                                }

                                                // 3. Nama Produk
                                                echo "<td class='fw-bold'>" . htmlspecialchars($row['nama_produk']) . "</td>";

                                                // 4. Kategori
                                                echo "<td>" . htmlspecialchars($row['nama_kategori']) . "</td>";
                                                
                                                // 5. Motor (Kompatibilitas)
                                                echo "<td>";
                                                if ($row['motor_list']) {
                                                    $motors = explode(', ', $row['motor_list']);
                                                    // Tampilkan maksimal 3 motor, sisanya "+2 more" agar tidak panjang
                                                    $count = 0;
                                                    foreach($motors as $motor) {
                                                        if($count < 3) {
                                                            echo "<span class='badge bg-info text-dark badge-motor'>$motor</span> ";
                                                        }
                                                        $count++;
                                                    }
                                                    if($count > 3) {
                                                        echo "<span class='badge bg-secondary badge-motor'>+" . ($count - 3) . " lainnya</span>";
                                                    }
                                                } else {
                                                    echo "<medium class='text-muted fst-italic'>Universal</medium>";
                                                }
                                                echo "</td>";

                                                // 6. Deskripsi (Dengan Tooltip biar rapi)
                                                // Logika: Potong teks jika lebih dari 50 karakter
                                                $deskripsi_pendek = strlen($row['deskripsi']) > 50 ? substr($row['deskripsi'], 0, 50) . "..." : $row['deskripsi'];
                                                echo "<td class='col-deskripsi' title='" . htmlspecialchars($row['deskripsi']) . "'>
                                                        " . htmlspecialchars($deskripsi_pendek) . "
                                                      </td>";

                                                // 7. Harga
                                                echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";

                                                // 8. Stok (Merah jika < 5)
                                                if ($row['stok'] < 5) {
                                                    echo "<td class='text-danger fw-bold text-center'>" . $row['stok'] . "</td>";
                                                } else {
                                                    echo "<td class='text-center'>" . $row['stok'] . "</td>";
                                                }
                                                
                                                // 9. Aksi
                                                echo "<td class='text-center'>
                                                        <a href='edit.php?id=" . $row['produk_id'] . "' class='btn btn-sm btn-warning text-white mb-1' title='Edit'><i class='fas fa-edit'></i></a>
                                                        <a href='#' class='btn btn-sm btn-danger delete-btn mb-1' data-id='" . $row['produk_id'] . "' title='Hapus'><i class='fas fa-trash'></i></a>
                                                      </td>";

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
                        <div class="text-muted small">Copyright &copy; GearZone 2025</div>
                    </div>
                </footer>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../js/scripts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
        <script src="../js/datatables-simple-demo.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const deleteButtons = document.querySelectorAll('.delete-btn');
                deleteButtons.forEach(button => {
                    button.addEventListener('click', function (e) {
                        e.preventDefault();
                        const id = this.getAttribute('data-id');
                        Swal.fire({
                            title: 'Hapus Produk?',
                            text: "Data tidak bisa dikembalikan!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'Ya, Hapus!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = `hapus.php?id=${id}`;
                            }
                        });
                    });
                });
            });
        </script>
    </body>
</html>