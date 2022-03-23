<?php
    $title = "Pengajuan Obat & BHP";
    $subtitle = $params["start"] . " s.d. " . $params["end"];
    $qGetPR = "SELECT PBM.no_pengajuan, PBM.tanggal, nama, DPBM.kode_brng, nama_brng, SK.satuan AS satuan_kecil, SB.satuan AS satuan_besar, B.isi, DPBM.jumlah, IfNull(jumlah_disetujui, DPBM.jumlah) AS jumlah_disetujui, PBM.status, DPBM.status AS status_dtl, IfNull(no_pemesanan, '-') AS no_pemesanan FROM pengajuan_barang_medis PBM INNER JOIN detail_pengajuan_barang_medis DPBM ON PBM.no_pengajuan=DPBM.no_pengajuan INNER JOIN petugas PT ON PBM.nip=PT.nip INNER JOIN databarang B ON B.kode_brng=DPBM.kode_brng INNER JOIN kodesatuan SK ON B.kode_sat=SK.kode_sat INNER JOIN kodesatuan SB ON B.kode_satbesar=SB.kode_sat LEFT JOIN detail_surat_pemesanan_medis DSPM ON PBM.no_pengajuan=DSPM.no_pr_ref WHERE PBM.tanggal BETWEEN '" . $params["start"] . "' AND '" . $params["end"] . "' ORDER BY PBM.no_pengajuan";
    $getPR = mysqli_query($connect_app, $qGetPR);
    $lstPR = ""; $i = 0;
    while ($data = mysqli_fetch_assoc($getPR)) {
        if ($data["status"] == "Pengajuan" && $data["status_dtl"] == "Proses Pengajuan") $status = "Pengajuan"; else $status = $data["status_dtl"];
        $lstPR .= "<tr><td>" . ++$i . "</td><td>" . $data["no_pengajuan"] . "</td><td>" . $data["tanggal"] . "</td><td>" . $data["nama"] . "</td><td>" . $data["kode_brng"] . "</td><td>" . $data["nama_brng"] . "</td><td>" . $data["satuan_kecil"] . "</td><td>" . $data["satuan_besar"] . "</td><td>" . $data["isi"] . "</td><td>" . $data["jumlah"] . "</td><td>" . ($data["status"] != "Proses Pengajuan" ? $data["jumlah_disetujui"] : "-") . "</td><td>" . $status . "</td><td>" . $data["no_pemesanan"] . "</td></tr>";
    }
    $lstPR = "<table><tr><th>No</th><th>No. Pengajuan</th><th>Tanggal</th><th>Petugas</th><th>Kode Barang</th><th>Nama Barang</th><th>SK</th><th>SB</th><th>Isi</th><th>Jml. Pengajuan</th><th>Jml. Disetujui</th><th>Status</th><th>No. Pemesanan</th></tr>" . $lstPR . "</table>";
    $table = $lstPR;
?>