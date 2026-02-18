-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 16, 2026 at 03:20 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `helpdesk_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `category_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Icon class or emoji',
  `order_index` int NOT NULL DEFAULT '0' COMMENT 'For sorting display',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `description`, `icon`, `order_index`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Hardware', 'Masalah terkait perangkat keras (PC, Laptop, Printer, dll)', '🖥️', 1, 1, '2026-02-14 01:42:56', '2026-02-14 01:42:56'),
(2, 'Software', 'Masalah terkait aplikasi dan software', '💻', 2, 1, '2026-02-14 01:42:56', '2026-02-14 01:42:56'),
(3, 'Network', 'Masalah terkait jaringan dan konektivitas', '🌐', 3, 1, '2026-02-14 01:42:56', '2026-02-14 01:42:56'),
(4, 'DMS', 'Masalah terkait Dealer Management System', '📊', 4, 1, '2026-02-14 01:42:56', '2026-02-14 01:42:56'),
(5, 'Email & Account', 'Masalah terkait email dan akun akses', '📧', 5, 1, '2026-02-14 01:42:56', '2026-02-14 01:42:56'),
(6, 'Lain-lain', 'Masalah lain yang tidak termasuk kategori di atas', '📝', 99, 1, '2026-02-14 01:42:56', '2026-02-14 01:42:56');

-- --------------------------------------------------------

--
-- Table structure for table `dealer_branches`
--

CREATE TABLE `dealer_branches` (
  `id` bigint UNSIGNED NOT NULL,
  `branch_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pic_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Person in Charge',
  `pic_email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dealer_branches`
--

INSERT INTO `dealer_branches` (`id`, `branch_name`, `branch_code`, `address`, `phone`, `pic_name`, `pic_email`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Jakarta Selatan', 'JKT-SLT', 'Jl. Fatmawati No. 123, Jakarta Selatan', '021-7654321', 'Budi Santoso', 'budi.santoso@dealer.com', 1, '2026-02-14 01:42:55', '2026-02-14 01:42:55'),
(2, 'Jakarta Pusat', 'JKT-PST', 'Jl. Thamrin No. 45, Jakarta Pusat', '021-1234567', 'Siti Nurhaliza', 'siti.nurhaliza@dealer.com', 1, '2026-02-14 01:42:55', '2026-02-14 01:42:55'),
(3, 'Bandung', 'BDG', 'Jl. Dago No. 78, Bandung', '022-9876543', 'Andi Wijaya', 'andi.wijaya@dealer.com', 1, '2026-02-14 01:42:55', '2026-02-14 01:42:55'),
(4, 'Surabaya', 'SBY', 'Jl. Basuki Rahmat No. 90, Surabaya', '031-5551234', 'Dewi Lestari', 'dewi.lestari@dealer.com', 1, '2026-02-14 01:42:55', '2026-02-14 01:42:55'),
(5, 'Tangerang', 'TNG', 'Jl. BSD Boulevard No. 12, Tangerang', '021-5554321', 'Rudi Hartono', 'rudi.hartono@dealer.com', 1, '2026-02-14 01:42:55', '2026-02-14 01:42:55'),
(6, 'Bekasi', 'BKS', 'Jl. Ahmad Yani No. 56, Bekasi', '021-8887654', 'Linda Sari', 'linda.sari@dealer.com', 1, '2026-02-14 01:42:55', '2026-02-14 01:42:55'),
(7, 'Semarang', 'SMG', 'Jl. Pandanaran No. 34, Semarang', '024-7778899', 'Hendra Gunawan', 'hendra.gunawan@dealer.com', 1, '2026-02-14 01:42:55', '2026-02-14 01:42:55'),
(8, 'Yogyakarta', 'YGY', 'Jl. Kaliurang KM 5, Yogyakarta', '0274-123456', 'Putri Rahayu', 'putri.rahayu@dealer.com', 1, '2026-02-14 01:42:55', '2026-02-14 01:42:55');

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2014_10_12_100000_create_password_resets_table', 2),
(6, '2026_02_14_082833_add_role_and_branch_to_users_table', 2),
(7, '2026_02_14_082841_create_dealer_branches_table', 2),
(8, '2026_02_14_082851_create_categories_table', 2),
(9, '2026_02_14_082858_create_sub_categories_table', 2),
(10, '2026_02_14_082905_create_tickets_table', 2),
(11, '2026_02_14_082911_create_ticket_attachments_table', 2),
(12, '2026_02_14_082923_create_ticket_comments_table', 2),
(13, '2026_02_14_082930_create_ticket_logs_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `sub_category_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` enum('high','medium','low') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `sla_minutes` int NOT NULL COMMENT 'SLA in minutes: 30, 120, 1440, etc',
  `default_specialist_id` bigint UNSIGNED DEFAULT NULL COMMENT 'Default helpdesk user to auto-assign',
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`id`, `category_id`, `sub_category_name`, `priority`, `sla_minutes`, `default_specialist_id`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'PC/Laptop Rusak', 'high', 30, 5, 'PC atau Laptop tidak bisa menyala, hang, atau error berat', 1, '2026-02-14 01:42:56', '2026-02-14 01:42:56'),
(2, 1, 'Printer Bermasalah', 'medium', 120, 5, 'Printer tidak bisa print, paper jam, atau kualitas print buruk', 1, '2026-02-14 01:42:56', '2026-02-14 01:42:56'),
(3, 1, 'Mouse/Keyboard Rusak', 'low', 1440, 5, 'Mouse atau keyboard tidak berfungsi', 1, '2026-02-14 01:42:56', '2026-02-14 01:42:56'),
(4, 1, 'Monitor Bermasalah', 'medium', 120, 5, 'Monitor blank, bergaris, atau tidak tampil', 1, '2026-02-14 01:42:56', '2026-02-14 01:42:56'),
(5, 1, 'Scanner Error', 'medium', 240, 5, 'Scanner tidak bisa scan atau terdeteksi', 1, '2026-02-14 01:42:56', '2026-02-14 01:42:56'),
(6, 2, 'Aplikasi Error/Crash', 'high', 60, 4, 'Aplikasi tidak bisa dibuka atau sering crash', 1, '2026-02-14 01:42:56', '2026-02-14 01:42:56'),
(7, 2, 'Email Issue', 'medium', 240, 4, 'Tidak bisa kirim/terima email', 1, '2026-02-14 01:42:56', '2026-02-14 01:42:56'),
(8, 2, 'Install Aplikasi', 'low', 1440, 4, 'Request instalasi aplikasi baru', 1, '2026-02-14 01:42:56', '2026-02-14 01:42:56'),
(9, 2, 'Microsoft Office Error', 'medium', 120, 4, 'Word, Excel, PowerPoint bermasalah', 1, '2026-02-14 01:42:57', '2026-02-14 01:42:57'),
(10, 2, 'Antivirus Issue', 'medium', 180, 4, 'Antivirus tidak update atau terdeteksi virus', 1, '2026-02-14 01:42:57', '2026-02-14 01:42:57'),
(11, 3, 'Internet Down', 'high', 30, 3, 'Internet mati total di cabang', 1, '2026-02-14 01:42:57', '2026-02-14 01:42:57'),
(12, 3, 'Koneksi Lambat', 'medium', 120, 3, 'Internet sangat lambat', 1, '2026-02-14 01:42:57', '2026-02-14 01:42:57'),
(13, 3, 'WiFi Issue', 'medium', 240, 3, 'WiFi tidak terdeteksi atau tidak bisa connect', 1, '2026-02-14 01:42:57', '2026-02-14 01:42:57'),
(14, 3, 'VPN Error', 'high', 60, 3, 'Tidak bisa connect ke VPN', 1, '2026-02-14 01:42:57', '2026-02-14 01:42:57'),
(15, 3, 'LAN/Kabel Network', 'medium', 180, 3, 'Kabel network bermasalah atau port tidak berfungsi', 1, '2026-02-14 01:42:57', '2026-02-14 01:42:57'),
(16, 4, 'DMS Down/Error', 'high', 30, 6, 'DMS tidak bisa diakses atau error critical', 1, '2026-02-14 01:42:57', '2026-02-14 01:42:57'),
(17, 4, 'DMS Lambat', 'medium', 120, 6, 'DMS sangat lambat saat digunakan', 1, '2026-02-14 01:42:57', '2026-02-14 01:42:57'),
(18, 4, 'Data DMS Tidak Sinkron', 'high', 60, 6, 'Data di DMS tidak update atau hilang', 1, '2026-02-14 01:42:57', '2026-02-14 01:42:57'),
(19, 4, 'Laporan DMS Error', 'medium', 240, 6, 'Tidak bisa generate laporan atau print', 1, '2026-02-14 01:42:57', '2026-02-14 01:42:57'),
(20, 4, 'Akses DMS Bermasalah', 'medium', 120, 6, 'User tidak bisa login atau access denied', 1, '2026-02-14 01:42:57', '2026-02-14 01:42:57'),
(21, 5, 'Lupa Password', 'medium', 120, 4, 'User lupa password akun', 1, '2026-02-14 01:42:57', '2026-02-14 01:42:57'),
(22, 5, 'Reset Password', 'medium', 60, 4, 'Request reset password', 1, '2026-02-14 01:42:57', '2026-02-14 01:42:57'),
(23, 5, 'Buat User Baru', 'low', 1440, 4, 'Request pembuatan user/akun baru', 1, '2026-02-14 01:42:57', '2026-02-14 01:42:57'),
(24, 5, 'Email Tidak Bisa Login', 'high', 60, 4, 'Tidak bisa login ke email', 1, '2026-02-14 01:42:57', '2026-02-14 01:42:57'),
(25, 6, 'Request Bantuan Umum', 'low', 1440, 4, 'Bantuan IT umum lainnya', 1, '2026-02-14 01:42:57', '2026-02-14 01:42:57'),
(26, 6, 'Konsultasi IT', 'low', 2880, 4, 'Konsultasi terkait IT', 1, '2026-02-14 01:42:57', '2026-02-14 01:42:57');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` bigint UNSIGNED NOT NULL,
  `ticket_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dealer_branch_id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL COMMENT 'User ID who created ticket',
  `category_id` bigint UNSIGNED NOT NULL,
  `sub_category_id` bigint UNSIGNED NOT NULL,
  `subject` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` enum('high','medium','low') COLLATE utf8mb4_unicode_ci NOT NULL,
  `sla_minutes` int NOT NULL,
  `sla_deadline` timestamp NULL DEFAULT NULL,
  `assigned_to` bigint UNSIGNED DEFAULT NULL,
  `status` enum('new','assigned','in_progress','pending','resolved','closed','reopened') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `assigned_at` timestamp NULL DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `resolution_note` text COLLATE utf8mb4_unicode_ci,
  `actual_minutes_taken` int DEFAULT NULL,
  `sla_met` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `ticket_number`, `dealer_branch_id`, `created_by`, `category_id`, `sub_category_id`, `subject`, `description`, `priority`, `sla_minutes`, `sla_deadline`, `assigned_to`, `status`, `assigned_at`, `started_at`, `resolved_at`, `closed_at`, `resolution_note`, `actual_minutes_taken`, `sla_met`, `created_at`, `updated_at`) VALUES
(1, 'TKT-20260214-0001', 1, 7, 2, 7, 'Urgent: Unsigned Corporate Policies', 'kelass', 'medium', 240, '2026-02-14 07:03:20', 3, 'resolved', '2026-02-14 03:03:20', '2026-02-16 03:11:59', '2026-02-16 03:13:27', NULL, 'done yah', 2890, 0, '2026-02-14 03:03:20', '2026-02-16 03:13:27'),
(2, 'TKT-20260214-0002', 1, 7, 3, 11, 'ini tolong cepet di tindak waduh gabisa internetan ini', 'tolong cepet', 'high', 30, '2026-02-14 04:18:26', 3, 'resolved', '2026-02-14 03:48:26', '2026-02-16 03:11:24', '2026-02-16 03:13:13', NULL, 'done yah', 2844, 0, '2026-02-14 03:48:26', '2026-02-16 03:13:13');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_attachments`
--

CREATE TABLE `ticket_attachments` (
  `id` bigint UNSIGNED NOT NULL,
  `ticket_id` bigint UNSIGNED NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'MIME type',
  `file_size` int DEFAULT NULL COMMENT 'Size in bytes',
  `uploaded_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ticket_attachments`
--

INSERT INTO `ticket_attachments` (`id`, `ticket_id`, `file_name`, `file_path`, `file_type`, `file_size`, `uploaded_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'pngtree-skill-development-tracking-icon-png-image_15111584.png', 'ticket_attachments/1771038200_pngtree-skill-development-tracking-icon-png-image_15111584.png', 'image/png', 14710, 7, '2026-02-14 03:03:20', '2026-02-14 03:03:20');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_comments`
--

CREATE TABLE `ticket_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `ticket_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_internal` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'true = only IT can see, false = dealer can see',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ticket_comments`
--

INSERT INTO `ticket_comments` (`id`, `ticket_id`, `user_id`, `comment`, `is_internal`, `created_at`, `updated_at`) VALUES
(1, 2, 3, 'maaf saya baru sembuh dari sakit', 0, '2026-02-16 03:11:40', '2026-02-16 03:11:40'),
(2, 1, 3, 'baik akan saya kerjakan sebentar', 1, '2026-02-16 03:12:11', '2026-02-16 03:12:11'),
(3, 2, 3, '**RESOLVED**\n\ndone yah', 0, '2026-02-16 03:13:13', '2026-02-16 03:13:13'),
(4, 1, 3, '**RESOLVED**\n\ndone yah', 0, '2026-02-16 03:13:27', '2026-02-16 03:13:27');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_logs`
--

CREATE TABLE `ticket_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `ticket_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'created, assigned, status_changed, commented, etc',
  `old_value` text COLLATE utf8mb4_unicode_ci,
  `new_value` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ticket_logs`
--

INSERT INTO `ticket_logs` (`id`, `ticket_id`, `user_id`, `action`, `old_value`, `new_value`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'created', NULL, NULL, 'Ticket created by Dealer User 1 - JKT-SLT', '2026-02-14 03:03:20', '2026-02-14 03:03:20'),
(2, 1, NULL, 'assigned', NULL, 'Dewi - Helpdesk Software', 'Auto-assigned to Dewi - Helpdesk Software', '2026-02-14 03:03:20', '2026-02-14 03:03:20'),
(3, 1, 2, 'reassigned', 'Dewi - Helpdesk Software', 'Dewi - Helpdesk Software', 'Reassigned from Dewi - Helpdesk Software to Dewi - Helpdesk Software. Reason: tolong nih benerin ini', '2026-02-14 03:06:48', '2026-02-14 03:06:48'),
(4, 2, NULL, 'created', NULL, NULL, 'Ticket created by Dealer User 1 - JKT-SLT', '2026-02-14 03:48:26', '2026-02-14 03:48:26'),
(5, 2, NULL, 'assigned', NULL, 'Budi - Helpdesk Network', 'Auto-assigned to Budi - Helpdesk Network', '2026-02-14 03:48:26', '2026-02-14 03:48:26'),
(6, 2, 3, 'status_changed', 'assigned', 'in_progress', 'Budi - Helpdesk Network started working on this ticket', '2026-02-16 03:11:24', '2026-02-16 03:11:24'),
(7, 2, 3, 'commented', NULL, NULL, 'Budi - Helpdesk Network added a comment', '2026-02-16 03:11:40', '2026-02-16 03:11:40'),
(8, 1, 3, 'status_changed', 'assigned', 'in_progress', 'Budi - Helpdesk Network started working on this ticket', '2026-02-16 03:11:59', '2026-02-16 03:11:59'),
(9, 1, 3, 'commented', NULL, NULL, 'Budi - Helpdesk Network added a comment', '2026-02-16 03:12:11', '2026-02-16 03:12:11'),
(10, 2, 3, 'resolved', NULL, NULL, 'Budi - Helpdesk Network resolved this ticket', '2026-02-16 03:13:13', '2026-02-16 03:13:13'),
(11, 1, 3, 'resolved', NULL, NULL, 'Budi - Helpdesk Network resolved this ticket', '2026-02-16 03:13:27', '2026-02-16 03:13:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('dealer','admin_it','helpdesk','super_admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dealer',
  `dealer_branch_id` bigint UNSIGNED DEFAULT NULL COMMENT 'NULL for IT staff, filled for dealer users',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `dealer_branch_id`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'superadmin@helpdesk.com', 'super_admin', NULL, 1, NULL, '$2y$10$7NUKJIziW8OobU.mesEGJu5JOdVEBqiB3NV7IwoW7u5vOEuJtYB2K', NULL, '2026-02-14 01:42:55', '2026-02-14 01:42:55'),
(2, 'Admin IT', 'admin@helpdesk.com', 'admin_it', NULL, 1, NULL, '$2y$10$1P5XKK6ZgZzea29bQ3GJn.7GEFNmb3ck3lNlyEGlSiP2.gewoHVx6', NULL, '2026-02-14 01:42:55', '2026-02-14 01:42:55'),
(3, 'Budi - Helpdesk Network', 'budi.helpdesk@helpdesk.com', 'helpdesk', NULL, 1, NULL, '$2y$10$59ve3.eRMKOhueUZOQuKB./oWxEKdupAr8pxgLH08xRDmOf8w664C', NULL, '2026-02-14 01:42:55', '2026-02-14 01:42:55'),
(4, 'Dewi - Helpdesk Software', 'dewi.helpdesk@helpdesk.com', 'helpdesk', NULL, 1, NULL, '$2y$10$KxUoqUrTI4rGUauFjIvYnOdw6Y8X.dAsvUxUVXuDJh0S3H3ocDgx6', NULL, '2026-02-14 01:42:55', '2026-02-14 01:42:55'),
(5, 'Andi - Helpdesk Hardware', 'andi.helpdesk@helpdesk.com', 'helpdesk', NULL, 1, NULL, '$2y$10$GVjbMr1rUH0ZlnT71Meay.DkTSXCmc6lGLsavQl33uSp35EQegt6C', NULL, '2026-02-14 01:42:55', '2026-02-14 01:42:55'),
(6, 'Rini - Helpdesk DMS', 'rini.helpdesk@helpdesk.com', 'helpdesk', NULL, 1, NULL, '$2y$10$bf.IUhvKPHeVVvUezd99OudxTm9h0HAQrgW7bmSHoIsPo9x2BxM9.', NULL, '2026-02-14 01:42:55', '2026-02-14 01:42:55'),
(7, 'Dealer User 1 - JKT-SLT', 'jkt-slt.user1@dealer.com', 'dealer', 1, 1, NULL, '$2y$10$Mp/pFY6r3fi63QncsTkjFuTixYhM38nB3SGaqmhFlGcN6srnzH9vW', NULL, '2026-02-14 01:42:55', '2026-02-14 01:42:55'),
(8, 'Dealer User 2 - JKT-SLT', 'jkt-slt.user2@dealer.com', 'dealer', 1, 1, NULL, '$2y$10$tTlQPE92j1uN4ROvch0cfuQUHvOpVjwnZS6Qj9g2sLypTUibC12IW', NULL, '2026-02-14 01:42:55', '2026-02-14 01:42:55'),
(9, 'Dealer User 1 - JKT-PST', 'jkt-pst.user1@dealer.com', 'dealer', 2, 1, NULL, '$2y$10$pV.TDuwKpMfUUFypssZdMOF0xe0Kw/rnAJcuLvpShGE7sTE5fGZxG', NULL, '2026-02-14 01:42:56', '2026-02-14 01:42:56'),
(10, 'Dealer User 2 - JKT-PST', 'jkt-pst.user2@dealer.com', 'dealer', 2, 1, NULL, '$2y$10$.MvIWxo.PYsoJv8FAazOSOihyHa0A1MbZoEx8YC4GI.WFKMI6jQm6', NULL, '2026-02-14 01:42:56', '2026-02-14 01:42:56'),
(11, 'Dealer User 1 - BDG', 'bdg.user1@dealer.com', 'dealer', 3, 1, NULL, '$2y$10$4oFYvZE2pxDBJ0Tsu73lEuPQFXu.OJh7DeUi6dCqvvbTGUoDRja56', NULL, '2026-02-14 01:42:56', '2026-02-14 01:42:56'),
(12, 'Dealer User 2 - BDG', 'bdg.user2@dealer.com', 'dealer', 3, 1, NULL, '$2y$10$Bsx2LCMJFewFviPBxuQa8OQvyY61m/4R3K3B.fRdfcZf8l742zvxC', NULL, '2026-02-14 01:42:56', '2026-02-14 01:42:56'),
(13, 'Dealer User 1 - SBY', 'sby.user1@dealer.com', 'dealer', 4, 1, NULL, '$2y$10$A4y2pfp/bD36EcB0XikXWe7iPy.FnHarYuIuA0r1OTUTPr4UBcV2K', NULL, '2026-02-14 01:42:56', '2026-02-14 01:42:56'),
(14, 'Dealer User 2 - SBY', 'sby.user2@dealer.com', 'dealer', 4, 1, NULL, '$2y$10$F2zNwTyvlqNUJNTSKK73XerUiQB17MyTxWAfzzkc9CAzsUqzfrkxe', NULL, '2026-02-14 01:42:56', '2026-02-14 01:42:56'),
(15, 'Dealer User 1 - TNG', 'tng.user1@dealer.com', 'dealer', 5, 1, NULL, '$2y$10$/imu.PXfvoyPlkitUo8lQeXCawEgqPoD4x6z.nQZqgHz9k0jy1rmK', NULL, '2026-02-14 01:42:56', '2026-02-14 01:42:56'),
(16, 'Dealer User 2 - TNG', 'tng.user2@dealer.com', 'dealer', 5, 1, NULL, '$2y$10$X1bHrBOqgLXc4nDaKofJH.w2xL0nDjryi.ZFicg3.Ky446YiOJvKe', NULL, '2026-02-14 01:42:56', '2026-02-14 01:42:56'),
(17, 'Dealer User 1 - BKS', 'bks.user1@dealer.com', 'dealer', 6, 1, NULL, '$2y$10$RxqQ4fRpksuMZMVOowXZfutqrW0GgW5B3IqHkcQooGpZ/wmCEuU1C', NULL, '2026-02-14 01:42:56', '2026-02-14 01:42:56'),
(18, 'Dealer User 2 - BKS', 'bks.user2@dealer.com', 'dealer', 6, 1, NULL, '$2y$10$ia0gyihvnCKSY2uxXUzq0uCt4kEufVPZRZRWIn/mgGP/yRgUdbNJq', NULL, '2026-02-14 01:42:56', '2026-02-14 01:42:56'),
(19, 'Dealer User 1 - SMG', 'smg.user1@dealer.com', 'dealer', 7, 1, NULL, '$2y$10$e1SWtvSqagQn8XrSrdx7mOmosuT.UZh.g3oNb84UXHofk.eJa3avC', NULL, '2026-02-14 01:42:56', '2026-02-14 01:42:56'),
(20, 'Dealer User 2 - SMG', 'smg.user2@dealer.com', 'dealer', 7, 1, NULL, '$2y$10$HFl6o3Z9Gl83wk6AiQeLF.tTbebgKescMsDCLU2STdhAEsJw5BDUW', NULL, '2026-02-14 01:42:56', '2026-02-14 01:42:56'),
(21, 'Dealer User 1 - YGY', 'ygy.user1@dealer.com', 'dealer', 8, 1, NULL, '$2y$10$tZYWdUtOljfq9wI21JfHyejPcVX1N1Y7AmNXNxmX7NvStDKA3Y.Zy', NULL, '2026-02-14 01:42:56', '2026-02-14 01:42:56'),
(22, 'Dealer User 2 - YGY', 'ygy.user2@dealer.com', 'dealer', 8, 1, NULL, '$2y$10$0j5UByF1ylOKmjqr1ERMoO5pph4AQLRJQRJmP45KFTAFx0rkW4B12', NULL, '2026-02-14 01:42:56', '2026-02-14 01:42:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_is_active_index` (`is_active`),
  ADD KEY `categories_order_index_index` (`order_index`);

--
-- Indexes for table `dealer_branches`
--
ALTER TABLE `dealer_branches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dealer_branches_branch_code_unique` (`branch_code`),
  ADD KEY `dealer_branches_branch_code_index` (`branch_code`),
  ADD KEY `dealer_branches_is_active_index` (`is_active`);

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
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

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
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sub_categories_default_specialist_id_foreign` (`default_specialist_id`),
  ADD KEY `sub_categories_category_id_is_active_index` (`category_id`,`is_active`),
  ADD KEY `sub_categories_priority_index` (`priority`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tickets_ticket_number_unique` (`ticket_number`),
  ADD KEY `tickets_created_by_foreign` (`created_by`),
  ADD KEY `tickets_category_id_foreign` (`category_id`),
  ADD KEY `tickets_sub_category_id_foreign` (`sub_category_id`),
  ADD KEY `tickets_ticket_number_index` (`ticket_number`),
  ADD KEY `tickets_status_priority_index` (`status`,`priority`),
  ADD KEY `tickets_sla_deadline_index` (`sla_deadline`),
  ADD KEY `tickets_dealer_branch_id_index` (`dealer_branch_id`),
  ADD KEY `tickets_assigned_to_index` (`assigned_to`),
  ADD KEY `tickets_created_at_index` (`created_at`);

--
-- Indexes for table `ticket_attachments`
--
ALTER TABLE `ticket_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_attachments_uploaded_by_foreign` (`uploaded_by`),
  ADD KEY `ticket_attachments_ticket_id_index` (`ticket_id`);

--
-- Indexes for table `ticket_comments`
--
ALTER TABLE `ticket_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_comments_user_id_foreign` (`user_id`),
  ADD KEY `ticket_comments_ticket_id_created_at_index` (`ticket_id`,`created_at`);

--
-- Indexes for table `ticket_logs`
--
ALTER TABLE `ticket_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_logs_user_id_foreign` (`user_id`),
  ADD KEY `ticket_logs_ticket_id_created_at_index` (`ticket_id`,`created_at`),
  ADD KEY `ticket_logs_action_index` (`action`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_dealer_branch_id_foreign` (`dealer_branch_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `dealer_branches`
--
ALTER TABLE `dealer_branches`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ticket_attachments`
--
ALTER TABLE `ticket_attachments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ticket_comments`
--
ALTER TABLE `ticket_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ticket_logs`
--
ALTER TABLE `ticket_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD CONSTRAINT `sub_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sub_categories_default_specialist_id_foreign` FOREIGN KEY (`default_specialist_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `tickets_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `tickets_dealer_branch_id_foreign` FOREIGN KEY (`dealer_branch_id`) REFERENCES `dealer_branches` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `tickets_sub_category_id_foreign` FOREIGN KEY (`sub_category_id`) REFERENCES `sub_categories` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `ticket_attachments`
--
ALTER TABLE `ticket_attachments`
  ADD CONSTRAINT `ticket_attachments_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_attachments_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `ticket_comments`
--
ALTER TABLE `ticket_comments`
  ADD CONSTRAINT `ticket_comments_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `ticket_logs`
--
ALTER TABLE `ticket_logs`
  ADD CONSTRAINT `ticket_logs_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_dealer_branch_id_foreign` FOREIGN KEY (`dealer_branch_id`) REFERENCES `dealer_branches` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
