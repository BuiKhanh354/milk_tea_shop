-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: vaa_the
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `cart_token` varchar(64) NOT NULL,
  `cart_key` varchar(64) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `sugar` varchar(50) DEFAULT NULL,
  `ice` varchar(50) DEFAULT NULL,
  `temperature` varchar(20) DEFAULT 'cold',
  `toppings` text DEFAULT NULL,
  `total_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_customer` (`customer_id`),
  KEY `idx_token` (`cart_token`),
  KEY `idx_cart_key` (`cart_key`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
INSERT INTO `cart_items` VALUES (4,5,'c267e70a6244727aaeee1dcca8125eae','98391dff79a94fa623b6614f153f3d83',12,'A-Mê Đào','http://localhost:8000/public/assets/images/products/1791104022_A-MeDao.png','S','50','50','cold','[]',55000.00,1,'2026-10-22 15:30:30','2026-10-22 15:31:32'),(8,4,'d9f3c9d6e51a54ac75a7e546b2570042','341d8e472f7ff25cc5ab540b89f1db76',11,'A-mê Đào','http://localhost:8000/public/assets/images/products/1791100779_A-MeDao.png','S','50','50','cold','[{\"id\":\"1\",\"name\":\"Tr\\u00e2n ch\\u00e2u \\u0111en\",\"price\":\"5000\"}]',81000.00,1,'2026-10-22 15:40:00','2026-10-22 15:40:00');
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Cà phê','Các loại cà phê','coffee.jpg',1,'2026-09-12 07:12:11','2026-09-12 07:12:11'),(2,'Trà sữa','Các loại trà sữa','milktea.jpg',1,'2026-09-12 07:12:11','2026-09-12 07:12:11'),(3,'Trà','Các loại trà trái cây và trà nguyên chất','tea.jpg',1,'2026-09-12 07:12:11','2026-09-12 07:12:11'),(4,'Đá xay','Các loại thức uống đá xay','smoothie.jpg',1,'2026-09-12 07:12:11','2026-09-12 07:12:11'),(5,'Nước ép','Các loại nước ép trái cây','juice.jpg',1,'2026-09-12 07:12:11','2026-09-12 07:12:11'),(8,'Trà trái cây','',NULL,1,'2026-10-04 08:42:33','2026-10-04 08:42:33');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'Nguyễn Văn An','an@gmail.com','$2y$10$ir2gFxUgzsOjvV0pKGu1feUuk5WjZC0LuyQK6hAp.Aas7PmN24Mfa','0900000011','TP. Hồ Chí Minh',1,'2026-09-12 07:12:11','2026-10-22 15:31:51'),(2,'Trần Thị Bình','binh@gmail.com','$2y$10$ir2gFxUgzsOjvV0pKGu1feUuk5WjZC0LuyQK6hAp.Aas7PmN24Mfa','0900000012','TP. Hồ Chí Minh',1,'2026-09-12 07:12:11','2026-10-22 15:31:48'),(3,'Lê Văn Cường','cuong@gmail.com','$2y$10$ir2gFxUgzsOjvV0pKGu1feUuk5WjZC0LuyQK6hAp.Aas7PmN24Mfa','0900000013','TP. Hồ Chí Minh',1,'2026-09-12 07:12:11','2026-10-22 15:31:45'),(4,'Bùi Viết Khánh','khanhbuiviet3@gmail.com','$2y$10$ir2gFxUgzsOjvV0pKGu1feUuk5WjZC0LuyQK6hAp.Aas7PmN24Mfa','0938793972','18A/1 Cộng Hoà, Phường Tân Bình',1,'2026-09-15 03:47:46','2026-10-21 15:54:09'),(5,'Nguyễn Văn A','nguyenvana@gmail.com','$2y$10$A/pqRaqvPR4xOfJ6BDX4sua6yUIwKyg4gAH1qzMvup9./eFxvnt9y',NULL,NULL,1,'2026-10-22 15:31:03','2026-10-22 15:31:03');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_shifts`
--

DROP TABLE IF EXISTS `employee_shifts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee_shifts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(10) unsigned NOT NULL,
  `shift_id` int(10) unsigned NOT NULL,
  `work_date` date NOT NULL,
  `status` enum('assigned','completed','absent','cancelled') DEFAULT 'assigned',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_id` (`employee_id`,`shift_id`,`work_date`),
  KEY `shift_id` (`shift_id`),
  CONSTRAINT `employee_shifts_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `employee_shifts_ibfk_2` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_shifts`
--

LOCK TABLES `employee_shifts` WRITE;
/*!40000 ALTER TABLE `employee_shifts` DISABLE KEYS */;
INSERT INTO `employee_shifts` VALUES (1,2,1,'2026-09-29','assigned','2026-09-29 14:02:33');
/*!40000 ALTER TABLE `employee_shifts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favorites`
--

DROP TABLE IF EXISTS `favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `favorites` (
  `customer_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`customer_id`,`product_id`),
  KEY `fk_favorites_product` (`product_id`),
  CONSTRAINT `fk_favorites_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_favorites_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorites`
--

LOCK TABLES `favorites` WRITE;
/*!40000 ALTER TABLE `favorites` DISABLE KEYS */;
INSERT INTO `favorites` VALUES (1,4,'2026-09-12 07:12:12'),(1,5,'2026-09-12 07:12:12'),(2,2,'2026-09-12 07:12:12'),(4,10,'2026-10-09 12:47:49'),(4,11,'2026-10-09 12:47:49'),(4,12,'2026-10-09 12:47:48'),(4,13,'2026-10-09 12:47:48');
/*!40000 ALTER TABLE `favorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ingredient_name` varchar(150) NOT NULL,
  `unit` varchar(30) NOT NULL,
  `quantity` decimal(12,2) NOT NULL DEFAULT 0.00,
  `min_quantity` decimal(12,2) NOT NULL DEFAULT 0.00,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_inventory_quantity` (`quantity`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory`
--

LOCK TABLES `inventory` WRITE;
/*!40000 ALTER TABLE `inventory` DISABLE KEYS */;
INSERT INTO `inventory` VALUES (1,'Cà phê hạt','kg',29.50,5.00,180000.00,1,'2026-09-12 07:12:11','2026-10-22 07:57:28'),(2,'Sữa đặc','lon',50.00,10.00,25000.00,1,'2026-09-12 07:12:11','2026-09-12 07:12:11'),(3,'Sữa tươi','lít',25.86,8.00,32000.00,1,'2026-09-12 07:12:11','2026-10-22 16:14:42'),(4,'Bột matcha','kg',4.82,1.00,450000.00,1,'2026-09-12 07:12:11','2026-10-22 16:14:42'),(5,'Trà đen','kg',5.98,2.00,180000.00,1,'2026-09-12 07:12:11','2026-10-22 16:14:42'),(6,'Trà đào','kg',8.00,2.00,220000.00,1,'2026-09-12 07:12:11','2026-09-12 07:12:11'),(7,'Trân châu đen','kg',9.97,2.00,80000.00,1,'2026-09-12 07:12:11','2026-10-22 07:56:47'),(8,'Đường','kg',30.00,5.00,20000.00,1,'2026-09-12 07:12:11','2026-09-12 07:12:11'),(9,'Đá viên','kg',99.98,20.00,3000.00,1,'2026-09-12 07:12:11','2026-10-22 07:57:28'),(10,'Cam','kg',25.00,5.00,35000.00,1,'2026-09-12 07:12:11','2026-09-12 07:12:11'),(11,'Nước đường','ml',1000.00,10.00,50000.00,1,'2026-10-22 16:16:31','2026-10-22 16:17:36');
/*!40000 ALTER TABLE `inventory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_audit_details`
--

DROP TABLE IF EXISTS `inventory_audit_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_audit_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `audit_id` int(10) unsigned NOT NULL,
  `inventory_id` int(10) unsigned NOT NULL,
  `system_quantity` decimal(12,2) NOT NULL DEFAULT 0.00,
  `actual_quantity` decimal(12,2) NOT NULL DEFAULT 0.00,
  `difference_quantity` decimal(12,2) NOT NULL DEFAULT 0.00,
  `reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_audit` (`audit_id`),
  KEY `idx_inventory` (`inventory_id`),
  CONSTRAINT `fk_audit_details_audit` FOREIGN KEY (`audit_id`) REFERENCES `inventory_audits` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_audit_details`
--

LOCK TABLES `inventory_audit_details` WRITE;
/*!40000 ALTER TABLE `inventory_audit_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory_audit_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_audits`
--

DROP TABLE IF EXISTS `inventory_audits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_audits` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `audit_code` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_items` int(10) unsigned DEFAULT 0,
  `total_difference_items` int(10) unsigned DEFAULT 0,
  `status` enum('completed','draft') DEFAULT 'completed',
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `audit_code` (`audit_code`),
  KEY `idx_user` (`user_id`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_audits`
--

LOCK TABLES `inventory_audits` WRITE;
/*!40000 ALTER TABLE `inventory_audits` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory_audits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_transactions`
--

DROP TABLE IF EXISTS `inventory_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inventory_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` enum('IMPORT','EXPORT','USAGE','ADJUSTMENT') NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `price` decimal(15,2) DEFAULT 0.00,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_transactions`
--

LOCK TABLES `inventory_transactions` WRITE;
/*!40000 ALTER TABLE `inventory_transactions` DISABLE KEYS */;
INSERT INTO `inventory_transactions` VALUES (1,3,NULL,'USAGE',0.50,0.00,'Bán hàng - Đơn #5','2026-10-04 06:38:15'),(2,4,NULL,'USAGE',0.01,0.00,'Bán hàng - Đơn #5','2026-10-04 06:38:15'),(3,5,NULL,'USAGE',0.50,0.00,'Bán hàng - Đơn #5','2026-10-04 06:38:15'),(4,3,NULL,'USAGE',0.50,0.00,'Bán hàng - Đơn #6','2026-10-04 06:38:42'),(5,4,NULL,'USAGE',0.01,0.00,'Bán hàng - Đơn #6','2026-10-04 06:38:42'),(6,5,NULL,'USAGE',0.50,0.00,'Bán hàng - Đơn #6','2026-10-04 06:38:42'),(7,3,NULL,'USAGE',1.00,0.00,'Bán hàng - Đơn #13','2026-10-21 11:29:29'),(8,4,NULL,'USAGE',0.02,0.00,'Bán hàng - Đơn #13','2026-10-21 11:29:29'),(9,5,NULL,'USAGE',1.00,0.00,'Bán hàng - Đơn #13','2026-10-21 11:29:29'),(10,3,NULL,'USAGE',1.00,0.00,'Bán hàng - Đơn #12','2026-10-21 11:29:52'),(11,4,NULL,'USAGE',0.02,0.00,'Bán hàng - Đơn #12','2026-10-21 11:29:52'),(12,5,NULL,'USAGE',1.00,0.00,'Bán hàng - Đơn #12','2026-10-21 11:29:52'),(13,1,1,'IMPORT',10.00,180000.00,'','2026-10-21 11:30:37'),(14,1,1,'IMPORT',20.00,180000.00,'','2026-10-21 11:30:56'),(15,1,1,'EXPORT',20.00,180000.00,'Lý do: Hàng hỏng/Hết hạn','2026-10-21 11:31:16'),(16,1,NULL,'USAGE',0.25,0.00,'Bán hàng - Đơn #15','2026-10-22 07:30:05'),(17,9,NULL,'USAGE',0.01,0.00,'Bán hàng - Đơn #15','2026-10-22 07:30:05'),(18,3,NULL,'USAGE',0.50,0.00,'Bán hàng - Đơn #20','2026-10-22 07:44:16'),(19,4,NULL,'USAGE',0.01,0.00,'Bán hàng - Đơn #20','2026-10-22 07:44:16'),(20,5,NULL,'USAGE',0.50,0.00,'Bán hàng - Đơn #20','2026-10-22 07:44:16'),(21,3,NULL,'USAGE',0.14,0.00,'Bán hàng - Đơn #21','2026-10-22 07:56:47'),(22,4,NULL,'USAGE',0.10,0.00,'Bán hàng - Đơn #21','2026-10-22 07:56:47'),(23,5,NULL,'USAGE',0.02,0.00,'Bán hàng - Đơn #21','2026-10-22 07:56:47'),(24,7,NULL,'USAGE',0.03,0.00,'Bán hàng - Đơn #21','2026-10-22 07:56:47'),(25,1,NULL,'USAGE',0.25,0.00,'Bán hàng - Đơn #17','2026-10-22 07:57:28'),(26,9,NULL,'USAGE',0.01,0.00,'Bán hàng - Đơn #17','2026-10-22 07:57:28'),(27,4,NULL,'USAGE',0.01,0.00,'Bán hàng - Đơn #23','2026-10-22 16:14:42'),(28,3,NULL,'USAGE',0.50,0.00,'Bán hàng - Đơn #23','2026-10-22 16:14:42'),(29,5,NULL,'USAGE',0.50,0.00,'Bán hàng - Đơn #23','2026-10-22 16:14:42'),(30,11,1,'IMPORT',1000.00,50000.00,'','2026-10-22 16:17:36');
/*!40000 ALTER TABLE `inventory_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_options`
--

DROP TABLE IF EXISTS `item_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('sugar','ice') NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` int(11) NOT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_options`
--

LOCK TABLES `item_options` WRITE;
/*!40000 ALTER TABLE `item_options` DISABLE KEYS */;
INSERT INTO `item_options` VALUES (1,'sugar','Không đường',0,0,1),(2,'sugar','Ít đường',25,0,1),(3,'sugar','Vừa phải',50,1,1),(4,'sugar','Ngọt',75,0,1),(5,'sugar','Rất ngọt',100,0,1),(6,'ice','Không đá',0,0,1),(7,'ice','Ít đá',25,0,1),(8,'ice','Đá vừa',50,1,1),(9,'ice','Nhiều đá',75,0,1),(10,'ice','Rất nhiều đá',100,0,1);
/*!40000 ALTER TABLE `item_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_detail_toppings`
--

DROP TABLE IF EXISTS `order_detail_toppings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_detail_toppings` (
  `order_detail_id` int(10) unsigned NOT NULL,
  `topping_id` int(10) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT 1,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`order_detail_id`,`topping_id`),
  KEY `fk_order_detail_toppings_topping` (`topping_id`),
  CONSTRAINT `fk_order_detail_toppings_detail` FOREIGN KEY (`order_detail_id`) REFERENCES `order_details` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_order_detail_toppings_topping` FOREIGN KEY (`topping_id`) REFERENCES `toppings` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_detail_toppings`
--

LOCK TABLES `order_detail_toppings` WRITE;
/*!40000 ALTER TABLE `order_detail_toppings` DISABLE KEYS */;
INSERT INTO `order_detail_toppings` VALUES (1,1,1,5000.00),(4,1,1,5000.00),(6,1,1,5000.00),(7,1,1,5000.00),(11,1,1,5000.00),(11,2,1,5000.00),(11,3,1,7000.00),(11,4,1,8000.00),(11,5,1,10000.00),(15,1,1,5000.00),(15,2,1,5000.00),(15,3,1,7000.00),(15,4,1,8000.00),(15,5,1,10000.00),(18,1,1,5000.00),(18,2,1,5000.00),(18,3,1,7000.00),(18,4,1,8000.00),(18,5,1,10000.00),(20,1,1,5000.00),(20,2,1,5000.00),(20,3,1,7000.00),(20,4,1,8000.00),(20,5,1,10000.00),(21,1,1,5000.00),(21,2,1,5000.00),(21,3,1,7000.00),(21,4,1,8000.00),(21,5,1,10000.00),(22,2,1,5000.00),(22,3,1,7000.00),(23,2,1,5000.00),(23,4,1,8000.00),(24,2,1,5000.00),(24,4,1,8000.00);
/*!40000 ALTER TABLE `order_detail_toppings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `size_id` int(10) unsigned DEFAULT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT 1,
  `unit_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `sugar_level` tinyint(3) unsigned DEFAULT 100,
  `ice_level` tinyint(3) unsigned DEFAULT 100,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_order_details_product` (`product_id`),
  KEY `fk_order_details_size` (`size_id`),
  KEY `idx_order_details_order` (`order_id`),
  CONSTRAINT `fk_order_details_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_order_details_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_order_details_size` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_details`
--

LOCK TABLES `order_details` WRITE;
/*!40000 ALTER TABLE `order_details` DISABLE KEYS */;
INSERT INTO `order_details` VALUES (1,1,4,2,1,40000.00,40000.00,70,50,'Ít ngọt','2026-09-12 07:12:12'),(2,1,1,1,1,25000.00,25000.00,100,50,NULL,'2026-09-12 07:12:12'),(3,2,1,1,2,20000.00,40000.00,100,100,NULL,'2026-09-29 14:20:41'),(4,2,2,2,1,25000.00,25000.00,100,100,NULL,'2026-09-29 14:20:41'),(5,3,1,1,1,20000.00,20000.00,100,100,NULL,'2026-09-29 14:23:24'),(6,3,2,2,2,30000.00,60000.00,100,100,NULL,'2026-09-29 14:23:24'),(7,4,9,1,1,50000.00,50000.00,100,100,NULL,'2026-09-29 14:29:18'),(8,5,11,NULL,1,76000.00,76000.00,100,100,NULL,'2026-10-04 06:28:34'),(9,6,11,NULL,1,76000.00,76000.00,100,100,NULL,'2026-10-04 06:37:46'),(10,7,9,2,1,55000.00,55000.00,100,100,NULL,'2026-10-04 07:41:02'),(11,8,8,NULL,3,80000.00,240000.00,100,100,NULL,'2026-10-04 07:42:19'),(12,9,10,NULL,1,30000.00,30000.00,100,100,NULL,'2026-10-04 08:38:33'),(13,10,10,NULL,1,30000.00,30000.00,100,100,NULL,'2026-10-04 08:40:09'),(14,11,11,NULL,1,86000.00,86000.00,100,100,NULL,'2026-10-16 12:22:04'),(15,12,11,NULL,2,121000.00,242000.00,100,100,NULL,'2026-10-20 02:08:39'),(16,12,8,NULL,3,35000.00,105000.00,100,100,NULL,'2026-10-20 02:08:39'),(17,13,11,NULL,1,76000.00,76000.00,100,100,NULL,'2026-10-21 10:56:21'),(18,13,11,NULL,1,111000.00,111000.00,100,100,NULL,'2026-10-21 10:56:21'),(19,14,11,NULL,1,76000.00,76000.00,100,100,NULL,'2026-10-21 15:54:05'),(20,15,13,NULL,1,92000.00,92000.00,100,100,NULL,'2026-10-21 15:54:48'),(21,16,11,NULL,1,111000.00,111000.00,100,100,NULL,'2026-10-22 07:37:37'),(22,17,13,NULL,1,59000.00,59000.00,100,100,NULL,'2026-10-22 07:38:48'),(23,18,12,NULL,1,68000.00,68000.00,100,100,NULL,'2026-10-22 07:39:30'),(24,19,10,NULL,1,43000.00,43000.00,100,100,NULL,'2026-10-22 07:40:51'),(25,20,11,NULL,1,76000.00,76000.00,100,100,NULL,'2026-10-22 07:41:48'),(26,21,4,NULL,1,35000.00,35000.00,100,100,NULL,'2026-10-22 07:44:44'),(27,22,11,NULL,6,76000.00,456000.00,100,100,NULL,'2026-10-22 15:27:47'),(28,23,4,NULL,1,35000.00,35000.00,100,100,NULL,'2026-10-22 15:39:55'),(29,23,4,NULL,1,35000.00,35000.00,100,100,NULL,'2026-10-22 15:39:55'),(30,23,11,NULL,1,76000.00,76000.00,100,100,NULL,'2026-10-22 15:39:55');
/*!40000 ALTER TABLE `order_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `table_id` int(10) unsigned DEFAULT NULL,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `final_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `order_type` enum('delivery','takeaway','dine_in') NOT NULL DEFAULT 'takeaway',
  `status` enum('pending','confirmed','preparing','ready','completed','cancelled') NOT NULL DEFAULT 'pending',
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_orders_user` (`user_id`),
  KEY `fk_orders_table` (`table_id`),
  KEY `idx_orders_customer` (`customer_id`),
  KEY `idx_orders_status` (`status`),
  KEY `idx_orders_created_at` (`created_at`),
  CONSTRAINT `fk_orders_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_orders_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,1,2,1,70000.00,0.00,70000.00,'dine_in','completed','Ít đá','2026-09-12 07:12:11','2026-09-12 07:12:11'),(2,NULL,1,NULL,65000.00,0.00,65000.00,'takeaway','completed',NULL,'2026-09-29 14:20:41','2026-09-29 14:26:51'),(3,NULL,1,NULL,80000.00,0.00,80000.00,'takeaway','completed',NULL,'2026-09-29 14:23:24','2026-09-29 14:26:50'),(4,NULL,1,NULL,50000.00,0.00,50000.00,'takeaway','cancelled',NULL,'2026-09-29 14:29:18','2026-09-29 14:29:24'),(5,4,NULL,NULL,91000.00,0.00,91000.00,'delivery','completed','','2026-10-04 06:28:34','2026-10-04 06:38:14'),(6,4,NULL,NULL,91000.00,0.00,91000.00,'delivery','completed','','2026-10-04 06:37:46','2026-10-04 06:38:42'),(7,NULL,1,NULL,55000.00,0.00,55000.00,'delivery','completed',NULL,'2026-10-04 07:41:02','2026-10-04 07:41:22'),(8,4,NULL,NULL,255000.00,0.00,255000.00,'delivery','completed','','2026-10-04 07:42:19','2026-10-04 07:42:42'),(9,4,NULL,NULL,45000.00,0.00,45000.00,'delivery','cancelled','','2026-10-04 08:38:33','2026-10-04 08:39:04'),(10,4,NULL,NULL,45000.00,0.00,45000.00,'delivery','completed','Bỏ đá riêng','2026-10-04 08:40:09','2026-10-04 08:40:36'),(11,4,NULL,NULL,101000.00,0.00,101000.00,'delivery','pending','','2026-10-16 12:22:04','2026-10-16 12:22:04'),(12,4,NULL,NULL,362000.00,0.00,362000.00,'delivery','completed','','2026-10-20 02:08:39','2026-10-21 11:29:52'),(13,4,NULL,NULL,202000.00,0.00,202000.00,'delivery','completed','','2026-10-21 10:56:21','2026-10-21 11:29:29'),(14,4,NULL,NULL,91000.00,0.00,91000.00,'delivery','pending','','2026-10-21 15:54:05','2026-10-21 15:54:05'),(15,4,NULL,NULL,107000.00,0.00,107000.00,'delivery','completed','','2026-10-21 15:54:48','2026-10-22 07:30:05'),(16,4,NULL,NULL,126000.00,0.00,126000.00,'delivery','pending','','2026-10-22 07:37:37','2026-10-22 07:37:37'),(17,4,NULL,NULL,74000.00,0.00,74000.00,'delivery','completed','','2026-10-22 07:38:48','2026-10-22 07:57:28'),(18,4,NULL,NULL,83000.00,0.00,83000.00,'delivery','completed','Ngọt','2026-10-22 07:39:30','2026-10-22 07:39:54'),(19,4,NULL,NULL,58000.00,0.00,58000.00,'delivery','cancelled','test','2026-10-22 07:40:51','2026-10-22 07:41:20'),(20,4,NULL,NULL,91000.00,0.00,91000.00,'delivery','completed','thêm ','2026-10-22 07:41:48','2026-10-22 07:44:16'),(21,4,NULL,NULL,50000.00,0.00,50000.00,'delivery','completed','','2026-10-22 07:44:44','2026-10-22 07:56:47'),(22,4,NULL,NULL,471000.00,0.00,471000.00,'delivery','pending','','2026-10-22 15:27:47','2026-10-22 15:27:47'),(23,4,NULL,NULL,161000.00,0.00,161000.00,'delivery','completed','','2026-10-22 15:39:55','2026-10-22 16:14:42');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `method` enum('cod','momo','vnpay') NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','paid','failed') NOT NULL DEFAULT 'pending',
  `transaction_code` varchar(100) DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_payments_order` (`order_id`),
  CONSTRAINT `fk_payments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,1,'cod',70000.00,'paid',NULL,'2026-09-12 14:12:12','2026-09-12 07:12:12'),(2,2,'',65000.00,'pending',NULL,NULL,'2026-09-29 14:20:41'),(3,3,'',80000.00,'pending',NULL,NULL,'2026-09-29 14:23:24'),(4,4,'',50000.00,'pending',NULL,NULL,'2026-09-29 14:29:18'),(5,5,'',91000.00,'pending',NULL,NULL,'2026-10-04 06:28:34'),(6,6,'',91000.00,'pending',NULL,NULL,'2026-10-04 06:37:46'),(7,7,'',55000.00,'pending',NULL,NULL,'2026-10-04 07:41:02'),(8,8,'',255000.00,'pending',NULL,NULL,'2026-10-04 07:42:19'),(9,9,'',45000.00,'pending',NULL,NULL,'2026-10-04 08:38:33'),(10,10,'',45000.00,'pending',NULL,NULL,'2026-10-04 08:40:09'),(11,11,'',101000.00,'pending',NULL,NULL,'2026-10-16 12:22:04'),(12,12,'momo',362000.00,'pending',NULL,NULL,'2026-10-20 02:08:39'),(13,13,'',202000.00,'pending',NULL,NULL,'2026-10-21 10:56:21'),(14,14,'',91000.00,'pending',NULL,NULL,'2026-10-21 15:54:05'),(15,15,'',107000.00,'pending',NULL,NULL,'2026-10-21 15:54:48'),(16,16,'',126000.00,'pending',NULL,NULL,'2026-10-22 07:37:37'),(17,17,'',74000.00,'pending',NULL,NULL,'2026-10-22 07:38:48'),(18,18,'',83000.00,'pending',NULL,NULL,'2026-10-22 07:39:30'),(19,19,'',58000.00,'pending',NULL,NULL,'2026-10-22 07:40:51'),(20,20,'',91000.00,'pending',NULL,NULL,'2026-10-22 07:41:48'),(21,21,'',50000.00,'pending',NULL,NULL,'2026-10-22 07:44:44'),(22,22,'',471000.00,'pending',NULL,NULL,'2026-10-22 15:27:47'),(23,23,'',161000.00,'pending',NULL,NULL,'2026-10-22 15:39:55');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_ingredients`
--

DROP TABLE IF EXISTS `product_ingredients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_ingredients` (
  `product_id` int(10) unsigned NOT NULL,
  `size_id` int(10) unsigned NOT NULL DEFAULT 0,
  `inventory_id` int(10) unsigned NOT NULL,
  `quantity` decimal(12,3) NOT NULL DEFAULT 0.000,
  PRIMARY KEY (`product_id`,`size_id`,`inventory_id`),
  KEY `fk_product_ingredients_iventory` (`inventory_id`),
  CONSTRAINT `fk_product_ingredients_iventory` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_product_ingredients_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_ingredients`
--

LOCK TABLES `product_ingredients` WRITE;
/*!40000 ALTER TABLE `product_ingredients` DISABLE KEYS */;
INSERT INTO `product_ingredients` VALUES (4,1,7,40.000),(4,1,8,30.000),(4,2,7,60.000),(4,2,8,45.000),(4,3,8,20.000),(11,0,3,0.500),(11,0,4,0.010),(11,0,5,0.500),(13,0,1,0.250),(13,0,9,0.010),(13,3,8,0.030);
/*!40000 ALTER TABLE `product_ingredients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_sugar_levels`
--

DROP TABLE IF EXISTS `product_sugar_levels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_sugar_levels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL,
  `size_id` int(10) unsigned NOT NULL DEFAULT 0,
  `sugar_percent` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 0.00,
  `inventory_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_prod_size_sugar` (`product_id`,`size_id`,`sugar_percent`),
  KEY `fk_sugar_inventory` (`inventory_id`),
  CONSTRAINT `fk_sugar_inventory` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_sugar_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_sugar_levels`
--

LOCK TABLES `product_sugar_levels` WRITE;
/*!40000 ALTER TABLE `product_sugar_levels` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_sugar_levels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_toppings`
--

DROP TABLE IF EXISTS `product_toppings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_toppings` (
  `product_id` int(10) unsigned NOT NULL,
  `topping_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`product_id`,`topping_id`),
  KEY `fk_product_toppings_topping` (`topping_id`),
  CONSTRAINT `fk_product_toppings_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_product_toppings_topping` FOREIGN KEY (`topping_id`) REFERENCES `toppings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_toppings`
--

LOCK TABLES `product_toppings` WRITE;
/*!40000 ALTER TABLE `product_toppings` DISABLE KEYS */;
INSERT INTO `product_toppings` VALUES (4,1),(4,2),(4,3),(4,4),(4,5),(5,1),(5,2),(5,3),(5,4),(5,5),(6,1),(6,2),(6,3),(6,4),(7,3),(7,5),(8,3),(8,5),(9,1),(9,4),(11,1),(11,3),(11,5),(13,1),(13,5);
/*!40000 ALTER TABLE `product_toppings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_products_category` (`category_id`),
  KEY `idx_products_status` (`status`),
  CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,'Cà phê đen','Cà phê đen truyền thống',25000.00,NULL,1,'2026-09-12 07:12:11','2026-10-09 13:32:52'),(2,1,'Cà phê sữa','Cà phê sữa truyền thống',30000.00,'ca-phe-sua.jpg',1,'2026-09-12 07:12:11','2026-09-29 14:09:27'),(3,1,'Bạc xỉu','Bạc xỉu thơm béo',35000.00,'bac-xiu.jpg',1,'2026-09-12 07:12:11','2026-09-29 14:09:25'),(4,2,'Trà sữa truyền thống','Trà sữa vị truyền thống',35000.00,'tra-sua-truyen-thong.jpg',1,'2026-09-12 07:12:11','2026-09-29 14:09:23'),(5,2,'Trà sữa matcha','Trà sữa matcha thơm mát',40000.00,'tra-sua-matcha.jpg',1,'2026-09-12 07:12:11','2026-09-29 14:09:22'),(6,2,'Trà sữa socola','Trà sữa socola',40000.00,'tra-sua-socola.jpg',1,'2026-09-12 07:12:11','2026-09-29 14:09:24'),(7,3,'Trà đào','Trà đào cam sả',35000.00,'tra-dao.jpg',1,'2026-09-12 07:12:11','2026-09-29 14:09:21'),(8,3,'Trà vải','Trà vải thanh mát',35000.00,'tra-vai.jpg',1,'2026-09-12 07:12:11','2026-09-29 14:09:21'),(9,4,'Matcha đá xay','Matcha đá xay',45000.00,'matcha-da-xay.jpg',1,'2026-09-12 07:12:11','2026-10-22 16:25:43'),(10,1,'A-Mê Yuzu','Nước cam nguyên chất',30000.00,'1791103057_A-Me_Yuzu.png',1,'2026-09-12 07:12:11','2026-10-04 08:37:37'),(11,1,'A-mê Đào','',76000.00,'1791100779_A-MeDao.png',1,'2026-09-29 14:06:12','2026-10-04 07:59:44'),(12,1,'A-Mê Đào','',55000.00,'1791104022_A-MeDao.png',1,'2026-10-04 08:53:42','2026-10-22 16:26:17'),(13,1,'Cold Brew Truyền Thống','test',47000.00,'1791104072_Traditional_Cold_Brew.png',1,'2026-10-04 08:54:32','2026-10-22 15:26:40');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reviews` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `order_id` int(10) unsigned NOT NULL,
  `rating` tinyint(3) unsigned NOT NULL,
  `comment` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_reviews_customer` (`customer_id`),
  KEY `fk_reviews_order` (`order_id`),
  KEY `idx_reviews_product` (`product_id`),
  CONSTRAINT `fk_reviews_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_reviews_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_reviews_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,1,4,1,5,'Trà sữa ngon, sẽ ủng hộ tiếp.',1,'2026-09-12 07:12:12');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shifts`
--

DROP TABLE IF EXISTS `shifts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shifts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shifts`
--

LOCK TABLES `shifts` WRITE;
/*!40000 ALTER TABLE `shifts` DISABLE KEYS */;
INSERT INTO `shifts` VALUES (1,'Ca Sáng','07:00:00','11:30:00',1,'2026-09-29 14:02:19','2026-09-29 14:02:19'),(2,'Ca Tối','16:30:00','21:00:00',1,'2026-10-07 13:46:42','2026-10-07 13:46:42');
/*!40000 ALTER TABLE `shifts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sizes`
--

DROP TABLE IF EXISTS `sizes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sizes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `extra_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sizes`
--

LOCK TABLES `sizes` WRITE;
/*!40000 ALTER TABLE `sizes` DISABLE KEYS */;
INSERT INTO `sizes` VALUES (1,'M',5000.00,1,'2026-09-12 07:12:11','2026-09-30 13:58:13'),(2,'L',10000.00,1,'2026-09-12 07:12:11','2026-09-30 13:58:18'),(3,'S',0.00,1,'2026-09-12 07:12:11','2026-10-04 06:04:24'),(4,'XL',20000.00,1,'2026-10-22 15:54:48','2026-10-22 15:54:48');
/*!40000 ALTER TABLE `sizes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tables`
--

DROP TABLE IF EXISTS `tables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tables` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `table_number` varchar(20) NOT NULL,
  `capacity` int(10) unsigned NOT NULL DEFAULT 4,
  `status` enum('available','occupied','reserved') NOT NULL DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `table_number` (`table_number`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tables`
--

LOCK TABLES `tables` WRITE;
/*!40000 ALTER TABLE `tables` DISABLE KEYS */;
INSERT INTO `tables` VALUES (1,'B01',2,'available','2026-09-12 07:12:11','2026-10-04 05:32:56'),(2,'B02',2,'available','2026-09-12 07:12:11','2026-10-04 05:34:08'),(3,'B03',4,'available','2026-09-12 07:12:11','2026-10-21 15:42:39'),(4,'B04',4,'available','2026-09-12 07:12:11','2026-10-04 07:45:20'),(5,'B05',6,'available','2026-09-12 07:12:11','2026-10-04 07:45:24'),(6,'B06',6,'available','2026-09-12 07:12:11','2026-10-04 07:45:32'),(9,'B11',4,'available','2026-10-21 15:35:18','2026-10-21 15:35:18'),(11,'b12',4,'available','2026-10-21 15:39:14','2026-10-21 15:39:14'),(13,'b15',4,'available','2026-10-21 15:40:47','2026-10-21 15:40:47'),(15,'b16',4,'available','2026-10-21 15:48:09','2026-10-21 15:48:09');
/*!40000 ALTER TABLE `tables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `toppings`
--

DROP TABLE IF EXISTS `toppings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `toppings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `toppings`
--

LOCK TABLES `toppings` WRITE;
/*!40000 ALTER TABLE `toppings` DISABLE KEYS */;
INSERT INTO `toppings` VALUES (1,'Trân châu đen',5000.00,1,'2026-09-12 07:12:11','2026-09-30 13:59:09'),(2,'Trân châu trắng',5000.00,1,'2026-09-12 07:12:11','2026-09-30 13:53:13'),(3,'Thạch trái cây',7000.00,1,'2026-09-12 07:12:11','2026-09-12 07:12:11'),(4,'Pudding trứng',8000.00,1,'2026-09-12 07:12:11','2026-09-12 07:12:11'),(5,'Kem cheese',10000.00,1,'2026-09-12 07:12:11','2026-09-12 07:12:11'),(6,'Thạch dừa',5000.00,1,'2026-10-22 15:19:25','2026-10-22 15:19:25');
/*!40000 ALTER TABLE `toppings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('admin','staff') NOT NULL DEFAULT 'staff',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','$2y$10$qnfS3W/zvcjWQsNIFgP1ZeMMCedqPnn2hNRCPJLwcXMak4y6aBuYW','Quản trị viên','admin@coffeeshop.local','0900001234','admin',1,'2026-09-12 07:12:11','2026-09-30 14:10:55'),(2,'staff01','$2y$10$cbC11NEE36hTX1BY/zgRt.tTc7MXPQCnGVcGsyTire1rg2mOiKL6O','Bùi Viết Khánh ','staff01@coffeeshop.local','0900000002','staff',1,'2026-09-12 07:12:11','2026-09-29 14:01:00');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-22 23:41:06
