-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 18 Jun 2024 pada 09.28
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistempendaftaran`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `fakultas`
--

CREATE TABLE `fakultas` (
  `idfakultas` int(11) NOT NULL,
  `nama` char(50) NOT NULL,
  `img` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `fakultas`
--

INSERT INTO `fakultas` (`idfakultas`, `nama`, `img`) VALUES
(1, 'FAKULTAS EKONOMI DAN BISNIS', 'uploads/gedung-fakultas-eekonomi-dan-bisnis-feb-upn-veteran-jatim-ist.jpg'),
(2, 'FAKULTAS ILMU SOSIAL DAN POLITIK', 'uploads/ilmu-sosial-1.jpg'),
(3, 'FAKULTAS PERTANIAN', 'uploads/rsz_img_20190219_071335-700x500.jpg'),
(4, 'FAKULTAS TEKNIK', 'uploads/teknik-2.jpg'),
(5, 'FAKULTAS ILMU KOMPUTER', 'uploads/santika-1.jpg'),
(6, 'FAKULTAS ARSITEKTUR DAN DESAIN', 'uploads/fad-1.jpg'),
(7, 'FAKULTAS HUKUM', 'uploads/hukum-1.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pemilihan`
--

CREATE TABLE `pemilihan` (
  `idpemilih` int(11) NOT NULL,
  `idpendaftar` int(11) NOT NULL,
  `rata_rata` float NOT NULL,
  `keterangan` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pendaftar`
--

CREATE TABLE `pendaftar` (
  `idpendaftar` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `asalsekolah` varchar(50) NOT NULL,
  `nisn` varchar(11) NOT NULL,
  `img` varchar(100) NOT NULL,
  `notelepon` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pendaftar`
--

INSERT INTO `pendaftar` (`idpendaftar`, `nama`, `email`, `password`, `alamat`, `asalsekolah`, `nisn`, `img`, `notelepon`) VALUES
(1, 'RIZKI BAEHTIAR AFANDI', 'rzkibhtrafnd@gmail.com', '123', 'Dusun Suci, Desa Jubellor, Kecamatan Sugio, Kabupapaten Lamongan', 'SMAN 1 KEDUNGPRING', '54615584', '1.jpg', '085158688431'),
(3, 'RYAN BAGUS PRAMONO', 'ryanbagus@gmail.com', '123', 'Sukodono, Sidoarjo', 'SMAN 1 TARIK', '95316584', '', ''),
(4, 'IRFAN PRAMONO', 'irfan@gmail.com', '123', 'Sukodono, Sidoarjo', 'SMAN 1 TARIK', '51658468', '', '085158688455'),
(5, 'Reyhan Fishena', 'reyhan@gmail.com', '123', 'Kabupaten Jombang', 'SMAN 1 JOMBANG', '56486254', '', '085158688444');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `idpengguna` int(11) NOT NULL,
  `username` char(50) NOT NULL,
  `password` char(50) NOT NULL,
  `role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`idpengguna`, `username`, `password`, `role`) VALUES
(1, 'RIZKI BAEHTIAR AFANDI', '123', 2),
(2, 'tarjo Admin', '123', 1),
(4, 'RYAN BAGUS', '123', 2),
(5, 'ALDEN HOYA', '123', 2),
(6, 'Bagus Budiman', '123', 1),
(7, 'REYHAN MUHAMMAD', '123', 2),
(8, 'NABIL RAMADHAN', '123', 2),
(9, 'LEON DANAR', '123', 2),
(10, 'ALFIAN SAKTI', '123', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `prodi`
--

CREATE TABLE `prodi` (
  `idprodi` int(11) NOT NULL,
  `idfakultas` int(11) NOT NULL,
  `nama_prodi` char(50) NOT NULL,
  `id_penilai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `prodi`
--

INSERT INTO `prodi` (`idprodi`, `idfakultas`, `nama_prodi`, `id_penilai`) VALUES
(1, 1, 'MANAJEMEN', 4),
(2, 1, 'EKONOMI PEMBANGUNAN', 4),
(3, 1, 'AKUNTANSI', 4),
(4, 1, 'KEWIRAUSAHAAN', 4),
(5, 3, 'AGRIBISNIS', 7),
(6, 3, 'AGROTEKNOLOGI', 7),
(7, 2, 'ILMU KOMUNIKASI', 5),
(8, 2, 'HUBUNGAN INTERNASIONAL', 5),
(9, 4, 'TEKNIK INDUSTRI', 8),
(10, 4, 'TEKNIK SIPIL', 8),
(11, 5, 'INFORMATIKA', 1),
(12, 5, 'SISTEM INFORMASI', 1),
(13, 6, 'DESIGN GRAFIS', 9),
(14, 6, 'ARSITEKTUR', 9),
(15, 7, 'HUKUM', 10),
(16, 2, 'ADMINISTRASI PUBLIK', 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `prodi_pil`
--

CREATE TABLE `prodi_pil` (
  `id_prodi_pil` int(11) NOT NULL,
  `idprodi` int(11) NOT NULL,
  `idpendaftar` int(11) NOT NULL,
  `pilihan` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `prodi_pil`
--

INSERT INTO `prodi_pil` (`id_prodi_pil`, `idprodi`, `idpendaftar`, `pilihan`, `status`) VALUES
(5, 11, 1, 1, 3),
(6, 9, 1, 2, 3),
(7, 11, 3, 1, 3),
(8, 12, 3, 2, 3),
(9, 9, 5, 1, 2),
(10, 11, 5, 2, 2),
(11, 9, 4, 1, 4),
(12, 6, 4, 2, 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `raport`
--

CREATE TABLE `raport` (
  `id_raport` int(11) NOT NULL,
  `sem` int(11) NOT NULL,
  `nilai` float NOT NULL,
  `idpendaftar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `raport`
--

INSERT INTO `raport` (`id_raport`, `sem`, `nilai`, `idpendaftar`) VALUES
(36, 1, 80, 1),
(37, 2, 83.5, 1),
(38, 3, 85, 1),
(39, 4, 87.5, 1),
(40, 5, 92, 1),
(66, 1, 80, 3),
(67, 2, 83.5, 3),
(68, 3, 85, 3),
(69, 4, 87.5, 3),
(70, 5, 90, 3),
(86, 1, 80, 5),
(87, 2, 83.5, 5),
(88, 3, 83, 5),
(89, 4, 80, 5),
(90, 5, 85, 5),
(91, 1, 75, 4),
(92, 2, 78, 4),
(93, 3, 81, 4),
(94, 4, 80, 4),
(95, 5, 82, 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sertifikat`
--

CREATE TABLE `sertifikat` (
  `idsertifikat` int(11) NOT NULL,
  `idpendaftar` int(11) NOT NULL,
  `judul` varchar(100) NOT NULL,
  `sertifikat` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sertifikat`
--

INSERT INTO `sertifikat` (`idsertifikat`, `idpendaftar`, `judul`, `sertifikat`) VALUES
(8, 1, 'Juara 1 OSN MATEMATIKA TINGKAT KABUPATEN LAMONGAN -2021', '1'),
(9, 1, 'Juara 1 OSN MATEMATIKA TINGKAT KABUPATEN LAMONGAN - 2022', '2'),
(10, 1, 'Juara 1 OSN TIK TINGKAT KABUPATEN LAMONGAN - 2023', '3'),
(11, 5, 'JUARA 1 BASKET TINGKAT PROVINSI PRAPORPROV - 2022', '1');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `fakultas`
--
ALTER TABLE `fakultas`
  ADD PRIMARY KEY (`idfakultas`);

--
-- Indeks untuk tabel `pemilihan`
--
ALTER TABLE `pemilihan`
  ADD PRIMARY KEY (`idpemilih`),
  ADD KEY `idpendaftar` (`idpendaftar`);

--
-- Indeks untuk tabel `pendaftar`
--
ALTER TABLE `pendaftar`
  ADD PRIMARY KEY (`idpendaftar`);

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`idpengguna`);

--
-- Indeks untuk tabel `prodi`
--
ALTER TABLE `prodi`
  ADD PRIMARY KEY (`idprodi`),
  ADD KEY `idfakultas` (`idfakultas`),
  ADD KEY `id_penilai` (`id_penilai`);

--
-- Indeks untuk tabel `prodi_pil`
--
ALTER TABLE `prodi_pil`
  ADD PRIMARY KEY (`id_prodi_pil`),
  ADD KEY `id_prodi` (`idprodi`),
  ADD KEY `prodi_pil_ibfk_2` (`idpendaftar`);

--
-- Indeks untuk tabel `raport`
--
ALTER TABLE `raport`
  ADD PRIMARY KEY (`id_raport`),
  ADD KEY `raport_ibfk_1` (`idpendaftar`);

--
-- Indeks untuk tabel `sertifikat`
--
ALTER TABLE `sertifikat`
  ADD PRIMARY KEY (`idsertifikat`),
  ADD KEY `sertifikat_ibfk_1` (`idpendaftar`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `fakultas`
--
ALTER TABLE `fakultas`
  MODIFY `idfakultas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `pendaftar`
--
ALTER TABLE `pendaftar`
  MODIFY `idpendaftar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `idpengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `prodi`
--
ALTER TABLE `prodi`
  MODIFY `idprodi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `prodi_pil`
--
ALTER TABLE `prodi_pil`
  MODIFY `id_prodi_pil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `raport`
--
ALTER TABLE `raport`
  MODIFY `id_raport` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT untuk tabel `sertifikat`
--
ALTER TABLE `sertifikat`
  MODIFY `idsertifikat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `pemilihan`
--
ALTER TABLE `pemilihan`
  ADD CONSTRAINT `pemilihan_ibfk_1` FOREIGN KEY (`idpendaftar`) REFERENCES `pendaftar` (`idpendaftar`);

--
-- Ketidakleluasaan untuk tabel `prodi`
--
ALTER TABLE `prodi`
  ADD CONSTRAINT `prodi_ibfk_1` FOREIGN KEY (`idfakultas`) REFERENCES `fakultas` (`idfakultas`),
  ADD CONSTRAINT `prodi_ibfk_2` FOREIGN KEY (`id_penilai`) REFERENCES `pengguna` (`idpengguna`);

--
-- Ketidakleluasaan untuk tabel `prodi_pil`
--
ALTER TABLE `prodi_pil`
  ADD CONSTRAINT `prodi_pil_ibfk_1` FOREIGN KEY (`idprodi`) REFERENCES `prodi` (`idprodi`),
  ADD CONSTRAINT `prodi_pil_ibfk_2` FOREIGN KEY (`idpendaftar`) REFERENCES `pendaftar` (`idpendaftar`);

--
-- Ketidakleluasaan untuk tabel `raport`
--
ALTER TABLE `raport`
  ADD CONSTRAINT `raport_ibfk_1` FOREIGN KEY (`idpendaftar`) REFERENCES `pendaftar` (`idpendaftar`);

--
-- Ketidakleluasaan untuk tabel `sertifikat`
--
ALTER TABLE `sertifikat`
  ADD CONSTRAINT `sertifikat_ibfk_1` FOREIGN KEY (`idpendaftar`) REFERENCES `pendaftar` (`idpendaftar`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
