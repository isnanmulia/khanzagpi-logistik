<?php
    require_once '../template/header.php';
    require_once '../config/connect_app.php';
    restrictAccess("surat_pemesanan_medis,surat_pemesanan_non_medis");
    if (!showApproval()) denyAccess();
    $qGetSPM = "SELECT no_pemesanan, tanggal, nama_suplier, P.nama, catatan FROM surat_pemesanan_medis SPM INNER JOIN datasuplier S ON SPM.kode_suplier=S.kode_suplier INNER JOIN pegawai P ON SPM.nip=P.nik WHERE tanggal BETWEEN '" . $today . "' AND '" . $today . "' AND SPM.status='Baru' ORDER BY no_pemesanan";
    $getSPM = mysqli_query($connect_app, $qGetSPM);
    $lstSPM = "";
    while ($data = mysqli_fetch_assoc($getSPM)) {
        $lstSPM .= "<tr id='tr_header_" . $data["no_pemesanan"] . "'><td>" . $data["no_pemesanan"] . " <span id='chevron_" . $data["no_pemesanan"] . "' class='chevron' title='Klik untuk menampilkan/menyembunyikan detail pemesanan' onClick='togglePODetail(&quot;" . $data["no_pemesanan"] . "&quot;, &quot;M&quot;)'><i class='fa fa-chevron-circle-down'></i></span></td><td>" . $data["tanggal"] . "</td><td>" . $data["nama_suplier"] . "</td><td>" . $data["nama"] . "</td><td>" . $data["catatan"] . "</td><td><i class='fa fa-plus-square text-blue' title='Baru'></td><td><a class='btn btn-success btn-sm' href='#' onClick='approvePO(&quot;" . $data["no_pemesanan"] . "&quot;, &quot;M&quot;)' title='Setujui'><i class='fa fa-check'></i></a></td></tr><tr id='tr_" . $data["no_pemesanan"] . "' style='display: none'><td id='td_" . $data["no_pemesanan"] . "' class='td-detail' colspan='7'>-</td></tr>";
    }
    if (!strlen($lstSPM)) $lstSPM = "<tr><td colspan='7' style='text-align: center'>--- Tidak ada data ---</td></tr>";
    $lstSPM = "<thead><tr><th style='width: 120px'>No. Pemesanan</th><th style='width: 85px'>Tanggal</th><th style='width: 200px'>Supplier</th><th style='width: 250px'>Petugas</th><th>Catatan</th><th style='width: 60px'>Status</th><th style='width: 125px'>Aksi</th></tr></thead><tbody>" . $lstSPM . "</tbody>";
    $qGetSPN = "SELECT no_pemesanan, tanggal, nama_suplier, P.nama, catatan FROM surat_pemesanan_non_medis SPN INNER JOIN ipsrssuplier S ON SPN.kode_suplier=S.kode_suplier INNER JOIN pegawai P ON SPN.nip=P.nik WHERE tanggal BETWEEN '" . $today . "' AND '" . $today . "' AND SPN.status='Baru' ORDER BY no_pemesanan";
    $getSPN = mysqli_query($connect_app, $qGetSPN);
    $lstSPN = "";
    while ($data = mysqli_fetch_assoc($getSPN)) {
        $lstSPN .= "<tr id='tr_header_" . $data["no_pemesanan"] . "'><td>" . $data["no_pemesanan"] . " <span id='chevron_" . $data["no_pemesanan"] . "' class='chevron' title='Klik untuk menampilkan/menyembunyikan detail pemesanan' onClick='togglePODetail(&quot;" . $data["no_pemesanan"] . "&quot;, &quot;N&quot;)'><i class='fa fa-chevron-circle-down'></i></span></td><td>" . $data["tanggal"] . "</td><td>" . $data["nama_suplier"] . "</td><td>" . $data["nama"] . "</td><td>" . $data["catatan"] . "</td><td><i class='fa fa-plus-square text-blue' title='Baru'></td><td><a class='btn btn-success btn-sm' href='#' onClick='approvePO(&quot;" . $data["no_pemesanan"] . "&quot;, &quot;N&quot;)' title='Setujui'><i class='fa fa-check'></i></a></td></tr><tr id='tr_" . $data["no_pemesanan"] . "' style='display: none'><td id='td_" . $data["no_pemesanan"] . "' class='td-detail' colspan='7'>-</td></tr>";
    }
    if (!strlen($lstSPN)) $lstSPN = "<tr><td colspan='7' style='text-align: center'>--- Tidak ada data ---</td></tr>";
    $lstSPN = "<thead><tr><th style='width: 120px'>No. Pemesanan</th><th style='width: 85px'>Tanggal</th><th style='width: 200px'>Supplier</th><th style='width: 250px'>Petugas</th><th>Catatan</th><th style='width: 60px'>Status</th><th style='width: 125px'>Aksi</th></tr></thead><tbody>" . $lstSPN . "</tbody>";
?>

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_SPM" data-toggle="tab" id="tab-spm"><i class="fa fa-medkit"></i> Surat Pemesanan Medis</a></li>
        <li><a href="#tab_SPN" data-toggle="tab" id="tab-spn"><i class="fa fa-cubes"></i> Surat Pemesanan Non Medis</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_SPM">
            Tanggal&nbsp;&nbsp;
            <input type="text" id="inp_tglmulaispm" name="inp_tglmulaispm" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $today ?>">&nbsp;&nbsp;s.d.&nbsp;&nbsp;<input type="text" id="inp_tglselesaispm" name="inp_tglselesaispm" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $today ?>">&nbsp;&nbsp;
            <a class="btn btn-primary" href="#" onClick="getPOList('M')"><i class="fa fa-search"></i> Cari</a>
            <table id="tbl_spm" class="table table-hover">
                <?php echo $lstSPM ?>
            </table>
        </div>
        <div class="tab-pane" id="tab_SPN">
            Tanggal&nbsp;&nbsp;
            <input type="text" id="inp_tglmulaispn" name="inp_tglmulaispn" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $today ?>">&nbsp;&nbsp;s.d.&nbsp;&nbsp;<input type="text" id="inp_tglselesaispn" name="inp_tglselesaispn" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $today ?>">&nbsp;&nbsp;
            <a class="btn btn-primary" href="#" onClick="getPOList('N')"><i class="fa fa-search"></i> Cari</a>
            <table id="tbl_spn" class="table table-hover">
                <?php echo $lstSPN ?>
            </table>
        </div>
    </div>
</div>

<script>
    function getPOList(type) {
        switch (type) {
            case "M":
                mulai = $("#inp_tglmulaispm").val();
                selesai = $("#inp_tglselesaispm").val();
                if (!mulai) {
                    alert("Tanggal Mulai kosong");
                    $("#inp_tglmulaispm").focus();
                } else if (!selesai) {
                    alert("Tanggal Selesai kosong");
                    $("#inp_tglselesaispm").focus();
                } else ajax_getData("ajax_getdata.php?type=polist&approval&start=" + mulai + "&end=" + selesai);
                break;
            case "N":
                mulai = $("#inp_tglmulaispn").val();
                selesai = $("#inp_tglselesaispn").val();
                if (!mulai) {
                    alert("Tanggal Mulai kosong");
                    $("#inp_tglmulaispn").focus();
                } else if (!selesai) {
                    alert("Tanggal Selesai kosong");
                    $("#inp_tglselesaispn").focus();
                } else ajax_getData("ajax_getdata.php?type=ponmlist&approval&start=" + mulai + "&end=" + selesai);
                break;
        }
    }
    function togglePODetail(PO_no, type) {
        if ($("#tr_" + PO_no).css("display") == "none") {
            $("#chevron_" + PO_no).html("<i class='fa fa-chevron-circle-up'></i>");
            if ($("#td_" + PO_no).html() == "-")
                switch (type) {
                    case "M": ajax_getData("ajax_getdata.php?type=podetail&id=" + PO_no); break;
                    case "N": ajax_getData("ajax_getdata.php?type=ponmdetail&id=" + PO_no); break;
                }
                
        } else $("#chevron_" + PO_no).html("<i class='fa fa-chevron-circle-down'></i>");
        $("#tr_" + PO_no).fadeToggle();
    }
    function approvePO(PO_no, type) {
        ajax_getData("approve_po.php?id=" + PO_no + "&type=" + type + "&approval");
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