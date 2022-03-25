/***************************************************************************
* D I S C L A I M E R                                                      *
* -------------------                                                      *
* Query yang ada di file ini dapat langsung dieksekusi, karena tabel-tabel *
* yang tercantum pada file ini tidak terdapat pada database original       *
* SIMRS Khanza & hanya digunakan pada aplikasi khanzagpi-logistik          *
***************************************************************************/

-- --------------------------------------------------------
-- Host:                         192.168.xxx.xxx
-- Versi server:                 10.1.38-MariaDB - Source distribution
-- OS Server:                    Linux
-- HeidiSQL Versi:               11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- membuang struktur untuk table sik.gpi_gudangbarangipsrs
CREATE TABLE IF NOT EXISTS `gpi_gudangbarangipsrs` (
  `kode_brng` varchar(15) NOT NULL,
  `kd_bangsal` char(5) NOT NULL DEFAULT '',
  `stok` double DEFAULT NULL,
  `no_batch` varchar(20) NOT NULL DEFAULT '',
  `no_faktur` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`kode_brng`,`kd_bangsal`,`no_batch`,`no_faktur`) USING BTREE,
  KEY `kode_brng` (`kode_brng`) USING BTREE,
  KEY `stok` (`stok`) USING BTREE,
  KEY `kd_bangsal` (`kd_bangsal`) USING BTREE,
  CONSTRAINT `gpi_gudangbarangipsrs_ibfk_1` FOREIGN KEY (`kd_bangsal`) REFERENCES `bangsal` (`kd_bangsal`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `gpi_gudangbarangipsrs_ibfk_2` FOREIGN KEY (`kode_brng`) REFERENCES `ipsrsbarang` (`kode_brng`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.gpi_jadwal_stokopname_medis
CREATE TABLE IF NOT EXISTS `gpi_jadwal_stokopname_medis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tgl_mulai` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `daf_bangsal` varchar(300) NOT NULL DEFAULT '-',
  `keterangan` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.gpi_jadwal_stokopname_nonmedis
CREATE TABLE IF NOT EXISTS `gpi_jadwal_stokopname_nonmedis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tgl_mulai` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `daf_bangsal` varchar(100) NOT NULL DEFAULT '-',
  `keterangan` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.gpi_log_penomoran
CREATE TABLE IF NOT EXISTS `gpi_log_penomoran` (
  `kode` varchar(10) NOT NULL,
  `tahun` int(11) NOT NULL,
  `bulan` int(11) NOT NULL,
  `tanggal` int(11) NOT NULL,
  `no_terakhir` int(11) NOT NULL,
  `dibuat_oleh` varchar(10) NOT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `diupdate_oleh` varchar(10) NOT NULL,
  `diupdate_pada` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`kode`,`tahun`,`bulan`,`tanggal`),
  CONSTRAINT `gpi_log_penomoran_ibfk_1` FOREIGN KEY (`kode`) REFERENCES `gpi_master_penomoran` (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.gpi_master_penomoran
CREATE TABLE IF NOT EXISTS `gpi_master_penomoran` (
  `kode` varchar(10) NOT NULL,
  `keterangan` varchar(50) DEFAULT NULL,
  `format_nomor` varchar(15) NOT NULL,
  `periode_reset` enum('H','B','T') NOT NULL,
  `dibuat_oleh` varchar(10) NOT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `diupdate_oleh` varchar(10) NOT NULL,
  `diupdate_pada` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Membuang data untuk tabel sik.gpi_master_penomoran: ~2 rows (lebih kurang)
/*!40000 ALTER TABLE `gpi_master_penomoran` DISABLE KEYS */;
INSERT IGNORE INTO `gpi_master_penomoran` (`kode`, `keterangan`, `format_nomor`, `periode_reset`, `dibuat_oleh`, `dibuat_pada`, `diupdate_oleh`, `diupdate_pada`) VALUES
	('APS', 'Nomor untuk pasien APS', 'APS-YYMMDDXXXX', 'H', 'isnanmulia', '2019-09-26 14:55:55', 'isnanmulia', '2019-09-26 14:55:55'),
	('JIT', 'Nomor untuk obat JIT', 'JITYYMMDDXXXX', 'H', 'isnanmulia', '2020-08-13 11:32:41', 'isnanmulia', '2020-08-13 10:30:00'),
	('PHM', 'Nomor untuk perubahan harga barang medis', 'PHM-YYMMXXX', 'B', 'isnanmulia', '2019-11-15 08:36:56', 'isnanmulia', '2019-11-15 08:36:56'),
	('PHN', 'Nomor untuk perubahan harga barang non medis', 'PHN-YYMMXXX', 'B', 'isnanmulia', '2020-04-24 11:32:02', 'isnanmulia', '2020-04-24 11:32:02');
/*!40000 ALTER TABLE `gpi_master_penomoran` ENABLE KEYS */;

-- membuang struktur untuk table sik.gpi_minmax_nonmedis
CREATE TABLE IF NOT EXISTS `gpi_minmax_nonmedis` (
  `kode_brng` varchar(15) NOT NULL,
  `kd_bangsal` varchar(5) NOT NULL,
  `min_stok` int(11) NOT NULL DEFAULT '0',
  `max_stok` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_brng`,`kd_bangsal`),
  KEY `kd_bangsal` (`kd_bangsal`),
  CONSTRAINT `gpi_minmax_nonmedis_ibfk_1` FOREIGN KEY (`kode_brng`) REFERENCES `ipsrsbarang` (`kode_brng`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `gpi_minmax_nonmedis_ibfk_2` FOREIGN KEY (`kd_bangsal`) REFERENCES `bangsal` (`kd_bangsal`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.gpi_minmax_obat
CREATE TABLE IF NOT EXISTS `gpi_minmax_obat` (
  `kode_brng` varchar(15) NOT NULL,
  `kd_bangsal` varchar(5) NOT NULL,
  `min_stok` int(11) NOT NULL DEFAULT '0',
  `max_stok` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_brng`,`kd_bangsal`),
  KEY `kd_bangsal` (`kd_bangsal`),
  CONSTRAINT `gpi_minmax_obat_ibfk_1` FOREIGN KEY (`kode_brng`) REFERENCES `databarang` (`kode_brng`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `gpi_minmax_obat_ibfk_2` FOREIGN KEY (`kd_bangsal`) REFERENCES `bangsal` (`kd_bangsal`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.gpi_mutasibarangipsrs
CREATE TABLE IF NOT EXISTS `gpi_mutasibarangipsrs` (
  `kode_brng` varchar(15) NOT NULL,
  `no_permintaan` varchar(20) DEFAULT NULL,
  `jml` double NOT NULL,
  `harga` double NOT NULL,
  `kd_bangsaldari` char(5) NOT NULL,
  `kd_bangsalke` char(5) NOT NULL,
  `tanggal` datetime NOT NULL,
  `keterangan` varchar(60) NOT NULL,
  `no_batch` varchar(20) NOT NULL,
  `no_faktur` varchar(20) NOT NULL,
  PRIMARY KEY (`kode_brng`,`kd_bangsaldari`,`kd_bangsalke`,`tanggal`,`no_batch`,`no_faktur`) USING BTREE,
  KEY `kd_bangsaldari` (`kd_bangsaldari`) USING BTREE,
  KEY `kd_bangsalke` (`kd_bangsalke`) USING BTREE,
  KEY `jml` (`jml`) USING BTREE,
  KEY `keterangan` (`keterangan`) USING BTREE,
  KEY `kode_brng` (`kode_brng`) USING BTREE,
  CONSTRAINT `gpi_mutasibarangipsrs_ibfk_1` FOREIGN KEY (`kode_brng`) REFERENCES `ipsrsbarang` (`kode_brng`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `gpi_mutasibarangipsrs_ibfk_2` FOREIGN KEY (`kd_bangsaldari`) REFERENCES `bangsal` (`kd_bangsal`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `gpi_mutasibarangipsrs_ibfk_3` FOREIGN KEY (`kd_bangsalke`) REFERENCES `bangsal` (`kd_bangsal`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.gpi_riwayat_barang_nonmedis
CREATE TABLE IF NOT EXISTS `gpi_riwayat_barang_nonmedis` (
  `kode_brng` varchar(15) DEFAULT NULL,
  `stok_awal` double DEFAULT NULL,
  `masuk` double DEFAULT NULL,
  `keluar` double DEFAULT NULL,
  `stok_akhir` double DEFAULT NULL,
  `posisi` enum('Pengadaan','Penerimaan','Piutang','Retur Beli','Retur Piutang','Mutasi','Opname','Stok Keluar','Hibah','Salah Input') DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jam` time DEFAULT NULL,
  `petugas` varchar(20) DEFAULT NULL,
  `kd_bangsal` char(5) DEFAULT NULL,
  `status` enum('Simpan','Hapus') DEFAULT NULL,
  `no_batch` varchar(20) NOT NULL,
  `no_faktur` varchar(20) NOT NULL,
  KEY `kode_brng` (`kode_brng`) USING BTREE,
  KEY `kd_bangsal` (`kd_bangsal`) USING BTREE,
  CONSTRAINT `gpi_riwayat_barang_nonmedis_ibfk_1` FOREIGN KEY (`kode_brng`) REFERENCES `ipsrsbarang` (`kode_brng`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `gpi_riwayat_barang_nonmedis_ibfk_2` FOREIGN KEY (`kd_bangsal`) REFERENCES `bangsal` (`kd_bangsal`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.gpi_riwayat_harga_nonmedis
CREATE TABLE IF NOT EXISTS `gpi_riwayat_harga_nonmedis` (
  `no_ref` varchar(15) NOT NULL,
  `kode_brng` varchar(15) NOT NULL,
  `tanggal_efektif` date NOT NULL,
  `harga_sat_kecil` double NOT NULL,
  `harga_sat_besar` double NOT NULL,
  `dibuat_oleh` varchar(10) NOT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`no_ref`,`kode_brng`),
  KEY `kode_brng` (`kode_brng`),
  CONSTRAINT `gpi_riwayat_harga_nonmedis_ibfk_1` FOREIGN KEY (`kode_brng`) REFERENCES `ipsrsbarang` (`kode_brng`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.gpi_riwayat_harga_obat
CREATE TABLE IF NOT EXISTS `gpi_riwayat_harga_obat` (
  `no_ref` varchar(15) NOT NULL,
  `kode_brng` varchar(15) NOT NULL,
  `tanggal_efektif` date NOT NULL,
  `harga_sat_kecil` double NOT NULL,
  `harga_sat_besar` double NOT NULL,
  `dibuat_oleh` varchar(10) NOT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`no_ref`,`kode_brng`),
  KEY `FK_gpi_riwayat_harga_obat_databarang` (`kode_brng`),
  CONSTRAINT `FK_gpi_riwayat_harga_obat_databarang` FOREIGN KEY (`kode_brng`) REFERENCES `databarang` (`kode_brng`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.gpi_setting
CREATE TABLE IF NOT EXISTS `gpi_setting` (
  `approver_po` varchar(50) DEFAULT NULL,
  `approver_prm` varchar(50) DEFAULT NULL,
  `approver_prnm` varchar(50) DEFAULT NULL,
  `kadaluarsa_pom` int(11) DEFAULT NULL,
  `kadaluarsa_ponm` int(11) DEFAULT NULL,
  `diupdate_oleh` varchar(10) NOT NULL,
  `diupdate_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.gpi_userbangsal
CREATE TABLE IF NOT EXISTS `gpi_userbangsal` (
  `id_user` varchar(10) NOT NULL,
  `-` enum('true','false') DEFAULT NULL,
  `A_02` enum('true','false') DEFAULT NULL,
  `A_03` enum('true','false') DEFAULT NULL,
  `A_04` enum('true','false') DEFAULT NULL,
  `A_05` enum('true','false') DEFAULT NULL,
  `A_06` enum('true','false') DEFAULT NULL,
  `A_07` enum('true','false') DEFAULT NULL,
  `A_08` enum('true','false') DEFAULT NULL,
  `A_09` enum('true','false') DEFAULT NULL,
  `A_10` enum('true','false') DEFAULT NULL,
  `A_11` enum('true','false') DEFAULT NULL,
  `A_12` enum('true','false') DEFAULT NULL,
  `A_13` enum('true','false') DEFAULT NULL,
  `A_14` enum('true','false') DEFAULT NULL,
  `A_15` enum('true','false') DEFAULT NULL,
  `A_19` enum('true','false') DEFAULT NULL,
  `A_21` enum('true','false') DEFAULT NULL,
  `A_22` enum('true','false') DEFAULT NULL,
  `A_23` enum('true','false') DEFAULT NULL,
  `A_24` enum('true','false') DEFAULT NULL,
  `A_25` enum('true','false') DEFAULT NULL,
  `A_26` enum('true','false') DEFAULT NULL,
  `A_27` enum('true','false') DEFAULT NULL,
  `A_28` enum('true','false') DEFAULT NULL,
  `B_01` enum('true','false') DEFAULT NULL,
  `B_02` enum('true','false') DEFAULT NULL,
  `B_03` enum('true','false') DEFAULT NULL,
  `B_04` enum('true','false') DEFAULT NULL,
  `B_06` enum('true','false') DEFAULT NULL,
  `B_07` enum('true','false') DEFAULT NULL,
  `B_08` enum('true','false') DEFAULT NULL,
  `B_09` enum('true','false') DEFAULT NULL,
  `B_10` enum('true','false') DEFAULT NULL,
  `B_11` enum('true','false') DEFAULT NULL,
  `B_12` enum('true','false') DEFAULT NULL,
  `B_13` enum('true','false') DEFAULT NULL,
  `B_14` enum('true','false') DEFAULT NULL,
  `B_15` enum('true','false') DEFAULT NULL,
  `B_17` enum('true','false') DEFAULT NULL,
  `C_01` enum('true','false') DEFAULT NULL,
  `C_02` enum('true','false') DEFAULT NULL,
  `C_03` enum('true','false') DEFAULT NULL,
  `C_04` enum('true','false') DEFAULT NULL,
  `C_05` enum('true','false') DEFAULT NULL,
  `C_06` enum('true','false') DEFAULT NULL,
  `C_07` enum('true','false') DEFAULT NULL,
  `C_08` enum('true','false') DEFAULT NULL,
  `C_09` enum('true','false') DEFAULT NULL,
  `C_10` enum('true','false') DEFAULT NULL,
  `C_11` enum('true','false') DEFAULT NULL,
  `C_12` enum('true','false') DEFAULT NULL,
  `C_13` enum('true','false') DEFAULT NULL,
  `C_14` enum('true','false') DEFAULT NULL,
  `C_15` enum('true','false') DEFAULT NULL,
  `C_16` enum('true','false') DEFAULT NULL,
  `C_17` enum('true','false') DEFAULT NULL,
  `C_18` enum('true','false') DEFAULT NULL,
  `C_19` enum('true','false') DEFAULT NULL,
  `D_01` enum('true','false') DEFAULT NULL,
  `D_04` enum('true','false') DEFAULT NULL,
  `D_05` enum('true','false') DEFAULT NULL,
  `D_07` enum('true','false') DEFAULT NULL,
  `D_08` enum('true','false') DEFAULT NULL,
  `D_09` enum('true','false') DEFAULT NULL,
  `D_10` enum('true','false') DEFAULT NULL,
  `D_11` enum('true','false') DEFAULT NULL,
  `D_12` enum('true','false') DEFAULT NULL,
  `D_13` enum('true','false') DEFAULT NULL,
  `D_14` enum('true','false') DEFAULT NULL,
  `D_15` enum('true','false') DEFAULT NULL,
  `D_16` enum('true','false') DEFAULT NULL,
  `D_17` enum('true','false') DEFAULT NULL,
  `D_18` enum('true','false') DEFAULT NULL,
  `D_19` enum('true','false') DEFAULT NULL,
  `D_21` enum('true','false') DEFAULT NULL,
  `D_23` enum('true','false') DEFAULT NULL,
  `D_27` enum('true','false') DEFAULT NULL,
  `D_30` enum('true','false') DEFAULT NULL,
  `D_33` enum('true','false') DEFAULT NULL,
  `D_40` enum('true','false') DEFAULT NULL,
  `D_42` enum('true','false') DEFAULT NULL,
  `D_45` enum('true','false') DEFAULT NULL,
  `D_46` enum('true','false') DEFAULT NULL,
  `D_47` enum('true','false') DEFAULT NULL,
  `D_53` enum('true','false') DEFAULT NULL,
  `D_55` enum('true','false') DEFAULT NULL,
  `D_56` enum('true','false') DEFAULT NULL,
  `D_57` enum('true','false') DEFAULT NULL,
  `D_58` enum('true','false') DEFAULT NULL,
  `D_59` enum('true','false') DEFAULT NULL,
  `D_60` enum('true','false') DEFAULT NULL,
  `E_01` enum('true','false') DEFAULT NULL,
  `FRMRI` enum('true','false') DEFAULT NULL,
  `FRMRJ` enum('true','false') DEFAULT NULL,
  `FRMSM` enum('true','false') DEFAULT NULL,
  `GUDMD` enum('true','false') DEFAULT NULL,
  `GUDNM` enum('true','false') DEFAULT NULL,
  `NSANK` enum('true','false') DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.khanzagpi_tracker
CREATE TABLE IF NOT EXISTS `khanzagpi_tracker` (
  `nip` varchar(20) NOT NULL,
  `tgl_login` date NOT NULL,
  `jam_login` time NOT NULL,
  `alamat_IP` varchar(15) NOT NULL,
  PRIMARY KEY (`nip`,`tgl_login`,`jam_login`,`alamat_IP`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.khanzagpi_trackersql
CREATE TABLE IF NOT EXISTS `khanzagpi_trackersql` (
  `tanggal` datetime NOT NULL,
  `sqle` text NOT NULL,
  `usere` varchar(20) NOT NULL,
  `alamat_IP` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
