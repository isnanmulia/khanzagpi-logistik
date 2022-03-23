<?php
    require_once '../template/header.php';
    require_once '../config/connect_app.php';
    require_once '../function/access.php';
    restrictAccess("penerimaan_non_medis");
    $qGetReception = "SELECT no_faktur, no_faktur_supplier, nama_suplier, tgl_pesan, tgl_faktur, PT.nama, P.status, catatan FROM ipsrspemesanan P INNER JOIN ipsrssuplier S ON P.kode_suplier=S.kode_suplier INNER JOIN petugas PT ON P.nip=PT.nip WHERE tgl_pesan BETWEEN '" . $today . "' AND '" . $today . "' ORDER BY tgl_pesan, no_faktur";
    $getReception = mysqli_query($connect_app, $qGetReception);
    $lstReception = "";
    while ($data = mysqli_fetch_assoc($getReception)) {
        switch ($data["status"]) {
            case "Belum Dibayar": $status = "<i class='fa fa-star-o text-green' title='Belum Dibayar'></i>"; break;
            case "Belum Lunas": $status = "<i class='fa fa-star-half-o text-green' title='Belum Lunas'></i>"; break;
            case "Sudah Lunas": $status = "<i class='fa fa-star text-green' title='Sudah Lunas'></i>"; break;
            default: $status = ""; break;
        }
        $lstReception .= "<tr><td>" . $data["no_faktur"] . " <span id='chevron_" . $data["no_faktur"] . "' class='chevron' title='Klik untuk menampilkan/menyembunyikan detail penerimaan' onClick='toggleRcpDetail(&quot;" . $data["no_faktur"] . "&quot;)'><i class='fa fa-chevron-circle-down'></i></span></td><td>" . $data["tgl_pesan"] . "</td><td>" . $data["no_faktur_supplier"] . "</td><td>" . $data["nama_suplier"] . "</td><td>" . $data["tgl_faktur"] . "</td><td>" . $data["nama"] . "</td><td>" . $data["catatan"] . "</td><td>" . $status . "</td><td><a class='btn btn-primary btn-sm' title='Ubah' href='edit_po_reception_nm.php?id=" . $data["no_faktur"] . "'><i class='fa fa-edit'></i></a></td></tr><tr id='tr_" . $data["no_faktur"] . "' style='display: none'><td id='td_" . $data["no_faktur"] . "' class='td-detail' colspan='9'>-</td></tr>";
    }
    if (!strlen($lstReception)) $lstReception = "<tr><td colspan='9' style='text-align: center'>--- Tidak ada data ---</td></tr>";
    $lstReception = "<thead><tr><th>No. Penerimaan</th><th>Tanggal Penerimaan</th><th>No. Faktur</th><th>Supplier</th><th>Tanggal Faktur</th><th>Petugas</th><th>Catatan</th><th>Status</th><th>Aksi</th></tr></thead><tbody>" . $lstReception . "</tbody>";
?>

Supplier&nbsp;&nbsp;
<input type="text" id="inp_supplier" name="inp_supplier" class="form-control inline-input" style="width: 250px" onKeyUp="getSupplierNM()" autocomplete="off"><input type="hidden" id="hdn_supplier" name="hdn_supplier">&nbsp;&nbsp;
Tanggal&nbsp;&nbsp;
<input type="text" id="inp_tglmulai" name="inp_tglmulai" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $today ?>">&nbsp;&nbsp;s.d.&nbsp;&nbsp;<input type="text" id="inp_tglselesai" name="inp_tglselesai" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $today ?>">&nbsp;&nbsp;
<a class="btn btn-primary" href="#" onClick="getRcpList()"><i class="fa fa-search"></i> Cari</a>
<a class="btn btn-primary" href="add_po_reception_nm.php"><i class="fa fa-plus"></i> Tambah</a>
<table id="tbl_reception" class="table table-hover">
    <?php echo $lstReception ?>
</table>

<script>
    function getRcpList() {
        supplier = $("#hdn_supplier").val();
        mulai = $("#inp_tglmulai").val();
        selesai = $("#inp_tglselesai").val();
        if (!mulai) {
            alert("Tanggal Mulai kosong");
            $("#inp_tglmulai").focus();
        } else if (!selesai) {
            alert("Tanggal Selesai kosong");
            $("#inp_tglselesai").focus();
        } else ajax_getData("ajax_getdata.php?type=rcpnmlist&supplier=" + supplier + "&start=" + mulai + "&end=" + selesai);
    }
    function toggleRcpDetail(rcpNo) {
        if ($("#tr_" + rcpNo).css("display") == "none") {
            $("#chevron_" + rcpNo).html("<i class='fa fa-chevron-circle-up'></i>");
            if ($("#td_" + rcpNo).html() == "-") ajax_getData("ajax_getdata.php?type=rcpnmdetail&id=" + rcpNo);
        } else $("#chevron_" + rcpNo).html("<i class='fa fa-chevron-circle-down'></i>");
        $("#tr_" + rcpNo).fadeToggle();
    }
    setTimeout(function() {
        $(".tanggal").datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            language: 'id',
            todayHighlight: true,
            weekStart: 1
        });
    }, 500);
</script>

<?php require_once '../template/footer.php' ?>