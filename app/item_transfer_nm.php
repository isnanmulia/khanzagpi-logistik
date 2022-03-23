<?php
    require_once '../template/header.php';
    require_once '../config/connect_app.php';
    require_once '../function/access.php';
    restrictAccess("rekap_permintaan_non_medis");
    $qGetTransfer = "SELECT M.kode_brng, nama_brng, satuan, jml, BD.nm_bangsal AS bangsaldari, BK.nm_bangsal AS bangsalke, tanggal, keterangan FROM gpi_mutasibarangipsrs M INNER JOIN ipsrsbarang B ON M.kode_brng=B.kode_brng INNER JOIN bangsal BD ON M.kd_bangsaldari=BD.kd_bangsal INNER JOIN bangsal BK ON M.kd_bangsalke=BK.kd_bangsal INNER JOIN kodesatuan S ON B.kode_sat=S.kode_sat WHERE tanggal BETWEEN '" . $today . " 00:00:00' AND '" . $today . " 23:59:59' ORDER BY tanggal, bangsaldari, M.kode_brng";
    $getTransfer = mysqli_query($connect_app, $qGetTransfer);
    $lstTransfer = "";
    while ($data = mysqli_fetch_assoc($getTransfer)) {
        $lstTransfer .= "<tr><td>" . $data["tanggal"] . "</td><td>" . $data["bangsaldari"] . "</td><td>" . $data["bangsalke"] . "</td><td>" . $data["kode_brng"] . "</td><td>" . $data["nama_brng"] . "</td><td>" . number_format($data["jml"], 2, ",", ".") . " " . $data["satuan"] . "</td><td>" . $data["keterangan"] . "</td></tr>";
    }
    if (!strlen($lstTransfer)) $lstTransfer = "<tr><td colspan='7' style='text-align: center'>--- Tidak ada data ---</td></tr>";
    $lstTransfer = "<thead><th style='width: 132px'>Tanggal</th><th style='width: 160px'>Asal Mutasi</th><th style='width: 160px'>Tujuan Mutasi</th><th style='width: 100px'>Kode</th><th>Nama Barang</th><th style='width: 100px'>Jumlah</th><th style='width: 175px'>Keterangan</th></thead><tbody>" . $lstTransfer . "</tbody>";
?>

Asal Mutasi&nbsp;&nbsp;
<input type="text" id="inp_serviceunitfrom" name="inp_serviceunitfrom" class="form-control inline-input" style="width: 200px" onKeyUp="getServiceUnit('from')" autocomplete="off"><input type="hidden" id="hdn_serviceunitfrom" name="hdn_serviceunitfrom">&nbsp;&nbsp;
Tujuan Mutasi&nbsp;&nbsp;
<input type="text" id="inp_serviceunitto" name="inp_serviceunitto" class="form-control inline-input" style="width: 200px" onKeyUp="getServiceUnit('to')" autocomplete="off"><input type="hidden" id="hdn_serviceunitto" name="hdn_serviceunitto">&nbsp;&nbsp;
Tanggal&nbsp;&nbsp;
<input type="text" id="inp_tglmulai" name="inp_tglmulai" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $today ?>">&nbsp;&nbsp;s.d.&nbsp;&nbsp;<input type="text" id="inp_tglselesai" name="inp_tglselesai" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $today ?>">&nbsp;&nbsp;
<a class="btn btn-primary" href="#" onClick="getTransferList()"><i class="fa fa-search"></i> Cari</a>
<a class="btn btn-primary" href="add_item_transfer_nm.php"><i class="fa fa-plus"></i> Tambah</a>
<table id="tbl_itemtransfer" class="table table-hover">
    <?php echo $lstTransfer ?>
</table>

<script>
    function getTransferList() {
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
        } else ajax_getData("ajax_getdata.php?type=transfernmlist&from=" + dari + "&to=" + ke + "&start=" + mulai + "&end=" + selesai);
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