-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2024 at 09:42 PM
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
-- Database: `student_complaint_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `admin_fullname` varchar(255) NOT NULL,
  `admin_email` varchar(175) NOT NULL,
  `admin_password` varchar(255) NOT NULL,
  `admin_joined_date` datetime NOT NULL DEFAULT current_timestamp(),
  `admin_last_login` datetime DEFAULT NULL,
  `admin_trash` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_fullname`, `admin_email`, `admin_password`, `admin_joined_date`, `admin_last_login`, `admin_trash`) VALUES
(1, 'alhaji priest babson', 'admin@email.com', '$2y$10$1H/AArTqlKyaYE8JYVLJV.mN.367tv5O3xEIavP1evN1LkppTfX0e', '2020-02-21 21:01:31', '2024-09-15 19:28:10', 0);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category`, `createdAt`, `updatedAt`) VALUES
(1, 'sc', '2024-06-24 22:28:52', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `complaint_id` varchar(200) DEFAULT NULL,
  `student_id` varchar(200) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `complaint_document` text DEFAULT NULL,
  `complaint_message` text NOT NULL,
  `complaint_comment` text DEFAULT NULL,
  `admin_comment` text NOT NULL,
  `complaint_date` date DEFAULT NULL,
  `complaint_status` tinyint(4) NOT NULL DEFAULT 0,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `trash` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `complaint_id`, `student_id`, `category_id`, `complaint_document`, `complaint_message`, `complaint_comment`, `admin_comment`, `complaint_date`, `complaint_status`, `createdAt`, `updatedAt`, `trash`) VALUES
(5, '668dc47c6da4a8.22130641', '1', 1, '', '<p>123</p>\r\n<p><img src=\"dist/media/complaint_media/1720567196.jpg\"></p>', 'this was not it', 'comment here.', '2024-07-09', 1, '2024-07-10 01:15:08', '2024-09-15 23:41:23', 0),
(7, '66e73765af2a51.90877481', '1', 1, '', '<p>hmmm</p>', NULL, '', '2024-09-26', 1, '2024-09-15 21:37:09', '2024-09-15 19:42:22', 0),
(8, '66e73a4b60f7e0.18512805', '1', 1, '', '<p>i wass ick</p>', 'i am not ok with the response', 'ok normal', '2024-09-10', 1, '2024-09-15 21:49:31', '2024-09-15 21:47:39', 0);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_id` varchar(200) DEFAULT NULL,
  `fullname` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `password` varchar(200) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `status` enum('student','admin') DEFAULT NULL,
  `trash` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_id`, `fullname`, `email`, `level`, `password`, `createdAt`, `updatedAt`, `status`, `trash`) VALUES
(1, '20210404221', 'abigirl asare', 'abigirl@email.com', 100, '$2y$10$wrGq5rfVGJJm5JniXt1gfuiGVeSAidLtiNt.LHQpJzrv39u3o32GC', '2024-06-24 19:22:53', '2024-09-15 23:50:18', NULL, 0),
(2, '1', 'alhaji priest babson', 'admin@email.com', NULL, '$2y$10$u92eFB8Vd1qxtxfpfiJbAeqCCFUXdOO84Ik6rE/CKRN6RRNqAAcIm', '2020-02-21 21:01:31', '2024-09-15 20:15:50', 'admin', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
