<?php
    require_once '../config/connect_app.php';
    require_once '../config/session_check.php';
    require_once '../function/access.php';
    if (isset($_GET["id"]) && isset($_GET["type"])) {
        $id = $_GET["id"];
        $type = $_GET["type"];
        $lstStock = ""; $total = 0;
        if ($type == "M") {
            // restrictAccess("LOGMED");
            $qGetItem = "SELECT nama_brng, SK.satuan AS satuan_kecil, SB.satuan AS satuan_besar, isi FROM databarang B INNER JOIN kodesatuan SK ON B.kode_sat=SK.kode_sat INNER JOIN kodesatuan SB ON B.kode_satbesar=SB.kode_sat WHERE kode_brng='" . $id . "'";
            $getItem = mysqli_query($connect_app, $qGetItem);
            $item = mysqli_fetch_assoc($getItem);
            $qGetStock = "SELECT nm_bangsal, stok FROM gudangbarang G INNER JOIN bangsal B ON G.kd_bangsal=B.kd_bangsal WHERE kode_brng='" . $id . "' AND G.kd_bangsal<>'FRMSM' ORDER BY nm_bangsal";
            $getStock = mysqli_query($connect_app, $qGetStock);
            while ($data = mysqli_fetch_assoc($getStock)) {
                $lstStock .= "<tr><td>" . $data["nm_bangsal"] . "</td><td class='num-input'>" . number_format($data["stok"], 0, ",", ".") . " " . $item["satuan_kecil"] . "</td></tr>";
                $total += $data["stok"];
            }
            $lstStock = "<tr><th>Bangsal</th><th style='width: 70px'>Jumlah</th></tr>" . $lstStock;
        } else if ($type == "N") {
            $qGetItem = "SELECT nama_brng, SK.satuan AS satuan_kecil, SB.satuan AS satuan_besar, isi FROM ipsrsbarang B INNER JOIN kodesatuan SK ON B.kode_sat=SK.kode_sat INNER JOIN kodesatuan SB ON B.kode_satbesar=SB.kode_sat WHERE kode_brng='" . $id . "'";
            $getItem = mysqli_query($connect_app, $qGetItem);
            $item = mysqli_fetch_assoc($getItem);
            $qGetStock = "SELECT nm_bangsal, stok FROM gpi_gudangbarangipsrs G INNER JOIN bangsal B ON G.kd_bangsal=B.kd_bangsal WHERE kode_brng='" . $id . "' ORDER BY nm_bangsal";
            $getStock = mysqli_query($connect_app, $qGetStock);
            while ($data = mysqli_fetch_assoc($getStock)) {
                $lstStock .= "<tr><td>" . $data["nm_bangsal"] . "</td><td class='num-input'>" . number_format($data["stok"], 0, ",", ".") . " " . $item["satuan_kecil"] . "</td></tr>";
                $total += $data["stok"];
            }
            $lstStock = "<tr><th>Bangsal</th><th style='width: 70px'>Jumlah</th></tr>" . $lstStock;
        }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Detail Stok | Aplikasi Utilitas Khanza GPI</title>
        <link rel="stylesheet" href="<?php echo $base_url ?>/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/bower_components/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/bower_components/jquery-ui/themes/base/jquery-ui.min.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/styles/style.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/styles/popup.css">
        <style>
            .num-input { width: 130px; }
            .span-amount { font-weight: bold; width: 130px }
        </style>
    </head>
    <body>
        <h4>Detail Stok</h4>
        <table class="table table-hover">
            <tr>
                <td style="width: 110px">Kode</td>
                <td><?php echo $id ?></td>
            </tr>
            <tr>
                <td>Nama Barang</td>
                <td><?php echo $item["nama_brng"] ?></td>
            </tr>
            <tr>
                <td></td><td>Satuan Kecil: <?php echo $item["satuan_kecil"] ?><span class="inline-spacer">|</span>Satuan Besar: <?php echo $item["satuan_besar"] ?><span class="inline-spacer">|</span>Isi: <?php echo $item["isi"] ?></td>
            </tr>
            <tr>
                <td>Stok Barang</td>
                <td>
                    <div style="height: 150px; overflow: scroll;">
                        <table class="table">
                            <?php echo $lstStock ?>
                        </table>
                    </div>
                    <div style="padding: 5px 25px 0 0; float: right">
                        Total: <span class="span-amount"><?php echo number_format($total, 0, ",", ".") . " " . $item["satuan_kecil"] ?></span>
                    </div>
                </td>
            </tr>
        </table>
    </body>
</html>

 <?php } ?>