<?php
    require_once '../template/header.php';
    require_once '../config/connect_app.php';
    require_once '../function/access.php';
    require_once '../function/function.php';
    restrictAccess("ipsrs_barang");
    if (isset($_POST["form_submit"])) {
        $qUpdateUnit = "UPDATE ipsrsbarang SET kode_satbesar='" . $_POST["hdn_satbesar"] . "', isi=" . $_POST["inp_isi"] . " WHERE kode_brng='" . $_POST["hdn_id"] . "'";
        $updateUnit = mysqli_query($connect_app, $qUpdateUnit);
        if ($updateUnit) {
            saveToTracker($qUpdateUnit, $connect_app);
            $msg = "Sukses mengatur satuan besar";
        } else {
            logError("set_large_unit_nm.php", "updateUnit:" . $updateUnit);
            $msg = "Gagal mengatur satuan besar";
        }
        echo "<script>alert('" . $msg . "'); window.location.href='large_units_nm.php';</script>";
    } else if (!isset($_GET["id"]) || $_GET["id"] == "") {
        denyAccess();
    } else {
        $qGetNMedLog = "SELECT kode_brng, nama_brng, SK.satuan AS satuan_kecil, kode_satbesar, SB.satuan AS satuan_besar, isi FROM ipsrsbarang B INNER JOIN kodesatuan SK ON B.kode_sat=SK.kode_sat INNER JOIN kodesatuan SB ON B.kode_satbesar=SB.kode_sat WHERE B.status='1' AND kode_brng='" . $_GET["id"] . "'";
        $getNMedLog = mysqli_query($connect_app, $qGetNMedLog);
        $data = mysqli_fetch_assoc($getNMedLog);
    }
?>

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
                <td><input type="text" id="inp_satbesar" name="inp_satbesar" class="form-control" value="<?php echo $data["satuan_besar"] ?>" onKeyUp="getSatuanBesar()" autocomplete="off"><input type="hidden" id="hdn_satbesar" name="hdn_satbesar" value="<?php echo $data["kode_satbesar"] ?>"></td>
            </tr>
            <tr>
                <td>Isi</td>
                <td><input type="text" id="inp_isi" name="inp_isi" class="form-control" value="<?php echo $data["isi"] ?>" autocomplete="off"></td>
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
        term = $("#inp_satbesar").val();
        if (term.length > 0) ajax_getData("ajax_getdata.php?type=unit&target=satbesar&term=" + term);
            else removeSuggest();
    }
    function validateForm() {
        hdn_unit = $("#hdn_satbesar").val();
        unit = $("#inp_satbesar").val();
        isi = $("#inp_isi").val();
        if (!unit || !hdn_unit) {
            alert("Satuan Besar kosong");
            $("#inp_satbesar").focus();
            return false;
        } else if (!isi) {
            alert("Isi kosong");
            $("#inp_isi").focus();
            return false;
        } else if (isi == 0) {
            alert("Isi tidak boleh nol");
            $("#inp_isi").focus();
            return false;
        }
        return true;
    }
</script>

<?php require_once '../template/footer.php' ?>