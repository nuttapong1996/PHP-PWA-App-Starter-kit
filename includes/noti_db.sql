-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 22, 2025 at 10:57 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `noti_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `push_subscribers`
--

CREATE TABLE `push_subscribers` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `endpoint` varchar(255) DEFAULT NULL,
  `p256dh` varchar(255) DEFAULT NULL,
  `authKey` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `push_subscribers`
--

INSERT INTO `push_subscribers` (`id`, `username`, `endpoint`, `p256dh`, `authKey`) VALUES
(1, 'nomad', 'https://fcm.googleapis.com/fcm/send/caU7h9ZYzuI:APA91bHVar2i41ZhPKSfgfIT_vZvlULmK6Ou15MXasFHH93U_89Ay3Rm8DL_zwNW8t-DW-aO2bkaFCO5c3pnCSxXuXMslZ1XvLu4dWw7WKYc4QzXUn4Z8GIa0yjBEZtj87QuLq9oQcMl', 'BK6QeX46MB_qYTbqnzpPs3uvsmXphZaF3y2nNbrJAwM0KwSM0dx_Oo_SWS5uKroTtTAuCb8dbxK8N9T0cbaXMpk', 'yMqUMa6bInVtd2vGnj5xOw');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_login`
--

CREATE TABLE `tbl_login` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `tbl_login`
--

INSERT INTO `tbl_login` (`user_id`, `username`, `password`) VALUES
(1, 'nomad', '123456');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `push_subscribers`
--
ALTER TABLE `push_subscribers`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `tbl_login`
--
ALTER TABLE `tbl_login`
  ADD PRIMARY KEY (`user_id`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `push_subscribers`
--
ALTER TABLE `push_subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_login`
--
ALTER TABLE `tbl_login`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
