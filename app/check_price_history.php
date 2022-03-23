<?php
    require_once '../config/connect_app.php';
    require_once '../config/session_check.php';
    require_once '../function/access.php';
    if (isset($_GET["id"]) && isset($_GET["type"])) {
        $id = $_GET["id"];
        $type = $_GET["type"];
        if ($type == "M") {
            restrictAccess("obat");
            $qGetItem = "SELECT nama_brng, SK.satuan AS satuan_kecil, SB.satuan AS satuan_besar, isi FROM databarang B INNER JOIN kodesatuan SK ON B.kode_sat=SK.kode_sat INNER JOIN kodesatuan SB ON B.kode_satbesar=SB.kode_sat WHERE kode_brng='" . $id . "'";
            $qGetHistory = "SELECT no_ref, tanggal_efektif, harga_sat_kecil, harga_sat_besar, CASE WHEN P.nama IS NOT NULL THEN P.nama ELSE 'admin' END AS nama_petugas FROM gpi_riwayat_harga_obat RO LEFT JOIN petugas P ON RO.dibuat_oleh=P.nip WHERE kode_brng='" . $id . "' ORDER BY tanggal_efektif DESC, no_ref DESC";
        } else if ($type == "N") {
            restrictAccess("ipsrs_barang");
            $qGetItem = "SELECT nama_brng, SK.satuan AS satuan_kecil, SB.satuan AS satuan_besar, isi FROM ipsrsbarang B INNER JOIN kodesatuan SK ON B.kode_sat=SK.kode_sat INNER JOIN kodesatuan SB ON B.kode_satbesar=SB.kode_sat WHERE kode_brng='" . $id . "'";
            $qGetHistory = "SELECT no_ref, tanggal_efektif, harga_sat_kecil, harga_sat_besar, CASE WHEN P.nama IS NOT NULL THEN P.nama ELSE 'admin' END AS nama_petugas FROM gpi_riwayat_harga_nonmedis RN LEFT JOIN petugas P ON RN.dibuat_oleh=P.nip WHERE kode_brng='" . $id . "' ORDER BY tanggal_efektif DESC, no_ref DESC";
        }
        $getItem = mysqli_query($connect_app, $qGetItem);
        $item = mysqli_fetch_assoc($getItem);
        $getHistory = mysqli_query($connect_app, $qGetHistory);
        $lstHistory = ""; $i = 0;
        while ($data = mysqli_fetch_assoc($getHistory)) {
            $lstHistory .= "<tr><td>" . ++$i . "</td><td>" . $data["no_ref"] . "</td><td>" . $data["tanggal_efektif"] . "</td><td class='num-input'>" . number_format($data["harga_sat_kecil"], 0, ",", ".") . "</td><td class='num-input'>" . number_format($data["harga_sat_besar"], 0, ",", ".") . "</td><td>" . $data["nama_petugas"] . "</td></tr>";
        }
        $lstHistory = "<tr><th style='width: 40px'>No</th><th style='width: 120px'>No. Referensi</th><th style='width: 120px'>Tanggal Efektif</th><th style='width: 150px'>Harga Satuan Kecil</th><th style='width: 150px'>Harga Satuan Besar</th><th style='width: 100px'>Petugas</th></tr>" . $lstHistory;
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Riwayat Harga | Aplikasi Utilitas Khanza GPI</title>
        <link rel="stylesheet" href="<?php echo $base_url ?>/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/bower_components/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/bower_components/jquery-ui/themes/base/jquery-ui.min.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/styles/style.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/styles/popup.css">
    </head>
    <body>
        <h4>Riwayat Harga</h4>
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
                <td>Jenis Barang</td>
                <td>Barang <?php echo ($type == "M" ? "Medis" : ($type == "N" ? "Non Medis" : "")) ?></td>
            </tr>
            <tr>
                <td></td><td>Satuan Kecil: <?php echo $item["satuan_kecil"] ?><span class="inline-spacer">|</span>Satuan Besar: <?php echo $item["satuan_besar"] ?><span class="inline-spacer">|</span>Isi: <?php echo $item["isi"] ?></td>
            </tr>
            <tr>
                <td>Riwayat Harga</td>
                <td>
                    <div style="height: 165px; overflow: scroll">
                        <table class="table">
                            <?php echo $lstHistory ?>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </body>
</html>

<?php } ?>