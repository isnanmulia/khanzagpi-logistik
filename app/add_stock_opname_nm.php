<?php
    require_once '../template/header.php';
    require_once '../config/connect_app.php';
    require_once '../function/access.php';
    require_once '../function/function.php';
    restrictAccess("stok_opname_logistik");
    if (isset($_POST["form_submit"])) {
        // var_dump($_POST); die();
        mysqli_autocommit($connect_app, FALSE);
        $items = explode(",", $_POST["hdn_SOitems"]);
        $status = TRUE;
        foreach ($items as $item) {
            $jml = str_replace(",", ".", str_replace(".", "", $_POST["inp_jml_" . $item]));
            $qInsertSO = "INSERT INTO ipsrsopname (kode_brng, h_beli, tanggal, stok, `real`, selisih, nomihilang, lebih, nomilebih, keterangan, kd_bangsal) VALUES ('" . $item . "', " . $_POST["hdn_dasar_" . $item] . ", '" . $today . "', " . $_POST["hdn_stokasal_" . $item] . ", " . $jml . ", " . $_POST["hdn_selisih_" . $item] . ", " . $_POST["hdn_nomhilang_" . $item] . ", " . $_POST["hdn_lebih_" . $item] . ", " . $_POST["hdn_nomlebih_" . $item] . ", '" . $_POST["inp_keterangan"] . "', '" . $_POST["hdn_serviceunit"] . "')";
            $insertSO = mysqli_query($connect_app, $qInsertSO);
            saveToTracker($qInsertSO, $connect_app);
            $qInsertHistory = "INSERT INTO gpi_riwayat_barang_nonmedis (kode_brng, stok_awal, masuk, keluar, stok_akhir, posisi, tanggal, jam, petugas, kd_bangsal, status, no_batch, no_faktur) VALUES ('" . $item . "', " . $_POST["hdn_stokasal_" . $item] . ", " . $jml . ", 0, " . $jml . ", 'Opname', '" . $today . "', '" . date("H:i:s") . "', '" . $_SESSION["USER"]["USERNAME"] . "', '" . $_POST["hdn_serviceunit"] . "', 'Simpan', '', '')";
            $insertHistory = mysqli_query($connect_app, $qInsertHistory);
            saveToTracker($qInsertHistory, $connect_app);
            $qCheckDepot = "SELECT stok FROM gpi_gudangbarangipsrs WHERE kode_brng='" . $item . "' AND kd_bangsal='" . $_POST["hdn_serviceunit"] . "'";
            $checkDepot = mysqli_query($connect_app, $qCheckDepot);
            $depot = mysqli_fetch_assoc($checkDepot);
            if ($depot) {
                $qDepot = "UPDATE gpi_gudangbarangipsrs SET stok=" . $jml . " WHERE kode_brng='" . $item . "' AND kd_bangsal='" . $_POST["hdn_serviceunit"] . "' AND no_batch='' AND no_faktur=''";
            } else {
                $qDepot = "INSERT INTO gpi_gudangbarangipsrs (kode_brng, kd_bangsal, stok) VALUES ('" . $item . "', '" . $_POST["hdn_serviceunit"] . "', " . $jml . ")";
            }
            $depot = mysqli_query($connect_app, $qDepot);
            saveToTracker($qDepot, $connect_app);
            $status = $status && ($insertSO && $insertHistory && $depot);
        }
        if ($status) {
            mysqli_commit($connect_app, TRUE);
            $msg = "Sukses menyimpan Stok Opname";
        } else {
            logError("add_stock_opname_nm.php", "insertSO:" . $insertSO . "|insertHistory:" . $insertHistory . "|depot:" . $depot);
            mysqli_rollback($connect_app);
            $msg = "Gagal menyimpan Stok Opname";
            die();
        }
        mysqli_autocommit($connect_app, TRUE);
        echo "<script>alert('" . $msg . "'); window.location.href='stock_opname_nm.php'</script>";
    }
?>

<form name="frmStokOpname" method="POST" target="" onSubmit="return validateForm()">
    <table id="tbl_stokopname" class="table">
        <tr>
            <td colspan="2">
                Tanggal:&nbsp;&nbsp;<?php echo $today ?><input type="hidden" id="inp_tanggal" name="inp_tanggal" value="<?php echo $today ?>"><span class="inline-spacer"></span>
                Lokasi <span class="span-required">*</span>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="inp_serviceunit" name="inp_serviceunit" class="form-control inline-input" style="width: 200px" onKeyUp="getServiceUnit()" autocomplete="off"><input type="hidden" id="hdn_serviceunit" name="hdn_serviceunit"><span class="inline-spacer"></span>
                Keterangan <span class="span-required">*</span>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="inp_keterangan" name="inp_keterangan" class="form-control inline-input" style="width: 300px" autocomplete="off">
            </td>
        </tr>
        <tr>
            <td style="width: 150px">Barang Stock Opname</td>
            <td style="width: 850px">
                <a class="btn btn-sm btn-primary" onClick="addStockOpnameItem()"><i class="fa fa-plus"></i> Tambah</a>
                <div id="div-scroll" style="height: 300px; overflow: scroll">
                    <input type="hidden" id="hdn_SOitems" name="hdn_SOitems">
                    <table class="table table-hover" id="tbl_listItems">
                        <thead>
                            <tr>
                                <th style="width: 90px">Kode</th><th style="min-width: 200px">Nama Barang</th><th style="min-width: 60px">Satuan</th><th style="width: 90px">Harga</th><th style="min-width: 80px">Stok</th><th colspan="2" style="min-width: 80px">Real</th><th style="min-width: 80px">Selisih</th><th style="min-width: 80px">Lebih</th><th style="width: 90px">Nominal Hilang</th><th style="width: 90px">Nominal Lebih</th><th style="width: 45px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: right">
                Hilang:&nbsp;&nbsp;<span id="span_nom_hilang" name="span_nom_hilang" class="span-amount">0</span><input type="hidden" id="inp_nom_hilang" name="inp_nom_hilang" value="0"><span class="inline-spacer">|</span>
                Lebih:&nbsp;&nbsp;<span id="span_nom_lebih" name="span_nom_lebih" class="span-amount">0</span><input type="hidden" id="inp_nom_lebih" name="inp_nom_lebih" value="0"><span class="inline-spacer"></span>
                <input type="hidden" id="form_submit" name="form_submit" value="1">
                <a class="btn btn-primary" href="#" onClick="history.back()"><i class="fa fa-times"></i> Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
            </td>
        </tr>
    </table>
</form>

<script>
    idx = 0;
    function addStockOpnameItem() {
        loc = $("#hdn_serviceunit").val();
        if (!loc) {
            alert("Lokasi kosong");
            $("#inp_serviceunit").focus();
        } else {
            idx++;
            $("#tbl_listItems tbody").append("<tr id='tr_newItem_" + idx + "'><td></td><td><input type='text' id='inp_newItem_" + idx + "' name='inp_newItem_" + idx + "' class='form-control' style='display: inline-block; width: 170px' placeholder='Ketikkan nama barang...' onKeyUp='getItem(&quot;newItem_" + idx + "&quot;)' autocomplete='off'><span class='text-red' title='Batal' style='padding: 4px' onClick='$(&quot;#tr_newItem_" + idx + "&quot;).fadeOut()'><i class='fa fa-times'></i></span></td></tr>");
            $("#inp_newItem_" + idx).focus();
        }
    }
    function getItem(where) {
        term = $("#inp_" + where).val();
        loc = $("#hdn_serviceunit").val();
        if (term.length > 0) {
            data = $("#hdn_SOitems").val();
            ajax_getData("ajax_getdata.php?type=sonmadd&from=" + where + "&loc=" + loc + "&term=" + term + "&data=" + btoa(data));
        } else removeSuggest();
    }
    function removeItem(id) {
        items = $("#hdn_SOitems").val().split(",");
        for (i=0; i<items.length; i++) {
            if (items[i] == id) {
                items.splice(i, 1);
                break;
            }
        }
        $("#hdn_SOitems").val(items.join(","));
        $("#tr_" + id).fadeOut();
        $("#tr_" + id).remove();
        recheckSO();
    }
    function validateForm() {
        loc = $("#hdn_serviceunit").val();
        nmloc = $("#inp_serviceunit").val();
        ket = $("#inp_keterangan").val();
        items = $("#hdn_SOitems").val();
        if (!loc) {
            alert("Lokasi kosong");
            $("#inp_serviceunit").focus();
            return false;
        } else if (!ket) {
            alert("Keterangan kosong");
            $("#inp_keterangan").focus();
            return false;
        } else if (!items) {
            alert("tidak ada barang yang diinput");
            return false;
        } else {
            items = items.split(",");
            for (i=0; i<items.length; i++) {
                isinput = $("#hdn_isinput_" + items[i]).val();
                nmbarang = $("#span_name_" + items[i]).html();
                if (isinput == "1") {
                    alert("Sudah ada input stok untuk barang " + nmbarang + " di lokasi " + nmloc + " pada periode Stok Opname yang sedang berlangsung");
                    return false;
                }
            }
        }
        return true;
    }
</script>

<?php require_once '../template/footer.php' ?>