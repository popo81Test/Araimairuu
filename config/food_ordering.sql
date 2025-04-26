-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2025 at 05:43 PM
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
(10, 'ทับทิมกรอบ', 'ทับทิมกรอบน้ำแข็งใส หวานเย็น', 40.00, 'uploads/16tub_mn.jpg', 5, 0, '2025-04-03 17:46:41'),
(11, 'มัทฉะลาเต้', 'มัทฉะนำเข้าจากญี่ปุ่น เกรดพิธีการ', 110.00, 'uploads/menu10.jpg', 4, 0, '2025-04-14 11:37:03'),
(12, 'น้ำตก เนื้อ (พิเศษ)', 'เมนูแนะนำจากทางร้าน เนื้อวัวนำเข้าจากออสเตรเลีย', 70.00, 'uploads/menu1.jpg', 2, 0, '2025-04-14 11:38:11'),
(13, 'ก๋วยเตี๋ยวหมูน้ำใส', 'ก๋วยเตี๋ยวน้ำใสแบบโบราณ ใส่เครื่องแน่นมีความหอมหวานจากกระดูกเล้งต้มฟักเขียว', 50.00, 'uploads/menu6.jpg', 2, 0, '2025-04-16 04:31:55'),
(15, 'น้ำแข็งใส', 'นำแข็งบริสุทธิ์ กลั่นจากเทือกเขาหิมาลัย', 35.00, 'uploads/5sau_mn.jpg', 1, 0, '2025-04-23 15:38:23');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('pending','processing','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `address`, `phone`, `status`, `created_at`, `updated_at`, `is_read`) VALUES
(151, 33, 45.00, NULL, NULL, 'completed', '2025-04-23 16:31:40', '2025-04-23 16:44:29', 0),
(152, 33, 50.00, NULL, NULL, 'completed', '2025-04-23 16:55:10', '2025-04-23 16:55:30', 0),
(153, 33, 95.00, NULL, NULL, 'processing', '2025-04-23 17:06:50', '2025-04-23 17:09:37', 0),
(154, 33, 80.00, NULL, NULL, 'completed', '2025-04-24 01:16:33', '2025-04-24 01:32:51', 0),
(155, 33, 35.00, NULL, NULL, 'completed', '2025-04-24 01:33:23', '2025-04-24 05:18:36', 0),
(156, 33, 35.00, NULL, NULL, 'processing', '2025-04-25 11:50:39', '2025-04-25 11:51:06', 0),
(157, 33, 110.00, NULL, NULL, 'pending', '2025-04-25 15:17:20', '2025-04-25 15:17:20', 0);

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
(165, 151, 3, 1, 45.00, '', '2025-04-23 16:31:40'),
(166, 152, 13, 1, 50.00, '', '2025-04-23 16:55:10'),
(167, 153, 1, 1, 50.00, '', '2025-04-23 17:06:50'),
(168, 154, 1, 1, 50.00, '', '2025-04-24 01:16:33'),
(169, 155, 15, 1, 35.00, '', '2025-04-24 01:33:23'),
(170, 156, 15, 1, 35.00, '', '2025-04-25 11:50:39'),
(171, 157, 7, 1, 25.00, '', '2025-04-25 15:17:20'),
(172, 157, 4, 1, 55.00, '', '2025-04-25 15:17:20');

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
(245, 165, 1, 0.00, '2025-04-23 16:31:40'),
(246, 165, 7, 0.00, '2025-04-23 16:31:40'),
(247, 166, 1, 0.00, '2025-04-23 16:55:10'),
(248, 166, 7, 0.00, '2025-04-23 16:55:10'),
(249, 167, 1, 0.00, '2025-04-23 17:06:50'),
(250, 167, 7, 0.00, '2025-04-23 17:06:50'),
(251, 167, 10, 15.00, '2025-04-23 17:06:50'),
(252, 167, 12, 15.00, '2025-04-23 17:06:50'),
(253, 167, 14, 15.00, '2025-04-23 17:06:50'),
(254, 168, 1, 0.00, '2025-04-24 01:16:33'),
(255, 168, 7, 0.00, '2025-04-24 01:16:33'),
(256, 168, 9, 15.00, '2025-04-24 01:16:33'),
(257, 168, 11, 15.00, '2025-04-24 01:16:33'),
(258, 172, 1, 0.00, '2025-04-25 15:17:20'),
(259, 172, 8, 0.00, '2025-04-25 15:17:20'),
(260, 172, 10, 15.00, '2025-04-25 15:17:20'),
(261, 172, 12, 15.00, '2025-04-25 15:17:20');

-- --------------------------------------------------------

--
-- Table structure for table `order_notifications`
--

CREATE TABLE `order_notifications` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `created_at` datetime NOT NULL,
  `last_notification_view_time` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `firstname`, `lastname`, `email`, `phone`, `role`, `created_at`, `last_notification_view_time`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Admin', 'jaa', 'admin@example.com', '0812345678', 'admin', '2025-04-03 01:43:34', 1745599866),
(2, 'okami', '499a1efd97237fe434b9fb083beda240', 'akami', 'rinna', 'Okm123@gmail.com', '11112223334', 'user', '2025-04-03 14:07:20', 0),
(4, 'Kanno', '9dfd2c6309a4e079f3379c925dfea0a6', 'Kanno', 'Kanno', 'kana@gmail.com', NULL, 'user', '2025-04-04 02:23:36', 0),
(5, 'aizen', '75311ef54f223a7d371cc1bbaf4a6c14', 'aizen', 'aizen', 'aizenInwza007@gmail.cm', NULL, 'user', '2025-04-04 10:35:55', 0),
(6, 'me', 'ab86a1e1ef70dff97959067b723c5c24', 'ka', 'gute', 'me1123@gmail.com', '224236248', 'admin', '2025-04-05 00:46:23', 0),
(28, 'mama', 'eeafbf4d9b3957b139da7b7f2e7f2d4a', 'mama', 'mama', 'ma@gamil.com', '123456789', 'user', '2025-04-08 08:34:40', 0),
(29, 'sn', 'afbe94cdbe69a93efabc9f1325fc7dff', 'sn', 'sn', 'da@gamil.com', '123', 'user', '2025-04-08 08:36:25', 0),
(32, 'Amy', '25f9e794323b453885f5181f1b624d0b', 'Amy', 'Amy', 'tgryhfgh@mail.com', '77855464', 'user', '2025-04-08 16:08:07', 0),
(33, 'student', 'cd73502828457d15655bbd7a63fb0bc8', 'student', 'student', 'killmoonsddah@mail.com', '076-766-8591', 'user', '2025-04-14 11:36:22', 1745612240),
(34, 'Guy', '25f9e794323b453885f5181f1b624d0b', 'Guy', 'Guy', 'tgryhfgh01@mail.com', '25698745', 'user', '2025-04-16 12:33:21', 0),
(35, 'ff', '633de4b0c14ca52ea2432a3c8a5c4c31', 'ff', 'ff', 'as@gamil.com', '1234', 'user', '2025-04-21 01:55:47', 0),
(36, 'dd', '1aabac6d068eef6a7bad3fdf50a05cc8', 'dd', 'dd', 'dd@gamil.com', '12345678', 'user', '2025-04-23 20:26:22', 0);

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
-- Indexes for table `order_notifications`
--
ALTER TABLE `order_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

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
-- AUTO_INCREMENT for table `foods`
--
ALTER TABLE `foods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173;

--
-- AUTO_INCREMENT for table `order_item_addons`
--
ALTER TABLE `order_item_addons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=262;

--
-- AUTO_INCREMENT for table `order_notifications`
--
ALTER TABLE `order_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

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
-- Constraints for table `order_notifications`
--
ALTER TABLE `order_notifications`
  ADD CONSTRAINT `order_notifications_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
