<?php
    $title = "Mutasi Obat & BHP";
    $subtitle = $params["start"] . " s.d. " . $params["end"];
    $qGetMovement = "SELECT BD.nm_bangsal AS dari, BK.nm_bangsal AS ke, MB.kode_brng, B.nama_brng, MB.jml, MB.harga, (MB.jml*MB.harga) AS total, MB.tanggal, MB.keterangan FROM mutasibarang MB INNER JOIN databarang B ON MB.kode_brng=B.kode_brng INNER JOIN bangsal BD ON MB.kd_bangsaldari=BD.kd_bangsal INNER JOIN bangsal BK ON MB.kd_bangsalke=BK.kd_bangsal WHERE MB.tanggal BETWEEN '" . $params["start"] . " 00:00:00' AND '" . $params["end"] . " 23:59:59' ORDER BY MB.tanggal, MB.kd_bangsaldari, MB.kode_brng";
    $getMovement = mysqli_query($connect_app, $qGetMovement);
    $lstMovement = ""; $i = 0; $total = 0;
    while ($data = mysqli_fetch_assoc($getMovement)) {
        $lstMovement .= "<tr><td>" . ++$i . "</td><td>" . $data["dari"] . "</td><td>" . $data["ke"] . "</td><td>" . $data["kode_brng"] . "</td><td>" . $data["nama_brng"] . "</td><td>" . number_format($data["jml"], 0, ",", ".") . "</td><td>" . number_format($data["harga"], 0, ",", ".") . "</td><td>" . number_format($data["total"], 0, ",", ".") . "</td><td>" . $data["tanggal"] . "</td><td>" . $data["keterangan"] . "</td></tr>";
        $total += $data["total"];
    }
    $lstMovement = "<table><tr><th>No</th><th>Dari Unit</th><th>Ke Unit</th><th>Kode Barang</th><th>Nama Barang</th><th>Jumlah</th><th>Harga</th><th>Total</th><th>Tanggal</th><th>Keterangan</th></tr>" . $lstMovement . "<tr><td colspan='7' style='text-align: right'>Total :</td><td>" . number_format($total, 0, ",", ".") . "</td></tr></table>";
    $table = $lstMovement;
?>