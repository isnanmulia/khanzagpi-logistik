<?php
    $title = "Penerimaan Obat & BHP";
    $subtitle = $params["start"] . " s.d. " . $params["end"];
    $qGetRcp = "SELECT P.no_faktur, tgl_pesan, no_faktur_supplier, nama_suplier, tgl_faktur, tgl_tempo, DP.kode_brng, nama_brng, no_pemesanan, SK.satuan AS satuan_kecil, SB.satuan AS satuan_besar, DP.isi, jumlah, h_pesan, subtotal, dis, dis2, besardis, total, CASE WHEN ppn<>0 THEN 1 ELSE 0 END AS is_ppn FROM pemesanan P INNER JOIN datasuplier S ON P.kode_suplier=S.kode_suplier INNER JOIN detailpesan DP ON P.no_faktur=DP.no_faktur INNER JOIN databarang B ON DP.kode_brng=B.kode_brng INNER JOIN kodesatuan SK ON DP.kode_sat=SK.kode_sat INNER JOIN kodesatuan SB ON DP.kode_satbesar=SB.kode_sat WHERE tgl_pesan BETWEEN '" . $params["start"] . "' AND '" . $params["end"] . "' ORDER BY P.no_faktur";
    $getRcp = mysqli_query($connect_app, $qGetRcp);
    $lstRcp = ""; $i = 0; $total = 0;
    while ($data = mysqli_fetch_assoc($getRcp)) {
        $lstRcp .= "<tr><td>" . ++$i . "</td><td>" . $data["no_faktur"] . "</td><td>" . $data["tgl_pesan"] . "</td><td class='str'>" . $data["no_faktur_supplier"] . "</td><td>" . $data["nama_suplier"] . "</td><td>" . $data["tgl_faktur"] . "</td><td>" . $data["tgl_tempo"] . "</td><td>" . $data["kode_brng"] . "</td><td>" . $data["nama_brng"] . "</td><td>" . $data["no_pemesanan"] . "</td><td>" . $data["satuan_kecil"] . "</td><td>" . $data["satuan_besar"] . "</td><td>" . number_format($data["isi"], 0, ",", ".") . "</td><td>" . ($data["is_ppn"] == 1 ? "Ya" : "Tidak") . "</td><td>" . number_format($data["jumlah"], 0, ",", ".") . "</td><td>" . number_format($data["h_pesan"], 0, ",", ".") . "</td><td>" . number_format($data["subtotal"], 0, ",", ".") . "</td><td>" . number_format($data["dis"], 2, ",", ".") . "</td><td>" . number_format($data["dis2"], 2, ",", ".") . "</td><td>" . number_format($data["besardis"], 0, ",", ".") . "</td><td>" . number_format($data["total"], 0, ",", ".") . "</td></tr>";
        $total += $data["total"];
    }
    $lstRcp .= "<tr><td colspan='20' style='text-align: right'>Total :</td><td>" . number_format($total, 0, ",", ".") . "</td></tr>";
    $lstRcp = "<table><tr><th>No</th><th>No. Penerimaan</th><th>Tanggal Penerimaan</th><th>No. Faktur</th><th>Supplier</th><th>Tanggal Faktur</th><th>Tanggal Jatuh Tempo</th><th>Kode Barang</th><th>Nama Barang</th><th>No. Pemesanan</th><th>SK</th><th>SB</th><th>Isi</th><th>PPN</th><th>Jumlah Datang</th><th>Harga Per SB</th><th>Subtotal</th><th>Diskon 1 (%)</th><th>Diskon 2 (%)</th><th>Total Diskon</th><th>Total</th></tr>" . $lstRcp . "</table>";
    $table = $lstRcp;
?>