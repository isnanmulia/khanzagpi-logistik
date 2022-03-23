<?php
    require_once '../template/header.php';
    require_once '../config/connect_app.php';
    require_once '../function/access.php';
    require_once '../function/function.php';
    restrictAccess("pengajuan_barang_nonmedis");
    if (isset($_POST["form_submit"])) {
        mysqli_autocommit($connect_app, FALSE);
        $qGetLastNo = "SELECT IfNull(Max(Convert(Right(no_pengajuan,3),signed)),0) AS lastNo FROM pengajuan_barang_nonmedis WHERE tanggal='" . $today . "'";
        $getLastNo = mysqli_query($connect_app, $qGetLastNo);
        $lastNo = mysqli_fetch_assoc($getLastNo)["lastNo"] + 1;
        $noPR = "PBNM" . date("Ymd") . str_pad($lastNo, 3, "0", STR_PAD_LEFT);
        $qInsertPR = "INSERT INTO pengajuan_barang_nonmedis (no_pengajuan, nip, tanggal, status, keterangan) VALUES ('" . $noPR . "', '" . $_POST["hdn_user"] . "', '" . $today . "', 'Proses Pengajuan', '" . $_POST["inp_keterangan"] . "')";
        $insertPR = mysqli_query($connect_app, $qInsertPR);
        saveToTracker($qInsertPR, $connect_app);
        $items = explode(",", $_POST["hdn_PRitems"]);
        $status = $insertPR;
        foreach($items as $item) {
            $qInsertPRDtl = "INSERT INTO detail_pengajuan_barang_nonmedis (no_pengajuan, kode_brng, kode_sat, jumlah, h_pengajuan, total, status, jumlah_disetujui) VALUES ('" . $noPR . "', '" . $item . "', '" . $_POST["hdn_satbesar_" . $item] . "', " . str_replace(",", ".", str_replace(".", "", $_POST["inp_jml_" . $item])) . ", " . $_POST["hdn_hpesan_" . $item] . ", " . $_POST["hdn_subtotal_" . $item] . ", 'Proses Pengajuan', " . str_replace(",", ".", str_replace(".", "", $_POST["inp_jml_" . $item])) . ")";
            $insertPRDtl = mysqli_query($connect_app, $qInsertPRDtl);
            saveToTracker($qInsertPRDtl, $connect_app);
            $status = $status && $insertPRDtl;
        }
        if ($status) {
            mysqli_commit($connect_app);
            $msg = "Sukses menyimpan Pengajuan";
        } else {
            logError("add_purchase_request_nm.php", "insertPR:" . $insertPR . "|insertPRDtl:" . $insertPRDtl . " " . $qInsertPRDtl);
            mysqli_rollback($connect_app);
            $msg = "Gagal menyimpan Pengajuan";
        }
        mysqli_autocommit($connect_app, TRUE);
        echo "<script>alert('" . $msg . "'); window.location.href='purchase_request_nm.php';</script>";
    }
?>

<form name="frmAddPR" method="POST" target="" onSubmit="return validateForm()">
    <table id="tbl_addPR" class="table">
        <tr>
            <td style="width: 150px">No Pengajuan</td>
            <td style="width: 350px">PBNMYYYYMMDDXXX</td>
            <td style="width: 150px">Tanggal</td>
            <td style="width: 350px"><?php echo $today ?><input type="hidden" id="inp_tanggal" name="inp_tanggal" value="<?php echo $today ?>"></td>
        </tr>
        <tr>
            <td>Petugas</td>
            <td>
                <?php if ($_SESSION["USER"]["USERNAME"] != "admin") echo $_SESSION["USER"]["FULLNAME"]; else echo "-"; ?>
                <input type="hidden" id="hdn_user" name="hdn_user" value="<?php if ($_SESSION["USER"]["USERNAME"] != "admin") echo $_SESSION["USER"]["USERNAME"]; else echo "-"; ?>">
            </td>
            <td>Keterangan</td>
            <td><input type="text" id="inp_keterangan" name="inp_keterangan" class="form-control inline-input" maxlength="150" style="width: 300px" autocomplete="off"></td>
        </tr>
        <tr>
            <td>Barang yang Diajukan</td>
            <td colspan="3">
                <a class="btn btn-sm btn-primary" onCLick="addPRItem()"><i class="fa fa-plus"></i> Tambah</a>
                <div id="div-scroll" style="height: 300px; overflow: scroll;">
                    <input type="hidden" id="hdn_PRitems" name="hdn_PRitems">
                    <table class="table table-hover" id="tbl_listItems">
                        <thead>
                            <th style="min-width: 90px">Kode</th><th style="min-width: 200px">Nama Barang</th><th title="Satuan Kecil" style="min-width: 55px">SK</th><th title="Satuan Besar" style="min-width: 55px">SB</th><th style="min-width: 50px">Isi</th><th colspan="2" style="min-width: 140px">Jml Pengajuan</th><th style="min-width: 110px">Harga Per SB</th><th style="min-width: 90px">Subtotal</th><th style="min-width: 60px">Stok Gudang</th><th style="min-width: 60px">Aksi</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" style="text-align: right">
                Total: <span id="span_total" class="span-amount">0</span><input type="hidden" id="hdn_total" name="hdn_total"><span class="inline-spacer"></span>
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
    function addPRItem() {
        idx++;
        $("#tbl_listItems tbody").append("<tr id='tr_newItem_" + idx + "'><td></td><td><input type='text' id='inp_newItem_" + idx + "' name='inp_newItem_" + idx + "' class='form-control' style='display: inline-block; width: 170px' placeholder='Ketikkan nama barang...' onKeyUp='getItem(&quot;newItem_" + idx + "&quot;)' autocomplete='off'><span class='text-red' title='Batal' style='padding: 4px' onClick='$(&quot;#tr_newItem_" + idx + "&quot;).fadeOut()'><i class='fa fa-times'></i></span></td></tr>");
        $("#inp_newItem_" + idx).focus();
    }
    function getItem(where) {
        term = $("#inp_" + where).val();
        if (term.length > 0) {
            data = $("#hdn_PRitems").val().split(",");
            ajax_getData("ajax_getdata.php?type=prnmadd&from=" + where + "&term=" + term + "&data=" + btoa(data));
        } else removeSuggest();
    }
    function removeItem(id) {
        items = $("#hdn_PRitems").val().split(",");
        for (i=0; i<items.length; i++) {
            if (items[i] == id) {
                items.splice(i, 1);
                break;
            }
        }
        $("#hdn_PRitems").val(items.join(","));
        $("#tr_" + id).fadeOut();
        $("#tr_" + id).remove();
    }
    function validateForm() {
        user = $("#hdn_user").val();
        items = $("#hdn_PRitems").val();
        if (user == "-") {
            alert("Admin Utama tidak diizinkan untuk membuat Pengajuan");
            return false;
        } else if (!items) {
            alert("Tidak ada barang yang diminta");
            return false;
        } else {
            items = items.split(",");
            for (i=0; i<items.length; i++) {
                jml = $("#inp_jml_" + items[i]).val();
                if (!jml || jml == 0) {
                    alert("Jumlah barang yang diajukan tidak boleh nol");
                    $("#inp_jml_" + items[i]).focus();
                    return false;
                }
            }
        }
        return true;
    }
</script>

<?php require_once '../template/footer.php' ?>