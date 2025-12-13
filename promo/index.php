<?php
    // Sesuai screenshot: Mundur 1 langkah (../) lalu masuk folder koneksi & template
    include_once '../koneksi/koneksi.php';
    include_once '../template/auth_check.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Data Promo - GearZone</title>
        
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        
        <link href="../css/styles.css" rel="stylesheet" />
        
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
                        <h1 class="mt-4">Data Promo & Diskon</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Promo</li>
                        </ol>

                        <div class="card mb-4">
                            <div class="card-header">
                                <a href="tambah.php" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i> Tambah Promo Baru
                                </a>
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple" class="table table-striped table-bordered table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="20%">Kode / Nama Promo</th>
                                            <th width="15%">Potongan</th>
                                            <th width="25%">Periode Aktif</th>
                                            <th width="15%">Status</th>
                                            <th width="15%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Query Data Promo
                                        $query = "SELECT * FROM promo ORDER BY promo_id DESC";
                                        $result = mysqli_query($conn, $query);
                                        $no = 1;
                                        $today = date('Y-m-d');

                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                // Logika Status Otomatis (Aktif/Expired)
                                                $is_active = ($today >= $row['tanggal_mulai'] && $today <= $row['tanggal_selesai']);
                                                $status_badge = $is_active 
                                                    ? '<span class="badge bg-success">Aktif</span>' 
                                                    : '<span class="badge bg-secondary">Expired</span>';
                                                
                                                echo "<tr>";
                                                echo "<td>" . $no++ . "</td>";
                                                echo "<td class='fw-bold'>" . htmlspecialchars($row['nama_promo']) . "<br><small class='text-muted'>". htmlspecialchars($row['deskripsi']) ."</small></td>";
                                                echo "<td>Rp " . number_format($row['potongan'], 0, ',', '.') . "</td>";
                                                
                                                // Format Tanggal Indonesia
                                                $mulai = date('d M Y', strtotime($row['tanggal_mulai']));
                                                $selesai = date('d M Y', strtotime($row['tanggal_selesai']));
                                                echo "<td>$mulai <br> s/d <br> $selesai</td>";
                                                
                                                echo "<td class='text-center'>$status_badge</td>";
                                                
                                                echo "<td class='text-center'>
                                                        <a href='edit.php?id=" . $row['promo_id'] . "' class='btn btn-sm btn-warning text-white mx-1' title='Edit'><i class='fas fa-edit'></i></a>
                                                        <a href='#' class='btn btn-sm btn-danger delete-btn mx-1' data-id='" . $row['promo_id'] . "' title='Hapus'><i class='fas fa-trash'></i></a>
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
                            title: 'Hapus Promo?',
                            text: "Promo yang dihapus tidak bisa dikembalikan!",
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