-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 31 Okt 2025 pada 15.53
-- Versi server: 8.4.3
-- Versi PHP: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `spkkomputer`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `username` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nama_lengkap` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`username`, `password`, `nama_lengkap`) VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrator'),
('Dony', '5007007bf0d84200644731d5d3bf9aff', 'Dony Cahyanto');

-- --------------------------------------------------------

--
-- Struktur dari tabel `basis_pengetahuan`
--

CREATE TABLE `basis_pengetahuan` (
  `kode_pengetahuan` int NOT NULL,
  `kode_kerusakan` int NOT NULL,
  `kode_gejala` int NOT NULL,
  `mb` double(11,1) NOT NULL,
  `md` double(11,1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `basis_pengetahuan`
--

INSERT INTO `basis_pengetahuan` (`kode_pengetahuan`, `kode_kerusakan`, `kode_gejala`, `mb`, `md`) VALUES
(2, 1, 1, 0.8, 0.3),
(3, 1, 25, 0.9, 0.1),
(4, 1, 2, 0.9, 0.1),
(5, 1, 32, 0.7, 0.3),
(6, 10, 24, 0.9, 0.1),
(7, 10, 10, 0.8, 0.2),
(8, 10, 40, 0.9, 0.2),
(9, 10, 33, 0.9, 0.2),
(10, 2, 1, 0.8, 0.2),
(11, 2, 17, 0.9, 0.1),
(12, 2, 29, 0.7, 0.2),
(13, 2, 26, 0.7, 0.3),
(14, 2, 18, 0.8, 0.2),
(15, 2, 4, 0.7, 0.3),
(16, 2, 7, 0.8, 0.2),
(17, 2, 31, 0.7, 0.3),
(18, 2, 32, 0.8, 0.3),
(19, 3, 18, 0.7, 0.3),
(20, 3, 30, 0.7, 0.3),
(21, 3, 13, 0.7, 0.2),
(22, 3, 8, 0.9, 0.1),
(23, 3, 6, 1.0, 0.0),
(24, 3, 20, 0.8, 0.2),
(25, 4, 1, 0.8, 0.2),
(26, 4, 19, 0.9, 0.2),
(27, 4, 9, 0.8, 0.2),
(28, 4, 12, 0.7, 0.3),
(29, 4, 18, 0.7, 0.3),
(30, 4, 10, 0.7, 0.2),
(31, 4, 32, 0.7, 0.3),
(32, 5, 18, 0.8, 0.3),
(33, 5, 30, 0.8, 0.2),
(34, 5, 12, 0.8, 0.2),
(35, 5, 13, 0.7, 0.3),
(36, 5, 7, 0.8, 0.2),
(37, 5, 11, 0.8, 0.1),
(38, 6, 28, 0.9, 0.2),
(39, 6, 16, 0.7, 0.4),
(40, 6, 14, 0.9, 0.2),
(41, 6, 27, 0.9, 0.2),
(42, 6, 11, 0.6, 0.4),
(43, 7, 5, 0.9, 0.3),
(44, 7, 3, 0.9, 0.1),
(45, 7, 4, 0.8, 0.3),
(46, 7, 1, 0.7, 0.3),
(47, 8, 22, 0.9, 0.1),
(48, 8, 28, 0.8, 0.2),
(49, 8, 14, 0.9, 0.2),
(50, 8, 27, 0.6, 0.2),
(51, 9, 23, 0.9, 0.1),
(52, 9, 10, 0.8, 0.4),
(53, 9, 20, 0.7, 0.4),
(54, 9, 31, 0.7, 0.3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `gejala`
--

CREATE TABLE `gejala` (
  `kode_gejala` int NOT NULL,
  `nama_gejala` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `gejala`
--

INSERT INTO `gejala` (`kode_gejala`, `nama_gejala`) VALUES
(1, 'CPU hidup tapi tidak ada gambar yang tertampil di monitor'),
(2, 'Terdapat garis horizontal atau vertikal di tengah monitor'),
(3, 'Tidak ada tampilan awal BIOS'),
(4, 'Muncul pesan error pada BIOS (isi pesan selalu berbeda pada tiap kasus)'),
(5, 'Alarm BIOS berbunyi'),
(33, 'Double klik pada mouse'),
(6, 'Terdengar suara aneh pada Harddisk'),
(7, 'Sering terjadi hang atau crash saat menjalankan aplikasi'),
(8, 'Selalu scandisk ketika booting'),
(9, 'Muncul pesan error saat menjalankan aplikasi grafis'),
(10, 'Device driver informasi tidak terdeteksi dalam device manager'),
(11, 'Tiba tiba OS melakukan restart otomatis'),
(12, 'Keluarnya bluescreen pada OS windows (isi pesan selalu berbeda pada tiap kasus)'),
(13, 'Muncul pesan error saat pertama OS di load dari harddisk'),
(14, 'Tidak ada tanda tanda dari sebagian atau seluruh perangkat menyala'),
(16, 'Sering tiba tiba mati tanpa sebab'),
(17, 'Muncul pesan pada windows, bahwa windows kekurangan memori'),
(18, 'Aplikasi berjalan dengan lambat'),
(19, 'Kinerja grafis terasa sangat berat'),
(20, 'Device tidak terdeteksi dalam BIOS'),
(21, 'Informasi deteksi yang salah dalam BIOS'),
(22, 'Hanya sebagian perangkat yang bekerja'),
(23, 'Sebagian atau seluruh karakter inputan mati'),
(24, 'Pointer mouse tidak merespon gerakan mouse'),
(25, 'Tampak blok hitam, dan gambar tidak simetris atau acak'),
(26, 'Keluar bunyi beep panjang pada saat dinyalakan'),
(27, 'Tidak ada indikasi masuk power'),
(28, 'Mati total'),
(29, 'Keluar beep berulang ulang kali'),
(30, 'Belum sampai windows sudah restart lagi'),
(31, 'Respon lambat pada inputan'),
(32, 'Lampu indikator monitor berwarna merah'),
(40, 'Lampu Indikator mouse tidak menyala');

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil`
--

CREATE TABLE `hasil` (
  `id_hasil` int NOT NULL,
  `tanggal` varchar(50) NOT NULL DEFAULT '0',
  `kerusakan` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `gejala` text NOT NULL,
  `hasil_id` int NOT NULL,
  `hasil_nilai` varchar(16) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `hasil`
--

INSERT INTO `hasil` (`id_hasil`, `tanggal`, `kerusakan`, `gejala`, `hasil_id`, `hasil_nilai`) VALUES
(201, '2018-02-20 13:25:09', 'a:10:{i:10;s:6:\"0.6480\";i:11;s:6:\"0.6000\";i:8;s:6:\"0.6000\";i:13;s:6:\"0.4800\";i:9;s:6:\"0.4720\";i:4;s:6:\"0.2960\";i:5;s:6:\"0.2400\";i:1;s:6:\"0.1200\";i:12;s:6:\"0.1200\";i:7;s:6:\"0.1200\";}', 'a:5:{i:1;s:1:\"3\";i:2;s:1:\"1\";i:3;s:1:\"5\";i:5;s:1:\"4\";i:7;s:1:\"2\";}', 10, '0.6480'),
(202, '2018-02-20 15:51:20', 'a:9:{i:3;s:6:\"1.0000\";i:2;s:6:\"0.8240\";i:5;s:6:\"0.2400\";i:10;s:6:\"0.1200\";i:12;s:6:\"0.1200\";i:9;s:6:\"0.1200\";i:7;s:6:\"0.1200\";i:1;s:6:\"0.1200\";i:4;s:6:\"0.1200\";}', 'a:4:{i:1;s:1:\"3\";i:7;s:1:\"6\";i:14;s:1:\"1\";i:15;s:1:\"2\";}', 3, '1.0000'),
(204, '2018-02-20 18:19:53', 'a:2:{i:2;s:6:\"0.6000\";i:13;s:6:\"0.4800\";}', 'a:4:{i:1;s:1:\"5\";i:3;s:1:\"2\";i:5;s:1:\"5\";i:7;s:1:\"2\";}', 2, '0.6000'),
(205, '2018-02-20 18:20:05', 'a:2:{i:9;s:6:\"0.8000\";i:7;s:6:\"0.6000\";}', 'a:2:{i:38;s:1:\"3\";i:40;s:1:\"2\";}', 9, '0.8000'),
(206, '2018-02-20 20:18:58', 'a:4:{i:10;s:6:\"1.0000\";i:9;s:6:\"0.6000\";i:7;s:6:\"0.6000\";i:11;s:6:\"0.4000\";}', 'a:4:{i:38;s:1:\"3\";i:40;s:1:\"3\";i:41;s:1:\"1\";i:42;s:1:\"4\";}', 10, '1.0000'),
(207, '2018-02-20 20:19:30', 'a:2:{i:5;s:6:\"0.8000\";i:1;s:6:\"0.4800\";}', 'a:2:{i:12;s:1:\"3\";i:16;s:1:\"1\";}', 5, '0.8000'),
(209, '2018-02-21 05:43:56', 'a:7:{i:2;s:6:\"0.6832\";i:5;s:6:\"0.2400\";i:12;s:6:\"0.1200\";i:10;s:6:\"0.1200\";i:7;s:6:\"0.1200\";i:1;s:6:\"0.1200\";i:4;s:6:\"0.1200\";}', 'a:3:{i:1;s:1:\"3\";i:3;s:1:\"2\";i:5;s:1:\"5\";}', 2, '0.6832'),
(210, '2018-02-21 09:13:13', 'a:0:{}', 'a:3:{i:1;s:1:\"8\";i:3;s:1:\"9\";i:6;s:1:\"7\";}', 0, ''),
(211, '2018-02-21 09:35:01', 'a:1:{i:12;s:6:\"0.4800\";}', 'a:1:{i:35;s:1:\"3\";}', 12, '0.4800'),
(212, '2018-02-21 10:18:30', 'a:8:{i:5;s:6:\"0.1600\";i:10;s:6:\"0.0800\";i:12;s:6:\"0.0800\";i:9;s:6:\"0.0800\";i:7;s:6:\"0.0800\";i:4;s:6:\"0.0800\";i:1;s:6:\"0.0800\";i:2;s:6:\"0.0800\";}', 'a:1:{i:1;s:1:\"4\";}', 5, '0.1600'),
(213, '2018-02-21 11:48:56', 'a:4:{i:4;s:6:\"0.2704\";i:11;s:6:\"0.2400\";i:8;s:6:\"0.2400\";i:10;s:6:\"0.2000\";}', 'a:3:{i:1;s:1:\"5\";i:2;s:1:\"4\";i:4;s:1:\"4\";}', 4, '0.2704'),
(214, '2018-02-21 11:50:21', 'a:9:{i:13;s:6:\"0.4800\";i:2;s:6:\"0.3744\";i:5;s:6:\"0.1600\";i:12;s:6:\"0.0800\";i:10;s:6:\"0.0800\";i:7;s:6:\"0.0800\";i:4;s:6:\"0.0800\";i:1;s:6:\"0.0800\";i:9;s:6:\"0.0800\";}', 'a:3:{i:1;s:1:\"4\";i:3;s:1:\"4\";i:7;s:1:\"2\";}', 13, '0.4800'),
(215, '2018-02-21 11:52:04', 'a:3:{i:7;s:6:\"0.6400\";i:5;s:6:\"0.6400\";i:2;s:6:\"0.6000\";}', 'a:4:{i:7;s:1:\"8\";i:15;s:1:\"3\";i:16;s:1:\"2\";i:39;s:1:\"2\";}', 7, '0.6400'),
(216, '2018-02-21 11:52:21', 'a:2:{i:2;s:6:\"1.0000\";i:11;s:6:\"0.8560\";}', 'a:4:{i:5;s:1:\"5\";i:15;s:1:\"1\";i:36;s:1:\"2\";i:42;s:1:\"3\";}', 2, '1.0000'),
(217, '2018-02-21 12:54:09', 'a:3:{i:9;s:6:\"0.8000\";i:1;s:6:\"0.6400\";i:5;s:6:\"0.3200\";}', 'a:3:{i:5;s:1:\"2\";i:12;s:1:\"2\";i:16;s:1:\"4\";}', 9, '0.8000'),
(218, '2018-02-21 12:54:43', 'a:3:{i:9;s:6:\"0.8000\";i:1;s:6:\"0.6400\";i:5;s:6:\"0.3200\";}', 'a:3:{i:5;s:1:\"2\";i:12;s:1:\"2\";i:16;s:1:\"4\";}', 9, '0.8000'),
(221, '2018-02-22 18:47:41', 'a:9:{i:2;s:6:\"0.8320\";i:10;s:6:\"0.4624\";i:11;s:6:\"0.3600\";i:8;s:6:\"0.3600\";i:5;s:6:\"0.3200\";i:12;s:6:\"0.1600\";i:7;s:6:\"0.1600\";i:9;s:6:\"0.1600\";i:1;s:6:\"0.1600\";}', 'a:4:{i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"1\";i:4;s:1:\"7\";}', 2, '0.8320'),
(222, '2018-02-27 14:12:19', 'a:7:{i:9;s:6:\"0.6640\";i:5;s:6:\"0.3200\";i:12;s:6:\"0.1600\";i:2;s:6:\"0.1600\";i:7;s:6:\"0.1600\";i:1;s:6:\"0.1600\";i:4;s:6:\"0.0400\";}', 'a:3:{i:1;s:1:\"2\";i:2;s:1:\"7\";i:5;s:1:\"3\";}', 9, '0.6640'),
(223, '2018-03-01 14:53:58', 'a:10:{i:2;s:6:\"0.5632\";i:10;s:6:\"0.3616\";i:5;s:6:\"0.3200\";i:11;s:6:\"0.2400\";i:8;s:6:\"0.2400\";i:4;s:6:\"0.2272\";i:12;s:6:\"0.1600\";i:7;s:6:\"0.1600\";i:1;s:6:\"0.1600\";i:9;s:6:\"0.1600\";}', 'a:3:{i:1;s:1:\"2\";i:2;s:1:\"4\";i:3;s:1:\"3\";}', 2, '0.5632'),
(225, '2018-03-07 05:26:33', 'a:0:{}', 'a:0:{}', 0, ''),
(226, '2018-03-07 05:26:53', 'a:8:{i:5;s:6:\"0.2400\";i:10;s:6:\"0.1200\";i:12;s:6:\"0.1200\";i:9;s:6:\"0.1200\";i:7;s:6:\"0.1200\";i:4;s:6:\"0.1200\";i:1;s:6:\"0.1200\";i:2;s:6:\"0.1200\";}', 'a:1:{i:1;s:1:\"3\";}', 5, '0.2400'),
(227, '2018-03-07 05:43:07', 'a:10:{i:3;s:6:\"1.0000\";i:13;s:6:\"0.3600\";i:5;s:6:\"0.3200\";i:12;s:6:\"0.1600\";i:2;s:6:\"0.1600\";i:10;s:6:\"0.1600\";i:9;s:6:\"0.1600\";i:1;s:6:\"0.1600\";i:7;s:6:\"0.1600\";i:4;s:6:\"0.1600\";}', 'a:3:{i:1;s:1:\"2\";i:7;s:1:\"3\";i:25;s:1:\"1\";}', 3, '1.0000'),
(232, '2018-03-07 06:23:47', 'a:8:{i:5;s:6:\"0.3200\";i:10;s:6:\"0.1600\";i:12;s:6:\"0.1600\";i:9;s:6:\"0.1600\";i:7;s:6:\"0.1600\";i:2;s:6:\"0.1600\";i:1;s:6:\"0.1600\";i:4;s:6:\"0.0400\";}', 'a:2:{i:1;s:1:\"2\";i:4;s:1:\"5\";}', 5, '0.3200'),
(233, '2018-03-07 06:24:13', 'a:8:{i:5;s:6:\"0.3200\";i:10;s:6:\"0.1600\";i:12;s:6:\"0.1600\";i:9;s:6:\"0.1600\";i:7;s:6:\"0.1600\";i:2;s:6:\"0.1600\";i:1;s:6:\"0.1600\";i:4;s:6:\"0.0400\";}', 'a:2:{i:1;s:1:\"2\";i:4;s:1:\"5\";}', 5, '0.3200'),
(275, '2018-03-13 12:57:51', 'a:7:{i:5;s:6:\"0.4000\";i:10;s:6:\"0.2000\";i:12;s:6:\"0.2000\";i:9;s:6:\"0.2000\";i:7;s:6:\"0.2000\";i:4;s:6:\"0.2000\";i:2;s:6:\"0.2000\";}', 'a:1:{i:1;s:1:\"1\";}', 5, '0.4000'),
(276, '2018-03-13 13:10:17', 'a:0:{}', 'a:1:{i:1;s:1:\"5\";}', 0, ''),
(277, '2018-03-13 13:10:32', 'a:7:{i:5;s:6:\"0.1600\";i:10;s:6:\"0.0800\";i:12;s:6:\"0.0800\";i:9;s:6:\"0.0800\";i:7;s:6:\"0.0800\";i:4;s:6:\"0.0800\";i:2;s:6:\"0.0800\";}', 'a:1:{i:1;s:1:\"4\";}', 5, '0.1600'),
(278, '2018-03-13 13:21:49', 'a:0:{}', 'a:1:{i:1;s:1:\"5\";}', 0, ''),
(253, '2018-03-07 06:41:06', 'a:11:{i:10;s:6:\"0.6640\";i:11;s:6:\"0.6000\";i:8;s:6:\"0.6000\";i:4;s:6:\"0.3280\";i:5;s:6:\"0.3200\";i:3;s:6:\"0.3200\";i:12;s:6:\"0.1600\";i:7;s:6:\"0.1600\";i:2;s:6:\"0.1600\";i:1;s:6:\"0.1600\";i:9;s:6:\"0.1600\";}', 'a:3:{i:1;s:1:\"2\";i:2;s:1:\"1\";i:19;s:1:\"2\";}', 10, '0.6640'),
(254, '2018-03-07 06:41:33', 'a:8:{i:2;s:6:\"0.6832\";i:5;s:6:\"0.2400\";i:10;s:6:\"0.1200\";i:12;s:6:\"0.1200\";i:9;s:6:\"0.1200\";i:7;s:6:\"0.1200\";i:1;s:6:\"0.1200\";i:4;s:6:\"0.1200\";}', 'a:2:{i:1;s:1:\"3\";i:3;s:1:\"2\";}', 2, '0.6832'),
(255, '2018-03-07 06:45:39', 'a:0:{}', 'a:0:{}', 0, ''),
(256, '2018-03-07 06:46:37', 'a:2:{i:13;s:6:\"0.5914\";i:4;s:6:\"0.2400\";}', 'a:4:{i:20;s:1:\"4\";i:27;s:1:\"3\";i:33;s:1:\"2\";i:34;s:1:\"3\";}', 13, '0.5914'),
(257, '2018-03-09 00:50:24', 'a:0:{}', 'a:0:{}', 0, ''),
(258, '2018-03-09 01:08:28', 'a:9:{i:10;s:6:\"0.5632\";i:11;s:6:\"0.4800\";i:8;s:6:\"0.4800\";i:5;s:6:\"0.3200\";i:4;s:6:\"0.2944\";i:12;s:6:\"0.1600\";i:9;s:6:\"0.1600\";i:2;s:6:\"0.1600\";i:7;s:6:\"0.1600\";}', 'a:2:{i:1;s:1:\"2\";i:2;s:1:\"2\";}', 10, '0.5632'),
(259, '2018-03-09 01:26:39', 'a:1:{i:12;s:6:\"0.4800\";}', 'a:2:{i:16;s:1:\"8\";i:17;s:1:\"2\";}', 12, '0.4800'),
(260, '2018-03-09 01:27:45', 'a:1:{i:12;s:6:\"0.4800\";}', 'a:2:{i:16;s:1:\"8\";i:17;s:1:\"2\";}', 12, '0.4800'),
(261, '2018-03-09 04:51:04', 'a:10:{i:13;s:6:\"0.8960\";i:8;s:6:\"0.8128\";i:10;s:6:\"0.5840\";i:2;s:6:\"0.5840\";i:11;s:6:\"0.4800\";i:5;s:6:\"0.4000\";i:4;s:6:\"0.2080\";i:12;s:6:\"0.2000\";i:9;s:6:\"0.2000\";i:7;s:6:\"0.2000\";}', 'a:7:{i:1;s:1:\"1\";i:2;s:1:\"2\";i:3;s:1:\"3\";i:4;s:1:\"5\";i:7;s:1:\"2\";i:8;s:1:\"2\";i:18;s:1:\"2\";}', 13, '0.8960'),
(262, '2018-03-10 00:04:05', 'a:3:{i:9;s:6:\"0.4800\";i:13;s:6:\"0.2400\";i:4;s:6:\"0.2400\";}', 'a:4:{i:4;s:1:\"4\";i:5;s:1:\"3\";i:7;s:1:\"4\";i:9;s:1:\"5\";}', 9, '0.4800'),
(263, '2018-03-10 03:14:39', 'a:4:{i:1;s:6:\"0.6400\";i:12;s:6:\"0.3600\";i:6;s:6:\"0.2400\";i:9;s:6:\"0.2400\";}', 'a:3:{i:9;s:1:\"4\";i:12;s:1:\"2\";i:17;s:1:\"3\";}', 1, '0.6400'),
(264, '2018-03-10 03:19:27', 'a:3:{i:11;s:6:\"1.0000\";i:10;s:6:\"0.4000\";i:9;s:6:\"0.4000\";}', 'a:3:{i:5;s:1:\"4\";i:37;s:1:\"1\";i:41;s:1:\"4\";}', 11, '1.0000'),
(265, '2018-03-10 03:21:03', 'a:4:{i:8;s:6:\"0.9293\";i:11;s:6:\"0.4800\";i:10;s:6:\"0.4800\";i:4;s:6:\"0.1600\";}', 'a:4:{i:2;s:1:\"2\";i:18;s:1:\"4\";i:29;s:1:\"2\";i:34;s:1:\"5\";}', 8, '0.9293'),
(266, '2018-03-10 03:22:27', 'a:3:{i:6;s:6:\"1.0000\";i:7;s:6:\"0.6000\";i:1;s:6:\"0.3200\";}', 'a:4:{i:12;s:1:\"4\";i:28;s:1:\"5\";i:30;s:1:\"1\";i:32;s:1:\"3\";}', 6, '1.0000'),
(267, '2018-03-10 17:35:54', 'a:4:{i:9;s:6:\"0.8000\";i:6;s:6:\"0.4800\";i:13;s:6:\"0.2400\";i:4;s:6:\"0.1600\";}', 'a:3:{i:5;s:1:\"2\";i:7;s:1:\"4\";i:10;s:1:\"2\";}', 9, '0.8000'),
(273, '2018-03-11 01:13:51', 'a:3:{i:6;s:6:\"0.8000\";i:5;s:6:\"0.6000\";i:13;s:6:\"0.0800\";}', 'a:3:{i:28;s:1:\"3\";i:30;s:1:\"2\";i:33;s:1:\"4\";}', 6, '0.8000'),
(279, '2018-03-15 05:15:44', 'a:6:{i:5;s:6:\"0.3200\";i:12;s:6:\"0.1600\";i:10;s:6:\"0.1600\";i:7;s:6:\"0.1600\";i:2;s:6:\"0.1600\";i:9;s:6:\"0.0400\";}', 'a:3:{i:1;s:1:\"2\";i:4;s:1:\"8\";i:9;s:1:\"5\";}', 5, '0.3200'),
(280, '2018-03-15 13:09:09', 'a:7:{i:5;s:6:\"0.4000\";i:10;s:6:\"0.2000\";i:12;s:6:\"0.2000\";i:9;s:6:\"0.2000\";i:7;s:6:\"0.2000\";i:4;s:6:\"0.2000\";i:2;s:6:\"0.2000\";}', 'a:1:{i:1;s:1:\"1\";}', 5, '0.4000'),
(281, '2018-03-15 13:10:02', 'a:7:{i:5;s:6:\"0.4000\";i:10;s:6:\"0.2000\";i:12;s:6:\"0.2000\";i:9;s:6:\"0.2000\";i:7;s:6:\"0.2000\";i:4;s:6:\"0.2000\";i:2;s:6:\"0.2000\";}', 'a:1:{i:1;s:1:\"1\";}', 5, '0.4000'),
(282, '2018-03-15 13:10:36', 'a:7:{i:5;s:6:\"0.4000\";i:10;s:6:\"0.2000\";i:12;s:6:\"0.2000\";i:9;s:6:\"0.2000\";i:7;s:6:\"0.2000\";i:4;s:6:\"0.2000\";i:2;s:6:\"0.2000\";}', 'a:1:{i:1;s:1:\"1\";}', 5, '0.4000'),
(283, '2018-03-15 13:12:00', 'a:8:{i:10;s:6:\"0.6800\";i:4;s:6:\"0.5840\";i:5;s:6:\"0.4000\";i:6;s:6:\"0.3200\";i:12;s:6:\"0.2000\";i:2;s:6:\"0.2000\";i:9;s:6:\"0.2000\";i:7;s:6:\"0.2000\";}', 'a:5:{i:1;s:1:\"1\";i:6;s:1:\"4\";i:11;s:1:\"3\";i:14;s:1:\"9\";i:22;s:1:\"3\";}', 10, '0.6800'),
(284, '2018-03-15 13:30:20', 'a:3:{i:11;s:6:\"0.6000\";i:12;s:6:\"0.4800\";i:13;s:6:\"0.0800\";}', 'a:5:{i:30;s:1:\"9\";i:32;s:1:\"5\";i:33;s:1:\"4\";i:35;s:1:\"3\";i:42;s:1:\"3\";}', 11, '0.6000'),
(285, '2025-10-27 19:07:27', 'a:8:{i:10;s:6:\"0.6320\";i:8;s:6:\"0.6000\";i:11;s:6:\"0.6000\";i:4;s:6:\"0.2640\";i:5;s:6:\"0.1600\";i:7;s:6:\"0.0800\";i:9;s:6:\"0.0800\";i:12;s:6:\"0.0800\";}', 'a:3:{i:1;s:1:\"4\";i:2;s:1:\"1\";i:3;s:1:\"7\";}', 10, '0.6320'),
(286, '2025-10-31 22:43:11', 'a:4:{i:1;s:6:\"0.8200\";i:2;s:6:\"0.6000\";i:4;s:6:\"0.6000\";i:7;s:6:\"0.1883\";}', 'a:4:{i:1;s:1:\"1\";i:2;s:1:\"2\";i:3;s:1:\"5\";i:5;s:1:\"5\";}', 1, '0.8200');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kerusakan`
--

CREATE TABLE `kerusakan` (
  `kode_kerusakan` int NOT NULL,
  `nama_kerusakan` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `det_kerusakan` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `srn_kerusakan` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `gambar` varchar(500) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `kerusakan`
--

INSERT INTO `kerusakan` (`kode_kerusakan`, `nama_kerusakan`, `det_kerusakan`, `srn_kerusakan`, `gambar`) VALUES
(1, 'Monitor Rusak', 'Monitor tidak berfungsi dengan baik atau mati total', 'Cek kabel Power Monitor dan Kabel VGA Monitor apabila tidak ada perubahan, segera lapor dan lakukan permintaan barang', '01 Monitor Rusak.jpg'),
(2, 'Memori Rusak', 'Memori card/RAM tidak berfungsi dengan baik', 'Coba bersihkan pin kuningan pada RAM dengan penghapus atau kain halus, Bersihkan slot ram pada motherboard dan coba ganti slot pada motherboard, kalau tetap tidak bisa, segera lapor dan lakukan permintaan barang', '02 Memori Rusak.jpg'),
(3, 'Harddisk Rusak', 'Harddisk tidak berfungsi dengan baik atau bad sector', 'Coba lakukan checkdisk/defragment disk, backup data, coba lakukan install ulang OS dan kalau masih tetap tidak berfungsi dengan baik, segera lapor dan lakukan permintaan barang', '03 Harddisk Rusak.jpg'),
(4, 'VGA Rusak', 'Ketika graphic card tidak berfungsi atau mati', 'Bersihkan pin pada VGA dan slot VGA card serta coba memakai display onboard motherboard, apabila pada onboard bisa menyala maka segera lakukan permintaan barang', '04 VGA Rusak.jpg'),
(5, 'OS Bermasalah', 'Windows tidak berjalan lancar atau Bluescreen', 'Cek sistem bios dan lakukan perbaikan sistem, jika masih belum bisa segera backup data dan lakukan instal ulang OS', '05 OS Bermasalah.jpg'),
(6, 'Power Supply Rusak', 'PSU tidak ada daya untuk menyalakan komputer', 'Coba jumper PSU apabila tidak ada daya segera lakukan permintaan PSU dan minta dengan daya yang sedikit lebih besar dari sebelumnya', '06 Power Supply Rusak.jpg'),
(7, 'Processor Rusak', 'Processor sudah tidak berfungsi dengan baik', 'Segera lakukan permintaan barang, kalau bisa dengan clock yang lebih tinggi dan untuk seterusnya lebih sering lakukan penambahan thermal paste agar processor lebih tahan lama', '07 Processor Rusak.jpg'),
(8, 'Motherboard Rusak', 'Slot dan port motherboard banyak yang tidak berfungsi', 'Periksa dengan pasti kondisi semua perangkat pada PC, coba dengan perangkat yang menyala dari PC lain, apabila tidak ada tanda perangkat menyala maka segera lakukan permintaan barang', '08 Motherboard Rusak.jpg'),
(9, 'Keyboard Rusak', 'Ketika keyboard tidak terdeteksi atau karakter inputan tidak bisa', 'Periksa slot USB pada motherboard dan coba tukar keyboard dengan yang menyala dari PC lain, apabila tidak ada tanda keyboard berfungsi maka lakukan permintaan barang', '09 Keyboard Rusak.jpg'),
(10, 'Mouse Rusak', 'Ketika mouse tidak terdeteksi dan pointer mouse tidak berjalan dengan baik', 'Periksa slot USB pada motherboard dan coba tukar mouse dengan yang menyala dari PC lain, apabila tidak ada tanda mouse berfungsi. Segera lakukan permintaan barang', '10 Mouse Rusak.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kondisi`
--

CREATE TABLE `kondisi` (
  `id` int NOT NULL,
  `kondisi` varchar(64) NOT NULL,
  `ket` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `kondisi`
--

INSERT INTO `kondisi` (`id`, `kondisi`, `ket`) VALUES
(1, 'Pasti ya', ''),
(2, 'Hampir pasti ya', ''),
(3, 'Kemungkinan besar ya', ''),
(4, 'Mungkin ya', ''),
(5, 'Tidak tahu', ''),
(6, 'Mungkin tidak', ''),
(7, 'Kemungkinan besar tidak', ''),
(8, 'Hampir pasti tidak', ''),
(9, 'Pasti tidak', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `post`
--

CREATE TABLE `post` (
  `kode_post` int NOT NULL,
  `nama_post` varchar(50) NOT NULL,
  `det_post` varchar(15000) NOT NULL,
  `srn_post` varchar(15000) NOT NULL,
  `gambar` varchar(500) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `post`
--

INSERT INTO `post` (`kode_post`, `nama_post`, `det_post`, `srn_post`, `gambar`) VALUES
(14, 'Harddisk Rusak', '<p>Harddisk tidak berfungsi dengan baik atau bad sector</p>\r\n', '<p>Coba lakukan checkdisk atau defragment disk, backup data, coba lakukan install ulang OS dan kalau masih tidak berfungsi segera lapor dan lakukan permintaan barang</p>\r\n', '1.png'),
(15, 'Keyboard Rusak', '<p>Ketika keyboard tidak terdeteksi atau karakter inputan tidak bisa</p>\r\n', '<p>Periksa slot USB pada motherboard dan coba tukar keyboard dengan yang menyala dari PC lain, apabila tidak ada tanda keyboard berfungsi maka lakukan permintaan barang</p>\r\n', '2.png'),
(16, 'Memori Rusak', '<p>Memory&nbsp;card (RAM) tidak berfungsi dengan baik</p>\r\n', '<p>Coba bersihkan pin kuningan pada RAM dengan penghapus atau kain halus, bersihkan slot RAM pada motherboard dan coba ganti slot pada motherboard, cek pada perangkat lain atau&nbsp;motherboard lain ,&nbsp;kalau tetap tidak bisa segera lapor dan lakukan permintaan barang</p>\r\n', '3.png'),
(17, 'Monitor Rusak', '<p>Monitor tidak berfungsi dengan baik atau mati total</p>\r\n', '<p>Cek kabel power monitor dan kabel VGA monitor, apabila tidak ada perubahan segera lapor dan lakukan permintaan barang</p>\r\n', '4.png'),
(18, 'Motherboard Rusak', '<p>Slot dan port motherboard banyak yang tidak berfungsi</p>\r\n', '<p>Periksa dengan pasti kondisi semua perangkat pada PC, coba dengan perangkat yang menyala dari PC lain, apabila tidak ada tanda perangkat menyala maka segera lakukan permintaan barang</p>\r\n', '5.png'),
(19, 'Mouse Rusak', '<p>Ketika mouse tidak terdeteksi dan pointer mouse tidak berjalan dengan baik</p>\r\n', '<p>Periksa slot USB pada motherboard dan coba tukar mouse dengan yang menyala dari PC lain, apabila tidak ada tanda mouse berfungsi segera lakukan permintaan barang</p>\r\n', '6.png'),
(20, 'OS Bermasalah', '<p>Windows tidak berjalan lancar atau bluescreen</p>\r\n', '<p>Cek semua driver sistem, cek sistem BIOS dan lakukan perbaikan sistem, jika masih belum bisa segera backup data dan lakukan instalasi ulang OS</p>\r\n', '7.png'),
(21, 'Processor Rusak', '<p>Processor sudah tidak berfungsi dengan baik</p>\r\n', '<p>Segera lakukan permintaan barang, kalau bisa dengan clock yang lebih tinggi dan untuk seterusnya lebih sering lakukan penambahan thermal paste agar processor lebih tahan lama</p>\r\n', '8.png'),
(22, 'Power Supply Rusak', '<p>PSU tidak ada daya untuk menyalakan komputer</p>\r\n', '<p>Coba jumper PSU apabila tidak ada daya segera lakukan permintaan PSU dan minta dengan daya yang sedikit lebih besar dari sebelumnya</p>\r\n', '9.png'),
(23, 'VGA Rusak', '<p>Ketika graphic card tidak berfungsi atau mati&nbsp;</p>\r\n', '<p>Bersihkan pin pada VGA card dan slot VGA card serta coba memakai display onboard motherboard, apabila pada onboard bisa menyala maka segera lakukan permintan barang</p>\r\n', '10.png');

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`username`);

--
-- Indeks untuk tabel `basis_pengetahuan`
--
ALTER TABLE `basis_pengetahuan`
  ADD PRIMARY KEY (`kode_pengetahuan`);

--
-- Indeks untuk tabel `gejala`
--
ALTER TABLE `gejala`
  ADD PRIMARY KEY (`kode_gejala`);

--
-- Indeks untuk tabel `hasil`
--
ALTER TABLE `hasil`
  ADD PRIMARY KEY (`id_hasil`);

--
-- Indeks untuk tabel `kerusakan`
--
ALTER TABLE `kerusakan`
  ADD PRIMARY KEY (`kode_kerusakan`);

--
-- Indeks untuk tabel `kondisi`
--
ALTER TABLE `kondisi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`kode_post`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `basis_pengetahuan`
--
ALTER TABLE `basis_pengetahuan`
  MODIFY `kode_pengetahuan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT untuk tabel `gejala`
--
ALTER TABLE `gejala`
  MODIFY `kode_gejala` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT untuk tabel `hasil`
--
ALTER TABLE `hasil`
  MODIFY `id_hasil` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=287;

--
-- AUTO_INCREMENT untuk tabel `kerusakan`
--
ALTER TABLE `kerusakan`
  MODIFY `kode_kerusakan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `kondisi`
--
ALTER TABLE `kondisi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `post`
--
ALTER TABLE `post`
  MODIFY `kode_post` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
