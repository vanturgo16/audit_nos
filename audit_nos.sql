-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 20, 2024 at 06:51 AM
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
-- Database: `audit_nos`
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
(76, 'admindev@gmail.com', '127.0.0.1', '0', 'Chrome 120', 'View List Mst User', '2024-01-19 22:45:44', '2024-01-19 22:45:44');

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
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1);

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
(2, 'Role User', 'Admin', 'AD', '1', '2024-01-19 21:38:10', '2024-01-19 21:44:47');

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
(1, '1', 'Test', '1', '2024-01-19 22:36:57', '2024-01-19 22:36:57');

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `mst_departments`
--
ALTER TABLE `mst_departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mst_dropdowns`
--
ALTER TABLE `mst_dropdowns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mst_positions`
--
ALTER TABLE `mst_positions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mst_rules`
--
ALTER TABLE `mst_rules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
