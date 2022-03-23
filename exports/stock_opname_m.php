<?php
    $title = "Stok Opname Obat & BHP";
    $subtitle = $params["start"] . " s.d. " . $params["end"];
    $qGetSO = "SELECT O.kode_brng, nama_brng, satuan, O.h_beli, tanggal, stok, O.real, selisih, nomihilang, lebih, nomilebih, keterangan, nm_bangsal, no_batch, no_faktur FROM opname O INNER JOIN databarang B ON O.kode_brng=B.kode_brng INNER JOIN kodesatuan S ON B.kode_sat=S.kode_sat INNER JOIN bangsal BS ON O.kd_bangsal=BS.kd_bangsal WHERE tanggal BETWEEN '" . $params["start"] . "' AND '" . $params["end"] . "' ORDER BY O.kd_bangsal, tanggal, nama_brng";
    $getSO = mysqli_query($connect_app, $qGetSO);
    $lstSO = ""; $i = 0;
    while ($data = mysqli_fetch_assoc($getSO)) {
        $lstSO .= "<tr><td>" . ++$i . "</td><td>" . $data["nm_bangsal"] . "</td><td>" . $data["tanggal"] . "</td><td>" . $data["kode_brng"] . "</td><td>" . $data["nama_brng"] . "</td><td>" . $data["satuan"] . "</td><td>" . number_format($data["h_beli"], 0, ",", ".") . "</td><td>" . number_format($data["stok"], 0, ",", ".") . "</td><td>" . number_format($data["real"], 0, ",", ".") . "</td><td>" . number_format($data["selisih"], 0, ",", ".") . "</td><td>" . number_format($data["lebih"], 0, ",", ".") . "</td><td>" . number_format($data["nomihilang"], 0, ",", ".") . "</td><td>" . number_format($data["nomilebih"], 0, ",", ".") . "</td><td>" . $data["no_batch"] . "</td><td>" . $data["no_faktur"] . "</td></tr>";
    }
    $lstSO = "<table><tr><th>No</th><th>Lokasi</th><th>Tanggal</th><th>Kode</th><th>Nama Barang</th><th>Satuan</th><th>Harga</th><th>Stok</th><th>Real</th><th>Selisih</th><th>Lebih</th><th>Nominal Hilang</th><th>Nominal Lebih</th><th>No.Batch</th><th>No.Faktur</th></tr>" . $lstSO . "</table>";
    $table = $lstSO;
?>