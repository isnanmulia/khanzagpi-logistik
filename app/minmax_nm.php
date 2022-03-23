<?php
    require_once '../template/header.php';
    require_once '../config/connect_app.php';
    require_once '../function/access.php';
    require_once '../function/function.php';
    restrictAccess("rekap_pemesanan_non_medis");
    if (isset($_POST["form_submit"])) {
        mysqli_autocommit($connect_app, FALSE);
        $items = explode(",", $_POST["hdn_MinMaxitems"]);
        $status = TRUE;
        foreach ($items as $itm) {
            $itmUpdate = [];
            $qMinMax = "";
            if (isset($_POST["hdn_min_" . $itm]) && isset($_POST["hdn_max_" . $itm])) {
                $inp_min = str_replace(".", "", $_POST["inp_min_" . $itm]);
                $inp_max = str_replace(".", "", $_POST["inp_max_" . $itm]);
                if ($_POST["hdn_min_" . $itm]!=$inp_min)
                    array_push($itmUpdate, "min_stok=" . $inp_min);
                if ($_POST["hdn_max_" . $itm]!=$inp_max)
                    array_push($itmUpdate, "max_stok=" . $inp_max);
                if (count($itmUpdate))
                    $qMinMax = "UPDATE gpi_minmax_nonmedis SET " . implode(",", $itmUpdate) . " WHERE kode_brng='" . $itm . "' AND kd_bangsal='" . $_POST["hdn_bangsal"] . "'";
            } else {
                $qMinMax = "INSERT INTO gpi_minmax_nonmedis VALUES ('" . $itm . "', '" . $_POST["hdn_bangsal"] . "', " . $_POST["inp_min_" . $itm] . ", " . $_POST["inp_max_" . $itm] . ")";
            }
            if (strlen($qMinMax)) {
                $minMax = mysqli_query($connect_app, $qMinMax);
                saveToTracker($qMinMax, $connect_app);
                $status = $status && $minMax;
            }
        }
        if ($status) {
            mysqli_commit($connect_app);
            $msg = "Sukses mengatur Stok Minimal/Maksimal Per Unit";
        } else {
            logError("minmax_nm.php", "minMax:" . $minMax . " " . $qMinMax);
            mysqli_rollback($connect_app);
            $msg = "Gagal mengatur Stok Minimal/Maksimal Per Unit";
        }
        mysqli_autocommit($connect_app, TRUE);
        echo "<script>alert('" . $msg . "'); window.location.href='minmax_nm.php?unit=" . $_POST["hdn_bangsal"] . "'</script>";
    }
?>

<form name="frmMinMaxNM" method="POST" target="" onSubmit="return validateForm()">
    Unit&nbsp;&nbsp;
    <input type="text" id="inp_bangsal" name="inp_bangsal" class="form-control inline-input" autocomplete="off" onKeyUp="getUnit()" style="width: 200px"><input type="hidden" id="hdn_bangsal" name="hdn_bangsal"> <button type="button" id="btn_clearBangsal" class="btn btn-primary btn-sm" onClick="clearUnit()" title="Bersihkan" disabled><i class="glyphicon glyphicon-erase"></i></button>
    <div id="div_unitDtl" style="display: none; height: 500px; margin-top: 10px; padding: 10px 20px; background-color: #eee">
        <h4 id="h4_unit" style="display: inline-block"></h4>
        <span style="margin-left: 50px">
            <input type="text" id="inp_filterItem" name="inp_filterItem" placeholder="Cari barang..." class="form-control inline-input" style="width: 200px" autocomplete="off"> <button type="button" id="btn_tambah" class="btn btn-primary btn-sm" onClick="addUnitItem2()" <?php if (!getAccess("rekap_permintaan_non_medis")) { ?>disabled<?php } ?>><i class="fa fa-plus"></i> Tambah Barang</button><input type="hidden" id="form_submit" name="form_submit" value="1"><input type="hidden" id="hdn_MinMaxitems" name="hdn_MinMaxitems">
        </span>
        <div id="div_lstItem" style="height: 400px; overflow:scroll;">
            <table id="tbl_lstItem" class="table table-hover">
                <thead>
                    <tr><th style="width: 500px">Barang</th><th style="width: 130px">Stok Minimal</th><th style="width: 130px">Stok Maksimal</th><th style="width: 100px">Stok Tersedia</th><th style="width: 50px">Aksi</th></tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <!-- span style="float: right; padding-top: 10px"><button id="btn_submit" type="submit" class="btn btn-primary btn-sm" disabled><i class="fa fa-save"></i> Simpan</button></span -->
    </div>
</form>

<script>
    idx = 0;
    function getUnit() {
        term = $("#inp_bangsal").val();
        ajax_getData("ajax_getdata.php?type=minmaxnmunit&term=" + term);
    }
    function clearUnit() {
        ajax_getData("ajax_getdata.php?type=minmaxnmunit&clear");
    }
    function addUnitItem() {
        idx++;
        if ($("#tbl_lstItem tbody").html().indexOf("Tidak ada data")>-1)
            $("#tbl_lstItem tbody").empty();
        $("#tbl_lstItem tbody").append("<tr id='tr_newItem_" + idx + "'><td><input type='text' id='inp_newItem_" + idx + "' name='inp_newItem_" + idx + "' class='form-control inline-input' style='width: 200px' placeholder='Ketikkan nama barang...' onKeyUp='getUnitItem(&quot;newItem_" + idx + "&quot;)' autocomplete='off'><span class='text-red' title='Batal' style='padding: 4px' onClick='$(&quot;#tr_newItem_" + idx + "&quot;).fadeOut()'><i class='fa fa-times'></i></span></td><td colspan='3'></td></tr>");
        $("#inp_newItem_" + idx).focus();
    }
    function addUnitItem2() {
        unit = $("#hdn_bangsal").val();
        location.href = "add_minmax_nm.php?unit=" + unit;
    }
    function getUnitItem(where) {
        unit = $("#hdn_bangsal").val()
        term = $("#inp_" + where).val();
        data = $("#hdn_MinMaxitems").val();
        if (term.length > 2) ajax_getData("ajax_getdata.php?type=minmaxnmadditem&from=" + where + "&term=" + term + "&unit=" + unit + "&data=" + btoa(data));
            else removeSuggest();
    }
    function editMinMax(id) {
        $("#span_min_" + id).hide();
        $("#span_max_" + id).hide();
        $("#btnEdit_" + id).hide();
        $("#inp_min_" + id).fadeIn();
        $("#inp_max_" + id).fadeIn();
        $("#btnBatal_" + id).fadeIn();
    }
    function editMinMax2(id) {
        unit = $("#hdn_bangsal").val();
        window.open("edit_minmax_nm.php?unit=" + unit + "&id=" + id, "_blank", "width=500, height=300");
    }
    function cancelEditMinMax(id) {
        $("#inp_min_" + id).fadeOut();
        $("#inp_max_" + id).fadeOut();
        $("#btnBatal_" + id).fadeOut();
        setTimeout(function() {
            $("#span_min_" + id).show();
            $("#span_max_" + id).show();
            $("#btnEdit_" + id).show();
            $("#inp_min_" + id).val(formatNumber($("#hdn_min_" + id).val()));
            $("#inp_max_" + id).val(formatNumber($("#hdn_max_" + id).val()));
            recheckMinMax();
        },400);
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
        return true;
    }
    $(document).ready(function(){
        $("#inp_filterItem").on("keyup", function(){
            var term = $(this).val().toLowerCase();
            $("#tbl_lstItem tbody tr").filter(function(){
                $(this).toggle($(this).text().toLowerCase().indexOf(term)>-1);
            });
        });
    });
    setTimeout(function() {
        unit = getQueryVariable("unit");
        if (unit.length) {
            ajax_getData("ajax_getdata.php?type=minmaxnmunit&id=" + unit);
        }
    }, 500);
</script>

<?php require_once '../template/footer.php' ?>