-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 26, 2025 at 02:32 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gearzone`
--

-- --------------------------------------------------------

--
-- Table structure for table `artikel`
--

CREATE TABLE `artikel` (
  `artikel_id` int NOT NULL,
  `user_id` int NOT NULL,
  `judul` varchar(150) NOT NULL,
  `isi` text NOT NULL,
  `tanggal_post` datetime NOT NULL,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kasir`
--

CREATE TABLE `kasir` (
  `kasir_id` int NOT NULL,
  `user_id` int NOT NULL,
  `shift` varchar(20) DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `kategori_id` int NOT NULL,
  `nama_kategori` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`kategori_id`, `nama_kategori`) VALUES
(4, 'Aki'),
(8, 'Ban'),
(1, 'busi'),
(5, 'Filter Udara'),
(3, 'Kampas Rem'),
(10, 'Knalpot'),
(7, 'Lampu'),
(2, 'Oli Mesin'),
(6, 'Rantai & Gear'),
(9, 'Spion');

-- --------------------------------------------------------

--
-- Table structure for table `pengiriman`
--

CREATE TABLE `pengiriman` (
  `pengiriman_id` int NOT NULL,
  `transaksi_id` int NOT NULL,
  `alamat_tujuan` text NOT NULL,
  `jasa_kirim` varchar(50) DEFAULT NULL,
  `no_resi` varchar(100) DEFAULT NULL,
  `status_kirim` enum('diproses','dikirim','diterima','dibatalkan') NOT NULL DEFAULT 'diproses'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `produk_id` int NOT NULL,
  `kategori_id` int NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `deskripsi` text,
  `harga` decimal(12,2) NOT NULL,
  `stok` int NOT NULL DEFAULT '0',
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`produk_id`, `kategori_id`, `nama_produk`, `deskripsi`, `harga`, `stok`, `gambar`) VALUES
(1, 1, 'NGK Iridium busi', 'busi iridium tahan lama 20k km', '50000.00', 200, 'joker.jpg'),
(3, 2, 'Shell Advance AX7', 'Oli mesin semi-sintetik untuk motor 4T.', '65000.00', 180, NULL),
(4, 2, 'Motul 7100 10W40', 'Oli premium dengan perlindungan maksimal.', '120000.00', 100, NULL),
(5, 3, 'Daytona Kampas Rem', 'Kampas rem berkualitas tinggi untuk pengereman lembut.', '70000.00', 90, NULL),
(6, 3, 'Aspira Disk Brake Pad', 'Kampas rem cakram standar OEM.', '55000.00', 120, NULL),
(7, 4, 'GS Astra MF', 'Aki bebas perawatan untuk motor bebek dan matic.', '160000.00', 80, NULL),
(8, 4, 'Yuasa YTX5L-BS', 'Aki kering dengan daya tahan tinggi.', '170000.00', 60, NULL),
(9, 5, 'Ferrox Filter Udara', 'Filter udara stainless untuk performa maksimal.', '250000.00', 40, NULL),
(10, 5, 'Sakura Air Filter', 'Filter udara pengganti OEM berkualitas.', '60000.00', 75, NULL),
(11, 6, 'TK Racing Gear Set', 'Gear set racing untuk peningkatan akselerasi.', '180000.00', 70, NULL),
(12, 6, 'SSS Rantai & Gear', 'Rantai kuat dan awet untuk pemakaian harian.', '210000.00', 90, NULL),
(13, 7, 'Osram LED H4', 'Lampu LED putih terang untuk visibilitas malam.', '90000.00', 110, NULL),
(14, 7, 'Philips X-treme Vision', 'Bohlam halogen dengan cahaya 130% lebih terang.', '85000.00', 100, NULL),
(15, 8, 'IRC NR77 Ban Depan', 'Ban tubeless dengan daya cengkeram tinggi.', '220000.00', 60, NULL),
(16, 8, 'FDR Blaze Ban Belakang', 'Ban sporty dengan grip kuat di berbagai kondisi.', '250000.00', 55, NULL),
(17, 9, 'Spion Rizoma Hitam', 'Spion gaya racing berbahan aluminium.', '120000.00', 90, NULL),
(18, 9, 'Spion Lipat Universal', 'Spion lipat praktis untuk motor harian.', '80000.00', 120, NULL),
(19, 10, 'WRX Knalpot Racing', 'Knalpot stainless untuk suara garang dan ringan.', '450000.00', 40, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `promo`
--

CREATE TABLE `promo` (
  `promo_id` int NOT NULL,
  `nama_promo` varchar(100) NOT NULL,
  `deskripsi` text,
  `potongan` decimal(10,2) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `transaksi_id` int NOT NULL,
  `user_id` int NOT NULL,
  `kasir_id` int DEFAULT NULL,
  `tanggal_datetime` datetime NOT NULL,
  `total_harga` decimal(12,2) NOT NULL,
  `metode_bayar` varchar(50) DEFAULT NULL,
  `status` enum('pending','paid','cancelled') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_detail`
--

CREATE TABLE `transaksi_detail` (
  `detail_id` int NOT NULL,
  `transaksi_id` int NOT NULL,
  `produk_id` int NOT NULL,
  `jumlah` int NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ulasan`
--

CREATE TABLE `ulasan` (
  `ulasan_id` int NOT NULL,
  `user_id` int NOT NULL,
  `produk_id` int NOT NULL,
  `rating` int DEFAULT NULL,
  `komentar` text,
  `tanggal` datetime NOT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','cust','staf_gudang','cs','marketing','kasir') NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `nama`, `email`, `password`, `role`, `no_hp`, `created_at`) VALUES
(1, 'jidan', 'jidan@gmail.com', 'jidan123', 'admin', '098898776615', '2025-10-20 12:53:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `artikel`
--
ALTER TABLE `artikel`
  ADD PRIMARY KEY (`artikel_id`);

--
-- Indexes for table `kasir`
--
ALTER TABLE `kasir`
  ADD PRIMARY KEY (`kasir_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`kategori_id`),
  ADD UNIQUE KEY `nama_kategori` (`nama_kategori`);

--
-- Indexes for table `pengiriman`
--
ALTER TABLE `pengiriman`
  ADD PRIMARY KEY (`pengiriman_id`),
  ADD UNIQUE KEY `transaksi_id` (`transaksi_id`),
  ADD UNIQUE KEY `no_resi` (`no_resi`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`produk_id`);

--
-- Indexes for table `promo`
--
ALTER TABLE `promo`
  ADD PRIMARY KEY (`promo_id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`transaksi_id`);

--
-- Indexes for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD PRIMARY KEY (`detail_id`);

--
-- Indexes for table `ulasan`
--
ALTER TABLE `ulasan`
  ADD PRIMARY KEY (`ulasan_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `kategori_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `produk_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
