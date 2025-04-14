-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 08, 2025 at 06:27 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `food_ordering`
--

-- --------------------------------------------------------

--
-- Table structure for table `addon_options`
--

CREATE TABLE `addon_options` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `type` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addon_options`
--

INSERT INTO `addon_options` (`id`, `name`, `price`, `type`, `created_at`) VALUES
(1, 'เส้นเล็ก', 0.00, 'noodle', '2025-04-03 17:46:41'),
(2, 'เส้นใหญ่', 0.00, 'noodle', '2025-04-03 17:46:41'),
(3, 'หมี่ขาว', 0.00, 'noodle', '2025-04-03 17:46:41'),
(4, 'หมี่เหลือง', 0.00, 'noodle', '2025-04-03 17:46:41'),
(5, 'บะหมี่', 0.00, 'noodle', '2025-04-03 17:46:41'),
(6, 'วุ้นเส้น', 0.00, 'noodle', '2025-04-03 17:46:41'),
(7, 'น้ำ', 0.00, 'soup', '2025-04-03 17:46:41'),
(8, 'แห้ง', 0.00, 'soup', '2025-04-03 17:46:41'),
(9, 'หมูสไลด์ 4 ชิ้น', 15.00, 'topping', '2025-04-03 17:46:41'),
(10, 'เนื้อตุ๋น 3 ชิ้น', 15.00, 'topping', '2025-04-03 17:46:41'),
(11, 'เนื้อสไลด์ 4 ชิ้น', 15.00, 'topping', '2025-04-03 17:46:41'),
(12, 'หมูตุ๋น 3 ชิ้น', 15.00, 'topping', '2025-04-03 17:46:41'),
(13, 'ลูกชิ้น 3 ลูก', 15.00, 'topping', '2025-04-03 17:46:41'),
(14, 'ตับ 4 ชิ้น', 15.00, 'topping', '2025-04-03 17:46:41'),
(15, 'พิเศษ (เนื้อเยอะ)', 10.00, 'special', '2025-04-03 17:46:41'),
(16, 'ธรรมดา', 0.00, 'special', '2025-04-03 17:46:41'),
(17, 'ไม่เผ็ด', 0.00, 'spiciness', '2025-04-03 17:46:41'),
(18, 'เผ็ดน้อย', 0.00, 'spiciness', '2025-04-03 17:46:41'),
(19, 'เผ็ดกลาง', 0.00, 'spiciness', '2025-04-03 17:46:41'),
(20, 'เผ็ดมาก', 0.00, 'spiciness', '2025-04-03 17:46:41');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `display_name`, `created_at`) VALUES
(1, 'recommended', 'แนะนำ', '2025-04-03 17:46:41'),
(2, 'noodles', 'ก๋วยเตี๋ยว', '2025-04-03 17:46:41'),
(3, 'rice_dishes', 'อาหารจานเดียว', '2025-04-03 17:46:41'),
(4, 'drinks', 'เครื่องดื่ม', '2025-04-03 17:46:41'),
(5, 'desserts', 'ของหวาน', '2025-04-03 17:46:41');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `food_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foods`
--

CREATE TABLE `foods` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `is_recommended` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `foods`
--

INSERT INTO `foods` (`id`, `name`, `description`, `price`, `image`, `category_id`, `is_recommended`, `created_at`) VALUES
(1, 'น้ำตก หมู (พิเศษ)', 'ก๋วยเตี๋ยวน้ำตกหมูพิเศษ เนื้อเยอะ น้ำซุปกลมกล่อม', 50.00, 'uploads/12ship_mn.jpg', 2, 1, '2025-04-03 17:46:41'),
(2, 'เย็นตาโฟ', 'เย็นตาโฟรสชาติเข้มข้น หอมกลิ่นเครื่องเทศ', 45.00, 'uploads/10pink_mn.jpg', 2, 0, '2025-04-03 17:46:41'),
(3, 'ก๋วยเตี๋ยวต้มยำ', 'ก๋วยเตี๋ยวต้มยำรสจัด เผ็ดสะใจ', 45.00, 'uploads/8tom_mn.jpg', 2, 1, '2025-04-03 17:46:41'),
(4, 'ก๋วยเตี๋ยวเนื้อตุ๋น', 'ก๋วยเตี๋ยวเนื้อตุ๋นเนื้อนุ่ม น้ำซุปหอมเครื่องเทศ', 55.00, 'uploads/manu1.jpeg', 2, 0, '2025-04-03 17:46:41'),
(5, 'ข้าวหมูกรอบ', 'ข้าวหมูทอดกรอบ ราดน้ำราดหวานพิเศษ', 60.00, 'uploads/9hgr_mn.jpg.webp', 3, 1, '2025-04-03 17:46:41'),
(6, 'ข้าวมันไก่', 'ข้าวมันไก่สูตรพิเศษ หอมมัน ไก่นุ่ม', 50.00, 'uploads/11kai_mn.jpg', 3, 0, '2025-04-03 17:46:41'),
(7, 'ชาเย็น', 'ชาไทยเย็น หวานมัน', 25.00, 'uploads/3cha_mn.jpg', 4, 0, '2025-04-03 17:46:41'),
(8, 'กาแฟเย็น', 'กาแฟดำเย็น รสเข้มข้น', 30.00, 'uploads/20feez_mn.jpg', 4, 0, '2025-04-03 17:46:41'),
(9, 'ลอดช่อง', 'ลอดช่องน้ำกะทิ หอมหวาน', 35.00, 'uploads/18lod_mn.jpg', 5, 0, '2025-04-03 17:46:41'),
(10, 'ทับทิมกรอบ', 'ทับทิมกรอบน้ำแข็งใส หวานเย็น', 40.00, 'uploads/16tub_mn.jpg', 5, 0, '2025-04-03 17:46:41');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `status` enum('pending','processing','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `address`, `phone`, `status`, `created_at`, `updated_at`) VALUES
(2, 1, 45.00, 'home', '0812345678', 'pending', '2025-04-05 20:29:42', '2025-04-05 20:29:42'),
(3, 1, 50.00, 'home', '0812345678', 'pending', '2025-04-05 20:33:46', '2025-04-05 20:33:46'),
(4, 1, 30.00, 'tr', '0812345678', 'pending', '2025-04-05 20:44:06', '2025-04-05 20:44:06'),
(5, 1, 45.00, 's', '0812345678', 'pending', '2025-04-05 20:45:10', '2025-04-05 20:45:10'),
(6, 1, 55.00, 'lk', '0812345678', 'pending', '2025-04-05 20:45:41', '2025-04-05 20:45:41'),
(7, 1, 45.00, 'dws', '0812345678', 'pending', '2025-04-05 20:47:09', '2025-04-05 20:47:09'),
(8, 1, 45.00, 'พภพภ', '0812345678', 'pending', '2025-04-05 20:48:36', '2025-04-05 20:48:36'),
(9, 1, 45.00, 'rw', '0812345678', 'pending', '2025-04-05 20:53:22', '2025-04-05 20:53:22'),
(10, 1, 45.00, 'yw4retfg', '0812345678', 'pending', '2025-04-05 20:53:44', '2025-04-05 20:53:44'),
(11, 1, 40.00, 'kpl;', '0812345678', 'pending', '2025-04-05 20:54:35', '2025-04-05 20:54:35'),
(12, 1, 45.00, 'frtgh', '0812345678', 'pending', '2025-04-05 20:56:12', '2025-04-05 20:56:12'),
(13, 1, 120.00, 'ำพเ', '0812345678', 'pending', '2025-04-05 21:01:22', '2025-04-05 21:01:22'),
(14, 1, 40.00, '/ำำ', '0812345678', 'pending', '2025-04-06 19:16:53', '2025-04-06 19:16:53'),
(15, 1, 45.00, 'efgt', '0812345678', 'pending', '2025-04-07 17:49:53', '2025-04-07 17:49:53'),
(18, 6, 40.00, 'df', '224236248', 'pending', '2025-04-07 20:08:36', '2025-04-07 20:08:36'),
(19, 6, 55.00, 'ำพด', '224236248', 'pending', '2025-04-07 20:08:53', '2025-04-07 20:08:53'),
(20, 1, 200.00, 'หดเเพ', '0812345678', 'pending', '2025-04-07 20:53:27', '2025-04-07 20:53:27');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `food_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `special_instructions` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `food_id`, `quantity`, `price`, `special_instructions`, `created_at`) VALUES
(1, 2, 3, 1, 45.00, '', '2025-04-05 20:29:42'),
(2, 3, 1, 1, 50.00, '', '2025-04-05 20:33:46'),
(3, 4, 8, 1, 30.00, '', '2025-04-05 20:44:06'),
(4, 5, 3, 1, 45.00, '', '2025-04-05 20:45:10'),
(5, 6, 4, 1, 55.00, '', '2025-04-05 20:45:41'),
(6, 7, 3, 1, 45.00, '', '2025-04-05 20:47:09'),
(7, 8, 3, 1, 45.00, '', '2025-04-05 20:48:36'),
(8, 9, 3, 1, 45.00, '', '2025-04-05 20:53:22'),
(9, 10, 3, 1, 45.00, '', '2025-04-05 20:53:44'),
(10, 11, 10, 1, 40.00, '', '2025-04-05 20:54:35'),
(11, 12, 3, 1, 45.00, '', '2025-04-05 20:56:12'),
(12, 13, 4, 1, 55.00, '', '2025-04-05 21:01:22'),
(13, 13, 9, 1, 35.00, '', '2025-04-05 21:01:22'),
(14, 13, 8, 1, 30.00, '', '2025-04-05 21:01:22'),
(15, 14, 10, 1, 40.00, '', '2025-04-06 19:16:53'),
(16, 15, 3, 1, 45.00, '', '2025-04-07 17:49:53'),
(17, 18, 10, 1, 40.00, '', '2025-04-07 20:08:36'),
(18, 19, 4, 1, 55.00, '', '2025-04-07 20:08:53'),
(19, 20, 1, 4, 50.00, 'ดก', '2025-04-07 20:53:27');

-- --------------------------------------------------------

--
-- Table structure for table `order_item_addons`
--

CREATE TABLE `order_item_addons` (
  `id` int(11) NOT NULL,
  `order_item_id` int(11) NOT NULL,
  `addon_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_item_addons`
--

INSERT INTO `order_item_addons` (`id`, `order_item_id`, `addon_id`, `price`, `created_at`) VALUES
(1, 1, 1, 0.00, '2025-04-05 20:29:42'),
(2, 1, 7, 0.00, '2025-04-05 20:29:42'),
(3, 2, 1, 0.00, '2025-04-05 20:33:46'),
(4, 2, 7, 0.00, '2025-04-05 20:33:46'),
(5, 3, 1, 0.00, '2025-04-05 20:44:06'),
(6, 3, 7, 0.00, '2025-04-05 20:44:06'),
(7, 4, 1, 0.00, '2025-04-05 20:45:10'),
(8, 4, 7, 0.00, '2025-04-05 20:45:10'),
(9, 5, 1, 0.00, '2025-04-05 20:45:41'),
(10, 5, 7, 0.00, '2025-04-05 20:45:41'),
(11, 6, 1, 0.00, '2025-04-05 20:47:09'),
(12, 6, 7, 0.00, '2025-04-05 20:47:09'),
(13, 7, 1, 0.00, '2025-04-05 20:48:36'),
(14, 7, 7, 0.00, '2025-04-05 20:48:36'),
(15, 8, 1, 0.00, '2025-04-05 20:53:22'),
(16, 8, 7, 0.00, '2025-04-05 20:53:22'),
(17, 9, 1, 0.00, '2025-04-05 20:53:44'),
(18, 9, 7, 0.00, '2025-04-05 20:53:44'),
(19, 10, 1, 0.00, '2025-04-05 20:54:35'),
(20, 10, 7, 0.00, '2025-04-05 20:54:35'),
(21, 11, 1, 0.00, '2025-04-05 20:56:12'),
(22, 11, 7, 0.00, '2025-04-05 20:56:12'),
(23, 12, 1, 0.00, '2025-04-05 21:01:22'),
(24, 12, 7, 0.00, '2025-04-05 21:01:22'),
(25, 13, 1, 0.00, '2025-04-05 21:01:22'),
(26, 13, 7, 0.00, '2025-04-05 21:01:22'),
(27, 14, 1, 0.00, '2025-04-05 21:01:22'),
(28, 14, 7, 0.00, '2025-04-05 21:01:22'),
(29, 15, 1, 0.00, '2025-04-06 19:16:53'),
(30, 15, 7, 0.00, '2025-04-06 19:16:53'),
(31, 16, 1, 0.00, '2025-04-07 17:49:53'),
(32, 16, 7, 0.00, '2025-04-07 17:49:53'),
(33, 17, 1, 0.00, '2025-04-07 20:08:36'),
(34, 17, 7, 0.00, '2025-04-07 20:08:36'),
(35, 18, 1, 0.00, '2025-04-07 20:08:53'),
(36, 18, 7, 0.00, '2025-04-07 20:08:53'),
(37, 19, 1, 0.00, '2025-04-07 20:53:27'),
(38, 19, 7, 0.00, '2025-04-07 20:53:27');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `food_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user2`
--

CREATE TABLE `user2` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user2`
--

INSERT INTO `user2` (`id`, `username`, `password`, `email`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$ZALc0A3u6M7RBHSsCJ9J3.wHxqXH3rNLX/uP4WrK5qwI4vWkMj2Nu', 'admin@example.com', 'admin', '2025-04-03 17:46:41'),
(2, 'user', '$2y$10$ZALc0A3u6M7RBHSsCJ9J3.wHxqXH3rNLX/uP4WrK5qwI4vWkMj2Nu', 'user@example.com', 'user', '2025-04-03 17:46:41');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(128) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `firstname`, `lastname`, `email`, `phone`, `role`, `created_at`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Admin', 'jaa', 'admin@example.com', '0812345678', 'admin', '2025-04-03 01:43:34'),
(2, 'okami', '499a1efd97237fe434b9fb083beda240', 'akami', 'rinna', 'Okm123@gmail.com', '11112223334', 'user', '2025-04-03 14:07:20'),
(4, 'Kanno', '9dfd2c6309a4e079f3379c925dfea0a6', 'Kanno', 'Kanno', 'kana@gmail.com', NULL, 'user', '2025-04-04 02:23:36'),
(5, 'aizen', '75311ef54f223a7d371cc1bbaf4a6c14', 'aizen', 'aizen', 'aizenInwza007@gmail.cm', NULL, 'user', '2025-04-04 10:35:55'),
(6, 'me', 'ab86a1e1ef70dff97959067b723c5c24', 'ka', 'gute', 'me1123@gmail.com', '224236248', 'admin', '2025-04-05 00:46:23'),
(28, 'mama', 'eeafbf4d9b3957b139da7b7f2e7f2d4a', 'mama', 'mama', 'ma@gamil.com', '123456789', 'user', '2025-04-08 08:34:40'),
(29, 'sn', 'afbe94cdbe69a93efabc9f1325fc7dff', 'sn', 'sn', 'da@gamil.com', '123', 'user', '2025-04-08 08:36:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addon_options`
--
ALTER TABLE `addon_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_fav` (`user_id`,`food_id`),
  ADD KEY `food_id` (`food_id`);

--
-- Indexes for table `foods`
--
ALTER TABLE `foods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `food_id` (`food_id`);

--
-- Indexes for table `order_item_addons`
--
ALTER TABLE `order_item_addons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_item_id` (`order_item_id`),
  ADD KEY `addon_id` (`addon_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `food_id` (`food_id`);

--
-- Indexes for table `user2`
--
ALTER TABLE `user2`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addon_options`
--
ALTER TABLE `addon_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `foods`
--
ALTER TABLE `foods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `order_item_addons`
--
ALTER TABLE `order_item_addons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user2`
--
ALTER TABLE `user2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`food_id`) REFERENCES `foods` (`id`);

--
-- Constraints for table `foods`
--
ALTER TABLE `foods`
  ADD CONSTRAINT `foods_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`food_id`) REFERENCES `foods` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_item_addons`
--
ALTER TABLE `order_item_addons`
  ADD CONSTRAINT `order_item_addons_ibfk_1` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_item_addons_ibfk_2` FOREIGN KEY (`addon_id`) REFERENCES `addon_options` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`food_id`) REFERENCES `foods` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
