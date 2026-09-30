-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 29, 2026 at 04:45 PM
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
(5, 'Nước ép', 'Các loại nước ép trái cây', 'juice.jpg', 1, '2026-09-12 07:12:11', '2026-09-12 07:12:11');

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
(1, 'Nguyễn Văn An', 'an@gmail.com', '123456', '0900000011', 'TP. Hồ Chí Minh', 1, '2026-09-12 07:12:11', '2026-09-12 07:12:11'),
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
(2, 2, '2026-09-12 07:12:12');

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
(3, 'Sữa tươi', 'lít', 30.00, 8.00, 32000.00, 1, '2026-09-12 07:12:11', '2026-09-12 07:12:11'),
(4, 'Bột matcha', 'kg', 5.00, 1.00, 450000.00, 1, '2026-09-12 07:12:11', '2026-09-12 07:12:11'),
(5, 'Trà đen', 'kg', 10.00, 2.00, 180000.00, 1, '2026-09-12 07:12:11', '2026-09-12 07:12:11'),
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
(4, NULL, 1, NULL, 50000.00, 0.00, 50000.00, 'takeaway', 'cancelled', NULL, '2026-09-29 14:29:18', '2026-09-29 14:29:24');

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
(7, 4, 9, 1, 1, 50000.00, 50000.00, 100, 100, NULL, '2026-09-29 14:29:18');

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
(7, 1, 1, 5000.00);

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
(4, 4, '', 50000.00, 'pending', NULL, NULL, '2026-09-29 14:29:18');

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
(1, 1, 'Cà phê đen', 'Cà phê đen truyền thống', 25000.00, 'ca-phe-den.jpg', 1, '2026-09-12 07:12:11', '2026-09-29 14:09:29'),
(2, 1, 'Cà phê sữa', 'Cà phê sữa truyền thống', 30000.00, 'ca-phe-sua.jpg', 1, '2026-09-12 07:12:11', '2026-09-29 14:09:27'),
(3, 1, 'Bạc xỉu', 'Bạc xỉu thơm béo', 35000.00, 'bac-xiu.jpg', 1, '2026-09-12 07:12:11', '2026-09-29 14:09:25'),
(4, 2, 'Trà sữa truyền thống', 'Trà sữa vị truyền thống', 35000.00, 'tra-sua-truyen-thong.jpg', 1, '2026-09-12 07:12:11', '2026-09-29 14:09:23'),
(5, 2, 'Trà sữa matcha', 'Trà sữa matcha thơm mát', 40000.00, 'tra-sua-matcha.jpg', 1, '2026-09-12 07:12:11', '2026-09-29 14:09:22'),
(6, 2, 'Trà sữa socola', 'Trà sữa socola', 40000.00, 'tra-sua-socola.jpg', 1, '2026-09-12 07:12:11', '2026-09-29 14:09:24'),
(7, 3, 'Trà đào', 'Trà đào cam sả', 35000.00, 'tra-dao.jpg', 1, '2026-09-12 07:12:11', '2026-09-29 14:09:21'),
(8, 3, 'Trà vải', 'Trà vải thanh mát', 35000.00, 'tra-vai.jpg', 1, '2026-09-12 07:12:11', '2026-09-29 14:09:21'),
(9, 4, 'Matcha đá xay', 'Matcha đá xay', 45000.00, 'matcha-da-xay.jpg', 1, '2026-09-12 07:12:11', '2026-09-29 14:09:20'),
(10, 5, 'Nước cam', 'Nước cam nguyên chất', 30000.00, 'nuoc-cam.jpg', 1, '2026-09-12 07:12:11', '2026-09-29 14:09:17'),
(11, 2, 'Trà Sữa Koi', '', 76000.00, '', 1, '2026-09-29 14:06:12', '2026-09-29 14:10:32');

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
(1, 'Ca Sáng', '07:00:00', '11:30:00', 1, '2026-09-29 14:02:19', '2026-09-29 14:02:19');

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
(1, 'M', 0.00, 1, '2026-09-12 07:12:11', '2026-09-12 07:12:11'),
(2, 'L', 5000.00, 1, '2026-09-12 07:12:11', '2026-09-12 07:12:11'),
(3, 'XL', 10000.00, 1, '2026-09-12 07:12:11', '2026-09-12 07:12:11');

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
(1, 'B01', 2, 'occupied', '2026-09-12 07:12:11', '2026-09-29 13:25:48'),
(2, 'B02', 2, 'reserved', '2026-09-12 07:12:11', '2026-09-29 13:25:55'),
(3, 'B03', 4, 'occupied', '2026-09-12 07:12:11', '2026-09-29 13:27:20'),
(4, 'B04', 4, 'occupied', '2026-09-12 07:12:11', '2026-09-29 13:27:24'),
(5, 'B05', 6, 'reserved', '2026-09-12 07:12:11', '2026-09-29 13:27:30'),
(6, 'B06', 6, 'occupied', '2026-09-12 07:12:11', '2026-09-29 13:27:35');

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
(1, 'Trân châu đen', 5000.00, 1, '2026-09-12 07:12:11', '2026-09-12 07:12:11'),
(2, 'Trân châu trắng', 5000.00, 1, '2026-09-12 07:12:11', '2026-09-12 07:12:11'),
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
(1, 'admin', '$2y$10$qnfS3W/zvcjWQsNIFgP1ZeMMCedqPnn2hNRCPJLwcXMak4y6aBuYW', 'Quản trị viên', 'admin@coffeeshop.local', '0900000001', 'admin', 1, '2026-09-12 07:12:11', '2026-09-15 12:39:28'),
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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