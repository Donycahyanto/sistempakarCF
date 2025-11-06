-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 06 Nov 2025 pada 11.33
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spkkomputer`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
  `kode_pengetahuan` int(11) NOT NULL,
  `kode_kerusakan` int(11) NOT NULL,
  `kode_gejala` int(11) NOT NULL,
  `mb` double(11,1) NOT NULL,
  `md` double(11,1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
  `kode_gejala` int(11) NOT NULL,
  `nama_gejala` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
  `id_hasil` int(11) NOT NULL,
  `tanggal` varchar(50) NOT NULL DEFAULT '0',
  `kerusakan` text NOT NULL,
  `gejala` text NOT NULL,
  `hasil_id` int(11) NOT NULL,
  `hasil_nilai` varchar(16) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `hasil`
--

INSERT INTO `hasil` (`id_hasil`, `tanggal`, `kerusakan`, `gejala`, `hasil_id`, `hasil_nilai`) VALUES
(285, '2025-10-27 19:07:27', 'a:8:{i:10;s:6:\"0.6320\";i:8;s:6:\"0.6000\";i:11;s:6:\"0.6000\";i:4;s:6:\"0.2640\";i:5;s:6:\"0.1600\";i:7;s:6:\"0.0800\";i:9;s:6:\"0.0800\";i:12;s:6:\"0.0800\";}', 'a:3:{i:1;s:1:\"4\";i:2;s:1:\"1\";i:3;s:1:\"7\";}', 10, '0.6320'),
(286, '2025-10-31 22:43:11', 'a:4:{i:1;s:6:\"0.8200\";i:2;s:6:\"0.6000\";i:4;s:6:\"0.6000\";i:7;s:6:\"0.1883\";}', 'a:4:{i:1;s:1:\"1\";i:2;s:1:\"2\";i:3;s:1:\"5\";i:5;s:1:\"5\";}', 1, '0.8200');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kerusakan`
--

CREATE TABLE `kerusakan` (
  `kode_kerusakan` int(11) NOT NULL,
  `nama_kerusakan` varchar(50) NOT NULL,
  `det_kerusakan` varchar(500) NOT NULL,
  `srn_kerusakan` varchar(500) NOT NULL,
  `gambar` varchar(500) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
  `id` int(11) NOT NULL,
  `kondisi` varchar(64) NOT NULL,
  `ket` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
  `kode_post` int(11) NOT NULL,
  `nama_post` varchar(50) NOT NULL,
  `det_post` varchar(15000) NOT NULL,
  `srn_post` varchar(15000) NOT NULL,
  `gambar` varchar(500) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
-- Indexes for dumped tables
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
  MODIFY `kode_pengetahuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT untuk tabel `gejala`
--
ALTER TABLE `gejala`
  MODIFY `kode_gejala` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT untuk tabel `hasil`
--
ALTER TABLE `hasil`
  MODIFY `id_hasil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=287;

--
-- AUTO_INCREMENT untuk tabel `kerusakan`
--
ALTER TABLE `kerusakan`
  MODIFY `kode_kerusakan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `kondisi`
--
ALTER TABLE `kondisi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `post`
--
ALTER TABLE `post`
  MODIFY `kode_post` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
