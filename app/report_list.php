<?php
    require_once '../template/header.php';
    require_once '../config/connect_app.php';
    require_once '../function/access.php';
    $tanggal1 = date("Y-m") . "-01";
    $i = 0;
    $reports = array(
        "logmed" => array(
            "namaModul" => "Logistik Medis",
            "isi" => array(
                "masterObat" => array(
                    "namaItem" => "Master Obat, Alkes, BHP",
                    "akses" => "obat",
                    "file" => "master_item_m",
                    "format" => "xls"
                ),
                "SOmedis" => array(
                    "namaItem" => "Stok Opname Obat & BHP",
                    "akses" => "stok_opname_obat",
                    "file" => "stock_opname_m",
                    "format" => "xls"
                ),
                "mutasiLogmed" => array(
                    "namaItem" => "Mutasi Obat & BHP",
                    "akses" => "mutasi_barang",
                    "file" => "item_movement_m",
                    "format" => "xls"
                ),
                "pengajuanLogmed" => array (
                    "namaItem" => "Pengajuan Obat & BHP",
                    "akses" => "pengajuan_barang_medis",
                    "file" => "purchase_request_m",
                    "format" => "xls"
                ),
                "penerimaanLogmed" => array(
                    "namaItem" => "Penerimaan Obat & BHP",
                    "akses" => "pemesanan_obat",
                    "file" => "item_reception_m",
                    "format" => "xls"
                ),
                "sisaStokObat" => array(
                    "namaItem" => "Sisa Stok Obat & BHP",
                    "akses" => "sisa_stok",
                    "file" => "remaining_stock_m",
                    "format" => "xls"
                ),
                "untungBeriObat" => array(
                    "namaItem" => "Keuntungan Pemberian Obat & BHP",
                    "akses" => "keuntungan_beri_obat",
                    "file" => "profit_drug_administration",
                    "format" => "xls"
                ),
                "untungJualObat" => array(
                    "namaItem" => "Keuntungan Penjualan Obat & BHP",
                    "akses" => "keuntungan_penjualan",
                    "file" => "profit_drug_sales",
                    "format" => "xls"
                ),
                "returObat" => array(
                    "namaItem" => "Retur Obat Pasien",
                    "akses" => "retur_dari_pembeli",
                    "file" => "patient_drug_return",
                    "format" => "xls"
                ),
            ),
        ),
        "lognmed" => array(
            "namaModul" => "Logistik Non Medis",
            "isi" => array(
                "masterBarangNmed" => array(
                    "namaItem" => "Master Barang Non Medis",
                    "akses" => "ipsrs_barang",
                    "file" => "master_item_nm",
                    "format" => "xls"
                ),
                "mutasiLogNmed" => array(
                    "namaItem" => "Mutasi Barang Non Medis",
                    "akses" => "rekap_permintaan_non_medis",
                    "file" => "item_movement_nm",
                    "format" => "xls"
                ),
                "pengajuanLogNmed" => array (
                    "namaItem" => "Pengajuan Barang Non Medis",
                    "akses" => "pengajuan_barang_nonmedis",
                    "file" => "purchase_request_nm",
                    "format" => "xls"
                ),
                "penerimaanLogNmed" => array(
                    "namaItem" => "Penerimaan Barang Non Medis",
                    "akses" => "penerimaan_non_medis",
                    "file" => "item_reception_nm",
                    "format" => "xls"
                ),
                "sisaStokLogNMed" => array(
                    "namaItem" => "Sisa Stok Barang Non Medis",
                    "akses" => "rekap_permintaan_non_medis",
                    "file" => "remaining_stock_nm",
                    "format" => "xls"
                ),
            ),
        ), 
    );
?>

Tanggal Transaksi&nbsp;&nbsp;
<input type="text" id="inp_tglmulai" name="inp_tglmulai" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $tanggal1 ?>">&nbsp;&nbsp;s.d.&nbsp;&nbsp;<input type="text" id="inp_tglselesai" name="inp_tglselesai" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $today ?>">&nbsp;&nbsp;
<table id="tbl_reportlist" class="table table-hover">
    <thead>
        <tr>
            <th style="min-width: 40px">No</th>
            <th>Modul</th>
            <th>Nama Laporan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($reports as $r) {
            foreach($r["isi"] as $ri) {
                if (showMenuByAccess($ri["akses"])) {
                    $opts = "";
                    if (strpos($ri["format"], "xls")>-1) {
                        $opts .= "<a class='btn btn-sm btn-primary' title='Ekspor menjadi file Excel' href='#' onClick='exportExcel(\"" . $ri["file"] . "\")'><i class='fa fa-file-excel-o'></i></a> ";
                    }
                    echo "<tr><td>" . ++$i . "</td><td>" . $r["namaModul"] . "</td><td>" . $ri["namaItem"] . "</td><td>" . $opts . "</td></tr>";
                }
            }
        } ?>
    </tbody>
</table>

<script>
    function exportExcel(filename) {
        mulai = $("#inp_tglmulai").val();
        selesai = $("#inp_tglselesai").val();
        if (!mulai) {
            alert("Tanggal Mulai kosong");
            $("#inp_tglmulai").focus();
        } else if (!selesai) {
            alert("Tanggal Selesai kosong");
            $("#inp_tglselesai").focus();
        } else {
            param = btoa("start=" + mulai + "&end=" + selesai);
            window.open("export_excel.php?id=" + filename + "&param=" + param);
        }
    }
    setTimeout(function() {
        $(".tanggal").datepicker({
            autoclose: "true",
            format: "yyyy-mm-dd",
            language: "id",
            todayHighlight: "true",
            weekStart: 1
        });
        $(function() {
            var t = $("#tbl_reportlist").DataTable({
                'language'      : {
                    'url'       : '//<?php echo $_SERVER["SERVER_NAME"] . "/" . $URI[1] ?>/plugins/datatables/Indonesian.json'
                },
                'paging'        : true,
                'lengthChange'  : false,
                'searching'     : true,
                'ordering'      : false,
                'autoWidth'     : false,
                'order'         : [],
            });
            t.on( 'order.dt search.dt', function () {
                t.column(0, {search:'applied', order:'applied'}).nodes().each(
                function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();
        });
    }, 500);
</script>

<?php require_once '../template/footer.php' ?>