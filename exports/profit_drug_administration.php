<?php
    $title = "Keuntungan Pemberian Obat & BHP";
    $subtitle = $params["start"] . " s.d. " . $params["end"];
    $qGetProfit = "SELECT tgl_perawatan, no_rawat, DPO.kode_brng, nama_brng, satuan, nm_bangsal, biaya_obat, jml, (biaya_obat*jml) AS subtotal, (embalase+tuslah) AS tambahan, total, DPO.h_beli, (DPO.h_beli*jml) AS total_asal, (total-(DPO.h_beli*jml)) AS keuntungan FROM detail_pemberian_obat DPO INNER JOIN databarang B ON DPO.kode_brng=B.kode_brng INNER JOIN kodesatuan S ON B.kode_sat=S.kode_sat INNER JOIN bangsal BS ON DPO.kd_bangsal=BS.kd_bangsal WHERE DPO.tgl_perawatan BETWEEN '" . $params["start"] . "' AND '" . $params["end"] . "' ORDER BY tgl_perawatan, no_rawat";
    $getProfit = mysqli_query($connect_app, $qGetProfit);
    $lstProfit = ""; $i = 0; $total = 0;
    while ($data = mysqli_fetch_assoc($getProfit)) {
        $lstProfit .= "<tr><td>" . ++$i . "</td><td>" . $data["tgl_perawatan"] . "</td><td>" . $data["no_rawat"] . "</td><td>" . $data["kode_brng"] . "</td><td>" . $data["nama_brng"] . "</td><td>" . $data["satuan"] . "</td><td>" . $data["nm_bangsal"] . "</td><td>" . number_format($data["biaya_obat"], 0, ",", ".") . "</td><td>" . number_format($data["jml"], 0, ",", ".") . "</td><td>" . number_format($data["subtotal"], 0, ",", ".") . "</td><td>" . number_format($data["tambahan"], 0, ",", ".") . "</td><td>" . number_format($data["total"], 0, ",", ".") . "</td><td>" . number_format($data["h_beli"], 0, ",", ".") . "</td><td>" . number_format($data["total_asal"], 0, ",", ".") . "</td><td>" . number_format($data["keuntungan"], 0, ",", ".") . "</td></tr>";
        $total += $data["keuntungan"];
    }
    $lstProfit .= "<tr><td colspan='14' style='text-align: right'>Total Keuntungan :</td><td>" . number_format($total, 0, ",", ".") . "</td></tr>";
    $lstProfit = "<table><tr><th>No</th><th>Tgl. Pemberian</th><th>No. Rawat</th><th>Kode Barang</th><th>Nama Barang</th><th>Satuan</th><th>Bangsal</th><th>Biaya Obat</th><th>Jml. Obat</th><th>Subtotal Biaya</th><th>Embalase+Tuslah</th><th>Total Biaya</th><th>Harga Beli</th><th>Total Beli</th><th>Keuntungan</th></tr>" . $lstProfit . "</table>";
    $table = $lstProfit;
?>