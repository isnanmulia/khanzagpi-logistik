<?php
    require_once '../template/header.php';
    require_once '../config/connect_app.php';
    require_once '../function/access.php';
    require_once '../function/function.php';
    restrictAccess("permintaan_non_medis");
    if (isset($_POST["form_submit"])) {
        // var_dump($_POST); die();
        mysqli_autocommit($connect_app, FALSE);
        $qGetLastNo = "SELECT IfNull(Max(Convert(Right(no_permintaan,3),signed)),0) AS lastNo FROM permintaan_non_medis WHERE tanggal='" . $today . "'";
        $getLastNo = mysqli_query($connect_app, $qGetLastNo);
        $lastNo = mysqli_fetch_assoc($getLastNo)["lastNo"] + 1;
        $noReq = "PN" . date("ymd") . str_pad($lastNo, 3, "0", STR_PAD_LEFT);
        $qInsertRequest = "INSERT INTO permintaan_non_medis (no_permintaan, nip, tanggal, kd_bangsal, kd_bangsaltujuan, status) VALUES ('" . $noReq . "', '" . $_POST["hdn_user"] . "', '" . $today . "', '" . $_POST["hdn_serviceunitfrom"] . "', '" . $_POST["hdn_serviceunitto"] . "', 'Baru')";
        $insertRequest = mysqli_query($connect_app, $qInsertRequest);
        $items = explode(",", $_POST["hdn_Requestitems"]);
        $status = $insertRequest;
        saveToTracker($qInsertRequest, $connect_app);
        foreach ($items as $item) {
            $qInsertRequestDtl = "INSERT INTO detail_permintaan_non_medis (no_permintaan, kode_brng, kode_sat, jumlah, keterangan, status) VALUES ('" . $noReq . "', '" . $item . "', '" . $_POST["hdn_sat_" . $item] . "', " . str_replace(",", ".", str_replace(".", "", $_POST["inp_jml_" . $item])) . ", '" . $_POST["inp_keterangan_" . $item] . "', 'Proses Permintaan')";
            $insertRequestDtl = mysqli_query($connect_app, $qInsertRequestDtl);
            saveToTracker($qInsertRequestDtl, $connect_app);
            $status = $status && $insertRequestDtl;
        }
        if ($status) {
            mysqli_commit($connect_app);
            $msg = "Sukses menyimpan Permintaan";
        } else {
            logError("add_item_request_nm.php", "insertRequest:" . $insertRequest . "|insertRequestDtl:" . $insertRequestDtl);
            mysqli_rollback($connect_app);
            $msg = "Gagal menyimpan Permintaan";
        }
        mysqli_autocommit($connect_app, TRUE);
        echo "<script>alert('" . $msg . "'); window.location.href='item_request_nm.php';</script>";
    }
?>

<form name="frmAddRequest" method="POST" target="" onSubmit="return validateForm()">
    <table id="tbl_addRequest" class="table">
        <tr>
            <td style="width: 150px">No Permintaan</td>
            <td style="width: 350px">PNYYMMDDXXX</td>
            <td style="width: 150px">Asal Permintaan <span class="span-required">*</span></td>
            <td style="width: 350px"><input type="text" id="inp_serviceunitfrom" name="inp_serviceunitfrom" class="form-control inline-input" style="width: 200px" onKeyUp="getServiceUnit('from')" autocomplete="off"><input type="hidden" id="hdn_serviceunitfrom" name="hdn_serviceunitfrom"></td>
        </tr>
        <tr>
            <td>Petugas</td>
            <td><?php if ($_SESSION["USER"]["USERNAME"] != "admin") echo $_SESSION["USER"]["FULLNAME"]; else echo "-"; ?><input type="hidden" id="hdn_user" name="hdn_user" value="<?php if ($_SESSION["USER"]["USERNAME"] != "admin") echo $_SESSION["USER"]["USERNAME"]; else echo "-"; ?>"></td>
            <td>Ditujukan Ke <span class="span-required">*</span></td>
            <td>GUDANG NON MEDIS<input type="hidden" id="hdn_serviceunitto" name="hdn_serviceunitto" value="GUDNM"></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td><?php echo $today ?><input type="hidden" id="inp_tanggal" name="inp_tanggal" value="<?php echo $today ?>"></td>
        </tr>
        <tr>
            <td>Barang yang Diminta</td>
            <td colspan="3">
                <a class="btn btn-sm btn-primary" onClick="addRequestItem()"><i class="fa fa-plus"></i> Tambah</a>
                <div id="div-scroll" style="height: 300px; overflow: scroll;">
                    <input type="hidden" id="hdn_Requestitems" name="hdn_Requestitems">
                    <table class="table table-hover" id="tbl_listItems">
                        <thead>
                            <tr>
                                <th style="min-width: 90px">Kode Barang</th><th style="min-width: 300px">Nama Barang</th><th style="min-width: 85px">Jenis Barang</th><th colspan="2" style="min-width: 140px">Jumlah</th><th style="min-width: 150px">Keterangan</th><th style="min-width: 70px">Stok Asal</th><th style='min-width: 45px'>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: right">
                <input type="hidden" id="form_submit" name="form_submit" value="1">
                <a class="btn btn-primary" href="#" onClick="history.back()">
                    <i class="fa fa-times"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Simpan
                </button>
            </td>
        </tr>
    </table>
</form>

<script>
    idx = 0;
    function addRequestItem() {
        frward = $("#hdn_serviceunitfrom").val();
        if (!frward) {
            alert("Asal Permintaan kosong");
            $("#inp_serviceunitfrom").focus();
        } else {
            idx++;
            $("#tbl_listItems tbody").append("<tr id='tr_newItem_" + idx + "'><td></td><td><input type='text' id='inp_newItem_" + idx + "' name='inp_newItem_" + idx + "' class='form-control' style='display: inline-block; width: 170px' placeholder='Ketikkan nama barang...' onKeyUp='getItem(&quot;newItem_" + idx + "&quot;)' autocomplete='off'><span class='text-red' title='Batal' style='padding: 4px' onClick='$(&quot;#tr_newItem_" + idx + "&quot;).fadeOut()'><i class='fa fa-times'></i></span></td></tr>");
            $("#inp_newItem_" + idx).focus();
        }
    }
    function getItem(where) {
        term = $("#inp_" + where).val();
        frward = $("#hdn_serviceunitfrom").val();
        if (term.length > 0) {
            data = $("#hdn_Requestitems").val().split(",");
            ajax_getData("ajax_getdata.php?type=reqnmadd&from=" + where + "&frward=" + frward + "&term=" + term + "&data=" + btoa(data));
        } else removeSuggest();
    }
    function removeItem(id) {
        items = $("#hdn_Requestitems").val().split(",");
        for (i=0; i<items.length; i++) {
            if (items[i] == id) {
                items.splice(i, 1);
                break;
            }
        }
        $("#hdn_Requestitems").val(items.join(","));
        $("#tr_" + id).fadeOut();
        $("#tr_" + id).remove();
        recheckRequest("N");
    }
    function validateForm() {
        user = $("#hdn_user").val();
        frward = $("#hdn_serviceunitfrom").val();
        toward = $("#hdn_serviceunitto").val();
        items = $("#hdn_Requestitems").val();
        if (user == "-") {
            alert("Admin Utama tidak diizinkan untuk membuat Permintaan");
            return false;
        } else if (!frward) {
            alert("Asal Permintaan kosong");
            $("#inp_serviceunitfrom").focus();
            return false;
        } else if (!toward) {
            alert("Ditujukan Ke kosong");
            $("#inp_serviceunitto").focus();
            return false;
        } else if (frward == toward) {
            alert("Asal Permintaan dan Ditujukan Ke tidak boleh sama");
            return false;
        } else if (!items) {
            alert("Tidak ada barang yang diminta");
            return false;
        } else {
            items = items.split(",");
            for (i=0; i<items.length; i++) {
                jml = $("#inp_jml_" + items[i]).val();
                if (!jml || jml == 0) {
                    alert("Jumlah barang yang diminta tidak boleh nol");
                    $("#inp_jml_" + items[i]).focus();
                    return false;
                }
            }
        }
        return true;
    }
</script>

<?php require_once '../template/footer.php' ?>