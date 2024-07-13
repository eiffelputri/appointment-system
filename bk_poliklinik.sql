-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Waktu pembuatan: 03 Jan 2024 pada 07.09
-- Versi server: 5.7.39
-- Versi PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bk_poliklinik`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `daftar_poli`
--

CREATE TABLE `daftar_poli` (
  `id` int(11) NOT NULL,
  `id_pasien` int(11) NOT NULL,
  `id_jadwal` int(11) NOT NULL,
  `keluhan` text,
  `no_antrian` int(10) UNSIGNED DEFAULT NULL,
  `status_periksa` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `daftar_poli`
--

INSERT INTO `daftar_poli` (`id`, `id_pasien`, `id_jadwal`, `keluhan`, `no_antrian`, `status_periksa`) VALUES
(1, 8, 1, 'Coba', 1, 1),
(2, 8, 1, 'Coba', 2, 0),
(3, 8, 2, 'Contoh', 1, 0),
(4, 7, 2, 'Saya ada gejala pusing dan mutah selama 2 hari', 2, 0),
(5, 8, 2, 'Saya ada keluhan sakit mata', 3, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_periksa`
--

CREATE TABLE `detail_periksa` (
  `id` int(11) NOT NULL,
  `id_periksa` int(11) NOT NULL,
  `id_obat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `detail_periksa`
--

INSERT INTO `detail_periksa` (`id`, `id_periksa`, `id_obat`) VALUES
(3, 5, 1),
(7, 6, 26),
(8, 6, 27),
(9, 7, 47),
(10, 7, 50),
(13, 8, 1),
(14, 8, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokter`
--

CREATE TABLE `dokter` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `no_hp` varchar(50) DEFAULT NULL,
  `id_poli` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `dokter`
--

INSERT INTO `dokter` (`id`, `nama`, `alamat`, `no_hp`, `id_poli`) VALUES
(11, 'Adi', 'Semarang', '0489921018', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_periksa`
--

CREATE TABLE `jadwal_periksa` (
  `id` int(11) NOT NULL,
  `id_dokter` int(11) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `aktif` enum('Y','T') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `jadwal_periksa`
--

INSERT INTO `jadwal_periksa` (`id`, `id_dokter`, `hari`, `jam_mulai`, `jam_selesai`, `aktif`) VALUES
(1, 11, 'Selasa', '07:00:00', '09:00:00', 'Y'),
(2, 11, 'Rabu', '08:30:00', '10:20:00', 'T');

-- --------------------------------------------------------

--
-- Struktur dari tabel `obat`
--

CREATE TABLE `obat` (
  `id` int(11) NOT NULL,
  `nama_obat` varbinary(255) NOT NULL,
  `kemasan` varchar(35) NOT NULL,
  `harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `obat`
--

INSERT INTO `obat` (`id`, `nama_obat`, `kemasan`, `harga`) VALUES
(1, 0x41435420284172746573756e617465207461626c6574203530206d67202b20416d6f6469617175696e6520616e68796472696461207461626c657420323030206d6729, '2 blister @ 12 tablet / kotak', 44000),
(2, 0x41435420284172746573756e617465207461626c6574203530206d67202b20416d6f6469617175696e6520616e68796472696461207461626c657420323030206d6729, '3 blister @ 8 tablet / kotak', 44000),
(3, 0x416c62656e6461736f6c2073757370656e736920323030206d672f35206d6c, 'Ktk 10 btl @ 10 ml', 6000),
(4, 0x416c62656e64617a6f6c207461626c65742f207461626c6574206b756e79616820343030206d67, 'ktk 5 x 6 tablet', 16000),
(5, 0x416c6f707572696e6f6c207461626c657420313030206d67, 'ktk 10 x 10 tablet', 16000),
(6, 0x416c6f707572696e6f6c207461626c657420333030206d67, 'ktk 10 x 10 tablet', 33000),
(7, 0x416c7072617a6f6c616d207461626c657420302c3235206d67, 'ktk 10 x 10 tablet', 64000),
(8, 0x416c7072617a6f6c616d207461626c657420302c35206d67, 'ktk 10 x 10 tablet', 77000),
(9, 0x416c7072617a6f6c616d207461626c65742031206d67, 'ktk 10 x 10 tablet', 118000),
(10, 0x416d62726f786f6c207369727570203135206d672f6d6c, 'btl 60 ml', 5000),
(11, 0x416d62726f786f6c207369727570203330206d67, 'ktk 10 x 10 tablet', 21000),
(12, 0x416d696c6f72696461207461626c65742035206d67, 'ktk 10 x 10 tablet', 12000),
(13, 0x416d696e6f66696c696e20696e6a656b7369203234206d672f6d6c, 'ktk 30 ampul @ 10 ml', 118000),
(14, 0x416d696e6f66696c696e207461626c657420313530, 'botol 1000 tablet', 57000),
(15, 0x416d696e6f66696c696e207461626c657420323030, 'botol 100 tablet', 15000),
(16, 0x416d697472697074696c696e207461626c65742073616c7574203235206d67202848434929, 'ktk 10 x 10 tablet salut', 16000),
(17, 0x416d6c6f646970696e207461626c65742035206d67, 'ktk 3 x 10 tablet', 9000),
(18, 0x416d6c6f646970696e207461626c65742035206d67, 'ktk 5 x 10 tablet', 63000),
(19, 0x416d6c6f646970696e207461626c6574203130206d67, 'ktk 3 x 10 tablet', 8750),
(20, 0x416d6c6f646970696e207461626c6574203130206d67, 'ktk 5 x 10 tablet', 111000),
(21, 0x416d6f6b736973696c696e202b41732e4b6c6176756c616e617420363235206d67207461626c6574, 'ktk 5 x 6 tablet', 209000),
(22, 0x416d6f6b736973696c696e206b617073756c20323530206d67, 'ktk 10 x 10 kapsul', 38000),
(23, 0x416d6f6b736973696c696e206b617073756c20323530206d67, 'ktk 12 x 10 kapsul', 52000),
(24, 0x416d6f6b736973696c696e204b61706c657420353030206d67, 'ktk 10 x 10 kapsul', 45000),
(25, 0x416d6f6b736973696c696e2073657262756b20696e6a656b73692031303030206d67, 'ktk 10 vial', 99000),
(26, 0x416d6f6b736973696c696e20736972757020313235206d672f35206d6c, 'botol 60 ml', 14000),
(27, 0x416d6f6b736973696c696e20736972757020323530206d672f35206d6c, 'botol 60 ml', 22000),
(28, 0x416d6f6b736973696c696e207461626c657420353030206d67, 'ktk 10 x 10 tablet', 39000),
(29, 0x416d6f6b736973696c696e202b41732e4b6c6176756c616e617420313235206d67207369727570, 'botol 60 ml', 19000),
(30, 0x416d6f6b736973696c696e202b41732e4b6c6176756c616e617420343030206d6720696e6a656b7369, 'ktk 1 vial + 1 ampul pelarut', 26000),
(31, 0x416d706973696c696e20313235206d67206b617073756c, 'ktk 10 x 10 kapsul', 19000),
(32, 0x416d706973696c696e20323530206d67206b617073756c, 'ktk 10 x 10 kapsul', 28000),
(33, 0x416d706973696c696e20353030206d67206b617073756c, 'ktk 10 x 10 kapsul', 38000),
(34, 0x416d706973696c696e20736972757020313235206d672f35206d6c, 'botol 60 ml', 12000),
(35, 0x416d706973696c696e20736972757020323530206d672f35206d6c, 'botol 60 ml', 18000),
(36, 0x4173616d204d6566656e616d6174207461626c657420353030206d67, 'ktk 10 x 10 tablet', 28000),
(37, 0x4173616d207472616e656b73616d6174207461626c657420353030206d67, 'ktk 10 x 10 tablet', 34000),
(38, 0x4174656e6f6c6f6c207461626c6574203530206d67, 'ktk 10 x 10 tablet', 22000),
(39, 0x426574616d657461736f6e206b72656d20302c3125, 'tube 5 gram', 12000),
(40, 0x426574616d657461736f6e2073616c657020302c3125, 'tube 5 gram', 14000),
(41, 0x426973616b6f64696c207461626c65742035206d67, 'ktk 10 x 10 tablet', 16000),
(42, 0x4269736f70726f6c6f6c2066756d61726174207461626c657420322c35206d67, 'ktk 3 x 10 tablet', 24000),
(43, 0x4269736f70726f6c6f6c2066756d61726174207461626c65742035206d67, 'ktk 3 x 10 tablet', 32000),
(44, 0x4269736f70726f6c6f6c2066756d61726174207461626c6574203130206d67, 'ktk 3 x 10 tablet', 45000),
(45, 0x42726f6d686578696e2073697275702034206d672f35206d6c, 'botol 60 ml', 6000),
(46, 0x42726f6d686578696e207461626c65742038206d67, 'ktk 10 x 10 tablet', 14000),
(47, 0x42726f6d6f6b72697074696e207461626c657420322c35206d67, 'ktk 3 x 10 tablet', 28000),
(48, 0x436170746f7072696c207461626c65742031322c35206d67, 'ktk 10 x 10 tablet', 25000),
(49, 0x4361706f74656e207461626c6574203235206d67, 'ktk 3 x 10 tablet', 24000),
(50, 0x436170746f7072696c207461626c6574203530206d67, 'ktk 10 x 10 tablet', 34000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pasien`
--

CREATE TABLE `pasien` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `no_ktp` varchar(255) NOT NULL,
  `no_hp` varchar(50) NOT NULL,
  `no_rm` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `pasien`
--

INSERT INTO `pasien` (`id`, `nama`, `alamat`, `no_ktp`, `no_hp`, `no_rm`) VALUES
(6, 'Adi', 'Semarang', '1234567890', '085294567', NULL),
(7, 'Coba', 'Coba Bang', '12345', '12345', '202401-001'),
(8, 'Coba', 'coba', '123456', '123456', '202401-002'),
(9, 'Adi', 'coba', '123455', '123455', '202401-003');

-- --------------------------------------------------------

--
-- Struktur dari tabel `periksa`
--

CREATE TABLE `periksa` (
  `id` int(11) NOT NULL,
  `id_daftar_poli` int(11) NOT NULL,
  `tgl_periksa` datetime NOT NULL,
  `catatan` text,
  `biaya_periksa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `periksa`
--

INSERT INTO `periksa` (`id`, `id_daftar_poli`, `tgl_periksa`, `catatan`, `biaya_periksa`) VALUES
(5, 1, '2024-01-03 10:42:00', 'Katarak sepertinya', 200000),
(6, 1, '2024-01-03 10:42:00', 'Katarak sepertinya', 200000),
(7, 1, '2024-01-03 10:42:00', 'Katarak sepertinya', 200000),
(8, 1, '2024-01-03 10:42:00', 'Katarak sepertinya', 200000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `poli`
--

CREATE TABLE `poli` (
  `id` int(11) NOT NULL,
  `nama_poli` varchar(25) NOT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `poli`
--

INSERT INTO `poli` (`id`, `nama_poli`, `keterangan`) VALUES
(1, 'Poliklinik Satu', 'Sangat murah');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `daftar_poli`
--
ALTER TABLE `daftar_poli`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pasien` (`id_pasien`,`id_jadwal`),
  ADD KEY `id_jadwal` (`id_jadwal`);

--
-- Indeks untuk tabel `detail_periksa`
--
ALTER TABLE `detail_periksa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_periksa` (`id_periksa`,`id_obat`),
  ADD KEY `id_obat` (`id_obat`);

--
-- Indeks untuk tabel `dokter`
--
ALTER TABLE `dokter`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `id_poli` (`id_poli`);

--
-- Indeks untuk tabel `jadwal_periksa`
--
ALTER TABLE `jadwal_periksa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_dokter` (`id_dokter`);

--
-- Indeks untuk tabel `obat`
--
ALTER TABLE `obat`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pasien`
--
ALTER TABLE `pasien`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `periksa`
--
ALTER TABLE `periksa`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `id_daftar_poli` (`id_daftar_poli`);

--
-- Indeks untuk tabel `poli`
--
ALTER TABLE `poli`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `daftar_poli`
--
ALTER TABLE `daftar_poli`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `detail_periksa`
--
ALTER TABLE `detail_periksa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `dokter`
--
ALTER TABLE `dokter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `jadwal_periksa`
--
ALTER TABLE `jadwal_periksa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `obat`
--
ALTER TABLE `obat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT untuk tabel `pasien`
--
ALTER TABLE `pasien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `periksa`
--
ALTER TABLE `periksa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `poli`
--
ALTER TABLE `poli`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `daftar_poli`
--
ALTER TABLE `daftar_poli`
  ADD CONSTRAINT `daftar_poli_ibfk_1` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `daftar_poli_ibfk_2` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal_periksa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `detail_periksa`
--
ALTER TABLE `detail_periksa`
  ADD CONSTRAINT `detail_periksa_ibfk_1` FOREIGN KEY (`id_periksa`) REFERENCES `periksa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_periksa_ibfk_2` FOREIGN KEY (`id_obat`) REFERENCES `obat` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `dokter`
--
ALTER TABLE `dokter`
  ADD CONSTRAINT `dokter_ibfk_1` FOREIGN KEY (`id_poli`) REFERENCES `poli` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `jadwal_periksa`
--
ALTER TABLE `jadwal_periksa`
  ADD CONSTRAINT `jadwal_periksa_ibfk_1` FOREIGN KEY (`id_dokter`) REFERENCES `dokter` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `periksa`
--
ALTER TABLE `periksa`
  ADD CONSTRAINT `periksa_ibfk_1` FOREIGN KEY (`id_daftar_poli`) REFERENCES `daftar_poli` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
