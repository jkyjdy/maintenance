-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 06, 2022 at 04:46 PM
-- Server version: 10.5.13-MariaDB-cll-lve
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u7718053_restoku`
--

-- --------------------------------------------------------

--
-- Table structure for table `akun`
--

CREATE TABLE `akun` (
  `id` int(11) NOT NULL,
  `nama` varchar(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `akun`
--

INSERT INTO `akun` (`id`, `nama`, `username`, `password`, `role`) VALUES
(6, 'Anya Geraldine', 'kasir1', 'kasir1', 'Kasir'),
(7, 'Adi Apr, S.Kom', 'admin1', 'admin1', 'Admin'),
(8, 'Sri Widodo, M.Kom', 'manager', 'manager', 'Manager');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `kategori` varchar(40) NOT NULL,
  `harga` int(10) NOT NULL,
  `gambar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `nama`, `kategori`, `harga`, `gambar`) VALUES
(2, 'Paket Big Order', 'Makanan', 17500, '3.png'),
(3, 'Beef Prosperity Burger', 'Makanan', 16500, '100022.png'),
(4, 'Mini Cuts Spicy Chicken', 'Makanan', 20000, '4.png');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `pesanan` varchar(255) NOT NULL,
  `harga` int(20) NOT NULL,
  `diskon` int(20) NOT NULL,
  `totalbayar` int(20) NOT NULL,
  `kasir` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id`, `nama`, `pesanan`, `harga`, `diskon`, `totalbayar`, `kasir`) VALUES
(11, '', 'Paket Big Order , Beef Prosperity Burger', 53500, 10000, 43500, 'Anya Geraldine, S.Kom'),
(12, 'asa', 'Paket Big Order', 29500, 0, 29500, 'Anya Geraldine, S.Kom'),
(13, 'wsw', 'Paket Big Order', 47000, 0, 47000, 'Anya Geraldine, S.Kom'),
(14, 'sdsd', 'Paket Big Order', 29500, 0, 29500, 'Anya Geraldine, S.Kom'),
(15, 'Isna Mulyani', 'Paket Big Order', 53500, 10000, 43500, 'Anya Geraldine, S.Kom'),
(16, 'Anto marito', 'Mini Cuts Spicy Chicken , Beef Prosperity Burger', 76500, 10000, 66500, 'Anya Geraldine, S.Kom');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akun`
--
ALTER TABLE `akun`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `akun`
--
ALTER TABLE `akun`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
