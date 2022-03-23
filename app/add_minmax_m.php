<?php
    require_once '../template/header.php';
    require_once '../config/connect_app.php';
    require_once '../function/access.php';
    require_once '../function/function.php';
    restrictAccess("rekap_pemesanan");
    if (isset($_POST["form_submit"])) {
        mysqli_autocommit($connect_app, FALSE);
        $items = explode(",", $_POST["hdn_MinMaxitems"]);
        $status = TRUE;
        foreach ($items as $itm) {
            $qMinMax = "INSERT INTO gpi_minmax_obat VALUES ('" . $itm . "', '" . $_POST["hdn_unit"] . "', " . $_POST["inp_min_" . $itm] . ", " . $_POST["inp_max_" . $itm] . ")";
            $minMax = mysqli_query($connect_app, $qMinMax);
            saveToTracker($qMinMax, $connect_app);
            $status = $status && $minMax;
        }
        if ($status) {
            mysqli_Commit($connect_app);
            $msg = "Sukses menambahkan Stok Minimal/Maksimal Per Unit";
        } else {
            logError("add_minmax_m.php", "minMax:" . $minMax . " " . $qMinMax);
            mysqli_rollback($connect_app);
            $msg = "Gagal menambahkan Stok Minimal/Maksimal Per Unit";
        }
        mysqli_autocommit($connect_app, TRUE);
        echo "<script>alert('" . $msg . "'); window.location.href='minmax_m.php?unit=" . $_POST["hdn_unit"] . "'</script>";
    }
    $qGetUnit = "SELECT nm_bangsal FROM bangsal WHERE kd_bangsal='" . $_GET["unit"] . "'";
    $getUnit = mysqli_query($connect_app, $qGetUnit);
    $unit = mysqli_fetch_assoc($getUnit)["nm_bangsal"];
?>

<form name="frmAddMinMaxM" method="POST" target="" onSubmit="return validateForm()">
    <h4 id="hd_unit" style="display: inline-block">Unit: <?php echo $unit . " [" . $_GET["unit"] . "]" ?></h4><input type="hidden" id="hdn_unit" name="hdn_unit" value="<?php echo $_GET["unit"] ?>">
    <span style="margin-left: 50px"><a class="btn btn-primary" href="#" onClick="addUnitItem()"><i class="fa fa-plus"></i> Tambah Barang</a><input type="hidden" id="form_submit" name="form_submit" value="1"><input type="hidden" id="hdn_MinMaxitems" name="hdn_MinMaxitems"> <a class="btn btn-primary" href="#" onClick="window.location.href='minmax_m.php?unit=<?php echo $_GET["unit"] ?>'"><i class="fa fa-times"></i> Batal</a> <button type="submit" class="btn btn-primary"><i class='fa fa-save'></i> Simpan</button></span>
    <table id="tbl_addMinMax" class="table table-hover">
        <thead>
            <tr><th style="width: 500px">Barang</th><th style="width: 130px">Stok Minimal</th><th style="width: 130px">Stok Maksimal</th><th style="width: 100px">Stok Tersedia</th><th style="width: 50px">Aksi</th></tr>
        </thead>
        <tbody></tbody>
    </table>
</form>

<script>
    idx = 0;
    function addUnitItem() {
        idx++;
        if ($("#tbl_addMinMax tbody").html().indexOf("Tidak ada data")>-1)
            $("#tbl_addMinMax tbody").empty();
        $("#tbl_addMinMax tbody").append("<tr id='tr_newItem_" + idx + "'><td><input type='text' id='inp_newItem_" + idx + "' name='inp_newItem_" + idx + "' class='form-control inline-input' style='width: 200px' placeholder='Ketikkan nama barang...' onKeyUp='getUnitItem(&quot;newItem_" + idx + "&quot;)' autocomplete='off'><span class='text-red' title='Batal' style='padding: 4px' onClick='$(&quot;#tr_newItem_" + idx + "&quot;).fadeOut()'><i class='fa fa-times'></i></span></td><td colspan='3'></td></tr>");
        $("#inp_newItem_" + idx).focus();
    }
    function getUnitItem(where) {
        unit = $("#hdn_unit").val()
        term = $("#inp_" + where).val();
        data = $("#hdn_MinMaxitems").val();
        if (term.length > 2) ajax_getData("ajax_getdata.php?type=minmaxmadditem&from=" + where + "&term=" + term + "&unit=" + unit + "&data=" + btoa(data));
            else removeSuggest();
    }
    function removeItem(id) {
        items = $("#hdn_MinMaxitems").val().split(",");
        for (i=0; i<items.length; i++) {
            if (items[i] == id) {
                items.splice(i, 1);
                break;
            }
        }
        $("#hdn_MinMaxitems").val(items.join(","));
        $("#tr_" + id).fadeOut();
        $("#tr_" + id).remove();
        recheckMinMax();
    }
    function validateForm() {
        items = $("#hdn_MinMaxitems").val();
        if (items.length < 1) {
            alert("Tidak ada data yang disimpan");
            return false;
        } else {
            items = items.split(",");
            for (i=0; i<items.length; i++) {
                min = toNumber($("#inp_min_" + items[i]).val());
                max = toNumber($("#inp_max_" + items[i]).val());
                if (max == 0) {
                    alert("Jumlah stok maksimal tidak boleh nol");
                    $("#inp_max_" + items[i]).focus();
                    return false;
                } else if (min > max) {
                    alert("Jumlah stok minimal lebih besar daripada stok maksimal");
                    $("#inp_min_" + items[i]).focus();
                    return false;
                }
            }
        }
        return true;
    }
</script>

<?php require_once '../template/footer.php' ?>