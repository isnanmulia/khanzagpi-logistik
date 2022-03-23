<?php
    require_once '../template/header.php';
    require_once '../config/connect_app.php';
    require_once '../function/access.php';
    require_once '../function/function.php';
    restrictAccess("LOGMED");
    if (isset($_POST["form_submit"])) {
        $qUpdateUnit = "UPDATE databarang SET kode_sat_besar='" . $_POST["hdn_sat_besar"] . "', faktor_konversi=" . $_POST["inp_faktor_konversi"] . " WHERE kode_brng='" . $_POST["hdn_id"] . "'";
        $updateUnit = mysqli_query($connect_app, $qUpdateUnit);
        if ($updateUnit) {
            saveToTracker($qUpdateUnit, $connect_app);
            $msg = "Sukses mengatur satuan besar";
        } else {
            logError("set_large_unit_m.php", "updateUnit:" . $updateUnit);
            $msg = "Gagal mengatur satuan besar";
        }
        echo "<script>alert('" . $msg . "'); window.location.href='large_units_m.php';</script>";
    } else if (!isset($_GET["id"]) || $_GET["id"] == "") {
        denyAccess();
    } else {
        $qGetMedLog = "SELECT kode_brng, nama_brng, SK.kode_sat, SK.satuan AS satuan_kecil, kode_sat_besar, SB.satuan AS satuan_besar, faktor_konversi FROM databarang B INNER JOIN kodesatuan SK ON B.kode_sat=SK.kode_sat INNER JOIN kodesatuan SB ON B.kode_sat_besar=SB.kode_sat WHERE B.status='1' AND kode_brng='" . $_GET["id"] ."'";
        $getMedLog = mysqli_query($connect_app, $qGetMedLog);
        $data = mysqli_fetch_assoc($getMedLog);
    }
?>

<style> .popover { width: 220px; } </style>

<form name="frmSetLargeUnit" action="" onSubmit="return validateForm()" method="POST">
    <div style="max-width: 500px">
        <table class="table table-hover">
            <tr>
                <td style="width: 150px">Kode Barang</td>
                <td><?php echo $data["kode_brng"] ?><input type="hidden" id="hdn_id" name="hdn_id" value="<?php echo $data["kode_brng"] ?>"></td>
            </tr>
            <tr>
                <td>Nama Barang</td>
                <td><?php echo $data["nama_brng"] ?></td>
            </tr>
            <tr>
                <td>Satuan Kecil</td>
                <td><?php echo $data["satuan_kecil"] ?></td>
            </tr>
            <tr>
                <td>Satuan Besar <span class="span-required">*</span></td>
                <td><input type="text" id="inp_sat_besar" name="inp_sat_besar" class="form-control" value="<?php echo $data["satuan_besar"] ?>" onKeyUp="getSatuanBesar()" autocomplete="off"><input type="hidden" id="hdn_sat_besar" name="hdn_sat_besar" value="<?php echo $data["kode_sat_besar"] ?>"></td>
            </tr>
            <tr>
                <td>Faktor Konversi <span class="span-required">*</span></td>
                <td><input type="text" id="inp_faktor_konversi" name="inp_faktor_konversi" class="form-control" value="<?php echo $data["faktor_konversi"] ?>" autocomplete="off"></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: right">
                    <input type="hidden" id="form_submit" name="form_submit" value="1">
                    <button type="button" class="btn btn-primary" onClick="history.back()"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Simpan</button>
                </td>
            </tr>
        </table>
    </div>
</form>

<script>
    function getSatuanBesar() {
        term = $("#inp_sat_besar").val();
        if (term.length > 0) ajax_getData("ajax_getdata.php?type=unit&target=sat_besar&term=" + term);
            else removeSuggest();
    }
    function clearInputUnit() {
        $("#inp_sat_besar").val("");
        $("#hdn_sat_besar").val("");
    }
    function validateForm() {
        hdn_unit = $("#hdn_sat_besar").val();
        unit = $("#inp_sat_besar").val();
        fac_conv = $("#inp_faktor_konversi").val();
        if (!unit || !hdn_unit) {
            alert("Satuan Besar kosong");
            $("#inp_sat_besar").focus();
            return false;
        } else if (!fac_conv) {
            alert("Faktor Konversi kosong");
            $("#inp_faktor_konversi").focus();
            return false;
        } else if (fac_conv == 0) {
            alert("Faktor Konversi tidak boleh nol");
            $("#inp_faktor_konversi").focus();
            return false;
        }
        return true;
    }
    setTimeout(function () {
        $('[data-toggle="popover"]').popover(); 
    }, 500);
</script>

<?php require_once '../template/footer.php' ?>