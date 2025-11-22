<?php
    include_once '../koneksi/koneksi.php';
    include_once '../template/auth_check.php';
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Data User - GearZone</title>
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
                        <h1 class="mt-4">Data User</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">User</li>
                        </ol>

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-users me-1"></i>
                                Data Pengguna
                            </div>
                            <div class="card-body">
                                <a href="tambah.php" class="btn btn-success mb-3">
                                    <i class="fas fa-plus"></i> Tambah User
                                </a>
                                <table id="datatablesSimple" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>No HP</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>No HP</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                            // Query ambil data users
                                            $query = "SELECT * FROM users ORDER BY user_id DESC";
                                            $result = mysqli_query($conn, $query);
                                            $no = 1;

                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $no++ . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                                    
                                                    // Badge warna untuk Role biar cantik
                                                    $badgeColor = ($row['role'] == 'admin') ? 'bg-danger' : 'bg-primary';
                                                    echo "<td><span class='badge $badgeColor'>" . ucfirst($row['role']) . "</span></td>";
                                                    
                                                    echo "<td>" . htmlspecialchars($row['no_hp']) . "</td>";
                                                    
                                                    echo "<td>
                                                            <a href='edit.php?id=" . $row['user_id'] . "' class='btn btn-sm btn-warning text-white'><i class='fas fa-edit'></i> Edit</a>
                                                            <a href='#' class='btn btn-sm btn-danger delete-btn' data-id='" . $row['user_id'] . "'><i class='fas fa-trash'></i> Hapus</a>
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
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; GearZone 2025</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="../js/scripts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="../js/datatables-simple-demo.js"></script>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            // SweetAlert untuk Hapus
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const id = this.getAttribute('data-id');
                    Swal.fire({
                        title: 'Hapus User ini?',
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

            // SweetAlert untuk Sukses dari PHP Session
            <?php if(isset($_SESSION['success'])): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '<?= $_SESSION['success']; ?>',
                    timer: 2000,
                    showConfirmButton: false
                });
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
        });
        </script>
    </body>
</html>