-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 22, 2025 at 05:15 PM
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
(5, 7, 3, 'A', 'otro try', NULL, 7, '2024-08-20 22:09:52', 6, '2024-08-20 22:09:52'),
(6, 24, 2, 'R', 'Algo 1', 'algo', 24, '2025-02-18 15:47:12', 6, '2025-02-18 15:47:12'),
(7, 24, 3, 'P', 'Algo 2', NULL, 24, '2025-02-18 15:47:23', 24, '2025-02-18 15:47:23'),
(8, 6, 1, 'P', 'qwe 123', NULL, 6, '2025-02-19 00:01:07', 6, '2025-02-19 00:01:07');

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
(4, 'DC', 'Devoluciones Cliente', 'I', 'C', 'N', 'N', 'Y', 1, 'A', 5, '2024-09-02 18:22:38', 5, '2025-02-18 06:34:27'),
(5, 'DP', 'Devoluciones Proveedor', 'O', 'S', 'N', 'N', 'Y', 1, 'A', 5, '2024-09-02 18:22:58', 5, '2025-02-18 06:34:46');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `language` varchar(16) NOT NULL,
  `translation` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `language`, `translation`) VALUES
(1, 'en-US', 'Welcome back {name}!'),
(1, 'es-CO', '¡Bienvenido, {name}!'),
(2, 'en-US', 'We are ready to begin our tasks.'),
(2, 'es-CO', 'Estamos listos para iniciar con lo nuestro.'),
(3, 'en-US', 'Create your first company!'),
(3, 'es-CO', '¡Crea tu primera empresa!'),
(4, 'en-US', 'Or you can apply to enter into a company'),
(4, 'es-CO', '¡O puedes aplicar para ingresar en otra empresa'),
(5, 'en-US', 'over here!'),
(5, 'es-CO', 'por aquí!'),
(6, 'en-US', 'It seems you have applied for any company. Check your status'),
(6, 'es-CO', 'Parece que ya has aplicado para alguna empresa. Revisa su estado'),
(7, 'en-US', 'over here.'),
(7, 'es-CO', 'por aquí.'),
(8, 'en-US', 'You should change your password to activate your account.'),
(8, 'es-CO', 'Debes cambiar tu contraseña para activar tu cuenta.'),
(9, 'en-US', 'It seems that you are {status}'),
(9, 'es-CO', 'Parece que estás {status}'),
(10, 'en-US', 'Active'),
(10, 'es-CO', 'Activo'),
(11, 'en-US', 'Inactive'),
(11, 'es-CO', 'Inactivo'),
(12, 'en-US', 'Nulled'),
(12, 'es-CO', 'Anulado'),
(13, 'en-US', 'Draft'),
(13, 'es-CO', 'Borrador'),
(14, 'en-US', 'Companies'),
(14, 'es-CO', 'Empresas'),
(15, 'en-US', 'Here, you can view the companies assigned to you.'),
(15, 'es-CO', 'Aquí puedes ver las empresas que tienes asignadas.'),
(16, 'en-US', 'You can select a company in this section.'),
(16, 'es-CO', 'Puedes seleccionar una empresa en esta sección.'),
(17, 'en-US', 'Continue {symbol}'),
(17, 'es-CO', 'Continuar {symbol}'),
(18, 'en-US', 'You should select a company to continue.'),
(18, 'es-CO', 'Debes seleccionar una empresa para continuar.'),
(19, 'en-US', 'Selected Company:'),
(19, 'es-CO', 'Empresa seleccionada:'),
(20, 'en-US', 'Documents'),
(20, 'es-CO', 'Documentos'),
(21, 'en-US', 'Here, you can view the types of documents used in your company.'),
(21, 'es-CO', 'Aquí puedes ver los tipos de documentos utilizados en tu empresa.'),
(22, 'en-US', 'Products'),
(22, 'es-CO', 'Productos'),
(23, 'en-US', 'Here, you can view the products available in your company.'),
(23, 'es-CO', 'Aquí puedes ver los productos disponibles en tu empresa.'),
(24, 'en-US', 'Warehouses'),
(24, 'es-CO', 'Bodegas'),
(25, 'en-US', 'Here, you can explore the warehouses within your company.'),
(25, 'es-CO', 'Aquí puedes explorar las bodegas de tu empresa.'),
(26, 'en-US', 'Suppliers'),
(26, 'es-CO', 'Proveedores'),
(27, 'en-US', 'Here, you can explore your company`s suppliers.'),
(27, 'es-CO', 'Aquí puedes explorar los proveedores de tu empresa.'),
(28, 'en-US', 'Transactions'),
(28, 'es-CO', 'Transacciones'),
(29, 'en-US', 'Here, you can view your company`s transactions.'),
(29, 'es-CO', 'Aquí puedes ver las transacciones de tu empresa.'),
(30, 'en-US', 'Existences'),
(30, 'es-CO', 'Existencias'),
(31, 'en-US', 'Here, you can oversee and manage your product inventory.'),
(31, 'es-CO', 'Aquí puedes supervisar y gestionar el inventario de productos.'),
(32, 'en-US', 'Kardex'),
(32, 'es-CO', 'Kárdex'),
(33, 'en-US', 'Here, you can oversee and manage the Kardex records of your product inventory.'),
(33, 'es-CO', 'Aquí puedes supervisar y gestionar los registros Kárdex de tu inventario de productos.'),
(34, 'en-US', 'Companies'),
(34, 'es-CO', 'Empresas'),
(35, 'en-US', 'These are the companies where you are included.'),
(35, 'es-CO', 'Estas son las empresas en las que formas parte.'),
(36, 'en-US', 'Create Company'),
(36, 'es-CO', 'Crear Empresa'),
(37, 'en-US', 'Information for company: {name}'),
(37, 'es-CO', 'Información de la empresa: {name}'),
(38, 'en-US', 'Select'),
(38, 'es-CO', 'Seleccionar'),
(39, 'en-US', 'View'),
(39, 'es-CO', 'Ver'),
(40, 'en-US', 'Update'),
(40, 'es-CO', 'Modificar'),
(41, 'en-US', 'List Users'),
(41, 'es-CO', 'Ver Usuarios'),
(42, 'en-US', 'Create User'),
(42, 'es-CO', 'Crear Usuario'),
(43, 'en-US', 'Are you sure you want to select the company {name}?'),
(43, 'es-CO', '¿Estás seguro de que de quieres seleccionar la empresa {name}?'),
(44, 'en-US', 'Other companies to apply'),
(44, 'es-CO', 'Otras empresas para postular'),
(45, 'en-US', 'These are the companies where you can apply to enter.'),
(45, 'es-CO', 'Estas son las empresas a las que puedes postularte para ingresar.'),
(46, 'en-US', 'Create'),
(46, 'es-CO', 'Crear'),
(47, 'en-US', 'Show Applications'),
(47, 'es-CO', 'Mostrar Postulaciones'),
(48, 'en-US', 'Company'),
(48, 'es-CO', 'Empresa'),
(49, 'en-US', 'ID'),
(49, 'es-CO', 'NIT'),
(50, 'en-US', 'Name'),
(50, 'es-CO', 'Nombre'),
(51, 'en-US', 'Phone'),
(51, 'es-CO', 'Teléfono'),
(52, 'en-US', 'Address'),
(52, 'es-CO', 'Dirección'),
(53, 'en-US', 'City'),
(53, 'es-CO', 'Ciudad'),
(54, 'en-US', 'Code'),
(54, 'es-CO', 'Código'),
(55, 'en-US', 'State'),
(55, 'es-CO', 'Departamento'),
(56, 'en-US', 'Country'),
(56, 'es-CO', 'País'),
(57, 'en-US', 'Status'),
(57, 'es-CO', 'Estado'),
(58, 'en-US', 'Created By'),
(58, 'es-CO', 'Creado Por'),
(59, 'en-US', 'Created At'),
(59, 'es-CO', 'Creado En'),
(60, 'en-US', 'Updated By'),
(60, 'es-CO', 'Modificado Por'),
(61, 'en-US', 'Updated At'),
(61, 'es-CO', 'Modificado En'),
(62, 'en-US', 'Company Status'),
(62, 'es-CO', 'Estado de la Empresa'),
(63, 'en-US', 'Your Status'),
(63, 'es-CO', 'Tu Estado'),
(64, 'en-US', 'Position'),
(64, 'es-CO', 'Posición'),
(65, 'en-US', 'Are you sure you want to delete this item?'),
(65, 'es-CO', '¿Estás seguro de que quieres eliminar este elemento?'),
(66, 'en-US', 'Save'),
(66, 'es-CO', 'Guardar'),
(67, 'en-US', 'Select...'),
(67, 'es-CO', 'Seleccionar...'),
(68, 'en-US', 'Update Company: {name}'),
(68, 'es-CO', 'Modificar Empresa: {name}'),
(69, 'en-US', 'User'),
(69, 'es-CO', 'Usuario'),
(70, 'en-US', 'Email'),
(70, 'es-CO', 'Correo'),
(71, 'en-US', 'Password'),
(71, 'es-CO', 'Contraseña'),
(72, 'en-US', 'Re-Password'),
(72, 'es-CO', 'Repetir Contraseña'),
(73, 'en-US', 'Role in the Company'),
(73, 'es-CO', 'Rol en la Empresa'),
(74, 'en-US', 'Please provide digits only no more than 13.'),
(74, 'es-CO', 'Por favor, ingresa solo dígitos, no más de 13.'),
(75, 'en-US', 'New password must contain at least one lower- and upper-case character and a digit.'),
(75, 'es-CO', 'La nueva contraseña debe contener al menos una letra minúscula, una mayúscula y un dígito.'),
(76, 'en-US', 'Passwords don`t match.'),
(76, 'es-CO', 'Las contraseñas no coinciden.'),
(77, 'en-US', 'Current Users'),
(77, 'es-CO', 'Usuarios Actuales'),
(78, 'en-US', 'Create User'),
(78, 'es-CO', 'Crear Usuario'),
(79, 'en-US', 'User Status'),
(79, 'es-CO', 'Estado del Usuario'),
(80, 'en-US', 'Pending Applications'),
(80, 'es-CO', 'Postulaciones Pendientes'),
(81, 'en-US', 'Status in Company'),
(81, 'es-CO', 'Estado en la Empresa'),
(82, 'en-US', 'Owner'),
(82, 'es-CO', 'Propietario'),
(83, 'en-US', 'Supervisor'),
(83, 'es-CO', 'Supervisor'),
(84, 'en-US', 'Member'),
(84, 'es-CO', 'Miembro'),
(85, 'en-US', 'Promote'),
(85, 'es-CO', 'Ascender'),
(86, 'en-US', 'Are you sure you want to promote the user {name} to {role}?'),
(86, 'es-CO', '¿Estás seguro de que quieres ascender al usuario {name} como {role}?'),
(87, 'en-US', 'Demote'),
(87, 'es-CO', 'Degradar'),
(88, 'en-US', 'Are you sure you want to demote the user {name} to {role}?'),
(88, 'es-CO', '¿Estás seguro de que quieres degradar al usuario {name} como {role}?'),
(89, 'en-US', 'Deactivate'),
(89, 'es-CO', 'Desactivar'),
(90, 'en-US', 'Are you sure you want to deactivate the user {name}?'),
(90, 'es-CO', '¿Estás seguro de que quieres desactivar al usuario {name}?'),
(91, 'en-US', 'Activate'),
(91, 'es-CO', 'Activar'),
(92, 'en-US', 'Are you sure you want to activate the user {name}?'),
(92, 'es-CO', '¿Estás seguro de que quieres activar al usuario {name}?'),
(93, 'en-US', 'Promote to Supervisor'),
(93, 'es-CO', 'Ascender como Supervisor'),
(94, 'en-US', 'Demote to Member'),
(94, 'es-CO', 'Degradar como Miembro'),
(95, 'en-US', 'Approved'),
(95, 'es-CO', 'Aprobado'),
(96, 'en-US', 'Rejected'),
(96, 'es-CO', 'Rechazado'),
(97, 'en-US', 'Pending'),
(97, 'es-CO', 'Pendiente'),
(98, 'en-US', 'Approve'),
(98, 'es-CO', 'Aprobar'),
(99, 'en-US', 'Are you sure you want to approve this application for the user {name}?'),
(99, 'es-CO', '¿Estás seguro de que quieres aprobar esta solicitud para el usuario {name}?'),
(100, 'en-US', 'Deny'),
(100, 'es-CO', 'Denegar'),
(101, 'en-US', 'Application'),
(101, 'es-CO', 'Postulación'),
(102, 'en-US', 'Comment from the Applicant'),
(102, 'es-CO', 'Comentario del Postulante'),
(103, 'en-US', 'Feedback from the Reviewer'),
(103, 'es-CO', 'Retroalimentación del Revisor'),
(104, 'en-US', 'Deny Application'),
(104, 'es-CO', 'Denegar Postulación'),
(105, 'en-US', 'You should leave a feedback for the applicant.'),
(105, 'es-CO', 'Debes proporcionar retroalimentación para el postulante.'),
(106, 'en-US', 'You should leave a comment for the company.'),
(106, 'es-CO', 'Debes dejar un comentario para la empresa.'),
(107, 'en-US', 'Applications'),
(107, 'es-CO', 'Postulaciones'),
(108, 'en-US', 'Create Application'),
(108, 'es-CO', 'Crear Postulación'),
(109, 'en-US', 'View Application for {name}'),
(109, 'es-CO', 'Ver Postulación para {name}'),
(110, 'en-US', 'Sent At'),
(110, 'es-CO', 'Enviado En'),
(111, 'en-US', 'View Application of {name}'),
(111, 'es-CO', 'Ver Postulación de {name}'),
(112, 'en-US', 'You cannot perform this action while you appear as a non-active user.'),
(112, 'es-CO', 'No puedes realizar esta acción mientras apareces como un usuario no activo.'),
(113, 'en-US', 'You do not have permission to visit this page.'),
(113, 'es-CO', 'No tienes permiso para visitar esta página.'),
(114, 'en-US', 'The requested page does not exist.'),
(114, 'es-CO', 'La página solicitada no existe.'),
(115, 'en-US', 'Incorrect email or password.'),
(115, 'es-CO', 'Correo o contraseña incorrectos.'),
(116, 'en-US', 'The owner of a company cannot be demoted.'),
(116, 'es-CO', 'El propietario de una empresa no puede ser degradado.'),
(117, 'en-US', 'You cannot promote nor demote inactive users.'),
(117, 'es-CO', 'No puedes ascender ni degradar a usuarios inactivos.'),
(118, 'en-US', 'You cannot activate nor deactivate yourself.'),
(118, 'es-CO', 'No puedes activarte ni desactivarte a ti mismo.'),
(119, 'en-US', 'The owner of a company cannot be activated or deactivated.'),
(119, 'es-CO', 'El propietario de una empresa no puede ser activado ni desactivado.'),
(120, 'en-US', 'You cannot activate nor deactivate other supervisors.'),
(120, 'es-CO', 'No puedes activar ni desactivar a otros supervisores.'),
(121, 'en-US', 'You cannot create a new application for this company while you have another pending one.'),
(121, 'es-CO', 'No puedes crear una nueva solicitud para esta empresa mientras tengas otra pendiente.'),
(122, 'en-US', 'That information does not belong to your selected company.'),
(122, 'es-CO', 'Esa información no pertenece a tu empresa seleccionada.'),
(123, 'en-US', 'You can only delete a draft transaction.'),
(123, 'es-CO', 'Solo puedes eliminar una transacción en borrador.'),
(124, 'en-US', 'Input'),
(124, 'es-CO', 'Entrada'),
(125, 'en-US', 'Output'),
(125, 'es-CO', 'Salida'),
(126, 'en-US', 'Supplier'),
(126, 'es-CO', 'Proveedor'),
(127, 'en-US', 'Customer'),
(127, 'es-CO', 'Cliente'),
(128, 'en-US', 'Yes'),
(128, 'es-CO', 'Sí'),
(129, 'en-US', 'No'),
(129, 'es-CO', 'No'),
(130, 'en-US', 'All'),
(130, 'es-CO', 'Todo'),
(131, 'en-US', 'Supplier'),
(131, 'es-CO', 'Proveedor'),
(132, 'en-US', 'Warehouse'),
(132, 'es-CO', 'Bodega'),
(133, 'en-US', 'Document'),
(133, 'es-CO', 'Documento'),
(134, 'en-US', 'Product'),
(134, 'es-CO', 'Producto'),
(135, 'en-US', 'Description'),
(135, 'es-CO', 'Descripción'),
(136, 'en-US', 'Has Existences?'),
(136, 'es-CO', '¿Tiene existencias?'),
(137, 'en-US', 'Tax Rate'),
(137, 'es-CO', 'Tasa de IVA'),
(138, 'en-US', 'Discount Rate'),
(138, 'es-CO', 'Tasa de descuento'),
(139, 'en-US', 'Minimum Stock'),
(139, 'es-CO', 'Stock mínimo'),
(140, 'en-US', 'Suggested Value'),
(140, 'es-CO', 'Valor sugerido'),
(141, 'en-US', 'Intended For'),
(141, 'es-CO', 'Destinado para'),
(142, 'en-US', 'Applies For'),
(142, 'es-CO', 'Aplica para'),
(143, 'en-US', 'Has Taxes?'),
(143, 'es-CO', '¿Tiene IVA?'),
(144, 'en-US', 'Has Expiration?'),
(144, 'es-CO', '¿Tiene fecha de expiración?'),
(145, 'en-US', 'Applies over other transaction?'),
(145, 'es-CO', '¿Aplica sobre otra transacción?'),
(146, 'en-US', 'Are you sure you want to deactivate the document {code}-{name}?'),
(146, 'es-CO', '¿Estás seguro de que quieres desactivar el documento {code}-{name}?'),
(147, 'en-US', 'Are you sure you want to activate the document {code}-{name}?'),
(147, 'es-CO', '¿Estás seguro de que quieres activar el documento {code}-{name}?'),
(148, 'en-US', 'Are you sure you want to deactivate the product {code}-{name}?'),
(148, 'es-CO', '¿Estás seguro de que quieres desactivar el producto {code}-{name}?'),
(149, 'en-US', 'Are you sure you want to activate the product {code}-{name}?'),
(149, 'es-CO', '¿Estás seguro de que quieres activar el producto {code}-{name}?'),
(150, 'en-US', 'Are you sure you want to deactivate the supplier {code}-{name}?'),
(150, 'es-CO', '¿Estás seguro de que quieres desactivar el proveedor {code}-{name}?'),
(151, 'en-US', 'Are you sure you want to activate the supplier {code}-{name}?'),
(151, 'es-CO', '¿Estás seguro de que quieres activar el proveedor {code}-{name}?'),
(152, 'en-US', 'Are you sure you want to deactivate the warehouse {code}-{name}?'),
(152, 'es-CO', '¿Estás seguro de que quieres desactivar la bodega {code}-{name}?'),
(153, 'en-US', 'Are you sure you want to activate the warehouse {code}-{name}?'),
(153, 'es-CO', '¿Estás seguro de que quieres activar la bodega {code}-{name}?'),
(154, 'en-US', 'Create Document'),
(154, 'es-CO', 'Crear Documento'),
(155, 'en-US', 'Update Document: {name}'),
(155, 'es-CO', 'Modificar Documento: {name}'),
(156, 'en-US', 'Create Product'),
(156, 'es-CO', 'Crear Producto'),
(157, 'en-US', 'Update Product: {name}'),
(157, 'es-CO', 'Modificar Producto: {name}'),
(158, 'en-US', 'Create Supplier'),
(158, 'es-CO', 'Crear Proveedor'),
(159, 'en-US', 'Update Supplier: {name}'),
(159, 'es-CO', 'Modificar Proveedor: {name}'),
(160, 'en-US', 'Create Warehouse'),
(160, 'es-CO', 'Crear Bodega'),
(161, 'en-US', 'Update Warehouse: {name}'),
(161, 'es-CO', 'Modificar Bodega: {name}'),
(162, 'en-US', 'Delete'),
(162, 'es-CO', 'Eliminar'),
(163, 'en-US', 'Cities'),
(163, 'es-CO', 'Ciudades'),
(164, 'en-US', 'Create City'),
(164, 'es-CO', 'Crear Ciudad'),
(165, 'en-US', 'Update City: {name}'),
(165, 'es-CO', 'Modificar Ciudad: {name}'),
(166, 'en-US', 'States'),
(166, 'es-CO', 'Departamentos'),
(167, 'en-US', 'Create State'),
(167, 'es-CO', 'Crear Departamento'),
(168, 'en-US', 'Update State: {name}'),
(168, 'es-CO', 'Modificar Departamento: {name}'),
(169, 'en-US', 'Countries'),
(169, 'es-CO', 'Países'),
(170, 'en-US', 'Create Country'),
(170, 'es-CO', 'Crear País'),
(171, 'en-US', 'Update Country: {name}'),
(171, 'es-CO', 'Modificar País: {name}'),
(172, 'en-US', 'City Information: {name}'),
(172, 'es-CO', 'Información de la Ciudad: {name}'),
(173, 'en-US', 'State Information: {name}'),
(173, 'es-CO', 'Información del Departamento: {name}'),
(174, 'en-US', 'Country Information: {name}'),
(174, 'es-CO', 'Información del País: {name}'),
(175, 'en-US', 'Panel to manage the list of countries.'),
(175, 'es-CO', 'Panel para gestionar la lista de países.'),
(176, 'en-US', 'Panel to manage the list of states.'),
(176, 'es-CO', 'Panel para gestionar la lista de departamentos.'),
(177, 'en-US', 'Panel to manage the list of cities.'),
(177, 'es-CO', 'Panel para gestionar la lista de ciudades.'),
(178, 'en-US', 'Administrator Control Panel'),
(178, 'es-CO', 'Panel de Control del Administrador'),
(179, 'en-US', 'Be careful of what you are going to do.'),
(179, 'es-CO', 'Ten cuidado con lo que vas a hacer.'),
(180, 'en-US', 'The above error occurred while the Web server was processing your request.'),
(180, 'es-CO', 'El error anterior ocurrió mientras el servidor web procesaba tu solicitud.'),
(181, 'en-US', 'Please contact us if you think this is a server error. Thank you.'),
(181, 'es-CO', 'Por favor, contáctanos si crees que se trata de un error del servidor. Gracias.'),
(182, 'en-US', 'Login'),
(182, 'es-CO', 'Iniciar sesión'),
(183, 'en-US', 'Logout'),
(183, 'es-CO', 'Cerrar sesión'),
(184, 'en-US', 'Sign Up'),
(184, 'es-CO', 'Registrarse'),
(185, 'en-US', 'Please fill out the following fields to login:'),
(185, 'es-CO', 'Por favor, completa los siguientes campos para iniciar sesión:'),
(186, 'en-US', 'Change Password'),
(186, 'es-CO', 'Cambiar contraseña'),
(187, 'en-US', 'Settings'),
(187, 'es-CO', 'Configuración'),
(188, 'en-US', 'Update Information'),
(188, 'es-CO', 'Actualizar Información'),
(189, 'en-US', 'Profile ({name})'),
(189, 'es-CO', 'Perfil ({name})'),
(190, 'en-US', 'Remember Me'),
(190, 'es-CO', 'Recuérdame'),
(191, 'en-US', 'Home'),
(191, 'es-CO', 'Inicio'),
(192, 'en-US', 'This field is required.'),
(192, 'es-CO', 'Este campo es obligatorio.'),
(193, 'en-US', 'Expiration date should be later or equals to Creation date.'),
(193, 'es-CO', 'La fecha de expiración debe ser posterior o igual a la fecha de creación.'),
(194, 'en-US', 'It should only be one product with its warehouse on this document.'),
(194, 'es-CO', 'Debe haber solo un producto con su respectiva bodega en este documento.'),
(195, 'en-US', 'Amount is below the minimum stock.'),
(195, 'es-CO', 'La cantidad está por debajo del stock mínimo.'),
(196, 'en-US', 'Transaction'),
(196, 'es-CO', 'Transacción'),
(197, 'en-US', 'Item'),
(197, 'es-CO', 'Elemento'),
(198, 'en-US', 'Creation Date'),
(198, 'es-CO', 'Fecha de Creación'),
(199, 'en-US', 'Expiration Date'),
(199, 'es-CO', 'Fecha de Expiración'),
(200, 'en-US', 'Linked Transaction'),
(200, 'es-CO', 'Transacción Referenciada'),
(201, 'en-US', 'Amount'),
(201, 'es-CO', 'Cantidad'),
(202, 'en-US', 'Unit Value'),
(202, 'es-CO', 'Valor Unitario'),
(203, 'en-US', 'Total Value'),
(203, 'es-CO', 'Valor Total'),
(204, 'en-US', 'Subtotal'),
(204, 'es-CO', 'Subtotal'),
(205, 'en-US', 'Taxes'),
(205, 'es-CO', 'IVA'),
(206, 'en-US', 'Total'),
(206, 'es-CO', 'Total'),
(207, 'en-US', 'Cut-off Date'),
(207, 'es-CO', 'Fecha de Corte'),
(208, 'en-US', 'Amount Input'),
(208, 'es-CO', 'Cantidad de Entrada'),
(209, 'en-US', 'Amount Output'),
(209, 'es-CO', 'Cantidad de Salida'),
(210, 'en-US', '# Transaction'),
(210, 'es-CO', '# Transacción'),
(211, 'en-US', 'Available'),
(211, 'es-CO', 'Disponible'),
(212, 'en-US', 'Empty'),
(212, 'es-CO', 'Vacío'),
(213, 'en-US', 'Date'),
(213, 'es-CO', 'Fecha'),
(214, 'en-US', 'Value Input'),
(214, 'es-CO', 'Valor de Entrada'),
(215, 'en-US', 'Value Output'),
(215, 'es-CO', 'Valor de Salida'),
(216, 'en-US', 'Available Amount'),
(216, 'es-CO', 'Cantidad Disponible'),
(217, 'en-US', 'Available Balance'),
(217, 'es-CO', 'Saldo Disponible'),
(218, 'en-US', 'Continue'),
(218, 'es-CO', 'Continuar'),
(219, 'en-US', 'Search'),
(219, 'es-CO', 'Buscar'),
(220, 'en-US', 'Are you sure you want to delete the transaction {code}-{name}?'),
(220, 'es-CO', '¿Estás seguro de que quieres eliminar la transacción {code}-{name}?'),
(221, 'en-US', 'Create Transaction'),
(221, 'es-CO', 'Crear Transacción'),
(222, 'en-US', 'Draft Transaction: {name}'),
(222, 'es-CO', 'Transacción en Borrador: {name}'),
(223, 'en-US', 'View Transactions'),
(223, 'es-CO', 'Ver Transacciones'),
(224, 'en-US', 'Draft: {name}'),
(224, 'es-CO', 'Borrador: {name}'),
(225, 'en-US', 'Drafts'),
(225, 'es-CO', 'Borradores'),
(226, 'en-US', 'Saved Transactions'),
(226, 'es-CO', 'Transacciones Guardadas'),
(227, 'en-US', 'Transaction: {name}'),
(227, 'es-CO', 'Transacción: {name}'),
(228, 'en-US', 'Add more'),
(228, 'es-CO', 'Añadir más'),
(229, 'en-US', 'Final date should be later or equals to Initial date.'),
(229, 'es-CO', 'La fecha final debe ser posterior o igual a la fecha inicial.'),
(230, 'en-US', 'Initial Date'),
(230, 'es-CO', 'Fecha Inicial'),
(231, 'en-US', 'Final Date'),
(231, 'es-CO', 'Fecha Final'),
(232, 'en-US', 'INITIAL BALANCE'),
(232, 'es-CO', 'INVENTARIO INICIAL');

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
  `discount_rate` double DEFAULT NULL,
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

INSERT INTO `product` (`product_id`, `code`, `name`, `description`, `has_existences`, `tax_rate`, `discount_rate`, `minimum_stock`, `sugested_value`, `company_id`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, '12345ASD', 'Chocoramo', 'Producto increiblemente popular y delicioso', 'Y', 19, 2, 10, 4500, 1, 'A', 5, '2024-09-03 00:16:14', 5, '2025-02-13 23:20:40');

-- --------------------------------------------------------

--
-- Table structure for table `source_message`
--

CREATE TABLE `source_message` (
  `id` int(11) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

--
-- Dumping data for table `source_message`
--

INSERT INTO `source_message` (`id`, `category`, `message`) VALUES
(1, 'index', 'INDEX_WELCOME_APP'),
(2, 'index', 'INDEX_MESSAGE_WELCOME_APP'),
(3, 'index', 'INDEX_CREATE_COMPANY'),
(4, 'index', 'INDEX_MESSAGE_ENTER_COMPANY_1'),
(5, 'index', 'INDEX_MESSAGE_ENTER_COMPANY_2'),
(6, 'index', 'INDEX_MESSAGE_PENDING_APPLICATIONS_1'),
(7, 'index', 'INDEX_MESSAGE_PENDING_APPLICATIONS_2'),
(8, 'index', 'INDEX_MESSAGE_ACTIVATE_ACCOUNT'),
(9, 'index', 'INDEX_MESSAGE_USER_STATUS'),
(10, 'status', 'STATUS_ACTIVE'),
(11, 'status', 'STATUS_INACTIVE'),
(12, 'status', 'STATUS_NULLED'),
(13, 'status', 'STATUS_DRAFT'),
(14, 'index', 'INDEX_COMPANIES_TITLE'),
(15, 'index', 'INDEX_COMPANIES_PARAGRAPH_1'),
(16, 'index', 'INDEX_COMPANIES_PARAGRAPH_2'),
(17, 'index', 'INDEX_CONTINUE'),
(18, 'app', 'MESSAGE_SELECT_COMPANY'),
(19, 'index', 'INDEX_SELECTED_COMPANY'),
(20, 'index', 'INDEX_DOCUMENTS_TITLE'),
(21, 'index', 'INDEX_DOCUMENTS_PARAGRAPH'),
(22, 'index', 'INDEX_PRODUCTS_TITLE'),
(23, 'index', 'INDEX_PRODUCTS_PARAGRAPH'),
(24, 'index', 'INDEX_WAREHOUSES_TITLE'),
(25, 'index', 'INDEX_WAREHOUSES_PARAGRAPH'),
(26, 'index', 'INDEX_SUPPLIERS_TITLE'),
(27, 'index', 'INDEX_SUPPLIERS_PARAGRAPH'),
(28, 'index', 'INDEX_TRANSACTIONS_TITLE'),
(29, 'index', 'INDEX_TRANSACTIONS_PARAGRAPH'),
(30, 'index', 'INDEX_EXISTENCES_TITLE'),
(31, 'index', 'INDEX_EXISTENCES_PARAGRAPH'),
(32, 'index', 'INDEX_KARDEX_TITLE'),
(33, 'index', 'INDEX_KARDEX_PARAGRAPH'),
(34, 'company', 'COMPANY_INDEX_TITLE'),
(35, 'company', 'COMPANY_INDEX_TEXT'),
(36, 'company', 'COMPANY_INDEX_CREATE'),
(37, 'company', 'COMPANY_VIEW_TITLE'),
(38, 'app', 'BUTTON_SELECT'),
(39, 'app', 'BUTTON_VIEW'),
(40, 'app', 'BUTTON_UPDATE'),
(41, 'company', 'COMPANY_INDEX_BUTTON_LIST_USERS'),
(42, 'company', 'COMPANY_INDEX_BUTTON_CREATE_USER'),
(43, 'company', 'COMPANY_INDEX_CONFIRMATION_SELECT'),
(44, 'company', 'COMPANY_INDEX_OTHER_TITLE'),
(45, 'company', 'COMPANY_INDEX_OTHER_TEXT'),
(46, 'app', 'BUTTON_CREATE'),
(47, 'company', 'COMPANY_INDEX_BUTTON_SHOW_APPLICATIONS'),
(48, 'company', 'COMPANY_MODEL_ID'),
(49, 'company', 'COMPANY_MODEL_CODE'),
(50, 'attribute', 'ATTRIBUTE_MODEL_NAME'),
(51, 'attribute', 'ATTRIBUTE_MODEL_PHONE'),
(52, 'attribute', 'ATTRIBUTE_MODEL_ADDRESS'),
(53, 'city', 'CITY_MODEL_ID'),
(54, 'attribute', 'ATTRIBUTE_MODEL_CODE'),
(55, 'state', 'STATE_MODEL_ID'),
(56, 'country', 'COUNTRY_MODEL_ID'),
(57, 'attribute', 'ATTRIBUTE_MODEL_STATUS'),
(58, 'attribute', 'ATTRIBUTE_MODEL_CREATED_BY'),
(59, 'attribute', 'ATTRIBUTE_MODEL_CREATED_AT'),
(60, 'attribute', 'ATTRIBUTE_MODEL_UPDATED_BY'),
(61, 'attribute', 'ATTRIBUTE_MODEL_UPDATED_AT'),
(62, 'company', 'COMPANY_MODEL_STATUS'),
(63, 'company', 'COMPANY_MODEL_YOUR_STATUS'),
(64, 'company', 'COMPANY_MODEL_POSITION'),
(65, 'app', 'MESSAGE_CONFIRMATION_DELETE'),
(66, 'app', 'BUTTON_SAVE'),
(67, 'app', 'OPTION_SELECT'),
(68, 'company', 'COMPANY_INDEX_UPDATE'),
(69, 'user', 'USER_MODEL_ID'),
(70, 'attribute', 'ATTRIBUTE_MODEL_EMAIL'),
(71, 'user', 'USER_MODEL_PASSWORD'),
(72, 'user', 'USER_MODEL_REPASSWORD'),
(73, 'company', 'COMPANY_MODEL_ROLE'),
(74, 'app', 'MESSAGE_VALIDATE_PHONE'),
(75, 'app', 'MESSAGE_PASSWORD_VALIDATION'),
(76, 'app', 'MESSAGE_PASSWORDS_NOT_MATCH'),
(77, 'company', 'COMPANY_LIST_USERS_TITLE'),
(78, 'company', 'COMPANY_BUTTON_CREATE_USER'),
(79, 'company', 'COMPANY_MODEL_USER_STATUS'),
(80, 'company', 'COMPANY_LIST_USERS_PENDING'),
(81, 'company', 'COMPANY_MODEL_STATUS_IN_COMPANY'),
(82, 'role', 'ROLE_OWNER'),
(83, 'role', 'ROLE_SUPERVISOR'),
(84, 'role', 'ROLE_MEMBER'),
(85, 'company', 'COMPANY_USERS_PROMOTE'),
(86, 'company', 'COMPANY_USERS_CONFIRMATION_PROMOTE'),
(87, 'company', 'COMPANY_USERS_DEMOTE'),
(88, 'company', 'COMPANY_USERS_CONFIRMATION_DEMOTE'),
(89, 'app', 'BUTTON_DEACTIVATE'),
(90, 'company', 'COMPANY_USERS_CONFIRMATION_DEACTIVATE'),
(91, 'app', 'BUTTON_ACTIVATE'),
(92, 'company', 'COMPANY_USERS_CONFIRMATION_ACTIVATE'),
(93, 'company', 'COMPANY_USERS_PROMOTE_SUPERVISOR'),
(94, 'company', 'COMPANY_USERS_DEMOTE_MEMBER'),
(95, 'status', 'STATUS_APPROVED'),
(96, 'status', 'STATUS_REJECTED'),
(97, 'status', 'STATUS_PENDING'),
(98, 'app', 'BUTTON_APPROVE'),
(99, 'company', 'COMPANY_USERS_CONFIRMATION_APPROVE'),
(100, 'app', 'BUTTON_DENY'),
(101, 'application', 'APPLICATION_MODEL_ID'),
(102, 'application', 'APPLICATION_MODEL_COMMENT'),
(103, 'application', 'APPLICATION_MODEL_FEEDBACK'),
(104, 'company', 'COMPANY_USERS_DENY'),
(105, 'company', 'COMPANY_USERS_MESSAGE_FEEDBACK'),
(106, 'application', 'COMPANY_APPLICATION_MESSAGE_COMMENT'),
(107, 'application', 'COMPANY_APPLICATIONS_TITLE'),
(108, 'application', 'COMPANY_BUTTON_CREATE_APPLICATION'),
(109, 'application', 'COMPANY_VIEW_APPLICATION_COMPANY_TITLE'),
(110, 'application', 'APPLICATION_MODEL_SENT_AT'),
(111, 'application', 'COMPANY_VIEW_APPLICATION_USER_TITLE'),
(112, 'app', 'MESSAGE_NON_ACTIVE_USER'),
(113, 'app', 'MESSAGE_NOT_ENOUGH_PERMISSIONS'),
(114, 'app', 'MESSAGE_PAGE_NOT_EXISTS'),
(115, 'app', 'MESSAGE_INCORRECT_LOGIN'),
(116, 'company', 'COMPANY_MESSAGE_OWNER_NOT_DEMOTE'),
(117, 'company', 'COMPANY_MESSAGE_CANNOT_PROMOTE_INACTIVE'),
(118, 'company', 'COMPANY_MESSAGE_CANNOT_ACTIVATE_YOURSELF'),
(119, 'company', 'COMPANY_MESSAGE_OWNER_NOT_ACTIVATE'),
(120, 'company', 'COMPANY_MESSAGE_CANNOT_ACTIVATE_SUPERVISORS'),
(121, 'application', 'APPLICATION_MESSAGE_NOT_CREATE_NEW_APPLICATION_EXISTING_PENDING'),
(122, 'company', 'COMPANY_MESSAGE_INFO_NOT_BELONG_COMPANY'),
(123, 'transaction', 'TRANSACTION_MESSAGE_INFO_DELETED_NOT_DRAFT_TRANSACTION'),
(124, 'document', 'DOCUMENT_ACTION_INTENDED_INPUT'),
(125, 'document', 'DOCUMENT_ACTION_INTENDED_OUTPUT'),
(126, 'document', 'DOCUMENT_APPLY_SUPPLIER'),
(127, 'document', 'DOCUMENT_APPLY_CUSTOMER'),
(128, 'app', 'OPTION_YES'),
(129, 'app', 'OPTION_NO'),
(130, 'app', 'OPTION_ALL'),
(131, 'supplier', 'SUPPLIER_MODEL_ID'),
(132, 'warehouse', 'WAREHOUSE_MODEL_ID'),
(133, 'document', 'DOCUMENT_MODEL_ID'),
(134, 'product', 'PRODUCT_MODEL_ID'),
(135, 'attribute', 'ATTRIBUTE_MODEL_DESCRIPTION'),
(136, 'product', 'PRODUCT_MODEL_HAS_EXISTENCES'),
(137, 'product', 'PRODUCT_MODEL_TAX_RATE'),
(138, 'product', 'PRODUCT_MODEL_DISCOUNT_RATE'),
(139, 'product', 'PRODUCT_MODEL_MINIMUM_STOCK'),
(140, 'product', 'PRODUCT_MODEL_SUGGESTED_VALUE'),
(141, 'document', 'DOCUMENT_MODEL_INTENDED_FOR'),
(142, 'document', 'DOCUMENT_MODEL_APPLY_FOR'),
(143, 'document', 'DOCUMENT_MODEL_HAS_TAXES'),
(144, 'document', 'DOCUMENT_MODEL_HAS_EXPIRATION'),
(145, 'document', 'DOCUMENT_MODEL_HAS_OTHER_TRANSACTION'),
(146, 'document', 'DOCUMENT_INDEX_CONFIRMATION_DEACTIVATE'),
(147, 'document', 'DOCUMENT_INDEX_CONFIRMATION_ACTIVATE'),
(148, 'product', 'PRODUCT_INDEX_CONFIRMATION_DEACTIVATE'),
(149, 'product', 'PRODUCT_INDEX_CONFIRMATION_ACTIVATE'),
(150, 'supplier', 'SUPPLIER_INDEX_CONFIRMATION_DEACTIVATE'),
(151, 'supplier', 'SUPPLIER_INDEX_CONFIRMATION_ACTIVATE'),
(152, 'warehouse', 'WAREHOUSE_INDEX_CONFIRMATION_DEACTIVATE'),
(153, 'warehouse', 'WAREHOUSE_INDEX_CONFIRMATION_ACTIVATE'),
(154, 'document', 'DOCUMENT_BUTTON_CREATE'),
(155, 'document', 'DOCUMENT_TITLE_UPDATE'),
(156, 'product', 'PRODUCT_BUTTON_CREATE'),
(157, 'product', 'PRODUCT_TITLE_UPDATE'),
(158, 'supplier', 'SUPPLIER_BUTTON_CREATE'),
(159, 'supplier', 'SUPPLIER_TITLE_UPDATE'),
(160, 'warehouse', 'WAREHOUSE_BUTTON_CREATE'),
(161, 'warehouse', 'WAREHOUSE_TITLE_UPDATE'),
(162, 'app', 'BUTTON_DELETE'),
(163, 'index', 'INDEX_CITIES_TITLE'),
(164, 'city', 'CITY_BUTTON_CREATE'),
(165, 'city', 'CITY_TITLE_UPDATE'),
(166, 'index', 'INDEX_STATES_TITLE'),
(167, 'state', 'STATE_BUTTON_CREATE'),
(168, 'state', 'STATE_TITLE_UPDATE'),
(169, 'index', 'INDEX_COUNTRIES_TITLE'),
(170, 'country', 'COUNTRY_BUTTON_CREATE'),
(171, 'country', 'COUNTRY_TITLE_UPDATE'),
(172, 'city', 'CITY_VIEW_TITLE'),
(173, 'state', 'STATE_VIEW_TITLE'),
(174, 'country', 'COUNTRY_VIEW_TITLE'),
(175, 'index', 'INDEX_COUNTRIES_PARAGRAPH'),
(176, 'index', 'INDEX_STATES_PARAGRAPH'),
(177, 'index', 'INDEX_CITIES_PARAGRAPH'),
(178, 'index', 'INDEX_ADMIN_WELCOME_APP'),
(179, 'index', 'INDEX_ADMIN_MESSAGE_WELCOME_APP'),
(180, 'app', 'MESSAGE_ERROR_WEB_SERVER_REQUEST'),
(181, 'app', 'MESSAGE_ERROR_CONTACT_US'),
(182, 'app', 'BUTTON_LOGIN'),
(183, 'app', 'BUTTON_LOGOUT'),
(184, 'app', 'BUTTON_SIGNUP'),
(185, 'app', 'MESSAGE_REQUIRED_FIELDS_LOGIN'),
(186, 'app', 'TITLE_CHANGE_PASSWORD'),
(187, 'app', 'TITLE_SETTINGS'),
(188, 'app', 'TITLE_UPDATE_INFO'),
(189, 'app', 'TITLE_PROFILE'),
(190, 'attribute', 'ATTRIBUTE_MODEL_REMEMBER_ME'),
(191, 'app', 'BUTTON_HOME'),
(192, 'app', 'MESSAGE_FIELD_REQUIRED'),
(193, 'transaction', 'TRANSACTION_MESSAGE_EXPIRATION_LATER_CREATION_DATE'),
(194, 'transaction', 'TRANSACTION_MESSAGE_UNIQUE_PRODUCT_WAREHOUSE'),
(195, 'transaction', 'TRANSACTION_MESSAGE_AMOUNT_BELOW_MINIMUM_STOCK'),
(196, 'transaction', 'TRANSACTION_MODEL_ID'),
(197, 'transaction', 'TRANSACTION_MODEL_ITEM_ID'),
(198, 'transaction', 'TRANSACTION_MODEL_CREATION_DATE'),
(199, 'transaction', 'TRANSACTION_MODEL_EXPIRATION_DATE'),
(200, 'transaction', 'TRANSACTION_MODEL_LINKED'),
(201, 'transaction', 'TRANSACTION_MODEL_AMOUNT'),
(202, 'transaction', 'TRANSACTION_MODEL_UNIT_VALUE'),
(203, 'transaction', 'TRANSACTION_MODEL_TOTAL_VALUE'),
(204, 'transaction', 'TRANSACTION_MODEL_SUBTOTAL'),
(205, 'transaction', 'TRANSACTION_MODEL_TAXES'),
(206, 'transaction', 'TRANSACTION_MODEL_TOTAL'),
(207, 'transaction', 'TRANSACTION_MODEL_CUTOFF_DATE'),
(208, 'transaction', 'TRANSACTION_MODEL_AMOUNT_INPUT'),
(209, 'transaction', 'TRANSACTION_MODEL_AMOUNT_OUTPUT'),
(210, 'transaction', 'TRANSACTION_MODEL_NUM_ID'),
(211, 'transaction', 'TRANSACTION_MODEL_AMOUNT_DIFFERENCE'),
(212, 'app', 'OPTION_EMPTY'),
(213, 'attribute', 'ATTRIBUTE_MODEL_DATE'),
(214, 'transaction', 'TRANSACTION_MODEL_VALUE_INPUT'),
(215, 'transaction', 'TRANSACTION_MODEL_VALUE_OUTPUT'),
(216, 'transaction', 'TRANSACTION_MODEL_AMOUNT_BALANCE'),
(217, 'transaction', 'TRANSACTION_MODEL_VALUE_BALANCE'),
(218, 'app', 'BUTTON_CONTINUE'),
(219, 'app', 'BUTTON_SEARCH'),
(220, 'transaction', 'TRANSACTION_INDEX_CONFIRMATION_DELETE'),
(221, 'transaction', 'TRANSACTION_BUTTON_CREATE'),
(222, 'transaction', 'TRANSACTION_TITLE_DRAFT'),
(223, 'transaction', 'TRANSACTION_TITLE_INDEX'),
(224, 'transaction', 'TRANSACTION_TITLE_DRAFT_MINI'),
(225, 'transaction', 'TRANSACTION_SUBTITLE_DRAFTS'),
(226, 'transaction', 'TRANSACTION_SUBTITLE_SAVED'),
(227, 'transaction', 'TRANSACTION_TITLE_VIEW'),
(228, 'transaction', 'TRANSACTION_BUTTON_ADD_ITEM'),
(229, 'transaction', 'TRANSACTION_MESSAGE_FINAL_LATER_INITIAL_DATE'),
(230, 'transaction', 'TRANSACTION_MODEL_INITIAL_DATE'),
(231, 'transaction', 'TRANSACTION_MODEL_FINAL_DATE'),
(232, 'transaction', 'TRANSACTION_KARDEX_INITIAL_STOCK');

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

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`transaction_id`, `num_transaction`, `document_id`, `creation_date`, `expiration_date`, `linked_transaction_id`, `supplier_id`, `company_id`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, '0000000001', 3, '2025-02-13', '2025-02-28', NULL, 1, 1, 'A', 7, '2025-02-15 06:09:00', 7, '2025-02-17 00:05:26'),
(2, '0000000001', 2, '2025-02-16', NULL, NULL, NULL, 1, 'D', 7, '2025-02-16 20:13:05', 7, '2025-02-17 02:39:00'),
(3, '0000000002', 2, '2025-02-16', NULL, NULL, NULL, 1, 'D', 7, '2025-02-16 20:41:18', 7, '2025-02-17 02:45:14'),
(4, '0000000003', 2, '2025-02-16', NULL, NULL, NULL, 1, 'A', 7, '2025-02-16 20:45:29', 7, '2025-02-17 02:45:40'),
(5, '0000000004', 2, '2025-02-17', NULL, NULL, NULL, 1, 'A', 7, '2025-02-16 20:50:15', 5, '2025-02-17 10:39:58'),
(6, '0000000002', 3, '2025-02-18', '2025-02-28', NULL, 2, 1, 'A', 7, '2025-02-16 20:50:40', 5, '2025-02-18 08:55:52'),
(7, '0000000001', 4, '2025-02-19', NULL, 5, NULL, 1, 'A', 7, '2025-02-16 21:12:51', 5, '2025-02-17 23:26:24'),
(8, '0000000005', 2, '2025-02-19', NULL, NULL, NULL, 1, 'A', 7, '2025-02-16 22:42:52', 7, '2025-02-17 04:43:03'),
(9, '0000000006', 2, '2025-02-20', NULL, NULL, NULL, 1, 'A', 5, '2025-02-17 04:40:20', 5, '2025-02-17 23:36:09'),
(10, '0000000007', 2, '2025-02-20', NULL, NULL, NULL, 1, 'A', 5, '2025-02-17 17:39:21', 5, '2025-02-17 23:39:42'),
(11, '0000000008', 2, '2025-02-20', NULL, NULL, NULL, 1, 'A', 5, '2025-02-17 17:40:03', 5, '2025-02-18 07:04:13'),
(12, '0000000001', 5, '2025-02-20', NULL, 1, 1, 1, 'A', 5, '2025-02-17 22:12:41', 5, '2025-02-18 04:13:54'),
(16, '0000000002', 4, '2025-02-20', NULL, NULL, NULL, 1, 'D', 5, '2025-02-18 02:08:49', 5, '2025-02-18 08:08:54'),
(17, '0000000003', 3, '2025-02-28', '2025-02-28', NULL, 1, 1, 'D', 5, '2025-02-18 02:33:19', 5, '2025-02-18 08:33:28'),
(18, '0000000002', 5, '2025-02-21', NULL, 1, 1, 1, 'D', 5, '2025-02-18 02:35:55', 7, '2025-02-19 07:02:08'),
(19, '0000000009', 2, '2025-02-21', NULL, NULL, NULL, 1, 'B', 5, '2025-02-18 13:47:49', 5, '2025-02-18 13:47:49'),
(20, '0000000003', 5, '2025-02-21', NULL, 6, 2, 1, 'B', 7, '2025-02-19 01:01:53', 7, '2025-02-19 01:01:53');

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
  `discount_rate` double DEFAULT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `warehouse_id` int(10) UNSIGNED DEFAULT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `status` varchar(1) NOT NULL DEFAULT 'A',
  `created_by` int(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(10) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

--
-- Dumping data for table `transaction_item`
--

INSERT INTO `transaction_item` (`transaction_item_id`, `transaction_id`, `amount`, `unit_value`, `tax_rate`, `discount_rate`, `product_id`, `warehouse_id`, `company_id`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 1, 15, 4500, NULL, NULL, 1, 1, 1, 'A', 7, '2025-02-16 18:05:26', 7, '2025-02-16 18:05:26'),
(2, 1, 13, 4400, NULL, NULL, 1, 2, 1, 'A', 7, '2025-02-16 18:05:26', 7, '2025-02-16 18:05:26'),
(3, 1, 14, 4600, NULL, NULL, 1, 3, 1, 'A', 7, '2025-02-16 18:05:26', 7, '2025-02-16 18:05:26'),
(4, 1, 8, 4100, NULL, NULL, 1, 4, 1, 'A', 7, '2025-02-16 18:05:26', 7, '2025-02-16 18:05:26'),
(5, 1, 5, 4000, NULL, NULL, 1, NULL, 1, 'A', 7, '2025-02-16 18:05:26', 7, '2025-02-16 18:05:26'),
(6, 4, 1, 4500, 19, 2, 1, NULL, 1, 'A', 7, '2025-02-16 20:45:40', 7, '2025-02-16 20:45:40'),
(12, 8, 4, 4500, 19, 2, 1, 2, 1, 'A', 7, '2025-02-16 22:43:03', 7, '2025-02-16 22:43:03'),
(13, 5, 1, 4500, 19, 2, 1, 3, 1, 'A', 5, '2025-02-17 04:39:58', 5, '2025-02-17 04:39:58'),
(14, 7, 1, 4500, NULL, 2, 1, 3, 1, 'A', 5, '2025-02-17 17:26:24', 5, '2025-02-17 17:26:24'),
(15, 9, 1, 4500, 19, 2, 1, NULL, 1, 'A', 5, '2025-02-17 17:36:09', 5, '2025-02-17 17:36:09'),
(16, 10, 1, 4500, 19, 2, 1, 3, 1, 'A', 5, '2025-02-17 17:39:42', 5, '2025-02-17 17:39:42'),
(17, 12, 1, 4500, NULL, NULL, 1, 1, 1, 'A', 5, '2025-02-17 22:13:54', 5, '2025-02-17 22:13:54'),
(18, 12, 1, 4400, NULL, NULL, 1, 2, 1, 'A', 5, '2025-02-17 22:13:54', 5, '2025-02-17 22:13:54'),
(19, 12, 1, 4600, NULL, NULL, 1, 3, 1, 'A', 5, '2025-02-17 22:13:54', 5, '2025-02-17 22:13:54'),
(20, 11, 3, 4600, 19, 2, 1, 3, 1, 'A', 5, '2025-02-18 01:04:13', 5, '2025-02-18 01:04:13'),
(23, 6, 1, 4400, NULL, NULL, 1, 2, 1, 'A', 5, '2025-02-18 02:55:52', 5, '2025-02-18 02:55:52'),
(24, 6, 2, 4100, NULL, NULL, 1, 4, 1, 'A', 5, '2025-02-18 02:55:52', 5, '2025-02-18 02:55:52');

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
(1, 'admin@admin.com', 'ca0ee3d7b56fbeb00d909bd0bf9c86dbf4f7361ed570d2ee9e7bb670466e994a', NULL, NULL, 'Admin', '12345678', 'Admin address', 1, 'A', 1, '2024-08-14 05:34:25', 1, '2024-08-17 04:06:19'),
(5, 'user@user.com', 'ca0ee3d7b56fbeb00d909bd0bf9c86dbf4f7361ed570d2ee9e7bb670466e994a', NULL, NULL, 'user 1', '1234232342', 'calle user asd123', 17, 'A', 1, '2024-08-15 07:20:16', 5, '2024-08-17 07:34:58'),
(6, 'user2@user.com', 'ca0ee3d7b56fbeb00d909bd0bf9c86dbf4f7361ed570d2ee9e7bb670466e994a', NULL, NULL, 'User two', '433654987', 'fake street 12345', 1, 'A', 1, '2024-08-15 23:04:34', 6, '2024-08-17 05:26:35'),
(7, 'user3@user.com', 'ca0ee3d7b56fbeb00d909bd0bf9c86dbf4f7361ed570d2ee9e7bb670466e994a', NULL, NULL, 'User 3', '12344433', 'fake street 456', 5, 'A', 1, '2024-08-16 05:15:23', 7, '2024-08-16 14:15:57'),
(23, 'user4@user.com', 'ca0ee3d7b56fbeb00d909bd0bf9c86dbf4f7361ed570d2ee9e7bb670466e994a', NULL, NULL, 'user # four', '213424546754', '341 Buchanan Ave', 12, 'A', 5, '2024-08-16 23:34:18', 23, '2024-08-21 04:00:17'),
(24, 'user5@user.com', 'ca0ee3d7b56fbeb00d909bd0bf9c86dbf4f7361ed570d2ee9e7bb670466e994a', NULL, NULL, 'User 5', '234534213', 'fake street 123', 20, 'A', 23, '2024-08-16 23:57:54', 24, '2024-08-17 09:02:05'),
(25, 'user6@user.com', 'ca0ee3d7b56fbeb00d909bd0bf9c86dbf4f7361ed570d2ee9e7bb670466e994a', NULL, NULL, 'User six', '34332324', 'fake street 123', 19, 'I', 24, '2024-08-17 02:28:41', 25, '2024-08-21 04:32:50'),
(26, 'user7@user.com', 'ca0ee3d7b56fbeb00d909bd0bf9c86dbf4f7361ed570d2ee9e7bb670466e994a', NULL, NULL, 'User 7', '1234443321', 'fake street 123', 15, 'N', 1, '2024-08-17 03:55:37', 26, '2024-08-17 12:55:59'),
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
(7, 1, 'S', 'A', 24, '2024-08-20 21:48:35', 24, '2024-08-20 21:48:35'),
(7, 3, 'M', 'A', 6, '2024-08-20 22:13:37', 6, '2024-08-20 22:13:37'),
(23, 1, 'S', 'I', 5, '2024-08-16 23:34:18', 5, '2024-08-16 23:34:18'),
(24, 1, 'M', 'A', 23, '2024-08-16 23:57:54', 23, '2024-08-16 23:57:54'),
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
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`,`language`),
  ADD KEY `idx_message_language` (`language`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `code_UNIQUE` (`code`,`company_id`),
  ADD KEY `fk_product_company_idx` (`company_id`);

--
-- Indexes for table `source_message`
--
ALTER TABLE `source_message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_source_message_category` (`category`);

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
  MODIFY `application_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
-- AUTO_INCREMENT for table `source_message`
--
ALTER TABLE `source_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=233;

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
  MODIFY `transaction_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `transaction_item`
--
ALTER TABLE `transaction_item`
  MODIFY `transaction_item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `fk_message_source_message` FOREIGN KEY (`id`) REFERENCES `source_message` (`id`) ON DELETE CASCADE;

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
