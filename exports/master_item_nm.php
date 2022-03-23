<?php
    $title = "Master Barang Non Medis";
    $subtitle = "";
    $qGetItem = "SELECT kode_brng, nama_brng, nm_jenis, SK.satuan AS satuan_kecil, SB.satuan AS satuan_besar, isi, dasar, harga FROM ipsrsbarang B INNER JOIN kodesatuan SK ON B.kode_sat=SK.kode_sat INNER JOIN kodesatuan SB ON B.kode_Satbesar=SB.kode_sat INNER JOIN ipsrsjenisbarang J ON B.jenis=J.kd_jenis WHERE status='1' ORDER BY nama_brng";
    $getItem = mysqli_query($connect_app, $qGetItem);
    $lstItem = ""; $idx = 0;
    while ($data = mysqli_fetch_assoc($getItem)) {
        $lstItem .= "<tr><td>" . ++$idx . "</td><td>" . $data["kode_brng"] . "</td><td>" . $data["nama_brng"] . "</td><td>" . $data["nm_jenis"] . "</td><td>" . $data["satuan_kecil"] . "</td><td>" . $data["satuan_besar"] . "</td><td>" . $data["isi"] . "</td><td>" . number_format($data["dasar"], 0, ",", ".") . "</td><td>" . number_format($data["harga"], 0, ",", ".") . "</td></tr>";
    }
    $table = "<table><tr><th>No</th><th>Kode Barang</th><th>Nama Barang</th><th>Jenis</th><th>SK</th><th>SB</th><th>Isi</th><th>Harga Dasar</th><th>Harga Beli</th></tr>" . $lstItem . "</table>";
?>