<?php
    require_once '../template/header.php';
    require_once '../config/connect_app.php';
    require_once '../function/access.php';
    require_once '../function/function.php';
    restrictAccess("pemesanan_obat");
    if (isset($_POST["form_submit"])) {
        mysqli_autocommit($connect_app, FALSE);
        $bangsal = "GUDMD";
        $qUpdateRcp = "UPDATE pemesanan SET no_faktur_supplier='" . $_POST["inp_faktursupplier"] . "', tgl_faktur='" . $_POST["inp_tglfaktur"] . "', tgl_tempo='" . $_POST["inp_tgljatuhtempo"] . "', total1=" . $_POST["hdn_total1"] . ", potongan=" . $_POST["hdn_potongan"] . ", total2=" . $_POST["hdn_total2"] . ", ppn=" . $_POST["hdn_ppn"] . ", meterai=" . str_replace(".", "", $_POST["inp_meterai"]) . ", tagihan=" . $_POST["hdn_jml_tagihan"] . ", catatan='" . $_POST["inp_catatan"] . "' WHERE no_faktur='" . $_POST["hdn_id"] . "'";
        $updateRcp = mysqli_query($connect_app, $qUpdateRcp);
        saveToTracker($qUpdateRcp, $connect_app);
        $items = explode(",", $_POST["hdn_Rcpitems"]);
        $status = $updateRcp;
        foreach ($items as $item) {
            $updateRcpDtl = TRUE; $insertRcpDtl = TRUE; $updatePODtl = TRUE; $updatePO = TRUE; $insertRiwayat = TRUE; $gudangBarang = TRUE; $insertPriceHistory = TRUE; $updatePriceMaster = TRUE;
            $itemx = explode("_", $item);
            if ($_POST["hdn_editable_" . $item] == "0") {
                $qUpdateRcpDtl = "UPDATE detailpesan SET no_batch='" . $_POST["inp_nobatch_" . $item] . "', kadaluarsa='" . $_POST["inp_expiry_" . $item] . "' WHERE no_faktur='" . $_POST["hdn_id"] . "' AND kode_brng='" . $itemx[0] . "'";
                $updateRcpDtl = mysqli_query($connect_app, $qUpdateRcpDtl);
                saveToTracker($qUpdateRcpDtl, $connect_app);
            } else {
                $diterima = str_replace(".", "", $_POST["inp_diterima_" . $item]);
                $hpesan = str_replace(".", "", $_POST["inp_hpesan_" . $item]);
                if ($_POST["hdn_jml_" . $item] == ($_POST["hdn_sdhditerima_" . $item] + $diterima)) {
                    $qUpdatePODtl = "UPDATE detail_surat_pemesanan_medis SET status='Sudah Datang' WHERE no_pemesanan='" . $itemx[1] . "' AND kode_brng='" . $itemx[0] . "'";
                    $updatePODtl = mysqli_query($connect_app, $qUpdatePODtl);
                    saveToTracker($qUpdatePODtl, $connect_app);
                }
                $qCheckPODtl = "SELECT (SELECT Count(kode_brng) FROM detail_surat_pemesanan_medis WHERE no_pemesanan='" . $itemx[1] . "') AS X, (SELECT Count(kode_brng) FROM detail_surat_pemesanan_medis WHERE no_pemesanan='" . $itemx[1] . "' AND status='Sudah Datang') AS Y";
                $checkPODtl = mysqli_query($connect_app, $qCheckPODtl);
                $PODtl = mysqli_fetch_assoc($checkPODtl);
                if ($PODtl["X"] == $PODtl["Y"]) {
                    $qUpdatePO = "UPDATE surat_pemesanan_medis SET status='Sudah Datang' WHERE no_pemesanan='" . $itemx[1] . "'";
                    $updatePO = mysqli_query($connect_app, $qUpdatePO);
                    saveToTracker($qUpdatePO, $connect_app);
                }
                $qInsertRcpDtl = "INSERT INTO detailpesan (no_faktur, kode_brng, no_pemesanan, kode_sat, kode_satbesar, isi, jumlah, h_pesan, subtotal, dis, dis2, besardis, total, no_batch, jumlah2, kadaluarsa) VALUES ('" . $_POST["hdn_id"] . "', '" . $itemx[0] . "', '" . $itemx[1] . "', '" . $_POST["hdn_sk_" . $item] . "', '" . $_POST["hdn_sb_" . $item] . "', " . $_POST["hdn_fk_" . $item] . ", " . $diterima . ", " . $hpesan . ", " . $_POST["hdn_subtotal_" . $item] . ", " . $_POST["hdn_disc1_" . $item] . ", " . $_POST["hdn_disc2_" . $item] . ", " . $_POST["hdn_ndisc_" . $item] . ", " . $_POST["hdn_total_" . $item] . ", '" . $_POST["inp_nobatch_" . $item] . "', " . $diterima . ", '" . $_POST["inp_expiry_" . $item] . "')";
                $insertRcpDtl = mysqli_query($connect_app, $qInsertRcpDtl);
                saveToTracker($qInsertRcpDtl, $connect_app);
                $stokMasuk = $diterima * $_POST["hdn_fk_" . $item];
                $qGetStockQty = "SELECT stok FROM gudangbarang WHERE kode_brng='" . $itemx[0] . "' AND kd_bangsal='" . $bangsal . "'";
                $getStockQty = mysqli_query($connect_app, $qGetStockQty);
                $stockQty = mysqli_fetch_assoc($getStockQty);
                if ($stockQty) {
                    $stokAwal = $stockQty["stok"];
                    $qGudangBarang = "UPDATE gudangbarang SET stok=stok+" . $stokMasuk . " WHERE kode_brng='" . $itemx[0] . "' AND kd_bangsal='" . $bangsal . "'";
                } else {
                    $stokAwal = 0;
                    $qGudangBarang = "INSERT INTO gudangbarang (kode_brng, kd_bangsal, stok) VALUES ('" . $itemx[0] . "', '" . $bangsal . "', " . $stokMasuk . ")";
                }
                $stokAkhir = $stokAwal + $stokMasuk;
                $qInsertRiwayat = "INSERT INTO riwayat_barang_medis (kode_brng, stok_awal, masuk, keluar, stok_akhir, posisi, tanggal, jam, petugas, kd_bangsal, status) VALUES ('" . $itemx[0] . "', " . $stokAwal . ", " . $stokMasuk . ", 0, " . $stokAkhir . ", 'Penerimaan', '" . $today . "', '" . date("H:i:s") . "', '" . $_POST["hdn_user"] . "', '" . $bangsal . "', 'Simpan')";
                $insertRiwayat = mysqli_query($connect_app, $qInsertRiwayat);
                saveToTracker($qInsertRiwayat, $connect_app);
                $gudangBarang = mysqli_query($connect_app, $qGudangBarang);
                saveToTracker($qGudangBarang, $connect_app);
                $qCheckPrice = "SELECT harga_sat_besar FROM gpi_riwayat_harga_obat WHERE kode_brng='" . $itemx[0] . "' ORDER BY dibuat_pada DESC LIMIT 1";
                $checkPrice = mysqli_query($connect_app, $qCheckPrice);
                $price = mysqli_fetch_assoc($checkPrice);
                if ($hpesan > $price["harga_sat_besar"]) {
                    $qGetPricePctg = "SELECT ralan, kelas1, kelas2, kelas3, utama, vip, vvip, beliluar, jualbebas, karyawan FROM setpenjualanumum";
                    $getPricePctg = mysqli_query($connect_app, $qGetPricePctg);
                    $pricePctg = mysqli_fetch_row($getPricePctg);
                    $basePrice = $hpesan/$_POST["hdn_fk_" . $item];
                    $basePriceVat = 11/10 * ($basePrice);
                    $qInsertPriceHistory = "INSERT INTO gpi_riwayat_harga_obat (no_ref, kode_brng, tanggal_efektif, harga_sat_kecil, harga_sat_besar, dibuat_oleh, dibuat_pada) VALUES ('" . $_POST["hdn_id"] . "', '" . $itemx[0] . "', '" . $today . "', " . $basePriceVat . ", " . $hpesan . ", '" . $_SESSION["USER"]["USERNAME"] . "', now())";
                    $insertPriceHistory = mysqli_query($connect_app, $qInsertPriceHistory);
                    saveToTracker($qInsertPriceHistory, $connect_app);
                    $newPrice = [];
                    foreach ($pricePctg as $x) {
                        array_push($newPrice, round($basePriceVat * ((100 + $x)/100)));
                    }
                    $qUpdatePriceMaster = "UPDATE databarang SET dasar=" . $basePrice . ", h_beli=" . $basePriceVat . ", ralan=" . $newPrice[0] . ", kelas1=" . $newPrice[1] . ", kelas2=" . $newPrice[2] . ", kelas3=" . $newPrice[3] . ", utama=" . $newPrice[4] . ", vip=" . $newPrice[5] . ", vvip=" . $newPrice[6] . ", beliluar=" . $newPrice[7] . ", jualbebas=" . $newPrice[8] . ", karyawan=" . $newPrice[9] . " WHERE kode_brng='" . $itemx[0] . "'";
                    $updatePriceMaster = mysqli_query($connect_app, $qUpdatePriceMaster);
                    saveToTracker($qUpdatePriceMaster, $connect_app);
                }
            }
            $status = $status && ($updateRcpDtl && $insertRcpDtl && $updatePO && $updatePODtl && $insertRiwayat && $gudangBarang && $insertPriceHistory && $updatePriceMaster);
        }
        if ($status) {
            mysqli_commit($connect_app);
            $msg = "Sukses mengubah Penerimaan Barang";
        } else {
            logError("edit_po_reception_m.php", "updateRcpDtl:" . $updateRcpDtl . "|insertRcpDtl" . $insertRcpDtl . "|updatePO:" . $updatePO . "|updatePODtl:" . $updatePODtl . "|insertRiwayat:" . $insertRiwayat . "|gudangBarang:" . $gudangBarang . "|insertPriceHistory:" . $insertPriceHistory . "|updatePriceMaster:" . $updatePriceMaster);
            mysqli_rollback($connect_app);
            $msg = "Gagal mengubah Penerimaan Barang";
        }
        mysqli_autocommit($connect_app, TRUE);
        echo "<script>alert('" . $msg . "'); window.location.href='po_reception_m.php'</script>";
    } else if (!isset($_GET["id"]) || $_GET["id"] == "") {
        denyAccess();
    } else if (isset($_GET["id"])) {
        $RcpNo = $_GET["id"];
        $qGetRcp = "SELECT no_faktur_supplier, PS.kode_suplier, nama_suplier, tgl_pesan, tgl_faktur, tgl_tempo, total1, potongan, total2, ppn, meterai, tagihan, PS.status, catatan, nama, nm_bangsal FROM pemesanan PS INNER JOIN datasuplier S ON PS.kode_suplier=S.kode_suplier INNER JOIN petugas P ON PS.nip=P.nip INNER JOIN bangsal B ON PS.kd_bangsal=B.kd_bangsal WHERE no_faktur='" . $RcpNo . "'";
        $getRcp = mysqli_query($connect_app, $qGetRcp);
        $Rcp = mysqli_fetch_assoc($getRcp);
        $qGetRcpDtl = "SELECT DPS.kode_brng, nama_brng, DPS.no_pemesanan, Concat(DPS.kode_brng,'_',DPS.no_pemesanan,'_',DSPM.jumlah) AS kode, DPS.kode_sat, DSPM.jumlah AS jumlah_dipesan, SK.satuan AS satuan_kecil, DPS.kode_satbesar, SB.satuan AS satuan_besar, DPS.isi, DPS.jumlah AS jumlah_diterima, DPS.h_pesan, DPS.subtotal, DPS.dis, DPS.dis2, DPS.besardis, DPS.total, no_batch, kadaluarsa FROM detailpesan DPS INNER JOIN databarang B ON DPS.kode_brng=B.kode_brng INNER JOIN kodesatuan SK ON DPS.kode_sat=SK.kode_sat INNER JOIN kodesatuan SB ON DPS.kode_satbesar=SB.kode_sat INNER JOIN detail_surat_pemesanan_medis DSPM ON DPS.kode_brng=DSPM.kode_brng AND DPS.no_pemesanan=DSPM.no_pemesanan WHERE no_faktur='" . $RcpNo . "'";
        $getRcpDtl = mysqli_query($connect_app, $qGetRcpDtl);
        $lstRcpDtl = ""; $lstRcpitems = [];
        while ($data = mysqli_fetch_assoc($getRcpDtl)) {
            array_push($lstRcpitems, $data["kode"]);
            $lstRcpDtl .= "<tr id='tr_" . $data["kode"] . "'><td>" . $data["kode_brng"] . "</td><td>" . str_replace('"', '\"', $data["nama_brng"]) . "</td><td>" . $data["satuan_kecil"] . "<input type='hidden' id='hdn_sk_" . $data["kode"] . "' name='hdn_sk_" . $data["kode"] . "' value='" . $data["kode_sat"] . "'></td><td>" . $data["satuan_besar"] . "<input type='hidden' id='hdn_sb_" . $data["kode"] . "' name='hdn_sb_" . $data["kode"] . "' value='" . $data["kode_satbesar"] . "'></td><td class='num-input'>" . number_format($data["isi"], 0, ",", ".") . "<input type='hidden' id='hdn_fk_" . $data["kode"] . "' name='hdn_fk_" . $data["kode"] . "' value='" . $data["isi"] . "'></td><td class='num-input'>" . number_format($data["jumlah_dipesan"], 0, ",", ".") . " " . $data["satuan_besar"] . "<input type='hidden' id='hdn_jml_" . $data["kode"] . "' name='hdn_jml_" . $data["kode"] . "' value='" . $data["jumlah_dipesan"] . "'></td><td class='num-input'>" . number_format($data["h_pesan"], 0, ",", ".") . "<input type='hidden' id='inp_hpesan_" . $data["kode"] . "' name='inp_hpesan_" . $data["kode"] . "' value='" . $data["h_pesan"] . "'></td><td class='num-input'><input type='hidden' id='hdn_sdhditerima_" . $data["kode"] . "' name='hdn_sdhditerima_" . $data["kode"] . "' value='" . $data["jumlah_diterima"] . "'></td><td colspan='2' class='num-input'>" . number_format($data["jumlah_diterima"], 0, ",", ".") . " " . $data["satuan_besar"] . "<input type='hidden' id='inp_diterima_" . $data["kode"] . "' name='inp_diterima_" . $data["kode"] . "' value='" . $data["jumlah_diterima"] . "'></td><td class='num-input'>" . number_format($data["subtotal"], 0, ",", ".") . "</td><td class='num-input'>" . str_replace(".", ",", $data["dis"]) . "</td><td class='num-input'>" . str_replace(".", ",", $data["dis2"]) . "</td><td class='num-input'>" . number_format($data["besardis"], 0, ",", ".") . "</td><td class='num-input td-total'>" . number_format($data["total"], 0, ",", ".") . "</td><td><input type='text' id='inp_nobatch_" . $data["kode"] . "' name='inp_nobatch_" . $data["kode"] . "' class='form-control' autocomplete='off' value='" . $data["no_batch"] . "'></td><td><input type='text' id='inp_expiry_" . $data["kode"] . "' name='inp_expiry_" . $data["kode"] . "' class='form-control inline-input tanggal' autocomplete='off' value='" . $data["kadaluarsa"] . "'></td><td>" . $data["no_pemesanan"] . "</td><td><input type='hidden' id='hdn_editable_" . $data["kode"] . "' name='hdn_editable_" . $data["kode"] . "' value='0'></td></tr>";
        }
    }
?>

<form name="frmEditRcp" method="POST" target="" onSubmit="return validateForm()">
    <table id="tbl_editRcp" class="table">
        <tr>
            <td style="width: 150px">No Penerimaan</td>
            <td style="width: 350px"><?php echo $RcpNo ?><input type="hidden" id="hdn_id" name="hdn_id" value="<?php echo $RcpNo ?>"></td>
            <td style="width: 150px">Tanggal Faktur <span class="span-required">*</span></td>
            <td style="width: 350px"><input type="text" id="inp_tglfaktur" name="inp_tglfaktur" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $Rcp["tgl_faktur"] ?>"></td>
        </tr>
        <tr>
            <td>Petugas</td>
            <td>
                <?php if ($_SESSION["USER"]["USERNAME"] != "admin") echo $_SESSION["USER"]["FULLNAME"]; else echo "-"; ?>
                <input type="hidden" id="hdn_user" name="hdn_user" value="<?php if ($_SESSION["USER"]["USERNAME"] != "admin") echo $_SESSION["USER"]["USERNAME"]; else echo "-"; ?>">
            </td>
            <td>Tanggal Jatuh Tempo <span class="span-required">*</span></td>
            <td><input type="text" id="inp_tgljatuhtempo" name="inp_tgljatuhtempo" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $Rcp["tgl_tempo"] ?>"></td>
        </tr>
        <tr>
            <td>Lokasi</td>
            <td><?php echo $Rcp["nm_bangsal"] ?></td>
            <td>No Faktur Supplier <span class="span-required">*</span></td>
            <td><input type="text" id="inp_faktursupplier" name="inp_faktursupplier" class="form-control inline-input" style="width: 180px" autocomplete="off" value="<?php echo $Rcp["no_faktur_supplier"] ?>"></td>
        </tr>
        <tr>
            <td>Supplier</td>
            <td><?php echo $Rcp["nama_suplier"] ?><input type="hidden" id="hdn_supplier" name="hdn_supplier" value="<?php echo $Rcp["kode_suplier"] ?>"></td>
            <td>Catatan</td>
            <td rowspan="2"><textarea id="inp_catatan" name="inp_catatan" maxlength="1000" class="form-control" rows="3" style="resize: none"><?php echo $Rcp["catatan"] ?></textarea></td>
        </tr>
        <tr>
            <td>Tanggal Datang</td>
            <td><?php echo $Rcp["tgl_pesan"] ?></td>
        </tr>
        <tr>
            <td>Barang yang Diterima</td>
            <td colspan="3">
                <a class="btn btn-sm btn-primary" onCLick="addRcpItem()"><i class="fa fa-check-square-o"></i> Pilih dari Pemesanan</a>
                <div id="div-scroll" style="height: 300px; overflow: scroll;">
                    <input type="hidden" id="hdn_Rcpitems" name="hdn_Rcpitems" value="<?php echo implode(",", $lstRcpitems) ?>">
                    <table class="table table-hover" id="tbl_listItems">
                        <thead>
                            <tr>
                                <th style="min-width: 90px">Kode</th><th style="min-width: 200px">Nama Barang</th><th style="min-width: 55px" title="Satuan Kecil">SK</th><th style="min-width: 55px" title="Satuan Besar">SB</th><th style="min-width: 55px">Isi</th><th style="min-width: 90px">Jml. Pesan</th><th style="min-width: 110px">Harga Per SB</th><th style="min-width: 80px">Sudah Diterima</th><th colspan="2" style="min-width: 80px">Jumlah Datang</th><th style="min-width: 90px">Subtotal</th><th style="min-width: 70px">Diskon 1 (%)</th><th style="min-width: 70px">Diskon 2 (%)</th><th style="min-width: 90px">Total Diskon</th><th style="min-width: 90px; background-color: #EEE">Total</th><th style="min-width: 110px">No. Batch</th><th style="min-width: 90px">Kadaluarsa</th><th style="min-width: 115px">No. Pemesanan</th><th style="min-width: 45px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody><?php echo $lstRcpDtl ?></tbody>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3">
                Subtotal: <span id="span_total1" class="span-amount"><?php echo number_format($Rcp["total1"], 0, ",", ".") ?></span><input type="hidden" id="hdn_total1" name="hdn_total1" value="<?php echo $Rcp["total1"] ?>"><span class="inline-spacer">|</span>
                Potongan: <span id="span_potongan" class="span-amount"><?php echo number_format($Rcp["potongan"], 0, ",", ".") ?></span><input type="hidden" id="hdn_potongan" name="hdn_potongan" value="<?php echo $Rcp["potongan"] ?>"><span class="inline-spacer">|</span>
                Total: <span id="span_total2" class="span-amount"><?php echo number_format($Rcp["total2"], 0, ",", ".") ?></span><input type="hidden" id="hdn_total2" name="hdn_total2" value="<?php echo $Rcp["total2"] ?>"><span class="inline-spacer">|</span>
                PPN(%): <input type="text" id="inp_ppn" name="inp_ppn" class="form-control inline-input num-input" value="10" maxlength="2" onKeyUp="removeNonNumeric('inp_ppn'); recalculateRcp()" style="width: 40px" autocomplete="off" value="<?php echo str_replace(".", ",", floor($Rcp["ppn"]/$Rcp["total2"]*100)) ?>"><input type="hidden" id="hdn_ppn" name="hdn_ppn" value="<?php echo $Rcp["ppn"] ?>"><span id="span_ppn" class="span-amount"><?php echo number_format($Rcp["ppn"], 0, ",", ".") ?></span><span class="inline-spacer">|</span>
                Meterai: <input type="text" id="inp_meterai" name="inp_meterai" class="form-control inline-input num-input" value="<?php echo number_format($Rcp["meterai"], 0, ",", ".") ?>" maxlength="6" onKeyUp="removeNonNumeric('inp_meterai'); recalculateRcp()" style="width: 65px" autocomplete="off">
                <span class="inline-spacer">|</span>
                Jumlah tagihan: <span id="span_jml_tagihan" class="span-amount"><?php echo number_format($Rcp["tagihan"], 0, ",", ".") ?></span><input type="hidden" id="hdn_jml_tagihan" name="hdn_jml_tagihan" value="<?php echo $Rcp["tagihan"] ?>">
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
    function addRcpItem() {
        data = $("#hdn_Rcpitems").val();
        supplier = $("#hdn_supplier").val();
        window.open("select_po_m.php?supplier=" + supplier + "&data=" + btoa(data), "_blank", "width=1000, height=500");
    }
    function removeItem(id) {
        items = $("#hdn_Rcpitems").val().split(",");
        for (i=0; i<items.length; i++) {
            if (items[i] == id) {
                items.splice(i, 1);
                break;
            }
        }
        $("#hdn_Rcpitems").val(items.join(","));
        $("#tr_" + id).fadeOut();
        $("#tr_" + id).remove();
        recalculateRcp();
    }
    function validateForm() {
        user = $("#hdn_user").val();
        tglfaktur = $("#inp_tglfaktur").val();
        tgljatuhtempo = $("#inp_tgljatuhtempo").val();
        nofaktur = $("#inp_faktursupplier").val();
        items = $("#hdn_Rcpitems").val();
        if (user == "-") {
            alert("Admin Utama tidak diizinkan untuk mengubah Penerimaan Barang");
            return false;
        } else if (!tglfaktur) {
            alert("Tanggal Faktur kosong");
            $("#inp_tglfaktur").focus();
            return false;
        } else if (!tgljatuhtempo) {
            alert("Tanggal Jatuh Tempo kosong");
            $("#inp_tgljatuhtempo").focus();
            return false;
        } else if (!nofaktur) {
            alert("No Faktur Supplier kosong");
            $("#inp_faktursupplier").focus();
            return false;
        } else if (!items) {
            alert("Tidak ada barang yang diterima");
            return false;
        } else {
            items = items.split(",");
            for (i=0; i<items.length; i++) {
                editable = $("#hdn_editable_" + items[i]).val();
                if (editable == "1") {
                    hrg = $("#inp_hpesan_" + items[i]).val();
                    arrive = $("#inp_diterima_" + items[i]).val();
                    total = $("#hdn_jml_" + items[i]).val();
                    received = $("#hdn_sdhditerima_" + items[i]).val();
                    remain = total - received;
                    if (!hrg || hrg == "0") {
                        alert("Harga Per Satuan Besar kosong");
                        $("#inp_hpesan_" + items[i]).focus();
                        return false;
                    } else if (!arrive || arrive == "0") {
                        alert("Jumlah Barang Datang kosong");
                        $("#inp_diterima_" + items[i]).focus();
                        return false;
                    } else if (arrive > remain) {
                        alert("Jumlah Barang Datang tidak boleh lebih dari " + remain);
                        $("#inp_diterima_" + items[i]).focus();
                        return false;
                    }
                }
            }
        }
        return true;
    }
    $(window).resize(function() { resizeDivScroll(); resizeSpacer(); });
    setTimeout(function() {
        $(".tanggal").datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            language: 'id',
            todayHighlight: true,
            weekStart: 1
        });
        resizeDivScroll();
        resizeSpacer();
    }, 500);
</script>

<?php require_once '../template/footer.php' ?>