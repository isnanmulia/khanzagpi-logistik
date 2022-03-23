<?php
    require_once '../template/header.php';
    require_once '../config/connect_app.php';
    require_once '../function/access.php';
    require_once '../function/function.php';
    restrictAccess("rekap_permintaan_non_medis");
    $req = array("kd_bangsal" => "", "bangsaldari" => "", "kd_bangsaltujuan" => "", "bangsalke" => ""); $lstReqDtl = ""; $lstReqItems = [];
    if (isset($_POST["form_submit"])) {
        mysqli_autocommit($connect_app, FALSE);
        $status = TRUE;
        $items = explode(",", $_POST["hdn_Transferitems"]);
        foreach ($items as $item) {
            $jml = str_replace(",", ".", str_replace(".", "", $_POST["inp_jml_" . $item]));
            $stokasal = $_POST["hdn_stokasal_" . $item];
            $stoktujuan = $_POST["hdn_stoktujuan_" . $item];
            if (strpos($item, "_") !== FALSE) {
                $itemx = explode("_", $item);
                $kode = $itemx[0];
                $reqNo = $itemx[1];
            } else {
                $kode = $item;
                $reqNo = "";
            }
            $qInsertTransfer = "INSERT INTO gpi_mutasibarangipsrs (kode_brng, no_permintaan, jml, harga, kd_bangsaldari, kd_bangsalke, tanggal, keterangan) VALUES ('" . $kode . "', '" . $reqNo . "', " . $jml . ", " . $_POST["hdn_dasar_" . $item] . ", '" . $_POST["hdn_serviceunitfrom"] . "', '" . $_POST["hdn_serviceunitto"] . "', '" . date("Y-m-d H:i:s") . "', '" . $_POST["inp_keterangan"] . "')";
            $insertTransfer = mysqli_query($connect_app, $qInsertTransfer);
            saveToTracker($qInsertTransfer, $connect_app);
            if (strlen($reqNo)) {
                $qUpdateReqDtl = "UPDATE detail_permintaan_non_medis SET status='Sudah Diproses' WHERE no_permintaan='" . $reqNo . "' AND kode_brng='" . $kode . "'";
                $updateReqDtl = mysqli_query($connect_app, $qUpdateReqDtl);
                saveToTracker($qUpdateReqDtl, $connect_app);
                $qCheckReqDtl = "SELECT (SELECT Count(kode_brng) FROM detail_permintaan_non_medis WHERE no_permintaan='" . $reqNo . "') AS X, (SELECT Count(kode_brng) FROM detail_permintaan_non_medis WHERE no_permintaan='" . $reqNo . "' AND status='Sudah Diproses') AS Y";
                $checkReqDtl = mysqli_query($connect_app, $qCheckReqDtl);
                $reqDtl = mysqli_fetch_assoc($checkReqDtl);
                if ($reqDtl["X"] == $reqDtl["Y"]) {
                    $qUpdateReq = "UPDATE permintaan_non_medis SET status='Disetujui' WHERE no_permintaan='" . $reqNo . "'";
                    $updateReq = mysqli_query($connect_app, $qUpdateReq);
                    saveToTracker($qUpdateReq, $connect_app);
                } else {
                    $qUpdateReq = "UPDATE permintaan_non_medis SET status='Disetujui Sebagian' WHERE no_permintaan='" . $reqNo . "'";
                    $updateReq = mysqli_query($connect_app, $qUpdateReq);
                    saveToTracker($qUpdateReq, $connect_app);
                }
            } else {
                $updateReqDtl = TRUE;
                $updateReq = TRUE;
            }
            $qHistoryFrom = "INSERT INTO gpi_riwayat_barang_nonmedis (kode_brng, stok_awal, masuk, keluar, stok_akhir, posisi, tanggal, jam, petugas, kd_bangsal, status) VALUES ('" . $kode . "', " . $stokasal . ", 0, " . $jml . ", " . ($stokasal-$jml) . ", 'Mutasi', '" . $today . "', '" . date("H:i:s") . "', '" . $_SESSION["USER"]["USERNAME"] . "', '" . $_POST["hdn_serviceunitfrom"] . "', 'Simpan')";
            $historyFrom = mysqli_query($connect_app, $qHistoryFrom);
            saveToTracker($qHistoryFrom, $connect_app);
            $qDepotFrom = "UPDATE gpi_gudangbarangipsrs SET stok=stok-" . $jml . " WHERE kode_brng='" . $kode . "' AND kd_bangsal='" . $_POST["hdn_serviceunitfrom"] . "'";
            $depotFrom = mysqli_query($connect_app, $qDepotFrom);
            saveToTracker($qDepotFrom, $connect_app);
            $qHistoryTo = "INSERT INTO gpi_riwayat_barang_nonmedis (kode_brng, stok_awal, masuk, keluar, stok_akhir, posisi, tanggal, jam, petugas, kd_bangsal, status) VALUES ('" . $kode . "', " . $stoktujuan . ", " . $jml . ", 0, " . ($stoktujuan+$jml) . ", 'Mutasi', '" . $today . "', '" . date("H:i:s") . "', '" . $_SESSION["USER"]["USERNAME"] . "', '" . $_POST["hdn_serviceunitto"] . "', 'Simpan')";
            $historyTo = mysqli_query($connect_app, $qHistoryTo);
            saveToTracker($qHistoryTo, $connect_app);
            $qCheckDepotTo = "SELECT stok FROM gpi_gudangbarangipsrs WHERE kode_brng='" . $kode . "' AND kd_bangsal='" . $_POST["hdn_serviceunitto"] . "'";
            $checkDepotTo = mysqli_query($connect_app, $qCheckDepotTo);
            $depotTo = mysqli_fetch_assoc($checkDepotTo);
            if ($depotTo) {
                $qDepotTo = "UPDATE gpi_gudangbarangipsrs SET stok=stok+" . $jml . " WHERE kode_brng='" . $kode . "' AND kd_bangsal='" . $_POST["hdn_serviceunitto"] . "'";
            } else {
                $qDepotTo = "INSERT INTO gpi_gudangbarangipsrs (kode_brng, kd_bangsal, stok) VALUES ('" . $kode . "', '" . $_POST["hdn_serviceunitto"] . "', " . $jml . ")";
            }
            $depotTo = mysqli_query($connect_app, $qDepotTo);
            saveToTracker($qDepotTo, $connect_app);
            $status = $status && ($insertTransfer && $historyFrom && $depotFrom && $historyTo && $depotTo && $updateReqDtl && $updateReq);
        }
        if ($status) {
            mysqli_commit($connect_app, TRUE);
            $msg = "Sukses menyimpan Mutasi Barang";
        } else {
            logError("add_item_transfer_nm.php", "insertTransfer:" . $insertTransfer . "|historyFrom:" . $historyFrom . "|depotFrom:" . $depotFrom . "|historyTo:" . $historyTo . "|depotTo:" . $depotTo . "|updateReqDtl:" . $updateReqDtl . "|updateReq:" . $updateReq);
            mysqli_rollback($connect_app);
            $msg = "Gagal menyimpan Mutasi Barang";
        }
        mysqli_autocommit($connect_app, TRUE);
        echo "<script>alert('" . $msg . "'); window.location.href='item_transfer_nm.php'</script>";
    } else if (isset($_GET["req"]) && $_GET["req"] != "") {
        $qGetReq = "SELECT M.kd_bangsal, BD.nm_bangsal AS bangsaldari, kd_bangsaltujuan, BK.nm_bangsal AS bangsalke FROM permintaan_non_medis M INNER JOIN bangsal BD ON M.kd_bangsal=BD.kd_bangsal INNER JOIN bangsal BK ON M.kd_bangsaltujuan=BK.kd_bangsal WHERE no_permintaan='" . $_GET["req"] . "'";
        $getReq = mysqli_query($connect_app, $qGetReq);
        $req = mysqli_fetch_assoc($getReq);
        $qGetReqDtl = "SELECT Concat(B.kode_brng,'_', '" . $_GET["req"] . "') AS kode, B.kode_brng, nama_brng, SK.satuan AS satuan_kecil, SB.satuan AS satuan_besar, B.isi, dasar, jumlah, IfNull(GA.stok, 0) AS stok_asal, IfNull(GT.stok, 0) AS stok_tujuan FROM ipsrsbarang B INNER JOIN kodesatuan SK ON B.kode_sat=SK.kode_sat INNER JOIN kodesatuan SB ON B.kode_satbesar=SB.kode_sat INNER JOIN detail_permintaan_non_medis DM ON B.kode_brng=DM.kode_brng LEFT JOIN gpi_gudangbarangipsrs GA ON B.kode_brng=GA.kode_brng AND GA.kd_bangsal='" . $req["kd_bangsaltujuan"] . "' LEFT JOIN gpi_gudangbarangipsrs GT ON B.kode_brng=GT.kode_brng AND GT.kd_bangsal='" . $req["kd_bangsal"] . "' WHERE no_permintaan='" . $_GET["req"] . "' AND DM.status='Proses Permintaan'";
        $getReqDtl = mysqli_query($connect_app, $qGetReqDtl);
        while ($data = mysqli_fetch_assoc($getReqDtl)) {
            array_push($lstReqItems, $data["kode"]);
            $lstReqDtl .= "<tr id='tr_" . $data["kode"] . "'><td>" . $data["kode_brng"] . "</td><td>" . str_replace('"', '&quot;', $data["nama_brng"]) . "<input type='hidden' id='hdn_dasar_" . $data["kode"] . "' name='hdn_dasar_" . $data["kode"] . "' value='" . $data["dasar"] . "'></td><td>" . $data["satuan_kecil"] . "</td><td>" . $data["satuan_besar"] . "</td><td class='num-input'>" . number_format($data["isi"], 0, ",", ".") . "</td><td class='num-input'>" . number_format($data["stok_asal"], 2, ",", ".") . " " . $data["satuan_kecil"] . "<input type='hidden' id='hdn_stokasal_" . $data["kode"] . "' name='hdn_stokasal_" . $data["kode"] . "' value='" . $data["stok_asal"] . "'></td><td class='num-input'>" . number_format($data["stok_tujuan"], 2, ",", ".") . " " . $data["satuan_kecil"] . "<input type='hidden' id='hdn_stoktujuan_" . $data["kode"] . "' name='hdn_stoktujuan_" . $data["kode"] . "' value='" . $data["stok_tujuan"] . "'></td><td style='width: 60px'><input type='text' id='inp_jml_" . $data["kode"] . "' name='inp_jml_" . $data["kode"] . "' class='form-control num-input' value='" . number_format($data["jumlah"], 2, ",", ".") . "' onKeyUp='removeNonNumeric(&quot;inp_jml_" . $data["kode_brng"] . "&quot;); recheckTransfer(&quot;N&quot;)' maxlength='5' autocomplete='off' style='width: 60px'></td><td style='width: 100px'>" . $data["satuan_kecil"] . "</td><td>" . $_GET["req"]  . "</td><td><a class='btn btn-danger btn-sm' title='Hapus' href='#' onClick='if(confirm(&quot;Apakah Anda yakin ingin menghapus data ini?&quot;)) removeItem(&quot;" . $data["kode"] . "&quot;)'><i class='fa fa-trash'></i></a></td></tr>";
        }
    }
?>

<form name="frmAddTransfer" method="POST" target="" onSubmit="return validateForm()">
    <table id="tbl_addTransfer" class="table">
        <tr>
            <td style="width: 150px">Asal Mutasi</td>
            <td style="width: 350px">GUDANG NON MEDIS<input type="hidden" id="hdn_serviceunitfrom" name="hdn_serviceunitfrom" value="GUDNM"></td>
            <td style="width: 150px">Tanggal</td>
            <td style="width: 350px"><?php echo $today ?><input type="hidden" id="inp_tanggal" name="inp_tanggal" value="<?php echo $today ?>"></td>
        </tr>
        <tr>
            <td>Tujuan Mutasi <span class="span-required">*</span></td>
            <td><input type="text" id="inp_serviceunitto" name="inp_serviceunitto" class="form-control inline-input" style="width: 200px" onKeyUp="getServiceUnit('to')" autocomplete="off" value="<?php echo $req["bangsaldari"] ?>" <?php if (strlen($req["bangsaldari"])) echo "disabled" ?>><input type="hidden" id="hdn_serviceunitto" name="hdn_serviceunitto" value="<?php echo $req["kd_bangsal"] ?>"></td>
            <td>Keterangan <span class="span-required">*</span></td>
            <td><input type="text" id="inp_keterangan" name="inp_keterangan" class="form-control inline-input" style="width: 200px" autocomplete="off"></td>
        </tr>
        <tr>
            <td>Barang yang Dimutasikan</td>
            <td colspan="3">
                <a class="btn btn-sm btn-primary" onClick="addTransferItem()"><i class='fa fa-plus'></i> Tambah</a>
                <a class="btn btn-sm btn-primary" onClick="addTransferItem('R')"><i class='fa fa-check-square-o'></i> Pilih dari Permintaan</a>
                <div id="div-scroll" style="height: 300px; overflow: scroll">
                    <input type="hidden" id="hdn_Transferitems" name="hdn_Transferitems" value="<?php echo implode(",", $lstReqItems) ?>">
                    <input type="hidden" id="hdn_Requestitems" name="hdn_Requestitems" value="<?php echo implode(",", $lstReqItems) ?>">
                    <table class="table table-hover" id="tbl_listItems">
                        <thead>
                            <tr>
                                <th style="min-width: 90px">Kode Barang</th><th style="min-width: 250px">Nama Barang</th><th style="min-width: 55px" title="Satuan Kecil">SK</th><th style="min-width: 55px" title="Satuan Besar">SB</th><th style="min-width: 55px">Isi</th><th style="min-width: 120px">Stok Asal</th><th style="min-width: 120px">Stok Tujuan</th><th colspan="2" style="min-width: 140px">Jumlah Mutasi</th><th style="min-width: 115px;">No. Pemesanan</th><th style='min-width: 45px'>Aksi</th>
                            </tr>
                        </thead>
                        <tbody><?php echo $lstReqDtl ?></tbody>
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
    function addTransferItem(type="") {
        frward = $("#hdn_serviceunitfrom").val();
        toward = $("#hdn_serviceunitto").val();
        if (!frward) {
            alert("Asal Mutasi kosong");
            $("#inp_serviceunitfrom").focus();
        } else if (!toward) {
            alert("Tujuan Mutasi kosong");
            $("#inp_serviceunitto").focus();
        } else if (frward == toward) {
            alert("Asal Mutasi dan Tujuan Mutasi tidak boleh sama");
            return false;
        } else {
            if (type == "R") {
                data = $("#hdn_Requestitems").val();
                window.open("select_req_nm.php?from=" + toward + "&to=" + frward + "&data=" + btoa(data), "_blank", "width=1000, height=500");
            } else {
                idx++;
                $("#tbl_listItems tbody").append("<tr id='tr_newItem_" + idx + "'><td></td><td><input type='text' id='inp_newItem_" + idx + "' name='inp_newItem_" + idx + "' class='form-control inline-input' style='width: 170px' placeholder='Ketikkan nama barang...' onKeyUp='getItem(&quot;newItem_" + idx + "&quot;)' autocomplete='off'><span class='text-red' title='Batal' style='padding: 4px' onClick='$(&quot;#tr_newItem_" + idx + "&quot;).fadeOut()'><i class='fa fa-times'></i></span></td></tr>");
                $("#inp_newItem_" + idx).focus();
            }
        }
    }
    function getItem(where) {
        term = $("#inp_" + where).val();
        frward = $("#hdn_serviceunitfrom").val();
        toward = $("#hdn_serviceunitto").val();
        if (term.length > 0) {
            data = $("#hdn_Transferitems").val().split(",");
            ajax_getData("ajax_getdata.php?type=transfernmadd&from=" + where + "&frward=" + frward + "&toward=" + toward + "&term=" + term + "&data=" + btoa(data));
        } else removeSuggest();
    }
    function removeItem(id) {
        items = $("#hdn_Transferitems").val().split(",");
        for (i=0; i<items.length; i++) {
            if (items[i] == id) {
                items.splice(i, 1);
                break;
            }
        }
        $("#hdn_Transferitems").val(items.join(","));
        $("#tr_" + id).fadeOut();
        $("#tr_" + id).remove();
    }
    function validateForm() {
        frward = $("#hdn_serviceunitfrom").val();
        toward = $("#hdn_serviceunitto").val();
        ket = $("#inp_keterangan").val();
        items = $("#hdn_Transferitems").val();
        if (!frward) {
            alert("Asal Mutasi kosong");
            $("#inp_serviceunitfrom").focus();
            return false;
        } else if (!toward) {
            alert("Tujuan Mutasi kosong");
            $("#inp_serviceunitto").focus();
            return false;
        } else if (frward == toward) {
            alert("Asal Mutasi dan Tujuan Mutasi tidak boleh sama");
            return false;
        } else if (!ket) {
            alert("Keterangan kosong");
            $("#inp_keterangan").focus();
            return false;
        } else if (!items) {
            alert("Tidak ada barang yang dimutasikan");
            return false;
        } else {
            items = items.split(",");
            for (i=0; i<items.length; i++) {
                jml = toFloat($("#inp_jml_" + items[i]).val());
                stokasal = parseFloat($("#hdn_stokasal_" + items[i]).val());
                if (!jml || jml == 0) {
                    alert("Jumlah barang yang dimutasikan tidak boleh nol");
                    $("#inp_jml_" + items[i]).focus();
                    return false;
                } else if (jml > stokasal) {
                    alert("Jumlah barang yang dimutasikan tidak boleh lebih dari " + formatNumber(stokasal));
                    $("#inp_jml_" + items[i]).focus();
                    return false;
                }
            }
        }
        return true;
    }
    setTimeout(function() { resizeDivScroll(); }, 500);
    $(window).resize(function() { resizeDivScroll(); });
</script>

<?php require_once '../template/footer.php' ?>