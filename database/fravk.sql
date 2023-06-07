-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2022 at 05:46 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fravk`
--

-- --------------------------------------------------------

--
-- Table structure for table `t_like`
--

CREATE TABLE `t_like` (
  `id_like` int(11) NOT NULL,
  `id_rating` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `t_rating`
--

CREATE TABLE `t_rating` (
  `id_rating` int(11) NOT NULL,
  `id_web` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `rating` float NOT NULL,
  `review` varchar(255) DEFAULT NULL,
  `likes_count` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_rating`
--

INSERT INTO `t_rating` (`id_rating`, `id_web`, `id_user`, `rating`, `review`, `likes_count`, `created_at`) VALUES
(22, 2, 4, 1, 'website jelek, sering lemot pas ujian/kuis, berkali-kali masuk top 3 tapi medali ga nambah', 0, '2022-06-09 15:38:52'),
(23, 2, 5, 5, 'pengiriman cepat, kurir ramah, barang sesuai gambar, nanti order lagi 5/5', 0, '2022-06-09 15:38:52'),
(24, 2, 3, 3, '', 0, '2022-06-09 15:38:52'),
(25, 2, 8, 1, 'wirgadium leviosa', 0, '2022-06-09 15:38:52'),
(26, 1, 4, 5, 'aku kangen doi', 0, '2022-06-09 15:38:52'),
(28, 3, 5, 5, '', 0, '2022-06-09 15:38:52');

--
-- Triggers `t_rating`
--
DELIMITER $$
CREATE TRIGGER `add_r_count` AFTER INSERT ON `t_rating` FOR EACH ROW BEGIN
	UPDATE t_website SET
    t_website.r_count = t_website.r_count + 1
    WHERE t_website.id_web = NEW.id_web;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sub_r_count` AFTER DELETE ON `t_rating` FOR EACH ROW BEGIN
	UPDATE t_website SET
    t_website.r_count = t_website.r_count - 1
    WHERE t_website.id_web = OLD.id_web;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `t_request`
--

CREATE TABLE `t_request` (
  `id_req` int(11) NOT NULL,
  `domain_req` varchar(100) NOT NULL,
  `nama_req` varchar(100) NOT NULL,
  `deskripsi_req` varchar(255) DEFAULT NULL,
  `kategori_req` varchar(25) DEFAULT NULL,
  `logo_req` varchar(100) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `status_req` varchar(25) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_request`
--

INSERT INTO `t_request` (`id_req`, `domain_req`, `nama_req`, `deskripsi_req`, `kategori_req`, `logo_req`, `id_user`, `status_req`, `created_at`) VALUES
(5, 'cesatu.com', 'Cesatu Class', 'Website Kelas C1', '', 'default.png', 4, 'accepted', '2022-06-09 15:39:26'),
(6, 'cspc.cs.upi.edu', 'CSPC', '', 'Education', 'Logo_Almamater_UPI.svg', 4, 'accepted', '2022-06-09 15:39:26'),
(7, 'kemakom.org', 'Kemakom', 'Website BEM Kemakom', 'Organization', 'logo-kemakom.png', 8, 'pending', '2022-06-09 15:39:26'),
(8, 'bintang.cesatu.com', 'Bintang Fajar', 'Website yang berisikan karya seorang pemuda', 'Pribadi', 'logo_bintang.jpg', 9, 'accepted', '2022-06-09 15:39:26'),
(9, 'domainwebsite.com', 'WebsiteKU', 'Ini adalah deskripsi WebsiteKU.', 'Pribadi', 'default.png', 4, 'rejected', '2022-06-09 15:39:26'),
(10, 'doctorstrange.com', 'Website Fandom Doctor Strange', 'Doctor Strange Bucin di film MOM', 'Horror', 'default.png', 8, 'rejected', '2022-06-09 15:39:26'),
(12, 'doctorstrange1.com', 'Website Fandom Doctor Strange', '', 'Horror', 'bintang.png', 8, 'rejected', '2022-06-09 15:39:26'),
(13, 'doctorstrange2.com', 'Website Fandom Doctor Strange', 'Doctor Strange adalah sebuah karakter bucin.', '', 'bintang.png', 8, 'pending', '2022-06-09 15:39:26');

-- --------------------------------------------------------

--
-- Table structure for table `t_user`
--

CREATE TABLE `t_user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_user` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `deskripsi_user` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `no_telepon` varchar(20) DEFAULT NULL,
  `profile_pic` varchar(100) DEFAULT 'default.jpg',
  `jenis_user` char(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_user`
--

INSERT INTO `t_user` (`id_user`, `username`, `password_user`, `nama_lengkap`, `deskripsi_user`, `email`, `alamat`, `tanggal_lahir`, `gender`, `no_telepon`, `profile_pic`, `jenis_user`, `created_at`) VALUES
(3, 'admin', '$2y$10$9BrE8dVEybuiJWtXX8.jveJZHH5apndeNw7Oah2IaFurC3y4AvPP.', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, 'default.jpg', 'A', '2022-05-24 11:34:24'),
(4, 'fauzan', '$2y$10$USCQUpKYudlHAxPnHMtPxOm2YAbZeBcPZ7uSyW/X.qn5y0FdIAjNq', 'Fauzan Doang', NULL, NULL, NULL, NULL, NULL, NULL, 'bebek.jpg', 'D', '2022-05-24 14:20:11'),
(5, 'rayhan', '$2y$10$g5inn6GbYv8OaVTMXYXCeOej.gP01o1V6vsRNH6QSxUHEI4RNsa5K', 'Muhammad Rayhan Nur', 'Aku seorang kapiten.', 'm.rayhan.nur@upi.edu', 'Jl. Gegerkalong Girang', '2003-05-15', 'L', '081234567896', 'default.jpg', 'R', '2022-05-24 14:21:12'),
(8, 'doctor_strange', '$2y$10$PSHzJqFGPwt6ygrbBaNsZuSkwM6F7Vk.1uslWrJk9B7LKcr4TvBl2', 'Doctor Stephen Strange', NULL, NULL, NULL, NULL, NULL, NULL, 'doctor-strange-1_169.jpeg', 'D', '2022-05-26 11:23:16'),
(9, 'bintang', '$2y$10$8YxTNZ1KU13ygH1z5EWdGum8nTwikF8GqHsR6/rjkOR2rzibL6RHG', 'bintang', NULL, NULL, NULL, NULL, NULL, NULL, 'bintang.png', 'D', '2022-05-27 03:37:53'),
(10, 'marimo', '$2y$10$/uGbsGEoMC3e4QXeYooyvuKIJ0iF9yoqbyefyjjl9J2r5ZSW0MuOq', 'Marimo Banget', NULL, NULL, NULL, NULL, NULL, NULL, 'bintang.png', '', '2022-05-27 09:47:50'),
(11, 'akunbaru', '$2y$10$8gb5vEwZLhnJ/5O5X362K.hM5pi/QcbOaXpO0sgsAtPF8A.qjOXDm', 'Akun Baru Loh', NULL, NULL, NULL, NULL, NULL, NULL, 'HASIL AKHIR20.png', 'D', '2022-05-27 09:50:34'),
(12, 'test_daftar', '$2y$10$6EtXJ1hHqAg0w1JDGDJQ.euXldqlPT30YGCpUTCRD74L24OemlaR6', 'Test Daftar', NULL, NULL, NULL, NULL, NULL, NULL, 'default.jpg', 'R', '2022-05-30 07:24:32');

-- --------------------------------------------------------

--
-- Table structure for table `t_website`
--

CREATE TABLE `t_website` (
  `id_web` int(11) NOT NULL,
  `domain_web` varchar(100) NOT NULL,
  `rating_web` float DEFAULT 0,
  `nama_web` varchar(100) NOT NULL,
  `deskripsi_web` varchar(255) DEFAULT NULL,
  `kategori_web` varchar(25) DEFAULT NULL,
  `logo_web` varchar(100) DEFAULT 'default.png',
  `id_user` int(11) DEFAULT NULL,
  `r_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_website`
--

INSERT INTO `t_website` (`id_web`, `domain_web`, `rating_web`, `nama_web`, `deskripsi_web`, `kategori_web`, `logo_web`, `id_user`, `r_count`) VALUES
(1, 'cesatu.com', 5, 'Cesatu Class', 'Website Kelas C1', '', 'default.png', 4, 1),
(2, 'cspc.cs.upi.edu', 2.5, 'CSPC', 'Website Menglieur Alpro', 'Education', 'Logo_Almamater_UPI.svg', 4, 4),
(3, 'bintang.cesatu.com', 5, 'Bintang Fajar', 'Website yang berisikan karya seorang pemuda', 'Pribadi', 'logo_bintang.jpg', 9, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `t_like`
--
ALTER TABLE `t_like`
  ADD PRIMARY KEY (`id_like`),
  ADD KEY `id_rating` (`id_rating`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `t_rating`
--
ALTER TABLE `t_rating`
  ADD PRIMARY KEY (`id_rating`),
  ADD KEY `id_web` (`id_web`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `t_request`
--
ALTER TABLE `t_request`
  ADD PRIMARY KEY (`id_req`),
  ADD UNIQUE KEY `domain_req` (`domain_req`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `t_user`
--
ALTER TABLE `t_user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `t_website`
--
ALTER TABLE `t_website`
  ADD PRIMARY KEY (`id_web`),
  ADD UNIQUE KEY `domain_web` (`domain_web`),
  ADD KEY `id_user` (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t_like`
--
ALTER TABLE `t_like`
  MODIFY `id_like` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_rating`
--
ALTER TABLE `t_rating`
  MODIFY `id_rating` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `t_request`
--
ALTER TABLE `t_request`
  MODIFY `id_req` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `t_user`
--
ALTER TABLE `t_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `t_website`
--
ALTER TABLE `t_website`
  MODIFY `id_web` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `t_like`
--
ALTER TABLE `t_like`
  ADD CONSTRAINT `t_like_ibfk_1` FOREIGN KEY (`id_rating`) REFERENCES `t_rating` (`id_rating`),
  ADD CONSTRAINT `t_like_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `t_user` (`id_user`);

--
-- Constraints for table `t_rating`
--
ALTER TABLE `t_rating`
  ADD CONSTRAINT `t_rating_ibfk_1` FOREIGN KEY (`id_web`) REFERENCES `t_website` (`id_web`),
  ADD CONSTRAINT `t_rating_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `t_user` (`id_user`);

--
-- Constraints for table `t_request`
--
ALTER TABLE `t_request`
  ADD CONSTRAINT `t_request_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `t_user` (`id_user`);

--
-- Constraints for table `t_website`
--
ALTER TABLE `t_website`
  ADD CONSTRAINT `t_website_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `t_user` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
