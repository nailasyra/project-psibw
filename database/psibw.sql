-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3308
-- Generation Time: May 12, 2026 at 05:46 AM
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
-- Database: `psibw`
--

-- --------------------------------------------------------

--
-- Table structure for table `dosen`
--

CREATE TABLE `dosen` (
  `nidn` int(10) NOT NULL,
  `nama` varchar(40) NOT NULL,
  `jur` int(6) NOT NULL,
  `email` varchar(40) NOT NULL,
  `agama` int(1) NOT NULL,
  `status` int(1) NOT NULL,
  `tmp_lahir` varchar(40) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `jk` int(1) NOT NULL,
  `alamat` varchar(40) NOT NULL,
  `pendidikan` varchar(40) NOT NULL,
  `fotodosen` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dosen`
--

INSERT INTO `dosen` (`nidn`, `nama`, `jur`, `email`, `agama`, `status`, `tmp_lahir`, `tgl_lahir`, `jk`, `alamat`, `pendidikan`, `fotodosen`) VALUES
(1023010001, 'Andi Saputra', 160305, 'andisiregar@unri.ac.id', 1, 1, 'Pekanbaru', '1985-03-12', 1, 'Jl. Garuda', 'S2', ''),
(1023010002, 'Siti Rahma', 160301, 'sitimayumi@unri.ac.id', 1, 1, 'Padang', '1987-07-21', 0, 'Jl. Melati', 'S3', ''),
(1023010003, 'Budi Santoso', 160302, 'budisantoso@unri.ac.id', 2, 1, 'Medan', '1980-01-15', 1, 'Jl. Sudirman', 'S2', ''),
(1023010004, 'Rina Amelia', 160303, 'rinalia@unri.ac.id', 1, 1, 'Dumai', '1990-09-10', 0, 'Jl. Hangtuah', 'S2', ''),
(1023010005, 'Fajar Nugraha', 160304, 'fajarra@unri.ac.id', 2, 1, 'Bangkinang', '1982-05-08', 1, 'Jl. Cendana', 'S3', ''),
(1023010006, 'Dewi Lestari', 160305, 'dewi@unri.ac.id', 3, 1, 'Siak', '1988-11-18', 0, 'Jl. Nangka', 'S2', ''),
(1023010007, 'Rizky Hidayat', 160301, 'rizky@unri.ac.id', 4, 1, 'Pelalawan', '1986-02-25', 1, 'Jl. Anggrek', 'S2', ''),
(1023010008, 'Maya Sari', 160302, 'maya@unri.ac.id', 4, 1, 'Bukittinggi', '1991-06-30', 0, 'Jl. Kenanga', 'S3', ''),
(1023010009, 'Hendra Wijaya', 160303, 'hendra@unri.ac.id', 1, 1, 'Pekanbaru', '1983-08-14', 1, 'Jl. Pepaya', 'S2', ''),
(1023010010, 'Nur Aini', 160304, 'nuraini@unri.ac.id', 1, 1, 'Kampar', '1992-12-05', 0, 'Jl. Mawar', 'S2', '');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `nim` varchar(10) NOT NULL,
  `nama` varchar(40) NOT NULL,
  `jur` int(6) NOT NULL,
  `prodi` int(6) NOT NULL,
  `email` varchar(40) NOT NULL,
  `agama` int(1) NOT NULL,
  `status` int(1) NOT NULL,
  `tmp_lahir` varchar(40) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `jk` int(1) NOT NULL,
  `alamat` varchar(40) NOT NULL,
  `foto` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`nim`, `nama`, `jur`, `prodi`, `email`, `agama`, `status`, `tmp_lahir`, `tgl_lahir`, `jk`, `alamat`, `foto`) VALUES
('2403112306', 'Alhaitam', 160305, 160317, 'alhaitam@student.unri.ac.id', 6, 4, 'Pekanbaru', '2004-09-14', 1, 'Jl. Cendana', 'al.jpg'),
('2403113007', 'Maya Lestari', 160301, 160312, 'maya@student.unri.ac.id', 1, 5, 'Padang', '2005-05-21', 0, 'Jl. Anggrek', ''),
('2403113405', 'Dinda Maharani', 160304, 160315, 'dinda@student.unri.ac.id', 5, 2, 'Pelalawan', '2005-02-28', 0, 'Jl. Kenanga', ''),
('2403113502', 'Rizky Pratama', 160301, 160311, 'rizky@student.unri.ac.id', 2, 2, 'Dumai', '2004-03-25', 1, 'Jl. Garuda', ''),
('2403114401', 'Nabila Azzahra', 160305, 160316, 'nabila@student.unri.ac.id', 1, 1, 'Pekanbaru', '2005-01-12', 0, 'Jl. Melati', ''),
('2403115510', 'Hendra Wijaya', 160304, 160315, 'hendra@student.unri.ac.id', 4, 2, 'Kampar', '2004-12-08', 1, 'Jl. Dahlia', ''),
('2403117604', 'Fajar Hidayat', 160303, 160314, 'fajar@student.unri.ac.id', 4, 1, 'Siak', '2004-11-03', 1, 'Jl. Sudirman', ''),
('2403117803', 'Salsa Putri', 160302, 160313, 'salsa@student.unri.ac.id', 3, 3, 'Bangkinang', '2005-07-19', 0, 'Jl. Nangka', ''),
('2403118108', 'Budi Santoso', 160302, 160313, 'budi@student.unri.ac.id', 2, 3, 'Medan', '2004-08-30', 1, 'Jl. Mawar', ''),
('2403119909', 'Citra Amelia', 160303, 160314, 'citra@student.unri.ac.id', 3, 1, 'Bukittinggi', '2005-10-17', 0, 'Jl. Pepaya', '');

-- --------------------------------------------------------

--
-- Table structure for table `matakuliah`
--

CREATE TABLE `matakuliah` (
  `kode_mk` int(10) NOT NULL,
  `nama_mk` varchar(40) NOT NULL,
  `dosen` varchar(40) NOT NULL,
  `sks` int(1) NOT NULL,
  `semester` int(1) NOT NULL,
  `jur` int(6) NOT NULL,
  `prodi` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `matakuliah`
--

INSERT INTO `matakuliah` (`kode_mk`, `nama_mk`, `dosen`, `sks`, `semester`, `jur`, `prodi`) VALUES
(1101, 'Kalkulus', '', 3, 1, 160301, 160311),
(1102, 'Statistika Dasar', '', 3, 1, 160301, 160312),
(1201, 'Fisika Dasar', '', 3, 1, 160302, 160313),
(1301, 'Kimia Dasar', '', 3, 1, 160303, 160314),
(1401, 'Biologi Umum', '', 3, 1, 160304, 160315),
(1501, 'Algoritma Pemrograman', '', 3, 1, 160305, 160316),
(1502, 'Basis Data', '', 3, 3, 160305, 160316),
(1503, 'Pemrograman Web', '', 3, 4, 160305, 160316),
(1504, 'Jaringan Komputer', '', 3, 4, 160305, 160317),
(1505, 'Sistem Operasi', '', 3, 3, 160305, 160317);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(10) NOT NULL,
  `username` varchar(40) NOT NULL,
  `pass` varchar(10) NOT NULL,
  `role` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `pass`, `role`) VALUES
(1, 'admin', 'admin123', 1),
(2, '2403114401', 'mhs123', 2),
(3, '2403113502', 'mhs123', 2),
(4, '2403117803', 'mhs123', 2),
(5, '1023010001', 'dsn123', 3),
(6, '1023010002', 'dsn123', 3),
(7, '1023010003', 'dsn123', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`nidn`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`nim`);

--
-- Indexes for table `matakuliah`
--
ALTER TABLE `matakuliah`
  ADD PRIMARY KEY (`kode_mk`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
