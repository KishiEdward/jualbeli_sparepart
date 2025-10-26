<?php
session_start();
include_once '../koneksi/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM kategori WHERE kategori_id = '$id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $_SESSION['success_delete'] = true;
    }
}

header('Location: index.php');
exit;
?>
