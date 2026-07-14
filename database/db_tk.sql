-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20260509.eb5f939582
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 06, 2026 at 05:51 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_tk`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensis`
--

CREATE TABLE `absensis` (
  `id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED DEFAULT NULL,
  `siswa_id` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `absensis`
--

INSERT INTO `absensis` (`id`, `school_id`, `siswa_id`, `tanggal`, `status`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-05-14', 'Izin', 'Keterangan Izin', '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(2, 1, 2, '2026-05-14', 'Hadir', NULL, '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(3, 1, 1, '2026-05-15', 'Alfa', 'Keterangan Alfa', '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(4, 1, 2, '2026-05-15', 'Hadir', NULL, '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(5, 1, 1, '2026-05-16', 'Hadir', NULL, '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(6, 1, 2, '2026-05-16', 'Izin', 'Keterangan Izin', '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(7, 1, 1, '2026-05-17', 'Hadir', NULL, '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(8, 1, 2, '2026-05-17', 'Hadir', NULL, '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(9, 1, 1, '2026-05-18', 'Izin', 'Keterangan Izin', '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(10, 1, 2, '2026-05-18', 'Alfa', 'Keterangan Alfa', '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(11, 1, 1, '2026-06-27', 'hadir', NULL, '2026-06-27 10:42:29', '2026-06-27 10:42:29'),
(12, 1, 2, '2026-06-27', 'hadir', NULL, '2026-06-27 10:42:29', '2026-06-27 10:42:29'),
(13, 1, 3, '2026-06-27', 'hadir', NULL, '2026-06-27 10:42:29', '2026-06-27 10:42:29'),
(14, 1, 4, '2026-06-27', 'hadir', NULL, '2026-06-27 10:42:29', '2026-06-27 10:42:29'),
(15, 1, 5, '2026-06-27', 'hadir', NULL, '2026-06-27 10:42:29', '2026-06-27 10:42:29'),
(16, 1, 6, '2026-06-27', 'hadir', NULL, '2026-06-27 10:42:29', '2026-06-27 10:42:29'),
(17, 1, 7, '2026-06-27', 'hadir', NULL, '2026-06-27 10:42:29', '2026-06-27 10:42:29'),
(18, 1, 8, '2026-06-27', 'hadir', NULL, '2026-06-27 10:42:29', '2026-06-27 10:42:29'),
(19, 1, 9, '2026-06-27', 'hadir', NULL, '2026-06-27 10:42:29', '2026-06-27 10:42:29'),
(20, 1, 10, '2026-06-27', 'hadir', NULL, '2026-06-27 10:42:29', '2026-06-27 10:42:29'),
(21, 1, 11, '2026-06-27', 'hadir', NULL, '2026-06-27 10:42:29', '2026-06-27 10:42:29'),
(22, 1, 12, '2026-06-27', 'hadir', NULL, '2026-06-27 10:42:29', '2026-06-27 10:42:29'),
(23, 1, 13, '2026-06-27', 'hadir', NULL, '2026-06-27 10:42:29', '2026-06-27 10:42:29'),
(24, 1, 15, '2026-06-27', 'hadir', NULL, '2026-06-27 10:42:29', '2026-06-27 10:42:29');

-- --------------------------------------------------------

--
-- Table structure for table `anggota_kelas`
--

CREATE TABLE `anggota_kelas` (
  `id` bigint UNSIGNED NOT NULL,
  `siswa_id` bigint UNSIGNED NOT NULL,
  `kelas_id` bigint UNSIGNED NOT NULL,
  `semester_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `anggota_kelas`
--

INSERT INTO `anggota_kelas` (`id`, `siswa_id`, `kelas_id`, `semester_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2026-05-19 04:25:37', '2026-05-19 04:25:37'),
(2, 2, 1, 1, '2026-05-19 04:25:41', '2026-05-19 04:25:41'),
(3, 3, 2, 1, '2026-05-19 10:01:00', '2026-05-19 10:01:00'),
(4, 1, 1, 2, '2026-05-19 22:34:42', '2026-05-19 22:34:42'),
(5, 2, 2, 2, '2026-05-19 22:34:47', '2026-05-19 22:34:47'),
(6, 1, 1, 3, '2026-05-21 03:33:08', '2026-06-27 09:29:45'),
(7, 2, 2, 3, '2026-05-21 03:33:08', '2026-05-21 03:33:08'),
(8, 3, 1, 3, '2026-06-21 21:20:46', '2026-06-27 09:29:45'),
(10, 13, 1, 3, '2026-06-27 09:52:16', '2026-06-27 09:52:16'),
(11, 4, 1, 3, '2026-06-27 09:52:23', '2026-07-05 10:30:16'),
(12, 5, 3, 3, '2026-06-27 09:52:26', '2026-06-27 09:52:26'),
(13, 6, 1, 3, '2026-06-27 09:52:30', '2026-07-05 10:30:16'),
(14, 7, 3, 3, '2026-06-27 09:52:35', '2026-06-27 09:52:35'),
(15, 8, 1, 3, '2026-06-27 09:52:39', '2026-07-05 10:30:16'),
(16, 9, 3, 3, '2026-06-27 09:52:42', '2026-06-27 09:52:42'),
(17, 10, 1, 3, '2026-06-27 09:52:47', '2026-07-05 10:30:16'),
(18, 11, 1, 3, '2026-06-27 09:52:52', '2026-07-05 10:30:16'),
(19, 12, 1, 3, '2026-06-27 09:52:56', '2026-07-05 10:30:16'),
(20, 14, 4, 3, '2026-06-27 09:53:22', '2026-06-27 09:53:22');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `kelas_id` bigint UNSIGNED DEFAULT NULL,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `isi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_kegiatan` date DEFAULT NULL,
  `target` enum('semua','guru','wali_murid') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'semua',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `school_id`, `kelas_id`, `judul`, `isi`, `tanggal_kegiatan`, `target`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'pengambilan buku', 'membayar buku seharga 30k', '2026-06-21', 'semua', '2026-06-20 19:59:13', '2026-06-20 19:59:13');

-- --------------------------------------------------------

--
-- Table structure for table `arsip_rapors`
--

CREATE TABLE `arsip_rapors` (
  `id` bigint UNSIGNED NOT NULL,
  `siswa_id` bigint UNSIGNED NOT NULL,
  `semester_id` bigint UNSIGNED NOT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `arsip_rapors`
--

INSERT INTO `arsip_rapors` (`id`, `siswa_id`, `semester_id`, `file_path`, `nama_file`, `created_at`, `updated_at`) VALUES
(2, 1, 3, 'arsip_rapor/D7Lzbims27pXlRbxnUeCYzbbPxwOZdeAriZwSBna.pdf', 'Tactical Career Blueprint.pdf', '2026-06-01 08:27:34', '2026-06-02 11:55:27'),
(3, 1, 2, 'arsip_rapor/D1p9mNbw3ywxYp72WwapOm7lz706OkeNO1CbNWyA.pdf', '1035+(Hlmn+308-318) (1).pdf', '2026-06-01 08:36:19', '2026-06-01 08:36:19');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jurnal_comments`
--

CREATE TABLE `jurnal_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `jurnal_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `komentar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jurnal_comments`
--

INSERT INTO `jurnal_comments` (`id`, `jurnal_id`, `user_id`, `komentar`, `created_at`, `updated_at`) VALUES
(1, 2, 4, 'materi nya bagus bu', '2026-07-06 10:34:09', '2026-07-06 10:34:09'),
(2, 3, 4, 'Terimakasih bu', '2026-07-06 10:41:21', '2026-07-06 10:41:21');

-- --------------------------------------------------------

--
-- Table structure for table `jurnal_harians`
--

CREATE TABLE `jurnal_harians` (
  `id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `kelas_id` bigint UNSIGNED NOT NULL,
  `materi_id` bigint UNSIGNED DEFAULT NULL,
  `siswa_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `presensi` enum('Hadir','Izin','Sakit','Alpa') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Hadir',
  `mood` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tema` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kegiatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `catatan_khusus` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `catatan_kepsek` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Catatan pengawasan dari Kepala Sekolah',
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jurnal_harians`
--

INSERT INTO `jurnal_harians` (`id`, `school_id`, `kelas_id`, `materi_id`, `siswa_id`, `user_id`, `tanggal`, `presensi`, `mood`, `tema`, `kegiatan`, `catatan`, `catatan_khusus`, `catatan_kepsek`, `foto`, `video`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, 1, 2, '2026-06-01', 'Hadir', NULL, 'day 1', 'mikir kidss', 'nangis sendiri masa?', NULL, NULL, 'jurnal_media/foto/7D6B2INDIT8XrMcRx06u6aKdckwdzWm5BT6oyQ6M.jpg', NULL, '2026-06-01 16:52:20', '2026-06-01 16:52:20'),
(2, 1, 1, 1, 1, 2, '2026-06-02', 'Hadir', NULL, 'Mewarnai di buku mewarna', 'sangat bagus warnanya', NULL, NULL, NULL, 'jurnal_media/foto/hms0gkVDjOCxzGfaYmMOB9Z4woxXzz2lxht3Ou6o.jpg', NULL, '2026-06-02 12:49:13', '2026-06-02 12:49:13'),
(3, 1, 1, 4, 1, 3, '2026-07-07', 'Hadir', NULL, 'Menghitung Dasar', 'Aman', 'Rajin', NULL, NULL, NULL, NULL, '2026-07-06 10:36:32', '2026-07-06 10:36:32');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `nama_kelas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelompok` enum('A','B','PAUD') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tingkat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `school_id`, `nama_kelas`, `kelompok`, `tingkat`, `created_at`, `updated_at`) VALUES
(1, 1, 'Tk A Mangga', 'A', 'TK A', '2026-05-19 03:59:03', '2026-05-19 03:59:03'),
(2, 1, 'Tk B Strobery', 'B', 'TK B', '2026-05-19 10:00:44', '2026-05-19 10:00:44'),
(3, 1, 'Tk A Nanas', 'A', 'TK A', '2026-06-02 12:47:58', '2026-06-02 12:47:58'),
(4, 2, 'Tk A Banana', NULL, 'TK A', '2026-06-26 19:56:08', '2026-06-26 19:56:08'),
(5, 2, 'Tk B Bunglon', NULL, 'TK B', '2026-06-26 19:56:19', '2026-06-26 19:56:19');

-- --------------------------------------------------------

--
-- Table structure for table `materis`
--

CREATE TABLE `materis` (
  `id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED DEFAULT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `mata_pelajaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `status` enum('Draft','Pending','Approved','Rejected','Published') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `materis`
--

INSERT INTO `materis` (`id`, `school_id`, `judul`, `deskripsi`, `mata_pelajaran`, `kelas`, `file`, `created_by`, `status`, `approved_by`, `approved_at`, `rejection_note`, `created_at`, `updated_at`) VALUES
(1, 1, 'Pengenalan Huruf Arab', 'Lorem ipsum', 'Pendidikan Agama', 'Tk A Mangga (Kelompok A)', NULL, 3, 'Published', 6, '2026-07-06 10:15:30', NULL, '2026-07-06 10:15:07', '2026-07-06 10:15:30'),
(2, 1, 'Pengenalan Huruf Arab 2', 'Part 2', 'Pendidikan Agama', 'Tk A Mangga (Kelompok A)', 'materi_files/lkMXV2jprxK1SEhahw7ZdWCfqcoBIeKdJ1gZOqzB.pdf', 3, 'Published', 6, '2026-07-06 10:25:04', NULL, '2026-07-06 10:24:30', '2026-07-06 10:25:04');

-- --------------------------------------------------------

--
-- Table structure for table `materi_comments`
--

CREATE TABLE `materi_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `materi_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `komentar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `materi_jurnals`
--

CREATE TABLE `materi_jurnals` (
  `id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL COMMENT 'Guru yang upload',
  `kelompok` enum('A','B','PAUD') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `judul_materi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tujuan_pembelajaran` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `file_materi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_dibuat` date NOT NULL,
  `tanggal_tayang` date DEFAULT NULL,
  `status` enum('DRAFT','Menunggu ACC','Disetujui','Ditolak') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'DRAFT',
  `catatan_penolakan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `materi_jurnals`
--

INSERT INTO `materi_jurnals` (`id`, `school_id`, `user_id`, `kelompok`, `judul_materi`, `deskripsi`, `tujuan_pembelajaran`, `file_materi`, `tanggal_dibuat`, `tanggal_tayang`, `status`, `catatan_penolakan`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'A', 'Mewarnai di buku mewarna', 'mewarnai gamabr', NULL, NULL, '2026-06-02', NULL, 'Menunggu ACC', NULL, '2026-06-02 12:39:25', '2026-06-02 12:39:25'),
(2, 1, NULL, 'B', 'Menulis', 'Menulis kalimat utuh', NULL, NULL, '2026-06-02', NULL, 'Menunggu ACC', NULL, '2026-06-02 12:48:36', '2026-06-02 12:48:36'),
(3, 1, NULL, 'A', 'Membaca Dasar', 'Siswa dapat membaca dengan baik', NULL, NULL, '2026-07-07', NULL, 'DRAFT', NULL, '2026-07-06 10:31:07', '2026-07-06 10:31:07'),
(4, 1, NULL, 'A', 'Menghitung Dasar', 'Anak menghitung', NULL, NULL, '2026-07-07', NULL, 'DRAFT', NULL, '2026-07-06 10:36:02', '2026-07-06 10:36:02');

-- --------------------------------------------------------

--
-- Table structure for table `materi_kelas_targets`
--

CREATE TABLE `materi_kelas_targets` (
  `id` bigint UNSIGNED NOT NULL,
  `materi_id` bigint UNSIGNED NOT NULL,
  `kelas_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `materi_read_receipts`
--

CREATE TABLE `materi_read_receipts` (
  `id` bigint UNSIGNED NOT NULL,
  `materi_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `read_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_04_01_130718_create_users_table', 1),
(2, '2026_04_09_080955_create_siswa_table', 1),
(3, '2026_04_09_081248_create_rapors_table', 1),
(4, '2026_04_21_045215_add_refleksi_fields_to_rapors_table', 1),
(5, '2026_04_21_052640_drop_refleksi_ortu_from_rapors_table', 1),
(6, '2026_04_21_141045_add_mulok_to_rapors_table', 1),
(7, '2026_04_24_034606_create_perkembangan_fisiks_table', 1),
(8, '2026_05_01_065701_create_absensis_table', 1),
(9, '2026_05_02_083513_create_schools_table', 1),
(10, '2026_05_02_083622_alter_tables_for_multi_tenancy', 1),
(11, '2026_05_04_033114_add_super_admin_role_to_users', 1),
(12, '2026_05_04_033522_add_details_to_schools_table', 1),
(13, '2026_05_06_090823_add_additional_fields_to_siswas_table', 1),
(14, '2026_05_06_100124_add_is_rapor_open_to_schools_table', 1),
(15, '2026_05_06_121724_add_kelas_id_to_users_and_siswas_table', 1),
(16, '2026_05_06_121724_create_kelas_table', 1),
(17, '2026_05_07_115718_add_status_to_siswas_table', 1),
(18, '2026_05_10_082823_create_modules_table', 1),
(19, '2026_05_10_082829_create_school_modules_table', 1),
(20, '2026_05_11_150520_create_announcements_table', 1),
(21, '2026_05_11_155300_add_details_to_announcements_table', 1),
(22, '2026_05_19_043013_create_semesters_table', 1),
(23, '2026_05_19_043048_create_anggota_kelas_table', 1),
(24, '2026_05_19_043106_remove_kelas_id_from_siswas_table', 1),
(25, '2026_05_19_043123_update_rapors_table', 1),
(26, '2026_05_19_163112_add_nisn_and_status_columns', 2),
(27, '2026_05_27_060919_create_rapor_templates_table', 3),
(28, '2026_05_30_060140_add_opsi_pilihan_to_rapor_template_items_table', 4),
(29, '2026_05_30_061559_update_struktur_rapor_templates', 5),
(30, '2026_06_01_023719_add_nilai_json_to_rapors_table', 6),
(31, '2026_06_01_144507_add_no_induk_to_siswas_table', 7),
(32, '2026_06_01_150432_create_arsip_rapors_table', 8),
(33, '2026_06_01_154823_add_dapodik_address_to_siswas_table', 9),
(34, '2026_06_01_223449_add_full_dapodik_to_siswas_table', 10),
(35, '2026_06_01_233053_create_jurnal_harians_table', 11),
(36, '2026_06_01_234040_add_siswa_id_to_jurnal_harians_table', 12),
(37, '2026_06_01_234723_add_media_to_jurnal_harians_table', 13),
(38, '2026_06_02_192719_setup_lms_jurnal_tables', 14),
(39, '2026_06_03_082645_change_role_column_in_users_table', 15),
(40, '2026_06_03_110755_add_multi_role_access_to_school_modules_table', 16),
(41, '2026_06_19_144428_add_kuota_and_program_columns', 17),
(42, '2026_07_01_113348_add_bukti_pembayaran_to_siswas_table', 18),
(43, '2026_07_01_171827_modify_status_column_in_siswas_table', 19),
(44, '2026_06_15_111146_create_sessions_table', 20),
(45, '2026_06_15_111250_create_cache_table', 20),
(46, '2026_06_15_183000_add_approval_to_materi_jurnals_table', 20),
(47, '2026_06_15_184000_add_guru_features_to_jurnals', 20),
(48, '2026_06_15_185000_add_kepsek_features_to_jurnals', 20),
(49, '2026_06_15_190000_add_walimurid_interaction_tables', 20),
(50, '2026_06_15_191000_add_status_and_notifications_table', 20),
(51, '2026_07_02_093212_add_kuota_settings_to_schools_table', 20),
(52, '2026_07_06_000000_create_settings_table', 21),
(53, '2026_07_06_171000_create_materis_table', 22);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `route_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_access` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `name`, `route_name`, `icon`, `category`, `role_access`, `created_at`, `updated_at`) VALUES
(1, 'Data Siswa', 'data-siswa', 'solar:users-group-rounded-bold-duotone', 'MANAJEMEN', 'admin,guru,kepsek', '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(2, 'Manajemen Kelas', 'kelas.index', 'solar:globus-bold-duotone', 'MANAJEMEN', 'admin,guru,kepsek', '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(3, 'Perkembangan Fisik', 'perkembangan.index', 'solar:graph-up-bold-duotone', 'MANAJEMEN', 'admin,guru,kepsek', '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(4, 'Absensi Harian', 'absensi.harian', 'solar:calendar-mark-bold-duotone', 'PRESENSI', 'admin,guru,kepsek', '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(5, 'Rekap Absensi', 'absensi.rekap', 'solar:clipboard-list-bold-duotone', 'PRESENSI', 'admin,guru,kepsek', '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(6, 'Isi Rapor', 'rapor.isi', 'solar:file-text-bold-duotone', 'RAPOR', 'admin,guru,kepsek', '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(7, 'Cetak Rapor', 'rapor.cetak', 'solar:printer-bold-duotone', 'RAPOR', 'admin,guru,kepsek', '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(8, 'Pengaturan Sekolah', 'pengaturan', 'solar:settings-bold-duotone', 'SISTEM', 'admin,kepsek', '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(9, 'Kenaikan Kelas', 'kenaikan.index', 'solar:double-alt-arrow-up-bold-duotone', 'SISTEM', 'admin,kepsek', '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(10, 'Pengumuman', 'pengumuman.index', 'solar:bell-bing-bold-duotone', 'BERANDA', 'admin,guru,kepsek', '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(11, 'Jurnal Harian', 'jurnal.index', 'solar:book-bookmark-bold-duotone', 'KEGIATAN', 'admin,guru,kepsek', '2026-06-03 07:39:30', '2026-06-03 07:39:30'),
(12, 'Arsip Rapor', 'arsip.index', 'solar:folder-with-files-bold-duotone', 'RAPOR', 'admin,guru,kepsek', '2026-06-03 07:39:30', '2026-06-03 07:39:30'),
(13, 'Template Rapor', 'template.index', 'solar:document-add-bold-duotone', 'SISTEM', 'admin,kepsek', '2026-06-03 07:39:30', '2026-06-03 07:39:30'),
(14, 'Rapor Anak', 'walimurid.rapor', 'solar:notebook-bold-duotone', 'AKADEMIK ANAK', 'wali_murid', '2026-06-03 07:39:30', '2026-06-03 07:39:30'),
(15, 'Laporan Fisik', 'walimurid.perkembangan', 'solar:pulse-bold-duotone', 'AKADEMIK ANAK', 'wali_murid', '2026-06-03 07:39:30', '2026-06-03 07:39:30');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `perkembangan_fisiks`
--

CREATE TABLE `perkembangan_fisiks` (
  `id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED DEFAULT NULL,
  `siswa_id` bigint UNSIGNED NOT NULL,
  `tgl_periksa` date NOT NULL,
  `berat_badan` decimal(5,2) NOT NULL,
  `tinggi_badan` decimal(5,2) NOT NULL,
  `lingkar_kepala` decimal(5,2) DEFAULT NULL,
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `perkembangan_fisiks`
--

INSERT INTO `perkembangan_fisiks` (`id`, `school_id`, `siswa_id`, `tgl_periksa`, `berat_badan`, `tinggi_badan`, `lingkar_kepala`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-12-19', 15.10, 100.60, 49.00, 'Pertumbuhan bulan ke-1 normal.', '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(2, 1, 1, '2026-01-19', 16.50, 101.90, 48.60, 'Pertumbuhan bulan ke-2 normal.', '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(3, 1, 1, '2026-02-19', 17.10, 102.20, 48.80, 'Pertumbuhan bulan ke-3 normal.', '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(4, 1, 1, '2026-03-19', 18.00, 103.90, 48.80, 'Pertumbuhan bulan ke-4 normal.', '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(5, 1, 1, '2026-04-19', 19.50, 104.10, 48.60, 'Pertumbuhan bulan ke-5 normal.', '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(6, 1, 1, '2026-05-19', 20.20, 105.10, 48.90, 'Pertumbuhan bulan ke-6 normal.', '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(7, 1, 2, '2025-12-19', 15.10, 100.70, 48.40, 'Pertumbuhan bulan ke-1 normal.', '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(8, 1, 2, '2026-01-19', 16.50, 101.70, 48.80, 'Pertumbuhan bulan ke-2 normal.', '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(9, 1, 2, '2026-02-19', 17.00, 102.20, 48.10, 'Pertumbuhan bulan ke-3 normal.', '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(10, 1, 2, '2026-03-19', 18.30, 103.90, 48.90, 'Pertumbuhan bulan ke-4 normal.', '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(11, 1, 2, '2026-04-19', 19.30, 104.50, 48.10, 'Pertumbuhan bulan ke-5 normal.', '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(12, 1, 2, '2026-05-19', 20.00, 105.50, 48.10, 'Pertumbuhan bulan ke-6 normal.', '2026-05-19 03:48:17', '2026-05-19 03:48:17');

-- --------------------------------------------------------

--
-- Table structure for table `rapors`
--

CREATE TABLE `rapors` (
  `id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED DEFAULT NULL,
  `siswa_id` bigint UNSIGNED NOT NULL,
  `semester_id` bigint UNSIGNED DEFAULT NULL,
  `sakit` int NOT NULL DEFAULT '0',
  `izin` int NOT NULL DEFAULT '0',
  `alfa` int NOT NULL DEFAULT '0',
  `tinggi_badan` double DEFAULT NULL,
  `berat_badan` double DEFAULT NULL,
  `nilai_agama_dan_budi_pekerti` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `jati_diri` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `literasi_sains_seni` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `kokurikuler` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `info_perkembangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `refleksi_1` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `refleksi_2` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `refleksi_3` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `mulok` json DEFAULT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `template_id` bigint UNSIGNED DEFAULT NULL,
  `nilai_json` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rapors`
--

INSERT INTO `rapors` (`id`, `school_id`, `siswa_id`, `semester_id`, `sakit`, `izin`, `alfa`, `tinggi_badan`, `berat_badan`, `nilai_agama_dan_budi_pekerti`, `jati_diri`, `literasi_sains_seni`, `kokurikuler`, `info_perkembangan`, `created_at`, `updated_at`, `refleksi_1`, `refleksi_2`, `refleksi_3`, `mulok`, `status`, `template_id`, `nilai_json`) VALUES
(1, 1, 3, 1, 0, 0, 0, NULL, NULL, 'Sadiya cantik ini lagi pusing bangettt', 'Sadiya cantik ini lagi pusing bangettt', 'Sadiya cantik ini lagi pusing bangettt', 'Sadiya cantik ini lagi pusing bangettt', 'Sadiya cantik ini lagi pusing bangettt', '2026-05-19 09:57:19', '2026-05-19 09:57:53', 'Sadiya cantik ini lagi pusing bangettt', 'Sadiya cantik ini lagi pusing bangettt', 'Sadiya cantik ini lagi pusing bangettt', '\"{\\\"m_Menyebutkan rukun iman secara urut\\\":\\\"BSB\\\",\\\"m_Menyebutkan ciptaan-ciptaan Allah yang ada dilangit dan dibumi\\\":\\\"BSB\\\",\\\"m_Menyebutkan asmaul Husna\\\":\\\"BSB\\\",\\\"m_Menyebutkan 4 kitab Allah dan nama Nabi yang menerimanya\\\":\\\"BSB\\\",\\\"m_Menyebutkan 10 malaikat Allah\\\":\\\"BSB\\\",\\\"m_Menyebutkan nama 25 Nabi dengan lagu\\\":\\\"BSB\\\",\\\"m_Menyebutkan dan hafal rukun islam secara urut\\\":\\\"BSB\\\",\\\"m_Melafalkan 2 kalimat syahadat dan mengetahui maknanya\\\":\\\"BSB\\\",\\\"m_Menyebutkan macam-macam sholat fardhu\\\":\\\"BSB\\\",\\\"m_Menyebutkan jumlah masing-masing rokaat dalam sholat\\\":\\\"BSB\\\",\\\"m_Menyebutkan waktu sholat fardhu\\\":\\\"BSB\\\",\\\"m_Menirukan gerakan dan bacaan dari takbirotul ihrom-salam\\\":\\\"BSB\\\",\\\"m_Mengucapkan bacaan dalam sholat (praktek sholat)\\\":\\\"BSB\\\",\\\"m_Melakukan niat dan doa sesudah wudhu\\\":\\\"BSB\\\",\\\"m_Praktek wudhu\\\":\\\"BSB\\\",\\\"m_Mengucapkan do\'a harian (makan, tidur, ortu, belajar) dengan fasih\\\":\\\"BSB\\\",\\\"m_Mengucapkan surat-surat pendek dengan fasih\\\":\\\"BSB\\\",\\\"m_Mengucapkan Hadits pendek dengan fasih\\\":\\\"BSB\\\",\\\"m_Mengenal dan menyebutkan huruf hijaiyah padanan fathah, kasroh, dhomah\\\":\\\"BSB\\\",\\\"m_Berdo\'a sebelum dan sesudah melaksanakan kegiatan\\\":\\\"BSB\\\",\\\"m_Berdo\'a ketika mendengar adzan\\\":\\\"BSB\\\",\\\"m_Mengucapkan kalimat-kalimat Thoyyibah dan Hauqolah\\\":\\\"BSB\\\",\\\"m_Mengucapkan sholawat, dzikir, tahlil, istighfar dan tahmid\\\":\\\"BSB\\\",\\\"m_Bersikap jujur dan suka menolong\\\":\\\"BSB\\\",\\\"m_Menyebutkan kepanjangan NU\\\":\\\"BSB\\\",\\\"m_Menyebutkan Ulama pendiri NU beserta fotonya\\\":\\\"BSB\\\",\\\"m_Menunjukkan lambang gambar NU dan warna dasarnya\\\":\\\"BSB\\\",\\\"m_Menunjukkan lambang muslimat NU beserta isinya\\\":\\\"BSB\\\",\\\"m_Menunjukkan gambar dan ciri-ciri pakaian Kyai \\\\/ Nyai\\\":\\\"BSB\\\",\\\"m_Meniru bacaan sholawat Nariyah, Nahdliyah, dan Burdah\\\":\\\"BSB\\\",\\\"m_Menyebutkan angka arab 1-10\\\":\\\"BSB\\\",\\\"m_Menyebutkan nama hari dan bulan-bulan hijriyah\\\":\\\"BSB\\\",\\\"m_Mengenal anggota tubuh dan benda-benda berbahasa arab\\\":\\\"BSB\\\",\\\"m_Ngaweruhi angka 1-10\\\":\\\"BSB\\\",\\\"m_Ngaweruhi jenenge awak lan anggota keluarga\\\":\\\"BSB\\\",\\\"m_Ngaweruhi jenenge dina lan wama (abang, ijo, kuning, biru, putih)\\\":\\\"BSB\\\",\\\"m_Menyebutkan angka 1-10 dalam bahasa inggris\\\":\\\"BSB\\\",\\\"m_Menyebutkan nama hari dalam bahasa inggris\\\":\\\"BSB\\\",\\\"m_Mengenal anggota tubuh dan benda disekitar dalam bahasa inggris\\\":\\\"BSB\\\"}\"', 'permanen', NULL, NULL),
(2, 1, 3, 2, 0, 0, 0, NULL, NULL, 'ini sadiya', 'ini sadiya', 'ini sadiya', 'ini sadiya', 'ini sadiya', '2026-05-20 01:28:27', '2026-05-21 00:03:46', 'ini sadiya', 'ini sadiya', 'ini sadiya', '\"{\\\"m_Menyebutkan rukun iman secara urut\\\":\\\"BSB\\\",\\\"m_Menyebutkan ciptaan-ciptaan Allah yang ada dilangit dan dibumi\\\":\\\"BSB\\\",\\\"m_Menyebutkan asmaul Husna\\\":\\\"BSB\\\",\\\"m_Menyebutkan 4 kitab Allah dan nama Nabi yang menerimanya\\\":\\\"BSB\\\",\\\"m_Menyebutkan 10 malaikat Allah\\\":\\\"BSB\\\",\\\"m_Menyebutkan nama 25 Nabi dengan lagu\\\":\\\"BSB\\\",\\\"m_Menyebutkan dan hafal rukun islam secara urut\\\":\\\"BSB\\\",\\\"m_Melafalkan 2 kalimat syahadat dan mengetahui maknanya\\\":\\\"BSB\\\",\\\"m_Menyebutkan macam-macam sholat fardhu\\\":\\\"MB\\\",\\\"m_Menyebutkan jumlah masing-masing rokaat dalam sholat\\\":\\\"MB\\\",\\\"m_Menyebutkan waktu sholat fardhu\\\":\\\"BSH\\\",\\\"m_Menirukan gerakan dan bacaan dari takbirotul ihrom-salam\\\":\\\"BSB\\\",\\\"m_Mengucapkan bacaan dalam sholat (praktek sholat)\\\":\\\"BSH\\\",\\\"m_Melakukan niat dan doa sesudah wudhu\\\":\\\"BSH\\\",\\\"m_Praktek wudhu\\\":\\\"BSB\\\",\\\"m_Mengucapkan do\'a harian (makan, tidur, ortu, belajar) dengan fasih\\\":\\\"BSH\\\",\\\"m_Mengucapkan surat-surat pendek dengan fasih\\\":\\\"BSH\\\",\\\"m_Mengucapkan Hadits pendek dengan fasih\\\":\\\"BSB\\\",\\\"m_Mengenal dan menyebutkan huruf hijaiyah padanan fathah, kasroh, dhomah\\\":\\\"BSH\\\",\\\"m_Berdo\'a sebelum dan sesudah melaksanakan kegiatan\\\":\\\"BSB\\\",\\\"m_Berdo\'a ketika mendengar adzan\\\":\\\"BSB\\\",\\\"m_Mengucapkan kalimat-kalimat Thoyyibah dan Hauqolah\\\":\\\"BSB\\\",\\\"m_Mengucapkan sholawat, dzikir, tahlil, istighfar dan tahmid\\\":\\\"BSB\\\",\\\"m_Bersikap jujur dan suka menolong\\\":\\\"BSB\\\",\\\"m_Menyebutkan kepanjangan NU\\\":\\\"BSH\\\",\\\"m_Menyebutkan Ulama pendiri NU beserta fotonya\\\":\\\"BSH\\\",\\\"m_Menunjukkan lambang gambar NU dan warna dasarnya\\\":\\\"BSH\\\",\\\"m_Menunjukkan lambang muslimat NU beserta isinya\\\":\\\"BSH\\\",\\\"m_Menunjukkan gambar dan ciri-ciri pakaian Kyai \\\\/ Nyai\\\":\\\"MB\\\",\\\"m_Meniru bacaan sholawat Nariyah, Nahdliyah, dan Burdah\\\":\\\"BB\\\",\\\"m_Menyebutkan angka arab 1-10\\\":\\\"BB\\\",\\\"m_Menyebutkan nama hari dan bulan-bulan hijriyah\\\":\\\"MB\\\",\\\"m_Mengenal anggota tubuh dan benda-benda berbahasa arab\\\":\\\"BSH\\\",\\\"m_Ngaweruhi angka 1-10\\\":\\\"BSH\\\",\\\"m_Ngaweruhi jenenge awak lan anggota keluarga\\\":\\\"MB\\\",\\\"m_Ngaweruhi jenenge dina lan wama (abang, ijo, kuning, biru, putih)\\\":\\\"BSH\\\",\\\"m_Menyebutkan angka 1-10 dalam bahasa inggris\\\":\\\"BSH\\\",\\\"m_Menyebutkan nama hari dalam bahasa inggris\\\":\\\"BSB\\\",\\\"m_Mengenal anggota tubuh dan benda disekitar dalam bahasa inggris\\\":\\\"BSB\\\"}\"', 'permanen', NULL, NULL),
(3, 1, 1, 2, 0, 2, 1, 105.1, 20.2, 'Diya Diya Diya Diya', 'Diya Diya Diya Diya', 'Diya Diya Diya Diya', 'Diya Diya Diya Diya', 'Diya Diya Diya Diya', '2026-05-21 00:30:38', '2026-05-21 00:30:38', 'Diya Diya Diya Diya', 'Diya Diya Diya Diya', 'Diya Diya Diya Diya', '\"{\\\"m_Menyebutkan rukun iman secara urut\\\":\\\"BSB\\\",\\\"m_Menyebutkan ciptaan-ciptaan Allah yang ada dilangit dan dibumi\\\":\\\"BSB\\\",\\\"m_Menyebutkan asmaul Husna\\\":\\\"BSB\\\",\\\"m_Menyebutkan 4 kitab Allah dan nama Nabi yang menerimanya\\\":\\\"BSB\\\",\\\"m_Menyebutkan 10 malaikat Allah\\\":\\\"BSB\\\",\\\"m_Menyebutkan nama 25 Nabi dengan lagu\\\":\\\"BSB\\\",\\\"m_Menyebutkan dan hafal rukun islam secara urut\\\":\\\"BSB\\\",\\\"m_Melafalkan 2 kalimat syahadat dan mengetahui maknanya\\\":\\\"BSB\\\",\\\"m_Menyebutkan macam-macam sholat fardhu\\\":\\\"BSB\\\",\\\"m_Menyebutkan jumlah masing-masing rokaat dalam sholat\\\":\\\"BSB\\\",\\\"m_Menyebutkan waktu sholat fardhu\\\":\\\"BSB\\\",\\\"m_Menirukan gerakan dan bacaan dari takbirotul ihrom-salam\\\":\\\"BSB\\\",\\\"m_Mengucapkan bacaan dalam sholat (praktek sholat)\\\":\\\"BSB\\\",\\\"m_Melakukan niat dan doa sesudah wudhu\\\":\\\"BSB\\\",\\\"m_Praktek wudhu\\\":\\\"BSB\\\",\\\"m_Mengucapkan do\'a harian (makan, tidur, ortu, belajar) dengan fasih\\\":\\\"BSB\\\",\\\"m_Mengucapkan surat-surat pendek dengan fasih\\\":\\\"BSB\\\",\\\"m_Mengucapkan Hadits pendek dengan fasih\\\":\\\"BSB\\\",\\\"m_Mengenal dan menyebutkan huruf hijaiyah padanan fathah, kasroh, dhomah\\\":\\\"BSB\\\",\\\"m_Berdo\'a sebelum dan sesudah melaksanakan kegiatan\\\":\\\"BSB\\\",\\\"m_Berdo\'a ketika mendengar adzan\\\":\\\"BSB\\\",\\\"m_Mengucapkan kalimat-kalimat Thoyyibah dan Hauqolah\\\":\\\"BSB\\\",\\\"m_Mengucapkan sholawat, dzikir, tahlil, istighfar dan tahmid\\\":\\\"BSB\\\",\\\"m_Bersikap jujur dan suka menolong\\\":\\\"BSB\\\",\\\"m_Menyebutkan kepanjangan NU\\\":\\\"BSB\\\",\\\"m_Menyebutkan Ulama pendiri NU beserta fotonya\\\":\\\"BSB\\\",\\\"m_Menunjukkan lambang gambar NU dan warna dasarnya\\\":\\\"BSB\\\",\\\"m_Menunjukkan lambang muslimat NU beserta isinya\\\":\\\"BSB\\\",\\\"m_Menunjukkan gambar dan ciri-ciri pakaian Kyai \\\\/ Nyai\\\":\\\"BSB\\\",\\\"m_Meniru bacaan sholawat Nariyah, Nahdliyah, dan Burdah\\\":\\\"BSB\\\",\\\"m_Menyebutkan angka arab 1-10\\\":\\\"BSB\\\",\\\"m_Menyebutkan nama hari dan bulan-bulan hijriyah\\\":\\\"BSB\\\",\\\"m_Mengenal anggota tubuh dan benda-benda berbahasa arab\\\":\\\"BSB\\\",\\\"m_Ngaweruhi angka 1-10\\\":\\\"BSB\\\",\\\"m_Ngaweruhi jenenge awak lan anggota keluarga\\\":\\\"BSB\\\",\\\"m_Ngaweruhi jenenge dina lan wama (abang, ijo, kuning, biru, putih)\\\":\\\"BSB\\\",\\\"m_Menyebutkan angka 1-10 dalam bahasa inggris\\\":\\\"BSB\\\",\\\"m_Menyebutkan nama hari dalam bahasa inggris\\\":\\\"BSB\\\",\\\"m_Mengenal anggota tubuh dan benda disekitar dalam bahasa inggris\\\":\\\"BSB\\\"}\"', 'draft', NULL, NULL),
(4, 1, 2, 3, 0, 1, 1, 105.5, 20, NULL, NULL, NULL, NULL, NULL, '2026-05-31 21:21:49', '2026-05-31 21:21:49', NULL, NULL, NULL, NULL, 'permanen', 1, '{\"24\": \"sadiya cantik hehehehhe\", \"25\": \"sadiya cantik hehehehhe\", \"27\": \"sadiya cantik hehehehhe\", \"30\": \"BSB\", \"31\": \"BSB\"}'),
(5, 1, 1, 3, 0, 2, 1, 105.1, 20.2, NULL, NULL, NULL, NULL, NULL, '2026-06-03 07:14:46', '2026-06-03 07:14:46', NULL, NULL, NULL, NULL, 'permanen', 2, '{\"40\": \"Rapor rapor rapor rapor\", \"41\": \"Rapor rapor rapor rapor\", \"44\": \"tinggi\", \"45\": \"tinggi\"}');

-- --------------------------------------------------------

--
-- Table structure for table `rapor_templates`
--

CREATE TABLE `rapor_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_template` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `opsi_mulok` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rapor_templates`
--

INSERT INTO `rapor_templates` (`id`, `nama_template`, `is_active`, `opsi_mulok`, `created_at`, `updated_at`) VALUES
(1, 'kurikulum kura dan kelinci', 0, '[\"BB\",\"MB\",\"BSH\",\"BSB\"]', '2026-05-28 00:03:46', '2026-06-01 19:34:13'),
(2, 'cah kene', 1, '[\"rendah\",\"sedang\",\"tinggi\"]', '2026-06-01 19:31:09', '2026-06-01 19:34:13');

-- --------------------------------------------------------

--
-- Table structure for table `rapor_template_items`
--

CREATE TABLE `rapor_template_items` (
  `id` bigint UNSIGNED NOT NULL,
  `template_id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `is_bab` tinyint(1) NOT NULL DEFAULT '0',
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opsi_pilihan` json DEFAULT NULL,
  `urutan` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rapor_template_items`
--

INSERT INTO `rapor_template_items` (`id`, `template_id`, `parent_id`, `is_bab`, `label`, `tipe`, `opsi_pilihan`, `urutan`, `created_at`, `updated_at`) VALUES
(5, 1, NULL, 0, 'Nilai narasi 1', 'textarea', NULL, 1, '2026-05-29 23:04:06', '2026-05-29 23:04:06'),
(6, 1, NULL, 0, 'Nilai narasi 2', 'textarea', NULL, 2, '2026-05-29 23:04:09', '2026-05-29 23:04:09'),
(7, 1, NULL, 0, 'Nilai narasi 3', 'textarea', NULL, 3, '2026-05-29 23:04:13', '2026-05-29 23:04:13'),
(8, 1, NULL, 0, 'Nilai narasi', 'textarea', NULL, 4, '2026-05-29 23:04:18', '2026-05-29 23:04:18'),
(12, 1, 10, 0, 'Nilai narasi 1', 'textarea', NULL, 8, '2026-05-29 23:30:58', '2026-05-29 23:30:58'),
(13, 1, 10, 0, 'Nilai narasi 2', 'textarea', NULL, 9, '2026-05-29 23:31:07', '2026-05-29 23:31:07'),
(14, 1, 10, 0, 'Nilai narasi 3', 'textarea', NULL, 10, '2026-05-29 23:31:14', '2026-05-29 23:31:14'),
(15, 1, 11, 0, 'Mulok 1', 'radio', NULL, 11, '2026-05-29 23:31:22', '2026-05-29 23:31:22'),
(16, 1, 11, 0, 'Mulok 2', 'radio', NULL, 12, '2026-05-29 23:31:35', '2026-05-29 23:31:35'),
(17, 1, 11, 0, 'Mulok 3', 'radio', NULL, 13, '2026-05-29 23:31:47', '2026-05-29 23:31:47'),
(18, 1, NULL, 1, 'Header 1.1', NULL, NULL, 14, '2026-05-30 00:23:56', '2026-05-30 00:23:56'),
(23, 1, 18, 0, 'Header 2.1', 'sub_judul', NULL, 15, '2026-05-30 00:25:34', '2026-05-30 00:25:34'),
(24, 1, 18, 0, 'Narasi 1', 'textarea', NULL, 16, '2026-05-30 00:25:50', '2026-05-30 00:25:50'),
(25, 1, 18, 0, 'Narasi', 'textarea', NULL, 17, '2026-05-30 00:25:58', '2026-05-30 00:25:58'),
(26, 1, 18, 0, 'Header 2.2', 'sub_judul', NULL, 18, '2026-05-30 00:26:09', '2026-05-30 00:26:09'),
(27, 1, 18, 0, 'Narasi 3', 'textarea', NULL, 19, '2026-05-30 00:26:23', '2026-05-30 00:26:23'),
(28, 1, NULL, 1, 'Header 1.2', NULL, NULL, 20, '2026-05-30 00:26:33', '2026-05-30 00:26:33'),
(29, 1, 28, 0, 'Header 2.1', 'sub_judul', NULL, 21, '2026-05-30 00:26:45', '2026-05-30 00:26:45'),
(30, 1, 28, 0, 'Mulok 1', 'radio', NULL, 22, '2026-05-30 00:26:53', '2026-05-30 00:26:53'),
(31, 1, 28, 0, 'Mulok 2', 'radio', NULL, 23, '2026-05-30 00:26:59', '2026-05-30 00:26:59'),
(33, 1, 28, 0, 'Header 2.2', 'sub_judul', NULL, 24, '2026-06-01 07:21:45', '2026-06-01 07:21:45'),
(34, 1, 28, 0, 'mulok 3', 'radio', NULL, 25, '2026-06-01 07:26:31', '2026-06-01 07:26:31'),
(35, 1, 28, 0, 'mulok 4', 'radio', NULL, 26, '2026-06-01 07:26:35', '2026-06-01 07:26:35'),
(36, 1, 28, 0, 'mulok 5', 'radio', NULL, 27, '2026-06-01 07:26:39', '2026-06-01 07:26:39'),
(37, 1, NULL, 1, 'Header 1.3', NULL, NULL, 28, '2026-06-01 07:34:14', '2026-06-01 07:34:14'),
(38, 2, NULL, 1, 'judul', NULL, NULL, 1, '2026-06-01 19:32:07', '2026-06-01 19:32:07'),
(39, 2, 38, 0, 'subjudul', 'sub_judul', NULL, 2, '2026-06-01 19:32:21', '2026-06-01 19:32:21'),
(40, 2, 38, 0, 'narasi 1', 'textarea', NULL, 3, '2026-06-01 19:32:32', '2026-06-01 19:32:32'),
(41, 2, 38, 0, 'narasi 2', 'textarea', NULL, 4, '2026-06-01 19:32:38', '2026-06-01 19:32:38'),
(42, 2, NULL, 1, 'judul 2', NULL, NULL, 5, '2026-06-01 19:33:02', '2026-06-01 19:33:02'),
(43, 2, 42, 0, 'subjudul', 'sub_judul', NULL, 6, '2026-06-01 19:33:15', '2026-06-01 19:33:15'),
(44, 2, 42, 0, 'mulok 1', 'radio', NULL, 7, '2026-06-01 19:33:25', '2026-06-01 19:33:25'),
(45, 2, 42, 0, 'mulok 2', 'radio', NULL, 8, '2026-06-01 19:33:31', '2026-06-01 19:33:31');

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_sekolah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `npsn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `kepala_sekolah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_ajaran_aktif` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `semester_aktif` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_rapor_open` tinyint(1) NOT NULL DEFAULT '0',
  `logo_sekolah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `max_indent_years` int NOT NULL DEFAULT '3',
  `kuota_tk_pagi` int NOT NULL DEFAULT '25',
  `kuota_tk_siang` int NOT NULL DEFAULT '25',
  `kuota_paud` int NOT NULL DEFAULT '25',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`id`, `nama_sekolah`, `npsn`, `alamat`, `kepala_sekolah`, `telepon`, `tahun_ajaran_aktif`, `semester_aktif`, `is_rapor_open`, `logo_sekolah`, `status`, `max_indent_years`, `kuota_tk_pagi`, `kuota_tk_siang`, `kuota_paud`, `created_at`, `updated_at`) VALUES
(1, 'TK Permata', '12345678', 'Jl. Pendidikan No. 1, Pasuruan', 'Ibu Siti Aminah', '08123456789', '2025/2026', 'Genap', 1, NULL, 'aktif', 3, 25, 30, 25, '2026-05-19 03:48:16', '2026-07-05 21:48:48'),
(2, 'Tk Matahari', '1234567890', 'Jalan Bunga Bunga no 00', 'Sadiya', '1234567890', '2026/2027', 'Genap', 0, 'logos/7kKGbTvePn5b7ovpz8M2GSdhxYwzuIEXetZ6s5dE.jpg', 'aktif', 3, 25, 25, 25, '2026-06-03 00:48:28', '2026-06-03 00:48:28');

-- --------------------------------------------------------

--
-- Table structure for table `school_modules`
--

CREATE TABLE `school_modules` (
  `id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `module_id` bigint UNSIGNED NOT NULL,
  `is_active_for_guru` tinyint(1) NOT NULL DEFAULT '1',
  `is_active_for_admin` tinyint(1) NOT NULL DEFAULT '1',
  `is_active_for_kepsek` tinyint(1) NOT NULL DEFAULT '1',
  `is_active_for_wali_murid` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `school_modules`
--

INSERT INTO `school_modules` (`id`, `school_id`, `module_id`, `is_active_for_guru`, `is_active_for_admin`, `is_active_for_kepsek`, `is_active_for_wali_murid`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 1, 1, '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(2, 1, 2, 0, 1, 1, 1, '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(3, 1, 3, 1, 1, 1, 1, '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(4, 1, 4, 1, 1, 0, 1, '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(5, 1, 5, 1, 1, 1, 1, '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(6, 1, 6, 1, 1, 1, 1, '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(7, 1, 7, 1, 1, 1, 1, '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(8, 1, 8, 0, 1, 0, 1, '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(9, 1, 9, 0, 1, 1, 1, '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(10, 1, 10, 1, 1, 1, 1, '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(11, 2, 1, 1, 1, 1, 1, '2026-06-03 00:48:47', '2026-06-03 00:48:47'),
(12, 2, 2, 1, 1, 1, 1, '2026-06-03 00:48:47', '2026-06-03 00:48:47'),
(13, 2, 3, 1, 1, 1, 1, '2026-06-03 00:48:47', '2026-06-03 00:48:47'),
(14, 2, 4, 1, 1, 1, 1, '2026-06-03 00:48:47', '2026-06-03 00:48:47'),
(15, 2, 5, 1, 1, 1, 1, '2026-06-03 00:48:47', '2026-06-03 00:48:47'),
(16, 2, 6, 1, 1, 1, 1, '2026-06-03 00:48:47', '2026-06-03 00:48:47'),
(17, 2, 7, 1, 1, 1, 1, '2026-06-03 00:48:47', '2026-06-03 00:48:47'),
(18, 2, 8, 1, 1, 1, 1, '2026-06-03 00:48:47', '2026-06-03 00:48:47'),
(19, 2, 9, 1, 1, 1, 1, '2026-06-03 00:48:47', '2026-06-03 00:48:47'),
(20, 2, 10, 1, 1, 1, 1, '2026-06-03 00:48:47', '2026-06-03 00:48:47'),
(21, 1, 11, 1, 1, 1, 1, NULL, NULL),
(22, 1, 12, 1, 1, 1, 1, NULL, NULL),
(23, 1, 13, 0, 1, 1, 1, NULL, NULL),
(24, 1, 14, 0, 1, 0, 1, NULL, NULL),
(25, 1, 15, 0, 1, 0, 1, NULL, NULL),
(26, 2, 11, 1, 1, 1, 1, NULL, NULL),
(27, 2, 12, 1, 1, 1, 1, NULL, NULL),
(28, 2, 13, 1, 1, 1, 1, NULL, NULL),
(29, 2, 14, 1, 1, 1, 1, NULL, NULL),
(30, 2, 15, 1, 1, 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

CREATE TABLE `semesters` (
  `id` bigint UNSIGNED NOT NULL,
  `tahun_ajaran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` enum('Ganjil','Genap') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `semesters`
--

INSERT INTO `semesters` (`id`, `tahun_ajaran`, `jenis`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '2025/2026', 'Genap', 0, '2026-05-19 03:48:16', '2026-05-21 03:33:08'),
(2, '2026/2027', 'Genap', 0, '2026-05-19 10:01:33', '2026-05-21 03:33:08'),
(3, '2027/2028', 'Ganjil', 1, '2026-05-21 03:33:04', '2026-05-21 03:33:08');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('jCypU6obL2Fsdr6MEAGHX8oECSs7p3YeVE8VSYAz', 6, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaFBjekxJNmRudkZjUTRmeTVXYzhmQzZpZEhGa2lRSkdkZzc2eVNoYiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYXRlcmkvcGVyc2V0dWp1YW4iO3M6NToicm91dGUiO3M6MTk6Im1hdGVyaS5rZXBzZWsuaW5kZXgiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo2O30=', 1783359847),
('kLnP78S2w7gAByQrcFt6egyxBD4QG1ppS2OganOy', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidm5zYTAzVTNIeHNrbTBLT0M2YzFmRDRjN2pVWUFGalMwWk9GY0IxWSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYXRlcmkvZ3VydSI7czo1OiJyb3V0ZSI7czoxNzoibWF0ZXJpLmd1cnUuaW5kZXgiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1783360230);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'hero_title_1', 'Ciptakan Generasi Gen Z', '2026-07-06 08:45:46', '2026-07-06 08:45:46'),
(2, 'hero_title_2', 'Cerdas & Ceria', '2026-07-06 08:45:46', '2026-07-06 08:45:46'),
(3, 'hero_subtitle', 'Memberikan pendidikan terbaik untuk generasi masa depan dengan fasilitas lengkap dan tenaga pengajar profesional. Amankan kuota putra-putri Anda sekarang. Cuy', '2026-07-06 08:45:46', '2026-07-06 08:47:00'),
(4, 'files', NULL, '2026-07-06 08:45:46', '2026-07-06 08:45:46'),
(5, 'profil_title', 'Kenali Lebih Dekat', '2026-07-06 08:45:46', '2026-07-06 08:45:46'),
(6, 'profil_subtitle', '<b>Pelajari lebih lanjut tentang institusi dan program unggulan kami untuk putra-putri Anda.</b>', '2026-07-06 08:45:46', '2026-07-06 08:47:00'),
(7, 'yayasan_title', 'Yayasan Pendidikan Al-Hikmah 2', '2026-07-06 08:45:46', '2026-07-06 08:47:00'),
(8, 'yayasan_desc', '<i>Yayasan kami berdiri sejak tahun 2010 dengan komitmen penuh untuk mencetak generasi Islami yang unggul, berakhlak mulia, dan siap menghadapi tantangan masa depan. Kami menaungi berbagai cabang pendidikan dengan standar pengajaran terpadu, fasilitas belajar yang modern, dan tenaga pendidik profesional.</i>', '2026-07-06 08:45:46', '2026-07-06 08:47:00'),
(9, 'yayasan_visi', '<u>Mewujudkan lembaga pendidikan yang berpijak pada penanaman aqidah Islam dan kemuliaan karakter.</u>', '2026-07-06 08:45:46', '2026-07-06 08:47:00'),
(10, 'yayasan_misi', 'Menyelenggarakan proses pembelajaran yang berkualitas, kreatif, inovatif, dan bermakna.', '2026-07-06 08:45:46', '2026-07-06 08:45:46'),
(11, 'tk_title', 'Taman Kanak-Kanak (TK)', '2026-07-06 08:45:46', '2026-07-06 08:45:46'),
(12, 'tk_desc', 'Program Taman Kanak-Kanak dirancang untuk mengasah kognitif, motorik, dan sosio-emosional anak melalui pendekatan <i>learning by playing</i>. Tersedia pilihan jam belajar fleksibel: <b>TK Pagi</b> dan <b>TK Siang</b> untuk mendukung rutinitas keluarga Anda.', '2026-07-06 08:45:46', '2026-07-06 08:45:46'),
(13, 'paud_title', 'Pendidikan Anak Usia Dini (PAUD)', '2026-07-06 08:45:46', '2026-07-06 08:45:46'),
(14, 'paud_desc', 'Program PAUD/Kelompok Bermain kami memfasilitasi masa emas <i>(golden age)</i> anak. Lingkungan sekolah dibuat senyaman dan seaman mungkin seperti di rumah (<i>homely environment</i>) untuk merangsang rasa ingin tahu dan keberanian anak bersosialisasi.', '2026-07-06 08:45:46', '2026-07-06 08:45:46'),
(15, 'yayasan_profile_name', 'Dr. H. Fulan, M.Pd', '2026-07-06 08:54:36', '2026-07-06 08:54:36'),
(16, 'yayasan_profile_title', 'Ketua Yayasan', '2026-07-06 08:54:36', '2026-07-06 08:54:36'),
(17, 'yayasan_bullets', 'Berdiri sejak 2010, Akreditasi A, 2 Unit Sekolah, 320+ Alumni', '2026-07-06 08:54:36', '2026-07-06 08:54:36'),
(18, 'yayasan_programs', 'Tahfidz Quran, Bahasa Arab & Inggris, Seni & Kreativitas, Pendidikan Karakter', '2026-07-06 08:54:36', '2026-07-06 08:54:36'),
(19, 'tk_profile_name', 'Bunda Fatimah, S.Pd', '2026-07-06 08:54:36', '2026-07-06 08:54:36'),
(20, 'tk_profile_title', 'Kepala Sekolah TK', '2026-07-06 08:54:36', '2026-07-06 08:54:36'),
(21, 'tk_bullets', 'Usia 4-6 Tahun, Kurikulum Tematik, Native Speaker', '2026-07-06 08:54:36', '2026-07-06 08:54:36'),
(22, 'tk_programs', 'Renang Ceria, Field Trip Edutainment, Cek Kesehatan Rutin', '2026-07-06 08:54:36', '2026-07-06 08:54:36'),
(23, 'paud_profile_name', 'Bunda Aisyah, S.Psi', '2026-07-06 08:54:36', '2026-07-06 08:54:36'),
(24, 'paud_profile_title', 'Koordinator PAUD', '2026-07-06 08:54:36', '2026-07-06 08:54:36'),
(25, 'paud_bullets', 'Usia 2-4 Tahun, Toilet Training, Indoor Playground', '2026-07-06 08:54:36', '2026-07-06 08:54:36'),
(26, 'paud_programs', 'Bernyanyi & Menari, Story Telling, Sensoric Play', '2026-07-06 08:54:36', '2026-07-06 08:54:36'),
(27, 'yayasan_image', '/storage/landing/DlcR9JMaT7veJME3jPcfleuDacdkGXUtxmNEPIfL.jpg', '2026-07-06 08:54:36', '2026-07-06 08:54:36'),
(28, 'footer_title', 'Yayasan Al-Hikmah 2', '2026-07-06 09:02:12', '2026-07-06 09:02:12'),
(29, 'footer_tagline', 'Ciptakan Generasi Cerdas, Ceria, & Berakhlak Mulia Sekali', '2026-07-06 09:02:12', '2026-07-06 09:02:12'),
(30, 'footer_address', 'Jl. KH. Wahid Hasyim No. 45, Kecamatan Sukun, Kota Malang, Jawa Timur 65117', '2026-07-06 09:02:12', '2026-07-06 09:02:12'),
(31, 'footer_ig', '#', '2026-07-06 09:02:12', '2026-07-06 09:02:12'),
(32, 'footer_fb', '#', '2026-07-06 09:02:12', '2026-07-06 09:02:12'),
(33, 'footer_tw', '#', '2026-07-06 09:02:12', '2026-07-06 09:02:12'),
(34, 'footer_yt', '#', '2026-07-06 09:02:12', '2026-07-06 09:02:12'),
(35, 'footer_phone', '(0341) 567-890 1', '2026-07-06 09:02:12', '2026-07-06 09:02:12'),
(36, 'footer_whatsapp', '0812-3456-78902', '2026-07-06 09:02:12', '2026-07-06 09:02:12'),
(37, 'footer_email', 'admin@alhikmah.sch.id', '2026-07-06 09:02:12', '2026-07-06 09:02:12'),
(38, 'footer_hours', 'Senin–Sabtu, 07.00–15.00', '2026-07-06 09:02:12', '2026-07-06 09:02:12'),
(39, 'site_title', 'Yayasan Al-Hikmah 2', '2026-07-06 09:20:19', '2026-07-06 09:20:19'),
(40, 'site_logo', '/storage/landing/pg89b801w6c10eeQwcUCEtA8wszdJSHCgDzlv8cf.webp', '2026-07-06 09:20:19', '2026-07-06 09:20:19');

-- --------------------------------------------------------

--
-- Table structure for table `siswas`
--

CREATE TABLE `siswas` (
  `id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED DEFAULT NULL,
  `nama_lengkap` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `program_pilihan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nisn` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_induk` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_ortu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_anak` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bukti_pembayaran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempat_lahir` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `nama_ibu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_ayah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tinggi_badan` int DEFAULT NULL,
  `berat_badan` int DEFAULT NULL,
  `koordinat_rumah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rt` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rw` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dusun` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kelurahan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kecamatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_pos` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `anak_ke` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jml_saudara` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_tinggal` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alat_transportasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jarak_rumah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lingkar_kepala` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik_ayah` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_lahir_ayah` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pendidikan_ayah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pekerjaan_ayah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `penghasilan_ayah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik_ibu` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_lahir_ibu` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pendidikan_ibu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pekerjaan_ibu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `penghasilan_ibu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `siswas`
--

INSERT INTO `siswas` (`id`, `school_id`, `nama_lengkap`, `program_pilihan`, `nik`, `nisn`, `no_induk`, `nama_ortu`, `foto_anak`, `bukti_pembayaran`, `tempat_lahir`, `tgl_lahir`, `jenis_kelamin`, `status`, `nama_ibu`, `nama_ayah`, `user_id`, `created_at`, `updated_at`, `tinggi_badan`, `berat_badan`, `koordinat_rumah`, `alamat`, `rt`, `rw`, `dusun`, `kelurahan`, `kecamatan`, `kode_pos`, `agama`, `anak_ke`, `jml_saudara`, `jenis_tinggal`, `alat_transportasi`, `telepon`, `hp`, `email`, `jarak_rumah`, `lingkar_kepala`, `nik_ayah`, `tahun_lahir_ayah`, `pendidikan_ayah`, `pekerjaan_ayah`, `penghasilan_ayah`, `nik_ibu`, `tahun_lahir_ibu`, `pendidikan_ibu`, `pekerjaan_ibu`, `penghasilan_ibu`) VALUES
(1, 1, 'Rania Putri', NULL, '3514012305200001', '1234567869', NULL, 'Bapak/Ibu Santoso', NULL, NULL, 'Surabaya', '2020-05-10', 'Perempuan', 'aktif', 'Ibu Sari', 'Bapak Santoso', NULL, '2026-05-19 03:48:17', '2026-06-27 09:54:51', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ortu@tk.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 1, 'Budi Santoso', NULL, '3514012305200002', '1234567899', NULL, 'Bapak/Ibu Harto', NULL, NULL, 'Malang', '2019-08-15', 'Laki-laki', 'lulus', 'Ibu Dewi', 'Bapak Harto', NULL, '2026-05-19 03:48:17', '2026-07-05 10:30:16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ortu2@tk.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 1, 'Sadiya Maritza', NULL, '12345678913', NULL, NULL, 'Ibu', 'foto_siswa/39luUUgudmM3h9ZlKdXL8phb7qHfwZ3CxBm4abR6.jpg', NULL, 'Blitar', '2016-09-08', 'Perempuan', 'aktif', NULL, NULL, NULL, '2026-05-19 08:23:25', '2026-05-19 08:23:25', 149, 50, '3J38+6C Jatimulyo, Kota Malang, Jawa Timur', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 1, 'AZZAM AULIAN PUTRA', NULL, '3505103112190002', '3196599897', '', '-', NULL, NULL, 'BLITAR', '2019-12-31', 'Laki-laki', 'aktif', NULL, NULL, NULL, '2026-06-01 15:56:22', '2026-06-01 15:56:22', NULL, NULL, NULL, '-', '-', '-', '-', '-', '-', '-', 'Islam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 1, 'KEANA GAZALA ACHSAN', NULL, '3572036402200002', '3204668269', '', '-', NULL, NULL, 'BLITAR', '2020-02-24', 'Perempuan', 'aktif', NULL, NULL, NULL, '2026-06-01 15:56:22', '2026-06-01 15:56:22', NULL, NULL, NULL, '-', '-', '-', '-', '-', '-', '-', 'Islam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 1, 'KENZIE ANDREW FORTUNIO', NULL, '3505101004200002', '3203476148', '', '-', NULL, NULL, 'BLITAR', '2020-04-10', 'Laki-laki', 'aktif', NULL, NULL, NULL, '2026-06-01 15:56:22', '2026-06-01 15:56:22', NULL, NULL, NULL, '-', '-', '-', '-', '-', '-', '-', 'Islam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 1, 'MUHAMMAD MIRZA HANIF', NULL, '3505101509190001', '3196279065', '', '-', NULL, NULL, 'BLITAR', '2019-09-15', 'Laki-laki', 'aktif', NULL, NULL, NULL, '2026-06-01 15:56:22', '2026-06-01 15:56:22', NULL, NULL, NULL, '-', '-', '-', '-', '-', '-', '-', 'Islam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 1, 'MUHAMMAD RUSLI RASYID', NULL, '3505101803200002', '3206749439', '', '-', NULL, NULL, 'BLITAR', '2020-03-18', 'Laki-laki', 'aktif', NULL, NULL, NULL, '2026-06-01 15:56:22', '2026-06-01 15:56:22', NULL, NULL, NULL, '-', '-', '-', '-', '-', '-', '-', 'Islam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 1, 'RADHITYA WIJAYA', NULL, '3505102311200002', '3209617761', '', '-', NULL, NULL, 'BLITAR', '2020-11-23', 'Laki-laki', 'aktif', NULL, NULL, NULL, '2026-06-01 15:56:22', '2026-06-01 15:56:22', NULL, NULL, NULL, '-', '-', '-', '-', '-', '-', '-', 'Islam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 1, 'RAFA ALTHAFUNNISA', NULL, '3505105603190002', '3191213107', '', '-', NULL, NULL, 'BLITAR', '2019-03-16', 'Perempuan', 'aktif', NULL, NULL, NULL, '2026-06-01 15:56:22', '2026-06-01 15:56:22', NULL, NULL, NULL, '-', '-', '-', '-', '-', '-', '-', 'Islam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 1, 'REYNAND EL BARCA PRATAMA', NULL, '3505101310200001', '3209183411', '', '-', NULL, NULL, 'BLITAR', '2020-10-13', 'Laki-laki', 'aktif', NULL, NULL, NULL, '2026-06-01 15:56:22', '2026-06-01 15:56:22', NULL, NULL, NULL, '-', '-', '-', '-', '-', '-', '-', 'Islam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 1, 'Sadiya Maritza', NULL, '3505101310200002', '1234567899', '', '-', NULL, NULL, 'BLITAR', '2020-10-14', 'Perempuan', 'aktif', NULL, NULL, NULL, '2026-06-01 15:56:22', '2026-06-02 11:50:58', NULL, NULL, NULL, '-', '-', '-', '-', '-', '-', '-', 'Islam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 1, 'anak wali2', 'TK Pagi', '1234575', NULL, NULL, NULL, 'foto_siswa/Lg0zvZnRkSfwxyC8Gk7SluxqHE7RsKqrdvFCG4zQ.jpg', NULL, 'Malang', '2019-07-12', 'Perempuan', 'aktif', 'Rahayu Kuswandari', 'Omar Suryono', NULL, '2026-06-19 19:35:36', '2026-06-19 19:35:36', 80, 20, NULL, 'Desa Kuningan', '2', '3', 'ad3wef', 'wdefwf', 'fwe3f3', '66171', 'Islam', '1', '2', 'Bersama orang tua', 'jalan', NULL, '089681010691', 'ortu2@tk.com', '4', '40', '123456789', '1234', 'asdfd', 'WDEWVGG', '500', '1234567', '1234', 'ASRG', 'AWWSDFG', '200'),
(14, 2, 'anak wali2', 'TK Pagi', '123456789009876543', NULL, NULL, NULL, 'foto_siswa/uEbNeXuN7OUXk7JrFH4KcJWqH0lK7YqQt8AllOFR.jpg', NULL, 'Malang', '2022-07-20', 'Perempuan', 'aktif', 'Rahayu Kuswandari', 'Omar Suryono', NULL, '2026-06-19 19:37:44', '2026-06-19 19:37:44', 70, 20, NULL, 'Desa Kuningan', '2', '3', 'ad3wef', 'scwecdecwe', 'dsvadv', '66171', 'Islam', '1', '2', 'Bersama orang tua', 'jalan', NULL, '123456789', 'ortu2@tk.com', '3', '40', '123456765432', '1234', 'adfg', 'asdf', '500', '167765245678998765', '1234', 'ASRG', 'AWWSDFG', '200'),
(15, 1, 'Sasa', 'TK Siang', '1234567865432', NULL, NULL, NULL, 'foto_siswa/oDlXBglYuqLUWtIXZohjQA9HS88iqLNNvrbPjCKD.jpg', NULL, 'argthn', '2023-06-06', 'Perempuan', 'aktif', 'adfghn', 'asadfgnh', NULL, '2026-06-19 22:46:07', '2026-06-19 22:46:07', 90, 35, NULL, '234rthyfregbh45t', '3', '5', 'sdfbgnh', 'asdfghn', 'sdfn', '1234', 'Islam', '2', '2', 'Bersama orang tua', NULL, NULL, '12345432134', 'ortu@tk.com', '2', '75', '1234432', '123', 'asf', 'wdefrg', '600', '12345432', '2344', 'efrgr', 'dewfrg', '300'),
(16, 1, 'Maritza', 'TK Pagi (Tahun: 2028/2029)', '3507542670943981', NULL, NULL, NULL, 'foto_siswa/Y3g6QJ95KzaWleRjWQKp9rJ2EAB18GRm5w8NtCmb.jpg', 'bukti_pendaftaran/cbYneseKBH3CZGwCoBb2BxgRfXYQW40yhnO87h5f.jpg', 'Malang', '2026-07-09', 'Perempuan', 'aktif', 'Rahayu Kuswandari', 'Omar Suryono', NULL, '2026-07-01 10:18:56', '2026-07-01 22:20:14', 105, 20, NULL, 'Desa Kuningan', '2', '3', 'ad3wef', 'scwecdecwe', 'fwe3f3', '66171', 'Islam', '2', '2', 'Bersama orang tua', 'jalan', NULL, '089681010691', NULL, '3', '45', '3514012305201111', '1234', 'asdfd', 'WDEWVGG', '500', '3514012305201212', '1234', 'ASRG', 'AWWSDFG', '200'),
(18, 1, 'Amar Amir', 'PAUD (Tahun: 2029/2030)', '3505105603191155', NULL, NULL, NULL, 'foto_siswa/qep7NdEGYiyntreEMdeOsXvWvyocwqh0xvvdndqO.jpg', 'bukti_pendaftaran/loHTbv7pKH4xqKSiUTIvRcqodhBetOd46solhxxS.jpg', 'Sibolga', '2024-05-02', 'Laki-laki', 'aktif', 'Anastasia Mandasari', 'Omar Suryono', NULL, '2026-07-01 22:03:19', '2026-07-01 22:18:31', 12, 22, NULL, 'Desa Kuningan', '2', '3', 'ad3wef', 'scwecdecwe', 'fwe3f3', '66171', 'Islam', '3', '4', 'Wali', 'jalan', NULL, '089681010691', 'ortu@tk.com', NULL, '13', '3505105603193333', '1234', 'asdfd', 'WDEWVGG', '500', '3505105603195555', '1234', 'ASRG', 'AWWSDFG', '200'),
(19, 1, 'asdfghj', 'TK Pagi (Tahun: 2031/2032)', '3514012305208768', NULL, NULL, NULL, 'foto_siswa/uSTJvAmKrbzOnP0FeSGjNKWuryZBxRbMlpzC3Ig4.jpg', 'bukti_pendaftaran/OAfcBW2pMR1vLAFluzZN95fKzjidWAL0irYtLMDq.jpg', 'argthn', '2026-07-22', 'Perempuan', 'menunggu', 'adfghn', 'asadfgnh', NULL, '2026-07-05 10:23:29', '2026-07-05 10:23:29', 80, 21, NULL, '234rthyfregbh45t', '3', '5', 'sdfbgnh', 'asdfghn', 'sdfn', '3134', 'Hindu', '2', '3', 'Bersama orang tua', 'motor', NULL, '12345432134', 'ortu5@tk.com', NULL, '30', '3514012305202568', '2345', 'asf', 'wdefrg', '600', '3514012305201702', '1234', 'efrgr', 'dewfrg', '300'),
(20, 1, 'Sadam', 'TK Siang (Tahun: 2027/2028)', '3514012305212367', NULL, NULL, NULL, 'foto_siswa/cCMM2Q7US0p8MEQZnLjje8vwnc5AV15a3ouFuo98.jpg', 'bukti_pendaftaran/SjaZJrTvwa0hEa8bmAJYrsddb3UuTYdT6X01Q3H1.jpg', 'argthn', '2026-07-02', 'Laki-laki', 'menunggu', 'adfghn', 'asadfgnh', NULL, '2026-07-06 07:59:45', '2026-07-06 07:59:45', 60, 18, NULL, '234rthyfregbh45t', '3', '5', 'sdfbgnh', 'asdfghn', 'sdfn', '1457', 'Islam', '1', '2', 'Wali', 'motor', NULL, '12345432134', 'ortu5@tk.com', NULL, '34', '3514012305226803', '1234', 'asf', 'wdefrg', '600', '3514012305782047', '1234', 'efrgr', 'dewfrg', '300');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED DEFAULT NULL,
  `kelas_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `school_id`, `kelas_id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 'Super Admin', 'superadmin@tkceria.com', '$2y$12$6Dv7X1W3iyrP4dSWmpWgb.MGWb82HwTJELjiC3XDjPoVYo7pUl8S.', 'super_admin', '2026-05-19 03:48:16', '2026-05-19 03:48:16'),
(2, 1, NULL, 'Admin TK Permata', 'admin@tk.com', '$2y$12$Z2XUUhApK4wiZlNfTOkBIebemWnjtEIsX8oyxXbsikIMQc3o5vEB.', 'admin', '2026-05-19 03:48:16', '2026-05-19 03:48:16'),
(3, 1, 1, 'Naning', 'naning@tk.com', '$2y$12$Ys0d25uZOO5h1euzu/kCgeNEAvsBSxgoAv2W/6DzDOOuh9wyWwC46', 'guru', '2026-05-19 03:48:16', '2026-06-27 10:08:40'),
(4, 1, NULL, 'Wali Murid', 'ortu@tk.com', '$2y$12$x7ZIWUi20hK0Psr3evkkeODLq65WxkW/Ogb7VjpcA2tN27hEgb2uu', 'wali_murid', '2026-05-19 03:48:17', '2026-05-19 03:48:17'),
(5, 2, NULL, 'adminmatahari', 'adminmatahari@tk.com', '$2y$12$tr0a9cbPfNqk2hrnSr.NYecu7TLHaXWRsKd8qK6JTjz4JuQP6q.3i', 'admin', '2026-06-03 00:49:47', '2026-06-03 01:32:59'),
(6, 1, NULL, 'Sadiya2', 'kepsek@tk.com', '$2y$12$hshkpXEMDxnf/Z81HjPbEuRHHkVGJL15enVcfrdRALHaWJkGEvmqW', 'kepsek', '2026-06-03 01:27:23', '2026-06-19 20:45:16'),
(7, NULL, NULL, 'ortu', 'ortu2@tk.com', '$2y$12$a067cIJwqd4lZePViI9FJe9SU6d0tK244tnUuyWKO005wjohcLxJ2', 'wali_murid', '2026-06-19 09:28:57', '2026-06-19 09:28:57'),
(8, NULL, NULL, 'ortu3', 'ortu3@tk.com', '$2y$12$BAS4vBwmUP93XSQqI2UD2OgWZnDO79I9LkLq58WMnfgJohFNmHlxa', 'wali_murid', '2026-06-19 09:38:49', '2026-06-19 09:38:49'),
(9, NULL, NULL, 'ortu4', 'ortu4@tk.com', '$2y$12$z/uCtfB4Shf66MN3E8.jn.RZ3PSp16PqIhclUSIY0bK0UKV2syWgi', 'wali_murid', '2026-06-19 09:45:33', '2026-06-19 09:45:33'),
(10, NULL, NULL, 'ortu5', 'ortu5@tk.com', '$2y$12$.JteLqWIQgPQnf31.38as.8FuQNwTU4TeptrXoJL3vHOJVJbJTZ8q', 'wali_murid', '2026-06-29 22:46:46', '2026-06-29 22:46:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensis`
--
ALTER TABLE `absensis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `absensis_siswa_id_foreign` (`siswa_id`),
  ADD KEY `absensis_school_id_foreign` (`school_id`);

--
-- Indexes for table `anggota_kelas`
--
ALTER TABLE `anggota_kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anggota_kelas_siswa_id_foreign` (`siswa_id`),
  ADD KEY `anggota_kelas_kelas_id_foreign` (`kelas_id`),
  ADD KEY `anggota_kelas_semester_id_foreign` (`semester_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcements_school_id_foreign` (`school_id`),
  ADD KEY `announcements_kelas_id_foreign` (`kelas_id`);

--
-- Indexes for table `arsip_rapors`
--
ALTER TABLE `arsip_rapors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `arsip_rapors_siswa_id_foreign` (`siswa_id`),
  ADD KEY `arsip_rapors_semester_id_foreign` (`semester_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `jurnal_comments`
--
ALTER TABLE `jurnal_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jurnal_comments_jurnal_id_foreign` (`jurnal_id`),
  ADD KEY `jurnal_comments_user_id_foreign` (`user_id`);

--
-- Indexes for table `jurnal_harians`
--
ALTER TABLE `jurnal_harians`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelas_school_id_foreign` (`school_id`);

--
-- Indexes for table `materis`
--
ALTER TABLE `materis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `materis_school_id_foreign` (`school_id`),
  ADD KEY `materis_created_by_foreign` (`created_by`),
  ADD KEY `materis_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `materi_comments`
--
ALTER TABLE `materi_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `materi_comments_materi_id_foreign` (`materi_id`),
  ADD KEY `materi_comments_user_id_foreign` (`user_id`);

--
-- Indexes for table `materi_jurnals`
--
ALTER TABLE `materi_jurnals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `materi_kelas_targets`
--
ALTER TABLE `materi_kelas_targets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `materi_kelas_targets_materi_id_foreign` (`materi_id`),
  ADD KEY `materi_kelas_targets_kelas_id_foreign` (`kelas_id`);

--
-- Indexes for table `materi_read_receipts`
--
ALTER TABLE `materi_read_receipts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `materi_read_receipts_materi_id_foreign` (`materi_id`),
  ADD KEY `materi_read_receipts_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `perkembangan_fisiks`
--
ALTER TABLE `perkembangan_fisiks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `perkembangan_fisiks_siswa_id_foreign` (`siswa_id`),
  ADD KEY `perkembangan_fisiks_school_id_foreign` (`school_id`);

--
-- Indexes for table `rapors`
--
ALTER TABLE `rapors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rapors_siswa_id_foreign` (`siswa_id`),
  ADD KEY `rapors_school_id_foreign` (`school_id`),
  ADD KEY `rapors_semester_id_foreign` (`semester_id`),
  ADD KEY `rapors_template_id_foreign` (`template_id`);

--
-- Indexes for table `rapor_templates`
--
ALTER TABLE `rapor_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rapor_template_items`
--
ALTER TABLE `rapor_template_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rapor_template_items_template_id_foreign` (`template_id`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `school_modules`
--
ALTER TABLE `school_modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `school_modules_school_id_foreign` (`school_id`),
  ADD KEY `school_modules_module_id_foreign` (`module_id`);

--
-- Indexes for table `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `siswas`
--
ALTER TABLE `siswas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `siswas_nik_unique` (`nik`),
  ADD KEY `siswas_user_id_foreign` (`user_id`),
  ADD KEY `siswas_school_id_foreign` (`school_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_school_id_foreign` (`school_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensis`
--
ALTER TABLE `absensis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `anggota_kelas`
--
ALTER TABLE `anggota_kelas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `arsip_rapors`
--
ALTER TABLE `arsip_rapors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jurnal_comments`
--
ALTER TABLE `jurnal_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jurnal_harians`
--
ALTER TABLE `jurnal_harians`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `materis`
--
ALTER TABLE `materis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `materi_comments`
--
ALTER TABLE `materi_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `materi_jurnals`
--
ALTER TABLE `materi_jurnals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `materi_kelas_targets`
--
ALTER TABLE `materi_kelas_targets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `materi_read_receipts`
--
ALTER TABLE `materi_read_receipts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `perkembangan_fisiks`
--
ALTER TABLE `perkembangan_fisiks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `rapors`
--
ALTER TABLE `rapors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rapor_templates`
--
ALTER TABLE `rapor_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rapor_template_items`
--
ALTER TABLE `rapor_template_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `school_modules`
--
ALTER TABLE `school_modules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `siswas`
--
ALTER TABLE `siswas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensis`
--
ALTER TABLE `absensis`
  ADD CONSTRAINT `absensis_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `absensis_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `anggota_kelas`
--
ALTER TABLE `anggota_kelas`
  ADD CONSTRAINT `anggota_kelas_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `anggota_kelas_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `anggota_kelas_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `announcements_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `arsip_rapors`
--
ALTER TABLE `arsip_rapors`
  ADD CONSTRAINT `arsip_rapors_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `arsip_rapors_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jurnal_comments`
--
ALTER TABLE `jurnal_comments`
  ADD CONSTRAINT `jurnal_comments_jurnal_id_foreign` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal_harians` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jurnal_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `materis`
--
ALTER TABLE `materis`
  ADD CONSTRAINT `materis_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `materis_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `materis_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `materi_comments`
--
ALTER TABLE `materi_comments`
  ADD CONSTRAINT `materi_comments_materi_id_foreign` FOREIGN KEY (`materi_id`) REFERENCES `materi_jurnals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `materi_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `materi_kelas_targets`
--
ALTER TABLE `materi_kelas_targets`
  ADD CONSTRAINT `materi_kelas_targets_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `materi_kelas_targets_materi_id_foreign` FOREIGN KEY (`materi_id`) REFERENCES `materi_jurnals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `materi_read_receipts`
--
ALTER TABLE `materi_read_receipts`
  ADD CONSTRAINT `materi_read_receipts_materi_id_foreign` FOREIGN KEY (`materi_id`) REFERENCES `materi_jurnals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `materi_read_receipts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `perkembangan_fisiks`
--
ALTER TABLE `perkembangan_fisiks`
  ADD CONSTRAINT `perkembangan_fisiks_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `perkembangan_fisiks_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rapors`
--
ALTER TABLE `rapors`
  ADD CONSTRAINT `rapors_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rapors_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rapors_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rapors_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `rapor_templates` (`id`);

--
-- Constraints for table `rapor_template_items`
--
ALTER TABLE `rapor_template_items`
  ADD CONSTRAINT `rapor_template_items_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `rapor_templates` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `school_modules`
--
ALTER TABLE `school_modules`
  ADD CONSTRAINT `school_modules_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `school_modules_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `siswas`
--
ALTER TABLE `siswas`
  ADD CONSTRAINT `siswas_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `siswas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
