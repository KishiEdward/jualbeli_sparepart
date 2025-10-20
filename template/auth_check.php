<?php
// Memulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Untuk cek apakah udah login
if (!isset($_SESSION['login'])) {
    header("Location: /gearzone/login.php");
    exit;
}

// Include koneksi database hanya sekali
require_once __DIR__ . '/../koneksi/koneksi.php';
?>
