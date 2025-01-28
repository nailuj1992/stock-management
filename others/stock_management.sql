-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 03, 2024 at 05:12 AM
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
-- Database: `stock_management`
--
CREATE DATABASE IF NOT EXISTS `stock_management` DEFAULT CHARACTER SET utf32 COLLATE utf32_general_ci;
USE `stock_management`;

-- --------------------------------------------------------

--
-- Table structure for table `application_company`
--

CREATE TABLE `application_company` (
  `application_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `status` varchar(1) NOT NULL DEFAULT 'P',
  `comment_user` varchar(500) DEFAULT NULL,
  `comment_company` varchar(500) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `application_company`
--

INSERT INTO `application_company` (`application_id`, `user_id`, `company_id`, `status`, `comment_user`, `comment_company`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(2, 7, 2, 'R', 'looking for a company :3', 'nokas mijo para la proxima :)', 7, '2024-08-20 05:58:41', 6, '2024-08-20 05:58:41'),
(3, 7, 1, 'R', 'please please let me in!!!!', 'You are not valid.', 7, '2024-08-20 06:27:46', 24, '2024-08-20 06:27:46'),
(4, 7, 1, 'A', 'Por favor por favor :(', NULL, 7, '2024-08-20 21:46:14', 24, '2024-08-20 21:46:14'),
(5, 7, 3, 'A', 'otro try', NULL, 7, '2024-08-20 22:09:52', 6, '2024-08-20 22:09:52');

-- --------------------------------------------------------

--
-- Table structure for table `auth_assignment`
--

CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `auth_assignment`
--

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('admin', 1, 1723616190),
('user', 5, 1723706416),
('user', 6, 1723763074),
('user', 7, 1723785323),
('user', 23, 1723851258),
('user', 24, 1723852674),
('user', 25, 1723861721),
('user', 26, 1723866938),
('user', 27, 1723867479);

-- --------------------------------------------------------

--
-- Table structure for table `auth_item`
--

CREATE TABLE `auth_item` (
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `rule_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('admin', 1, NULL, NULL, NULL, 1723616189, 1723616189),
('user', 1, NULL, NULL, NULL, 1723616189, 1723616189);

-- --------------------------------------------------------

--
-- Table structure for table `auth_item_child`
--

CREATE TABLE `auth_item_child` (
  `parent` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `auth_item_child`
--

INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
('admin', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `auth_rule`
--

CREATE TABLE `auth_rule` (
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `data` blob DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `city_id` int(10) UNSIGNED NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `state_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`city_id`, `code`, `name`, `state_id`) VALUES
(1, 'BOG', 'Bogota D.C', 1),
(2, 'CHI', 'Chia', 2),
(3, 'MED', 'Medellin', 3),
(5, 'SLT', 'Saltillo', 9),
(7, 'CJZ', 'Ciudad Juarez', 10),
(8, 'YVR', 'Vancouver', 6),
(9, 'BUR', 'Burnaby', 6),
(10, 'CGR', 'Calgary', 7),
(11, 'BNF', 'Banff', 7),
(12, 'ORL', 'Orlando', 5),
(13, 'MIA', 'Miami', 5),
(14, 'LAX', 'Los Angeles', 8),
(15, 'SFR', 'San Francisco', 8),
(16, 'COT', 'Cota', 2),
(17, 'RNG', 'Rionegro', 3),
(18, 'VLL', 'Villavicencio', 11),
(19, 'NVA', 'Neiva', 12),
(20, 'IBG', 'Ibague', 13),
(21, 'BQL', 'Barranquilla', 14),
(22, 'SMR', 'Santa Marta', 15),
(24, 'ARM', 'Armenia', 17);

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `company_id` int(10) UNSIGNED NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(100) NOT NULL,
  `city` int(10) UNSIGNED NOT NULL,
  `status` varchar(1) NOT NULL DEFAULT 'A',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`company_id`, `code`, `name`, `phone`, `address`, `city`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, '12345678-6', 'Delicium S.A.S', '12456511', 'Calle falsa 123', 22, 'A', 5, '2024-08-16 06:18:03', 5, '2024-08-17 08:22:32'),
(2, '12345234-4', 'MugCup', '6726994543', '480 Robson Street', 8, 'A', 6, '2024-08-16 18:40:44', 6, '2024-08-17 04:17:24'),
(3, '13246452', 'Alfabeticus', '456123053', 'Calle 123 asd', 24, 'A', 6, '2024-08-17 04:03:21', 6, '2024-08-17 13:03:50');

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE `country` (
  `country_id` int(10) UNSIGNED NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`country_id`, `code`, `name`) VALUES
(1, 'COL', 'Colombia'),
(2, 'MEX', 'Mexico'),
(3, 'CAN', 'Canada'),
(5, 'USA', 'United States of America');

-- --------------------------------------------------------

--
-- Table structure for table `document`
--

CREATE TABLE `document` (
  `document_id` int(10) UNSIGNED NOT NULL,
  `code` varchar(5) NOT NULL,
  `name` varchar(50) NOT NULL,
  `intended_for` varchar(1) NOT NULL,
  `apply_for` varchar(1) NOT NULL,
  `has_taxes` varchar(1) NOT NULL,
  `has_expiration` varchar(1) NOT NULL,
  `has_other_transaction` varchar(1) NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `status` varchar(1) NOT NULL DEFAULT 'A',
  `created_by` int(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(10) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

--
-- Dumping data for table `document`
--

INSERT INTO `document` (`document_id`, `code`, `name`, `intended_for`, `apply_for`, `has_taxes`, `has_expiration`, `has_other_transaction`, `company_id`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(2, 'FV', 'Factura de Venta', 'O', 'C', 'Y', 'N', 'N', 1, 'A', 5, '2024-09-01 20:23:36', 5, '2024-09-03 06:22:45'),
(3, 'FC', 'Factura de Compra', 'I', 'S', 'N', 'Y', 'N', 1, 'A', 5, '2024-09-01 20:51:02', 5, '2024-09-02 05:51:18'),
(4, 'NC', 'Nota Credito', 'I', 'C', 'N', 'N', 'Y', 1, 'A', 5, '2024-09-02 18:22:38', 5, '2024-09-02 18:22:38'),
(5, 'ND', 'Nota Debito', 'O', 'S', 'N', 'N', 'Y', 1, 'A', 5, '2024-09-02 18:22:58', 5, '2024-09-02 18:22:58');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(10) UNSIGNED NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `has_existences` varchar(1) NOT NULL,
  `tax_rate` double DEFAULT NULL,
  `minimum_stock` int(11) DEFAULT NULL,
  `sugested_value` int(11) DEFAULT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `status` varchar(1) NOT NULL DEFAULT 'A',
  `created_by` int(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(10) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `code`, `name`, `description`, `has_existences`, `tax_rate`, `minimum_stock`, `sugested_value`, `company_id`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, '12345ASD', 'Chocoramo', 'Producto increiblemente popular y delicioso', 'Y', 19, 10, 2000, 1, 'A', 5, '2024-09-03 00:16:14', 5, '2024-09-03 09:16:40');

-- --------------------------------------------------------

--
-- Table structure for table `state`
--

CREATE TABLE `state` (
  `state_id` int(10) UNSIGNED NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `country_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `state`
--

INSERT INTO `state` (`state_id`, `code`, `name`, `country_id`) VALUES
(1, 'BOG', 'Bogota D.C', 1),
(2, 'CUND', 'Cundinamarca', 1),
(3, 'ANTQ', 'Antioquia', 1),
(5, 'FL', 'Florida', 5),
(6, 'BC', 'British Columbia', 3),
(7, 'ALB', 'Alberta', 3),
(8, 'CA', 'California', 5),
(9, 'COA', 'Coahuila', 2),
(10, 'CHH', 'Chihuahua', 2),
(11, 'MT', 'Meta', 1),
(12, 'HUI', 'Huila', 1),
(13, 'TOL', 'Tolima', 1),
(14, 'ATL', 'Atlantico', 1),
(15, 'MAG', 'Magdalena', 1),
(17, 'QUI', 'Quindio', 1);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplier_id` int(10) UNSIGNED NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(100) NOT NULL,
  `city` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `status` varchar(1) NOT NULL DEFAULT 'A',
  `created_by` int(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(10) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplier_id`, `code`, `name`, `email`, `phone`, `address`, `city`, `company_id`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, '1324561234', 'Proveedor 02', 'supplier2@supplier.com', '1234567845', 'Calle Falsa 456', 13, 1, 'A', 5, '2024-09-03 02:31:15', 5, '2024-09-03 11:33:17'),
(2, '561231256ASD', 'Proveedor 01', 'supplier1@supplier.com', '2051315165', '341 Buchanan Ave', 9, 1, 'A', 5, '2024-09-03 02:33:52', 5, '2024-09-03 02:33:52');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `transaction_id` int(10) UNSIGNED NOT NULL,
  `num_transaction` varchar(20) NOT NULL,
  `document_id` int(10) UNSIGNED NOT NULL,
  `creation_date` date NOT NULL,
  `expiration_date` date DEFAULT NULL,
  `linked_transaction_id` int(10) UNSIGNED DEFAULT NULL,
  `supplier_id` int(10) UNSIGNED DEFAULT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `status` varchar(1) NOT NULL DEFAULT 'A',
  `created_by` int(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(10) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_item`
--

CREATE TABLE `transaction_item` (
  `transaction_item_id` int(10) UNSIGNED NOT NULL,
  `transaction_id` int(10) UNSIGNED NOT NULL,
  `amount` int(11) NOT NULL,
  `unit_value` double NOT NULL,
  `tax_rate` double DEFAULT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `warehouse_id` int(10) UNSIGNED DEFAULT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `status` varchar(1) NOT NULL DEFAULT 'A',
  `created_by` int(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(10) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `auth_key` varchar(70) DEFAULT NULL,
  `access_token` varchar(70) DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(100) NOT NULL,
  `city` int(10) UNSIGNED NOT NULL,
  `status` varchar(1) NOT NULL DEFAULT 'A',
  `created_by` int(11) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) UNSIGNED DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `email`, `password`, `auth_key`, `access_token`, `name`, `phone`, `address`, `city`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'admin@admin.com', '1398b8a4076328726d2802b0c5acfb81c83188a42fe18ed1887a8482d1b0b8a8', NULL, NULL, 'Admin', '12345678', 'Admin address', 1, 'A', 1, '2024-08-14 05:34:25', 1, '2024-08-17 04:06:19'),
(5, 'user@user.com', 'ca0ee3d7b56fbeb00d909bd0bf9c86dbf4f7361ed570d2ee9e7bb670466e994a', NULL, NULL, 'user 1', '1234232342', 'calle user asd123', 17, 'A', 1, '2024-08-15 07:20:16', 5, '2024-08-17 07:34:58'),
(6, 'user2@user.com', 'ca0ee3d7b56fbeb00d909bd0bf9c86dbf4f7361ed570d2ee9e7bb670466e994a', NULL, NULL, 'User two', '433654987', 'fake street 12345', 1, 'A', 1, '2024-08-15 23:04:34', 6, '2024-08-17 05:26:35'),
(7, 'user3@user.com', 'ca0ee3d7b56fbeb00d909bd0bf9c86dbf4f7361ed570d2ee9e7bb670466e994a', NULL, NULL, 'User 3', '12344433', 'fake street 456', 5, 'A', 1, '2024-08-16 05:15:23', 7, '2024-08-16 14:15:57'),
(23, 'user4@user.com', 'ca0ee3d7b56fbeb00d909bd0bf9c86dbf4f7361ed570d2ee9e7bb670466e994a', NULL, NULL, 'user # four', '213424546754', '341 Buchanan Ave', 12, 'A', 5, '2024-08-16 23:34:18', 23, '2024-08-21 04:00:17'),
(24, 'user5@user.com', 'ca0ee3d7b56fbeb00d909bd0bf9c86dbf4f7361ed570d2ee9e7bb670466e994a', NULL, NULL, 'User 5', '234534213', 'fake street 123', 20, 'A', 23, '2024-08-16 23:57:54', 24, '2024-08-17 09:02:05'),
(25, 'user6@user.com', 'ca0ee3d7b56fbeb00d909bd0bf9c86dbf4f7361ed570d2ee9e7bb670466e994a', NULL, NULL, 'User six', '34332324', 'fake street 123', 19, 'A', 24, '2024-08-17 02:28:41', 25, '2024-08-21 04:32:50'),
(26, 'user7@user.com', 'ca0ee3d7b56fbeb00d909bd0bf9c86dbf4f7361ed570d2ee9e7bb670466e994a', NULL, NULL, 'User 7', '1234443321', 'fake street 123', 15, 'A', 1, '2024-08-17 03:55:37', 26, '2024-08-17 12:55:59'),
(27, 'user8@user.com', 'ca0ee3d7b56fbeb00d909bd0bf9c86dbf4f7361ed570d2ee9e7bb670466e994a', NULL, NULL, 'Usuario Ocho', '541201133', 'algo 123 # 456 - 78', 11, 'A', 6, '2024-08-17 04:04:39', 27, '2024-08-17 13:16:24');

-- --------------------------------------------------------

--
-- Table structure for table `user_company`
--

CREATE TABLE `user_company` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `role` varchar(1) NOT NULL,
  `status` varchar(1) NOT NULL DEFAULT 'A',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_company`
--

INSERT INTO `user_company` (`user_id`, `company_id`, `role`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(5, 1, 'O', 'A', 5, '2024-08-16 06:18:03', 5, '2024-08-16 06:18:03'),
(6, 2, 'O', 'A', 6, '2024-08-16 18:40:44', 6, '2024-08-16 18:40:44'),
(6, 3, 'O', 'A', 6, '2024-08-17 04:03:21', 6, '2024-08-17 04:03:21'),
(7, 1, 'M', 'A', 24, '2024-08-20 21:48:35', 24, '2024-08-20 21:48:35'),
(7, 3, 'M', 'A', 6, '2024-08-20 22:13:37', 6, '2024-08-20 22:13:37'),
(23, 1, 'S', 'I', 5, '2024-08-16 23:34:18', 5, '2024-08-16 23:34:18'),
(24, 1, 'S', 'A', 23, '2024-08-16 23:57:54', 23, '2024-08-16 23:57:54'),
(25, 1, 'S', 'I', 24, '2024-08-17 02:28:41', 24, '2024-08-17 02:28:41'),
(27, 3, 'S', 'A', 6, '2024-08-17 04:04:39', 6, '2024-08-17 04:04:39');

-- --------------------------------------------------------

--
-- Table structure for table `warehouse`
--

CREATE TABLE `warehouse` (
  `warehouse_id` int(10) UNSIGNED NOT NULL,
  `code` varchar(5) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `city` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `status` varchar(1) NOT NULL DEFAULT 'A',
  `created_by` int(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(10) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

--
-- Dumping data for table `warehouse`
--

INSERT INTO `warehouse` (`warehouse_id`, `code`, `name`, `address`, `city`, `company_id`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'BD01', 'Bodega 01', 'Calle falsa 123', 17, 1, 'A', 5, '2024-09-03 01:32:29', 5, '2024-09-03 10:40:33'),
(2, 'BD02', 'Bodega 02', 'Calle falsa 456', 2, 1, 'A', 5, '2024-09-03 01:36:16', 5, '2024-09-03 01:36:16'),
(3, 'BD03', 'Bodega 03', 'Calle falsa 789', 7, 1, 'A', 5, '2024-09-03 01:37:09', 5, '2024-09-03 10:43:40'),
(4, 'BD04', 'Bodega 04', '480 Robson Street', 8, 1, 'A', 5, '2024-09-03 01:56:45', 5, '2024-09-03 10:57:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `application_company`
--
ALTER TABLE `application_company`
  ADD PRIMARY KEY (`application_id`),
  ADD KEY `fk_apply_user_idx` (`user_id`),
  ADD KEY `fk_apply_company_idx` (`company_id`);

--
-- Indexes for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD PRIMARY KEY (`item_name`,`user_id`),
  ADD KEY `idx-auth_assignment-user_id` (`user_id`);

--
-- Indexes for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD PRIMARY KEY (`name`),
  ADD KEY `rule_name` (`rule_name`),
  ADD KEY `idx-auth_item-type` (`type`);

--
-- Indexes for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `child` (`child`);

--
-- Indexes for table `auth_rule`
--
ALTER TABLE `auth_rule`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`city_id`),
  ADD UNIQUE KEY `code_UNIQUE` (`code`),
  ADD KEY `fk_city_state_idx` (`state_id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`company_id`),
  ADD UNIQUE KEY `code_UNIQUE` (`code`),
  ADD KEY `fk_company_city_idx` (`city`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`country_id`),
  ADD UNIQUE KEY `code_UNIQUE` (`code`);

--
-- Indexes for table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`document_id`),
  ADD UNIQUE KEY `code_UNIQUE` (`code`,`company_id`),
  ADD KEY `fk_document_company_idx` (`company_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `code_UNIQUE` (`code`,`company_id`),
  ADD KEY `fk_product_company_idx` (`company_id`);

--
-- Indexes for table `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`state_id`),
  ADD UNIQUE KEY `code_UNIQUE` (`code`),
  ADD KEY `fk_state_country_idx` (`country_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplier_id`),
  ADD UNIQUE KEY `code_UNIQUE` (`code`,`company_id`),
  ADD KEY `fk_supplier_company_idx` (`company_id`),
  ADD KEY `fk_supplier_city_idx` (`city`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`transaction_id`),
  ADD UNIQUE KEY `num_transaction_UNIQUE` (`num_transaction`,`document_id`),
  ADD KEY `fk_transaction_document_idx` (`document_id`),
  ADD KEY `fk_transaction_supplier_idx` (`supplier_id`),
  ADD KEY `fk_transaction_company_idx` (`company_id`),
  ADD KEY `fk_transaction_linked_idx` (`linked_transaction_id`);

--
-- Indexes for table `transaction_item`
--
ALTER TABLE `transaction_item`
  ADD PRIMARY KEY (`transaction_item_id`),
  ADD KEY `fk_trans_item_product_idx` (`product_id`),
  ADD KEY `fk_trans_item_warehouse_idx` (`warehouse_id`),
  ADD KEY `fk_trans_item_company_idx` (`company_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD KEY `fk_user_city_idx` (`city`);

--
-- Indexes for table `user_company`
--
ALTER TABLE `user_company`
  ADD PRIMARY KEY (`user_id`,`company_id`),
  ADD KEY `fk_user_company_2_idx` (`company_id`);

--
-- Indexes for table `warehouse`
--
ALTER TABLE `warehouse`
  ADD PRIMARY KEY (`warehouse_id`),
  ADD UNIQUE KEY `code_UNIQUE` (`code`,`company_id`),
  ADD KEY `fk_warehouse_company_idx` (`company_id`),
  ADD KEY `fk_warehouse_city_idx` (`city`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `application_company`
--
ALTER TABLE `application_company`
  MODIFY `application_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `city_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `company_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `country`
--
ALTER TABLE `country`
  MODIFY `country_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `document`
--
ALTER TABLE `document`
  MODIFY `document_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `state`
--
ALTER TABLE `state`
  MODIFY `state_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `transaction_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction_item`
--
ALTER TABLE `transaction_item`
  MODIFY `transaction_item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `warehouse`
--
ALTER TABLE `warehouse`
  MODIFY `warehouse_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `application_company`
--
ALTER TABLE `application_company`
  ADD CONSTRAINT `fk_apply_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_apply_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_assignment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `city`
--
ALTER TABLE `city`
  ADD CONSTRAINT `fk_city_state` FOREIGN KEY (`state_id`) REFERENCES `state` (`state_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `company`
--
ALTER TABLE `company`
  ADD CONSTRAINT `fk_company_city` FOREIGN KEY (`city`) REFERENCES `city` (`city_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `document`
--
ALTER TABLE `document`
  ADD CONSTRAINT `fk_document_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_product_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `state`
--
ALTER TABLE `state`
  ADD CONSTRAINT `fk_state_country` FOREIGN KEY (`country_id`) REFERENCES `country` (`country_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `supplier`
--
ALTER TABLE `supplier`
  ADD CONSTRAINT `fk_supplier_city` FOREIGN KEY (`city`) REFERENCES `city` (`city_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_supplier_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `fk_transaction_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_transaction_document` FOREIGN KEY (`document_id`) REFERENCES `document` (`document_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_transaction_linked` FOREIGN KEY (`linked_transaction_id`) REFERENCES `transaction` (`transaction_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_transaction_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `transaction_item`
--
ALTER TABLE `transaction_item`
  ADD CONSTRAINT `fk_trans_item_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_trans_item_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_trans_item_warehouse` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouse` (`warehouse_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_city` FOREIGN KEY (`city`) REFERENCES `city` (`city_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_company`
--
ALTER TABLE `user_company`
  ADD CONSTRAINT `fk_user_company_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_company_2` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `warehouse`
--
ALTER TABLE `warehouse`
  ADD CONSTRAINT `fk_warehouse_city` FOREIGN KEY (`city`) REFERENCES `city` (`city_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_warehouse_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
