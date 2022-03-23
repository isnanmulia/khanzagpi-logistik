<?php
    require_once '../template/header.php';
    require_once '../config/connect_app.php';
    require_once '../function/access.php';
    restrictAccess("surat_pemesanan_non_medis");
    $qGetPO = "SELECT no_pemesanan, tanggal, nama_suplier, P.nama, catatan, SPNM.status, kadaluarsa FROM surat_pemesanan_non_medis SPNM INNER JOIN ipsrssuplier S ON SPNM.kode_suplier=S.kode_suplier INNER JOIN pegawai P ON SPNM.nip=P.nik WHERE tanggal BETWEEN '" . $today . "' AND '" . $today . "' ORDER BY no_pemesanan";
    $getPO = mysqli_query($connect_app, $qGetPO);
    $lstPO = "";
    while ($data = mysqli_fetch_assoc($getPO)) {
        switch ($data["status"]) {
            case "Baru": $status = "<i class='fa fa-plus-square text-blue' title='baru'></i>"; break;
            case "Proses Pesan": $status = "<i class='fa fa-shopping-cart text-purple' title='Proses Pesan'></i>"; break;
            case "Sudah Datang": $status = "<i class='glyphicon glyphicon-saved text-green' title='Sudah Datang'></i>"; break;
            default: $status = ""; break;
        }
        if ($data["kadaluarsa"] == "1")
            $status .= "&nbsp;<i class='fa fa-calendar-times-o text-red' title='Kadaluarsa'></i>";
        $lstPO .= "<tr><td>" . $data["no_pemesanan"] . " <span id='chevron_" . $data["no_pemesanan"] . "' class='chevron' title='Klik untuk menampilkan/menyembunyikan detail pemesanan' onClick='togglePODetail(&quot;" . $data["no_pemesanan"] . "&quot;)'><i class='fa fa-chevron-circle-down'></i></span></td><td>" . $data["tanggal"] . "</td><td>" . $data["nama_suplier"] . "</td><td>" . $data["nama"] . "</td><td>" . $data["catatan"] . "</td><td id='td_status_" . $data["no_pemesanan"] . "'>" . $status . "</td><td id='td_btn_" . $data["no_pemesanan"] . "'>" . ($data["status"] == "Baru" ? ($data["kadaluarsa"] == "0" ? "<a class='btn btn-primary btn-sm' href='edit_purchase_order_nm.php?id=" . $data["no_pemesanan"] . "' title='Ubah'><i class='fa fa-edit'></i></a>" . (showApproval() ? " <a class='btn btn-success btn-sm' href='#' onClick='approvePO(&quot;" . $data["no_pemesanan"] . "&quot;)' title='Setujui'><i class='fa fa-check'></i></a>" : "") : "") : "<a class='btn btn-primary btn-sm' href='#' onClick='printPO(&quot;" . $data["no_pemesanan"] . "&quot;)' title='Cetak'><i class='fa fa-print'></i></a>") . ($data["kadaluarsa"] == "1" ? " <a class='btn btn-sm bg-purple' href='#' onClick='openExpiredPO(&quot;N&quot;, &quot;" . $data["no_pemesanan"] . "&quot;)' title='Buka Akses'><i class='fa fa-undo'></i></a>" : "") . "</td></tr><tr id='tr_" . $data["no_pemesanan"] . "' style='display: none'><td id='td_" . $data["no_pemesanan"] . "' class='td-detail' colspan='7'>-</td></tr>";
    }
    if (!strlen($lstPO)) $lstPO = "<tr><td colspan='7' style='text-align: center'>--- Tidak ada data ---</td></tr>";
    $lstPO = "<thead><tr><th style='width: 120px'>No. Pemesanan</th><th style='width: 85px'>Tanggal</th><th style='width: 200px'>Supplier</th><th style='width: 250px'>Petugas</th><th>Catatan</th><th style='width: 60px'>Status</th><th style='width: 125px'>Aksi</th></tr></thead><tbody>" . $lstPO . "</tbody>";
?>

Supplier&nbsp;&nbsp;
<input type="text" id="inp_supplier" name="inp_supplier" class="form-control inline-input" style="width: 250px" onKeyUp="getSupplierNM()" autocomplete="off"><input type="hidden" id="hdn_supplier" name="hdn_supplier">&nbsp;&nbsp;
Tanggal&nbsp;&nbsp;
<input type="text" id="inp_tglmulai" name="inp_tglmulai" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $today ?>">&nbsp;&nbsp;s.d.&nbsp;&nbsp;<input type="text" id="inp_tglselesai" name="inp_tglselesai" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $today ?>">&nbsp;&nbsp;
<a class="btn btn-primary" href="#" onClick="getPOList()"><i class="fa fa-search"></i> Cari</a>
<a class="btn btn-primary" href="add_purchase_order_nm.php"><i class="fa fa-plus"></i> Tambah</a>
<table id="tbl_purchaseorder" class="table table-hover">
    <?php echo $lstPO ?>
</table>

<script>
    function getPOList() {
        supplier = $("#hdn_supplier").val();
        mulai = $("#inp_tglmulai").val();
        selesai = $("#inp_tglselesai").val();
        if (!mulai) {
            alert("Tanggal Mulai kosong");
            $("#inp_tglmulai").focus();
        } else if (!selesai) {
            alert("Tanggal Selesai kosong");
            $("#inp_tglselesai").focus();
        } else ajax_getData("ajax_getdata.php?type=ponmlist&supplier=" + supplier + "&start=" + mulai + "&end=" + selesai);
    }
    function togglePODetail(PO_no) {
        if ($("#tr_" + PO_no).css("display") == "none") {
            $("#chevron_" + PO_no).html("<i class='fa fa-chevron-circle-up'></i>");
            if ($("#td_" + PO_no).html() == "-") ajax_getData("ajax_getdata.php?type=ponmdetail&id=" + PO_no);
        } else $("#chevron_" + PO_no).html("<i class='fa fa-chevron-circle-down'></i>");
        $("#tr_" + PO_no).fadeToggle();
    }
    function approvePO(PO_no) {
        ajax_getData("approve_po.php?id=" + PO_no + "&type=N");
    }
    function printPO(PO_no) {
        param = btoa("id=" + PO_no);
        window.open("show_report.php?id=purchase_order_nm&param=" + param, "_blank", "width=1000,height=700");
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