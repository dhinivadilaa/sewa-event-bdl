-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2025 at 07:45 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sewa_event`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `nama_admin` varchar(100) NOT NULL,
  `no_telp` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `nama_admin`, `no_telp`, `email`) VALUES
(1, 'Super Admin', '081122334455', 'admin@sewaevent.com');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id_customer` int(11) NOT NULL,
  `nama_customer` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_telp` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id_customer`, `nama_customer`, `alamat`, `no_telp`, `email`) VALUES
(1, 'Budi Santoso', 'Jl. Merdeka No. 10, Jakarta', '081234567890', 'budi@mail.com'),
(2, 'Citra Dewi', 'Perumahan Indah Blok C5, Bandung', '087766554433', 'citra@mail.com');

-- --------------------------------------------------------

--
-- Table structure for table `kategori_pakaian`
--

CREATE TABLE `kategori_pakaian` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori_pakaian`
--

INSERT INTO `kategori_pakaian` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Gaun Pesta'),
(2, 'Jas Formal'),
(3, 'Pakaian Adat');

-- --------------------------------------------------------

--
-- Table structure for table `layanan_event`
--

CREATE TABLE `layanan_event` (
  `id_layanan` int(11) NOT NULL,
  `id_vendor` int(11) DEFAULT NULL COMMENT 'NULL jika layanan internal',
  `nama_layanan` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `biaya_layanan` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `layanan_event`
--

INSERT INTO `layanan_event` (`id_layanan`, `id_vendor`, `nama_layanan`, `deskripsi`, `biaya_layanan`) VALUES
(1, 1, 'Jasa Fotografer 6 Jam', 'Fotografi liputan acara 6 jam', 1500000),
(2, 2, 'Jasa MUA Full Day', 'Make Up Artist untuk 1 orang (siang & malam)', 1000000),
(3, NULL, 'Asisten Pakaian', 'Jasa staff untuk membantu penggunaan pakaian adat', 250000);

-- --------------------------------------------------------

--
-- Table structure for table `pakaian`
--

CREATE TABLE `pakaian` (
  `id_pakaian` varchar(10) NOT NULL COMMENT 'Contoh: GP001',
  `id_kategori` int(11) NOT NULL,
  `nama_pakaian` varchar(100) NOT NULL,
  `ukuran` varchar(10) NOT NULL,
  `warna` varchar(50) DEFAULT NULL,
  `harga_sewa` decimal(10,0) NOT NULL,
  `stok` int(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pakaian`
--

INSERT INTO `pakaian` (`id_pakaian`, `id_kategori`, `nama_pakaian`, `ukuran`, `warna`, `harga_sewa`, `stok`) VALUES
('GP001', 1, 'Gaun Merah Elegan', 'M', 'Merah', 500000, 5),
('GP002', 1, 'Gaun Malam Biru', 'S', 'Biru Navy', 750000, 2),
('JF001', 2, 'Jas Hitam Slim Fit', 'L', 'Hitam', 350000, 8),
('PA001', 3, 'Baju Adat Sunda Lengkap', 'All Size', 'Kuning Emas', 600000, 3);

-- --------------------------------------------------------

--
-- Table structure for table `pengembalian`
--

CREATE TABLE `pengembalian` (
  `no_kembali` varchar(20) NOT NULL COMMENT 'Contoh: KEMB-20251106-001',
  `no_sewa` varchar(20) NOT NULL,
  `id_staff` int(11) NOT NULL,
  `tgl_kembali_aktual` date NOT NULL,
  `status_pengembalian` enum('Selesai','Terlambat','Rusak/Hilang') NOT NULL,
  `total_denda` decimal(10,0) DEFAULT 0,
  `keterangan_denda` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengembalian`
--

INSERT INTO `pengembalian` (`no_kembali`, `no_sewa`, `id_staff`, `tgl_kembali_aktual`, `status_pengembalian`, `total_denda`, `keterangan_denda`) VALUES
('KEMB-20251030-001', 'SEW-20251025-002', 2, '2025-10-30', 'Selesai', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `hak_akses` enum('Admin','Customer','Staff','Vendor') NOT NULL,
  `id_referensi` int(11) NOT NULL COMMENT 'FK ke id_admin, id_customer, id_staff, atau id_vendor'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `username`, `password`, `hak_akses`, `id_referensi`) VALUES
(1, 'superadmin', 'password123', 'Admin', 1),
(2, 'rina', 'password123', 'Staff', 1),
(3, 'budi', 'password123', 'Customer', 1),
(4, 'studiocepat', 'password123', 'Vendor', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sewa_detail_layanan`
--

CREATE TABLE `sewa_detail_layanan` (
  `id_detail_layanan` int(11) NOT NULL,
  `no_sewa` varchar(20) NOT NULL,
  `id_layanan` int(11) NOT NULL,
  `biaya_saat_transaksi` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sewa_detail_layanan`
--

INSERT INTO `sewa_detail_layanan` (`id_detail_layanan`, `no_sewa`, `id_layanan`, `biaya_saat_transaksi`) VALUES
(1, 'SEW-20251106-001', 1, 1500000),
(2, 'SEW-20251106-001', 3, 250000);

-- --------------------------------------------------------

--
-- Table structure for table `sewa_detail_pakaian`
--

CREATE TABLE `sewa_detail_pakaian` (
  `id_detail_pakaian` int(11) NOT NULL,
  `no_sewa` varchar(20) NOT NULL,
  `id_pakaian` varchar(10) NOT NULL,
  `harga_sewa_saat_transaksi` decimal(10,0) NOT NULL,
  `jumlah` int(3) NOT NULL,
  `subtotal` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sewa_detail_pakaian`
--

INSERT INTO `sewa_detail_pakaian` (`id_detail_pakaian`, `no_sewa`, `id_pakaian`, `harga_sewa_saat_transaksi`, `jumlah`, `subtotal`) VALUES
(1, 'SEW-20251106-001', 'GP001', 500000, 1, 500000),
(2, 'SEW-20251106-001', 'JF001', 350000, 1, 350000);

-- --------------------------------------------------------

--
-- Table structure for table `sewa_header`
--

CREATE TABLE `sewa_header` (
  `no_sewa` varchar(20) NOT NULL COMMENT 'Contoh: SEW-20251106-001',
  `tgl_sewa` date NOT NULL,
  `id_customer` int(11) NOT NULL,
  `id_staff` int(11) NOT NULL,
  `tgl_ambil_rencana` date NOT NULL,
  `tgl_kembali_rencana` date NOT NULL,
  `total_biaya` decimal(10,0) NOT NULL,
  `uang_muka` decimal(10,0) DEFAULT 0,
  `status_pembayaran` enum('DP','Lunas','Batal') NOT NULL DEFAULT 'DP',
  `status_sewa` enum('Draft','Aktif','Selesai','Batal') NOT NULL DEFAULT 'Draft'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sewa_header`
--

INSERT INTO `sewa_header` (`no_sewa`, `tgl_sewa`, `id_customer`, `id_staff`, `tgl_ambil_rencana`, `tgl_kembali_rencana`, `total_biaya`, `uang_muka`, `status_pembayaran`, `status_sewa`) VALUES
('SEW-20251025-002', '2025-10-25', 2, 1, '2025-10-28', '2025-10-30', 600000, 600000, 'Lunas', 'Selesai'),
('SEW-20251106-001', '2025-11-06', 1, 1, '2025-11-15', '2025-11-17', 2600000, 500000, 'DP', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id_staff` int(11) NOT NULL,
  `nama_staff` varchar(100) NOT NULL,
  `no_telp` varchar(15) DEFAULT NULL,
  `jabatan` varchar(50) DEFAULT 'Petugas Transaksi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id_staff`, `nama_staff`, `no_telp`, `jabatan`) VALUES
(1, 'Rina Petugas', '085000111222', 'Petugas Transaksi'),
(2, 'Andi Kasir', '085000333444', 'Petugas Pengembalian');

-- --------------------------------------------------------

--
-- Table structure for table `vendor`
--

CREATE TABLE `vendor` (
  `id_vendor` int(11) NOT NULL,
  `nama_vendor` varchar(100) NOT NULL,
  `kontak` varchar(15) NOT NULL,
  `jenis_layanan` varchar(50) DEFAULT NULL COMMENT 'Contoh: MUA, Fotografi, Dekorasi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendor`
--

INSERT INTO `vendor` (`id_vendor`, `nama_vendor`, `kontak`, `jenis_layanan`) VALUES
(1, 'Studio Cepat', '021987654', 'Fotografi & Videografi'),
(2, 'Glamour MUA', '082121212121', 'Make Up Artist');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id_customer`);

--
-- Indexes for table `kategori_pakaian`
--
ALTER TABLE `kategori_pakaian`
  ADD PRIMARY KEY (`id_kategori`),
  ADD UNIQUE KEY `nama_kategori` (`nama_kategori`);

--
-- Indexes for table `layanan_event`
--
ALTER TABLE `layanan_event`
  ADD PRIMARY KEY (`id_layanan`),
  ADD KEY `id_vendor` (`id_vendor`);

--
-- Indexes for table `pakaian`
--
ALTER TABLE `pakaian`
  ADD PRIMARY KEY (`id_pakaian`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indexes for table `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD PRIMARY KEY (`no_kembali`),
  ADD UNIQUE KEY `no_sewa` (`no_sewa`),
  ADD KEY `id_staff` (`id_staff`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `sewa_detail_layanan`
--
ALTER TABLE `sewa_detail_layanan`
  ADD PRIMARY KEY (`id_detail_layanan`),
  ADD KEY `no_sewa` (`no_sewa`),
  ADD KEY `id_layanan` (`id_layanan`);

--
-- Indexes for table `sewa_detail_pakaian`
--
ALTER TABLE `sewa_detail_pakaian`
  ADD PRIMARY KEY (`id_detail_pakaian`),
  ADD KEY `no_sewa` (`no_sewa`),
  ADD KEY `id_pakaian` (`id_pakaian`);

--
-- Indexes for table `sewa_header`
--
ALTER TABLE `sewa_header`
  ADD PRIMARY KEY (`no_sewa`),
  ADD KEY `id_customer` (`id_customer`),
  ADD KEY `id_staff` (`id_staff`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id_staff`);

--
-- Indexes for table `vendor`
--
ALTER TABLE `vendor`
  ADD PRIMARY KEY (`id_vendor`),
  ADD UNIQUE KEY `nama_vendor` (`nama_vendor`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id_customer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kategori_pakaian`
--
ALTER TABLE `kategori_pakaian`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `layanan_event`
--
ALTER TABLE `layanan_event`
  MODIFY `id_layanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sewa_detail_layanan`
--
ALTER TABLE `sewa_detail_layanan`
  MODIFY `id_detail_layanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sewa_detail_pakaian`
--
ALTER TABLE `sewa_detail_pakaian`
  MODIFY `id_detail_pakaian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id_staff` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vendor`
--
ALTER TABLE `vendor`
  MODIFY `id_vendor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `layanan_event`
--
ALTER TABLE `layanan_event`
  ADD CONSTRAINT `fk_layanan_vendor` FOREIGN KEY (`id_vendor`) REFERENCES `vendor` (`id_vendor`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `pakaian`
--
ALTER TABLE `pakaian`
  ADD CONSTRAINT `fk_pakaian_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_pakaian` (`id_kategori`) ON UPDATE CASCADE;

--
-- Constraints for table `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD CONSTRAINT `fk_pengembalian_sewa` FOREIGN KEY (`no_sewa`) REFERENCES `sewa_header` (`no_sewa`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pengembalian_staff` FOREIGN KEY (`id_staff`) REFERENCES `staff` (`id_staff`) ON UPDATE CASCADE;

--
-- Constraints for table `sewa_detail_layanan`
--
ALTER TABLE `sewa_detail_layanan`
  ADD CONSTRAINT `fk_detail_layanan_layanan` FOREIGN KEY (`id_layanan`) REFERENCES `layanan_event` (`id_layanan`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_layanan_sewa` FOREIGN KEY (`no_sewa`) REFERENCES `sewa_header` (`no_sewa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sewa_detail_pakaian`
--
ALTER TABLE `sewa_detail_pakaian`
  ADD CONSTRAINT `fk_detail_pakaian_pakaian` FOREIGN KEY (`id_pakaian`) REFERENCES `pakaian` (`id_pakaian`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_pakaian_sewa` FOREIGN KEY (`no_sewa`) REFERENCES `sewa_header` (`no_sewa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sewa_header`
--
ALTER TABLE `sewa_header`
  ADD CONSTRAINT `fk_sewa_customer` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id_customer`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sewa_staff` FOREIGN KEY (`id_staff`) REFERENCES `staff` (`id_staff`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



use sewa_event;

ALTER TABLE pakaian
ADD COLUMN deskripsi TEXT NULL AFTER warna,
ADD COLUMN gambar VARCHAR(255) NULL AFTER deskripsi;