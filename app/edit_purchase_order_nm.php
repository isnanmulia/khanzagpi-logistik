<?php
    require_once '../template/header.php';
    require_once '../config/connect_app.php';
    require_once '../function/access.php';
    require_once '../function/function.php';
    restrictAccess("surat_pemesanan_non_medis");
    if (isset($_POST["form_submit"])) {
        mysqli_autocommit($connect_app, FALSE);
        $lstPOItems = explode(",", $_POST["hdn_POitems"]);
        $lstOriItems = explode(",", $_POST["hdn_oriitems"]);
        $lstAdd = array_diff($lstPOItems, $lstOriItems);
        $lstKeep = array_intersect($lstPOItems, $lstOriItems);
        $qUpdatePO = "UPDATE surat_pemesanan_non_medis SET kode_suplier='" . $_POST["hdn_supplier"] . "', nip='" . $_POST["hdn_user"] . "', subtotal=" . $_POST["hdn_total1"] . ", potongan=" . $_POST["hdn_potongan"] . ", total=" . $_POST["hdn_total2"] . ", ppn=" . $_POST["hdn_ppn"] . ", meterai=" . str_replace(".", "", $_POST["inp_meterai"]) . ", tagihan=" . $_POST["hdn_jml_tagihan"] . ", catatan='" . $_POST["inp_catatan"] . "' WHERE no_pemesanan='" . $_POST["hdn_id"] . "' AND tanggal='" . $_POST["hdn_date"] . "'";
        $updatePO = mysqli_query($connect_app, $qUpdatePO);
        saveToTracker($qUpdatePO, $connect_app);
        $updatePRD = TRUE; $updatePRDtlD = TRUE; $updatePRA = TRUE; $updatePRDtlA = TRUE; $deletePODtl = TRUE; $insertPODtl = TRUE;
        if (strlen($_POST["hdn_delitems"])) {
            $delitems = explode(",", $_POST["hdn_delitems"]);
            foreach ($delitems as $item) {
                $itemx = explode("_", $item);
                if (isset($itemx[1])) {
                    $qUpdatePRDtlD = "UPDATE detail_pengajuan_barang_nonmedis SET status='Proses Pengajuan' WHERE no_pengajuan='" . $itemx[1] . "' AND kode_brng='" . $itemx[0] . "'";
                    $updatePRDtlD = mysqli_query($connect_app, $qUpdatePRDtlD);
                    saveToTracker($qUpdatePRDtlD, $connect_app);
                    $qUpdatePRD = "UPDATE pengajuan_barang_nonmedis SET status='Proses Pengajuan' WHERE no_pengajuan='" . $itemx[1] . "'";
                    $updatePRD = mysqli_query($connect_app, $qUpdatePRD);
                    saveToTracker($qUpdatePRD, $connect_app);
                    $no_pr = $itemx[1];
                } else $no_pr = "";
                $qDeletePODtl = "DELETE FROM detail_surat_pemesanan_non_medis WHERE no_pemesanan='" . $_POST["hdn_id"] . "' AND kode_brng='" . $itemx[0] . "' AND no_pr_ref='" . $no_pr . "'";
                $deletePODtl = mysqli_query($connect_app, $qDeletePODtl);
                saveToTracker($qDeletePODtl, $connect_app);
            }
        }
        foreach ($lstKeep as $item) {
            $itemx = explode("_", $item);
            if (isset($itemx[1])) $no_pr = $itemx[1];
                else $no_pr = "";
            $qUpdatePODtl = "UPDATE detail_surat_pemesanan_non_medis SET jumlah=" . str_replace(",", ".", str_replace(".", "", $_POST["inp_jml_" . $item])) . ", h_pesan=" . str_replace(".", "", $_POST["inp_hpesan_" . $item]) . ", subtotal=" . $_POST["hdn_subtotal_" . $item] . ", dis=" . str_replace(",", ".", $_POST["inp_disc1_" . $item]) . ", dis2=" . str_replace(",", ".", $_POST["inp_disc2_" . $item]) . ", besardis=" . $_POST["hdn_ndisc_" . $item] . ", total=" . $_POST["hdn_total_" . $item] . " WHERE no_pemesanan='" . $_POST["hdn_id"] . "' AND kode_brng='" . $itemx[0] . "' AND no_pr_ref='" . $no_pr . "'";
            $updatePODtl = mysqli_query($connect_app, $qUpdatePODtl);
            saveToTracker($qUpdatePODtl, $connect_app);
        }
        foreach ($lstAdd as $item) {
            $itemx = explode("_", $item);
            if (isset($itemx[1])) {
                $qUpdatePRDtlA = "UPDATE detail_pengajuan_barang_nonmedis SET status='Disetujui' WHERE no_pengajuan='" . $itemx[1] . "' AND kode_brng='" . $itemx[0] . "'";
                $updatePRDtlA = mysqli_query($connect_app, $qUpdatePRDtlA);
                saveToTracker($qUpdatePRDtlA, $connect_app);
                $qCheckPRDtl = "SELECT(SELECT Count(kode_brng) FROM detail_pengajuan_barang_nonmedis WHERE no_pengajuan='" . $itemx[1] . "') AS X, (SELECT Count(kode_brng) FROM detail_pengajuan_barang_nonmedis WHERE no_pengajuan='" . $itemx[1] . "' AND status='Disetujui') AS Y";
                $checkPRDtl = mysqli_query($connect_app, $qCheckPRDtl);
                $PRDtl = mysqli_fetch_assoc($checkPRDtl);
                if ($PRDtl["X"] == $PRDtl["Y"]) {
                    $qUpdatePRA = "UPDATE pengajuan_barang_nonmedis SET status='Disetujui' WHERE no_pengajuan='" . $itemx[1] . "'";
                    $updatePRA = mysqli_query($connect_app, $qUpdatePRA);
                    saveToTracker($qUpdatePRA, $connect_app);
                }
                $no_pr = $itemx[1];
            } else $no_pr = "";
            $qInsertPODtl = "INSERT INTO detail_surat_pemesanan_non_medis (no_pemesanan, kode_brng, no_pr_ref, kode_sat, kode_satbesar, isi, jumlah, h_pesan, subtotal, dis, dis2, besardis, total, status) VALUES ('" . $_POST["hdn_id"] . "', '" . $itemx[0] . "', '" . $no_pr . "', '" . $_POST["hdn_sk_" . $item] . "', '" . $_POST["hdn_sb_" . $item] . "', '" . $_POST["hdn_isi_" . $item] . "', " . str_replace(",", ".", str_replace(".", "", $_POST["inp_jml_" . $item])) . ", " . str_replace(".", "", $_POST["inp_hpesan_" . $item]) . ", " . $_POST["hdn_subtotal_" . $item] . ", " . str_replace(",", ".", $_POST["inp_disc1_" . $item]) . ", " . str_replace(",", ".", $_POST["inp_disc2_" . $item]) . ", " . $_POST["hdn_ndisc_" . $item] . ", " . $_POST["hdn_total_" . $item] . ", 'Baru')";
            $insertPODtl = mysqli_query($connect_app, $qInsertPODtl);
            saveToTracker($qInsertPODtl, $connect_app);
        }
        if ($updatePO && $updatePRD && $updatePRDtlD && $updatePRA && $updatePRDtlA && $deletePODtl && $updatePODtl && $insertPODtl) {
            mysqli_commit($connect_app);
            $msg = "Sukses mengubah Surat Pemesanan";
        } else {
            logError("edit_purchase_order_nm.php", "updatePO:" . $updatePO . "|updatePRD:" . $updatePRD . "|updatePRDtlD:" . $updatePRDtlD . "|updatePRA:" . $updatePRA . "|updatePRDtlA:" . $updatePRDtlA . "|deletePODtl:" . $deletePODtl . "|updatePODtl:" . $updatePODtl . "|insertPODtl:" . $insertPODtl);
            mysqli_rollback($connect_app);
            $msg = "Gagal mengubah Surat Pemesanan";
            echo "updatePO:" . $updatePO . " updatePRD:" . $updatePRD . " updatePRDtlD:" . $updatePRDtlD . " updatePRA:" . $updatePRA . " updatePRDtlA:" . $updatePRDtlA . " deletePODtl:" . $deletePODtl . " updatePODtl:" . $updatePODtl . " insertPODtl:" . $insertPODtl;
            var_dump($qInsertPODtl);
            die();
        }
        mysqli_autocommit($connect_app, TRUE);
        echo "<script>alert('" . $msg . "'); window.location.href='purchase_order_nm.php';</script>";
    } else if (!isset($_GET["id"]) || $_GET["id"] == "") {
        denyAccess();
    } else if (isset($_GET["id"])) {
        $PO_no = $_GET["id"];
        $qGetPO = "SELECT SPN.kode_suplier, nama_suplier, tanggal, subtotal, potongan, total, ppn, meterai, tagihan, status, catatan FROM surat_pemesanan_non_medis SPN INNER JOIN ipsrssuplier S ON SPN.kode_suplier=S.kode_suplier WHERE no_pemesanan='" . $PO_no . "'";
        $getPO = mysqli_query($connect_app, $qGetPO);
        $PO = mysqli_fetch_assoc($getPO);
        $qGetPODtl = "SELECT DSPN.kode_brng, nama_brng, no_pr_ref, CASE WHEN Length(no_pr_ref) THEN Concat(DSPN.kode_brng,'_',no_pr_ref) ELSE DSPN.kode_brng END AS kode, DSPN.kode_sat, CASE WHEN DPBN.jumlah THEN DPBN.jumlah ELSE '-' END AS jumlah_diajukan, SK.satuan AS satuan_kecil, DSPN.kode_satbesar, SB.satuan AS satuan_besar, DSPN.isi, DSPN.jumlah AS jumlah_dipesan, h_pesan, subtotal, dis, dis2, besardis, DSPN.total, IfNull(Sum(G.stok), 0) AS stok FROM detail_surat_pemesanan_non_medis DSPN INNER JOIN ipsrsbarang B ON DSPN.kode_brng=B.kode_brng INNER JOIN kodesatuan SK ON DSPN.kode_sat=SK.kode_sat INNER JOIN kodesatuan SB ON DSPN.kode_satbesar=SB.kode_sat LEFT JOIN detail_pengajuan_barang_nonmedis DPBN ON DSPN.kode_brng=DPBN.kode_brng AND DSPN.no_pr_ref=DPBN.no_pengajuan LEFT JOIN gpi_gudangbarangipsrs G ON G.kode_brng=B.kode_brng WHERE no_pemesanan='" . $PO_no . "' GROUP BY kode_brng, no_pr_ref ORDER BY kode_brng, no_pr_ref";
        $getPODtl = mysqli_query($connect_app, $qGetPODtl);
        $lstPODtl = ""; $lstPRitems = []; $lstPOitems = [];
        while ($data = mysqli_fetch_assoc($getPODtl)) {
            if (strlen($data["no_pr_ref"])) array_push($lstPRitems, $data["kode"]);
            array_push($lstPOitems, $data["kode"]);
            $lstPODtl .= "<tr id='tr_" . $data["kode"] . "'><td>" . $data["kode_brng"] . "</td><td>" . $data["nama_brng"] . "</td><td>" . $data["satuan_kecil"] . "<input type='hidden' id='hdn_sk_" . $data["kode"] . "' name='hdn_sk_" . $data["kode"] . "' value='" . $data["kode_sat"] . "'></td><td>" . $data["satuan_besar"] . "<input type='hidden' id='hdn_sb_" . $data["kode"] . "' name='hdn_sb_" . $data["kode"] . "' value='" . $data["kode_satbesar"] . "'></td><td class='num-input'>" . $data["isi"] . "<input type='hidden' id='hdn_isi_" . $data["kode"] . "' name='hdn_isi_" . $data["kode"] . "' value='" . $data["isi"] . "'></td><td class='num-input'>" . ($data["jumlah_diajukan"] != "-" ? number_format($data["jumlah_diajukan"], 0, ",", ".") . " " . $data["satuan_besar"] : $data["jumlah_diajukan"]) . "</td><td style='width: 60px'><input type='text' id='inp_jml_" . $data["kode"] . "' name='inp_jml_" . $data["kode"] . "' class='form-control num-input' value='" . number_format($data["jumlah_dipesan"], 2, ",", ".") . "' style='width: 60px' onKeyUp='removeNonNumeric(&quot;inp_jml_" . $data["kode"] . "&quot;); recalculatePO(&quot;N&quot;)' maxlength='5' autocomplete='off'></td><td style='width: 100px'>" . $data["satuan_besar"] . "</td><td class='num-input'><input type='text' id='inp_hpesan_" . $data["kode"] . "' name='inp_hpesan_" . $data["kode"] . "' class='form-control num-input' style='width: 100px' value='" . number_format($data["h_pesan"], 0, ",", ".") . "' onKeyUp='removeNonNumeric(&quot;inp_hpesan_" . $data["kode"] . "&quot;); recalculatePO(&quot;N&quot;)' autocomplete='off' maxlength='11'></td><td class='num-input'><span id='span_subtotal_" . $data["kode"] . "' name='span_subtotal_" . $data["kode"] . "'>" . number_format($data["subtotal"], 0, ",", ".") . "</span><input type='hidden' id='hdn_subtotal_" . $data["kode"] . "' name='hdn_subtotal_" . $data["kode"] . "' value='" . $data["subtotal"] . "'></td><td><input type='text' id='inp_disc1_" . $data["kode"] . "' name='inp_disc1_" . $data["kode"] . "' class='form-control num-input' value='" . str_replace(".", ",", $data["dis"]) . "' maxlength='5' onKeyUp='removeNonNumeric(&quot;inp_disc1_" . $data["kode"] . "&quot;); recalculatePO(&quot;N&quot;)' autocomplete='off'></td><td><input type='text' id='inp_disc2_" . $data["kode"] . "' name='inp_disc2_" . $data["kode"] . "' class='form-control num-input' value='" . str_replace(".", ",", $data["dis2"]) . "' maxlength='5' onKeyUp='removeNonNumeric(&quot;inp_disc2_" . $data["kode"] . "&quot;); recalculatePO(&quot;N&quot;)' autocomplete='off'></td><td class='num-input'><span id='span_ndisc_" . $data["kode"] . "' name='span_ndisc_" . $data["kode"] . "'>" . number_format($data["besardis"], 0, ",", ".") . "</span><input type='hidden' id='hdn_ndisc_" . $data["kode"] . "' name='hdn_ndisc_" . $data["kode"] . "' value='" . $data["besardis"] . "'></td><td class='num-input td-total'><span id='span_total_" . $data["kode"] . "' name='span_total_" . $data["kode"] . "'>" . number_format($data["total"], 0, ",", ".") . "</span><input type='hidden' id='hdn_total_" . $data["kode"] . "' name='hdn_total_" . $data["kode"] . "' value='" . $data["total"] . "'></td><td class='num-input'>" . number_format($data["stok"], 0, ",", ".") . " " . $data["satuan_kecil"] . " <a class='btn btn-primary btn-sm' onClick='getStockDtl(&quot;" . $data["kode_brng"] . "&quot;, &quot;N&quot;)' title='Klik untuk menampilkan detail stok barang per bangsal'><i class='fa fa-eye'></i></a></td><td>" . $data["no_pr_ref"] . "<input type='hidden' id='hdn_PR_" . $data["kode"] . "' name='hdn_PR_" . $data["kode"] . "' value='" . $data["no_pr_ref"] . "'></td><td><a class='btn btn-danger btn-sm' title='Hapus' onClick='if(confirm(&quot;Apakah Anda yakin ingin menghapus data ini?&quot;)) removeItem(&quot;" . $data["kode"] . "&quot;)'><i class='fa fa-trash'></i></a></td></tr>";
        }
    }
?>

<form name="frmEditPO" method="POST" target="" onSubmit="return validateForm()">
    <table id="tbl_editPO" class="table">
        <tr>
            <td style="width: 150px">No Pemesanan</td>
            <td style="width: 350px"><?php echo $PO_no ?><input type="hidden" id="hdn_id" name="hdn_id" value="<?php echo $PO_no ?>"></td>
            <td style="width: 150px">Supplier <span class="span-required">*</span></td>
            <td style="width: 350px"><input type="text" id="inp_supplier" name="inp_supplier" class="form-control inline-input" style="width: 400px" onKeyUp="getSupplierNM()" value="<?php echo $PO["nama_suplier"] ?>" autocomplete="off"><input type="hidden" id="hdn_supplier" name="hdn_supplier" value="<?php echo $PO["kode_suplier"] ?>"></td>
        </tr>
        <tr>
            <td>Petugas</td>
            <td><?php if ($_SESSION["USER"]["USERNAME"] != "admin") echo $_SESSION["USER"]["FULLNAME"]; else echo "-" ?><input type="hidden" id="hdn_user" name="hdn_user" value="<?php if ($_SESSION["USER"]["USERNAME"] != "admin") echo $_SESSION["USER"]["USERNAME"]; else echo "-" ?>"></td>
            <td>Catatan</td>
            <td rowspan="3"><textarea id="inp_catatan" name="inp_catatan" maxlength="1000" class="form-control" rows="4" style="resize: none"><?php echo $PO["catatan"] ?></textarea></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td><?php echo $PO["tanggal"] ?><input type="hidden" id="hdn_date" name="hdn_date" value="<?php echo $PO["tanggal"] ?>"></td>
        </tr>
        <tr>
            <td>Status</td>
            <td><?php echo $PO["status"] ?></td>
        </tr>
        <tr>
            <td>Barang yang Dipesan</td>
            <td colspan="3">
            <a class="btn btn-sm btn-primary" onClick="addPOItem()"><i class="fa fa-plus"></i> Tambah</a>
                <a class="btn btn-sm btn-primary" onClick="addPOItem('PR')"><i class="fa fa-check-square-o"></i> Pilih dari Pengajuan</a>
                <div id="div-scroll" style="height: 300px; overflow: scroll;">
                    <input type="hidden" id="hdn_PRitems" name="hdn_PRitems" value="<?php echo implode(",", $lstPRitems) ?>">
                    <input type="hidden" id="hdn_POitems" name="hdn_POitems" value="<?php echo implode(",", $lstPOitems) ?>">
                    <input type="hidden" id="hdn_delitems" name="hdn_delitems">
                    <input type="hidden" id="hdn_oriitems" name="hdn_oriitems" value="<?php echo implode(",", $lstPOitems) ?>">
                    <table class="table table-hover" id="tbl_listItems">
                        <thead>
                            <tr>
                                <th style="min-width: 90px;">Kode</th><th style="min-width: 200px;">Nama Barang</th><th title="Satuan Kecil" style="min-width: 55px;">SK</th><th title="Satuan Besar" style="min-width: 55px;">SB</th><th style="min-width: 50px;">Isi</th><th style="min-width: 110px;">Jml Pengajuan</th><th colspan="2" style="min-width: 140px;">Jml Pesan</th><th style="min-width: 110px;">Harga Per SB</th><th style="min-width: 90px;">Subtotal</th><th style="min-width: 70px;">Diskon 1 (%)</th><th style="min-width: 70px;">Diskon 2 (%)</th><th style="min-width: 90px;">Total Diskon</th><th style="min-width: 90px; background-color: #EEE">Total</th><th style="min-width: 140px">Stok RS</th><th style="min-width: 115px">No. Pengajuan</th><th style="min-width: 45px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo $lstPODtl ?>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3">
                Subtotal: <span id="span_total1" class="span-amount"><?php echo number_format($PO["subtotal"], 0, ",", ".") ?></span><input type="hidden" id="hdn_total1" name="hdn_total1" value="<?php echo $PO["subtotal"] ?>"><span class="inline-spacer">|</span>
                Potongan: <span id="span_potongan" class="span-amount"><?php echo number_format($PO["potongan"], 0, ",", ".") ?></span><input type="hidden" id="hdn_potongan" name="hdn_potongan" value="<?php echo $PO["potongan"] ?>"><span class="inline-spacer">|</span>
                Total: <span id="span_total2" class="span-amount"><?php echo number_format($PO["total"], 0, ",", ".") ?></span><input type="hidden" id="hdn_total2" name="hdn_total2" value="<?php echo $PO["total"] ?>"><span class="inline-spacer">|</span>
                PPN (%) <input type="text" id="inp_ppn" name="inp_ppn" class="form-control inline-input num-input" value="<?php echo floor($PO["ppn"]/$PO["total"]*100) ?>" maxlength="2" onKeyUp="removeNonNumeric('inp_ppn'); recalculatePO('N')" style="width: 40px" autocomplete="off"><span id="span_ppn" class="span-amount"><?php echo number_format($PO["ppn"], 0, ",", ".") ?></span><input type="hidden" id="hdn_ppn" name="hdn_ppn" value="<?php echo $PO["ppn"] ?>"><span class="inline-spacer">|</span>
                Meterai <input type="text" id="inp_meterai" name="inp_meterai" class="form-control inline-input num-input" value="<?php echo number_format($PO["meterai"], 0, ",", ".") ?>" maxlength="6" onKeyUp="removeNonNumeric('inp_meterai'); recalculatePO('N')" style="width: 65px" autocomplete="off"><span class="inline-spacer">|</span>
                Jumlah tagihan: <span id="span_jml_tagihan" class="span-amount"><?php echo number_format($PO["tagihan"], 0, ",", ".") ?></span><input type="hidden" id="hdn_jml_tagihan" name="hdn_jml_tagihan" value="<?php echo $PO["tagihan"] ?>">
            </td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: right">
                <input type="hidden" id="form_submit" name="form_submit" value="1">
                <a class="btn btn-primary" onClick="history.back()">
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
            window.open("select_pr_nm.php?data=" + btoa(data), "_blank", "width=1000, height=500");
        } else {
            idx++;
            $("#tbl_listItems tbody").append("<tr id='tr_newItem_" + idx + "'><td></td><td><input type='text' id='inp_newItem_" + idx + "' name='inp_newItem_" + idx + "' class='form-control' style='display: inline-block; width: 170px' placeholder='Ketikkan nama barang...' onKeyUp='getItemNonPR(&quot;newItem_" + idx + "&quot;)' autocomplete='off'><span class='text-red' title='Batal' style='padding: 4px;' onClick='$(&quot;#tr_newItem_" + idx + "&quot;).fadeOut()'><i class='fa fa-times'></i></span></td><td colspan='12'></td></tr>");
            $("#inp_newItem_" + idx).focus();
        }
    }
    function getItemNonPR(where) {
        term = $("#inp_" + where).val();
        if (term.length > 0) {
            data = $("#hdn_POitems").val().split(",");
            for (i=data.length; i>0; i--) {
                if (data[i-1].indexOf("_") > -1) {
                    data.splice(i-1, 1);
                }
            }
            ajax_getData("ajax_getdata.php?type=ponmnonpr&from=" + where + "&term=" + term + "&data=" + btoa(data.join(",")));
        } else removeSuggest();
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
        appendItems("del", id);
        $("#tr_" + id).fadeOut();
        $("#tr_" + id).remove();
        recalculatePO("N");
    }
    function validateForm() {
        user = $("#hdn_user").val();
        supplier = $("#hdn_supplier").val();
        items = $("#hdn_POitems").val();
        items2 = [];
        if (user == "-") {
            alert("Admin Utama tidak diizinkan untuk mengubah Surat Pemesanan");
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
    <?php if ($PO["status"] != "Baru") { ?>setTimeout(function() { disableForm(); }, 500);<?php } ?>
    $(window).resize(function() { resizeDivScroll(); resizeSpacer(); });
    setTimeout(function() { resizeDivScroll(); resizeSpacer(); recalculatePO("N"); }, 500);
</script>

<?php require_once '../template/footer.php' ?>