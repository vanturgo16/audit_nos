-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 02, 2024 at 01:16 AM
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
-- Database: `laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `location` text NOT NULL,
  `access_from` varchar(255) NOT NULL,
  `activity` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `username`, `ip_address`, `location`, `access_from`, `activity`, `created_at`, `updated_at`) VALUES
(1, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'Deactivate User (admindev@gmail.com)', '2024-01-19 19:55:09', '2024-01-19 19:55:09'),
(2, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'Activate User (admindev@gmail.com)', '2024-01-19 19:55:14', '2024-01-19 19:55:14'),
(3, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Audit Log', '2024-01-19 19:57:52', '2024-01-19 19:57:52'),
(4, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst User', '2024-01-19 19:57:58', '2024-01-19 19:57:58'),
(5, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Audit Log', '2024-01-19 19:58:00', '2024-01-19 19:58:00'),
(6, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst User', '2024-01-19 19:58:02', '2024-01-19 19:58:02'),
(7, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst User', '2024-01-19 20:01:29', '2024-01-19 20:01:29'),
(8, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst User', '2024-01-19 20:04:04', '2024-01-19 20:04:04'),
(9, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst User', '2024-01-19 20:06:45', '2024-01-19 20:06:45'),
(10, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst User', '2024-01-19 20:25:42', '2024-01-19 20:25:42'),
(11, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst User', '2024-01-19 20:26:02', '2024-01-19 20:26:02'),
(12, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst User', '2024-01-19 20:26:32', '2024-01-19 20:26:32'),
(13, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst User', '2024-01-19 20:26:44', '2024-01-19 20:26:44'),
(14, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst User', '2024-01-19 20:26:59', '2024-01-19 20:26:59'),
(15, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst User', '2024-01-19 20:27:13', '2024-01-19 20:27:13'),
(16, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst User', '2024-01-19 20:27:56', '2024-01-19 20:27:56'),
(17, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst User', '2024-01-19 20:37:41', '2024-01-19 20:37:41'),
(18, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst User', '2024-01-19 21:21:06', '2024-01-19 21:21:06'),
(19, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst User', '2024-01-19 21:21:25', '2024-01-19 21:21:25'),
(20, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dropdown', '2024-01-19 21:23:15', '2024-01-19 21:23:15'),
(21, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dropdown', '2024-01-19 21:23:51', '2024-01-19 21:23:51'),
(22, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dropdown', '2024-01-19 21:24:29', '2024-01-19 21:24:29'),
(23, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dropdown', '2024-01-19 21:25:17', '2024-01-19 21:25:17'),
(24, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dropdown', '2024-01-19 21:25:25', '2024-01-19 21:25:25'),
(25, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dropdown', '2024-01-19 21:35:35', '2024-01-19 21:35:35'),
(26, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'Create New Dropdown', '2024-01-19 21:35:49', '2024-01-19 21:35:49'),
(27, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dropdown', '2024-01-19 21:35:49', '2024-01-19 21:35:49'),
(28, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dropdown', '2024-01-19 21:37:17', '2024-01-19 21:37:17'),
(29, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'Create New Dropdown', '2024-01-19 21:38:10', '2024-01-19 21:38:10'),
(30, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dropdown', '2024-01-19 21:38:10', '2024-01-19 21:38:10'),
(31, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dropdown', '2024-01-19 21:41:17', '2024-01-19 21:41:17'),
(32, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dropdown', '2024-01-19 21:42:12', '2024-01-19 21:42:12'),
(33, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dropdown', '2024-01-19 21:42:18', '2024-01-19 21:42:18'),
(34, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dropdown', '2024-01-19 21:42:30', '2024-01-19 21:42:30'),
(35, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'Update Dropdown', '2024-01-19 21:42:36', '2024-01-19 21:42:36'),
(36, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dropdown', '2024-01-19 21:42:37', '2024-01-19 21:42:37'),
(37, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'Update Dropdown', '2024-01-19 21:42:42', '2024-01-19 21:42:42'),
(38, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dropdown', '2024-01-19 21:42:42', '2024-01-19 21:42:42'),
(39, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'Deactivate Dropdown (Admin)', '2024-01-19 21:44:44', '2024-01-19 21:44:44'),
(40, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dropdown', '2024-01-19 21:44:44', '2024-01-19 21:44:44'),
(41, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'Activate Dropdown (Admin)', '2024-01-19 21:44:47', '2024-01-19 21:44:47'),
(42, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dropdown', '2024-01-19 21:44:47', '2024-01-19 21:44:47'),
(43, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dropdown', '2024-01-19 21:57:38', '2024-01-19 21:57:38'),
(44, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dropdown', '2024-01-19 21:58:00', '2024-01-19 21:58:00'),
(45, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Rules', '2024-01-19 21:58:02', '2024-01-19 21:58:02'),
(46, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'Create New Rule', '2024-01-19 21:58:12', '2024-01-19 21:58:12'),
(47, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Rules', '2024-01-19 21:58:12', '2024-01-19 21:58:12'),
(48, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Rules', '2024-01-19 21:58:41', '2024-01-19 21:58:41'),
(49, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Rules', '2024-01-19 21:58:50', '2024-01-19 21:58:50'),
(50, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'Deactivate Rule (123)', '2024-01-19 21:58:53', '2024-01-19 21:58:53'),
(51, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Rules', '2024-01-19 21:58:53', '2024-01-19 21:58:53'),
(52, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'Activate Rule (123)', '2024-01-19 21:58:56', '2024-01-19 21:58:56'),
(53, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Rules', '2024-01-19 21:58:57', '2024-01-19 21:58:57'),
(54, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'Deactivate Rule (123)', '2024-01-19 21:59:02', '2024-01-19 21:59:02'),
(55, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Rules', '2024-01-19 21:59:02', '2024-01-19 21:59:02'),
(56, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Rules', '2024-01-19 22:12:11', '2024-01-19 22:12:11'),
(57, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Department', '2024-01-19 22:12:13', '2024-01-19 22:12:13'),
(58, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Department', '2024-01-19 22:12:43', '2024-01-19 22:12:43'),
(59, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Department', '2024-01-19 22:14:23', '2024-01-19 22:14:23'),
(60, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'Create New Department', '2024-01-19 22:14:27', '2024-01-19 22:14:27'),
(61, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Department', '2024-01-19 22:14:27', '2024-01-19 22:14:27'),
(62, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'Create New Department', '2024-01-19 22:14:36', '2024-01-19 22:14:36'),
(63, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Department', '2024-01-19 22:14:37', '2024-01-19 22:14:37'),
(64, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Department', '2024-01-19 22:34:39', '2024-01-19 22:34:39'),
(65, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Department', '2024-01-19 22:34:57', '2024-01-19 22:34:57'),
(66, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Position', '2024-01-19 22:34:59', '2024-01-19 22:34:59'),
(67, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Position', '2024-01-19 22:35:56', '2024-01-19 22:35:56'),
(68, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Position', '2024-01-19 22:36:37', '2024-01-19 22:36:37'),
(69, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Position', '2024-01-19 22:36:51', '2024-01-19 22:36:51'),
(70, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'Create New Position', '2024-01-19 22:36:57', '2024-01-19 22:36:57'),
(71, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Position', '2024-01-19 22:36:58', '2024-01-19 22:36:58'),
(72, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Position', '2024-01-19 22:37:13', '2024-01-19 22:37:13'),
(73, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Position', '2024-01-19 22:37:40', '2024-01-19 22:37:40'),
(74, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst User', '2024-01-19 22:44:20', '2024-01-19 22:44:20'),
(75, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst User', '2024-01-19 22:45:07', '2024-01-19 22:45:07'),
(76, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst User', '2024-01-19 22:45:44', '2024-01-19 22:45:44'),
(77, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst User', '2024-01-21 20:13:50', '2024-01-21 20:13:50'),
(78, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Position', '2024-01-21 20:13:53', '2024-01-21 20:13:53'),
(79, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Rules', '2024-01-21 20:13:55', '2024-01-21 20:13:55'),
(80, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Department', '2024-01-21 20:14:44', '2024-01-21 20:14:44'),
(81, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Position', '2024-01-21 20:14:47', '2024-01-21 20:14:47'),
(82, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Rules', '2024-01-21 20:14:51', '2024-01-21 20:14:51'),
(83, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dropdown', '2024-01-21 20:15:04', '2024-01-21 20:15:04'),
(84, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Audit Log', '2024-01-21 20:15:21', '2024-01-21 20:15:21'),
(85, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst User', '2024-01-21 20:15:35', '2024-01-21 20:15:35'),
(86, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Department', '2024-01-21 20:15:38', '2024-01-21 20:15:38'),
(87, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst User', '2024-01-21 20:16:12', '2024-01-21 20:16:12'),
(88, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Department', '2024-01-21 20:20:26', '2024-01-21 20:20:26'),
(89, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Rules', '2024-01-21 20:20:32', '2024-01-21 20:20:32'),
(90, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dropdown', '2024-01-21 20:20:35', '2024-01-21 20:20:35'),
(91, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Department', '2024-01-21 20:21:20', '2024-01-21 20:21:20'),
(92, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst User', '2024-01-21 20:22:29', '2024-01-21 20:22:29'),
(93, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Department', '2024-01-21 20:22:37', '2024-01-21 20:22:37'),
(94, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Position', '2024-01-21 20:41:51', '2024-01-21 20:41:51'),
(95, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Audit Log', '2024-01-21 20:41:53', '2024-01-21 20:41:53'),
(96, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Rules', '2024-01-21 20:42:07', '2024-01-21 20:42:07'),
(97, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Audit Log', '2024-01-21 20:42:09', '2024-01-21 20:42:09'),
(98, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Position', '2024-01-26 02:39:15', '2024-01-26 02:39:15'),
(99, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Department', '2024-01-26 02:40:23', '2024-01-26 02:40:23'),
(100, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Department', '2024-01-26 02:46:07', '2024-01-26 02:46:07'),
(101, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dealer', '2024-01-26 02:46:49', '2024-01-26 02:46:49'),
(102, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dealer', '2024-01-26 02:47:09', '2024-01-26 02:47:09'),
(103, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Department', '2024-01-26 02:49:35', '2024-01-26 02:49:35'),
(104, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dealer', '2024-01-26 02:51:16', '2024-01-26 02:51:16'),
(105, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Department', '2024-01-26 02:51:29', '2024-01-26 02:51:29'),
(106, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Rules', '2024-01-26 02:52:11', '2024-01-26 02:52:11'),
(107, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dealer', '2024-01-26 02:52:14', '2024-01-26 02:52:14'),
(108, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dealer', '2024-01-26 02:52:30', '2024-01-26 02:52:30'),
(109, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Audit Log', '2024-01-26 02:53:30', '2024-01-26 02:53:30'),
(110, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dealer', '2024-01-26 02:53:38', '2024-01-26 02:53:38'),
(111, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dealer', '2024-01-26 02:58:40', '2024-01-26 02:58:40'),
(112, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'Create New Dealer', '2024-01-26 02:59:00', '2024-01-26 02:59:00'),
(113, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dealer', '2024-01-26 02:59:00', '2024-01-26 02:59:00'),
(114, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dealer', '2024-01-26 02:59:47', '2024-01-26 02:59:47'),
(115, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dealer', '2024-01-26 03:01:02', '2024-01-26 03:01:02'),
(116, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dealer', '2024-01-26 03:02:20', '2024-01-26 03:02:20'),
(117, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dealer', '2024-01-26 03:04:45', '2024-01-26 03:04:45'),
(118, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dealer', '2024-01-26 03:10:20', '2024-01-26 03:10:20'),
(119, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dealer', '2024-01-26 03:10:56', '2024-01-26 03:10:56'),
(120, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 03:13:01', '2024-01-26 03:13:01'),
(121, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 03:13:33', '2024-01-26 03:13:33'),
(122, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 03:16:14', '2024-01-26 03:16:14'),
(123, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 03:19:08', '2024-01-26 03:19:08'),
(124, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 03:19:38', '2024-01-26 03:19:38'),
(125, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 03:19:49', '2024-01-26 03:19:49'),
(126, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Rules', '2024-01-26 03:21:48', '2024-01-26 03:21:48'),
(127, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dropdown', '2024-01-26 03:21:53', '2024-01-26 03:21:53'),
(128, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 03:24:04', '2024-01-26 03:24:04'),
(129, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 03:27:12', '2024-01-26 03:27:12'),
(130, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 03:27:37', '2024-01-26 03:27:37'),
(131, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 03:27:53', '2024-01-26 03:27:53'),
(132, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 03:30:19', '2024-01-26 03:30:19'),
(133, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 03:31:24', '2024-01-26 03:31:24'),
(134, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'Create New Employee', '2024-01-26 03:31:43', '2024-01-26 03:31:43'),
(135, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 03:31:44', '2024-01-26 03:31:44'),
(136, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 03:33:25', '2024-01-26 03:33:25'),
(137, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 03:34:20', '2024-01-26 03:34:20'),
(138, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dealer', '2024-01-26 03:34:32', '2024-01-26 03:34:32'),
(139, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'Create New Dealer', '2024-01-26 03:34:47', '2024-01-26 03:34:47'),
(140, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dealer', '2024-01-26 03:34:47', '2024-01-26 03:34:47'),
(141, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 03:35:29', '2024-01-26 03:35:29'),
(142, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Department', '2024-01-26 03:36:21', '2024-01-26 03:36:21'),
(143, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dropdown', '2024-01-26 03:36:23', '2024-01-26 03:36:23'),
(144, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 03:38:38', '2024-01-26 03:38:38'),
(145, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 03:42:58', '2024-01-26 03:42:58'),
(146, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'Update Employee', '2024-01-26 03:43:06', '2024-01-26 03:43:06'),
(147, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 03:43:06', '2024-01-26 03:43:06'),
(148, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 03:44:03', '2024-01-26 03:44:03'),
(149, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 03:46:38', '2024-01-26 03:46:38'),
(150, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 03:46:47', '2024-01-26 03:46:47'),
(151, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 03:47:37', '2024-01-26 03:47:37'),
(152, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Rules', '2024-01-26 03:48:05', '2024-01-26 03:48:05'),
(153, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dropdown', '2024-01-26 03:48:07', '2024-01-26 03:48:07'),
(154, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dealer', '2024-01-26 06:21:43', '2024-01-26 06:21:43'),
(155, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Department', '2024-01-26 06:21:53', '2024-01-26 06:21:53'),
(156, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Position', '2024-01-26 06:30:56', '2024-01-26 06:30:56'),
(157, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 06:30:58', '2024-01-26 06:30:58'),
(158, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dealer', '2024-01-26 06:31:00', '2024-01-26 06:31:00'),
(159, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dealer', '2024-01-26 06:33:23', '2024-01-26 06:33:23'),
(160, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Dealer', '2024-01-26 06:35:04', '2024-01-26 06:35:04'),
(161, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 06:35:38', '2024-01-26 06:35:38'),
(162, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 06:42:33', '2024-01-26 06:42:33'),
(163, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 06:57:36', '2024-01-26 06:57:36'),
(164, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 06:59:36', '2024-01-26 06:59:36'),
(165, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 07:00:09', '2024-01-26 07:00:09'),
(166, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 07:00:30', '2024-01-26 07:00:30'),
(167, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 07:01:39', '2024-01-26 07:01:39'),
(168, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 07:02:43', '2024-01-26 07:02:43'),
(169, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 07:03:48', '2024-01-26 07:03:48'),
(170, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 07:05:10', '2024-01-26 07:05:10'),
(171, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Position', '2024-01-26 07:05:30', '2024-01-26 07:05:30'),
(172, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'Create New Position', '2024-01-26 07:05:39', '2024-01-26 07:05:39'),
(173, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Position', '2024-01-26 07:05:39', '2024-01-26 07:05:39'),
(174, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 07:05:43', '2024-01-26 07:05:43'),
(175, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 07:06:25', '2024-01-26 07:06:25'),
(176, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 07:07:04', '2024-01-26 07:07:04'),
(177, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 07:07:49', '2024-01-26 07:07:49'),
(178, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 07:10:09', '2024-01-26 07:10:09'),
(179, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 07:10:32', '2024-01-26 07:10:32'),
(180, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 07:12:33', '2024-01-26 07:12:33'),
(181, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 07:13:46', '2024-01-26 07:13:46'),
(182, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 07:21:17', '2024-01-26 07:21:17'),
(183, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 07:21:43', '2024-01-26 07:21:43'),
(184, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 07:22:13', '2024-01-26 07:22:13'),
(185, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 07:32:22', '2024-01-26 07:32:22'),
(186, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 07:33:05', '2024-01-26 07:33:05'),
(187, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 07:33:18', '2024-01-26 07:33:18'),
(188, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 07:36:41', '2024-01-26 07:36:41'),
(189, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 07:46:06', '2024-01-26 07:46:06'),
(190, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 07:47:13', '2024-01-26 07:47:13'),
(191, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 07:48:13', '2024-01-26 07:48:13'),
(192, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 07:50:27', '2024-01-26 07:50:27'),
(193, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'Create New Employee', '2024-01-26 07:56:43', '2024-01-26 07:56:43'),
(194, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 07:56:43', '2024-01-26 07:56:43'),
(195, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 07:58:46', '2024-01-26 07:58:46'),
(196, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 08:00:26', '2024-01-26 08:00:26'),
(197, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 08:01:28', '2024-01-26 08:01:28'),
(198, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 08:03:07', '2024-01-26 08:03:07'),
(199, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst Employee', '2024-01-26 08:05:13', '2024-01-26 08:05:13'),
(200, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 17:24:27', '2024-03-01 17:24:27'),
(201, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Dropdown', '2024-03-01 17:24:37', '2024-03-01 17:24:37'),
(202, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Dropdown', '2024-03-01 17:26:31', '2024-03-01 17:26:31'),
(203, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Dropdown', '2024-03-01 17:26:31', '2024-03-01 17:26:31'),
(204, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Dropdown', '2024-03-01 17:26:40', '2024-03-01 17:26:40'),
(205, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Dropdown', '2024-03-01 17:26:41', '2024-03-01 17:26:41'),
(206, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Dropdown', '2024-03-01 17:27:12', '2024-03-01 17:27:12'),
(207, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Dropdown', '2024-03-01 17:27:12', '2024-03-01 17:27:12'),
(208, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Dropdown', '2024-03-01 17:27:36', '2024-03-01 17:27:36'),
(209, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Dropdown', '2024-03-01 17:27:36', '2024-03-01 17:27:36'),
(210, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Dropdown', '2024-03-01 17:27:57', '2024-03-01 17:27:57'),
(211, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Dropdown', '2024-03-01 17:27:57', '2024-03-01 17:27:57'),
(212, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Dropdown', '2024-03-01 17:28:09', '2024-03-01 17:28:09'),
(213, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Dropdown', '2024-03-01 17:28:09', '2024-03-01 17:28:09'),
(214, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 17:28:11', '2024-03-01 17:28:11'),
(215, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Checklist', '2024-03-01 17:29:28', '2024-03-01 17:29:28'),
(216, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 17:29:28', '2024-03-01 17:29:28'),
(217, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mark Checklist (Kebersihan Network)', '2024-03-01 17:29:32', '2024-03-01 17:29:32'),
(218, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Mark Checklist', '2024-03-01 17:29:40', '2024-03-01 17:29:40'),
(219, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mark Checklist (Kebersihan Network)', '2024-03-01 17:29:40', '2024-03-01 17:29:40'),
(220, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 17:29:56', '2024-03-01 17:29:56'),
(221, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 17:33:03', '2024-03-01 17:33:03'),
(222, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 17:35:38', '2024-03-01 17:35:38'),
(223, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Period Checklist', '2024-03-01 17:35:52', '2024-03-01 17:35:52'),
(224, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 17:35:52', '2024-03-01 17:35:52'),
(225, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 17:37:19', '2024-03-01 17:37:19'),
(226, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 17:40:35', '2024-03-01 17:40:35'),
(227, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mid 2024)', '2024-03-01 17:40:38', '2024-03-01 17:40:38'),
(228, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Assign Checklist', '2024-03-01 17:40:47', '2024-03-01 17:40:47'),
(229, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mid 2024)', '2024-03-01 17:40:47', '2024-03-01 17:40:47'),
(230, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Jaringan Checklist', '2024-03-01 17:40:54', '2024-03-01 17:40:54'),
(231, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 17:41:16', '2024-03-01 17:41:16'),
(232, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Dropdown', '2024-03-01 17:41:19', '2024-03-01 17:41:19'),
(233, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Dropdown', '2024-03-01 17:41:35', '2024-03-01 17:41:35'),
(234, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Dropdown', '2024-03-01 17:41:35', '2024-03-01 17:41:35'),
(235, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Dropdown', '2024-03-01 17:41:52', '2024-03-01 17:41:52'),
(236, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Dropdown', '2024-03-01 17:41:52', '2024-03-01 17:41:52'),
(237, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Dropdown', '2024-03-01 17:42:04', '2024-03-01 17:42:04'),
(238, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Dropdown', '2024-03-01 17:42:04', '2024-03-01 17:42:04'),
(239, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Jaringan Checklist', '2024-03-01 17:42:06', '2024-03-01 17:42:06'),
(240, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Periode Form Checklist', '2024-03-01 17:42:49', '2024-03-01 17:42:49'),
(241, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Periode Form Checklist', '2024-03-01 17:48:32', '2024-03-01 17:48:32'),
(242, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Periode Form Checklist', '2024-03-01 17:48:57', '2024-03-01 17:48:57'),
(243, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 17:50:31', '2024-03-01 17:50:31'),
(244, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 17:51:02', '2024-03-01 17:51:02'),
(245, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 17:51:11', '2024-03-01 17:51:11'),
(246, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 17:52:15', '2024-03-01 17:52:15'),
(247, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 17:53:16', '2024-03-01 17:53:16'),
(248, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 17:54:22', '2024-03-01 17:54:22'),
(249, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 17:54:39', '2024-03-01 17:54:39'),
(250, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 17:57:05', '2024-03-01 17:57:05'),
(251, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 17:57:52', '2024-03-01 17:57:52'),
(252, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 18:02:58', '2024-03-01 18:02:58'),
(253, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 18:03:16', '2024-03-01 18:03:16'),
(254, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 18:06:54', '2024-03-01 18:06:54'),
(255, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 18:12:10', '2024-03-01 18:12:10'),
(256, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 18:12:25', '2024-03-01 18:12:25'),
(257, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 18:14:12', '2024-03-01 18:14:12'),
(258, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Jaringan Checklist', '2024-03-01 18:17:15', '2024-03-01 18:17:15'),
(259, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Periode Form Checklist', '2024-03-01 18:17:19', '2024-03-01 18:17:19'),
(260, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 18:17:21', '2024-03-01 18:17:21'),
(261, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 18:23:11', '2024-03-01 18:23:11'),
(262, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Start Checklist :', '2024-03-01 18:23:16', '2024-03-01 18:23:16'),
(263, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 18:23:17', '2024-03-01 18:23:17'),
(264, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 18:23:42', '2024-03-01 18:23:42'),
(265, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 18:24:11', '2024-03-01 18:24:11'),
(266, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 18:24:28', '2024-03-01 18:24:28'),
(267, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 18:24:46', '2024-03-01 18:24:46'),
(268, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 18:24:55', '2024-03-01 18:24:55'),
(269, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 18:25:01', '2024-03-01 18:25:01'),
(270, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 18:25:09', '2024-03-01 18:25:09'),
(271, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 18:25:18', '2024-03-01 18:25:18'),
(272, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 18:25:28', '2024-03-01 18:25:28'),
(273, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 18:25:35', '2024-03-01 18:25:35'),
(274, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 18:25:59', '2024-03-01 18:25:59'),
(275, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 18:26:33', '2024-03-01 18:26:33'),
(276, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 18:26:55', '2024-03-01 18:26:55'),
(277, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 18:27:04', '2024-03-01 18:27:04'),
(278, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 18:29:31', '2024-03-01 18:29:31'),
(279, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 18:31:36', '2024-03-01 18:31:36'),
(280, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 18:34:40', '2024-03-01 18:34:40'),
(281, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mid 2024)', '2024-03-01 18:34:44', '2024-03-01 18:34:44'),
(282, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 18:39:55', '2024-03-01 18:39:55'),
(283, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 18:44:02', '2024-03-01 18:44:02'),
(284, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mark Checklist (Kebersihan Network)', '2024-03-01 18:44:10', '2024-03-01 18:44:10'),
(285, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 18:48:17', '2024-03-01 18:48:17'),
(286, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Checklist', '2024-03-01 18:49:02', '2024-03-01 18:49:02'),
(287, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 18:49:02', '2024-03-01 18:49:02'),
(288, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mark Checklist (Kebersihan Network)', '2024-03-01 18:49:43', '2024-03-01 18:49:43'),
(289, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Mark Checklist', '2024-03-01 18:49:50', '2024-03-01 18:49:50'),
(290, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mark Checklist (Kebersihan Network)', '2024-03-01 18:49:50', '2024-03-01 18:49:50'),
(291, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 18:49:54', '2024-03-01 18:49:54'),
(292, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 18:50:21', '2024-03-01 18:50:21'),
(293, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mid 2024)', '2024-03-01 18:50:24', '2024-03-01 18:50:24'),
(294, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Assign Checklist', '2024-03-01 18:50:30', '2024-03-01 18:50:30'),
(295, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mid 2024)', '2024-03-01 18:50:30', '2024-03-01 18:50:30'),
(296, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 18:50:41', '2024-03-01 18:50:41'),
(297, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Checklist', '2024-03-01 18:52:03', '2024-03-01 18:52:03'),
(298, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 18:52:03', '2024-03-01 18:52:03'),
(299, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 18:52:08', '2024-03-01 18:52:08'),
(300, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mid 2024)', '2024-03-01 18:52:11', '2024-03-01 18:52:11'),
(301, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Assign Checklist', '2024-03-01 18:52:19', '2024-03-01 18:52:19'),
(302, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mid 2024)', '2024-03-01 18:52:19', '2024-03-01 18:52:19'),
(303, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 18:53:06', '2024-03-01 18:53:06'),
(304, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mid 2024)', '2024-03-01 18:53:10', '2024-03-01 18:53:10'),
(305, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Jaringan Checklist', '2024-03-01 18:53:18', '2024-03-01 18:53:18'),
(306, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Periode Form Checklist', '2024-03-01 18:53:21', '2024-03-01 18:53:21'),
(307, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 18:53:25', '2024-03-01 18:53:25'),
(308, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 18:55:28', '2024-03-01 18:55:28'),
(309, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mid 2024)', '2024-03-01 18:55:46', '2024-03-01 18:55:46'),
(310, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:23:03', '2024-03-01 19:23:03'),
(311, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:23:59', '2024-03-01 19:23:59'),
(312, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:24:13', '2024-03-01 19:24:13'),
(313, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:24:24', '2024-03-01 19:24:24'),
(314, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:25:11', '2024-03-01 19:25:11'),
(315, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:25:38', '2024-03-01 19:25:38'),
(316, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:26:03', '2024-03-01 19:26:03'),
(317, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:26:22', '2024-03-01 19:26:22'),
(318, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:28:26', '2024-03-01 19:28:26'),
(319, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:28:50', '2024-03-01 19:28:50'),
(320, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:29:17', '2024-03-01 19:29:17'),
(321, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:29:41', '2024-03-01 19:29:41'),
(322, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:33:29', '2024-03-01 19:33:29'),
(323, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:33:56', '2024-03-01 19:33:56'),
(324, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:34:29', '2024-03-01 19:34:29'),
(325, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:34:41', '2024-03-01 19:34:41'),
(326, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:35:18', '2024-03-01 19:35:18'),
(327, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:35:59', '2024-03-01 19:35:59'),
(328, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:36:16', '2024-03-01 19:36:16'),
(329, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:36:39', '2024-03-01 19:36:39'),
(330, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:37:28', '2024-03-01 19:37:28'),
(331, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:37:55', '2024-03-01 19:37:55'),
(332, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:40:49', '2024-03-01 19:40:49'),
(333, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:41:27', '2024-03-01 19:41:27'),
(334, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:41:52', '2024-03-01 19:41:52'),
(335, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:42:41', '2024-03-01 19:42:41'),
(336, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:43:56', '2024-03-01 19:43:56'),
(337, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:45:39', '2024-03-01 19:45:39'),
(338, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:47:47', '2024-03-01 19:47:47'),
(339, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:49:25', '2024-03-01 19:49:25'),
(340, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:51:12', '2024-03-01 19:51:12'),
(341, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:51:55', '2024-03-01 19:51:55'),
(342, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:53:11', '2024-03-01 19:53:11'),
(343, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:53:50', '2024-03-01 19:53:50'),
(344, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:53:59', '2024-03-01 19:53:59'),
(345, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:54:14', '2024-03-01 19:54:14'),
(346, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:55:31', '2024-03-01 19:55:31'),
(347, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 19:55:45', '2024-03-01 19:55:45'),
(348, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 19:57:12', '2024-03-01 19:57:12'),
(349, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Checklist', '2024-03-01 19:59:33', '2024-03-01 19:59:33'),
(350, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 19:59:33', '2024-03-01 19:59:33'),
(351, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 20:04:13', '2024-03-01 20:04:13'),
(352, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Checklist', '2024-03-01 20:04:47', '2024-03-01 20:04:47'),
(353, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 20:04:47', '2024-03-01 20:04:47'),
(354, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 20:05:19', '2024-03-01 20:05:19'),
(355, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 20:06:14', '2024-03-01 20:06:14'),
(356, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 20:06:50', '2024-03-01 20:06:50'),
(357, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 20:08:06', '2024-03-01 20:08:06'),
(358, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 20:09:19', '2024-03-01 20:09:19'),
(359, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Checklist', '2024-03-01 20:10:08', '2024-03-01 20:10:08'),
(360, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 20:10:08', '2024-03-01 20:10:08'),
(361, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 20:15:21', '2024-03-01 20:15:21'),
(362, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Checklist', '2024-03-01 20:16:05', '2024-03-01 20:16:05'),
(363, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 20:16:05', '2024-03-01 20:16:05'),
(364, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Jaringan Checklist', '2024-03-01 20:16:27', '2024-03-01 20:16:27'),
(365, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 20:16:29', '2024-03-01 20:16:29'),
(366, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mid 2024)', '2024-03-01 20:16:33', '2024-03-01 20:16:33'),
(367, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Assign Checklist', '2024-03-01 20:16:43', '2024-03-01 20:16:43'),
(368, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mid 2024)', '2024-03-01 20:16:43', '2024-03-01 20:16:43'),
(369, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Jaringan Checklist', '2024-03-01 20:16:46', '2024-03-01 20:16:46'),
(370, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Periode Form Checklist', '2024-03-01 20:16:49', '2024-03-01 20:16:49'),
(371, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 20:16:50', '2024-03-01 20:16:50'),
(372, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 20:16:53', '2024-03-01 20:16:53'),
(373, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 20:17:10', '2024-03-01 20:17:10'),
(374, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 20:20:26', '2024-03-01 20:20:26'),
(375, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 20:20:33', '2024-03-01 20:20:33'),
(376, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Checklist', '2024-03-01 20:22:14', '2024-03-01 20:22:14'),
(377, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 20:22:14', '2024-03-01 20:22:14');
INSERT INTO `audit_logs` (`id`, `username`, `ip_address`, `location`, `access_from`, `activity`, `created_at`, `updated_at`) VALUES
(378, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 20:23:38', '2024-03-01 20:23:38'),
(379, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 20:24:24', '2024-03-01 20:24:24'),
(380, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 20:24:41', '2024-03-01 20:24:41'),
(381, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 20:24:55', '2024-03-01 20:24:55'),
(382, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 20:25:23', '2024-03-01 20:25:23'),
(383, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 20:27:11', '2024-03-01 20:27:11'),
(384, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 20:27:24', '2024-03-01 20:27:24'),
(385, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 20:27:34', '2024-03-01 20:27:34'),
(386, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 20:27:53', '2024-03-01 20:27:53'),
(387, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 20:27:59', '2024-03-01 20:27:59'),
(388, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 20:28:05', '2024-03-01 20:28:05'),
(389, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 20:30:09', '2024-03-01 20:30:09'),
(390, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 20:32:58', '2024-03-01 20:32:58'),
(391, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 20:34:50', '2024-03-01 20:34:50'),
(392, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 20:35:48', '2024-03-01 20:35:48'),
(393, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 20:36:05', '2024-03-01 20:36:05'),
(394, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 20:36:36', '2024-03-01 20:36:36'),
(395, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 20:37:27', '2024-03-01 20:37:27'),
(396, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 20:37:53', '2024-03-01 20:37:53'),
(397, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 20:39:32', '2024-03-01 20:39:32'),
(398, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Jaringan Checklist', '2024-03-01 20:42:30', '2024-03-01 20:42:30'),
(399, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Periode Form Checklist', '2024-03-01 20:42:33', '2024-03-01 20:42:33'),
(400, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 20:42:35', '2024-03-01 20:42:35'),
(401, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 20:42:40', '2024-03-01 20:42:40'),
(402, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Jaringan Checklist', '2024-03-01 21:10:06', '2024-03-01 21:10:06'),
(403, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 21:12:55', '2024-03-01 21:12:55'),
(404, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 21:12:57', '2024-03-01 21:12:57'),
(405, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 21:13:49', '2024-03-01 21:13:49'),
(406, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 21:13:58', '2024-03-01 21:13:58'),
(407, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Jaringan Checklist', '2024-03-01 21:20:33', '2024-03-01 21:20:33'),
(408, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Periode Form Checklist', '2024-03-01 21:20:36', '2024-03-01 21:20:36'),
(409, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 21:20:38', '2024-03-01 21:20:38'),
(410, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 21:20:40', '2024-03-01 21:20:40'),
(411, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Periode Form Checklist', '2024-03-01 21:25:24', '2024-03-01 21:25:24'),
(412, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 21:25:26', '2024-03-01 21:25:26'),
(413, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 21:29:28', '2024-03-01 21:29:28'),
(414, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 21:30:17', '2024-03-01 21:30:17'),
(415, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 21:30:27', '2024-03-01 21:30:27'),
(416, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mid 2024)', '2024-03-01 21:30:35', '2024-03-01 21:30:35'),
(417, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 21:30:53', '2024-03-01 21:30:53'),
(418, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mid 2024)', '2024-03-01 21:30:56', '2024-03-01 21:30:56'),
(419, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mid 2024)', '2024-03-01 21:31:17', '2024-03-01 21:31:17'),
(420, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 21:34:14', '2024-03-01 21:34:14'),
(421, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mid 2024)', '2024-03-01 21:34:17', '2024-03-01 21:34:17'),
(422, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mid 2024)', '2024-03-01 21:34:25', '2024-03-01 21:34:25'),
(423, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mid 2024)', '2024-03-01 21:34:40', '2024-03-01 21:34:40'),
(424, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mid 2024)', '2024-03-01 21:37:57', '2024-03-01 21:37:57'),
(425, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mid 2024)', '2024-03-01 21:39:15', '2024-03-01 21:39:15'),
(426, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 21:39:44', '2024-03-01 21:39:44'),
(427, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 21:39:47', '2024-03-01 21:39:47'),
(428, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 21:43:40', '2024-03-01 21:43:40'),
(429, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 21:43:54', '2024-03-01 21:43:54'),
(430, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 21:44:12', '2024-03-01 21:44:12'),
(431, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 21:45:34', '2024-03-01 21:45:34'),
(432, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 21:46:00', '2024-03-01 21:46:00'),
(433, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 21:46:09', '2024-03-01 21:46:09'),
(434, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mid 2024)', '2024-03-01 21:46:57', '2024-03-01 21:46:57'),
(435, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mid 2024)', '2024-03-01 21:58:41', '2024-03-01 21:58:41'),
(436, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mid 2024)', '2024-03-01 21:59:13', '2024-03-01 21:59:13'),
(437, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 22:04:35', '2024-03-01 22:04:35'),
(438, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mid 2024)', '2024-03-01 22:04:43', '2024-03-01 22:04:43'),
(439, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Assign Checklist', '2024-03-01 22:05:22', '2024-03-01 22:05:22'),
(440, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mid 2024)', '2024-03-01 22:05:22', '2024-03-01 22:05:22'),
(441, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 22:14:21', '2024-03-01 22:14:21'),
(442, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Checklist', '2024-03-01 22:14:53', '2024-03-01 22:14:53'),
(443, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 22:14:54', '2024-03-01 22:14:54'),
(444, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 22:14:57', '2024-03-01 22:14:57'),
(445, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mid 2024)', '2024-03-01 22:15:00', '2024-03-01 22:15:00'),
(446, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Assign Checklist', '2024-03-01 22:15:09', '2024-03-01 22:15:09'),
(447, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mid 2024)', '2024-03-01 22:15:09', '2024-03-01 22:15:09'),
(448, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 23:12:42', '2024-03-01 23:12:42'),
(449, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 23:12:43', '2024-03-01 23:12:43'),
(450, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Checklist', '2024-03-01 23:13:24', '2024-03-01 23:13:24'),
(451, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 23:13:24', '2024-03-01 23:13:24'),
(452, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Checklist', '2024-03-01 23:13:52', '2024-03-01 23:13:52'),
(453, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 23:13:52', '2024-03-01 23:13:52'),
(454, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Checklist', '2024-03-01 23:14:29', '2024-03-01 23:14:29'),
(455, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 23:14:29', '2024-03-01 23:14:29'),
(456, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Checklist', '2024-03-01 23:15:25', '2024-03-01 23:15:25'),
(457, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 23:15:25', '2024-03-01 23:15:25'),
(458, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Checklist', '2024-03-01 23:15:55', '2024-03-01 23:15:55'),
(459, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 23:15:55', '2024-03-01 23:15:55'),
(460, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Checklist', '2024-03-01 23:16:39', '2024-03-01 23:16:39'),
(461, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 23:16:39', '2024-03-01 23:16:39'),
(462, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mark Checklist (Kebersihan Network)', '2024-03-01 23:16:46', '2024-03-01 23:16:46'),
(463, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Mark Checklist', '2024-03-01 23:16:55', '2024-03-01 23:16:55'),
(464, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mark Checklist (Kebersihan Network)', '2024-03-01 23:16:55', '2024-03-01 23:16:55'),
(465, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mark Checklist (Kebersihan Network)', '2024-03-01 23:17:05', '2024-03-01 23:17:05'),
(466, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Mark Checklist', '2024-03-01 23:17:13', '2024-03-01 23:17:13'),
(467, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mark Checklist (Kebersihan Network)', '2024-03-01 23:17:13', '2024-03-01 23:17:13'),
(468, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mark Checklist (Kebersihan Network)', '2024-03-01 23:17:22', '2024-03-01 23:17:22'),
(469, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Mark Checklist', '2024-03-01 23:17:31', '2024-03-01 23:17:31'),
(470, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mark Checklist (Kebersihan Network)', '2024-03-01 23:17:31', '2024-03-01 23:17:31'),
(471, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 23:17:32', '2024-03-01 23:17:32'),
(472, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mark Checklist (Approval Layout New VinCi)', '2024-03-01 23:17:36', '2024-03-01 23:17:36'),
(473, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Mark Checklist', '2024-03-01 23:17:43', '2024-03-01 23:17:43'),
(474, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mark Checklist (Approval Layout New VinCi)', '2024-03-01 23:17:44', '2024-03-01 23:17:44'),
(475, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 23:17:45', '2024-03-01 23:17:45'),
(476, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mark Checklist (Approval Layout New VinCi)', '2024-03-01 23:17:50', '2024-03-01 23:17:50'),
(477, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Mark Checklist', '2024-03-01 23:17:58', '2024-03-01 23:17:58'),
(478, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mark Checklist (Approval Layout New VinCi)', '2024-03-01 23:17:58', '2024-03-01 23:17:58'),
(479, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 23:18:00', '2024-03-01 23:18:00'),
(480, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mark Checklist (Kepala)', '2024-03-01 23:18:05', '2024-03-01 23:18:05'),
(481, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Mark Checklist', '2024-03-01 23:18:16', '2024-03-01 23:18:16'),
(482, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mark Checklist (Kepala)', '2024-03-01 23:18:16', '2024-03-01 23:18:16'),
(483, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 23:18:20', '2024-03-01 23:18:20'),
(484, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Period Checklist', '2024-03-01 23:18:46', '2024-03-01 23:18:46'),
(485, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 23:18:47', '2024-03-01 23:18:47'),
(486, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Tahun 2024)', '2024-03-01 23:18:51', '2024-03-01 23:18:51'),
(487, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Assign Checklist', '2024-03-01 23:19:01', '2024-03-01 23:19:01'),
(488, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Tahun 2024)', '2024-03-01 23:19:01', '2024-03-01 23:19:01'),
(489, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Assign Checklist', '2024-03-01 23:19:08', '2024-03-01 23:19:08'),
(490, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Tahun 2024)', '2024-03-01 23:19:09', '2024-03-01 23:19:09'),
(491, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Assign Checklist', '2024-03-01 23:19:15', '2024-03-01 23:19:15'),
(492, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Tahun 2024)', '2024-03-01 23:19:15', '2024-03-01 23:19:15'),
(493, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Assign Checklist', '2024-03-01 23:19:43', '2024-03-01 23:19:43'),
(494, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Tahun 2024)', '2024-03-01 23:19:43', '2024-03-01 23:19:43'),
(495, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Tahun 2024)', '2024-03-01 23:21:12', '2024-03-01 23:21:12'),
(496, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Tahun 2024)', '2024-03-01 23:38:28', '2024-03-01 23:38:28'),
(497, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Tahun 2024)', '2024-03-01 23:38:57', '2024-03-01 23:38:57'),
(498, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 23:39:31', '2024-03-01 23:39:31'),
(499, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Tahun 2024)', '2024-03-01 23:41:24', '2024-03-01 23:41:24'),
(500, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Tahun 2024)', '2024-03-01 23:42:20', '2024-03-01 23:42:20'),
(501, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Tahun 2024)', '2024-03-01 23:42:58', '2024-03-01 23:42:58'),
(502, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Checklist', '2024-03-01 23:45:25', '2024-03-01 23:45:25'),
(503, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 23:45:27', '2024-03-01 23:45:27'),
(504, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 23:47:06', '2024-03-01 23:47:06'),
(505, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Period Checklist', '2024-03-01 23:47:33', '2024-03-01 23:47:33'),
(506, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 23:47:34', '2024-03-01 23:47:34'),
(507, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Jaringan Checklist', '2024-03-01 23:47:38', '2024-03-01 23:47:38'),
(508, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Periode Form Checklist', '2024-03-01 23:47:41', '2024-03-01 23:47:41'),
(509, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 23:47:54', '2024-03-01 23:47:54'),
(510, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 23:48:11', '2024-03-01 23:48:11'),
(511, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 23:48:20', '2024-03-01 23:48:20'),
(512, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Jaringan Checklist', '2024-03-01 23:48:28', '2024-03-01 23:48:28'),
(513, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Periode Form Checklist', '2024-03-01 23:48:32', '2024-03-01 23:48:32'),
(514, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Periode Form Checklist', '2024-03-01 23:49:19', '2024-03-01 23:49:19'),
(515, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 23:49:23', '2024-03-01 23:49:23'),
(516, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Tahun 2024)', '2024-03-01 23:49:28', '2024-03-01 23:49:28'),
(517, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Assign Checklist', '2024-03-01 23:49:40', '2024-03-01 23:49:40'),
(518, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Tahun 2024)', '2024-03-01 23:49:40', '2024-03-01 23:49:40'),
(519, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 23:49:46', '2024-03-01 23:49:46'),
(520, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Tahun 2024)', '2024-03-01 23:49:50', '2024-03-01 23:49:50'),
(521, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Jaringan Checklist', '2024-03-01 23:49:56', '2024-03-01 23:49:56'),
(522, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Periode Form Checklist', '2024-03-01 23:49:59', '2024-03-01 23:49:59'),
(523, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 23:50:06', '2024-03-01 23:50:06'),
(524, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 23:52:13', '2024-03-01 23:52:13'),
(525, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 23:52:17', '2024-03-01 23:52:17'),
(526, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mingguan)', '2024-03-01 23:52:21', '2024-03-01 23:52:21'),
(527, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Assign Checklist', '2024-03-01 23:52:27', '2024-03-01 23:52:27'),
(528, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mingguan)', '2024-03-01 23:52:27', '2024-03-01 23:52:27'),
(529, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Assign Checklist', '2024-03-01 23:52:33', '2024-03-01 23:52:33'),
(530, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mingguan)', '2024-03-01 23:52:34', '2024-03-01 23:52:34'),
(531, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Assign Checklist', '2024-03-01 23:52:41', '2024-03-01 23:52:41'),
(532, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mingguan)', '2024-03-01 23:52:41', '2024-03-01 23:52:41'),
(533, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Assign Checklist', '2024-03-01 23:52:56', '2024-03-01 23:52:56'),
(534, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mingguan)', '2024-03-01 23:52:57', '2024-03-01 23:52:57'),
(535, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mingguan)', '2024-03-01 23:53:20', '2024-03-01 23:53:20'),
(536, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Assign Checklist', '2024-03-01 23:53:27', '2024-03-01 23:53:27'),
(537, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Mingguan)', '2024-03-01 23:53:28', '2024-03-01 23:53:28'),
(538, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 23:53:32', '2024-03-01 23:53:32'),
(539, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Jaringan Checklist', '2024-03-01 23:53:37', '2024-03-01 23:53:37'),
(540, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Periode Form Checklist', '2024-03-01 23:53:41', '2024-03-01 23:53:41'),
(541, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 23:53:43', '2024-03-01 23:53:43'),
(542, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 23:55:23', '2024-03-01 23:55:23'),
(543, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Period Checklist', '2024-03-01 23:55:49', '2024-03-01 23:55:49'),
(544, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 23:55:49', '2024-03-01 23:55:49'),
(545, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Tri Wulan)', '2024-03-01 23:55:52', '2024-03-01 23:55:52'),
(546, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Assign Checklist', '2024-03-01 23:55:58', '2024-03-01 23:55:58'),
(547, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Tri Wulan)', '2024-03-01 23:55:58', '2024-03-01 23:55:58'),
(548, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Assign Checklist', '2024-03-01 23:56:04', '2024-03-01 23:56:04'),
(549, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Tri Wulan)', '2024-03-01 23:56:04', '2024-03-01 23:56:04'),
(550, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Assign Checklist', '2024-03-01 23:56:10', '2024-03-01 23:56:10'),
(551, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Tri Wulan)', '2024-03-01 23:56:10', '2024-03-01 23:56:10'),
(552, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Assign Checklist', '2024-03-01 23:56:22', '2024-03-01 23:56:22'),
(553, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Tri Wulan)', '2024-03-01 23:56:22', '2024-03-01 23:56:22'),
(554, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Assign Checklist', '2024-03-01 23:56:29', '2024-03-01 23:56:29'),
(555, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Tri Wulan)', '2024-03-01 23:56:29', '2024-03-01 23:56:29'),
(556, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Assign Checklist', '2024-03-01 23:56:42', '2024-03-01 23:56:42'),
(557, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Tri Wulan)', '2024-03-01 23:56:42', '2024-03-01 23:56:42'),
(558, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Jaringan Checklist', '2024-03-01 23:56:46', '2024-03-01 23:56:46'),
(559, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Periode Form Checklist', '2024-03-01 23:56:50', '2024-03-01 23:56:50'),
(560, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 23:56:53', '2024-03-01 23:56:53'),
(561, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Start Checklist :', '2024-03-01 23:57:09', '2024-03-01 23:57:09'),
(562, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 23:57:10', '2024-03-01 23:57:10'),
(563, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 23:57:16', '2024-03-01 23:57:16'),
(564, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 23:57:31', '2024-03-01 23:57:31'),
(565, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Jaringan Checklist', '2024-03-01 23:57:43', '2024-03-01 23:57:43'),
(566, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Periode Form Checklist', '2024-03-01 23:57:46', '2024-03-01 23:57:46'),
(567, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 23:57:48', '2024-03-01 23:57:48'),
(568, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Start Checklist :', '2024-03-01 23:57:53', '2024-03-01 23:57:53'),
(569, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 23:57:53', '2024-03-01 23:57:53'),
(570, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 23:57:57', '2024-03-01 23:57:57'),
(571, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Data Checklist, Period: ', '2024-03-01 23:58:08', '2024-03-01 23:58:08'),
(572, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View Checklist Form:', '2024-03-01 23:58:15', '2024-03-01 23:58:15'),
(573, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 23:58:43', '2024-03-01 23:58:43'),
(574, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Tri Wulan)', '2024-03-01 23:58:50', '2024-03-01 23:58:50'),
(575, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Period Checklist', '2024-03-01 23:59:20', '2024-03-01 23:59:20'),
(576, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Period Checklist', '2024-03-01 23:59:20', '2024-03-01 23:59:20'),
(577, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Semester)', '2024-03-01 23:59:24', '2024-03-01 23:59:24'),
(578, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Semester)', '2024-03-01 23:59:52', '2024-03-01 23:59:52'),
(579, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Semester)', '2024-03-02 00:00:12', '2024-03-02 00:00:12'),
(580, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Semester)', '2024-03-02 00:00:33', '2024-03-02 00:00:33'),
(581, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Semester)', '2024-03-02 00:00:53', '2024-03-02 00:00:53'),
(582, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Semester)', '2024-03-02 00:01:11', '2024-03-02 00:01:11'),
(583, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Semester)', '2024-03-02 00:04:41', '2024-03-02 00:04:41'),
(584, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Semester)', '2024-03-02 00:04:53', '2024-03-02 00:04:53'),
(585, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Semester)', '2024-03-02 00:10:41', '2024-03-02 00:10:41'),
(586, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Semester)', '2024-03-02 00:10:59', '2024-03-02 00:10:59'),
(587, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Semester)', '2024-03-02 00:14:03', '2024-03-02 00:14:03'),
(588, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Semester)', '2024-03-02 00:14:26', '2024-03-02 00:14:26'),
(589, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'Create New Assign Checklist', '2024-03-02 00:14:41', '2024-03-02 00:14:41'),
(590, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 122', 'View List Mst Assign Checklist (Periode Semester)', '2024-03-02 00:14:41', '2024-03-02 00:14:41');

-- --------------------------------------------------------

--
-- Table structure for table `checklist_jaringan`
--

CREATE TABLE `checklist_jaringan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_periode` bigint(20) UNSIGNED NOT NULL,
  `type_checklist` varchar(255) NOT NULL,
  `total_checklist` int(11) DEFAULT NULL,
  `checklist_remaining` int(11) DEFAULT 0,
  `status` int(11) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `checklist_jaringan`
--

INSERT INTO `checklist_jaringan` (`id`, `id_periode`, `type_checklist`, `total_checklist`, `checklist_remaining`, `status`, `start_date`, `created_at`, `updated_at`) VALUES
(1, 1, 'H1 People', 1, 1, NULL, NULL, '2024-03-01 23:49:40', '2024-03-01 23:49:40'),
(2, 1, 'H1 Premises', 3, 1, 0, '2024-03-02 06:57:53', '2024-03-01 23:49:40', '2024-03-01 23:58:08'),
(3, 2, 'H1 People', 1, 1, NULL, NULL, '2024-03-01 23:53:27', '2024-03-01 23:53:27'),
(4, 2, 'H1 Premises', 3, 3, NULL, NULL, '2024-03-01 23:53:27', '2024-03-01 23:53:27'),
(5, 3, 'H1 People', 1, 1, NULL, NULL, '2024-03-01 23:56:42', '2024-03-01 23:56:42'),
(6, 3, 'H1 Premises', 4, 0, 1, '2024-03-02 06:57:09', '2024-03-01 23:56:42', '2024-03-01 23:57:31');

-- --------------------------------------------------------

--
-- Table structure for table `checklist_response`
--

CREATE TABLE `checklist_response` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_assign_checklist` bigint(20) UNSIGNED NOT NULL,
  `response` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `checklist_response`
--

INSERT INTO `checklist_response` (`id`, `id_assign_checklist`, `response`, `created_at`, `updated_at`) VALUES
(3, 1, 'Exist, Good', '2024-03-01 21:29:28', '2024-03-01 21:29:28'),
(4, 2, 'Exist Not Good', '2024-03-01 21:29:28', '2024-03-01 21:29:28'),
(5, 1, 'Exist, Good', '2024-03-01 23:57:31', '2024-03-01 23:57:31'),
(6, 2, 'Exist Not Good', '2024-03-01 23:57:31', '2024-03-01 23:57:31'),
(7, 3, 'Exist, Good', '2024-03-01 23:57:31', '2024-03-01 23:57:31'),
(8, 4, 'Not Exist', '2024-03-01 23:57:31', '2024-03-01 23:57:31'),
(9, 1, 'Exist, Good', '2024-03-01 23:58:08', '2024-03-01 23:58:08'),
(10, 2, 'Exist Not Good', '2024-03-01 23:58:08', '2024-03-01 23:58:08');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_01_26_091100_mst_dealers', 2),
(6, '2024_01_26_091106_mst_employees', 2),
(7, '2024_01_26_131756_add_column_type_to_mst_dealers', 3),
(8, '2024_01_26_132341_add_email_dept_position_coloumn__mst_employees', 3),
(9, '2016_06_01_000001_create_oauth_auth_codes_table', 4),
(10, '2016_06_01_000002_create_oauth_access_tokens_table', 4),
(11, '2016_06_01_000003_create_oauth_refresh_tokens_table', 4),
(12, '2016_06_01_000004_create_oauth_clients_table', 4),
(13, '2016_06_01_000005_create_oauth_personal_access_clients_table', 4),
(14, '2024_02_02_082659_add_4_coloumn__mst_employees', 5),
(15, '2024_02_02_083849_add_1_coloumn__mst_employees', 5),
(16, '2024_02_02_091134_add_1_coloumn_update_mst_dealers', 5),
(17, '2024_02_02_093956_add_4_coloumn__mst_dealers', 5),
(21, '2024_02_02_113619_mst_checklists', 6),
(22, '2024_02_02_114955_mst_checklist_details', 6),
(23, '2024_02_04_102624_mst_parent_cheklists', 6),
(25, '2024_02_04_104204_mst_periode_checklists', 7),
(26, '2024_02_04_104634_mst_assign_checklists', 7),
(27, '2024_02_04_102651_add_and_modify_colomn', 8),
(28, '2024_02_04_105626_modify_colomn_checklist', 9),
(29, '2024_03_01_234553_create_cheklist_jaringan_table', 9),
(30, '2024_03_02_035856_create_checklist_respons_table', 10);

-- --------------------------------------------------------

--
-- Table structure for table `mst_assign_checklists`
--

CREATE TABLE `mst_assign_checklists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_periode_checklist` bigint(20) UNSIGNED NOT NULL,
  `id_mst_checklist` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mst_assign_checklists`
--

INSERT INTO `mst_assign_checklists` (`id`, `id_periode_checklist`, `id_mst_checklist`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2024-03-01 23:19:01', '2024-03-01 23:19:01'),
(2, 1, 2, '2024-03-01 23:19:08', '2024-03-01 23:19:08'),
(3, 1, 3, '2024-03-01 23:19:15', '2024-03-01 23:19:15'),
(4, 1, 6, '2024-03-01 23:19:43', '2024-03-01 23:19:43'),
(5, 2, 1, '2024-03-01 23:52:27', '2024-03-01 23:52:27'),
(6, 2, 4, '2024-03-01 23:52:33', '2024-03-01 23:52:33'),
(7, 2, 6, '2024-03-01 23:52:41', '2024-03-01 23:52:41'),
(8, 2, 2, '2024-03-01 23:52:56', '2024-03-01 23:52:56'),
(9, 3, 1, '2024-03-01 23:55:58', '2024-03-01 23:55:58'),
(10, 3, 2, '2024-03-01 23:56:04', '2024-03-01 23:56:04'),
(11, 3, 3, '2024-03-01 23:56:10', '2024-03-01 23:56:10'),
(12, 3, 4, '2024-03-01 23:56:22', '2024-03-01 23:56:22'),
(13, 3, 6, '2024-03-01 23:56:28', '2024-03-01 23:56:28'),
(14, 4, 1, '2024-03-02 00:14:41', '2024-03-02 00:14:41');

-- --------------------------------------------------------

--
-- Table structure for table `mst_checklists`
--

CREATE TABLE `mst_checklists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_parent_checklist` bigint(20) UNSIGNED DEFAULT NULL,
  `child_point_checklist` varchar(255) DEFAULT NULL,
  `sub_point_checklist` varchar(255) NOT NULL,
  `indikator` text NOT NULL,
  `mandatory_silver` varchar(1) NOT NULL,
  `mandatory_gold` varchar(1) NOT NULL,
  `mandatory_platinum` varchar(1) NOT NULL,
  `upload_file` varchar(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mst_checklists`
--

INSERT INTO `mst_checklists` (`id`, `id_parent_checklist`, `child_point_checklist`, `sub_point_checklist`, `indikator`, `mandatory_silver`, `mandatory_gold`, `mandatory_platinum`, `upload_file`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Exterior', '<p><span style=\"font-size:11pt\"><span style=\"font-family:Calibri\">Exterior dalam keadaan: Bersih (dibersihkan 1 tahun sekali - bukti ditunjukan) , Rapih &amp; Tidak Rusak.<br />\r\nExterior terdiri dari: Fascia, Pylon / Projecting Sign, Window Display, Louver, dan Kanopi<br />\r\n<br />\r\nKiriteria PENGECATAN dinding Exterior (mana yang tercapai lebih dulu):<br />\r\n1. Pengecatan 3 tahun sekali (bukti ditunjukan) - Jika tidak ada temuaan anomali kondisi<br />\r\n2. Jika ditemukan kondisi anomali perlu segera diperbaiki<br />\r\n<br />\r\n*PENGECATAN: ditemukan kondisi cat Exterior kotor &amp; pudar<br />\r\n*KEBERSIHAN: ditemukan kondisi kotor yang bukan akibat dari proses waktu (kebocoran , sarang binatang, lumpur, jejak sepatu maka harus segera dibersihkan tanpa menunggu jadwal rutin pembersihan)<br />\r\n*BUKTI PEMBERSIHAN:<br />\r\n&nbsp;- Bila menggunakan Vendor maka&nbsp; Bukti Pembersihan berupa Kwitansi dan Dokumentasi dengan tanggal<br />\r\n&nbsp;- Bila dilakukan oleh Dealer sendiri maka Bukti Pembersihan berupa Dokumentasi dengan tanggal</span></span></p>', '1', '1', '1', '0', '2024-03-01 23:13:24', '2024-03-01 23:13:24'),
(2, 1, NULL, 'Interior', '<p><span style=\"font-size:11pt\"><span style=\"font-family:Calibri\">Kebersihan Interior :<br />\r\n- Dinding Interior<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - Kiriteria PENGECATAN (mana yang tercapai lebih dulu):<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1. Pengecatan 3 tahun sekali (bukti ditunjukan) - Jika tidak ada temuaan anomali kondisi<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2. Jika ditemukan kondisi anomali perlu segera diperbaiki<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - Bersih dari sarang binatang<br />\r\n*PENGECATAN: ditemukan kondisi cat Interior kotor &amp; pudar<br />\r\n*KEBERSIHAN: ditemukan kondisi kotor yang bukan akibat dari proses waktu (kebocoran , sarang binatang, lumpur, jejak sepatu maka harus segera dibersihkan tanpa menunggu jadwal rutin pembersihan)<br />\r\n*BUKTI PEMBERSIHAN:<br />\r\n&nbsp;- Bila menggunakan Vendor maka&nbsp; Bukti Pembersihan berupa Kwitansi dan Dokumentasi dengan tanggal<br />\r\n&nbsp;- Bila dilakukan oleh Dealer sendiri maka Bukti Pembersihan berupa Dokumentasi dengan tanggal<br />\r\n<br />\r\n- Lantai<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - Dibersihkan minimal sehari 2 kali - bukti ditunjukan dengan Checklist Harian<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - Dalam keadaan rapih dan tidak rusak<br />\r\n- Toilet<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - Dibersihkan minimal sehari 3 kali - bukti ditunjukan dengan Checklist Harian<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - Dalam keadaan rapih dan tidak rusak<br />\r\n- Furniture Standard Item:<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - Dibersihkan minimal sehari 2 kali - bukti ditunjukan dengan Checklist Harian<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - Dalam keadaan rapih dan tidak rusak<br />\r\n*Bila ditemukan kondisi kotor (perubahan warna) maka harus segera dibersihkan tanpa menunggu jadwal rutin pembersihan atau diganti bila sudah tidak bisa dibersihkan<br />\r\n<br />\r\n- Item Furniture Recommended Item<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - Dibersihkan minimal sehari 2 kali - bukti ditunjukan dengan Checklist Harian<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - Dalam keadaan rapih dan tidak rusak<br />\r\n*Bila ditemukan kondisi kotor (perubahan warna) maka harus segera dibersihkan tanpa menunggu jadwal rutin pembersihan atau diganti bila sudah tidak bisa dibersihkan<br />\r\n<br />\r\n- Display Motor (Dibersihkan minimal sehari 2 kali - bukti ditunjukan dengan Checklist Harian)</span></span></p>', '1', '1', '1', '0', '2024-03-01 23:13:52', '2024-03-01 23:13:52'),
(3, 1, NULL, 'Checklist Kebersihan Atribut', '<p><span style=\"font-size:11pt\"><span style=\"font-family:Calibri\">1. Terdapat checklist kebersihan untuk unit display, HRT, accessories, dan apparel<br />\r\n2. Terdapat kolom checklist kebersihan minimal pada jam 07.30, 12.00, 15.00</span></span></p>', '0', '0', '0', '0', '2024-03-01 23:14:29', '2024-03-01 23:14:29'),
(4, 2, NULL, 'Exterior', '<p><span style=\"font-size:11pt\"><span style=\"font-family:Calibri\">1. Sesuai dengan gambar tampak depan yang sudah diapprove bersama<br />\r\n2. Gambar eksterior yang sudah diapproved, dicetak, ditempel di ruang Kacab</span></span></p>', '0', '1', '1', '0', '2024-03-01 23:15:25', '2024-03-01 23:15:25'),
(5, 2, NULL, 'Interior', '<p><span style=\"font-size:11pt\"><span style=\"font-family:Calibri\">1. Area / Zoning Showroom sesuai dengan gambar yang sudah diapprove bersama :<br />\r\nItem Interior yang ada di gambar approval berada didalam showroom dan digunakan sesuai fungsinya, khususnya:<br />\r\n&nbsp;&nbsp;&nbsp; - Sales &amp; Finance Front Desk<br />\r\n&nbsp;&nbsp;&nbsp; - Negotiation Table&nbsp; minimal sejumlah yang ada didalam gambar approval<br />\r\n&nbsp;&nbsp;&nbsp; - Khusus Wing Dealer maka Posisi Wing Corner harus sesuai dengan gambar approval dan Item Interior yang ada didalam Wing Corner harus sesuai dan tidak boleh dipindahkan , terkecuali meja negosiasi bila harus digantikan oleh unit motor<br />\r\n2. Area / Zoning HUB / PENGHUBUNG sesuai dengan gambar yang sudah diapprove bersama :<br />\r\na. Ruang Tunggu<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp; - Posisi dan Fungsinya sesuai dengan gambar interior yang sudah diapprove bersama<br />\r\nb. Kasir<br />\r\n&nbsp;&nbsp;&nbsp; - Posisi dan Fungsinya sesuai dengan gambar interior yang sudah diapprove bersama<br />\r\n3.Area / Zoning&nbsp; H3 sesuai dengan gambar yang sudah diapprove bersama (4 Ruko)<br />\r\n4. Area / Zoning&nbsp; H2 sesuai dengan gambar yang sudah diapprove bersama<br />\r\n5. Gambar interior yang sudah diapproved, dicetak, ditempel di ruang Kacab (untuk Wing Dealer, gambar yang dipajang adalah gambar yang sudah terdapat wing corner)1. Area / Zoning Showroom sesuai dengan gambar yang sudah diapprove bersama :<br />\r\nItem Interior yang ada di gambar approval berada didalam showroom dan digunakan sesuai fungsinya, khususnya:<br />\r\n&nbsp;&nbsp;&nbsp; - Sales &amp; Finance Front Desk<br />\r\n&nbsp;&nbsp;&nbsp; - Negotiation Table&nbsp; minimal sejumlah yang ada didalam gambar approval<br />\r\n&nbsp;&nbsp;&nbsp; - Khusus Wing Dealer maka Posisi Wing Corner harus sesuai dengan gambar approval dan Item Interior yang ada didalam Wing Corner harus sesuai dan tidak boleh dipindahkan , terkecuali meja negosiasi bila harus digantikan oleh unit motor<br />\r\n2. Area / Zoning HUB / PENGHUBUNG sesuai dengan gambar yang sudah diapprove bersama :<br />\r\na. Ruang Tunggu<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp; - Posisi dan Fungsinya sesuai dengan gambar interior yang sudah diapprove bersama<br />\r\nb. Kasir<br />\r\n&nbsp;&nbsp;&nbsp; - Posisi dan Fungsinya sesuai dengan gambar interior yang sudah diapprove bersama<br />\r\n3.Area / Zoning&nbsp; H3 sesuai dengan gambar yang sudah diapprove bersama (4 Ruko)<br />\r\n4. Area / Zoning&nbsp; H2 sesuai dengan gambar yang sudah diapprove bersama<br />\r\n5. Gambar interior yang sudah diapproved, dicetak, ditempel di ruang Kacab (untuk Wing Dealer, gambar yang dipajang adalah gambar yang sudah terdapat wing corner)</span></span></p>', '0', '1', '1', '0', '2024-03-01 23:15:55', '2024-03-01 23:15:55'),
(6, 3, NULL, 'Jumlah', '<p><span style=\"font-size:11pt\"><span style=\"font-family:Calibri\">Dealer wajib memiliki 1 Kepala Cabang&nbsp;</span></span></p>', '1', '1', '1', '0', '2024-03-01 23:16:39', '2024-03-01 23:16:39');

-- --------------------------------------------------------

--
-- Table structure for table `mst_checklist_details`
--

CREATE TABLE `mst_checklist_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_checklist` bigint(20) UNSIGNED NOT NULL,
  `result` varchar(255) NOT NULL,
  `meta_name` varchar(255) NOT NULL,
  `meta_value` varchar(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mst_checklist_details`
--

INSERT INTO `mst_checklist_details` (`id`, `id_checklist`, `result`, `meta_name`, `meta_value`, `created_at`, `updated_at`) VALUES
(1, 1, 'EG', 'Exist, Good', '1', '2024-03-01 23:16:55', '2024-03-01 23:16:55'),
(2, 1, 'ENG', 'Exist Not Good', '1', '2024-03-01 23:16:55', '2024-03-01 23:16:55'),
(3, 1, 'NE', 'Not Exist', '1', '2024-03-01 23:16:55', '2024-03-01 23:16:55'),
(4, 1, 'NA', 'N/A', '1', '2024-03-01 23:16:55', '2024-03-01 23:16:55'),
(5, 2, 'EG', 'Exist, Good', '1', '2024-03-01 23:17:13', '2024-03-01 23:17:13'),
(6, 2, 'ENG', 'Exist Not Good', '1', '2024-03-01 23:17:13', '2024-03-01 23:17:13'),
(7, 2, 'NE', 'Not Exist', '1', '2024-03-01 23:17:13', '2024-03-01 23:17:13'),
(8, 2, 'NA', 'N/A', '1', '2024-03-01 23:17:13', '2024-03-01 23:17:13'),
(9, 3, 'EG', 'Exist, Good', '1', '2024-03-01 23:17:31', '2024-03-01 23:17:31'),
(10, 3, 'ENG', 'Exist Not Good', '1', '2024-03-01 23:17:31', '2024-03-01 23:17:31'),
(11, 3, 'NE', 'Not Exist', '1', '2024-03-01 23:17:31', '2024-03-01 23:17:31'),
(12, 3, 'NA', 'N/A', '1', '2024-03-01 23:17:31', '2024-03-01 23:17:31'),
(13, 4, 'EG', 'Exist, Good', '1', '2024-03-01 23:17:43', '2024-03-01 23:17:43'),
(14, 4, 'ENG', 'Exist Not Good', '1', '2024-03-01 23:17:43', '2024-03-01 23:17:43'),
(15, 4, 'NE', 'Not Exist', '1', '2024-03-01 23:17:43', '2024-03-01 23:17:43'),
(16, 4, 'NA', 'N/A', '1', '2024-03-01 23:17:43', '2024-03-01 23:17:43'),
(17, 5, 'EG', 'Exist, Good', '1', '2024-03-01 23:17:58', '2024-03-01 23:17:58'),
(18, 5, 'ENG', 'Exist Not Good', '1', '2024-03-01 23:17:58', '2024-03-01 23:17:58'),
(19, 5, 'NE', 'Not Exist', '1', '2024-03-01 23:17:58', '2024-03-01 23:17:58'),
(20, 5, 'NA', 'N/A', '1', '2024-03-01 23:17:58', '2024-03-01 23:17:58'),
(21, 6, 'EG', 'Exist, Good', '1', '2024-03-01 23:18:16', '2024-03-01 23:18:16'),
(22, 6, 'NE', 'Not Exist', '1', '2024-03-01 23:18:16', '2024-03-01 23:18:16'),
(23, 6, 'NA', 'N/A', '1', '2024-03-01 23:18:16', '2024-03-01 23:18:16');

-- --------------------------------------------------------

--
-- Table structure for table `mst_dealers`
--

CREATE TABLE `mst_dealers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dealer_name` varchar(255) NOT NULL,
  `dealer_address` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `dealer_code` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `subdistrict` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mst_dealers`
--

INSERT INTO `mst_dealers` (`id`, `dealer_name`, `dealer_address`, `created_at`, `updated_at`, `type`, `dealer_code`, `province`, `city`, `district`, `subdistrict`) VALUES
(1, 'Dealer A', 'Alamat Dealer A', '2024-01-26 02:59:00', '2024-01-26 02:59:00', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'Dealer B', 'Alamat Dealer B', '2024-01-26 03:34:47', '2024-01-26 03:34:47', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mst_departments`
--

CREATE TABLE `mst_departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `is_active` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mst_departments`
--

INSERT INTO `mst_departments` (`id`, `department_name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Auditor Department', '1', '2024-01-19 22:14:27', '2024-01-19 22:14:27'),
(2, 'Test Department', '1', '2024-01-19 22:14:36', '2024-01-19 22:14:36');

-- --------------------------------------------------------

--
-- Table structure for table `mst_dropdowns`
--

CREATE TABLE `mst_dropdowns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category` varchar(255) NOT NULL,
  `name_value` varchar(255) NOT NULL,
  `code_format` varchar(255) NOT NULL,
  `is_active` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mst_dropdowns`
--

INSERT INTO `mst_dropdowns` (`id`, `category`, `name_value`, `code_format`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Role User', 'Super Admin', 'SA', '1', '2024-01-19 21:35:49', '2024-01-19 21:35:49'),
(2, 'Role User', 'Admin', 'AD', '1', '2024-01-19 21:38:10', '2024-01-19 21:44:47'),
(3, 'Type Checklist', 'H1 Premises', 'H1Premises', '1', '2024-03-01 17:26:31', '2024-03-01 17:26:31'),
(4, 'Type Checklist', 'H1 People', 'H1People', '1', '2024-03-01 17:26:40', '2024-03-01 17:26:40'),
(5, 'Type Mark Checklist', 'Exist, Good', 'EG', '1', '2024-03-01 17:27:12', '2024-03-01 17:27:12'),
(6, 'Type Mark Checklist', 'Exist Not Good', 'ENG', '1', '2024-03-01 17:27:36', '2024-03-01 17:27:36'),
(7, 'Type Mark Checklist', 'Not Exist', 'NE', '1', '2024-03-01 17:27:57', '2024-03-01 17:27:57'),
(8, 'Type Mark Checklist', 'N/A', 'NA', '1', '2024-03-01 17:28:09', '2024-03-01 17:28:09'),
(9, 'Type Dealer', 'Main Dealer', 'MD', '1', '2024-03-01 17:41:35', '2024-03-01 17:41:35'),
(10, 'Type Dealer', 'Wing Dealer', 'WD', '1', '2024-03-01 17:41:52', '2024-03-01 17:41:52'),
(11, 'Type Dealer', 'Ahas Dealer', 'AD', '1', '2024-03-01 17:42:04', '2024-03-01 17:42:04');

-- --------------------------------------------------------

--
-- Table structure for table `mst_employees`
--

CREATE TABLE `mst_employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_dealer` bigint(20) UNSIGNED NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `employee_nik` varchar(255) NOT NULL,
  `employee_telephone` varchar(255) NOT NULL,
  `employee_address` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `id_dept` bigint(20) UNSIGNED DEFAULT NULL,
  `id_position` bigint(20) UNSIGNED DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `subdistrict` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mst_employees`
--

INSERT INTO `mst_employees` (`id`, `id_dealer`, `employee_name`, `employee_nik`, `employee_telephone`, `employee_address`, `created_at`, `updated_at`, `email`, `id_dept`, `id_position`, `province`, `city`, `district`, `subdistrict`, `postal_code`) VALUES
(1, 2, 'User 1', '12239890908', '08827789009', 'alamat user 1', '2024-01-26 03:31:43', '2024-01-26 03:43:06', 'user@gmail.com', 1, 1, NULL, NULL, NULL, NULL, NULL),
(2, 1, 'User 1', '12239890908', '088274090609', 'Serbajadi 1', '2024-01-26 07:56:43', '2024-01-26 07:56:43', 'user1@gmail.com', 1, 2, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mst_parent_checklists`
--

CREATE TABLE `mst_parent_checklists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type_checklist` varchar(255) NOT NULL,
  `parent_point_checklist` varchar(255) NOT NULL,
  `path_guide_premises` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mst_parent_checklists`
--

INSERT INTO `mst_parent_checklists` (`id`, `type_checklist`, `parent_point_checklist`, `path_guide_premises`, `created_at`, `updated_at`) VALUES
(1, 'H1 Premises', 'Kebersihan Network', 'assets/images/thumbnails/mXbljxOIEbKSvWpEBF5pp9C6hPKZRq9MjGueZxFD.png', '2024-03-01 23:13:24', '2024-03-01 23:13:24'),
(2, 'H1 Premises', 'Approval Layout New VinCi', 'assets/images/thumbnails/KGFfrO7HIB8zUyFDrG9fCm4wiNAXMFuHvHI6gdMi.png', '2024-03-01 23:15:25', '2024-03-01 23:15:25'),
(3, 'H1 People', 'Kepala', 'assets/images/thumbnails/Usk10tpQBlD8DSnaDpzEgFseVl4x2Ven1POphOeR.png', '2024-03-01 23:16:39', '2024-03-01 23:16:39');

-- --------------------------------------------------------

--
-- Table structure for table `mst_periode_checklists`
--

CREATE TABLE `mst_periode_checklists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `period` text NOT NULL,
  `id_branch` bigint(20) UNSIGNED NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `is_active` varchar(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mst_periode_checklists`
--

INSERT INTO `mst_periode_checklists` (`id`, `period`, `id_branch`, `start_date`, `end_date`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Periode Tahun 2024', 1, '2024-03-02 00:00:00', '2024-04-02 00:00:00', '1', '2024-03-01 23:18:46', '2024-03-01 23:49:40'),
(2, 'Periode Mingguan', 1, '2024-03-02 00:00:00', '2024-03-09 00:00:00', '1', '2024-03-01 23:47:33', '2024-03-01 23:53:27'),
(3, 'Periode Tri Wulan', 1, '2024-03-02 00:00:00', '2024-05-02 00:00:00', '1', '2024-03-01 23:55:49', '2024-03-01 23:56:42'),
(4, 'Periode Semester', 1, '2024-03-02 00:00:00', '2024-08-02 00:00:00', '0', '2024-03-01 23:59:20', '2024-03-01 23:59:20');

-- --------------------------------------------------------

--
-- Table structure for table `mst_positions`
--

CREATE TABLE `mst_positions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_department` varchar(255) NOT NULL,
  `position_name` varchar(255) NOT NULL,
  `is_active` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mst_positions`
--

INSERT INTO `mst_positions` (`id`, `id_department`, `position_name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '1', 'Test', '1', '2024-01-19 22:36:57', '2024-01-19 22:36:57'),
(2, '1', 'test2', '1', '2024-01-26 07:05:39', '2024-01-26 07:05:39');

-- --------------------------------------------------------

--
-- Table structure for table `mst_rules`
--

CREATE TABLE `mst_rules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rule_name` varchar(255) NOT NULL,
  `rule_value` varchar(255) NOT NULL,
  `is_active` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mst_rules`
--

INSERT INTO `mst_rules` (`id`, `rule_name`, `rule_value`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Test', '123', '0', '2024-01-19 21:58:12', '2024-01-19 21:59:02');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `secret` varchar(100) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `redirect` text NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) NOT NULL,
  `access_token_id` varchar(100) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `is_superadmin` varchar(1) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `login_counter` int(11) DEFAULT NULL,
  `is_active` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `is_superadmin`, `last_login`, `login_counter`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Admin Dev', 'admindev@gmail.com', NULL, '$2y$12$fzdDANrgXpR8um1N0FYZo.fLXl6876J/6AoDYUO/KG4e1UEehXyiK', NULL, 'Super Admin', '0', NULL, NULL, '1', '2024-01-19 19:46:46', '2024-01-19 19:55:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `checklist_jaringan`
--
ALTER TABLE `checklist_jaringan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cheklist_jaringan_id_periode_foreign` (`id_periode`);

--
-- Indexes for table `checklist_response`
--
ALTER TABLE `checklist_response`
  ADD PRIMARY KEY (`id`),
  ADD KEY `checklist_response_id_assign_checklist_foreign` (`id_assign_checklist`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mst_assign_checklists`
--
ALTER TABLE `mst_assign_checklists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mst_assign_checklists_id_periode_checklist_foreign` (`id_periode_checklist`);

--
-- Indexes for table `mst_checklists`
--
ALTER TABLE `mst_checklists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mst_checklists_id_parent_checklist_foreign` (`id_parent_checklist`);

--
-- Indexes for table `mst_checklist_details`
--
ALTER TABLE `mst_checklist_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mst_checklist_details_id_checklist_foreign` (`id_checklist`);

--
-- Indexes for table `mst_dealers`
--
ALTER TABLE `mst_dealers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mst_departments`
--
ALTER TABLE `mst_departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mst_dropdowns`
--
ALTER TABLE `mst_dropdowns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mst_employees`
--
ALTER TABLE `mst_employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mst_employees_email_unique` (`email`),
  ADD KEY `mst_employees_id_dealer_foreign` (`id_dealer`),
  ADD KEY `mst_employees_id_dept_foreign` (`id_dept`),
  ADD KEY `mst_employees_id_position_foreign` (`id_position`);

--
-- Indexes for table `mst_parent_checklists`
--
ALTER TABLE `mst_parent_checklists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mst_periode_checklists`
--
ALTER TABLE `mst_periode_checklists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mst_periode_checklists_id_branch_foreign` (`id_branch`);

--
-- Indexes for table `mst_positions`
--
ALTER TABLE `mst_positions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mst_rules`
--
ALTER TABLE `mst_rules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=591;

--
-- AUTO_INCREMENT for table `checklist_jaringan`
--
ALTER TABLE `checklist_jaringan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `checklist_response`
--
ALTER TABLE `checklist_response`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `mst_assign_checklists`
--
ALTER TABLE `mst_assign_checklists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `mst_checklists`
--
ALTER TABLE `mst_checklists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `mst_checklist_details`
--
ALTER TABLE `mst_checklist_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `mst_dealers`
--
ALTER TABLE `mst_dealers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mst_departments`
--
ALTER TABLE `mst_departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mst_dropdowns`
--
ALTER TABLE `mst_dropdowns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `mst_employees`
--
ALTER TABLE `mst_employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mst_parent_checklists`
--
ALTER TABLE `mst_parent_checklists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mst_periode_checklists`
--
ALTER TABLE `mst_periode_checklists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `mst_positions`
--
ALTER TABLE `mst_positions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mst_rules`
--
ALTER TABLE `mst_rules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `checklist_jaringan`
--
ALTER TABLE `checklist_jaringan`
  ADD CONSTRAINT `cheklist_jaringan_id_periode_foreign` FOREIGN KEY (`id_periode`) REFERENCES `mst_periode_checklists` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `checklist_response`
--
ALTER TABLE `checklist_response`
  ADD CONSTRAINT `checklist_response_id_assign_checklist_foreign` FOREIGN KEY (`id_assign_checklist`) REFERENCES `mst_assign_checklists` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mst_assign_checklists`
--
ALTER TABLE `mst_assign_checklists`
  ADD CONSTRAINT `mst_assign_checklists_id_periode_checklist_foreign` FOREIGN KEY (`id_periode_checklist`) REFERENCES `mst_periode_checklists` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mst_checklists`
--
ALTER TABLE `mst_checklists`
  ADD CONSTRAINT `mst_checklists_id_parent_checklist_foreign` FOREIGN KEY (`id_parent_checklist`) REFERENCES `mst_parent_checklists` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mst_checklist_details`
--
ALTER TABLE `mst_checklist_details`
  ADD CONSTRAINT `mst_checklist_details_id_checklist_foreign` FOREIGN KEY (`id_checklist`) REFERENCES `mst_checklists` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mst_employees`
--
ALTER TABLE `mst_employees`
  ADD CONSTRAINT `mst_employees_id_dealer_foreign` FOREIGN KEY (`id_dealer`) REFERENCES `mst_dealers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mst_employees_id_dept_foreign` FOREIGN KEY (`id_dept`) REFERENCES `mst_departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mst_employees_id_position_foreign` FOREIGN KEY (`id_position`) REFERENCES `mst_positions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mst_periode_checklists`
--
ALTER TABLE `mst_periode_checklists`
  ADD CONSTRAINT `mst_periode_checklists_id_branch_foreign` FOREIGN KEY (`id_branch`) REFERENCES `mst_dealers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
