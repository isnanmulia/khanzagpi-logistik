<?php
    require_once '../config/connect_app.php';
    require_once '../config/session_check.php';
    require_once '../function/access.php';
    require_once '../function/function.php';
    restrictAccess("rekap_pemesanan");
    if (isset($_POST["form_submit"])) {
        mysqli_autocommit($connect_app, FALSE);
        $id = $_POST["hdn_id"];
        $itm = $_POST["hdn_MinMaxitems"];
        $itm_min = str_replace(".", "", $_POST["inp_min_" . $itm]);
        $itm_max = str_replace(".", "", $_POST["inp_max_" . $itm]);
        $itmUpdate = [];
        if ($_POST["hdn_min_" . $itm] != $itm_min)
            array_push($itmUpdate, "min_stok=" . $itm_min);
        if ($_POST["hdn_max_" . $itm] != $itm_max)
            array_push($itmUpdate, "max_stok=" . $itm_max);
        $qUpdateMinMax = "UPDATE gpi_minmax_obat SET " . implode(",", $itmUpdate) . " WHERE kode_brng='" . $id . "' AND kd_bangsal='" . $_POST["hdn_bangsal"] . "'";
        $updateMinMax = mysqli_query($connect_app, $qUpdateMinMax);
        if ($updateMinMax) {
            mysqli_commit($connect_app);
            $msg = "Sukses mengubah Stok Minimal/Maksimal Per Unit";
        } else {
            logError("edit_minmax_m.php", "updateMinMax:" . $updateMinMax);
            mysqli_rollback($connect_app);
            $msg = "Gagal mengubah Stok Minimal/Maksimal Per Unit";
        }
        mysqli_autocommit($connect_app, TRUE);
        echo "<script>alert('" . $msg . "'); window.opener.$('#span_min_" . $id . "').html('" . number_format($itm_min, 0, ",", ".") . "'); window.opener.$('#span_max_" . $id . "').html('" . number_format($itm_max, 0, ",", ".") . "'); window.close();</script>";
    }
    $id = $_GET["id"];
    $qGetMinMax = "SELECT MM.kode_brng, nama_brng, satuan, nm_bangsal, min_stok, max_stok FROM gpi_minmax_obat MM INNER JOIN databarang B ON MM.kode_brng=B.kode_brng INNER JOIN kodesatuan S ON B.kode_sat=S.kode_sat INNER JOIN bangsal BS ON MM.kd_bangsal=BS.kd_bangsal WHERE MM.kode_brng='" . $id . "' AND MM.kd_bangsal='" . $_GET["unit"] . "'";
    $getMinMax = mysqli_query($connect_app, $qGetMinMax);
    $minMax = mysqli_fetch_assoc($getMinMax);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Ubah Stok Minimal/Maksimal Per Unit | Aplikasi Utilitas Khanza GPI</title>
        <link rel="stylesheet" href="<?php echo $base_url ?>/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/bower_components/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/bower_components/jquery-ui/themes/base/jquery-ui.min.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/plugins/pace/pace.min.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/dist/css/AdminLTE.min.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/styles/style.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/styles/popup.css">
    </head>
    <body>
        <h4>Ubah Stok Minimal/Maksimal Per Unit</h4>
        <form id="frmEditMinMax" action="" method="POST" onSubmit="return validateForm()">
            <table id="tbl_editminmaxm" class="table">
                <tr>
                    <td style="width: 110px">Barang</td>
                    <td>
                        <?php echo $minMax["nama_brng"] . " [" . $minMax["kode_brng"] . "]" ?>
                        <input type="hidden" id="hdn_id" name="hdn_id" value="<?php echo $id ?>">
                        <input type="hidden" id="hdn_MinMaxitems" name="hdn_MinMaxitems" value="<?php echo $id ?>">
                    </td>
                </tr>
                <tr>
                    <td>Unit</td>
                    <td>
                        <?php echo $minMax["nm_bangsal"] ?>
                        <input type="hidden" id="hdn_bangsal" name="hdn_bangsal" value="<?php echo $_GET["unit"] ?>">
                    </td>
                </tr>
                <tr>
                    <td>Stok Minimal</td>
                    <td><input type="hidden" id="hdn_min_<?php echo $id ?>" name="hdn_min_<?php echo $id ?>" value="<?php echo $minMax["min_stok"] ?>"><input type="text" id="inp_min_<?php echo $id ?>" name="inp_min_<?php echo $id ?>" class="form-control inline-input" onkeyup="removeNonNumeric(&quot;inp_min_<?php echo $id ?>&quot;);recheckMinMax(&quot;M&quot;);" style="width: 70px" autocomplete="off" value="<?php echo number_format($minMax["min_stok"], 0, ",", ".") ?>">&nbsp;&nbsp;<?php echo $minMax["satuan"] ?></td>
                </tr>
                <tr>
                    <td>Stok Maksimal</td>
                    <td><input type="hidden" id="hdn_max_<?php echo $id ?>" name="hdn_max_<?php echo $id ?>" value="<?php echo $minMax["max_stok"] ?>"><input type="text" id="inp_max_<?php echo $id ?>" name="inp_max_<?php echo $id ?>" class="form-control inline-input" onkeyup="removeNonNumeric(&quot;inp_max_<?php echo $id ?>&quot;);recheckMinMax(&quot;M&quot;);" style="width: 70px" autocomplete="off" value="<?php echo number_format($minMax["max_stok"], 0, ",", ".") ?>">&nbsp;&nbsp;<?php echo $minMax["satuan"] ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right">
                        <input type="hidden" id="form_submit" name="form_submit" value="1">
                        <a class="btn btn-primary" href="#" onClick="window.close()"><i class="fa fa-times"></i> Batal</a>
                        <button type="submit" id="btn_submit" disabled class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                    </td>
                </tr>
            </table>
        </form>
    </body>
    <script src="<?php echo $base_url ?>/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo $base_url ?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?php echo $base_url ?>/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo $base_url ?>/bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.id.min.js"></script>
    <script src="<?php echo $base_url ?>/bower_components/PACE/pace.min.js"></script>
    <script src="<?php echo $base_url ?>/scripts/script.js"></script>
    <script>
        function validateForm() {
            items = $("#hdn_MinMaxitems").val();
            min = toNumber($("#inp_min_" + items).val());
            max = toNumber($("#inp_max_" + items).val());
            if (max == 0) {
                alert("Jumlah stok maksimal tidak boleh nol");
                $("#inp_max_" + items).focus();
                return false;
            } else if (min > max) {
                alert("Jumlah stok minimal lebih besar daripada stok maksimal");
                $("#inp_min_" + items).focus();
                return false;
            }
            return true;
        }
    </script>
</html>