-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 29, 2025 at 01:44 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `penjualan`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(2, 'Desain', '<p>Barang-barang yang termasuk desain grafis 1</p>', '2025-10-09 20:43:53', '2025-10-09 21:53:29'),
(3, 'Fotografi', '<p>Hasil Jepretan kamera atau kamera hp</p>', '2025-10-10 23:56:06', '2025-10-10 23:56:06');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2025_10_03_040623_create_users_table', 1),
(4, '2025_10_03_075600_create_products_table', 2),
(5, '2025_10_03_075616_create_orders_table', 2),
(6, '2025_10_03_075627_create_order_items_table', 2),
(7, '2025_10_03_075641_create_payments_table', 2),
(8, '2025_10_04_061809_create_sessions_table', 3),
(9, '2025_10_09_035433_create_categories_table', 4),
(10, '2025_10_12_063953_create_transactions_table', 5),
(11, '2025_10_12_064035_create_transactions_details_table', 6),
(12, '2025_10_16_041932_add_role_to_users_table', 7),
(13, '2025_10_21_025708_add_tax_discount_to_transactions_table', 8),
(14, '2025_10_22_014120_add_discount_to_products_table', 9),
(15, '2025_10_24_010555_add_session_fields_to_users_table', 10),
(16, '2025_10_28_012325_add_last_activity_to_users_table', 11),
(17, '2025_10_29_011614_add_customer_name_to_transactions_table', 12);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `image` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(12,2) NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `discount` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Diskon dalam persen',
  `discount_amount` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'Diskon nominal',
  `has_discount` tinyint(1) NOT NULL DEFAULT '0',
  `final_price` decimal(12,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `image`, `name`, `description`, `price`, `stock`, `created_at`, `updated_at`, `discount`, `discount_amount`, `has_discount`, `final_price`) VALUES
(17, 2, '2KPALj8HSne8w6uOvZtxAhzYDGebPX2CpCzHA1im.png', 'Infografik', '<p>infografik yang menampilkan informasi&nbsp;</p>', 50000.00, 94, '2025-10-21 19:34:47', '2025-10-28 18:35:46', 10.00, 0.00, 1, 45000.00),
(18, 2, 'sUM7qnjqDhoPoVZHSBniEDidvZiZVO4bSyfnY7n9.png', 'Logo 1', '<p>logo punya fael</p>', 40000.00, 99, '2025-10-21 19:36:58', '2025-10-23 23:01:52', 10.00, 0.00, 1, 36000.00),
(19, 2, 'k2QuwiT34BqrY3BhWyz3GdB2Bf5Wy9GW2haZzg7r.png', 'logo G', '<p>asdasdasdasd</p>', 1000000.00, 100, '2025-10-21 20:12:18', '2025-10-21 20:12:48', 0.00, 0.00, 0, 1000000.00);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('PzrjhvBowqCvSuRg9uORvkwbIm6GV64XYQYgVVUO', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNXk2VFdDWlVsYzJDRWZDUThvbDNBMGFrcFNSVmNjOGNZYlhZMDB2MiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9wZW5qdWFsYW4udGVzdC9sb2dpbiI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1760671977);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `invoice` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total` decimal(12,2) NOT NULL,
  `payment` decimal(12,2) NOT NULL,
  `change_amount` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(12,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `invoice`, `customer_name`, `total`, `payment`, `change_amount`, `created_at`, `updated_at`, `subtotal`, `tax`, `discount`) VALUES
(1, 'INV-1760254507', NULL, 20000.00, 50000.00, 30000.00, '2025-10-12 00:35:07', '2025-10-12 00:35:07', 0.00, 0.00, 0.00),
(2, 'INV-1760255227', NULL, 10000.00, 15000.00, 5000.00, '2025-10-12 00:47:07', '2025-10-12 00:47:07', 0.00, 0.00, 0.00),
(3, 'INV-1760255308', NULL, 10000.00, 15000.00, 5000.00, '2025-10-12 00:48:28', '2025-10-12 00:48:28', 0.00, 0.00, 0.00),
(4, 'INV-1760330120', NULL, 60000.00, 10000.00, -50000.00, '2025-10-12 21:35:20', '2025-10-12 21:35:20', 0.00, 0.00, 0.00),
(5, 'INV-1760331774', NULL, 14000.00, 15000.00, 1000.00, '2025-10-12 22:02:54', '2025-10-12 22:02:54', 0.00, 0.00, 0.00),
(6, 'INV-1760331809', NULL, 20000.00, 20000.00, 0.00, '2025-10-12 22:03:29', '2025-10-12 22:03:29', 0.00, 0.00, 0.00),
(7, 'INV-1760332096', NULL, 20000.00, 20000.00, 0.00, '2025-10-12 22:08:16', '2025-10-12 22:08:16', 0.00, 0.00, 0.00),
(8, 'INV-1760332428', NULL, 20000.00, 20000.00, 0.00, '2025-10-12 22:13:48', '2025-10-12 22:13:48', 0.00, 0.00, 0.00),
(9, 'INV-1760332512', NULL, 30000.00, 50000.00, 20000.00, '2025-10-12 22:15:12', '2025-10-12 22:15:12', 0.00, 0.00, 0.00),
(10, 'INV-1760333544', NULL, 20000.00, 20000.00, 0.00, '2025-10-12 22:32:24', '2025-10-12 22:32:24', 0.00, 0.00, 0.00),
(11, 'INV-1760585766', NULL, 100000.00, 100000.00, 0.00, '2025-10-15 20:36:06', '2025-10-15 20:36:06', 0.00, 0.00, 0.00),
(12, 'INV-1760779682', NULL, 305000.00, 500000.00, 195000.00, '2025-10-18 02:28:02', '2025-10-18 02:28:02', 0.00, 0.00, 0.00),
(13, 'INV-1761015598', NULL, 344090.00, 400000.00, 55910.00, '2025-10-20 19:59:58', '2025-10-20 19:59:58', 0.00, 0.00, 0.00),
(14, 'INV-1761015886', NULL, 433990.00, 500000.00, 66010.00, '2025-10-20 20:04:46', '2025-10-20 20:04:46', 391000.00, 43010.00, 20.00),
(15, 'INV-1761016964', NULL, 61000.00, 70000.00, 9000.00, '2025-10-20 20:22:44', '2025-10-20 20:22:44', 100000.00, 11000.00, 50000.00),
(16, 'INV-1761104448', NULL, 90000.00, 100000.00, 10000.00, '2025-10-21 20:40:48', '2025-10-21 20:40:48', 90000.00, 0.00, 0.00),
(17, 'INV-1761285712', NULL, 89910.00, 100000.00, 10090.00, '2025-10-23 23:01:52', '2025-10-23 23:01:52', 81000.00, 8910.00, 0.00),
(18, 'INV-1761701746', 'Jojo', 149850.00, 150000.00, 150.00, '2025-10-28 18:35:46', '2025-10-28 18:35:46', 135000.00, 14850.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_details`
--

CREATE TABLE `transaction_details` (
  `id` bigint UNSIGNED NOT NULL,
  `transaction_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `qty` int NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_details`
--

INSERT INTO `transaction_details` (`id`, `transaction_id`, `product_id`, `qty`, `price`, `subtotal`, `created_at`, `updated_at`) VALUES
(25, 16, 17, 2, 45000.00, 90000.00, '2025-10-21 20:40:48', '2025-10-21 20:40:48'),
(26, 17, 17, 1, 45000.00, 45000.00, '2025-10-23 23:01:52', '2025-10-23 23:01:52'),
(27, 17, 18, 1, 36000.00, 36000.00, '2025-10-23 23:01:52', '2025-10-23 23:01:52'),
(28, 18, 17, 3, 45000.00, 135000.00, '2025-10-28 18:35:46', '2025-10-28 18:35:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kasir',
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login_ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `current_device` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_activity` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`, `role`, `session_id`, `last_login_ip`, `last_login_at`, `current_device`, `last_activity`) VALUES
(1, 'Gregorius Joses Davin Oemar', 'admin@admin.com', '$2y$12$WCogR6Ge1PZmL.Ib572sPOqeGy9Fl5ZbFEHXqQdBWPAJC6TddCvQm', '2025-10-15 21:32:26', '2025-10-28 18:44:33', 'admin', NULL, '127.0.0.1', '2025-10-28 18:32:15', NULL, NULL),
(2, 'Rafael Aria Oemar', 'kasir@kasir.com', '$2y$12$BCnP.V9NJkc6xImY4c0mnOqccYSyTlziGmuS.HnLoJEeOuzK2yYjC', '2025-10-15 21:32:27', '2025-10-27 17:48:30', 'kasir', NULL, '127.0.0.1', '2025-10-27 17:48:21', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transactions_invoice_unique` (`invoice`);

--
-- Indexes for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_details_transactions_id_foreign` (`transaction_id`),
  ADD KEY `transactions_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_session_id_unique` (`session_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `transaction_details`
--
ALTER TABLE `transaction_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD CONSTRAINT `transactions_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_details_transactions_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
