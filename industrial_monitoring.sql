-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 23 Des 2025 pada 04.07
-- Versi server: 11.7.2-MariaDB
-- Versi PHP: 8.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `industrial_monitoring`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `kondisi_mesin`
--

CREATE TABLE `kondisi_mesin` (
  `id` bigint(20) NOT NULL,
  `machine_id` varchar(20) NOT NULL,
  `status_mesin` enum('ON','OFF') NOT NULL,
  `mode_mesin` enum('AUTO','MANUAL','FAULT') NOT NULL,
  `tanggal` date NOT NULL,
  `shift` varchar(10) NOT NULL,
  `durasi` int(11) NOT NULL,
  `waktu` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kondisi_mesin`
--

INSERT INTO `kondisi_mesin` (`id`, `machine_id`, `status_mesin`, `mode_mesin`, `tanggal`, `shift`, `durasi`, `waktu`) VALUES
(1, 'MC-CURING-01', 'OFF', 'AUTO', '2025-12-23', 'SHIFT_2', 0, '2025-12-23 02:33:02'),
(2, 'MC-CURING-01', 'OFF', 'AUTO', '2025-12-23', 'SHIFT_2', 60, '2025-12-23 02:34:02'),
(3, 'MC-CURING-01', 'ON', 'AUTO', '2025-12-23', 'SHIFT_2', 21, '2025-12-23 02:34:23'),
(4, 'MC-CURING-01', 'OFF', 'AUTO', '2025-12-23', 'SHIFT_2', 155, '2025-12-23 02:36:58'),
(5, 'MC-CURING-01', 'ON', 'AUTO', '2025-12-23', 'SHIFT_2', 15, '2025-12-23 02:37:13'),
(6, 'MC-01', 'OFF', 'AUTO', '2025-12-23', 'SHIFT_1', 6, '2025-12-23 02:41:52'),
(7, 'MC-01', 'ON', 'AUTO', '2025-12-23', 'SHIFT_1', 4, '2025-12-23 02:41:58'),
(8, 'MC-01', 'OFF', 'AUTO', '2025-12-23', 'SHIFT_1', 4, '2025-12-23 02:42:02'),
(9, 'MC-01', 'ON', 'AUTO', '2025-12-23', 'SHIFT_1', 98, '2025-12-23 02:42:06'),
(10, 'MC-01', 'OFF', 'FAULT', '2025-12-23', 'SHIFT_1', 9, '2025-12-23 02:43:44'),
(11, 'MC-01', 'ON', 'FAULT', '2025-12-23', 'SHIFT_1', 0, '2025-12-23 02:43:53'),
(12, 'MC-01', 'ON', 'MANUAL', '2025-12-23', 'SHIFT_1', 395, '2025-12-23 02:43:57'),
(13, 'MC-01', 'OFF', 'AUTO', '2025-12-23', 'SHIFT_1', 10, '2025-12-23 02:50:32'),
(14, 'MC-01', 'ON', 'AUTO', '2025-12-23', 'SHIFT_1', 0, '2025-12-23 02:50:42'),
(15, 'MC-01', 'ON', 'AUTO', '2025-12-23', 'SHIFT_1', 0, '2025-12-23 02:56:31'),
(16, 'MC-01', 'ON', 'AUTO', '2025-12-23', 'SHIFT_1', 0, '2025-12-23 02:56:32'),
(17, 'MC-01', 'ON', 'MANUAL', '2025-12-23', 'SHIFT_1', 249, '2025-12-23 02:56:36'),
(18, 'MC-01', 'OFF', 'AUTO', '2025-12-23', 'SHIFT_1', 8, '2025-12-23 03:00:45'),
(19, 'MC-01', 'ON', 'AUTO', '2025-12-23', 'SHIFT_1', 0, '2025-12-23 03:00:53'),
(20, 'MC-01', 'ON', 'FAULT', '2025-12-23', 'SHIFT_1', 6, '2025-12-23 03:01:10'),
(21, 'MC-01', 'OFF', 'FAULT', '2025-12-23', 'SHIFT_1', 4, '2025-12-23 03:01:16'),
(22, 'MC-01', 'ON', 'FAULT', '2025-12-23', 'SHIFT_1', 0, '2025-12-23 03:01:20'),
(23, 'MC-01', 'ON', 'FAULT', '2025-12-23', 'SHIFT_1', 0, '2025-12-23 03:05:49'),
(24, 'MC-01', 'ON', 'MANUAL', '2025-12-23', 'SHIFT_1', 0, '2025-12-23 03:05:58'),
(25, 'MC-01', 'ON', 'AUTO', '2025-12-23', 'SHIFT_1', 118, '2025-12-23 03:16:56'),
(26, 'MC-01', 'OFF', 'AUTO', '2025-12-23', 'SHIFT_1', 0, '2025-12-23 03:18:54'),
(27, 'MC-01', 'OFF', 'FAULT', '2025-12-23', 'SHIFT_1', 0, '2025-12-23 03:19:04'),
(28, 'MC-01', 'OFF', 'AUTO', '2025-12-23', 'SHIFT_1', 0, '2025-12-23 03:19:12'),
(29, 'MC-01', 'OFF', 'FAULT', '2025-12-23', 'SHIFT_1', 0, '2025-12-23 03:19:17'),
(30, 'MC-01', 'OFF', 'FAULT', '2025-12-23', 'SHIFT_1', 0, '2025-12-23 03:19:18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kondisi_mesin_write`
--

CREATE TABLE `kondisi_mesin_write` (
  `id` bigint(20) NOT NULL,
  `machine_id` varchar(20) NOT NULL,
  `perintah` enum('ON','OFF') NOT NULL,
  `mode_target` enum('AUTO','MANUAL','FAULT') DEFAULT NULL,
  `waktu` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kondisi_mesin_write`
--

INSERT INTO `kondisi_mesin_write` (`id`, `machine_id`, `perintah`, `mode_target`, `waktu`) VALUES
(1, 'MC-CURING-01', 'OFF', 'AUTO', '2025-12-23 02:33:02'),
(2, 'MC-CURING-01', 'OFF', 'AUTO', '2025-12-23 02:34:02'),
(3, 'MC-CURING-01', 'ON', 'AUTO', '2025-12-23 02:34:23'),
(4, 'MC-CURING-01', 'OFF', 'AUTO', '2025-12-23 02:36:58'),
(5, 'MC-CURING-01', 'ON', 'AUTO', '2025-12-23 02:37:13'),
(6, 'MC-01', 'OFF', 'AUTO', '2025-12-23 02:41:52'),
(7, 'MC-01', 'ON', 'AUTO', '2025-12-23 02:41:58'),
(8, 'MC-01', 'OFF', 'AUTO', '2025-12-23 02:42:02'),
(9, 'MC-01', 'ON', 'AUTO', '2025-12-23 02:42:06'),
(10, 'MC-01', 'OFF', 'FAULT', '2025-12-23 02:43:44'),
(11, 'MC-01', 'ON', 'FAULT', '2025-12-23 02:43:53'),
(12, 'MC-01', 'ON', 'MANUAL', '2025-12-23 02:43:57'),
(13, 'MC-01', 'OFF', 'AUTO', '2025-12-23 02:50:32'),
(14, 'MC-01', 'ON', 'AUTO', '2025-12-23 02:50:42'),
(15, 'MC-01', 'ON', 'AUTO', '2025-12-23 02:56:31'),
(16, 'MC-01', 'ON', 'AUTO', '2025-12-23 02:56:32'),
(17, 'MC-01', 'ON', 'MANUAL', '2025-12-23 02:56:36'),
(18, 'MC-01', 'OFF', 'AUTO', '2025-12-23 03:00:45'),
(19, 'MC-01', 'ON', 'AUTO', '2025-12-23 03:00:53'),
(20, 'MC-01', 'ON', 'FAULT', '2025-12-23 03:01:10'),
(21, 'MC-01', 'OFF', 'FAULT', '2025-12-23 03:01:16'),
(22, 'MC-01', 'ON', 'FAULT', '2025-12-23 03:01:20'),
(23, 'MC-01', 'ON', 'FAULT', '2025-12-23 03:05:49'),
(24, 'MC-01', 'ON', 'MANUAL', '2025-12-23 03:05:58'),
(25, 'MC-01', 'ON', 'AUTO', '2025-12-23 03:16:56'),
(26, 'MC-01', 'OFF', 'AUTO', '2025-12-23 03:18:54'),
(27, 'MC-01', 'OFF', 'FAULT', '2025-12-23 03:19:04'),
(28, 'MC-01', 'OFF', 'AUTO', '2025-12-23 03:19:12'),
(29, 'MC-01', 'OFF', 'FAULT', '2025-12-23 03:19:17'),
(30, 'MC-01', 'OFF', 'FAULT', '2025-12-23 03:19:18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produksi_curing`
--

CREATE TABLE `produksi_curing` (
  `id` bigint(20) NOT NULL,
  `machine_id` varchar(20) NOT NULL,
  `tanggal` date NOT NULL,
  `shift` varchar(10) NOT NULL,
  `jumlah_ban` int(11) NOT NULL,
  `waktu` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `produksi_curing`
--

INSERT INTO `produksi_curing` (`id`, `machine_id`, `tanggal`, `shift`, `jumlah_ban`, `waktu`) VALUES
(1, 'MC-01', '2025-12-23', 'SHIFT_1', 5, '2025-12-23 02:56:46'),
(2, 'MC-01', '2025-12-23', 'SHIFT_1', 20, '2025-12-23 03:01:00'),
(3, 'MC-01', '2025-12-23', 'SHIFT_1', 90, '2025-12-23 03:05:12');

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_quality_per_shift`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_quality_per_shift` (
`machine_id` varchar(20)
,`tanggal` date
,`shift` varchar(10)
,`total_fault` bigint(21)
,`quality_percentage` int(3)
);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `kondisi_mesin`
--
ALTER TABLE `kondisi_mesin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kondisi_mesin_write`
--
ALTER TABLE `kondisi_mesin_write`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `produksi_curing`
--
ALTER TABLE `produksi_curing`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `kondisi_mesin`
--
ALTER TABLE `kondisi_mesin`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `kondisi_mesin_write`
--
ALTER TABLE `kondisi_mesin_write`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `produksi_curing`
--
ALTER TABLE `produksi_curing`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_quality_per_shift`
--
DROP TABLE IF EXISTS `v_quality_per_shift`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_quality_per_shift`  AS SELECT `kondisi_mesin`.`machine_id` AS `machine_id`, `kondisi_mesin`.`tanggal` AS `tanggal`, `kondisi_mesin`.`shift` AS `shift`, count(0) AS `total_fault`, CASE WHEN count(0) <= 1 THEN 100 WHEN count(0) = 2 THEN 80 WHEN count(0) = 3 THEN 60 WHEN count(0) = 4 THEN 40 ELSE 0 END AS `quality_percentage` FROM `kondisi_mesin` WHERE `kondisi_mesin`.`mode_mesin` = 'FAULT' GROUP BY `kondisi_mesin`.`machine_id`, `kondisi_mesin`.`tanggal`, `kondisi_mesin`.`shift` ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
