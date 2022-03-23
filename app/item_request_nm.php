<?php
    require_once '../template/header.php';
    require_once '../config/connect_app.php';
    require_once '../function/access.php';
    restrictAccess("permintaan_non_medis");
    var_dump($_SESSION["USER"]["BANGSAL"]);
    $qGetRequest = "SELECT no_permintaan, P.nip, P.nama, tanggal, PNM.kd_bangsal, BD.nm_bangsal AS bangsaldari, BK.nm_bangsal AS bangsalke, PNM.status FROM permintaan_non_medis PNM INNER JOIN petugas P ON PNM.nip=P.nip INNER JOIN bangsal BD ON PNM.kd_bangsal=BD.kd_bangsal INNER JOIN bangsal BK ON PNM.kd_bangsaltujuan=BK.kd_bangsal WHERE tanggal BETWEEN '" . $today . "' AND '" . $today . "'" . (!getAccess("rekap_permintaan_non_medis") ? " AND PNM.nip='" . $_SESSION["USER"]["USERNAME"] . "'" : "") . " ORDER BY no_permintaan";
    $getRequest = mysqli_query($connect_app, $qGetRequest);
    $lstRequest = "";
    while ($data = mysqli_fetch_assoc($getRequest)) {
        $btnEdit = ""; $btnAct = ""; $btnKonfirmasi = "";
        switch ($data["status"]) {
            case "Baru":
                $status = "<i class='fa fa-plus-square text-blue' title='Baru'></i>";
                $btnEdit = "<a class='btn btn-primary btn-sm' href='edit_item_request_nm.php?id=" . $data["no_permintaan"] . "' title='Ubah'><i class='fa fa-edit'></i></a> ";
                $btnAct = "<a class='btn btn-success btn-sm' href='add_item_transfer_nm.php?req=" . $data["no_permintaan"] . "' title='Mutasi'><i class='fa fa-arrow-right'></i></a>";
                $btnKonfirmasi = "<a class='btn btn-sm bg-purple' href='#' onClick='confirmRequest(\"" . $data["no_permintaan"] . "\")' title='Dikonfirmasi'><i class='fa fa-check-square-o'></i></a> ";
                break;
            case "Dikonfirmasi":
                $status = "<i class='fa fa-check-square-o text-purple' title='Dikonfirmasi'></i>";
                $btnAct = "<a class='btn btn-success btn-sm' href='add_item_transfer_nm.php?req=" . $data["no_permintaan"] . "' title='Mutasi'><i class='fa fa-arrow-right'></i></a>";
                break;
            case "Disetujui Sebagian":
                $status = "<i class='fa fa-list text-blue' title='Disetujui Sebagian'></i>";
                $btnAct = "<a class='btn btn-success btn-sm' href='add_item_transfer_nm.php?req=" . $data["no_permintaan"] . "' title='Mutasi'><i class='fa fa-arrow-right'></i></a>";
                break;
            case "Disetujui": $status = "<i class='fa fa-check text-green' title='Disetujui'></i>"; break;
            case "Tidak Disetujui": $status = "<i class='fa fa-minus-circle text-red' title='Tidak Disetujui'></i>"; break;
            default: $status = ""; break;
        }
        if (strpos($_SESSION["USER"]["BANGSAL"], str_replace("-", "_", $data["kd_bangsal"])) !== false) {
            $btnPrint = "<a class='btn btn-primary btn-sm' href='#' onClick='printReq(&quot;" . $data["no_permintaan"] . "&quot;)' title='Cetak'><i class='fa fa-print'></i></a> ";
        } else $btnPrint = "";
        $lstRequest .= "<tr><td>" . $data["no_permintaan"] . " <span id='chevron_" . $data["no_permintaan"] . "' class='chevron' title='Klik untuk menampilkan/menyembunyikan detail permintaan' onClick='toggleRequestDetail(&quot;" . $data["no_permintaan"] . "&quot;)'><i class='fa fa-chevron-circle-down'></i></span></td><td>" . $data["tanggal"] . "</td><td>" . $data["bangsaldari"] . "</td><td>" . $data["bangsalke"] . "</td><td>" . $data["nama"] . "</td><td>" . $status . "</td><td>" . ($data["nip"] == $_SESSION["USER"]["USERNAME"] ? $btnEdit : "") . $btnPrint . (getAccess("rekap_permintaan_non_medis") ? $btnKonfirmasi . $btnAct : "") . "</td></tr><tr id='tr_" . $data["no_permintaan"] . "' style='display: none'><td id='td_" . $data["no_permintaan"] . "' class='td-detail' colspan='7'>-</td></tr>";
    }
    if (!strlen($lstRequest)) $lstRequest = "<tr><td colspan='7' style='text-align: center'>--- Tidak ada data ---</td></tr>";
    $lstRequest = "<thead><th style='width: 115px'>No. Permintaan</th><th style='width: 85px'>Tanggal</th><th style='width: 160px'>Asal Permintaan</th><th style='width: 160px'>Ditujukan Ke</th><th style='width: 200px'>Petugas</th><th style='width: 60px'>Status</th><th style='width: 115px'>Aksi</th></thead><tbody>" . $lstRequest . "</tbody>";
?>

Asal Permintaan&nbsp;&nbsp;
<input type="text" id="inp_serviceunitfrom" name="inp_serviceunitfrom" class="form-control inline-input" style="width: 200px" onKeyUp="getServiceUnit('from')" autocomplete="off"><input type="hidden" id="hdn_serviceunitfrom" name="hdn_serviceunitfrom">&nbsp;&nbsp;
Ditujukan Ke&nbsp;&nbsp;
<input type="text" id="inp_serviceunitto" name="inp_serviceunitto" class="form-control inline-input" style="width: 200px" onKeyUp="getServiceUnit('to')" autocomplete="off" value="GUDANG NON MEDIS" disabled><input type="hidden" id="hdn_serviceunitto" name="hdn_serviceunitto" value="GUDNM">&nbsp;&nbsp;
Tanggal&nbsp;&nbsp;
<input type="text" id="inp_tglmulai" name="inp_tglmulai" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $today ?>">&nbsp;&nbsp;s.d.&nbsp;&nbsp;<input type="text" id="inp_tglselesai" name="inp_tglselesai" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $today ?>">&nbsp;&nbsp;
<a class="btn btn-primary" href="#" onClick="getRequestList()"><i class="fa fa-search"></i> Cari</a>
<a class="btn btn-primary" href="add_item_request_nm.php"><i class="fa fa-plus"></i> Tambah</a>
<table id="tbl_itemrequest" class="table table-hover">
    <?php echo $lstRequest ?>
</table>

<script>
    function getRequestList() {
        dari = $("#hdn_serviceunitfrom").val();
        ke = $("#hdn_serviceunitto").val();
        mulai = $("#inp_tglmulai").val();
        selesai = $("#inp_tglselesai").val();
        if (!mulai) {
            alert("Tanggal Mulai kosong");
            $("#inp_tglmulai").focus();
        } else if (!selesai) {
            alert("Tanggal Selesai kosong");
            $("#inp_tglselesai").focus();
        } else ajax_getData("ajax_getdata.php?type=reqnmlist&from=" + dari + "&to=" + ke + "&start=" + mulai + "&end=" + selesai);
    }
    function toggleRequestDetail(requestNo) {
        if ($("#tr_" + requestNo).css("display") == "none") {
            $("#chevron_" + requestNo).html("<i class='fa fa-chevron-circle-up'></i>");
            if ($("#td_" + requestNo).html() == "-") ajax_getData("ajax_getdata.php?type=reqnmdtl&id=" + requestNo);
        } else $("#chevron_" + requestNo).html("<i class='fa fa-chevron-circle-down'></i>");
        $("#tr_" + requestNo).fadeToggle();
    }
    function confirmRequest(requestNo) {
        ajax_getData("confirm_item_request.php?type=N&id=" + requestNo);
    }
    function printReq(reqNo) {
        param = btoa("id=" + reqNo);
        window.open("show_report.php?id=item_request_nm&param=" + param, "_blank", "width=1000, height=700");
    }
    setTimeout(function() {
        $(".tanggal").datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            language: 'id',
            todayHighlight: true,
            weekStart: 1
        });
    }, 1000);
</script>

<?php require_once '../template/footer.php' ?>