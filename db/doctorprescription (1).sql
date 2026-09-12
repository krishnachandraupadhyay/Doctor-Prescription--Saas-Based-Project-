-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 20, 2026 at 05:01 AM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `doctorprescription`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_names`
--

DROP TABLE IF EXISTS `company_names`;
CREATE TABLE IF NOT EXISTS `company_names` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company_names_company_name_unique` (`company_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_names`
--

INSERT INTO `company_names` (`id`, `company_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Remeo Pharmacy', 1, '2026-08-07 00:42:59', '2026-08-07 00:42:59');

-- --------------------------------------------------------

--
-- Table structure for table `diagnosis_tests`
--

DROP TABLE IF EXISTS `diagnosis_tests`;
CREATE TABLE IF NOT EXISTS `diagnosis_tests` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `test_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `diagnosis_tests_test_name_unique` (`test_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `diagnosis_tests`
--

INSERT INTO `diagnosis_tests` (`id`, `test_name`, `description`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'X-ray', 'X-ray', 1, 'Super Admin', 'Super Admin', '2026-08-07 00:48:28', '2026-08-07 00:48:28'),
(2, 'CBC', 'Blood Test', 1, 'Super Admin', 'Super Admin', '2026-08-07 00:48:59', '2026-08-07 00:48:59'),
(3, 'WBC', 'Blood Test', 1, 'Super Admin', 'Super Admin', '2026-08-07 00:48:59', '2026-08-07 00:48:59'),
(4, 'malariya', NULL, 1, 'Sonal Singh', 'Sonal Singh', '2026-08-19 01:40:46', '2026-08-19 01:40:46');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

DROP TABLE IF EXISTS `doctors`;
CREATE TABLE IF NOT EXISTS `doctors` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `Doctor_Emp_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `clinic_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qualification` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `specialization` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registration_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clinic_address` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Experience` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clinic_stamp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prescription_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_completed` tinyint(1) NOT NULL DEFAULT '0',
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `has_member` tinyint(1) NOT NULL DEFAULT '1',
  `has_payment_category` tinyint(1) NOT NULL DEFAULT '0',
  `has_deleted_staff` tinyint(1) NOT NULL DEFAULT '0',
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `doctors_doctor_emp_id_unique` (`Doctor_Emp_id`),
  UNIQUE KEY `doctors_email_unique` (`email`),
  UNIQUE KEY `doctors_registration_number_unique` (`registration_number`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `Doctor_Emp_id`, `name`, `email`, `password`, `clinic_name`, `qualification`, `specialization`, `registration_number`, `clinic_address`, `phone`, `Experience`, `license_number`, `logo`, `signature`, `clinic_stamp`, `prescription_type`, `profile_completed`, `verified`, `status`, `has_member`, `has_payment_category`, `has_deleted_staff`, `isdeleted`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'DOC000001', 'Richa Pathak', 'richapathak@gmail.com', '$2y$12$xekxRQSeClMdtmTSZMTqx.eqiC1xBLDpfHq4q.NboCh7wyWtu0KTS', 'Rani Hospital', 'MBBS,MD', 'Physician', NULL, 'Aliganj Lucknow', '6207055580', '2', NULL, NULL, NULL, NULL, 'fixed', 0, 1, 1, 1, 1, 0, 0, 'OB-0001', 'OB-0001', '2026-08-07 00:05:59', '2026-08-14 05:28:22'),
(2, 'DOC000002', 'Sonal Singh', 'sonalsingh@gmail.com', '$2y$12$W2WLAwe7/DcWHON0C6z9zuiRrYp/X1gDU3OzK.kE8zRaMa8nXs39u', 'Rani Hospital', 'MBBS', 'Gastrology', NULL, 'Aliganj Lucknow', '6207055581', '3', NULL, NULL, NULL, NULL, 'customize', 0, 1, 1, 1, 1, 0, 0, 'OB-0001', 'OB-0001', '2026-08-07 00:16:13', '2026-08-14 04:55:57'),
(3, 'DOC-0099', 'Ramesh Gupta', 'rameshgupta@gmail.com', '$2y$12$Q/JzIwSdWh3f4J9RiMRsBep7RMxxEdxY1MtbrBGHNFQE2xgr5d8pW', 'Gupta Heart Care Center', 'MBBS, MD Cardiology', 'Cardiologist', NULL, '45 MG Road, Connaught Place, New Delhi', '9876543210', '12 Years', NULL, NULL, NULL, NULL, 'customize', 0, 1, 1, 1, 1, 0, 1, 'Super Admin', 'Super Admin', '2026-08-14 02:57:42', '2026-08-19 01:58:38'),
(4, 'DOC-0100', 'KL Rahul', 'klrahul@gmail.com', '$2y$12$paY7booo0s6D0oixrkc6O.xEFL72R1nsDuuKyAfTpFiuFwicu8tc2', 'Apex Health Care', 'MS', 'Medicine', NULL, '121 medical chauraha prayagraj', '9519992382', '2 Years', NULL, NULL, NULL, NULL, 'customize', 0, 1, 1, 1, 1, 0, 0, 'Super Admin', 'OB-0001', '2026-08-14 03:02:03', '2026-08-14 05:19:21');

-- --------------------------------------------------------

--
-- Table structure for table `doctors_document`
--

DROP TABLE IF EXISTS `doctors_document`;
CREATE TABLE IF NOT EXISTS `doctors_document` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `document_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issue_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `document_step` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_completed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `doctors_document_doctor_id_foreign` (`doctor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_clinic_documents`
--

DROP TABLE IF EXISTS `doctor_clinic_documents`;
CREATE TABLE IF NOT EXISTS `doctor_clinic_documents` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `header` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `footer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clinic_stamp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `doctor_sign` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `doctor_clinic_documents_doctor_id_foreign` (`doctor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doctor_clinic_documents`
--

INSERT INTO `doctor_clinic_documents` (`id`, `doctor_id`, `header`, `footer`, `photo`, `clinic_stamp`, `doctor_sign`, `member_id`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'doctor_asset/6a758515226d7_header.png', 'doctor_asset/6a75851522f6e_header.png', 'doctor_asset/6a7585152034d_avatar-1.jpg', 'doctor_asset/6a75851521f84_stamp.png', 'doctor_asset/6a758515212f1_D (1).png', 'OB-0001', 1, 'OB-0001', 'OB-0001', '2026-08-07 01:41:17', '2026-08-13 01:27:53'),
(2, 2, NULL, NULL, 'doctor_asset/6a75a8cacec67_avatar-3.jpg', 'doctor_asset/6a75a8cad0438_D (1).png', 'doctor_asset/6a75a8cacf673_logo.png', 'OB-0001', 1, 'OB-0001', 'OB-0001', '2026-08-07 04:13:38', '2026-08-07 04:13:38'),
(3, 4, NULL, NULL, 'doctor_asset/6a7eee17810a1_avatar-8.jpg', 'doctor_asset/6a7eee1782549_layout1.png', 'doctor_asset/6a7eee1781c31_avatar.jpg', 'OB-0001', 1, 'OB-0001', 'OB-0001', '2026-08-14 04:59:43', '2026-08-14 04:59:43');

-- --------------------------------------------------------

--
-- Table structure for table `dosage_name`
--

DROP TABLE IF EXISTS `dosage_name`;
CREATE TABLE IF NOT EXISTS `dosage_name` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` bigint UNSIGNED NOT NULL,
  `dosage_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_id` bigint UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dosage_name_category_id_foreign` (`category_id`),
  KEY `dosage_name_unit_id_foreign` (`unit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dosage_name`
--

INSERT INTO `dosage_name` (`id`, `category_id`, `dosage_name`, `unit_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, '650', 2, 1, '2026-08-07 00:43:26', '2026-08-07 00:43:26'),
(2, 1, '20', 1, 1, '2026-08-07 00:43:40', '2026-08-07 00:43:40');

-- --------------------------------------------------------

--
-- Table structure for table `duration_names`
--

DROP TABLE IF EXISTS `duration_names`;
CREATE TABLE IF NOT EXISTS `duration_names` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `duration_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `duration_names`
--

INSERT INTO `duration_names` (`id`, `duration_name`, `status`, `created_at`, `updated_at`) VALUES
(1, '20', 1, '2026-08-07 00:42:18', '2026-08-07 00:42:18'),
(2, '650', 1, '2026-08-07 00:42:18', '2026-08-07 00:42:18'),
(3, '500', 1, '2026-08-07 00:42:18', '2026-08-07 00:42:18');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `interval_names`
--

DROP TABLE IF EXISTS `interval_names`;
CREATE TABLE IF NOT EXISTS `interval_names` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `interval_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `interval_names`
--

INSERT INTO `interval_names` (`id`, `interval_name`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Once a day(OID)', 1, NULL, NULL, '2026-08-07 00:41:00', '2026-08-07 00:41:00'),
(2, 'Twice a day (BID)', 1, NULL, NULL, '2026-08-07 00:41:00', '2026-08-07 00:41:00'),
(3, 'Three times a day (TID)', 1, NULL, NULL, '2026-08-07 00:41:00', '2026-08-07 00:41:00');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicine`
--

DROP TABLE IF EXISTS `medicine`;
CREATE TABLE IF NOT EXISTS `medicine` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `medicine_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `generic_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `company_id` bigint UNSIGNED DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `medicine_category_id_foreign` (`category_id`),
  KEY `medicine_company_id_foreign` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medicine`
--

INSERT INTO `medicine` (`id`, `medicine_name`, `generic_name`, `category_id`, `company_id`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Paracetamol', 'Para', 3, 1, 'good for health', 'Super Admin', 'Super Admin', '2026-08-07 00:44:31', '2026-08-07 00:44:31'),
(2, 'Dolo', 'Dolo', 2, 1, 'No order', 'Super Admin', 'Super Admin', '2026-08-07 00:45:20', '2026-08-07 00:45:20'),
(3, 'Due', 'Due', 1, 1, 'Added by Doctor during Prescription', '4', '2', '2026-08-19 01:40:46', '2026-08-19 01:53:00');

-- --------------------------------------------------------

--
-- Table structure for table `medicine_categories`
--

DROP TABLE IF EXISTS `medicine_categories`;
CREATE TABLE IF NOT EXISTS `medicine_categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `medicine_categories_category_name_unique` (`category_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medicine_categories`
--

INSERT INTO `medicine_categories` (`id`, `category_name`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Heart Drugs', 1, NULL, NULL, '2026-08-07 00:39:27', '2026-08-07 00:39:27'),
(2, 'Infection Fighters', 1, NULL, NULL, '2026-08-07 00:39:27', '2026-08-07 00:39:27'),
(3, 'Pain killers', 1, NULL, NULL, '2026-08-07 00:39:27', '2026-08-07 00:39:27');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
CREATE TABLE IF NOT EXISTS `members` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `doctor_id` bigint UNSIGNED DEFAULT NULL,
  `member_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `members_member_id_unique` (`member_id`),
  UNIQUE KEY `members_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `doctor_id`, `member_id`, `name`, `email`, `password`, `role`, `status`, `isdeleted`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, NULL, 'MEM26080800001', 'Aditya Raman', 'aditya@gmail.com', '$2y$12$ygz02c.X8Em8VGIKOF.YKurVbP/pDAKPF5aYczplAVoeJ1FPAOIKe', 'receptionist', 'active', 0, 'Richa Pathak', 'Richa Pathak', '2026-08-08 00:37:01', '2026-08-08 00:37:01'),
(2, NULL, 'MEM26081100001', 'Rekha Gupta', 'rekhagupta@gmail.com', '$2y$12$qtAtwltGpZAkkDsf3Fz0f.W/oKMr7pT4BmhkIJSnIS.zjf3IXg7hO', 'staff', 'active', 0, 'Richa Pathak', 'Richa Pathak', '2026-08-11 05:01:11', '2026-08-11 05:01:11'),
(4, 1, 'MEM26081400001', 'Raunak Singh', 'raunak21@gmail.com', '$2y$12$jW21ez6v1c3/EOTA28RPf.OoapslhgYYF5uZSqVk8VgioLfAxdYDi', 'receptionist', 'active', 1, 'Richa Pathak', 'Richa Pathak', '2026-08-14 05:21:55', '2026-08-14 05:22:05');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_07_03_051902_create_doctors_table', 1),
(5, '2026_07_04_074435_create_doctors_document_table', 1),
(6, '2026_07_13_061916_create_patients_table', 1),
(7, '2026_07_15_091959_create_medicine_categorie', 1),
(8, '2026_07_15_094810_create_duration_name', 1),
(9, '2026_07_15_094851_create_unit_name', 1),
(10, '2026_07_15_095042_create_interval_name', 1),
(11, '2026_07_15_095211_create_company_name', 1),
(12, '2026_07_15_095239_create_dosage_name', 1),
(13, '2026_07_17_090858_create_medicine', 1),
(14, '2026_07_18_062858_create_suggestion', 1),
(15, '2026_07_22_173337_create_presciption_data_table', 1),
(16, '2026_07_28_070136_create_symptoms_table', 1),
(17, '2026_07_28_082326_create_diagnosis_tests_table', 1),
(18, '2026_07_29_060459_add_member_id_and_phone_to_users_table', 1),
(19, '2026_07_30_081704_doctor_clinic_documents', 1),
(20, '2026_08_05_082730_create_members_table', 1),
(21, '2026_08_05_090020_add_created_updated_by_to_members_table', 1),
(22, '2026_08_05_120000_add_spo2_sugar_and_diagnosis_test_to_presciption_data_table', 1),
(23, '2026_08_06_075723_create_payment_categories_table', 1),
(24, '2026_08_11_090232_add_member_id_to_patients_table', 2),
(25, '2026_08_13_060000_add_payment_category_id_to_patients_table', 3),
(26, '2026_08_13_070000_add_payment_category_id_to_presciption_data_table', 3),
(27, '2026_08_13_080000_make_fields_nullable_in_presciption_data_table', 4),
(28, '2026_08_13_090000_make_fields_nullable_in_medicine_table', 5),
(29, '2026_08_13_100000_add_next_visit_date_to_presciption_data_table', 6),
(30, '2026_08_14_055624_add_has_member_to_doctors_table', 7),
(31, '2026_08_14_060841_add_has_payment_category_to_doctors_table', 8),
(32, '2026_08_14_063830_add_isdeleted_to_doctors_and_users_tables', 9),
(33, '2026_08_14_161500_add_has_deleted_staff_to_doctors_table', 10),
(34, '2026_08_14_161600_add_isdeleted_to_members_table', 10),
(35, '2026_08_14_161730_add_doctor_id_to_members_table', 11),
(36, '2026_08_19_124500_add_created_by_and_updated_by_to_medicine_table', 12);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

DROP TABLE IF EXISTS `patients`;
CREATE TABLE IF NOT EXISTS `patients` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `doctor_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `member_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_category_id` bigint UNSIGNED DEFAULT NULL,
  `patient_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `registration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guardian_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `husband_father_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `age_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `age_month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Registration_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aaddhar_num` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_by` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `patients_patient_id_unique` (`patient_id`),
  UNIQUE KEY `patients_registration_unique` (`registration`),
  KEY `patients_payment_category_id_foreign` (`payment_category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `doctor_id`, `member_id`, `payment_category_id`, `patient_id`, `registration`, `patient_name`, `guardian_type`, `husband_father_name`, `age_year`, `age_month`, `mobile`, `gender`, `Registration_date`, `address`, `aaddhar_num`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '1', NULL, 1, 'PAT26080700001', '26080700001', 'Krishna Upadhyay', 'father', 'Kamlesh Upadhyay', '22', '0', '9519992343', 'male', '2026-08-07 09:30:37', 'Lanka Varanasi', '877865544321', 'waiting', '', '', '2026-08-07 04:00:37', '2026-08-13 07:02:10'),
(2, '2', NULL, NULL, 'PAT26080700002', '26080700002', 'Anjana Singh', 'husband', 'Ramvilash Singh', '42', '0', '9898454234', 'female', '2026-08-07 09:50:29', 'Bangladesh', '542342123456', '1', '', '', '2026-08-07 04:20:29', '2026-08-07 04:20:29'),
(3, '1', 'MEM26080800001', 1, 'PAT26081100001', '26081100001', 'John Die', 'father', 'Ramvilash Singh', '32', '0', '9519992343', 'male', '2026-08-11 09:06:16', 'Lanka ,Srilanka', '222222223434', 'completed', 'Aditya Raman', 'Aditya Raman', '2026-08-11 03:36:16', '2026-08-13 06:48:22'),
(4, '1', 'MEM26080800001', 1, 'PAT26081300001', '26081300001', 'Rakesh Kumar', 'father', 'RamLakhan Singh', '34', '0', '9519992342', 'male', '2026-08-13 06:03:28', 'Bhagwanpur Shiv Mandir BHU Lanka Varanasi', '988976675445', 'completed', 'Aditya Raman', 'Aditya Raman', '2026-08-13 00:33:28', '2026-08-13 01:21:02'),
(5, '1', NULL, 1, 'PAT26081300002', '26081300002', 'Raju Rastogi', 'father', 'Ramesh Rastogi', '25', '0', '9898454234', 'male', '2026-08-13 07:18:54', 'Nai Basti Lucknow', '459988772317', 'completed', 'Richa Pathak', 'Richa Pathak', '2026-08-13 01:48:54', '2026-08-13 01:57:00'),
(6, '2', NULL, 3, 'PAT26081900001', '26081900001', 'Rahul Sharma', 'father', 'Krishu Sharma', '23', '0', '9519992343', 'male', '2026-08-19 07:06:06', 'Nai Basti Alliganj', '991342139942', 'completed', 'Sonal Singh', 'Sonal Singh', '2026-08-19 01:36:06', '2026-08-19 01:40:46');

-- --------------------------------------------------------

--
-- Table structure for table `payment_categories`
--

DROP TABLE IF EXISTS `payment_categories`;
CREATE TABLE IF NOT EXISTS `payment_categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `doctor_id` bigint UNSIGNED DEFAULT NULL,
  `price` decimal(10,0) DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_categories_doctor_id_foreign` (`doctor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_categories`
--

INSERT INTO `payment_categories` (`id`, `name`, `doctor_id`, `price`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Emergency', 1, 500, 1, 'OB-0001', 'OB-0001', '2026-08-07 01:48:56', '2026-08-07 03:51:33'),
(2, 'OPD', 1, 250, 1, 'OB-0001', 'OB-0001', '2026-08-07 01:49:20', '2026-08-14 05:00:28'),
(3, 'OPD', 2, 120, 1, 'OB-0001', 'OB-0001', '2026-08-07 04:13:58', '2026-08-19 01:55:42'),
(4, 'General', 4, NULL, 1, 'OB-0001', 'OB-0001', '2026-08-14 05:00:22', '2026-08-14 05:00:32');

-- --------------------------------------------------------

--
-- Table structure for table `presciption_data`
--

DROP TABLE IF EXISTS `presciption_data`;
CREATE TABLE IF NOT EXISTS `presciption_data` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `Doctor_Emp_id` bigint UNSIGNED NOT NULL,
  `payment_category_id` bigint UNSIGNED DEFAULT NULL,
  `patient_id` bigint UNSIGNED NOT NULL,
  `weight` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `height` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blood_pressure` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pulse_rate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `temperature` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `spo2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sugar` text COLLATE utf8mb4_unicode_ci,
  `blood_groups` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `symptoms` text COLLATE utf8mb4_unicode_ci,
  `diagnosis` text COLLATE utf8mb4_unicode_ci,
  `diagnosis_test` text COLLATE utf8mb4_unicode_ci,
  `medicine_id` bigint UNSIGNED DEFAULT NULL,
  `dosage` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `frequency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `advice` text COLLATE utf8mb4_unicode_ci,
  `followup` text COLLATE utf8mb4_unicode_ci,
  `next_visit_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `presciption_data_doctor_emp_id_foreign` (`Doctor_Emp_id`),
  KEY `presciption_data_patient_id_foreign` (`patient_id`),
  KEY `presciption_data_medicine_id_foreign` (`medicine_id`),
  KEY `presciption_data_payment_category_id_foreign` (`payment_category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `presciption_data`
--

INSERT INTO `presciption_data` (`id`, `Doctor_Emp_id`, `payment_category_id`, `patient_id`, `weight`, `height`, `blood_pressure`, `pulse_rate`, `temperature`, `spo2`, `sugar`, `blood_groups`, `symptoms`, `diagnosis`, `diagnosis_test`, `medicine_id`, `dosage`, `unit`, `frequency`, `duration`, `advice`, `followup`, `next_visit_date`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 1, '56', '5.6', '102/22', '102', '120', '102', 'FBS:12,PPBS:22,RBS:22', 'A-', 'Fever,Heart Attack', 'Dawa Khao', 'CBC', 1, '1', '2', '1', '1', NULL, NULL, NULL, '2026-08-07 04:05:53', '2026-08-07 04:05:53'),
(2, 2, NULL, 2, '65', '6.4', '102/22', '102', '120', '22', 'FBS:102', 'AB+', 'Headache,Fever', 'Go for checkUp', 'CBC,WBC', 1, '2', '1', '2', '1', '1', 'Repeat till end', NULL, '2026-08-07 04:23:34', '2026-08-07 04:23:34'),
(3, 1, NULL, 3, '65', '6.4', '102/22', '102', '120', '102', 'FBS:102,PPBS:22,RBS:22', 'A+', 'Headache', 'NO', 'CBC', 1, '1', '2', '1', '2', '2', 'Repeat till end', NULL, '2026-08-11 03:54:28', '2026-08-11 03:54:28'),
(5, 1, 1, 4, '65', '172', '120/20', '78', '102', '97', 'FBS:12,PBS:15,RBS:17', 'AB+', 'Fever,Headache', 'No', 'CBC,WBC', 1, '1', '2', '2', '1', '2', 'Review after 5days', NULL, '2026-08-13 01:21:02', '2026-08-13 01:21:02'),
(6, 1, 1, 5, '65', '164', '102/22', '102', '120', '102', 'FBS:122,PPBS:122,RBS:122', 'B-', 'Fever,Headache', 'NO Entry', 'WBC,X-ray', 1, '2', '1', '2', '1', '2', 'Continue 5 days', '2026-08-18', '2026-08-13 01:57:00', '2026-08-13 01:57:00'),
(7, 1, 1, 3, '65', '164', '102/22', '102', '120', '22', 'FBS:102,PBS:122,RBS:122', 'B+', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-13 05:21:18', '2026-08-13 06:48:22'),
(8, 1, 1, 1, '56', '5.6', '102/21', '100', '120', '102', 'FBS:12,PBS:P22,RBS:22', 'A-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-13 06:59:04', '2026-08-13 07:02:10'),
(9, 2, 3, 6, '120', '65', '120/60', '102', '120', '123', 'FBS:102,PPBS:20,RBS:56', 'AB+', 'Headache,Fever', 'nothing', 'CBC,malariya', 3, '1', '2', '2', '1', NULL, NULL, '2026-08-24', '2026-08-19 01:40:46', '2026-08-19 01:40:46');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('Cgd11uJgtMV2WCNBVtoeMhqmYlJXxzZPXBX7ZC8i', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiI0MXMwNHlQYm15bUxqU1ZKcTJXYmRQdjFTMzNXSFpVM0FrSWd1N3dMIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX2RvY3Rvcl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoyLCJ1cmwiOnsiaW50ZW5kZWQiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvcGF5bWVudGNhdGVnb3J5In19', 1787128033),
('FTXSFJEUeKFhCChL6jmLQLe0vKnvkS66IKnFlkgw', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJaOU56cDdjRVVrUnRtaHgyU05GSmlaYjBob2pEVHJOU09KRGVQeE5PIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1787117643),
('gm57BG6Bp1x3BgsfrDzkpy7rbVLVB5ChQr8lz1ta', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.133.0 Chrome/148.0.7778.280 Electron/42.8.0 Safari/537.36', 'eyJfdG9rZW4iOiJhMTdodE5IVzlpMDNGWEtYWExRNEZSTnBPcTBxZEVRQVhDaklrV3JLIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1787118514),
('jy8RFNNcNLq74kWr48EI50DSJiUEoBKYn3ev9L2h', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJxNXhoS0phR2ozM2ZqcmxLYWZyQ3NNWVY1SnBtZ1dqVHQ0d3hQRkFXIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvbG9jYWxob3N0OjgwMDBcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9LCJsb2dpbl9kb2N0b3JfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvbG9jYWxob3N0OjgwMDBcL2RvY3RvclwvbGlzdG9mbWVkaWNpbmUifX0=', 1787042807),
('LdKTfN67dBZdW7QegjnROAMbUJ9Il3mVepds1BNp', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ1TllaeTZuak1YV2pvRmZDcFVocUJGZUtFcXB1bjgxOVFmTzhPMjNRIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1787118532),
('qZRsmowFHvGea3tZwHWbudgNLXKObLxixY3hW6L3', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ0bjFlWUJET3N0S2dqT0NXaDhkaU5uR3o4ZTVWejNTa2Vsck1RempsIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1787132524),
('zUFBuJPe4FHYex9o2D4OMPOY4UXteWtF5oj16GRc', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJtaDlFaERWZXljNFB6SG1hMVhOVExCU3cwWTVvckNEMWV5b2VKb1d3IiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvbG9jYWxob3N0OjgwMDAiLCJyb3V0ZSI6bnVsbH19', 1787127930);

-- --------------------------------------------------------

--
-- Table structure for table `suggestion`
--

DROP TABLE IF EXISTS `suggestion`;
CREATE TABLE IF NOT EXISTS `suggestion` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `suggestion_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suggestion`
--

INSERT INTO `suggestion` (`id`, `suggestion_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Drink Water', 'It is very necessary for your life', '2026-08-07 00:50:26', '2026-08-07 00:50:26'),
(2, 'Take Bed Rest', 'It is very necessary for your life', '2026-08-07 00:50:26', '2026-08-07 00:50:26'),
(3, 'Take Juice', 'Take Juice', '2026-08-07 00:50:51', '2026-08-07 00:50:51');

-- --------------------------------------------------------

--
-- Table structure for table `symptoms`
--

DROP TABLE IF EXISTS `symptoms`;
CREATE TABLE IF NOT EXISTS `symptoms` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `symptom_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `symptoms_symptom_name_unique` (`symptom_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `symptoms`
--

INSERT INTO `symptoms` (`id`, `symptom_name`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Fever', 1, 'Super Admin', 'Super Admin', '2026-08-07 00:47:37', '2026-08-07 00:47:37'),
(2, 'Heart Attack', 1, 'Super Admin', 'Super Admin', '2026-08-07 00:47:37', '2026-08-07 00:47:37'),
(3, 'Headache', 1, 'Super Admin', 'Super Admin', '2026-08-07 00:47:37', '2026-08-07 00:47:37');

-- --------------------------------------------------------

--
-- Table structure for table `unit_names`
--

DROP TABLE IF EXISTS `unit_names`;
CREATE TABLE IF NOT EXISTS `unit_names` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `unit_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `unit_names`
--

INSERT INTO `unit_names` (`id`, `unit_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'ml', 1, '2026-08-07 00:42:31', '2026-08-07 00:42:31'),
(2, 'mg', 1, '2026-08-07 00:42:31', '2026-08-07 00:42:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_member_id_unique` (`member_id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `member_id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `role`, `status`, `isdeleted`, `remember_token`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '', 'Super Admin', 'superadmin@gmail.com', NULL, NULL, '$2y$12$Pxx4Ekk7nNss6y4PZ41Xa.ALKQjZmgjKTgXPG/HCqEFYR./Kwoh3O', 'admin', 1, 0, NULL, '', '', NULL, NULL),
(2, 'OB-0001', 'Krishna Chandra Upadhyay', 'kcup@gmail.com', '9889766754', NULL, '$2y$12$N4PPN3wb/glDESTZYwIeTOmWpPci/bKSrO1OXOxvhjwric7x2WXV2', 'onboarding', 1, 0, NULL, 'Super Admin', 'Super Admin', '2026-08-06 23:57:15', '2026-08-14 01:25:32'),
(3, 'OB-0002', 'kajal Tiwari', 'kajaltiwari@gmail.com', '9889766754', NULL, '$2y$12$YXHkOOHCk8Hx3R8uDaRxw.tWL7f7TOJka.OitE1EH0pI.dV0vkLpO', 'onboarding', 1, 0, NULL, 'Super Admin', 'Super Admin', '2026-08-14 01:16:35', '2026-08-14 01:25:32');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `doctors_document`
--
ALTER TABLE `doctors_document`
  ADD CONSTRAINT `doctors_document_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor_clinic_documents`
--
ALTER TABLE `doctor_clinic_documents`
  ADD CONSTRAINT `doctor_clinic_documents_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dosage_name`
--
ALTER TABLE `dosage_name`
  ADD CONSTRAINT `dosage_name_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `medicine_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dosage_name_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `unit_names` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `medicine`
--
ALTER TABLE `medicine`
  ADD CONSTRAINT `medicine_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `medicine_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `medicine_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `company_names` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_payment_category_id_foreign` FOREIGN KEY (`payment_category_id`) REFERENCES `payment_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payment_categories`
--
ALTER TABLE `payment_categories`
  ADD CONSTRAINT `payment_categories_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `presciption_data`
--
ALTER TABLE `presciption_data`
  ADD CONSTRAINT `presciption_data_doctor_emp_id_foreign` FOREIGN KEY (`Doctor_Emp_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `presciption_data_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `medicine` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `presciption_data_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `presciption_data_payment_category_id_foreign` FOREIGN KEY (`payment_category_id`) REFERENCES `payment_categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
