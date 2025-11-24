-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 23, 2025 at 01:37 PM
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
-- Database: `gearzone3`
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
(6, 'Aksesoris'),
(3, 'Body Parts'),
(4, 'Kaki'),
(2, 'Kelistrikan'),
(1, 'Mesin'),
(5, 'Oli');

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `keranjang_id` int NOT NULL,
  `user_id` int NOT NULL,
  `produk_id` int NOT NULL,
  `jumlah` int NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_motor`
--

CREATE TABLE `model_motor` (
  `model_id` int UNSIGNED NOT NULL,
  `nama_model` varchar(100) DEFAULT NULL,
  `merek` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `model_motor`
--

INSERT INTO `model_motor` (`model_id`, `nama_model`, `merek`) VALUES
(1, 'Supra X 125', 'Honda'),
(2, 'NMax 155', 'Yamaha'),
(3, 'Beat Street', 'Honda'),
(4, 'Vario 160', 'Honda'),
(5, 'Aerox 155', 'Yamaha'),
(6, 'Scoopy', 'Honda'),
(7, 'PCX 160', 'Honda'),
(8, 'Mio M3', 'Yamaha'),
(9, 'CRF150L', 'Honda'),
(10, 'WR155R', 'Yamaha');

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

--
-- Dumping data for table `pengiriman`
--

INSERT INTO `pengiriman` (`pengiriman_id`, `transaksi_id`, `alamat_tujuan`, `jasa_kirim`, `no_resi`, `status_kirim`) VALUES
(1, 1, 'Jl. Kenanga No. 45, Kecamatan Lowokwaru, Malang', 'JNE', 'JNE88291002', 'dikirim'),
(2, 2, 'Jl. Kenanga No. 45, Kecamatan Lowokwaru, Malang', 'J&T', '', 'dikirim'),
(3, 3, 'Jl. Kenanga No. 45, Kecamatan Lowokwaru, Malang', 'SiCepat', NULL, 'dibatalkan');

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
(1, 1, 'Piston Kit Supra X 125', 'Piston kit original untuk Supra X 125', '185000.00', 4, 'piston_supra.jpg'),
(2, 1, 'V-Belt NMax 155', 'V-Belt CVT original Yamaha NMax', '230000.00', 15, 'vbelt_nmax.jpg'),
(3, 2, 'Aki Motor Beat', 'Aki kering 24V untuk Honda Beat', '175000.00', 18, 'aki_beat.jpg'),
(4, 2, 'Koil Racing Aerox', 'Koil racing high performance Yamaha Aerox', '285000.00', 12, 'koil_aerox.jpg'),
(5, 3, 'Cover Body Vario 160', 'Cover body kanan kiri warna hitam doff', '350000.00', 10, 'cover_vario.jpg'),
(6, 3, 'Spakbor Depan Scoopy', 'Spakbor depan original Honda Scoopy', '140000.00', 25, 'spakbor_scoopy.jpg'),
(7, 4, 'Shockbreaker PCX 160', 'Shockbreaker belakang PCX premium quality', '750000.00', 8, 'shock_pcx.jpg'),
(8, 4, 'Velg Mio M3', 'Velg racing Mio M3 ukuran standar', '650000.00', 7, 'velg_mio.jpg'),
(9, 5, 'Oli MPX2 10W-30', 'Oli mesin MPX2 Honda 10W-30 0.8L', '48000.00', 50, 'oli_mpx2.jpg'),
(10, 5, 'Yamalube Sport', 'Oli mesin Yamalube khusus motor sport', '53000.00', 40, 'yamalube_sport.jpg'),
(11, 6, 'Handgrip Racing', 'Handgrip karet racing anti slip universal', '35000.00', 30, 'handgrip_racing.jpg'),
(12, 6, 'Spion Lipat NMax', 'Spion lipat model touring untuk Yamaha NMax', '85000.00', 20, '1763904846-images.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `produk_kompatibel`
--

CREATE TABLE `produk_kompatibel` (
  `id` int NOT NULL,
  `produk_id` int NOT NULL,
  `model_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `produk_kompatibel`
--

INSERT INTO `produk_kompatibel` (`id`, `produk_id`, `model_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(4, 4, 5),
(5, 5, 4),
(6, 6, 6),
(7, 7, 7),
(8, 8, 8),
(9, 9, 1),
(10, 10, 2),
(11, 11, 3),
(58, 12, 4),
(59, 12, 7),
(60, 12, 2),
(61, 12, 5);

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
  `tanggal_datetime` datetime NOT NULL,
  `total_harga` decimal(12,2) NOT NULL,
  `metode_bayar` varchar(50) DEFAULT NULL,
  `status` enum('pending','paid','cancelled') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`transaksi_id`, `user_id`, `tanggal_datetime`, `total_harga`, `metode_bayar`, `status`) VALUES
(1, 1, '2025-11-20 14:30:00', '315000.00', 'transfer', 'paid'),
(2, 1, '2025-11-22 22:07:38', '96000.00', 'cod', 'paid'),
(3, 1, '2025-11-19 09:00:00', '750000.00', 'transfer', 'cancelled');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_detail`
--

CREATE TABLE `transaksi_detail` (
  `detail_id` int NOT NULL,
  `transaksi_id` int NOT NULL,
  `produk_id` int NOT NULL,
  `jumlah` int NOT NULL,
  `harga_satuan` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi_detail`
--

INSERT INTO `transaksi_detail` (`detail_id`, `transaksi_id`, `produk_id`, `jumlah`, `harga_satuan`, `subtotal`) VALUES
(1, 1, 2, 1, '230000.00', '230000.00'),
(2, 1, 12, 1, '85000.00', '85000.00'),
(3, 2, 9, 2, '48000.00', '96000.00'),
(4, 3, 7, 1, '750000.00', '750000.00');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `foto_profil` varchar(255) DEFAULT 'default.jpg',
  `password` varchar(255) NOT NULL,
  `role` enum('admin','cust','staf_gudang','cs','marketing','kasir') NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `nama`, `email`, `foto_profil`, `password`, `role`, `no_hp`, `created_at`) VALUES
(1, 'Agra Alfian Hafiz', 'agrahafiz2@gmail.com', 'default.jpg', '131305', 'cust', '08987892216', '2025-11-15 10:26:46'),
(2, 'RadenZ', '1123150025@global.ac.id', 'default.jpg', '1123150025', 'admin', '0897636631', '2025-11-17 13:44:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `artikel`
--
ALTER TABLE `artikel`
  ADD PRIMARY KEY (`artikel_id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`kategori_id`),
  ADD UNIQUE KEY `nama_kategori` (`nama_kategori`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`keranjang_id`);

--
-- Indexes for table `model_motor`
--
ALTER TABLE `model_motor`
  ADD PRIMARY KEY (`model_id`),
  ADD UNIQUE KEY `model_id` (`model_id`),
  ADD UNIQUE KEY `nama_model` (`nama_model`);

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
-- Indexes for table `produk_kompatibel`
--
ALTER TABLE `produk_kompatibel`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `artikel`
--
ALTER TABLE `artikel`
  MODIFY `artikel_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `kategori_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `keranjang_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `model_motor`
--
ALTER TABLE `model_motor`
  MODIFY `model_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pengiriman`
--
ALTER TABLE `pengiriman`
  MODIFY `pengiriman_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `produk_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `produk_kompatibel`
--
ALTER TABLE `produk_kompatibel`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `promo`
--
ALTER TABLE `promo`
  MODIFY `promo_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `transaksi_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  MODIFY `detail_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ulasan`
--
ALTER TABLE `ulasan`
  MODIFY `ulasan_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
