<?php
    $title = "Retur Obat Pasien";
    $subtitle = $params["start"] . " s.d. " . $params["end"];
    $qGetRetur = "SELECT R.no_retur_jual, IF(Length(R.no_retur_jual)=19, Substring(R.no_retur_jual,1,17), '') AS regno, tgl_retur, nm_bangsal, R.no_rkm_medis, nm_pasien, DR.kode_brng, nama_brng, satuan, jml_retur, h_retur, subtotal, IfNull(png_jawab, '') AS png_jawab FROM returjual R INNER JOIN detreturjual DR ON R.no_retur_jual=DR.no_retur_jual INNER JOIN petugas PT ON R.nip=PT.nip INNER JOIN bangsal B ON R.kd_bangsal=B.kd_bangsal INNER JOIN pasien PS ON R.no_rkm_medis=PS.no_rkm_medis INNER JOIN databarang O ON DR.kode_brng=O.kode_brng INNER JOIN kodesatuan S ON DR.kode_sat=S.kode_sat LEFT JOIN reg_periksa RG ON Substring(R.no_retur_jual,1,17)=RG.no_rawat LEFT JOIN penjab PJ ON RG.kd_pj=PJ.kd_pj WHERE tgl_retur BETWEEN '" . $params["start"] . "' AND '" . $params["end"] . "' ORDER BY tgl_retur";
    $getRetur = mysqli_query($connect_app, $qGetRetur);
    $lstRetur = ""; $i = 0; $total = 0;
    while ($data = mysqli_fetch_assoc($getRetur)) {
        $lstRetur .= "<tr><td>" . ++$i . "</td><td>" . $data["no_retur_jual"] . "</td><td>" . $data["regno"] . "</td><td>" . $data["tgl_retur"] . "</td><td>" . $data["nm_pasien"] . " (" . $data["no_rkm_medis"] . ")</td><td>" . $data["png_jawab"] . "</td><td>" . $data["nm_bangsal"] . "</td><td>" . $data["kode_brng"] . "</td><td>" . $data["nama_brng"] . "</td><td>" . $data["satuan"] . "</td><td>" . number_format($data["jml_retur"], 0, ",", ".") . "</td><td>" . number_format($data["h_retur"], 0, ",", ".") . "</td><td>" . number_format($data["subtotal"], 0, ",", ".") . "</td></tr>";
        $total += $data["subtotal"];
    }
    $lstRetur = "<table><tr><th>No</th><th>No. Retur Jual</th><th>No. Rawat</th><th>Tanggal Retur</th><th>Pasien</th><th>Jenis Bayar</th><th>Tujuan Retur</th><th>Kode Barang</th><th>Nama Barang</th><th>Satuan</th><th>Jml. Retur</th><th>Harga Retur</th><th>Total</th></tr>" . $lstRetur . "<tr><td colspan='12' style='text-align: right'>Total Retur :</td><td>" . number_format($total, 0, ",", ".") . "</td></tr></table>";
    $table = $lstRetur;
?>