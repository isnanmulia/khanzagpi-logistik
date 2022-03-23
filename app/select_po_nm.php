<?php
    require_once '../config/connect_app.php';
    require_once '../config/session_check.php';
    require_once '../function/access.php';
    restrictAccess("penerimaan_non_medis");
    $startdate = date("Y-m-d", strtotime("-30 days"));
    if (isset($_POST["form_submit"])) {
        $barang = $_POST["hdn_itemsToRcp"];
        $qGetSelPOItems = "SELECT * FROM (SELECT Concat(B.kode_brng, '_', no_pemesanan) AS kode, no_pemesanan, B.kode_brng, nama_brng, SK.kode_sat, SK.satuan AS satuan_kecil, DSPN.kode_satbesar, SB.satuan As satuan_besar, DSPN.isi, jumlah, h_pesan, subtotal, dis, dis2, besardis, total, IfNull((SELECT Sum(jumlah) FROM ipsrsdetailpesan WHERE kode_brng=B.kode_brng AND no_pemesanan=DSPN.no_pemesanan), 0) AS sdh_diterima FROM detail_surat_pemesanan_non_medis DSPN INNER JOIN ipsrsbarang B ON DSPN.kode_brng=B.kode_brng INNER JOIN kodesatuan SK ON DSPN.kode_sat=SK.kode_sat INNER JOIN kodesatuan SB ON DSPN.kode_satbesar=SB.kode_sat) AS t1 WHERE kode IN ('" . str_replace(",", "','", $barang) . "') AND jumlah>0 ORDER BY kode_brng, no_pemesanan";
        $getSelPOItems = mysqli_query($connect_app, $qGetSelPOItems);
        $selPOItems = "";
        while ($data = mysqli_fetch_assoc($getSelPOItems)) {
            $sisa_brng = $data["jumlah"] - $data["sdh_diterima"];
            $selPOItems .= "<tr id='tr_" . $data["kode"] . "'><td>" . $data["kode_brng"] . "</td><td>" . str_replace('"', '\"', $data["nama_brng"]) . "</td><td>" . $data["satuan_kecil"] . "<input type='hidden' id='hdn_sk_" . $data["kode"] . "' name='hdn_sk_" . $data["kode"] . "' value='" . $data["kode_sat"] . "'></td><td>" . $data["satuan_besar"] . "<input type='hidden' id='hdn_sb_" . $data["kode"] . "' name='hdn_sb_" . $data["kode"] . "' value='" . $data["kode_satbesar"] . "'></td><td class='num-input'>" . number_format($data["isi"], 0, ",", ".") . "<input type='hidden' id='hdn_fk_" . $data["kode"] . "' name='hdn_fk_" . $data["kode"] . "' value='" . $data["isi"] . "'></td><td class='num-input'>" . number_format($data["jumlah"], 2, ",", ".") . " " . $data["satuan_besar"] . "<input type='hidden' id='hdn_jml_" . $data["kode"] . "' name='hdn_jml_" . $data["kode"] . "' value='" . $data["jumlah"] . "'></td><td><input type='text' id='inp_hpesan_" . $data["kode"] . "' name='inp_hpesan_" . $data["kode"] . "' class='form-control num-input' style='width: 100px' value='" . number_format($data["h_pesan"], 0, ",", ".") . "' onKeyUp='removeNonNumeric(&quot;inp_hpesan_" . $data["kode"] . "&quot;); recalculateRcp(&quot;N&quot;)' autocomplete='off' maxlength='11'></td><td class='num-input'>" . number_format($data["sdh_diterima"], 2, ",", ".") . " " . $data["satuan_besar"] . "<input type='hidden' id='hdn_sdhditerima_" . $data["kode"] . "' name='hdn_sdhditerima_" . $data["kode"] . "' value='" . $data["sdh_diterima"] . "'></td><td><input type='text' id='inp_diterima_" . $data["kode"] . "' name='inp_diterima_" . $data["kode"] . "' class='form-control num-input' style='width: 60px' value='" . number_format($sisa_brng, 2, ",", ".") . "' onKeyUp='removeNonNumeric(&quot;inp_diterima_" . $data["kode"] . "&quot;); recalculateRcp(&quot;N&quot;)' maxlength='4' autocomplete='off'></td><td style='width: 100px'>" . $data["satuan_besar"] . "</td><td class='num-input'><span id='span_subtotal_" . $data["kode"] . "' name='span_subtotal_" . $data["kode"] . "'>" . number_format($data["subtotal"], 0, ",", ".") . "</span><input type='hidden' id='hdn_subtotal_" . $data["kode"] . "' name='hdn_subtotal_" . $data["kode"] . "' value='" . $data["kode"] . "'></td><td class='num-input'><span id='span_disc1_" . $data["kode"] . "' name='span_disc1_" . $data["kode"] . "'>" . str_replace(".", ",", $data["dis"]) . "</span><input type='hidden' id='hdn_disc1_" . $data["kode"] . "' name='hdn_disc1_" . $data["kode"] . "' value='" . $data["dis"] . "'><td class='num-input'><span id='span_disc2_" . $data["kode"] . "' name='span_disc2_" . $data["kode"] . "'>" . str_replace(".", ",", $data["dis2"]) . "</span><input type='hidden' id='hdn_disc2_" . $data["kode"] . "' name='hdn_disc2_" . $data["kode"] . "' value='" . $data["dis2"] . "'></td><td class='num-input'><span id='span_ndisc_" . $data["kode"] . "' name='span_ndisc_" . $data["kode"] . "'>" . number_format($data["besardis"], 0, ",", ".") . "</span><input type='hidden' id='hdn_ndisc_" . $data["kode"] . "' name='hdn_ndisc_" . $data["kode"] . "' value='" . $data["besardis"] . "'></td><td class='num-input td-total'><span id='span_total_" . $data["kode"] . "' name='span_total_" . $data["kode"] . "'>" . number_format($data["total"], 0, ",", ".") . "</span><input type='hidden' id='hdn_total_" . $data["kode"] . "' name='hdn_total_" . $data["kode"] . "' value='" . $data["total"] . "'></td><td>" . $data["no_pemesanan"] . "</td><td><a class='btn btn-danger btn-sm' title='Hapus' href='#' onClick='if (confirm(&quot;Apakah Anda yakin ingin menghapus data ini?&quot;)) removeItem(&quot;" . $data["kode"] . "&quot;)'><i class='fa fa-trash'></i></a><input type='hidden' id='hdn_editable_" . $data["kode"] . "' name='hdn_editable_" . $data["kode"] . "' value='1'></td></tr>";
        }
        echo '<script>
            window.opener.$("#tbl_listItems tbody").append("' . $selPOItems . '");
            window.opener.appendItems("Rcp", "' . $barang . '");
            window.opener.$("#span_supplier").html("' . $_POST["inp_supplier"] . '");
            window.opener.$("#hdn_supplier").val("' . $_POST["hdn_supplier"] . '");
            window.opener.recalculateRcp("N");
            window.opener.$(".tanggal").datepicker({ autoclose: true, format: \'yyyy-mm-dd\', language: \'id\', todayHighlight: true, weekStart: 1 });
            window.close();
        </script>';
    }
    $lstPO = "<thead><tr><th style='width: 120px'>No. Pemesanan</th><th style='width: 85px'>Tanggal</th><th style='width: 200px'>Supplier</th><th style='width: 250px'>Petugas</th><th>Catatan</th><th style='width: 60px'>Status</th></tr></thead><tbody><tr><td colspan='6' style='text-align: center'>--- Tidak ada data ---</td></tr></tbody>";
    if (isset($_GET["supplier"])) {
        $kdsupplier = $_GET["supplier"];
        $qGetSupplier = "SELECT nama_suplier FROM ipsrssuplier WHERE kode_suplier='" . $kdsupplier . "'";
        $getSupplier = mysqli_query($connect_app, $qGetSupplier);
        $supplier = mysqli_fetch_assoc($getSupplier)["nama_suplier"];
    } else {
        $kdsupplier = "";
        $supplier = "";
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Pemesanan Barang Non Medis yang Belum Selesai | Aplikasi Utilitas Khanza GPI</title>
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
            <h4>Pemesanan Barang Non Medis yang Belum Selesai</h4>
            <form id="frmSelectPO" action="" method="POST">
            Supplier&nbsp;&nbsp;
                <input type="text" id="inp_supplier" name="inp_supplier" class="form-control inline-input" style="width: 250px" onKeyUp="getSupplierNM()" autocomplete="off" value="<?php echo $supplier ?>" <?php if (strlen($kdsupplier)) { ?>disabled<?php } ?>><input type="hidden" id="hdn_supplier" name="hdn_supplier" value="<?php echo $kdsupplier ?>">&nbsp;&nbsp;
                Tanggal&nbsp;&nbsp;
                <input type="text" id="inp_tglmulai" name="inp_tglmulai" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $startdate ?>">&nbsp;&nbsp;s.d.&nbsp;&nbsp;<input type="text" id="inp_tglselesai" name="inp_tglselesai" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $today ?>">
                <a class="btn btn-primary" href="#" onClick="getPOList()"><i class="fa fa-search"></i> Cari</a>
                <div style="float: right">
                    <input type="hidden" id="form_submit" name="form_submit" value="1">
                    <input type="hidden" id="hdn_itemsToRcp" name="hdn_itemsToRcp">
                    <button type="submit" id="btn_submit" class="btn btn-primary" disabled><i class="fa fa-check-square-o"></i> Pilih</button>
                </div>
                <div class="div-list-popup">
                    <table id="tbl_purchaseorder" class="table table-hover">
                        <?php echo $lstPO ?>
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
            itemsToRcp();
        }
        function itemsToRcp() {
            lstChecked = [];
            $("input:checkbox:checked").each(function() {
                a = $(this).prop("name");
                b = a.split("_");
                lstChecked.push(b[1] + "_" + b[2]);
            });
            $("#hdn_itemsToRcp").val(lstChecked.sort().join(","));
        }
        function getPOList() {
            supplier = $("#hdn_supplier").val();
            mulai = $("#inp_tglmulai").val();
            selesai = $("#inp_tglselesai").val();
            if (!supplier) {
                alert("Supplier kosong");
                $("#inp_supplier").focus();
            } else if (!mulai) {
                alert("Tanggal Mulai kosong");
                $("#inp_tglmulai").focus();
            } else if (!selesai) {
                alert("Tanggal Selesai kosong");
                $("#inp_tglselesai").focus();
            } else ajax_getData("ajax_getdata.php?type=ponmlist&start=" + mulai + "&end=" + selesai + "&supplier=" + supplier + "&mode=popup");
        }
        function togglePODetail(PO_no) {
            if ($("#tr_" + PO_no).css("display") == "none") {
                $("#chevron_" + PO_no).html("<i class='fa fa-chevron-circle-up'></i>");
                if ($("#td_" + PO_no).html() == "-") ajax_getData("ajax_getdata.php?type=ponmdetail&id=" + PO_no + "&mode=popup&data=<?php echo $_GET["data"] ?>");
            } else $("#chevron_" + PO_no).html("<i class='fa fa-chevron-circle-down'></i>");
            $("#tr_" + PO_no).fadeToggle();
        }
        $(".tanggal").datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            language: 'id',
            todayHighlight: true,
            weekStart: 1
        });
        if (!$("#hdn_supplier").val()) $("#inp_supplier").focus();
    </script>
</html>