<?php
    require_once '../template/header.php';
    require_once '../config/connect_app.php';
    require_once '../function/access.php';
    require_once '../function/function.php';
    restrictAccess("surat_pemesanan_medis");
    if (isset($_POST["form_submit"])) {
        mysqli_autocommit($connect_app, FALSE);
        $qGetLastNo = "SELECT IfNull(Max(Convert(Right(no_pemesanan,3),signed)),0) AS lastNo FROM surat_pemesanan_medis WHERE tanggal='" . $today . "'";
        $getLastNo = mysqli_query($connect_app, $qGetLastNo);
        $lastNo = mysqli_fetch_assoc($getLastNo)["lastNo"] + 1;
        $noSPM = "SPM" . date("ymd") . str_pad($lastNo, 3, "0", STR_PAD_LEFT);
        $qInsertPO = "INSERT INTO surat_pemesanan_medis (no_pemesanan, kode_suplier, nip, tanggal, total1, potongan, total2, ppn, meterai, tagihan, status, catatan) VALUES ('" . $noSPM . "', '" . $_POST["hdn_supplier"] . "', '" . $_POST["hdn_user"] . "', '" . $today . "', " . $_POST["hdn_total1"] . ", " . $_POST["hdn_potongan"] . ", " . $_POST["hdn_total2"] . ", " . $_POST["hdn_ppn"] . ", " . str_replace(".", "", $_POST["inp_meterai"]) . ", " . $_POST["hdn_jml_tagihan"] . ", 'Baru', '" . $_POST["inp_catatan"] . "')";
        $insertPO = mysqli_query($connect_app, $qInsertPO);
        saveToTracker($qInsertPO, $connect_app);
        $items = explode(",", $_POST["hdn_POitems"]);
        $status = $insertPO;
        foreach ($items as $item) {
            $kode = $item;
            $noPR = "";
            if (strpos($item, "_") !== FALSE) {
                $itemx = explode("_", $item);
                $qUpdatePRDtl = "UPDATE detail_pengajuan_barang_medis SET status='Disetujui' WHERE no_pengajuan='" . $itemx[1] . "' AND kode_brng='" . $itemx[0] . "'";
                $updatePRDtl = mysqli_query($connect_app, $qUpdatePRDtl);
                saveToTracker($qUpdatePRDtl, $connect_app);
                $qCheckPRDtl = "SELECT(SELECT Count(kode_brng) FROM detail_pengajuan_barang_medis WHERE no_pengajuan='" . $itemx[1] . "') AS X, (SELECT Count(kode_brng) FROM detail_pengajuan_barang_medis WHERE no_pengajuan='" . $itemx[1] . "' AND status='Disetujui') AS Y";
                $checkPRDtl = mysqli_query($connect_app, $qCheckPRDtl);
                $PRDtl = mysqli_fetch_assoc($checkPRDtl);
                if ($PRDtl["X"] == $PRDtl["Y"]) {
                    $qUpdatePR = "UPDATE pengajuan_barang_medis SET status='Disetujui' WHERE no_pengajuan='" . $itemx[1] . "'";
                    $updatePR = mysqli_query($connect_app, $qUpdatePR);
                    saveToTracker($qUpdatePR, $connect_app);
                } else $updatePR = TRUE;
                $item = $itemx[0];
                $noPR = $itemx[1];
            } else {
                $updatePRDtl = TRUE;
                $updatePR = TRUE;
            }
            $qInsertPODtl = "INSERT INTO detail_surat_pemesanan_medis (no_pemesanan, kode_brng, no_pr_ref, kode_sat, kode_satbesar, isi, jumlah, h_pesan, subtotal, dis, dis2, besardis, total, status, jumlah2) VALUES ('" . $noSPM . "', '" . $item . "', '" . $noPR . "', '" . $_POST["hdn_sk_" . $kode] . "', '" . $_POST["hdn_sb_" . $kode] . "', '" . $_POST["hdn_fk_" . $kode] . "', '" . str_replace(".", "", $_POST["inp_jml_" . $kode]) . "', " . str_replace(".", "", $_POST["inp_hpesan_" . $kode]) . ", " . $_POST["hdn_subtotal_" . $kode] . ", " . str_replace(",", ".", $_POST["inp_disc1_" . $kode]) .", " . str_replace(",", ".", $_POST["inp_disc2_" . $kode]) .", " . $_POST["hdn_ndisc_" . $kode] . ", " . $_POST["hdn_total_" . $kode] . ", 'Baru', " . str_replace(".", "", $_POST["inp_jml_" . $kode]) . ")";
            $insertPODtl = mysqli_query($connect_app, $qInsertPODtl);
            saveToTracker($qInsertPODtl, $connect_app);
            $status = $status && ($insertPODtl && $updatePR && $updatePRDtl);
        }
        if ($status) {
            mysqli_commit($connect_app);
            $msg = "Sukses menyimpan Surat Pemesanan";
        } else {
            logError("add_purchase_order_m.php", "insertPO:" . $insertPO . "|updatePRDtl:" . $updatePRDtl . "|updatePR:" . $updatePR . "|insertPODtl:" . $insertPODtl);
            mysqli_rollback($connect_app);
            $msg = "Gagal menyimpan Surat Pemesanan";
        }
        mysqli_autocommit($connect_app, TRUE);
        echo "<script>alert('" . $msg . "'); window.location.href='purchase_order_m.php';</script>";
    }
?>

<form name="frmAddPO" method="POST" target="" onSubmit="return validateForm()">
    <table id="tbl_addPO" class="table">
        <tr>
            <td style="width: 150px">No Pemesanan</td>
            <td style="width: 350px">SPMYYMMDDXXX</td>
            <td style="width: 150px">Supplier <span class="span-required">*</span></td>
            <td style="width: 350px"><input type="text" id="inp_supplier" name="inp_supplier" class="form-control inline-input" style="width: 400px" onKeyUp="getSupplier()" autocomplete="off"><input type="hidden" id="hdn_supplier" name="hdn_supplier"></td>
        </tr>
        <tr>
            <td>Petugas</td>
            <td>
                <?php if ($_SESSION["USER"]["USERNAME"] != "admin") echo $_SESSION["USER"]["FULLNAME"]; else echo "-"; ?>
                <input type="hidden" id="hdn_user" name="hdn_user" value="<?php if ($_SESSION["USER"]["USERNAME"] != "admin") echo $_SESSION["USER"]["USERNAME"]; else echo "-"; ?>">
            </td>
            <td>Catatan</td>
            <td rowspan="2"><textarea id="inp_catatan" name="inp_catatan" maxlength="1000" class="form-control" rows="3" style="resize: none"></textarea></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td><?php echo $today ?><input type="hidden" id="inp_tanggal" name="inp_tanggal" value="<?php echo $today ?>"></td>
        </tr>
        <tr>
            <td>Barang yang Dipesan</td>
            <td colspan="3">
                <a class="btn btn-sm btn-primary" onClick="addPOItem()"><i class="fa fa-plus"></i> Tambah</a>
                <a class="btn btn-sm btn-primary" onClick="addPOItem('PR')"><i class="fa fa-check-square-o"></i> Pilih dari Pengajuan</a>
                <div id="div-scroll" style="height: 300px; overflow: scroll;">
                    <input type="hidden" id="hdn_PRitems" name="hdn_PRitems"><input type="hidden" id="hdn_POitems" name="hdn_POitems">
                    <table class="table table-hover" id="tbl_listItems">
                        <thead>
                            <!-- tr>
                                <th rowspan="2" style="min-width: 90px;">Kode</th><th rowspan="2" style="min-width: 200px;">Nama Barang</th><th colspan="2" style="min-width: 120px;">Pengajuan</th><th rowspan="2" title="Faktor Konversi" style="min-width: 50px;">FK</th><th colspan="2" style="min-width: 120px;">Pemesanan</th><th rowspan="2" style="min-width: 110px;">Harga Per SB</th><th rowspan="2" style="min-width: 90px;">Subtotal</th><th rowspan="2" style="min-width: 70px;">Diskon 1 (%)</th><th rowspan="2" style="min-width: 70px;">Diskon 2 (%)</th><th rowspan="2" style="min-width: 90px;">Total Diskon</th><th rowspan="2" style="min-width: 90px; background-color: #EEE">Total</th><th rowspan="2" style="min-width: 60px">Stok RS</th><th rowspan="2" style="min-width: 115px">No. Pengajuan</th><th rowspan="2" style="min-width: 45px;">Aksi</th>
                            </tr>
                            <tr>
                                <th style="min-width: 65px;">Jml</th><th title="Satuan Kecil" style="min-width: 55px;">SK</th><th style="min-width: 70px;">Jml</th><th title="Satuan Besar" style="min-width: 55px;">SB</th>
                            </tr -->
                            <tr>
                                <th style="min-width: 90px;">Kode</th><th style="min-width: 200px;">Nama Barang</th><th title="Satuan Kecil" style="min-width: 55px;">SK</th><th title="Satuan Besar" style="min-width: 55px;">SB</th><th style="min-width: 50px;">Isi</th><th style="min-width: 110px;">Jml Pengajuan</th><th colspan="2" style="min-width: 140px;">Jml Pesan</th><th style="min-width: 110px;">Harga Per SB</th><th style="min-width: 90px;">Subtotal</th><th style="min-width: 70px;">Diskon 1 (%)</th><th style="min-width: 70px;">Diskon 2 (%)</th><th style="min-width: 90px;">Total Diskon</th><th style="min-width: 90px; background-color: #EEE">Total</th><th style="min-width: 140px">Stok RS</th><th style="min-width: 115px">No. Pengajuan</th><th style="min-width: 45px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3">
                Subtotal: <span id="span_total1" class="span-amount">0</span><input type="hidden" id="hdn_total1" name="hdn_total1"><span class="inline-spacer">|</span>
                Potongan: <span id="span_potongan" class="span-amount">0</span><input type="hidden" id="hdn_potongan" name="hdn_potongan"><span class="inline-spacer">|</span>
                Total: <span id="span_total2" class="span-amount">0</span><input type="hidden" id="hdn_total2" name="hdn_total2"><span class="inline-spacer">|</span>
                PPN (%) <input type="text" id="inp_ppn" name="inp_ppn" class="form-control inline-input num-input" value="10" maxlength="2" onKeyUp="removeNonNumeric('inp_ppn'); recalculatePO()" style="width: 40px" autocomplete="off"><span id="span_ppn" class="span-amount">0</span><input type="hidden" id="hdn_ppn" name="hdn_ppn"><span class="inline-spacer">|</span>
                Meterai <input type="text" id="inp_meterai" name="inp_meterai" class="form-control inline-input num-input" value="0" maxlength="6" onKeyUp="removeNonNumeric('inp_meterai'); recalculatePO()" style="width: 65px" autocomplete="off"><span class="inline-spacer">|</span>
                Jumlah tagihan: <span id="span_jml_tagihan" class="span-amount">0</span><input type="hidden" id="hdn_jml_tagihan" name="hdn_jml_tagihan">
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
    function addPOItem(type = "") {
        if (type == "PR") {
            data = $("#hdn_PRitems").val();
            window.open("select_pr_m.php?data=" + btoa(data), "_blank", "width=1000, height=500");
        } else {
            idx++;
            $("#tbl_listItems tbody").append("<tr id='tr_newItem_" + idx + "'><td></td><td><input type='text' id='inp_newItem_" + idx + "' name='inp_newItem_" + idx + "' class='form-control' style='display: inline-block; width: 170px;' placeholder='Ketikkan nama barang...' onKeyUp='getItemNonPR(&quot;newItem_" + idx + "&quot;)' autocomplete='off'><span class='text-red' title='Batal' style='padding: 4px;' onClick='$(&quot;#tr_newItem_" + idx + "&quot;).fadeOut()'><i class='fa fa-times'></i></span></td><td colspan='12'></td></tr>");
            $("#inp_newItem_" + idx).focus();
        }
    }
    function getItemNonPR(where) {
        term = $("#inp_" + where).val();
        if (term.length > 2) {
            data = $("#hdn_POitems").val().split(",");
            for (i=data.length; i>0; i--) {
                if (data[i-1].indexOf("_") > -1) {
                    data.splice(i-1, 1);
                }
            }
            ajax_getData("ajax_getdata.php?type=itemnonpr&from=" + where + "&term=" + term + "&data=" + btoa(data.join(",")));
        } else removeSuggest();
    }
    function clearInputItem(id) {
        $("#inp_" + id).val("");
    }
    function appendItems(type, items) {
        x = $("#hdn_" + type + "items").val();
        if (x == "") $("#hdn_" + type + "items").val(items);
            else $("#hdn_" + type + "items").val(x + "," + items);
    }
    function removeItem(id) {
        ["PO", "PR"].forEach(function(x) {
            items = $("#hdn_" + x + "items").val().split(",");
            for (i=0; i<items.length; i++) {
                if (items[i] == id) {
                    items.splice(i, 1);
                    break;
                }
            }
            $("#hdn_" + x + "items").val(items.join(","));
        });
        $("#tr_" + id).fadeOut();
        $("#tr_" + id).remove();
        recalculatePO();
    }
    function validateForm() {
        user = $("#hdn_user").val();
        supplier = $("#hdn_supplier").val();
        items = $("#hdn_POitems").val();
        items2 = [];
        if (user == "-") {
            alert("Admin Utama tidak diizinkan untuk membuat Surat Pemesanan");
            return false;
        } else if (!supplier) {
            alert("Supplier kosong");
            $("#inp_supplier").focus();
            return false;
        } else if (!items) {
            alert("Tidak ada barang yang dipesan");
            return false;
        } else {
            items = items.split(",");
            for (i=0; i<items.length; i++) {
                itm = items[i].split("_");
                hrg = $("#inp_hpesan_" + items[i]).val();
                jml = $("#inp_jml_" + items[i]).val();
                if (!jml || (jml == 0 && items2.indexOf(itm[0]) == -1)) {
                    alert("Jumlah barang yang dipesan tidak boleh nol");
                    $("#inp_jml_" + items[i]).focus();
                    return false;
                } else if (!hrg || hrg == "0") {
                    alert("Harga Per Satuan Besar kosong");
                    $("#inp_hpesan_" + items[i]).focus();
                    return false;
                }
                items2.push(itm[0]);
            }
        }
        return true;
    }
    $(window).resize(function() { resizeDivScroll(); resizeSpacer(); });
    setTimeout(function() { resizeDivScroll(); resizeSpacer(); }, 500);
</script>

<?php require_once '../template/footer.php' ?>