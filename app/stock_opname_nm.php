<?php
    require_once '../template/header.php';
    require_once '../config/connect_app.php';
    require_once '../function/access.php';
    restrictAccess("stok_opname_logistik");
    $qGetSONM = "SELECT tanggal, nm_bangsal, O.kode_brng, nama_brng, satuan, O.stok, O.real, selisih, lebih, keterangan FROM ipsrsopname O INNER JOIN bangsal BS ON O.kd_bangsal=BS.kd_bangsal INNER JOIN ipsrsbarang B ON O.kode_brng=B.kode_brng INNER JOIN kodesatuan S ON B.kode_sat=S.kode_sat WHERE tanggal BETWEEN '" . $today . "' AND '" . $today . "'";
    $getSONM = mysqli_query($connect_app, $qGetSONM);
    $lstSONM = "";
    while ($data = mysqli_fetch_assoc($getSONM)) {
        $lstSONM .= "<tr><td>" . $data["tanggal"] . "</td><td>" . $data["nm_bangsal"] . "</td><td>" . $data["kode_brng"] . "</td><td>" . $data["nama_brng"] . "</td><td>" . number_format($data["stok"], 2, ",", ".") . " " . $data["satuan"] . "</td><td>" . number_format($data["real"], 2, ",", ".") . " " . $data["satuan"] . "</td><td>" . number_format($data["selisih"], 2, ",", ".") . " " . $data["satuan"] . "</td><td>" . number_format($data["lebih"], 2, ",", ".") . " " . $data["satuan"] . "</td><td>" . $data["keterangan"] . "</td></tr>";
    }
    if (!strlen($lstSONM)) $lstSONM = "<tr><td colspan='9' style='text-align: center'>--- Tidak ada data ---</td></tr>";
    $lstSONM = "<thead><tr><th style='width: 85px'>Tanggal</td><th style='width: 160px'>Lokasi</td><th style='width: 100px'>Kode</td><th style='width: 250px'>Nama Barang</td><th style='width: 70px'>Stok</td><th style='width: 70px'>Real</td><th style='width: 70px'>Selisih</td><th style='width: 70px'>Lebih</td><th style='width: 150px'>Keterangan</td></tr></thead><tbody>" . $lstSONM . "</tbody>";
?>

Lokasi&nbsp;&nbsp;
<input type="text" id="inp_serviceunit" name="inp_serviceunit" class="form-control inline-input" style="width: 200px" onKeyUp="getServiceUnit()" autocomplete="off"><input type="hidden" id="hdn_serviceunit" name="hdn_serviceunit">&nbsp;&nbsp;
Tanggal&nbsp;&nbsp;
<input type="text" id="inp_tglmulai" name="inp_tglmulai" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $today ?>">&nbsp;&nbsp;s.d.&nbsp;&nbsp;<input type="text" id="inp_tglselesai" name="inp_tglselesai" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $today ?>">&nbsp;&nbsp;
<a class="btn btn-primary" href="#" onClick="getSOList()"><i class="fa fa-search"></i> Cari</a>
<a class="btn btn-primary" href="add_stock_opname_nm.php"><i class="fa fa-plus"></i> Tambah</a>
<table id="tbl_stockopname" class="table table-hover">
    <?php echo $lstSONM ?>
</table>

<script>
    function getSOList() {
        bangsal = $("#hdn_serviceunit").val();
        mulai = $("#inp_tglmulai").val();
        selesai = $("#inp_tglselesai").val();
        if (!mulai) {
            alert("Tanggal Mulai kosong");
            $("#inp_tglmulai").focus();
        } else if (!selesai) {
            alert("Tanggal Selesai kosong");
            $("#inp_tglselesai").focus();
        } else ajax_getData("ajax_getdata.php?type=sonmlist&where=" + bangsal + "&start=" + mulai + "&end=" + selesai);
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