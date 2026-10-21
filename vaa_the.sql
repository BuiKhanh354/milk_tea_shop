-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2026 at 01:21 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vaa_the`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Cà phê', 'Các loại cà phê', 'coffee.jpg', 1, '2026-09-12 07:12:11', '2026-09-12 07:12:11'),
(2, 'Trà sữa', 'Các loại trà sữa', 'milktea.jpg', 1, '2026-09-12 07:12:11', '2026-09-12 07:12:11'),
(3, 'Trà', 'Các loại trà trái cây và trà nguyên chất', 'tea.jpg', 1, '2026-09-12 07:12:11', '2026-09-12 07:12:11'),
(4, 'Đá xay', 'Các loại thức uống đá xay', 'smoothie.jpg', 1, '2026-09-12 07:12:11', '2026-09-12 07:12:11'),
(5, 'Nước ép', 'Các loại nước ép trái cây', 'juice.jpg', 1, '2026-09-12 07:12:11', '2026-09-12 07:12:11'),
(8, 'Trà trái cây', '', NULL, 1, '2026-10-04 08:42:33', '2026-10-04 08:42:33');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `full_name`, `email`, `password`, `phone`, `address`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Nguyễn Văn An', 'an@gmail.com', '123456', '0900000011', 'TP. Hồ Chí Minh', 1, '2026-09-12 07:12:11', '2026-10-04 09:21:31'),
(2, 'Trần Thị Bình', 'binh@gmail.com', '123456', '0900000012', 'TP. Hồ Chí Minh', 1, '2026-09-12 07:12:11', '2026-09-15 13:35:54'),
(3, 'Lê Văn Cường', 'cuong@gmail.com', '123456', '0900000013', 'TP. Hồ Chí Minh', 1, '2026-09-12 07:12:11', '2026-09-12 07:12:11'),
(4, 'Bùi Viết Khánh', 'khanhbuiviet3@gmail.com', '$2y$10$ir2gFxUgzsOjvV0pKGu1feUuk5WjZC0LuyQK6hAp.Aas7PmN24Mfa', '0938793972', '18A/1 Cộng Hoà, Phường Tân Bình', 1, '2026-09-15 03:47:46', '2026-09-15 11:33:29');

-- --------------------------------------------------------

--
-- Table structure for table `employee_shifts`
--

CREATE TABLE `employee_shifts` (
  `id` int(10) UNSIGNED NOT NULL,
  `employee_id` int(10) UNSIGNED NOT NULL,
  `shift_id` int(10) UNSIGNED NOT NULL,
  `work_date` date NOT NULL,
  `status` enum('assigned','completed','absent','cancelled') DEFAULT 'assigned',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_shifts`
--

INSERT INTO `employee_shifts` (`id`, `employee_id`, `shift_id`, `work_date`, `status`, `created_at`) VALUES
(1, 2, 1, '2026-09-29', 'assigned', '2026-09-29 14:02:33');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `customer_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`customer_id`, `product_id`, `created_at`) VALUES
(1, 4, '2026-09-12 07:12:12'),
(1, 5, '2026-09-12 07:12:12'),
(2, 2, '2026-09-12 07:12:12'),
(4, 10, '2026-10-09 12:47:49'),
(4, 11, '2026-10-09 12:47:49'),
(4, 12, '2026-10-09 12:47:48'),
(4, 13, '2026-10-09 12:47:48');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(10) UNSIGNED NOT NULL,
  `ingredient_name` varchar(150) NOT NULL,
  `unit` varchar(30) NOT NULL,
  `quantity` decimal(12,2) NOT NULL DEFAULT 0.00,
  `min_quantity` decimal(12,2) NOT NULL DEFAULT 0.00,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `ingredient_name`, `unit`, `quantity`, `min_quantity`, `price`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Cà phê hạt', 'kg', 20.00, 5.00, 180000.00, 1, '2026-09-12 07:12:11', '2026-09-12 07:12:11'),
(2, 'Sữa đặc', 'lon', 50.00, 10.00, 25000.00, 1, '2026-09-12 07:12:11', '2026-09-12 07:12:11'),
(3, 'Sữa tươi', 'lít', 29.00, 8.00, 32000.00, 1, '2026-09-12 07:12:11', '2026-10-04 06:38:42'),
(4, 'Bột matcha', 'kg', 4.98, 1.00, 450000.00, 1, '2026-09-12 07:12:11', '2026-10-04 06:38:42'),
(5, 'Trà đen', 'kg', 9.00, 2.00, 180000.00, 1, '2026-09-12 07:12:11', '2026-10-04 06:38:42'),
(6, 'Trà đào', 'kg', 8.00, 2.00, 220000.00, 1, '2026-09-12 07:12:11', '2026-09-12 07:12:11'),
(7, 'Trân châu đen', 'kg', 10.00, 2.00, 80000.00, 1, '2026-09-12 07:12:11', '2026-09-12 07:12:11'),
(8, 'Đường', 'kg', 30.00, 5.00, 20000.00, 1, '2026-09-12 07:12:11', '2026-09-12 07:12:11'),
(9, 'Đá viên', 'kg', 100.00, 20.00, 3000.00, 1, '2026-09-12 07:12:11', '2026-09-12 07:12:11'),
(10, 'Cam', 'kg', 25.00, 5.00, 35000.00, 1, '2026-09-12 07:12:11', '2026-09-12 07:12:11');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_transactions`
--

CREATE TABLE `inventory_transactions` (
  `id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` enum('IMPORT','EXPORT','USAGE','ADJUSTMENT') NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `price` decimal(15,2) DEFAULT 0.00,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_transactions`
--

INSERT INTO `inventory_transactions` (`id`, `inventory_id`, `user_id`, `type`, `quantity`, `price`, `note`, `created_at`) VALUES
(1, 3, NULL, 'USAGE', 0.50, 0.00, 'Bán hàng - Đơn #5', '2026-10-04 06:38:15'),
(2, 4, NULL, 'USAGE', 0.01, 0.00, 'Bán hàng - Đơn #5', '2026-10-04 06:38:15'),
(3, 5, NULL, 'USAGE', 0.50, 0.00, 'Bán hàng - Đơn #5', '2026-10-04 06:38:15'),
(4, 3, NULL, 'USAGE', 0.50, 0.00, 'Bán hàng - Đơn #6', '2026-10-04 06:38:42'),
(5, 4, NULL, 'USAGE', 0.01, 0.00, 'Bán hàng - Đơn #6', '2026-10-04 06:38:42'),
(6, 5, NULL, 'USAGE', 0.50, 0.00, 'Bán hàng - Đơn #6', '2026-10-04 06:38:42');

-- --------------------------------------------------------

--
-- Table structure for table `item_options`
--

CREATE TABLE `item_options` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` enum('sugar','ice') NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` int(11) NOT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_options`
--

INSERT INTO `item_options` (`id`, `type`, `name`, `value`, `is_default`, `status`) VALUES
(1, 'sugar', 'Không đường', 0, 0, 1),
(2, 'sugar', 'Ít đường', 25, 0, 1),
(3, 'sugar', 'Vừa phải', 50, 1, 1),
(4, 'sugar', 'Ngọt', 75, 0, 1),
(5, 'sugar', 'Rất ngọt', 100, 0, 1),
(6, 'ice', 'Không đá', 0, 0, 1),
(7, 'ice', 'Ít đá', 25, 0, 1),
(8, 'ice', 'Đá vừa', 50, 1, 1),
(9, 'ice', 'Nhiều đá', 75, 0, 1),
(10, 'ice', 'Rất nhiều đá', 100, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `table_id` int(10) UNSIGNED DEFAULT NULL,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `final_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `order_type` enum('delivery','takeaway','dine_in') NOT NULL DEFAULT 'takeaway',
  `status` enum('pending','confirmed','preparing','ready','completed','cancelled') NOT NULL DEFAULT 'pending',
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `user_id`, `table_id`, `total_amount`, `discount`, `final_amount`, `order_type`, `status`, `note`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, 70000.00, 0.00, 70000.00, 'dine_in', 'completed', 'Ít đá', '2026-09-12 07:12:11', '2026-09-12 07:12:11'),
(2, NULL, 1, NULL, 65000.00, 0.00, 65000.00, 'takeaway', 'completed', NULL, '2026-09-29 14:20:41', '2026-09-29 14:26:51'),
(3, NULL, 1, NULL, 80000.00, 0.00, 80000.00, 'takeaway', 'completed', NULL, '2026-09-29 14:23:24', '2026-09-29 14:26:50'),
(4, NULL, 1, NULL, 50000.00, 0.00, 50000.00, 'takeaway', 'cancelled', NULL, '2026-09-29 14:29:18', '2026-09-29 14:29:24'),
(5, 4, NULL, NULL, 91000.00, 0.00, 91000.00, 'delivery', 'completed', '', '2026-10-04 06:28:34', '2026-10-04 06:38:14'),
(6, 4, NULL, NULL, 91000.00, 0.00, 91000.00, 'delivery', 'completed', '', '2026-10-04 06:37:46', '2026-10-04 06:38:42'),
(7, NULL, 1, NULL, 55000.00, 0.00, 55000.00, 'delivery', 'completed', NULL, '2026-10-04 07:41:02', '2026-10-04 07:41:22'),
(8, 4, NULL, NULL, 255000.00, 0.00, 255000.00, 'delivery', 'completed', '', '2026-10-04 07:42:19', '2026-10-04 07:42:42'),
(9, 4, NULL, NULL, 45000.00, 0.00, 45000.00, 'delivery', 'cancelled', '', '2026-10-04 08:38:33', '2026-10-04 08:39:04'),
(10, 4, NULL, NULL, 45000.00, 0.00, 45000.00, 'delivery', 'completed', 'Bỏ đá riêng', '2026-10-04 08:40:09', '2026-10-04 08:40:36'),
(11, 4, NULL, NULL, 101000.00, 0.00, 101000.00, 'delivery', 'pending', '', '2026-10-16 12:22:04', '2026-10-16 12:22:04'),
(12, 4, NULL, NULL, 362000.00, 0.00, 362000.00, 'delivery', 'pending', '', '2026-10-20 02:08:39', '2026-10-20 02:08:39'),
(13, 4, NULL, NULL, 202000.00, 0.00, 202000.00, 'delivery', 'pending', '', '2026-10-21 10:56:21', '2026-10-21 10:56:21');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `size_id` int(10) UNSIGNED DEFAULT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `unit_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `sugar_level` tinyint(3) UNSIGNED DEFAULT 100,
  `ice_level` tinyint(3) UNSIGNED DEFAULT 100,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `size_id`, `quantity`, `unit_price`, `subtotal`, `sugar_level`, `ice_level`, `note`, `created_at`) VALUES
(1, 1, 4, 2, 1, 40000.00, 40000.00, 70, 50, 'Ít ngọt', '2026-09-12 07:12:12'),
(2, 1, 1, 1, 1, 25000.00, 25000.00, 100, 50, NULL, '2026-09-12 07:12:12'),
(3, 2, 1, 1, 2, 20000.00, 40000.00, 100, 100, NULL, '2026-09-29 14:20:41'),
(4, 2, 2, 2, 1, 25000.00, 25000.00, 100, 100, NULL, '2026-09-29 14:20:41'),
(5, 3, 1, 1, 1, 20000.00, 20000.00, 100, 100, NULL, '2026-09-29 14:23:24'),
(6, 3, 2, 2, 2, 30000.00, 60000.00, 100, 100, NULL, '2026-09-29 14:23:24'),
(7, 4, 9, 1, 1, 50000.00, 50000.00, 100, 100, NULL, '2026-09-29 14:29:18'),
(8, 5, 11, NULL, 1, 76000.00, 76000.00, 100, 100, NULL, '2026-10-04 06:28:34'),
(9, 6, 11, NULL, 1, 76000.00, 76000.00, 100, 100, NULL, '2026-10-04 06:37:46'),
(10, 7, 9, 2, 1, 55000.00, 55000.00, 100, 100, NULL, '2026-10-04 07:41:02'),
(11, 8, 8, NULL, 3, 80000.00, 240000.00, 100, 100, NULL, '2026-10-04 07:42:19'),
(12, 9, 10, NULL, 1, 30000.00, 30000.00, 100, 100, NULL, '2026-10-04 08:38:33'),
(13, 10, 10, NULL, 1, 30000.00, 30000.00, 100, 100, NULL, '2026-10-04 08:40:09'),
(14, 11, 11, NULL, 1, 86000.00, 86000.00, 100, 100, NULL, '2026-10-16 12:22:04'),
(15, 12, 11, NULL, 2, 121000.00, 242000.00, 100, 100, NULL, '2026-10-20 02:08:39'),
(16, 12, 8, NULL, 3, 35000.00, 105000.00, 100, 100, NULL, '2026-10-20 02:08:39'),
(17, 13, 11, NULL, 1, 76000.00, 76000.00, 100, 100, NULL, '2026-10-21 10:56:21'),
(18, 13, 11, NULL, 1, 111000.00, 111000.00, 100, 100, NULL, '2026-10-21 10:56:21');

-- --------------------------------------------------------

--
-- Table structure for table `order_detail_toppings`
--

CREATE TABLE `order_detail_toppings` (
  `order_detail_id` int(10) UNSIGNED NOT NULL,
  `topping_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_detail_toppings`
--

INSERT INTO `order_detail_toppings` (`order_detail_id`, `topping_id`, `quantity`, `price`) VALUES
(1, 1, 1, 5000.00),
(4, 1, 1, 5000.00),
(6, 1, 1, 5000.00),
(7, 1, 1, 5000.00),
(11, 1, 1, 5000.00),
(11, 2, 1, 5000.00),
(11, 3, 1, 7000.00),
(11, 4, 1, 8000.00),
(11, 5, 1, 10000.00),
(15, 1, 1, 5000.00),
(15, 2, 1, 5000.00),
(15, 3, 1, 7000.00),
(15, 4, 1, 8000.00),
(15, 5, 1, 10000.00),
(18, 1, 1, 5000.00),
(18, 2, 1, 5000.00),
(18, 3, 1, 7000.00),
(18, 4, 1, 8000.00),
(18, 5, 1, 10000.00);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `method` enum('cod','momo','vnpay') NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','paid','failed') NOT NULL DEFAULT 'pending',
  `transaction_code` varchar(100) DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `method`, `amount`, `status`, `transaction_code`, `paid_at`, `created_at`) VALUES
(1, 1, 'cod', 70000.00, 'paid', NULL, '2026-09-12 14:12:12', '2026-09-12 07:12:12'),
(2, 2, '', 65000.00, 'pending', NULL, NULL, '2026-09-29 14:20:41'),
(3, 3, '', 80000.00, 'pending', NULL, NULL, '2026-09-29 14:23:24'),
(4, 4, '', 50000.00, 'pending', NULL, NULL, '2026-09-29 14:29:18'),
(5, 5, '', 91000.00, 'pending', NULL, NULL, '2026-10-04 06:28:34'),
(6, 6, '', 91000.00, 'pending', NULL, NULL, '2026-10-04 06:37:46'),
(7, 7, '', 55000.00, 'pending', NULL, NULL, '2026-10-04 07:41:02'),
(8, 8, '', 255000.00, 'pending', NULL, NULL, '2026-10-04 07:42:19'),
(9, 9, '', 45000.00, 'pending', NULL, NULL, '2026-10-04 08:38:33'),
(10, 10, '', 45000.00, 'pending', NULL, NULL, '2026-10-04 08:40:09'),
(11, 11, '', 101000.00, 'pending', NULL, NULL, '2026-10-16 12:22:04'),
(12, 12, 'momo', 362000.00, 'pending', NULL, NULL, '2026-10-20 02:08:39'),
(13, 13, '', 202000.00, 'pending', NULL, NULL, '2026-10-21 10:56:21');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `price`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Cà phê đen', 'Cà phê đen truyền thống', 25000.00, NULL, 1, '2026-09-12 07:12:11', '2026-10-09 13:32:52'),
(2, 1, 'Cà phê sữa', 'Cà phê sữa truyền thống', 30000.00, 'ca-phe-sua.jpg', 1, '2026-09-12 07:12:11', '2026-09-29 14:09:27'),
(3, 1, 'Bạc xỉu', 'Bạc xỉu thơm béo', 35000.00, 'bac-xiu.jpg', 1, '2026-09-12 07:12:11', '2026-09-29 14:09:25'),
(4, 2, 'Trà sữa truyền thống', 'Trà sữa vị truyền thống', 35000.00, 'tra-sua-truyen-thong.jpg', 1, '2026-09-12 07:12:11', '2026-09-29 14:09:23'),
(5, 2, 'Trà sữa matcha', 'Trà sữa matcha thơm mát', 40000.00, 'tra-sua-matcha.jpg', 1, '2026-09-12 07:12:11', '2026-09-29 14:09:22'),
(6, 2, 'Trà sữa socola', 'Trà sữa socola', 40000.00, 'tra-sua-socola.jpg', 1, '2026-09-12 07:12:11', '2026-09-29 14:09:24'),
(7, 3, 'Trà đào', 'Trà đào cam sả', 35000.00, 'tra-dao.jpg', 1, '2026-09-12 07:12:11', '2026-09-29 14:09:21'),
(8, 3, 'Trà vải', 'Trà vải thanh mát', 35000.00, 'tra-vai.jpg', 1, '2026-09-12 07:12:11', '2026-09-29 14:09:21'),
(9, 4, 'Matcha đá xay', 'Matcha đá xay', 45000.00, 'matcha-da-xay.jpg', 1, '2026-09-12 07:12:11', '2026-09-29 14:09:20'),
(10, 1, 'A-Mê Yuzu', 'Nước cam nguyên chất', 30000.00, '1791103057_A-Me_Yuzu.png', 1, '2026-09-12 07:12:11', '2026-10-04 08:37:37'),
(11, 1, 'A-mê Đào', '', 76000.00, '1791100779_A-MeDao.png', 1, '2026-09-29 14:06:12', '2026-10-04 07:59:44'),
(12, 1, 'A-Mê Đào', '', 55000.00, '1791104022_A-MeDao.png', 1, '2026-10-04 08:53:42', '2026-10-04 08:53:42'),
(13, 1, 'Cold Brew Truyền Thống', '', 47000.00, '1791104072_Traditional_Cold_Brew.png', 1, '2026-10-04 08:54:32', '2026-10-04 08:54:32');

-- --------------------------------------------------------

--
-- Table structure for table `product_ingredients`
--

CREATE TABLE `product_ingredients` (
  `product_id` int(10) UNSIGNED NOT NULL,
  `inventory_id` int(10) UNSIGNED NOT NULL,
  `quantity` decimal(12,3) NOT NULL DEFAULT 0.000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_ingredients`
--

INSERT INTO `product_ingredients` (`product_id`, `inventory_id`, `quantity`) VALUES
(4, 3, 0.140),
(4, 4, 0.100),
(4, 5, 0.020),
(4, 7, 0.030),
(11, 3, 0.500),
(11, 4, 0.010),
(11, 5, 0.500);

-- --------------------------------------------------------

--
-- Table structure for table `product_toppings`
--

CREATE TABLE `product_toppings` (
  `product_id` int(10) UNSIGNED NOT NULL,
  `topping_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_toppings`
--

INSERT INTO `product_toppings` (`product_id`, `topping_id`) VALUES
(4, 1),
(4, 2),
(4, 3),
(4, 4),
(4, 5),
(5, 1),
(5, 2),
(5, 3),
(5, 4),
(5, 5),
(6, 1),
(6, 2),
(6, 3),
(6, 4),
(7, 3),
(7, 5),
(8, 3),
(8, 5),
(9, 1),
(9, 4);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `comment` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `customer_id`, `product_id`, `order_id`, `rating`, `comment`, `status`, `created_at`) VALUES
(1, 1, 4, 1, 5, 'Trà sữa ngon, sẽ ủng hộ tiếp.', 1, '2026-09-12 07:12:12');

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shifts`
--

INSERT INTO `shifts` (`id`, `name`, `start_time`, `end_time`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Ca Sáng', '07:00:00', '11:30:00', 1, '2026-09-29 14:02:19', '2026-09-29 14:02:19'),
(2, 'Ca Tối', '16:30:00', '21:00:00', 1, '2026-10-07 13:46:42', '2026-10-07 13:46:42');

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

CREATE TABLE `sizes` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `extra_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sizes`
--

INSERT INTO `sizes` (`id`, `name`, `extra_price`, `status`, `created_at`, `updated_at`) VALUES
(1, 'M', 5000.00, 1, '2026-09-12 07:12:11', '2026-09-30 13:58:13'),
(2, 'L', 10000.00, 1, '2026-09-12 07:12:11', '2026-09-30 13:58:18'),
(3, 'S', 0.00, 1, '2026-09-12 07:12:11', '2026-10-04 06:04:24');

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `id` int(10) UNSIGNED NOT NULL,
  `table_number` varchar(20) NOT NULL,
  `capacity` int(10) UNSIGNED NOT NULL DEFAULT 4,
  `status` enum('available','occupied','reserved') NOT NULL DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`id`, `table_number`, `capacity`, `status`, `created_at`, `updated_at`) VALUES
(1, 'B01', 2, 'available', '2026-09-12 07:12:11', '2026-10-04 05:32:56'),
(2, 'B02', 2, 'available', '2026-09-12 07:12:11', '2026-10-04 05:34:08'),
(3, 'B03', 4, 'available', '2026-09-12 07:12:11', '2026-10-04 05:33:02'),
(4, 'B04', 4, 'available', '2026-09-12 07:12:11', '2026-10-04 07:45:20'),
(5, 'B05', 6, 'available', '2026-09-12 07:12:11', '2026-10-04 07:45:24'),
(6, 'B06', 6, 'available', '2026-09-12 07:12:11', '2026-10-04 07:45:32');

-- --------------------------------------------------------

--
-- Table structure for table `toppings`
--

CREATE TABLE `toppings` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `toppings`
--

INSERT INTO `toppings` (`id`, `name`, `price`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Trân châu đen', 5000.00, 1, '2026-09-12 07:12:11', '2026-09-30 13:59:09'),
(2, 'Trân châu trắng', 5000.00, 1, '2026-09-12 07:12:11', '2026-09-30 13:53:13'),
(3, 'Thạch trái cây', 7000.00, 1, '2026-09-12 07:12:11', '2026-09-12 07:12:11'),
(4, 'Pudding trứng', 8000.00, 1, '2026-09-12 07:12:11', '2026-09-12 07:12:11'),
(5, 'Kem cheese', 10000.00, 1, '2026-09-12 07:12:11', '2026-09-12 07:12:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('admin','staff') NOT NULL DEFAULT 'staff',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `email`, `phone`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$qnfS3W/zvcjWQsNIFgP1ZeMMCedqPnn2hNRCPJLwcXMak4y6aBuYW', 'Quản trị viên', 'admin@coffeeshop.local', '0900001234', 'admin', 1, '2026-09-12 07:12:11', '2026-09-30 14:10:55'),
(2, 'staff01', '$2y$10$cbC11NEE36hTX1BY/zgRt.tTc7MXPQCnGVcGsyTire1rg2mOiKL6O', 'Bùi Viết Khánh ', 'staff01@coffeeshop.local', '0900000002', 'staff', 1, '2026-09-12 07:12:11', '2026-09-29 14:01:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `employee_shifts`
--
ALTER TABLE `employee_shifts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`,`shift_id`,`work_date`),
  ADD KEY `shift_id` (`shift_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`customer_id`,`product_id`),
  ADD KEY `fk_favorites_product` (`product_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_inventory_quantity` (`quantity`);

--
-- Indexes for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_options`
--
ALTER TABLE `item_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orders_user` (`user_id`),
  ADD KEY `fk_orders_table` (`table_id`),
  ADD KEY `idx_orders_customer` (`customer_id`),
  ADD KEY `idx_orders_status` (`status`),
  ADD KEY `idx_orders_created_at` (`created_at`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_details_product` (`product_id`),
  ADD KEY `fk_order_details_size` (`size_id`),
  ADD KEY `idx_order_details_order` (`order_id`);

--
-- Indexes for table `order_detail_toppings`
--
ALTER TABLE `order_detail_toppings`
  ADD PRIMARY KEY (`order_detail_id`,`topping_id`),
  ADD KEY `fk_order_detail_toppings_topping` (`topping_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_payments_order` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_products_category` (`category_id`),
  ADD KEY `idx_products_status` (`status`);

--
-- Indexes for table `product_ingredients`
--
ALTER TABLE `product_ingredients`
  ADD PRIMARY KEY (`product_id`,`inventory_id`),
  ADD KEY `fk_product_ingredients_iventory` (`inventory_id`);

--
-- Indexes for table `product_toppings`
--
ALTER TABLE `product_toppings`
  ADD PRIMARY KEY (`product_id`,`topping_id`),
  ADD KEY `fk_product_toppings_topping` (`topping_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reviews_customer` (`customer_id`),
  ADD KEY `fk_reviews_order` (`order_id`),
  ADD KEY `idx_reviews_product` (`product_id`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `table_number` (`table_number`);

--
-- Indexes for table `toppings`
--
ALTER TABLE `toppings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employee_shifts`
--
ALTER TABLE `employee_shifts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `item_options`
--
ALTER TABLE `item_options`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sizes`
--
ALTER TABLE `sizes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `toppings`
--
ALTER TABLE `toppings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employee_shifts`
--
ALTER TABLE `employee_shifts`
  ADD CONSTRAINT `employee_shifts_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_shifts_ibfk_2` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `fk_favorites_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_favorites_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orders_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk_order_details_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_details_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_details_size` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `order_detail_toppings`
--
ALTER TABLE `order_detail_toppings`
  ADD CONSTRAINT `fk_order_detail_toppings_detail` FOREIGN KEY (`order_detail_id`) REFERENCES `order_details` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_detail_toppings_topping` FOREIGN KEY (`topping_id`) REFERENCES `toppings` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `product_ingredients`
--
ALTER TABLE `product_ingredients`
  ADD CONSTRAINT `fk_product_ingredients_iventory` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_product_ingredients_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_toppings`
--
ALTER TABLE `product_toppings`
  ADD CONSTRAINT `fk_product_toppings_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_product_toppings_topping` FOREIGN KEY (`topping_id`) REFERENCES `toppings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reviews_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reviews_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
