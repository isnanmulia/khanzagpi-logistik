<?php
    $title = "Sisa Stok Obat & BHP";
    $subtitle = "Per " . date("Y-m-d H:i:s");
    $qGetBangsal = "SELECT kd_bangsal, nm_bangsal FROM bangsal WHERE status='1' AND kd_bangsal<>'-' ORDER BY kd_bangsal";
    $getBangsal = mysqli_query($connect_app, $qGetBangsal);
    $cntBangsal = mysqli_num_rows($getBangsal);
    $lstItem = "<tr><th rowspan='2'>No</th><th rowspan='2'>Kode Barang</th><th rowspan='2'>Nama Barang</th><th rowspan='2'>Satuan</th><th colspan='" . $cntBangsal . "'>Sisa Stok</th><th rowspan='2'>Jumlah</th></tr><tr>";
    $pivotBangsal = "";
    $lstBangsal = [];
    while ($bangsal = mysqli_fetch_assoc($getBangsal)) {
        $lstItem .= "<th>" . $bangsal["nm_bangsal"] . "</th>";
        array_push($lstBangsal, $bangsal["kd_bangsal"]);
        $pivotBangsal .= (strlen($pivotBangsal) ? ", " : "") . "Sum(If(kd_bangsal='" . $bangsal["kd_bangsal"] . "', stok, 0)) AS " . str_replace("-", "_", $bangsal["kd_bangsal"]);
    }
    $lstItem .= "</tr>";
    $qGetItem = "SELECT kode_brng, nama_brng, satuan FROM databarang B INNER JOIN kodesatuan S ON B.kode_sat=S.kode_sat ORDER BY kode_brng";
    $getItem = mysqli_query($connect_app, $qGetItem);
    $i = 0;
    while ($data = mysqli_fetch_assoc($getItem)) {
        $total = 0;
        $lstItem .= "<tr><td>" . ++$i . "</td><td>" . $data["kode_brng"] . "</td><td>" . $data["nama_brng"] . "</td><td>" . $data["satuan"] . "</td>";
        $qGetStok = "SELECT " . $pivotBangsal . " FROM gudangbarang WHERE kode_brng='" . $data["kode_brng"] . "'";
        $getStok = mysqli_query($connect_app, $qGetStok);
        $stok = mysqli_fetch_assoc($getStok);
        foreach ($lstBangsal as $b) {
            $lstItem .= "<td>" . number_format($stok[str_replace("-", "_", $b)], 0, ",", ".") . "</td>";
            $total += $stok[str_replace("-", "_", $b)];
        }
        $lstItem .= "<td>" . number_format($total, 0, ",", ".") . "</td></tr>";
    }
    $table = "<table>" . $lstItem . "</table>";
?>