<?php
include_once '../koneksi/koneksi.php';
include_once '../template/auth_check.php';

// Ambil ID dari URL untuk menampilkan data lama
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$query_data = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$id'");
$data = mysqli_fetch_assoc($query_data);

if (!$data) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User - GearZone</title>
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
                <h1 class="mt-4">Edit User</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="index.php">User</a></li>
                    <li class="breadcrumb-item active">Edit User</li>
                </ol>

                <div class="card mb-4">
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" value="<?= $data['nama']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="<?= $data['email']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password Baru</label>
                                <input type="text" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengganti password">
                                <small class="text-muted">*Biarkan kosong jika password tetap sama.</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Role (Hak Akses)</label>
                                <select name="role" class="form-select" required>
                                    <option value="cust" <?= ($data['role'] == 'cust') ? 'selected' : ''; ?>>Customer</option>
                                    <option value="admin" <?= ($data['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                                    <option value="staf_gudang" <?= ($data['role'] == 'staf_gudang') ? 'selected' : ''; ?>>Staf Gudang</option>
                                    <option value="cs" <?= ($data['role'] == 'cs') ? 'selected' : ''; ?>>Customer Service</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">No. HP</label>
                                <input type="text" name="no_hp" class="form-control" value="<?= $data['no_hp']; ?>">
                            </div>
                            
                            <button type="submit" name="submit" class="btn btn-primary">Update</button>
                            <a href="index.php" class="btn btn-secondary">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="../js/scripts.js"></script>
</body>
</html>

<?php 
// Proses form update user
if (isset($_POST['submit'])) {
    
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $no_hp = $_POST['no_hp'];
    $password_baru = $_POST['password'];

    // Logika Update Password Tanpa Hash
    if (!empty($password_baru)) {
        // Jika password diisi, update langsung string-nya
        $query = "UPDATE users SET 
                    nama = '$nama', 
                    email = '$email', 
                    password = '$password_baru', 
                    role = '$role', 
                    no_hp = '$no_hp' 
                  WHERE user_id = '$id'";
    } else {
        // Jika password kosong, jangan update kolom password
        $query = "UPDATE users SET 
                    nama = '$nama', 
                    email = '$email', 
                    role = '$role', 
                    no_hp = '$no_hp' 
                  WHERE user_id = '$id'";
    }

    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "
        <script>
            Swal.fire({
                title: 'Berhasil!',
                text: 'Data user berhasil diperbarui!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'index.php';
            });
        </script>
        ";
    } else {
        echo "
        <script>
            Swal.fire({
                title: 'Gagal!',
                text: 'Terjadi kesalahan: " . mysqli_error($conn) . "',
                icon: 'error',
                confirmButtonText: 'Coba Lagi'
            });
        </script>
        ";
    }
}
?>