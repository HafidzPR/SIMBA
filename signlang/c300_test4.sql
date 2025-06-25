-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2025 at 12:26 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `c300_test4`
--

-- --------------------------------------------------------

--
-- Table structure for table `konfigurasi`
--

CREATE TABLE `konfigurasi` (
  `id_konfigurasi` int(11) NOT NULL,
  `nama_konfigurasi` varchar(100) DEFAULT NULL,
  `nilai_konfigurasi` varchar(100) DEFAULT NULL,
  `id_admin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `konfigurasi`
--

INSERT INTO `konfigurasi` (`id_konfigurasi`, `nama_konfigurasi`, `nilai_konfigurasi`, `id_admin`) VALUES
(1, 'sensitivitas_kamera', 'tinggi', 1),
(2, 'deteksi_mode', 'otomatis', 1),
(3, 'notifikasi_email', 'true', 2),
(4, 'bahasa_default', 'en', 2);

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_admin`, `username`, `password`, `email`) VALUES
(1, 'faizalaaa', '$2y$10$7wjFjOGmA6MVBi82K5Poh./oYiGWXdk5rKboQuDjqJTX/XhYsCViO', 'faizalaaa@gmail.com'),
(2, 'admin_dua', '$2y$10$d8Xzti2Rk7YhmJcqToA1BeIucDYJCHRSADBhcuBRjumvjq3J1/Zk.', 'admin2@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_terjemahan`
--

CREATE TABLE `riwayat_terjemahan` (
  `id_riwayat` int(11) NOT NULL,
  `waktu_terjemahan` datetime NOT NULL,
  `hasil_terjemahan` text DEFAULT NULL,
  `id_admin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `riwayat_terjemahan`
--

INSERT INTO `riwayat_terjemahan` (`id_riwayat`, `waktu_terjemahan`, `hasil_terjemahan`, `id_admin`) VALUES
(1, '2025-05-25 14:01:21', '\"Selamat Malam\"', 1),
(2, '2025-05-25 19:02:21', '\"Ingin membeli minyak\"', 1),
(3, '0000-00-00 00:00:00', 'Good morning -> Selamat pagi', 2),
(4, '0000-00-00 00:00:00', 'Thank you -> Terima kasih', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `konfigurasi`
--
ALTER TABLE `konfigurasi`
  ADD PRIMARY KEY (`id_konfigurasi`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `riwayat_terjemahan`
--
ALTER TABLE `riwayat_terjemahan`
  ADD PRIMARY KEY (`id_riwayat`),
  ADD KEY `id_admin` (`id_admin`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `konfigurasi`
--
ALTER TABLE `konfigurasi`
  MODIFY `id_konfigurasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `riwayat_terjemahan`
--
ALTER TABLE `riwayat_terjemahan`
  MODIFY `id_riwayat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `konfigurasi`
--
ALTER TABLE `konfigurasi`
  ADD CONSTRAINT `konfigurasi_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `pengguna` (`id_admin`);

--
-- Constraints for table `riwayat_terjemahan`
--
ALTER TABLE `riwayat_terjemahan`
  ADD CONSTRAINT `riwayat_terjemahan_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `pengguna` (`id_admin`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
