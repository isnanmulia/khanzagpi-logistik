<?php
    require_once '../template/header.php';
    require_once '../config/connect_app.php';
    require_once '../function/access.php';
    require_once '../function/function.php';
    restrictAccess("penerimaan_non_medis");
    if (isset($_POST["form_submit"])) {
        mysqli_autocommit($connect_app, FALSE);
        $bangsal = "GUDNM";
        $qGetLastNo = "SELECT IfNull(Max(Convert(Right(no_faktur,3),signed)),0) AS lastNo FROM ipsrspemesanan WHERE tgl_pesan='" . $today . "'";
        $getLastNo = mysqli_query($connect_app, $qGetLastNo);
        $lastNo = mysqli_fetch_assoc($getLastNo)["lastNo"] + 1;
        $noRcp = "PN" . date("ymd") . str_pad($lastNo, 3, "0", STR_PAD_LEFT);
        $qInsertRcp = "INSERT INTO ipsrspemesanan (no_faktur, no_faktur_supplier, kode_suplier, nip, tgl_pesan, tgl_faktur, tgl_tempo, total1, potongan, total2, ppn, meterai, tagihan, kd_bangsal, status, catatan) VALUES ('" . $noRcp . "', '" . $_POST["inp_faktursupplier"] . "', '" . $_POST["hdn_supplier"] . "', '" . $_POST["hdn_user"] . "', '" . $_POST["inp_tgldatang"] . "', '" . $_POST["inp_tglfaktur"] . "', '" . $_POST["inp_tgljatuhtempo"] . "', " . $_POST["hdn_total1"] . ", " . $_POST["hdn_potongan"] . ", " . $_POST["hdn_total2"] . ", " . $_POST["hdn_ppn"] . ", " . str_replace(".", "", $_POST["inp_meterai"]) . ", " . $_POST["hdn_jml_tagihan"] . ", '" . $bangsal . "', 'Belum Dibayar', '" . $_POST["inp_catatan"] . "')";
        $insertRcp = mysqli_query($connect_app, $qInsertRcp);
        saveToTracker($qInsertRcp, $connect_app);
        $items = explode(",", $_POST["hdn_Rcpitems"]);
        $status = $insertRcp;
        foreach ($items as $item) {
            $itemx = explode("_", $item);
            $diterima = str_replace(",", ".", str_replace(".", "", $_POST["inp_diterima_" . $item]));
            $hpesan = str_replace(".", "", $_POST["inp_hpesan_" . $item]);
            if ($_POST["hdn_jml_" . $item] == ($_POST["hdn_sdhditerima_" . $item] + $diterima)) {
                $qUpdatePODtl = "UPDATE detail_surat_pemesanan_non_medis SET status='Sudah Datang' WHERE no_pemesanan='" . $itemx[1] . "' AND kode_brng='" . $itemx[0] . "'";
                $updatePODtl = mysqli_query($connect_app, $qUpdatePODtl);
                saveToTracker($qUpdatePODtl, $connect_app);
            } else $updatePODtl = TRUE;
            $qCheckPODtl = "SELECT (SELECT Count(kode_brng) FROM detail_surat_pemesanan_non_medis WHERE no_pemesanan='" . $itemx[1] . "') AS X, (SELECT Count(kode_brng) FROM detail_surat_pemesanan_non_medis WHERE no_pemesanan='" . $itemx[1] . "' AND status='Sudah Datang') AS Y";
            $checkPODtl = mysqli_query($connect_app, $qCheckPODtl);
            $PODtl = mysqli_fetch_assoc($checkPODtl);
            if ($PODtl["X"] == $PODtl["Y"]) {
                $qUpdatePO = "UPDATE surat_pemesanan_non_medis SET status='Sudah Datang' WHERE no_pemesanan='" . $itemx[1] . "'";
                $updatePO = mysqli_query($connect_app, $qUpdatePO);
                saveToTracker($qUpdatePO, $connect_app);
            } else $updatePO = TRUE;
            $qInsertRcpDtl = "INSERT INTO ipsrsdetailpesan (no_faktur, kode_brng, no_pemesanan, kode_sat, kode_satbesar, isi, jumlah, harga, subtotal, dis, dis2, besardis, total) VALUES ('" . $noRcp . "', '" . $itemx[0] . "', '" . $itemx[1] . "', '" . $_POST["hdn_sk_" . $item] . "', '" . $_POST["hdn_sb_" . $item] . "', " . $_POST["hdn_fk_" . $item] . ", " . $diterima . ", " . $hpesan . ", " . $_POST["hdn_subtotal_" . $item] . ", " . $_POST["hdn_disc1_" . $item] . ", " . $_POST["hdn_disc2_" . $item] . ", " . $_POST["hdn_ndisc_" . $item] . ", " . $_POST["hdn_total_" . $item] . ")";
            $insertRcpDtl = mysqli_query($connect_app, $qInsertRcpDtl);
            saveToTracker($qInsertRcpDtl, $connect_app);
            $stokMasuk = $diterima * $_POST["hdn_fk_" . $item];
            $qGetStockQty = "SELECT stok FROM gpi_gudangbarangipsrs WHERE kode_brng='" . $itemx[0] . "' AND kd_bangsal='" . $bangsal . "'";
            $getStockQty = mysqli_query($connect_app, $qGetStockQty);
            $stockQty = mysqli_fetch_assoc($getStockQty);
            if ($stockQty) {
                $stokAwal = $stockQty["stok"];
                $qGudangBarang = "UPDATE gpi_gudangbarangipsrs SET stok=stok+" . $stokMasuk . " WHERE kode_brng='" . $itemx[0] . "' AND kd_bangsal='" . $bangsal . "'";
            } else {
                $stokAwal = 0;
                $qGudangBarang = "INSERT INTO gpi_gudangbarangipsrs (kode_brng, kd_bangsal, stok) VALUES ('" . $itemx[0] . "', '" . $bangsal . "', " . $stokMasuk . ")";
            }
            $stokAkhir = $stokAwal + $stokMasuk;
            $qInsertRiwayat = "INSERT INTO gpi_riwayat_barang_nonmedis (kode_brng, stok_awal, masuk, keluar, stok_akhir, posisi, tanggal, jam, petugas, kd_bangsal, status) VALUES ('" . $itemx[0] . "', " . $stokAwal . ", " . $stokMasuk . ", 0, " . $stokAkhir . ", 'Penerimaan', '" . $today . "', '" . date("H:i:s") . "', '" . $_POST["hdn_user"] . "', '" . $bangsal . "', 'Simpan')";
            $insertRiwayat = mysqli_query($connect_app, $qInsertRiwayat);
            saveToTracker($qInsertRiwayat, $connect_app);
            $gudangBarang = mysqli_query($connect_app, $qGudangBarang);
            saveToTracker($qGudangBarang, $connect_app);
            $qCheckPrice = "SELECT harga_sat_besar FROM gpi_riwayat_harga_nonmedis WHERE kode_brng='" . $itemx[0] . "' ORDER BY dibuat_pada DESC LIMIT 1";
            $checkPrice = mysqli_query($connect_app, $qCheckPrice);
            $price = mysqli_fetch_assoc($checkPrice);
            if ($hpesan > $price["harga_sat_besar"]) {
                $basePrice = $hpesan/$_POST["hdn_fk_" . $item];
                $basePriceVat = 11/10 * ($basePrice);
                $qInsertPriceHistory = "INSERT INTO gpi_riwayat_harga_nonmedis (no_ref, kode_brng, tanggal_efektif, harga_sat_kecil, harga_sat_besar, dibuat_oleh, dibuat_pada) VALUES ('" . $noRcp . "', '" . $itemx[0] . "', '" . $today . "', " . $basePriceVat . ", " . $hpesan . ", '" . $_SESSION["USER"]["USERNAME"] . "', now())";
                $insertPriceHistory = mysqli_query($connect_app, $qInsertPriceHistory);
                saveToTracker($qInsertPriceHistory, $connect_app);
                $qUpdatePriceMaster = "UPDATE ipsrsbarang SET dasar=" . $basePrice . ", harga=" . $basePriceVat . " WHERE kode_brng='" . $itemx[0] . "'";
                $updatePriceMaster = mysqli_query($connect_app, $qUpdatePriceMaster);
                saveToTracker($qUpdatePriceMaster, $connect_app);
            } else {
                $insertPriceHistory = TRUE;
                $updatePriceMaster = TRUE;
            }
            $status = $status && ($insertRcpDtl && $updatePO && $updatePODtl && $insertRiwayat && $gudangBarang && $insertPriceHistory && $updatePriceMaster);
        }
        if ($status) {
            mysqli_commit($connect_app);
            $msg = "Sukses menyimpan Penerimaan Barang";
        } else {
            logError("add_po_reception_nm.php", "insertRcp:" . $insertRcp . "|updatePODtl:" . $updatePODtl . "|updatePO:" . $updatePO . "|insertRcpDtl:" . $insertRcpDtl . "|insertRiwayat:" . $insertRiwayat . "|gudangBarang:" . $gudangBarang . "|insertPriceHistory:" . $insertPriceHistory . "|updatePriceMaster:" . $updatePriceMaster);
            mysqli_rollback($connect_app);
            $msg = "Gagal menyimpan Penerimaan Barang";
        }
        mysqli_autocommit($connect_app, TRUE);
        echo "<script>alert('" . $msg . "'); window.location.href='po_reception_nm.php'</script>";
    }
?>

<form name="frmAddRcp" method="POST" target="" onSUbmit="return validateForm()">
    <table id="tbl_addRcp" class="table">
        <tr>
            <td style="width: 150px">No Penerimaan</td>
            <td style="width: 350px">PNYYMMDDXXX</td>
            <td style="width: 150px">Tanggal Faktur <span class="span-required">*</span></td>
            <td style="width: 350px"><input type="text" id="inp_tglfaktur" name="inp_tglfaktur" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $today ?>"></td>
        </tr>
        <tr>
            <td>Petugas</td>
            <td>
                <?php if ($_SESSION["USER"]["USERNAME"] != "admin") echo $_SESSION["USER"]["FULLNAME"]; else echo "-"; ?>
                <input type="hidden" id="hdn_user" name="hdn_user" value="<?php if ($_SESSION["USER"]["USERNAME"] != "admin") echo $_SESSION["USER"]["USERNAME"]; else echo "-"; ?>">
            </td>
            <td>Tanggal Jatuh Tempo <span class="span-required">*</span></td>
            <td><input type="text" id="inp_tgljatuhtempo" name="inp_tgljatuhtempo" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $today ?>"></td>
        </tr>
        <tr>
            <td>Lokasi</td>
            <td>Gudang Non Medis<input type="hidden" id="hdn_lokasi" name="hdn_lokasi" value="GUDNM"></td>
            <td>No Faktur Supplier <span class="span-required">*</span></td>
            <td><input type="text" id="inp_faktursupplier" name="inp_faktursupplier" class="form-control inline-input" style="width: 180px" autocomplete="off"></td>
        </tr>
        <tr>
            <td>Supplier <span class="span-required">*</span></td>
            <td><span id="span_supplier"></span><input type="hidden" id="hdn_supplier" name="hdn_supplier"></td>
            <td>Catatan</td>
            <td rowspan="2"><textarea id="inp_catatan" name="inp_catatan" maxlength="1000" class="form-control" rows="3" style="resize: none"></textarea></td>
        </tr>
        <tr>
            <td>Tanggal Datang</td>
            <td><?php echo $today ?><input type="hidden" id="inp_tgldatang" name="inp_tgldatang" value="<?php echo $today ?>"></td>
        </tr>
        <tr>
            <td>Barang yang Diterima</td>
            <td colspan="3">
                <a class="btn btn-sm btn-primary" onCLick="addRcpItem()"><i class="fa fa-check-square-o"></i> Pilih dari Pemesanan</a>
                <div id="div-scroll" style="height: 300px; overflow: scroll">
                    <input type="hidden" id="hdn_Rcpitems" name="hdn_Rcpitems">
                    <table class="table table-hover" id="tbl_listItems">
                        <thead>
                        <tr>
                                <th rowspan="2" style="min-width: 90px;">Kode</th><th rowspan="2" style="min-width: 200px;">Nama Barang</th><th rowspan="2" style="min-width: 55px" title="Satuan Kecil">SK</th><th rowspan="2" style="min-width: 55px" title="Satuan Besar">SB</th><th rowspan="2" style="min-width: 55px">Isi</th><th rowspan="2" style="min-width: 90px;">Jml. Pesan</th><th rowspan="2" style="min-width: 110px;">Harga Per SB</th><th colspan="3" style="min-width: 150px;">Penerimaan</th><th rowspan="2" style="min-width: 90px;">Subtotal</th><th rowspan="2" style="min-width: 70px;">Diskon 1 (%)</th><th rowspan="2" style="min-width: 70px;">Diskon 2 (%)</th><th rowspan="2" style="min-width: 90px;">Total Diskon</th><th rowspan="2" style="min-width: 90px; background-color: #EEE">Total</th><th rowspan="2" style="min-width: 115px;">No. Pemesanan</th><th rowspan="2" style="min-width: 45px;">Aksi</th>
                            </tr>
                            <tr>
                                <th style="min-width: 80px;">Sudah Diterima</th><th colspan="2" style="min-width: 70px;">Jumlah Datang</th>
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
                PPN (%) <input type="text" id="inp_ppn" name="inp_ppn" class="form-control inline-input num-input" value="10" maxlength="2" onKeyUp="removeNonNumeric('inp_ppn'); recalculateRcp('N')" style="width: 40px" autocomplete="off"><span id="span_ppn" class="span-amount">0</span><input type="hidden" id="hdn_ppn" name="hdn_ppn"><span class="inline-spacer">|</span>
                Meterai <input type="text" id="inp_meterai" name="inp_meterai" class="form-control inline-input num-input" value="0" maxlength="6" onKeyUp="removeNonNumeric('inp_meterai'); recalculateRcp('N')" style="width: 65px" autocomplete="off"><span class="inline-spacer">|</span>
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
    function addRcpItem() {
        data = $("#hdn_Rcpitems").val();
        supplier = $("#hdn_supplier").val();
        window.open("select_po_nm.php?supplier=" + supplier + "&data=" + btoa(data), "_blank", "width=1000, height=500");
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
        recalculateRcp("N");
    }
    function validateForm() {
        user = $("#hdn_user").val();
        supplier = $("#hdn_supplier").val();
        tglfaktur = $("#inp_tglfaktur").val();
        tgljatuhtempo = $("#inp_tgljatuhtempo").val();
        nofaktur = $("#inp_faktursupplier").val();
        items = $("#hdn_Rcpitems").val();
        if (user == "-") {
            alert("Admin Utama tidak diizinkan untuk membuat Penerimaan Barang");
            return false;
        } else if (!supplier) {
            alert("Supplier kosong");
            $("#inp_supplier").focus();
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
                hrg = $("#inp_hpesan_" + items[i]).val();
                arrive = toFloat($("#inp_diterima_" + items[i]).val());
                total = $("#hdn_jml_" + items[i]).val();
                received = $("#hdn_sdhditerima_" + items[i]).val();
                remain = ((total*100) - (received*100))/100;
                if (!hrg || hrg == "0") {
                    alert("Harga Per Satuan Besar kosong");
                    $("#inp_hpesan_" + items[i]).focus();
                    return false;
                } else if (!arrive || arrive == "0") {
                    alert("Jumlah Barang Datang kosong");
                    $("#inp_diterima_" + items[i]).focus();
                    return false;
                } else if (arrive > remain) {
                    alert("Jumlah Barang Datang tidak boleh lebih dari " + formatNumber(remain));
                    $("#inp_diterima_" + items[i]).focus();
                    return false;
                }
            }
        }
        return true;
    }
    setTimeout(function () {
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
    $(window).resize(function() { resizeDivScroll(); resizeSpacer(); });
</script>

<?php require_once '../template/footer.php' ?>