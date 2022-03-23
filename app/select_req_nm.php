<?php
    require_once '../config/connect_app.php';
    require_once '../config/session_check.php';
    require_once '../function/access.php';
    restrictAccess("rekap_permintaan_non_medis");
    if (isset($_POST["form_submit"])) {
        $barang = $_POST["hdn_itemsToTransfer"];
        $qGetSelTransferItems = "SELECT * FROM (SELECT Concat(B.kode_brng, '_', no_permintaan) AS kode, no_permintaan, B.kode_brng, nama_brng, nm_jenis, B.kode_sat, SK.satuan AS satuan_kecil, B.kode_satbesar, SB.satuan AS satuan_besar, isi, dasar, jumlah, IfNull(GA.stok, 0) AS stok_asal, IfNull(GT.stok, 0) AS stok_tujuan FROM detail_permintaan_non_medis DPNM INNER JOIN ipsrsbarang B ON DPNM.kode_brng=B.kode_brng INNER JOIN ipsrsjenisbarang J ON B.jenis=J.kd_jenis INNER JOIN kodesatuan SK ON B.kode_sat=SK.kode_sat INNER JOIN kodesatuan SB ON B.kode_satbesar=SB.kode_sat LEFT JOIN gpi_gudangbarangipsrs GA ON GA.kode_brng=B.kode_brng AND GA.kd_bangsal='" . $_POST["hdn_serviceunitto"] . "' LEFT JOIN gpi_gudangbarangipsrs GT ON GT.kode_brng=B.kode_brng AND GT.kd_bangsal='" . $_POST["hdn_serviceunitfrom"] . "') AS t1 WHERE kode IN ('" . str_replace(",", "','", $barang) . "') ORDER BY kode_brng, no_permintaan";
        $getSelTransferItems = mysqli_query($connect_app, $qGetSelTransferItems);
        $selTransferItems = "";
        while ($data = mysqli_fetch_assoc($getSelTransferItems)) {
            $selTransferItems .= "<tr id='tr_" . $data["kode"] . "'><td>" . $data["kode_brng"] . "</td><td>" . addslashes($data["nama_brng"]) . "<input type='hidden' id='hdn_dasar_" . $data["kode"] . "' name='hdn_dasar_" . $data["kode"] . "' value='" . $data["dasar"] . "'></td><td>" . $data["satuan_kecil"] . "</td><td>" . $data["satuan_besar"] . "</td><td class='num-input'>" . number_format($data["isi"], 0, ",", ".") . "</td><td class='num-input'>" . number_format($data["stok_asal"], 2, ",", ".") . " " . $data["satuan_kecil"] . "<input type='hidden' id='hdn_stokasal_" . $data["kode"] . "' name='hdn_stokasal_" . $data["kode"] . "' value='" . $data["stok_asal"] . "'></td><td class='num-input'>" . number_format($data["stok_tujuan"], 2, ",", ".") . " " . $data["satuan_kecil"] . "<input type='hidden' id='hdn_stoktujuan_" . $data["kode"] . "' name='hdn_stoktujuan_" . $data["kode"] . "' value='" . $data["stok_tujuan"] . "'></td><td style='width: 60px'><input type='text' id='inp_jml_" . $data["kode"] . "' name='inp_jml_" . $data["kode"] . "' class='form-control num-input' value='" . number_format($data["jumlah"], 2, ",", ".") . "' onKeyUp='removeNonNumeric(&quot;inp_jml_" . $data["kode"] . "&quot;); recheckTransfer(&quot;N&quot;)' maxlength='5' autocomplete='off' style='width: 60px'></td><td style='width: 100px'>" . $data["satuan_kecil"] . "</td><td>" . $data["no_permintaan"] . "</td><td><a class='btn btn-danger btn-sm' title='Hapus' href='#' onClick='if(confirm(&quot;Apakah Anda yakin ingin menghapus data ini?&quot;)) removeItem(&quot;" . $data["kode"] . "&quot;)'><i class='fa fa-trash'></i></a></td></tr>";
        }
        echo '<script>
            window.opener.$("#tbl_listItems tbody").append("' . $selTransferItems . '");
            window.opener.appendItems("Transfer", "' . $barang . '");
            window.opener.appendItems("Request", "' . $barang . '");
            window.opener.recheckTransfer();
            window.close();
        </script>';
        exit();
    }
    $qGetReq = "SELECT no_permintaan, P.nip, P.nama, tanggal, PNM.status FROM permintaan_non_medis PNM INNER JOIN petugas P ON PNM.nip=P.nip INNER JOIN bangsal BD ON PNM.kd_bangsal=BD.kd_bangsal INNER JOIN bangsal BK ON PNM.kd_bangsaltujuan=BK.kd_bangsal WHERE tanggal BETWEEN '" . $today . "' AND '" . $today . "' AND PNM.kd_bangsal='" . $_GET["from"] . "' AND kd_bangsaltujuan='" . $_GET["to"] . "' AND PNM.status IN ('Baru','Disetujui Sebagian') ORDER BY no_permintaan";
    $qGetReq = mysqli_query($connect_app, $qGetReq);
    $lstReq = "";
    while ($data = mysqli_fetch_assoc($qGetReq)) {
        switch ($data["status"]) {
            case "Baru": $status = "<i class='fa fa-plus-square text-blue' title='Baru'></i>"; break;
            case "Disetujui Sebagian": $status = "<i class='fa fa-list text-blue' title='Disetujui Sebagian'></i>"; break;
        }
        $lstReq .= "<tr><td>" . $data["no_permintaan"] . " <span id='chevron_" . $data["no_permintaan"] . "' class='chevron' title='Klik untuk menampilkan/menyembunyikan detail permintaan' onClick='toggleRequestDetail(&quot;" . $data["no_permintaan"] . "&quot;)'><i class='fa fa-chevron-circle-down'></i></span></td><td>" . $data["tanggal"] . "</td><td>" . $data["nama"] . "</td><td>" . $status . "</td></tr><tr id='tr_" . $data["no_permintaan"] . "' style='display: none'><td id='td_" . $data["no_permintaan"] . "' class='td-detail' colspan='4'>-</td></tr>";
        if (!strlen($lstReq)) $lstReq = "<tr><td colspan='4' style='text-align: center'>--- Tidak ada data ---</tr>";
    }
    $lstReq = "<thead><th style='width: 115px'>No. Permintaan</th><th style='width: 85px'>Tanggal</th><th style='width: 200px'>Petugas</th><th style='width: 60px'>Status</th></thead><tbody>" . $lstReq . "</tbody>";
    $qGetServiceUnit = "SELECT * FROM (SELECT nm_bangsal AS nmDari FROM bangsal WHERE kd_bangsal='" . $_GET["from"] . "') X CROSS JOIN (SELECT nm_bangsal AS nmKe FROM bangsal WHERE kd_bangsal='" . $_GET["to"] . "') Y" ;
    $getServiceUnit = mysqli_query($connect_app, $qGetServiceUnit);
    $serviceUnit = mysqli_fetch_assoc($getServiceUnit);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Permintaan Non Medis yang Belum Selesai | Aplikasi Utilitas Khanza GPI</title>
        <link rel="stylesheet" href="<?php echo $base_url ?>/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/bower_components/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/bower_components/jquery-ui/themes/base/jquery-ui.min.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/plugins/pace/pace.min.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/dist/css/AdminLTE.min.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/styles/style.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/styles/popup.css">
    </head>
    <body onClick="removeSuggest()">
        <div class="content-wrapper">
            <h4>Permintaan Non Medis yang Belum Selesai</h4>
            <form id="frmSelectReq" action="" method="POST">
                Asal Permintaan: <strong><?php echo $serviceUnit["nmDari"] ?></strong><input type="hidden" id="hdn_serviceunitfrom" name="hdn_serviceunitfrom" value="<?php echo $_GET["from"] ?>"><span class="inline-spacer">|</span> 
                Ditujukan Ke: <strong><?php echo $serviceUnit["nmKe"] ?></strong><input type="hidden" id="hdn_serviceunitto" name="hdn_serviceunitto" value="<?php echo $_GET["to"] ?>"><br>
                Tanggal&nbsp;&nbsp;
                <input type="text" id="inp_tglmulai" name="inp_tglmulai" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $today ?>">&nbsp;&nbsp;s.d.&nbsp;&nbsp;<input type="text" id="inp_tglselesai" name="inp_tglselesai" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $today ?>">
                <a class="btn btn-primary" href="#" onClick="getRequestList()"><i class="fa fa-search"></i> Cari</a>
                <div style="float: right">
                    <input type="hidden" id="form_submit" name="form_submit" value="1">
                    <input type="hidden" id="hdn_itemsToTransfer" name="hdn_itemsToTransfer">
                    <button type="submit" id="btn_submit" class="btn btn-primary" disabled><i class="fa fa-check-square-o"></i> Pilih</button>
                </div>
                <div class="div-list-popup">
                    <table id="tbl_itemrequest" class="table table-hover">
                        <?php echo $lstReq ?>
                    </table>
                </div>
            </form>
        </div>
    </body>
    <script src="<?php echo $base_url ?>/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo $base_url ?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?php echo $base_url ?>/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo $base_url ?>/bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.id.min.js"></script>
    <script src="<?php echo $base_url ?>/bower_components/PACE/pace.min.js"></script>
    <script src="<?php echo $base_url ?>/scripts/script.js"></script>
    <script>
        function activateBtnPilih() {
            if ($("input:checkbox:checked").length) $("#btn_submit").prop("disabled", false);
                else $("#btn_submit").prop("disabled", true);
            itemsToTransfer();
        }
        function itemsToTransfer() {
            lstChecked = [];
            $("input:checkbox:checked").each(function() {
                a = $(this).prop("name");
                b = a.split("_");
                lstChecked.push(b[1] + "_" + b[2]);
            });
            $("#hdn_itemsToTransfer").val(lstChecked.sort().join(","));
        }
        function getRequestList() {
            frward = $("#hdn_serviceunitfrom").val();
            toward = $("#hdn_serviceunitto").val();
            mulai = $("#inp_tglmulai").val();
            selesai = $("#inp_tglselesai").val();
            if (!mulai) {
                alert("Tanggal Mulai kosong");
                $("#inp_tglmulai").focus();
            } else if (!selesai) {
                alert("Tanggal Selesai kosong");
                $("#inp_tglselesai").focus();
            } else ajax_getData("ajax_getdata.php?type=reqnmlist&start=" + mulai + "&end=" + selesai + "&from=" + frward + "&to=" + toward + "&mode=popup");
        }
        function toggleRequestDetail(requestNo) {
            if ($("#tr_" + requestNo).css("display") == "none") {
                $("#chevron_" + requestNo).html("<i class='fa fa-chevron-circle-up'></i>");
                if ($("#td_" + requestNo).html() == "-") ajax_getData("ajax_getdata.php?type=reqnmdtl&id=" + requestNo + "&mode=popup&data=<?php echo $_GET["data"] ?>");
            } else $("#chevron_" + requestNo).html("<i class='fa fa-chevron-circle-down'></i>");
            $("#tr_" + requestNo).fadeToggle();
        }
        $(".tanggal").datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            language: 'id',
            todayHighlight: true,
            weekStart: 1
        });
    </script>
</html>
