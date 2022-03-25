/*************************************************************************
* D I S C L A I M E R                                                    *
* -------------------                                                    *
* Query yang ada di file ini tidak untuk langsung dieksekusi, karena     *
* pasti akan bentrok dengan tabel asli dari SIMRS Khanza                 *
* File SQL ini dibuat sebagai sarana perbandingan antara struktur tabel  *
* yang digunakan pada aplikasi khanzagpi-logistik dengan struktur tabel  *
* yang asli pada SIMRS Khanza                                            *
**************************************************************************/

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

-- membuang struktur untuk table sik.detailpesan
CREATE TABLE IF NOT EXISTS `detailpesan` (
  `no_faktur` varchar(20) NOT NULL,
  `kode_brng` varchar(15) NOT NULL DEFAULT '',
  `no_pemesanan` varchar(20) DEFAULT NULL,
  `kode_sat` char(4) DEFAULT NULL,
  `kode_satbesar` char(4) DEFAULT '-',
  `isi` double DEFAULT NULL,
  `jumlah` double DEFAULT NULL,
  `h_pesan` double DEFAULT NULL,
  `subtotal` double DEFAULT NULL,
  `dis` double NOT NULL,
  `dis2` double NOT NULL DEFAULT '0',
  `besardis` double NOT NULL,
  `total` double NOT NULL,
  `no_batch` varchar(20) NOT NULL,
  `jumlah2` double DEFAULT NULL,
  `kadaluarsa` date DEFAULT NULL,
  KEY `no_faktur` (`no_faktur`),
  KEY `kode_brng` (`kode_brng`),
  KEY `kode_sat` (`kode_sat`),
  KEY `jumlah` (`jumlah`),
  KEY `h_pesan` (`h_pesan`),
  KEY `subtotal` (`subtotal`),
  KEY `dis` (`dis`),
  KEY `besardis` (`besardis`),
  KEY `total` (`total`),
  KEY `jumlah2` (`jumlah2`),
  CONSTRAINT `detailpesan_ibfk_1` FOREIGN KEY (`kode_brng`) REFERENCES `databarang` (`kode_brng`) ON UPDATE CASCADE,
  CONSTRAINT `detailpesan_ibfk_2` FOREIGN KEY (`kode_sat`) REFERENCES `kodesatuan` (`kode_sat`) ON UPDATE CASCADE,
  CONSTRAINT `detailpesan_ibfk_3` FOREIGN KEY (`no_faktur`) REFERENCES `pemesanan` (`no_faktur`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.detail_pengajuan_barang_medis
CREATE TABLE IF NOT EXISTS `detail_pengajuan_barang_medis` (
  `no_pengajuan` varchar(20) NOT NULL,
  `kode_brng` varchar(15) NOT NULL DEFAULT '',
  `kode_sat` char(4) DEFAULT NULL,
  `jumlah` double DEFAULT NULL,
  `h_pengajuan` double DEFAULT NULL,
  `total` double NOT NULL,
  `status` enum('Proses Pengajuan','Disetujui','Ditolak') DEFAULT NULL,
  `jumlah2` double NOT NULL,
  `jumlah_disetujui` double DEFAULT NULL,
  KEY `kode_brng` (`kode_brng`),
  KEY `kode_sat` (`kode_sat`),
  KEY `no_pengajuan` (`no_pengajuan`),
  CONSTRAINT `detail_pengajuan_barang_medis_ibfk_1` FOREIGN KEY (`kode_brng`) REFERENCES `databarang` (`kode_brng`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detail_pengajuan_barang_medis_ibfk_2` FOREIGN KEY (`kode_sat`) REFERENCES `kodesatuan` (`kode_sat`) ON UPDATE CASCADE,
  CONSTRAINT `detail_pengajuan_barang_medis_ibfk_3` FOREIGN KEY (`no_pengajuan`) REFERENCES `pengajuan_barang_medis` (`no_pengajuan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.detail_pengajuan_barang_nonmedis
CREATE TABLE IF NOT EXISTS `detail_pengajuan_barang_nonmedis` (
  `no_pengajuan` varchar(20) NOT NULL,
  `kode_brng` varchar(15) NOT NULL DEFAULT '',
  `kode_sat` char(4) DEFAULT NULL,
  `jumlah` double DEFAULT NULL,
  `h_pengajuan` double DEFAULT NULL,
  `total` double NOT NULL,
  `status` enum('Proses Pengajuan','Disetujui','Ditolak') DEFAULT NULL,
  `jumlah_disetujui` double DEFAULT NULL,
  KEY `kode_brng` (`kode_brng`),
  KEY `kode_sat` (`kode_sat`),
  KEY `no_pengajuan` (`no_pengajuan`),
  CONSTRAINT `detail_pengajuan_barang_nonmedis_ibfk_1` FOREIGN KEY (`kode_brng`) REFERENCES `ipsrsbarang` (`kode_brng`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detail_pengajuan_barang_nonmedis_ibfk_2` FOREIGN KEY (`kode_sat`) REFERENCES `kodesatuan` (`kode_sat`) ON UPDATE CASCADE,
  CONSTRAINT `detail_pengajuan_barang_nonmedis_ibfk_3` FOREIGN KEY (`no_pengajuan`) REFERENCES `pengajuan_barang_nonmedis` (`no_pengajuan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.detail_permintaan_non_medis
CREATE TABLE IF NOT EXISTS `detail_permintaan_non_medis` (
  `no_permintaan` varchar(20) DEFAULT NULL,
  `kode_brng` varchar(15) DEFAULT NULL,
  `kode_sat` char(4) DEFAULT NULL,
  `jumlah` double DEFAULT NULL,
  `keterangan` varchar(150) DEFAULT NULL,
  `status` enum('Proses Permintaan','Sudah Diproses') DEFAULT NULL,
  KEY `no_permintaan` (`no_permintaan`),
  KEY `kode_brng` (`kode_brng`),
  KEY `kode_sat` (`kode_sat`),
  CONSTRAINT `detail_permintaan_non_medis_ibfk_1` FOREIGN KEY (`no_permintaan`) REFERENCES `permintaan_non_medis` (`no_permintaan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detail_permintaan_non_medis_ibfk_2` FOREIGN KEY (`kode_brng`) REFERENCES `ipsrsbarang` (`kode_brng`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detail_permintaan_non_medis_ibfk_3` FOREIGN KEY (`kode_sat`) REFERENCES `kodesatuan` (`kode_sat`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.detail_surat_pemesanan_medis
CREATE TABLE IF NOT EXISTS `detail_surat_pemesanan_medis` (
  `no_pemesanan` varchar(20) NOT NULL,
  `kode_brng` varchar(15) NOT NULL DEFAULT '',
  `no_pr_ref` varchar(15) DEFAULT NULL,
  `kode_sat` char(4) DEFAULT NULL,
  `kode_satbesar` char(4) DEFAULT '-',
  `isi` double DEFAULT NULL,
  `jumlah` double DEFAULT NULL,
  `h_pesan` double DEFAULT NULL,
  `subtotal` double DEFAULT NULL,
  `dis` double NOT NULL,
  `dis2` double NOT NULL DEFAULT '0',
  `besardis` double NOT NULL,
  `total` double NOT NULL,
  `status` enum('Baru','Proses Pesan','Sudah Datang') DEFAULT NULL,
  `jumlah2` double DEFAULT NULL,
  KEY `kode_brng` (`kode_brng`),
  KEY `kode_sat` (`kode_sat`),
  KEY `jumlah` (`jumlah`),
  KEY `h_pesan` (`h_pesan`),
  KEY `subtotal` (`subtotal`),
  KEY `dis` (`dis`),
  KEY `besardis` (`besardis`),
  KEY `total` (`total`),
  KEY `no_pemesanan` (`no_pemesanan`),
  CONSTRAINT `detail_surat_pemesanan_medis_ibfk_1` FOREIGN KEY (`kode_brng`) REFERENCES `databarang` (`kode_brng`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detail_surat_pemesanan_medis_ibfk_2` FOREIGN KEY (`kode_sat`) REFERENCES `kodesatuan` (`kode_sat`) ON UPDATE CASCADE,
  CONSTRAINT `detail_surat_pemesanan_medis_ibfk_3` FOREIGN KEY (`no_pemesanan`) REFERENCES `surat_pemesanan_medis` (`no_pemesanan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.detail_surat_pemesanan_non_medis
CREATE TABLE IF NOT EXISTS `detail_surat_pemesanan_non_medis` (
  `no_pemesanan` varchar(20) NOT NULL,
  `kode_brng` varchar(15) NOT NULL DEFAULT '',
  `no_pr_ref` varchar(15) DEFAULT NULL,
  `kode_sat` char(4) DEFAULT NULL,
  `kode_satbesar` char(4) DEFAULT '-',
  `isi` int(11) DEFAULT '1' COMMENT 'jmlh_satuan_kecil = jmlh_satuan_besar x isi',
  `jumlah` double DEFAULT NULL,
  `h_pesan` double DEFAULT NULL,
  `subtotal` double DEFAULT NULL,
  `dis` double NOT NULL,
  `dis2` double NOT NULL DEFAULT '0',
  `besardis` double NOT NULL,
  `total` double NOT NULL,
  `status` enum('Baru','Proses Pesan','Sudah Datang') DEFAULT NULL,
  KEY `kode_brng` (`kode_brng`),
  KEY `kode_sat` (`kode_sat`),
  KEY `jumlah` (`jumlah`),
  KEY `h_pesan` (`h_pesan`),
  KEY `subtotal` (`subtotal`),
  KEY `dis` (`dis`),
  KEY `besardis` (`besardis`),
  KEY `total` (`total`),
  KEY `no_pemesanan` (`no_pemesanan`),
  CONSTRAINT `detail_surat_pemesanan_non_medis_ibfk_1` FOREIGN KEY (`kode_brng`) REFERENCES `ipsrsbarang` (`kode_brng`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detail_surat_pemesanan_non_medis_ibfk_2` FOREIGN KEY (`kode_sat`) REFERENCES `kodesatuan` (`kode_sat`) ON UPDATE CASCADE,
  CONSTRAINT `detail_surat_pemesanan_non_medis_ibfk_3` FOREIGN KEY (`no_pemesanan`) REFERENCES `surat_pemesanan_non_medis` (`no_pemesanan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.ipsrsbarang
CREATE TABLE IF NOT EXISTS `ipsrsbarang` (
  `kode_brng` varchar(15) NOT NULL,
  `nama_brng` varchar(80) NOT NULL,
  `kode_sat` char(4) NOT NULL,
  `kode_satbesar` char(4) DEFAULT '-',
  `isi` double DEFAULT '1',
  `jenis` char(5) DEFAULT NULL,
  `stok` double NOT NULL,
  `dasar` double NOT NULL,
  `harga` double NOT NULL,
  `status` enum('0','1') NOT NULL,
  PRIMARY KEY (`kode_brng`),
  KEY `kode_sat` (`kode_sat`),
  KEY `nama_brng` (`nama_brng`),
  KEY `jenis` (`jenis`(1)),
  KEY `stok` (`stok`),
  KEY `harga` (`harga`),
  KEY `jenis_2` (`jenis`),
  CONSTRAINT `ipsrsbarang_ibfk_1` FOREIGN KEY (`kode_sat`) REFERENCES `kodesatuan` (`kode_sat`) ON UPDATE CASCADE,
  CONSTRAINT `ipsrsbarang_ibfk_2` FOREIGN KEY (`jenis`) REFERENCES `ipsrsjenisbarang` (`kd_jenis`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.ipsrsdetailpesan
CREATE TABLE IF NOT EXISTS `ipsrsdetailpesan` (
  `no_faktur` varchar(20) NOT NULL,
  `kode_brng` varchar(15) NOT NULL,
  `no_pemesanan` varchar(20) DEFAULT NULL,
  `kode_sat` char(4) NOT NULL,
  `kode_satbesar` char(4) DEFAULT '-',
  `isi` int(11) DEFAULT '1' COMMENT 'jmlh_satuan_kecil = jmlh_satuan_besar x isi',
  `jumlah` double NOT NULL,
  `harga` double NOT NULL,
  `subtotal` double NOT NULL,
  `dis` double NOT NULL,
  `dis2` double NOT NULL DEFAULT '0',
  `besardis` double NOT NULL,
  `total` double NOT NULL,
  KEY `no_faktur` (`no_faktur`),
  KEY `kode_brng` (`kode_brng`),
  KEY `kode_sat` (`kode_sat`),
  CONSTRAINT `ipsrsdetailpesan_ibfk_1` FOREIGN KEY (`no_faktur`) REFERENCES `ipsrspemesanan` (`no_faktur`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ipsrsdetailpesan_ibfk_2` FOREIGN KEY (`kode_brng`) REFERENCES `ipsrsbarang` (`kode_brng`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ipsrsdetailpesan_ibfk_3` FOREIGN KEY (`kode_sat`) REFERENCES `kodesatuan` (`kode_sat`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.ipsrsopname
CREATE TABLE IF NOT EXISTS `ipsrsopname` (
  `kode_brng` varchar(15) NOT NULL,
  `h_beli` double DEFAULT NULL,
  `tanggal` date NOT NULL,
  `stok` int(11) NOT NULL,
  `real` int(11) NOT NULL,
  `selisih` int(11) NOT NULL,
  `nomihilang` double NOT NULL,
  `lebih` double NOT NULL,
  `nomilebih` double NOT NULL,
  `keterangan` varchar(60) NOT NULL,
  `kd_bangsal` char(5) NOT NULL,
  `no_batch` varchar(20) NOT NULL DEFAULT '',
  `no_faktur` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`kode_brng`,`tanggal`,`kd_bangsal`),
  KEY `stok` (`stok`),
  KEY `real` (`real`),
  KEY `selisih` (`selisih`),
  KEY `nomihilang` (`nomihilang`),
  KEY `keterangan` (`keterangan`),
  KEY `kode_brng` (`kode_brng`),
  CONSTRAINT `ipsrsopname_ibfk_1` FOREIGN KEY (`kode_brng`) REFERENCES `ipsrsbarang` (`kode_brng`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.ipsrspembelian
CREATE TABLE IF NOT EXISTS `ipsrspembelian` (
  `no_faktur` varchar(15) NOT NULL,
  `kode_suplier` char(10) NOT NULL,
  `nip` varchar(20) NOT NULL,
  `tgl_beli` date NOT NULL,
  `subtotal` double NOT NULL,
  `potongan` double NOT NULL,
  `total` double NOT NULL,
  `ppn` double DEFAULT NULL,
  `meterai` double DEFAULT NULL,
  `tagihan` double DEFAULT NULL,
  `kd_rek` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`no_faktur`),
  KEY `kode_suplier` (`kode_suplier`),
  KEY `nip` (`nip`),
  KEY `tgl_beli` (`tgl_beli`),
  KEY `subtotal` (`subtotal`),
  KEY `potongan` (`potongan`),
  KEY `total` (`total`),
  KEY `ipsrspembelian_ibfk_5` (`kd_rek`),
  CONSTRAINT `ipsrspembelian_ibfk_4` FOREIGN KEY (`nip`) REFERENCES `petugas` (`nip`) ON UPDATE CASCADE,
  CONSTRAINT `ipsrspembelian_ibfk_5` FOREIGN KEY (`kd_rek`) REFERENCES `rekening` (`kd_rek`) ON UPDATE CASCADE,
  CONSTRAINT `ipsrspembelian_ibfk_6` FOREIGN KEY (`kode_suplier`) REFERENCES `ipsrssuplier` (`kode_suplier`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.ipsrspemesanan
CREATE TABLE IF NOT EXISTS `ipsrspemesanan` (
  `no_faktur` varchar(20) NOT NULL,
  `no_order` varchar(20) NOT NULL,
  `no_faktur_supplier` varchar(20) DEFAULT NULL,
  `kode_suplier` char(10) DEFAULT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `tgl_pesan` date DEFAULT NULL,
  `tgl_faktur` date DEFAULT NULL,
  `tgl_tempo` date DEFAULT NULL,
  `total1` double NOT NULL,
  `potongan` double NOT NULL,
  `total2` double NOT NULL,
  `ppn` double NOT NULL,
  `meterai` double DEFAULT NULL,
  `tagihan` double NOT NULL,
  `kd_bangsal` char(5) NOT NULL DEFAULT '-',
  `status` enum('Sudah Dibayar','Belum Dibayar','Belum Lunas') DEFAULT NULL,
  `catatan` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`no_faktur`),
  KEY `kode_suplier` (`kode_suplier`),
  KEY `nip` (`nip`),
  CONSTRAINT `ipsrspemesanan_ibfk_1` FOREIGN KEY (`kode_suplier`) REFERENCES `ipsrssuplier` (`kode_suplier`) ON UPDATE CASCADE,
  CONSTRAINT `ipsrspemesanan_ibfk_2` FOREIGN KEY (`nip`) REFERENCES `petugas` (`nip`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.ipsrssuplier
CREATE TABLE IF NOT EXISTS `ipsrssuplier` (
  `kode_suplier` char(10) NOT NULL,
  `nama_suplier` varchar(50) DEFAULT NULL,
  `alamat` varchar(50) DEFAULT NULL,
  `kota` varchar(20) DEFAULT NULL,
  `no_telp` varchar(13) DEFAULT NULL,
  `nama_bank` varchar(30) DEFAULT NULL,
  `rekening` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`kode_suplier`),
  KEY `nama_suplier` (`nama_suplier`),
  KEY `alamat` (`alamat`),
  KEY `kota` (`kota`),
  KEY `no_telp` (`no_telp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.pemesanan
CREATE TABLE IF NOT EXISTS `pemesanan` (
  `no_faktur` varchar(20) NOT NULL,
  `no_order` varchar(20) NOT NULL,
  `no_faktur_supplier` varchar(20) DEFAULT NULL,
  `kode_suplier` char(5) DEFAULT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `tgl_pesan` date DEFAULT NULL,
  `tgl_faktur` date DEFAULT NULL,
  `tgl_tempo` date DEFAULT NULL,
  `total1` double NOT NULL,
  `potongan` double NOT NULL,
  `total2` double NOT NULL,
  `ppn` double NOT NULL,
  `meterai` double DEFAULT NULL,
  `tagihan` double NOT NULL,
  `kd_bangsal` char(5) NOT NULL,
  `status` enum('Sudah Dibayar','Belum Dibayar','Belum Lunas') DEFAULT NULL,
  `catatan` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`no_faktur`),
  KEY `kode_suplier` (`kode_suplier`),
  KEY `nip` (`nip`),
  KEY `kd_bangsal` (`kd_bangsal`),
  CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`kode_suplier`) REFERENCES `datasuplier` (`kode_suplier`) ON UPDATE CASCADE,
  CONSTRAINT `pemesanan_ibfk_2` FOREIGN KEY (`nip`) REFERENCES `petugas` (`nip`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pemesanan_ibfk_3` FOREIGN KEY (`kd_bangsal`) REFERENCES `bangsal` (`kd_bangsal`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.pengajuan_barang_medis
CREATE TABLE IF NOT EXISTS `pengajuan_barang_medis` (
  `no_pengajuan` varchar(20) NOT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `status` enum('Proses Pengajuan','Disetujui','Ditolak','Pengajuan') DEFAULT NULL,
  `keterangan` varchar(150) DEFAULT NULL,
  `tanggal_disetujui` date DEFAULT NULL,
  PRIMARY KEY (`no_pengajuan`),
  KEY `nip` (`nip`),
  CONSTRAINT `pengajuan_barang_medis_ibfk_1` FOREIGN KEY (`nip`) REFERENCES `pegawai` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.pengajuan_barang_nonmedis
CREATE TABLE IF NOT EXISTS `pengajuan_barang_nonmedis` (
  `no_pengajuan` varchar(20) NOT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `status` enum('Proses Pengajuan','Disetujui','Ditolak','Pengajuan') DEFAULT NULL,
  `keterangan` varchar(150) DEFAULT NULL,
  `tanggal_disetujui` date DEFAULT NULL,
  PRIMARY KEY (`no_pengajuan`),
  KEY `nip` (`nip`),
  CONSTRAINT `pengajuan_barang_nonmedis_ibfk_1` FOREIGN KEY (`nip`) REFERENCES `pegawai` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.permintaan_non_medis
CREATE TABLE IF NOT EXISTS `permintaan_non_medis` (
  `no_permintaan` varchar(20) NOT NULL,
  `ruang` varchar(50) DEFAULT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `kd_bangsal` char(5) NOT NULL DEFAULT '-',
  `kd_bangsaltujuan` char(5) NOT NULL DEFAULT '-',
  `status` enum('Baru','Disetujui','Tidak Disetujui','Disetujui Sebagian','Dikonfirmasi') DEFAULT NULL,
  PRIMARY KEY (`no_permintaan`),
  KEY `nip` (`nip`),
  KEY `kd_bangsal` (`kd_bangsal`),
  KEY `kd_bangsaltujuan` (`kd_bangsaltujuan`),
  CONSTRAINT `permintaan_non_medis_ibfk_1` FOREIGN KEY (`nip`) REFERENCES `pegawai` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.surat_pemesanan_medis
CREATE TABLE IF NOT EXISTS `surat_pemesanan_medis` (
  `no_pemesanan` varchar(20) NOT NULL,
  `kode_suplier` char(5) DEFAULT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `total1` double NOT NULL,
  `potongan` double NOT NULL,
  `total2` double NOT NULL,
  `ppn` double DEFAULT NULL,
  `meterai` double DEFAULT NULL,
  `tagihan` double DEFAULT NULL,
  `status` enum('Baru','Proses Pesan','Sudah Datang') DEFAULT NULL,
  `catatan` varchar(1000) DEFAULT NULL,
  `kadaluarsa` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`no_pemesanan`),
  KEY `kode_suplier` (`kode_suplier`),
  KEY `surat_pemesanan_medis_ibfk_2` (`nip`),
  CONSTRAINT `surat_pemesanan_medis_ibfk_1` FOREIGN KEY (`kode_suplier`) REFERENCES `datasuplier` (`kode_suplier`) ON UPDATE CASCADE,
  CONSTRAINT `surat_pemesanan_medis_ibfk_2` FOREIGN KEY (`nip`) REFERENCES `pegawai` (`nik`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table sik.surat_pemesanan_non_medis
CREATE TABLE IF NOT EXISTS `surat_pemesanan_non_medis` (
  `no_pemesanan` varchar(20) NOT NULL,
  `kode_suplier` char(10) DEFAULT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `subtotal` double NOT NULL,
  `potongan` double NOT NULL,
  `total` double NOT NULL,
  `ppn` double DEFAULT NULL,
  `meterai` double DEFAULT NULL,
  `tagihan` double DEFAULT NULL,
  `status` enum('Baru','Proses Pesan','Sudah Datang') DEFAULT NULL,
  `catatan` varchar(1000) DEFAULT NULL,
  `kadaluarsa` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`no_pemesanan`),
  KEY `kode_suplier` (`kode_suplier`),
  KEY `nip` (`nip`),
  CONSTRAINT `surat_pemesanan_non_medis_ibfk_1` FOREIGN KEY (`kode_suplier`) REFERENCES `ipsrssuplier` (`kode_suplier`) ON UPDATE CASCADE,
  CONSTRAINT `surat_pemesanan_non_medis_ibfk_2` FOREIGN KEY (`nip`) REFERENCES `pegawai` (`nik`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Pengeluaran data tidak dipilih.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
