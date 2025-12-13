<?php
    include_once '../koneksi/koneksi.php';
    include_once '../template/auth_check.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Data Model Motor - GearZone</title>
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
                        <h1 class="mt-4">Data Model Motor</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Model Motor</li>
                        </ol>

                        <div class="card mb-4">
                            <div class="card-header">
                                <a href="tambah.php" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i> Tambah Model
                                </a>
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple" class="table table-striped table-bordered table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="40%">Nama Model</th>
                                            <th width="30%">Merek</th>
                                            <th width="15%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Query Data Model Motor
                                        $query = "SELECT * FROM model_motor ORDER BY model_id DESC";
                                        
                                        $result = mysqli_query($conn, $query);
                                        $no = 1;

                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<tr>";

                                                // 1. No
                                                echo "<td>" . $no++ . "</td>";

                                                // 2. Nama Model
                                                echo "<td class='fw-bold'>" . htmlspecialchars($row['nama_model']) . "</td>";

                                                // 3. Merek (Kita beri badge warna biar cantik)
                                                $badgeColor = ($row['merek'] == 'Honda') ? 'bg-danger' : (($row['merek'] == 'Yamaha') ? 'bg-primary' : (($row['merek'] == 'kawasaki') ? 'bg-success' : 'bg-secondary'));
                                                echo "<td><span class='badge $badgeColor'>" . htmlspecialchars($row['merek']) . "</span></td>";
                                                
                                                // 4. Aksi
                                                echo "<td class='text-center'>
                                                        <a href='edit.php?id=" . $row['model_id'] . "' class='btn btn-sm btn-warning text-white mx-1' title='Edit'><i class='fas fa-edit'></i></a>
                                                        <a href='#' class='btn btn-sm btn-danger delete-btn mx-1' data-id='" . $row['model_id'] . "' title='Hapus'><i class='fas fa-trash'></i></a>
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
                            title: 'Hapus Model?',
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