<?php
    require_once '../template/header.php';
    require_once '../config/connect_app.php';
    require_once '../function/access.php';
    restrictAccess("pengajuan_barang_nonmedis");
    $qGetPR = "SELECT no_pengajuan, P.nama, tanggal, status, keterangan FROM pengajuan_barang_nonmedis PBN INNER JOIN pegawai P ON PBN.nip=P.nik WHERE tanggal BETWEEN '" . $today . "' AND '" . $today . "' ORDER BY no_pengajuan DESC";
    $getPR = mysqli_query($connect_app, $qGetPR);
    $lstPR = "";
    while ($data = mysqli_fetch_assoc($getPR)) {
        switch ($data["status"]) {
            case "Proses Pengajuan": $status = "<i class='fa fa-list text-blue' title='Proses Pengajuan'></i>"; break;
            case "Pengajuan": $status = "<i class='fa fa-angle-double-right text-blue' title='Pengajuan'></i>"; break;
            case "Disetujui": $status = "<i class='fa fa-check text-green' title='Proses Pengajuan'></i>"; break;
            case "Ditolak": $status = "<i class='fa fa-times text-red' title='Ditolak'></i>"; break;
        }
        $lstPR .= "<tr><td>" . $data["no_pengajuan"] . " <span id='chevron_" . $data["no_pengajuan"] . "' class='chevron' title='Klik untuk menampilkan/menyembunyikan detail pengajuan' onClick='togglePRDetail(&quot;" . $data["no_pengajuan"] . "&quot;)'><i class='fa fa-chevron-circle-down'></i></span></td><td>" . $data["tanggal"] . "</td><td>" . $data["nama"] . "</td><td>" . $data["keterangan"] . "</td><td>" . $status . "</td></tr><tr id='tr_" . $data["no_pengajuan"] . "' style='display: none'><td id='td_" . $data["no_pengajuan"] . "' class='td-detail' colspan='5'>-</td></tr>";
    }
    if (!strlen($lstPR)) $lstPR = "<tr><td colspan='5' style='text-align: center'>--- Tidak ada data ---</td></tr>";
    $lstPR = "<thead><tr><th style='width: 150px'>No. Pengajuan</th><th style='width: 100px'>Tanggal</th><th style='width: 250px'>Yang Mengajukan</th><th>Catatan</th><th style='width: 60px'>Status</th></tr></thead><tbody>" . $lstPR . "</tbody>";
?>

Tanggal&nbsp;&nbsp;
<input type="text" id="inp_tglmulai" name="inp_tglmulai" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $today ?>">&nbsp;&nbsp;s.d.&nbsp;&nbsp;<input type="text" id="inp_tglselesai" name="inp_tglselesai" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $today ?>">
<a class="btn btn-primary" href="#" onClick="getPRList()"><i class="fa fa-search"></i> Cari</a>
<a class="btn btn-primary" href="add_purchase_request_nm.php"><i class="fa fa-plus"></i> Tambah</a>
<table id="tbl_purchaserequest" class="table table-hover">
    <?php echo $lstPR ?>
</table>

<script>
    function getPRList() {
        mulai = $("#inp_tglmulai").val();
        selesai = $("#inp_tglselesai").val();
        if (!mulai) {
            alert("Tanggal Mulai kosong");
            $("#inp_tglmulai").focus();
        } else if (!selesai) {
            alert("Tanggal Selesai kosong");
            $("#inp_tglselesai").focus();
        } else ajax_getData("ajax_getdata.php?type=prnmlist&start=" + mulai + "&end=" + selesai);
    }
    function togglePRDetail(PR_no) {
        if ($("#tr_" + PR_no).css("display") == "none") {
            $("#chevron_" + PR_no).html("<i class='fa fa-chevron-circle-up'></i>");
            if ($("#td_" + PR_no).html() == "-") ajax_getData("ajax_getdata.php?type=prnmdetail&id=" + PR_no);
        } else $("#chevron_" + PR_no).html("<i class='fa fa-chevron-circle-down'></i>");
        $("#tr_" + PR_no).fadeToggle();
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