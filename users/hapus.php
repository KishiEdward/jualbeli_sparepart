<?php
include '../koneksi/koneksi.php';
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $query = "DELETE FROM users WHERE user_id = '$id'";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = 'User berhasil dihapus!';
    } else {
        $_SESSION['error'] = 'Gagal menghapus user';
    }
}

header("Location: index.php");
exit;
?>