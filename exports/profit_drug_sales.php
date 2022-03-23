<?php
    $title = "Keuntungan Penjualan Obat & BHP";
    $subtitle = $params["start"] . " s.d. " . $params["end"];
    $qGetProfit = "SELECT tgl_jual, J.nota_jual, DJ.kode_brng, nama_brng, satuan, nm_bangsal, h_jual, DJ.jumlah, DJ.subtotal, DJ.dis, DJ.bsr_dis, DJ.tambahan, DJ.total, DJ.h_beli, (DJ.h_beli*DJ.jumlah) AS total_asal, (DJ.total-(DJ.h_beli*DJ.jumlah)) AS keuntungan FROM penjualan J INNER JOIN detailjual DJ ON J.nota_jual=DJ.nota_jual INNER JOIN databarang B ON DJ.kode_brng=B.kode_brng INNER JOIN kodesatuan S ON DJ.kode_sat=S.kode_sat INNER JOIN bangsal BS ON J.kd_bangsal=BS.kd_bangsal WHERE tgl_jual BETWEEN '" . $params["start"] . "' AND '" . $params["end"] . "' ORDER BY tgl_jual, J.nota_jual";
    $getProfit = mysqli_query($connect_app, $qGetProfit);
    $lstProfit = ""; $i = 0; $total = "";
    while ($data = mysqli_fetch_assoc($getProfit)) {
        $lstProfit .= "<tr><td>" . ++$i . "</td><td>" . $data["tgl_jual"] . "</td><td>" . $data["nota_jual"] . "</td><td>" . $data["kode_brng"] . "</td><td>" . $data["nama_brng"] . "</td><td>" . $data["satuan"] . "</td><td>" . $data["nm_bangsal"] . "</td><td>" . number_format($data["h_jual"], 0, ",", ".") . "</td><td>" . number_format($data["jumlah"], 0, ",", ".") . "</td><td>" . number_format($data["subtotal"], 0, ",", ".") . "</td><td>" . number_format($data["dis"], 0, ",", ".") . "</td><td>" . number_format($data["bsr_dis"], 0, ",", ".") . "</td><td>" . number_format($data["tambahan"], 0, ",", ".") . "</td><td>" . number_format($data["total"], 0, ",", ".") . "</td><td>" . number_format($data["h_beli"], 0, ",", ".") . "</td><td>" . number_format($data["total_asal"], 0, ",", ".") . "</td><td>" . number_format($data["keuntungan"], 0, ",", ".") . "</td></tr>";
        $total += $data["keuntungan"];
    }
    $lstProfit .= "<tr><td colspan='16' style='text-align: right'>Total Keuntungan :</td><td>" . number_format($total, 0, ",", ".") . "</td></tr>";
    $lstProfit = "<table><tr><th>No</th><th>Tgl. Penjualan</th><th>Nota Jual</th><th>Kode Barang</th><th>Nama Barang</th><th>Satuan</th><th>Bangsal</th><th>Harga Jual</th><th>Jml. Jual</th><th>Subtotal Jual</th><th>Disc (%)</th><th>Besar Disc (Rp)</th><th>Tambahan</th><th>Total Jual</th><th>Harga Beli</th><th>Total Beli</th><th>Keuntungan</th></tr>" . $lstProfit . "</table>";
    $table = $lstProfit;
?>