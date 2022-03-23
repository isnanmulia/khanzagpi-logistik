<?php
    require_once '../config/connect_app.php';
    require_once '../config/session_check.php';
    require_once '../function/access.php';
    restrictAccess("surat_pemesanan_medis");
    if (isset($_POST["form_submit"])) {
        $barang = explode(",", $_POST["hdn_itemsToPO"]);
        $qGetSelPRItems = "SELECT * FROM (SELECT Concat(B.kode_brng, '_', no_pengajuan) AS kode, no_pengajuan, B.kode_brng, nama_brng, SK.kode_sat, SK.satuan AS satuan_kecil, kode_satbesar, SB.satuan AS satuan_besar, isi, dasar*isi AS harga_beli, DPBM.jumlah_disetujui, IfNull(Sum(stok), 0) AS stok FROM databarang B INNER JOIN kodesatuan SK ON B.kode_sat=SK.kode_sat INNER JOIN kodesatuan SB ON B.kode_satbesar=SB.kode_sat INNER JOIN detail_pengajuan_barang_medis DPBM ON B.kode_brng=DPBM.kode_brng LEFT JOIN gudangbarang G ON B.kode_brng=G.kode_brng GROUP BY B.kode_brng, kode) AS t1 WHERE kode IN ('" . implode("','", $barang) . "') ORDER BY kode_brng, no_pengajuan";
        $getSelPRItems = mysqli_query($connect_app, $qGetSelPRItems);
        $selPRItems = ""; $i = 0; $total = 0;
        while ($data = mysqli_fetch_assoc($getSelPRItems)) {
            // $jml_pesan = ceil($data["jumlah"]/$data["faktor_konversi"]);
            // $h_pesan = $data["h_beli_satbesar"] * $jml_pesan;
            $h_pesan = $data["harga_beli"] * $data["jumlah_disetujui"];
            $total += $h_pesan;
            // $selPRItems .= "<tr id='tr_" . $data["kode"] . "'><td>" . $data["kode_brng"] . "</td><td>" . $data["nama_brng"] . "</td><td class='num-input'>" . $data["jumlah"] . "</td><td>" . $data["satuan_kecil"] . "<input type='hidden' id='hdn_sk_" . $data["kode"] . "' name='hdn_sk_" . $data["kode"] . "' value='" . $data["kode_sat"] . "'></td><td class='num-input'>" . $data["faktor_konversi"] . "<input type='hidden' id='hdn_fk_" . $data["kode"] . "' name='hdn_fk_" . $data["kode"] . "' value='" . $data["faktor_konversi"] . "'></td><td><input type='text' id='inp_jml_" . $data["kode"] . "' name='inp_jml_" . $data["kode"] . "' class='form-control num-input' value='" . $jml_pesan . "' onKeyUp='removeNonNumeric(&quot;inp_jml_" . $data["kode"] . "&quot;); recalculatePO()' maxlength='4' autocomplete='off'></td><td>" . $data["satuan_besar"] . "<input type='hidden' id='hdn_sb_" . $data["kode"] . "' name='hdn_sb_" . $data["kode"] . "' value='" . $data["kode_sat_besar"] . "'></td><td class='num-input'><input type='text' id='inp_hpesan_" . $data["kode"] . "' name='inp_hpesan_" . $data["kode"] . "' class='form-control num-input' style='width: 100px' value='" . number_format($data["h_beli_satbesar"], 0, ",", ".") . "' onKeyUp='removeNonNumeric(&quot;inp_hpesan_" . $data["kode"] . "&quot;); recalculatePO()' autocomplete='off' maxlength='11'></td><td class='num-input'><span id='span_subtotal_" . $data["kode"] . "' name='span_subtotal_" . $data["kode"] . "'>" . number_format($h_pesan, 0, ",", ".") . "</span><input type='hidden' id='hdn_subtotal_" . $data["kode"] . "' name='hdn_subtotal_" . $data["kode"] . "' value='" . $h_pesan . "'></td><td><input type='text' id='inp_disc1_" . $data["kode"] . "' name='inp_disc1_" . $data["kode"] . "' class='form-control num-input' value='0' maxlength='2' onKeyUp='removeNonNumeric(&quot;inp_disc1_" . $data["kode"] . "&quot;); recalculatePO()' autocomplete='off'></td><td><input type='text' id='inp_disc2_" . $data["kode"] . "' name='inp_disc2_" . $data["kode"] . "' class='form-control num-input' value='0' maxlength='2' onKeyUp='removeNonNumeric(&quot;inp_disc2_" . $data["kode"] . "&quot;); recalculatePO()' autocomplete='off'></td><td class='num-input'><span id='span_ndisc_" . $data["kode"] . "' name='span_ndisc_" . $data["kode"] . "'>0</span><input type='hidden' id='hdn_ndisc_" . $data["kode"] . "' name='hdn_ndisc_" . $data["kode"] . "'></td><td class='num-input td-total'><span id='span_total_" . $data["kode"] . "' name='span_total_" . $data["kode"] . "'>" . number_format($h_pesan, 0, ",", ".") . "</span><input type='hidden' id='hdn_total_" . $data["kode"] . "' name='hdn_total_" . $data["kode"] . "' value='" . $h_pesan . "'></td><td class='num-input'><a class='a-text' onClick='getStockDtl(&quot;" . $data["kode_brng"] . "&quot;, &quot;M&quot;)'>" . number_format($data["stok"], 0, ",", ".") . "</a></td><td>" . $data["no_pengajuan"] . "<input type='hidden' id='hdn_PR_" . $data["kode"] . "' name='hdn_PR_" . $data["kode"] . "' value='" . $data["no_pengajuan"] . "'></td><td><a class='btn btn-danger btn-sm' title='Hapus' href='#' onClick='if (confirm(&quot;Apakah Anda yakin ingin menghapus data ini?&quot;)) removeItem(&quot;" . $data["kode"] . "&quot;)'><i class='fa fa-trash'></i></a></td></tr>";
            $selPRItems .= "<tr id='tr_" . $data["kode"] . "'><td>" . $data["kode_brng"] . "</td><td>" . addslashes($data["nama_brng"]) . "</td><td>" . $data["satuan_kecil"] . "<input type='hidden' id='hdn_sk_" . $data["kode"] . "' name='hdn_sk_" . $data["kode"] . "' value='" . $data["kode_sat"] . "'></td><td>" . $data["satuan_besar"] . "<input type='hidden' id='hdn_sb_" . $data["kode"] . "' name='hdn_sb_" . $data["kode"] . "' value='" . $data["kode_satbesar"] . "'></td><td class='num-input'>" . $data["isi"] . "<input type='hidden' id='hdn_isi_" . $data["kode"] . "' name='hdn_fk_" . $data["kode"] . "' value='" . $data["isi"] . "'></td><td class='num-input'>" . $data["jumlah_disetujui"] . " " . $data["satuan_besar"] . "</td><td style='width: 60px'><input type='text' id='inp_jml_" . $data["kode"] . "' name='inp_jml_" . $data["kode"] . "' class='form-control num-input' value='" . $data["jumlah_disetujui"] . "' style='width: 60px' onKeyUp='removeNonNumeric(&quot;inp_jml_" . $data["kode"] . "&quot;); recalculatePO()' maxlength='4' autocomplete='off'></td><td style='width: 100px'>" . $data["satuan_besar"] . "</td><td class='num-input'><input type='text' id='inp_hpesan_" . $data["kode"] . "' name='inp_hpesan_" . $data["kode"] . "' class='form-control num-input' style='width: 100px' value='" . number_format($data["harga_beli"], 0, ",", ".") . "' onKeyUp='removeNonNumeric(&quot;inp_hpesan_" . $data["kode"] . "&quot;); recalculatePO()' autocomplete='off' maxlength='11'></td><td class='num-input'><span id='span_subtotal_" . $data["kode"] . "' name='span_subtotal_" . $data["kode"] . "'>" . number_format($h_pesan, 0, ",", ".") . "</span><input type='hidden' id='hdn_subtotal_" . $data["kode"] . "' name='hdn_subtotal_" . $data["kode"] . "' value='" . $h_pesan . "'></td><td><input type='text' id='inp_disc1_" . $data["kode"] . "' name='inp_disc1_" . $data["kode"] . "' class='form-control num-input' value='0' maxlength='5' onKeyUp='removeNonNumeric(&quot;inp_disc1_" . $data["kode"] . "&quot;); recalculatePO()' autocomplete='off'></td><td><input type='text' id='inp_disc2_" . $data["kode"] . "' name='inp_disc2_" . $data["kode"] . "' class='form-control num-input' value='0' maxlength='5' onKeyUp='removeNonNumeric(&quot;inp_disc2_" . $data["kode"] . "&quot;); recalculatePO()' autocomplete='off'></td><td class='num-input'><span id='span_ndisc_" . $data["kode"] . "' name='span_ndisc_" . $data["kode"] . "'>0</span><input type='hidden' id='hdn_ndisc_" . $data["kode"] . "' name='hdn_ndisc_" . $data["kode"] . "'></td><td class='num-input td-total'><span id='span_total_" . $data["kode"] . "' name='span_total_" . $data["kode"] . "'>" . number_format($h_pesan, 0, ",", ".") . "</span><input type='hidden' id='hdn_total_" . $data["kode"] . "' name='hdn_total_" . $data["kode"] . "' value='" . $h_pesan . "'></td><td class='num-input'>" . number_format($data["stok"], 0, ",", ".") . " " . $data["satuan_kecil"] . " <a class='btn btn-primary btn-sm' onClick='getStockDtl(&quot;" . $data["kode_brng"] . "&quot;, &quot;M&quot;)' title='Klik untuk menampilkan detail stok barang per bangsal'><i class='fa fa-eye'></i></a></td><td>" . $data["no_pengajuan"] . "<input type='hidden' id='hdn_PR_" . $data["kode"] . "' name='hdn_PR_" . $data["kode"] . "' value='" . $data["no_pengajuan"] . "'></td><td><a class='btn btn-danger btn-sm' title='Hapus' href='#' onClick='if (confirm(&quot;Apakah Anda yakin ingin menghapus data ini?&quot;)) removeItem(&quot;" . $data["kode"] . "&quot;)'><i class='fa fa-trash'></i></a></td></tr>";
            $i++;
        }
        echo '<script>
            window.opener.$("#tbl_listItems tbody").append("' . $selPRItems . '");
            window.opener.appendItems("PO", "' . implode(",", $barang) . '");
            window.opener.appendItems("PR", "' . implode(",", $barang) . '");
            window.opener.recalculatePO();
            window.close();
        </script>';
        exit();
    }
    $qGetPR = "SELECT no_pengajuan, P.nama, tanggal_disetujui, status, keterangan FROM pengajuan_barang_medis PBM INNER JOIN pegawai P ON PBM.nip=P.nik WHERE tanggal_disetujui BETWEEN '" . $today . "' AND '" . $today . "' AND PBM.status='Pengajuan' ORDER BY tanggal_disetujui DESC, no_pengajuan DESC";
    $getPR = mysqli_query($connect_app, $qGetPR);
    $lstPR = "";
    $status = "<i class='fa fa-angle-double-right text-blue' title='Pengajuan'></i>";
    while ($data = mysqli_fetch_assoc($getPR)) {
        $lstPR .= "<tr><td>" . $data["no_pengajuan"] . " <span id='chevron_" . $data["no_pengajuan"] . "' class='chevron' title='Klik untuk menampilkan/menyembunyikan detail pengajuan' onClick='togglePRDetail(&quot;" . $data["no_pengajuan"] . "&quot;)'><i class='fa fa-chevron-circle-down'></i></span></td><td>" . $data["tanggal_disetujui"] . "</td><td>" . $data["nama"] . "</td><td>" . $data["keterangan"] . "</td><td>" . $status . "</td></tr><tr id='tr_" . $data["no_pengajuan"] . "' style='display: none'><td id='td_" . $data["no_pengajuan"] . "' class='td-detail' colspan='5'>-</td></tr>";
    }
    if (!strlen($lstPR)) $lstPR = "<tr><td colspan='5' style='text-align: center'>--- Tidak ada data ---</td></tr>";
    $lstPR = "<thead><tr><th style='width: 150px'>No. Pengajuan</th><th style='width: 100px'>Tgl. Disetujui</th><th style='width: 250px'>Yang Mengajukan</th><th>Catatan</th><th style='width: 60px'>Status</th></tr></thead><tbody>" . $lstPR . "</tbody>";
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Pengajuan Pembelian Obat & BHP yang Belum Diproses | Aplikasi Utilitas Khanza GPI</title>
        <link rel="stylesheet" href="<?php echo $base_url ?>/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/bower_components/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/bower_components/jquery-ui/themes/base/jquery-ui.min.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/plugins/pace/pace.min.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/dist/css/AdminLTE.min.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/styles/style.css">
        <link rel="stylesheet" href="<?php echo $base_url ?>/styles/popup.css">
    </head>
    <body>
        <h4>Pengajuan Pembelian Obat & BHP yang Belum Diproses</h4>
        <form id="frmPurchaseRequest" action="" method="POST">
            Tanggal&nbsp;&nbsp;
            <input type="text" id="inp_tglmulai" name="inp_tglmulai" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $today ?>">&nbsp;&nbsp;s.d.&nbsp;&nbsp;<input type="text" id="inp_tglselesai" name="inp_tglselesai" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $today ?>">
            <a class="btn btn-primary" href="#" onClick="getPRList()"><i class="fa fa-search"></i> Cari</a>
            <div style="float: right">
                <input type="hidden" id="form_submit" name="form_submit" value="1">
                <input type="hidden" id="hdn_itemsToPO" name="hdn_itemsToPO">
                <button type="submit" id="btn_submit" class="btn btn-primary" disabled><i class="fa fa-check-square-o"></i> Pilih</button>
            </div>
            <div class="div-list-popup">
                <table id="tbl_purchaserequest" class="table table-hover">
                    <?php echo $lstPR ?>
                </table>
            </div>
        </form>
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
            itemsToPO();
        }
        function itemsToPO() {
            lstChecked = [];
            $("input:checkbox:checked").each(function() {
                a = $(this).prop("name");
                b = a.split("_");
                lstChecked.push(b[1] + "_" + b[2]);
            });
            $("#hdn_itemsToPO").val(lstChecked.sort().join(","));
        }
        function getPRList() {
            mulai = $("#inp_tglmulai").val();
            selesai = $("#inp_tglselesai").val();
            if (!mulai) {
                alert("Tanggal Mulai kosong");
                $("#inp_tglmulai").focus();
            } else if (!selesai) {
                alert("Tanggal Selesai kosong");
                $("#inp_tglselesai").focus();
            } else ajax_getData("ajax_getdata.php?type=prlist&start=" + mulai + "&end=" + selesai);
        }
        function togglePRDetail(PR_no) {
            if ($("#tr_" + PR_no).css("display") == "none") {
                $("#chevron_" + PR_no).html("<i class='fa fa-chevron-circle-up'></i>");
                if ($("#td_" + PR_no).html() == "-") ajax_getData("ajax_getdata.php?type=prdetail&id=" + PR_no + "&data=<?php echo $_GET["data"] ?>&select");
            } else $("#chevron_" + PR_no).html("<i class='fa fa-chevron-circle-down'></i>");
            $("#tr_" + PR_no).fadeToggle();
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

