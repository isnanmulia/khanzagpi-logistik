<?php
    require_once '../template/header.php';
    require_once '../config/connect_app.php';
    require_once '../function/access.php';
    require_once '../function/function.php';
    restrictAccess("permintaan_non_medis");
    if (isset($_POST["form_submit"])) {
        mysqli_autocommit($connect_app, FALSE);
        $lstReqItems = explode(",", $_POST["hdn_Requestitems"]);
        $lstOriItems = explode(",", $_POST["hdn_oriitems"]);
        $lstAdd = array_diff($lstReqItems, $lstOriItems);
        $lstKeep = array_intersect($lstReqItems, $lstOriItems);
        $status = TRUE;
        if (strlen($_POST["hdn_delitems"])) {
            $delitems = explode(",", $_POST["hdn_delitems"]);
            foreach ($delitems as $item) {
                $qDeleteReqDtl = "DELETE FROM detail_permintaan_non_medis WHERE no_permintaan='" . $_POST["hdn_id"] . "' AND kode_brng='" . $item . "'";
                $deleteReqDtl = mysqli_query($connect_app, $qDeleteReqDtl);
                saveToTracker($qDeleteReqDtl, $connect_app);
                $status = $status && $deleteReqDtl;
            }
        }
        foreach ($lstKeep as $item) {
            $qUpdateReqDtl = "UPDATE detail_permintaan_non_medis SET jumlah=" . str_replace(",", ".", str_replace(".", "", $_POST["inp_jml_" . $item])) . ", keterangan='" . $_POST["inp_keterangan_" . $item] . "' WHERE no_permintaan='" . $_POST["hdn_id"] . "' AND kode_brng='" . $item . "'";
            $updateReqDtl = mysqli_query($connect_app, $qUpdateReqDtl);
            saveToTracker($qUpdateReqDtl, $connect_app);
            $status = $status && $updateReqDtl;
        }
        foreach ($lstAdd as $item) {
            $qInsertReqDtl = "INSERT INTO detail_permintaan_non_medis (no_permintaan, kode_brng, kode_sat, jumlah, keterangan, status) VALUES ('" . $_POST["hdn_id"] . "', '" . $item . "', '" . $_POST["hdn_sat_" . $item] . "', " . str_replace(",", ".", str_replace(".", "", $_POST["inp_jml_" . $item])) . ", '" . $_POST["inp_keterangan_" . $item] . "', 'Proses Permintaan')";
            $insertReqDtl = mysqli_query($connect_app, $qInsertReqDtl);
            saveToTracker($qInsertReqDtl, $connect_app);
            $status = $status && $insertReqDtl;
        }
        if ($status) {
            mysqli_commit($connect_app);
            $msg = "Sukses mengubah Permintaan";
        } else {
            mysqli_rollback($connect_app);
            $msg = "Gagal mengubah Permintaan";
        }
        mysqli_autocommit($connect_app, TRUE);
        echo "<script>alert('" . $msg . "'); window.location.href='item_request_nm.php'</script>";
    } else if (!isset($_GET["id"]) || $_GET["id"] == "") {
        denyAccess();
    } else if (isset($_GET["id"])) {
        $reqNo = $_GET["id"];
        $qGetReq = "SELECT P.nama, tanggal, M.kd_bangsal, BD.nm_bangsal AS bangsaldari, kd_bangsaltujuan, BK.nm_bangsal AS bangsalke, M.status FROM permintaan_non_medis M INNER JOIN petugas P ON M.nip=P.nip INNER JOIN bangsal BD ON M.kd_bangsal=BD.kd_bangsal INNER JOIN bangsal BK ON M.kd_bangsaltujuan=BK.kd_bangsal WHERE no_permintaan='" . $reqNo . "'";
        $getReq = mysqli_query($connect_app, $qGetReq);
        $req = mysqli_fetch_assoc($getReq);
        $qGetReqDtl = "SELECT DM.kode_brng, nama_brng, nm_jenis, DM.kode_sat, satuan, jumlah, keterangan, IfNull(G.stok, 0) AS stok_asal FROM detail_permintaan_non_medis DM INNER JOIN ipsrsbarang B ON DM.kode_brng=B.kode_brng INNER JOIN ipsrsjenisbarang J ON B.jenis=J.kd_jenis INNER JOIN kodesatuan S ON DM.kode_sat=S.kode_sat LEFT JOIN gpi_gudangbarangipsrs G ON B.kode_brng=G.kode_brng AND kd_bangsal='" . $req["kd_bangsal"] . "' WHERE no_permintaan='" . $reqNo . "'";
        $getReqDtl = mysqli_query($connect_app, $qGetReqDtl);
        $lstReqDtl = ""; $lstReqItems = [];
        while ($data = mysqli_fetch_assoc($getReqDtl)) {
            array_push($lstReqItems, $data["kode_brng"]);
            $lstReqDtl .= "<tr id='tr_" . $data["kode_brng"] . "'><td>" . $data["kode_brng"] . "</td><td>" . addslashes($data["nama_brng"]) . "</td><td>" . $data["nm_jenis"] . "</td><td style='width: 60px'><input type='text' id='inp_jml_" . $data["kode_brng"] . "' name='inp_jml_" . $data["kode_brng"] . "' class='form-control num-input' value='" . $data["jumlah"] . "' onKeyUp='removeNonNumeric(&quot;inp_jml_" . $data["kode_brng"] . "&quot;); recheckRequest(&quot;N&quot;)' maxlength='5' autocomplete='off' style='width: 60px'></td><td>" . $data["satuan"] . "<input type='hidden' id='hdn_sat_" . $data["kode_brng"] . "' name='hdn_sat_" . $data["kode_brng"] . "' value='" . $data["kode_sat"] . "'></td><td><input type='text' id='inp_keterangan_" . $data["kode_brng"] . "' name='inp_keterangan_" . $data["kode_brng"] . "' class='form-control' autocomplete='off' style='width: 150px' value='" . $data["keterangan"] . "'></td><td class='num-input'>" . $data["stok_asal"] . "</td><td><a class='btn btn-danger btn-sm' title='Hapus' href='#' onClick='if(confirm(&quot;Apakah Anda yakin ingin menghapus data ini?&quot;)) removeItem(&quot;" . $data["kode_brng"] . "&quot;)'><i class='fa fa-trash'></i></a></td></tr>";
        }
    }
?>

<form name="frmEditRequest" method="POST" target="" onSubmit="return validateForm()">
    <table id="tbl_editRequest" class="table">
        <tr>
            <td style="width: 150px">No Permintaan</td>
            <td style="width: 350px"><?php echo $reqNo ?><input type="hidden" id="hdn_id" name="hdn_id" value="<?php echo $reqNo ?>"></td>
            <td style="width: 150px">Asal Permintaan</td>
            <td style="width: 350px"><?php echo $req["bangsaldari"] ?><input type="hidden" id="hdn_serviceunitfrom" name="hdn_serviceunitfrom" value="<?php echo $req["kd_bangsal"] ?>"></td>
        </tr>
        <tr>
            <td>Petugas</td>
            <td><?php if ($_SESSION["USER"]["USERNAME"] != "admin") echo $_SESSION["USER"]["FULLNAME"]; else echo "-"; ?><input type="hidden" id="hdn_user" name="hdn_user" value="<?php if ($_SESSION["USER"]["USERNAME"] != "admin") echo $_SESSION["USER"]["USERNAME"]; else echo "-"; ?>"></td>
            <td>Ditujukan Ke</td>
            <td><?php echo $req["bangsalke"] ?><input type="hidden" id="hdn_serviceunitto" name="hdn_serviceunitto" value="<?php echo $req["kd_bangsaltujuan"] ?>"></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td><?php echo $req["tanggal"] ?><input type="hidden" id="inp_tanggal" name="inp_tanggal" value="<?php echo $req["tanggal"] ?>"></td>
        </tr>
        <tr>
            <td>Barang yang Diminta</td>
            <td colspan="3">
                <a class="btn btn-sm btn-primary" onClick="addRequestItem()"><i class="fa fa-plus"></i> Tambah</a>
                <div id="div-scroll" style="height: 300px; overflow: scroll;">
                    <input type="hidden" id="hdn_Requestitems" name="hdn_Requestitems" value="<?php echo implode(",", $lstReqItems) ?>">
                    <input type="hidden" id="hdn_delitems" name="hdn_delitems">
                    <input type="hidden" id="hdn_oriitems" name="hdn_oriitems" value="<?php echo implode(",", $lstReqItems) ?>">
                    <table class="table table-hover" id="tbl_listItems">
                        <thead>
                            <tr>
                                <th style="min-width: 90px">Kode Barang</th><th style="min-width: 300px">Nama Barang</th><th style="min-width: 85px">Jenis Barang</th><th colspan="2" style="min-width: 140px">Jumlah</th><th style="min-width: 150px">Keterangan</th><th style="min-width: 70px">Stok Asal</th><th style='min-width: 45px'>Aksi</th>
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
    function addRequestItem() {
        frward = $("#hdn_serviceunitfrom").val();
        idx++;
        $("#tbl_listItems tbody").append("<tr id='tr_newItem_" + idx + "'><td></td><td><input type='text' id='inp_newItem_" + idx + "' name='inp_newItem_" + idx + "' class='form-control' style='display: inline-block; width: 170px' placeholder='Ketikkan nama barang...' onKeyUp='getItem(&quot;newItem_" + idx + "&quot;)' autocomplete='off'><span class='text-red' title='Batal' style='padding: 4px' onClick='$(&quot;#tr_newItem_" + idx + "&quot;).fadeOut()'><i class='fa fa-times'></i></span></td></tr>");
        $("#inp_newItem_" + idx).focus();
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
        appendItems("del", id);
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