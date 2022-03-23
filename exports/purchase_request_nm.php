<?php
    $title = "Pengajuan Barang Non Medis";
    $subtitle = $params["start"] . " s.d. " . $params["end"];
    $qGetPR = "SELECT PBN.no_pengajuan, PBN.tanggal, nama, DPBN.kode_brng, nama_brng, SK.satuan AS satuan_kecil, SB.satuan AS satuan_besar, B.isi, DPBN.jumlah, IfNull(jumlah_disetujui, DPBN.jumlah) AS jumlah_disetujui, PBN.status, DPBN.status AS status_dtl, IfNull(no_pemesanan, '-') AS no_pemesanan FROM pengajuan_barang_nonmedis PBN INNER JOIN detail_pengajuan_barang_nonmedis DPBN ON PBN.no_pengajuan=DPBN.no_pengajuan INNER JOIN petugas PT ON PBN.nip=PT.nip INNER JOIN ipsrsbarang B ON B.kode_brng=DPBN.kode_brng INNER JOIN kodesatuan SK ON B.kode_sat=SK.kode_sat INNER JOIN kodesatuan SB ON B.kode_satbesar=SB.kode_sat LEFT JOIN detail_surat_pemesanan_non_medis DSPN ON PBN.no_pengajuan=DSPN.no_pr_ref WHERE PBN.tanggal BETWEEN '" . $params["start"] . "' AND '" . $params["end"] . "' ORDER BY PBN.no_pengajuan";
    $getPR = mysqli_query($connect_app, $qGetPR);
    $lstPR = ""; $i = 0;
    while ($data = mysqli_fetch_assoc($getPR)) {
        if ($data["status"] == "Pengajuan" && $data["status_dtl"] == "Proses Pengajuan") $status = "Pengajuan"; else $status = $data["status_dtl"];
        $lstPR .= "<tr><td>" . ++$i . "</td><td>" . $data["no_pengajuan"] . "</td><td>" . $data["tanggal"] . "</td><td>" . $data["nama"] . "</td><td>" . $data["kode_brng"] . "</td><td>" . $data["nama_brng"] . "</td><td>" . $data["satuan_kecil"] . "</td><td>" . $data["satuan_besar"] . "</td><td>" . $data["isi"] . "</td><td>" . $data["jumlah"] . "</td><td>" . ($data["status"] != "Proses Pengajuan" ? $data["jumlah_disetujui"] : "-") . "</td><td>" . $status . "</td><td>" . $data["no_pemesanan"] . "</td></tr>";
    }
    $lstPR = "<table><tr><th>No</th><th>No. Pengajuan</th><th>Tanggal</th><th>Petugas</th><th>Kode Barang</th><th>Nama Barang</th><th>SK</th><th>SB</th><th>Isi</th><th>Jml. Pengajuan</th><th>Jml. Disetujui</th><th>Status</th><th>No. Pemesanan</th></tr>" . $lstPR . "</table>";
    $table = $lstPR;
?>