-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 12, 2023 at 02:57 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mifo`
--

-- --------------------------------------------------------

--
-- Table structure for table `asteelu_user`
--

CREATE TABLE `asteelu_user` (
  `user_id` int(11) NOT NULL,
  `user_fullname` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_phone` varchar(50) DEFAULT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_country` varchar(255) DEFAULT NULL,
  `user_state` varchar(225) DEFAULT NULL,
  `user_city` varchar(225) DEFAULT NULL,
  `user_address` varchar(255) DEFAULT NULL,
  `user_address2` varchar(255) NOT NULL,
  `user_postcode` varchar(50) NOT NULL,
  `user_company` varchar(255) NOT NULL,
  `user_verified` tinyint(1) DEFAULT 0,
  `user_vericode` varchar(50) NOT NULL,
  `user_joined_date` datetime NOT NULL DEFAULT current_timestamp(),
  `user_last_login` datetime DEFAULT NULL,
  `user_trash` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `asteelu_user`
--

INSERT INTO `asteelu_user` (`user_id`, `user_fullname`, `user_email`, `user_phone`, `user_password`, `user_country`, `user_state`, `user_city`, `user_address`, `user_address2`, `user_postcode`, `user_company`, `user_verified`, `user_vericode`, `user_joined_date`, `user_last_login`, `user_trash`) VALUES
(1, 'tijani moro', 'tjhackx111@gmail.com', NULL, '$2y$10$ejbc/7Yd2CeOAvLKQlDUN.kDfz2scDSNm6pW7pQp18V2wCt.187Qm', NULL, NULL, NULL, NULL, '', '', '', 1, '61e69278933d90611304a7b4562d83e0', '2022-12-04 21:21:41', '2022-12-04 22:23:10', 0);

-- --------------------------------------------------------

--
-- Table structure for table `mifo_about`
--

CREATE TABLE `mifo_about` (
  `about_id` int(11) NOT NULL,
  `about_info` text NOT NULL,
  `about_street1` varchar(250) NOT NULL,
  `about_street2` varchar(250) NOT NULL,
  `about_country` varchar(250) NOT NULL,
  `about_state` varchar(250) NOT NULL,
  `about_city` varchar(250) NOT NULL,
  `about_phone` varchar(20) NOT NULL,
  `about_email` varchar(250) NOT NULL,
  `about_phone2` varchar(20) NOT NULL,
  `about_fax` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mifo_about`
--

INSERT INTO `mifo_about` (`about_id`, `about_info`, `about_street1`, `about_street2`, `about_country`, `about_state`, `about_city`, `about_phone`, `about_email`, `about_phone2`, `about_fax`) VALUES
(1, '                                        <p>mifo Lorem moro dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. tijani Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation <strong>ullamco </strong>laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. wsf</p>                                ', 'AF-0006-0091', 'FL 32904', 'ghana', 'ashanti', 'kumasi', '+1 233 553 477 150', 'info@mifostore.com', '+1 234 567 999', '+1 234 567 89');

-- --------------------------------------------------------

--
-- Table structure for table `mifo_admin`
--

CREATE TABLE `mifo_admin` (
  `admin_id` int(11) NOT NULL,
  `admin_fullname` varchar(255) NOT NULL,
  `admin_email` varchar(175) NOT NULL,
  `admin_password` varchar(255) NOT NULL,
  `admin_joined_date` datetime NOT NULL DEFAULT current_timestamp(),
  `admin_last_login` datetime DEFAULT NULL,
  `admin_permissions` varchar(255) NOT NULL,
  `admin_trash` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mifo_admin`
--

INSERT INTO `mifo_admin` (`admin_id`, `admin_fullname`, `admin_email`, `admin_password`, `admin_joined_date`, `admin_last_login`, `admin_permissions`, `admin_trash`) VALUES
(1, 'alhaji priest babson', 'admin@mifo.com', '$2y$10$YisQySADJR3ZPrlg.Tezt.1WxV/fqoMYSr902u6DN.Dah8xhpLnR2', '2020-02-21 21:01:31', '2023-04-11 16:33:07', 'admin,editor', 0);

-- --------------------------------------------------------

--
-- Table structure for table `mifo_brand`
--

CREATE TABLE `mifo_brand` (
  `brand_id` bigint(20) NOT NULL,
  `brand_name` varchar(255) DEFAULT NULL,
  `brand_banner` text DEFAULT NULL,
  `brand_url` varchar(1000) NOT NULL,
  `brand_added_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mifo_brand`
--

INSERT INTO `mifo_brand` (`brand_id`, `brand_name`, `brand_banner`, `brand_url`, `brand_added_date`) VALUES
(1, 'Ss20', 'shop/assets/media/collection/5dd205e817a6314b7ba4d8ffbf9b8974.jpg', 'ss20', '2023-04-09 05:09:25'),
(2, 'Pr3moato', 'shop/assets/media/collection/156cec3fe84bde0caf244144dad2e930.jpg', 'pr3moato', '2023-04-09 05:09:45'),
(3, 'BOLA CITIZNS', 'shop/assets/media/collection/18ddfdf01d4aaef8bd79db55cc634504.jpg', 'bola-citizns', '2023-04-09 05:10:04'),
(4, 'MIFO MONDAYS', 'shop/assets/media/collection/ce757573092ec950f1eaf7fb48a49015.jpg', 'mifo-mondays', '2023-04-09 05:10:27');

-- --------------------------------------------------------

--
-- Table structure for table `mifo_cart`
--

CREATE TABLE `mifo_cart` (
  `cart_id` int(11) NOT NULL,
  `order_number` varchar(100) NOT NULL,
  `items` text NOT NULL,
  `expire_date` datetime NOT NULL,
  `paid` tinyint(4) NOT NULL DEFAULT 0,
  `shipped` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mifo_cart`
--

INSERT INTO `mifo_cart` (`cart_id`, `order_number`, `items`, `expire_date`, `paid`, `shipped`) VALUES
(2, '1680-206272-2221', '[{\"user_id\":\"1\",\"id\":\"2\",\"size\":\"small\",\"quantity\":4,\"pays\":0},{\"user_id\":\"0\",\"id\":\"2\",\"size\":\"small\",\"quantity\":\"2\",\"pays\":0}]', '2023-04-29 22:00:20', 0, 0),
(8, '1681-190854-7271', '[{\"user_id\":\"1\",\"id\":\"2\",\"size\":\"small\",\"quantity\":\"2\",\"pays\":0},{\"user_id\":\"1\",\"id\":\"4\",\"size\":\"n/a\",\"quantity\":2,\"pays\":0}]', '2023-05-11 07:29:45', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `mifo_category`
--

CREATE TABLE `mifo_category` (
  `category_id` int(11) NOT NULL,
  `category` varchar(200) NOT NULL,
  `category_parent` int(11) NOT NULL,
  `category_added_date` datetime NOT NULL DEFAULT current_timestamp(),
  `category_url` varchar(1000) DEFAULT NULL,
  `category_trash` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mifo_category`
--

INSERT INTO `mifo_category` (`category_id`, `category`, `category_parent`, `category_added_date`, `category_url`, `category_trash`) VALUES
(1, 'menswear', 0, '2023-03-27 14:52:25', 'menswear', 0),
(2, 'womeanwear', 0, '2023-03-27 14:52:41', 'womeanwear', 0),
(3, 'tops', 1, '2023-03-27 14:52:55', 'tops', 0),
(4, 'tops', 2, '2023-03-27 14:53:03', 'tops', 0),
(5, 'downs', 1, '2023-03-27 14:53:12', 'downs', 0),
(6, 'downs', 2, '2023-03-27 14:53:20', 'downs', 0),
(7, 'pants', 2, '2023-03-27 15:16:31', 'pants', 0);

-- --------------------------------------------------------

--
-- Table structure for table `mifo_collection`
--

CREATE TABLE `mifo_collection` (
  `id` bigint(20) NOT NULL,
  `collection_name` varchar(500) DEFAULT NULL,
  `collection_image` text DEFAULT NULL,
  `collection_date_added` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mifo_contact`
--

CREATE TABLE `mifo_contact` (
  `contact_id` int(11) NOT NULL,
  `contact_firstname` varchar(255) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `contact_subject` varchar(500) NOT NULL,
  `contact_message` text NOT NULL,
  `contact_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mifo_contact`
--

INSERT INTO `mifo_contact` (`contact_id`, `contact_firstname`, `contact_email`, `contact_subject`, `contact_message`, `contact_date`) VALUES
(1, 'TIJANI MORO', 'tjhackx111@gmail.com', 'wossop', 'fwfw', '2023-04-12 05:14:19'),
(2, 'wknwk', 'lkmkl@fefe.com', 'fmkm', 'dfsdf', '2023-04-12 05:23:25'),
(3, 'fkejnkjn', 'knjfvn@fefed.com', 'kjnfj', 'kjdsnkfs', '2023-04-12 05:23:59'),
(4, 'jkwnfj', 'kjnvjkn@fr.com', 'jbwfjwn', 'kjndkjn', '2023-04-12 05:24:19');

-- --------------------------------------------------------

--
-- Table structure for table `mifo_faq`
--

CREATE TABLE `mifo_faq` (
  `id` int(11) NOT NULL,
  `faq_heading` varchar(300) NOT NULL,
  `faq_added_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mifo_faq`
--

INSERT INTO `mifo_faq` (`id`, `faq_heading`, `faq_added_date`) VALUES
(1, 'price', '2023-04-12'),
(2, 'cos', '2023-04-12');

-- --------------------------------------------------------

--
-- Table structure for table `mifo_faq_details`
--

CREATE TABLE `mifo_faq_details` (
  `id` bigint(20) NOT NULL,
  `faq_parent` int(11) NOT NULL,
  `faq_question` varchar(500) NOT NULL,
  `faq_answer` text NOT NULL,
  `faq_added_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mifo_faq_details`
--

INSERT INTO `mifo_faq_details` (`id`, `faq_parent`, `faq_question`, `faq_answer`, `faq_added_date`) VALUES
(1, 1, 'how much', 'nndss', '2023-04-12 05:32:13'),
(2, 2, 'dfsd', 'sdsvsdvsdvs', '2023-04-12 05:33:19'),
(3, 1, 'sdvsd', 'svsdvsvs', '2023-04-12 05:33:47');

-- --------------------------------------------------------

--
-- Table structure for table `mifo_product`
--

CREATE TABLE `mifo_product` (
  `product_id` bigint(20) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_category` int(11) DEFAULT NULL,
  `product_list_price` decimal(10,2) DEFAULT NULL,
  `product_price` decimal(10,2) DEFAULT NULL,
  `product_brand` int(11) NOT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `product_description` text DEFAULT NULL,
  `product_added_by` int(11) DEFAULT NULL,
  `product_added_date` datetime DEFAULT current_timestamp(),
  `product_featured` tinyint(4) NOT NULL DEFAULT 0,
  `product_sizes` text NOT NULL,
  `product_url` varchar(1000) DEFAULT NULL,
  `product_trash` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mifo_product`
--

INSERT INTO `mifo_product` (`product_id`, `product_name`, `product_category`, `product_list_price`, `product_price`, `product_brand`, `product_image`, `product_description`, `product_added_by`, `product_added_date`, `product_featured`, `product_sizes`, `product_url`, `product_trash`) VALUES
(2, 'again me', 3, '12.00', '13.20', 2, 'shop/media/uploaded-products/16d2af9116425f000562a5a5a25499f5.jpg', '<p>bla</p>', 1, '2023-03-27 15:25:46', 1, 'small:12:', 'again-me', 0),
(3, 'bye', 7, '0.00', '12.00', 1, 'shop/media/uploaded-products/8b33ce178db7d36b7a5d555fa24c11b5.jpg,shop/media/uploaded-products/6c015f8b1ce35bf8394dd09b8df73b75.jpg', 'bla', 1, '2023-03-27 15:39:24', 1, 'n/a:12:', 'bye', 0),
(4, 'one on one', 6, '0.00', '12.00', 1, 'shop/media/uploaded-products/794df58129f45027c422f38e4a6f6897.jpg,shop/media/uploaded-products/e87c5dafd4c6a48d10b1770f28f32f06.jpg,shop/media/uploaded-products/c02d19f3d3dc24c68756d5e52f43f999.jpg', '<p>just ne</p>', 1, '2023-03-27 15:55:08', 1, 'n/a:12:', 'one-on-one', 0),
(5, 'lkevm kem new', 4, '0.00', '34.00', 4, 'shop/media/uploaded-products/d55e11ba839f6dd3d0bd96615557649d.jpg,shop/media/uploaded-products/14e042d9356c3566765ca39c0976c43c.jpg', '<p>bla</p>', 1, '2023-03-31 01:23:39', 1, 'mv:12:', 'lkevm-kem-new', 0),
(6, 'my good', 3, '34.00', '33.00', 4, 'shop/media/uploaded-products/09d6c8a2b8f8c8e6b9901b7b24748a34.jpg,shop/media/uploaded-products/25627cb16f116655425df459bdc3e588.jpg', '<p>bla</p>', 1, '2023-04-04 04:51:55', 0, 'n/a:4:', 'my-good', 0),
(7, 'we de', 3, '0.02', '0.50', 2, 'shop/media/uploaded-products/569151874c55861e4e5db843838a3cb7.png,shop/media/uploaded-products/d8981227e9b7a9ccad4b558a864d3023.png', '<p>we</p>', 1, '2023-04-09 13:51:53', 0, 'n/a:2:', 'we-de', 0);

-- --------------------------------------------------------

--
-- Table structure for table `mifo_subscription`
--

CREATE TABLE `mifo_subscription` (
  `subscription_id` int(11) NOT NULL,
  `subscription_email` varchar(255) NOT NULL,
  `subscription_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mifo_subscription`
--

INSERT INTO `mifo_subscription` (`subscription_id`, `subscription_email`, `subscription_date`) VALUES
(1, 'tjhackx111@gmail.com', '2023-04-04 03:18:43');

-- --------------------------------------------------------

--
-- Table structure for table `mifo_transaction`
--

CREATE TABLE `mifo_transaction` (
  `transaction_id` int(11) NOT NULL,
  `transaction_charge_id` varchar(255) NOT NULL,
  `transaction_cart_id` int(11) NOT NULL,
  `transaction_user_id` int(11) NOT NULL,
  `transaction_full_name` varchar(255) NOT NULL,
  `transaction_email` varchar(255) NOT NULL,
  `transaction_street` varchar(255) NOT NULL,
  `transaction_street2` varchar(255) NOT NULL,
  `transaction_company` varchar(255) NOT NULL,
  `transaction_country` varchar(255) NOT NULL,
  `transaction_state` varchar(255) NOT NULL,
  `transaction_city` varchar(255) NOT NULL,
  `transaction_postcode` varchar(50) NOT NULL,
  `transaction_phone` varchar(20) NOT NULL,
  `transaction_sub_total` decimal(10,2) NOT NULL,
  `transaction_tax` decimal(10,2) NOT NULL,
  `transaction_grand_total` decimal(10,2) NOT NULL,
  `transaction_description` text NOT NULL,
  `transaction_order_note` text NOT NULL,
  `transaction_type` varchar(255) NOT NULL,
  `transaction_txn_date` datetime NOT NULL DEFAULT current_timestamp(),
  `transaction_shipped_product_date` datetime DEFAULT NULL,
  `transaction_status` tinyint(2) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mifo_user`
--

CREATE TABLE `mifo_user` (
  `user_id` int(11) NOT NULL,
  `user_fullname` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_phone` varchar(50) DEFAULT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_gender` varchar(200) NOT NULL,
  `user_country` varchar(255) DEFAULT NULL,
  `user_state` varchar(225) DEFAULT NULL,
  `user_city` varchar(225) DEFAULT NULL,
  `user_address` varchar(255) DEFAULT NULL,
  `user_address2` varchar(255) NOT NULL,
  `user_postcode` varchar(50) NOT NULL,
  `user_company` varchar(255) NOT NULL,
  `user_verified` tinyint(1) NOT NULL DEFAULT 0,
  `user_vericode` varchar(50) NOT NULL,
  `user_joined_date` datetime NOT NULL DEFAULT current_timestamp(),
  `user_last_login` datetime DEFAULT NULL,
  `user_trash` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mifo_user`
--

INSERT INTO `mifo_user` (`user_id`, `user_fullname`, `user_email`, `user_phone`, `user_password`, `user_gender`, `user_country`, `user_state`, `user_city`, `user_address`, `user_address2`, `user_postcode`, `user_company`, `user_verified`, `user_vericode`, `user_joined_date`, `user_last_login`, `user_trash`) VALUES
(1, 'tijani moro', 'tjhackx111@gmail.com', '4242', '$2y$10$pfdP.ODcKe7.1mRj8AXo0.4lyKGqRQJ86jvVIe4hr37Ma2GoldEYe', 'Male', 'Ghana', 'ashanti', 'Kumasi', 'af-0006-0091', '', '00233', '', 1, '61e69278933d90611304a7b4562d83e0', '2022-12-04 21:21:41', '2023-04-11 07:26:10', 0);

-- --------------------------------------------------------

--
-- Table structure for table `mifo_user_password_resets`
--

CREATE TABLE `mifo_user_password_resets` (
  `password_reset_id` int(11) NOT NULL,
  `password_reset_created_at` datetime DEFAULT NULL,
  `password_reset_user_id` int(11) NOT NULL,
  `password_reset_verify` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `asteelu_user`
--
ALTER TABLE `asteelu_user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_phone` (`user_phone`),
  ADD KEY `user_country` (`user_country`),
  ADD KEY `user_state` (`user_state`),
  ADD KEY `user_trash` (`user_trash`),
  ADD KEY `user_fullname` (`user_fullname`),
  ADD KEY `user_email` (`user_email`),
  ADD KEY `user_postcode` (`user_postcode`),
  ADD KEY `user_company` (`user_company`),
  ADD KEY `user_city` (`user_city`) USING BTREE;

--
-- Indexes for table `mifo_about`
--
ALTER TABLE `mifo_about`
  ADD PRIMARY KEY (`about_id`);

--
-- Indexes for table `mifo_admin`
--
ALTER TABLE `mifo_admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `mifo_brand`
--
ALTER TABLE `mifo_brand`
  ADD PRIMARY KEY (`brand_id`),
  ADD KEY `brand_name` (`brand_name`),
  ADD KEY `brand_added_date` (`brand_added_date`);

--
-- Indexes for table `mifo_cart`
--
ALTER TABLE `mifo_cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `product_id` (`items`(768)),
  ADD KEY `expire_date` (`expire_date`) USING BTREE,
  ADD KEY `paid` (`paid`) USING BTREE,
  ADD KEY `order_number` (`order_number`);

--
-- Indexes for table `mifo_category`
--
ALTER TABLE `mifo_category`
  ADD PRIMARY KEY (`category_id`),
  ADD KEY `category_name` (`category`),
  ADD KEY `category_added_date` (`category_added_date`),
  ADD KEY `category_url` (`category_url`(768));

--
-- Indexes for table `mifo_collection`
--
ALTER TABLE `mifo_collection`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mifo_contact`
--
ALTER TABLE `mifo_contact`
  ADD PRIMARY KEY (`contact_id`),
  ADD KEY `contact_firstname` (`contact_firstname`),
  ADD KEY `contact_email` (`contact_email`),
  ADD KEY `contact_subject` (`contact_subject`),
  ADD KEY `contact_date` (`contact_date`);

--
-- Indexes for table `mifo_faq`
--
ALTER TABLE `mifo_faq`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faq_heading` (`faq_heading`);

--
-- Indexes for table `mifo_faq_details`
--
ALTER TABLE `mifo_faq_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mifo_product`
--
ALTER TABLE `mifo_product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `product_name` (`product_name`),
  ADD KEY `product_category` (`product_category`),
  ADD KEY `product_price` (`product_price`),
  ADD KEY `product_added_date` (`product_added_date`),
  ADD KEY `product_added_by` (`product_added_by`),
  ADD KEY `product_list_price` (`product_list_price`),
  ADD KEY `product_url` (`product_url`(768)),
  ADD KEY `product_brand` (`product_brand`);

--
-- Indexes for table `mifo_subscription`
--
ALTER TABLE `mifo_subscription`
  ADD PRIMARY KEY (`subscription_id`);

--
-- Indexes for table `mifo_transaction`
--
ALTER TABLE `mifo_transaction`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `transaction_user_id` (`transaction_user_id`),
  ADD KEY `transaction_status` (`transaction_status`);

--
-- Indexes for table `mifo_user`
--
ALTER TABLE `mifo_user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_phone` (`user_phone`),
  ADD KEY `user_country` (`user_country`),
  ADD KEY `user_state` (`user_state`),
  ADD KEY `user_trash` (`user_trash`),
  ADD KEY `user_fullname` (`user_fullname`),
  ADD KEY `user_email` (`user_email`),
  ADD KEY `user_postcode` (`user_postcode`),
  ADD KEY `user_company` (`user_company`),
  ADD KEY `user_city` (`user_city`) USING BTREE,
  ADD KEY `user_gender` (`user_gender`);

--
-- Indexes for table `mifo_user_password_resets`
--
ALTER TABLE `mifo_user_password_resets`
  ADD PRIMARY KEY (`password_reset_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `asteelu_user`
--
ALTER TABLE `asteelu_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mifo_about`
--
ALTER TABLE `mifo_about`
  MODIFY `about_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mifo_admin`
--
ALTER TABLE `mifo_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `mifo_brand`
--
ALTER TABLE `mifo_brand`
  MODIFY `brand_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `mifo_cart`
--
ALTER TABLE `mifo_cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `mifo_category`
--
ALTER TABLE `mifo_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `mifo_collection`
--
ALTER TABLE `mifo_collection`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mifo_contact`
--
ALTER TABLE `mifo_contact`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `mifo_faq`
--
ALTER TABLE `mifo_faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mifo_faq_details`
--
ALTER TABLE `mifo_faq_details`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mifo_product`
--
ALTER TABLE `mifo_product`
  MODIFY `product_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `mifo_subscription`
--
ALTER TABLE `mifo_subscription`
  MODIFY `subscription_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mifo_transaction`
--
ALTER TABLE `mifo_transaction`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mifo_user`
--
ALTER TABLE `mifo_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mifo_user_password_resets`
--
ALTER TABLE `mifo_user_password_resets`
  MODIFY `password_reset_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
