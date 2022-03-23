<?php
    require_once '../template/header.php';
    require_once '../config/connect_app.php';
    require_once '../function/access.php';
    restrictAccess("surat_pemesanan_medis,pemesanan_obat");
?>

<style>
    #tbl_listItems tbody tr td:nth-child(5), #tbl_listItems tbody tr td:nth-child(10), #tbl_listItems tbody tr td:nth-child(14) { border-right: 2px solid #AAA; }
    .alternate-hdr { background-color: #AAA; }
</style>

Barang&nbsp;&nbsp;
<input type="text" id="inp_barang_m" name="inp_barang_m" class="form-control inline-input" style="width: 250px" onKeyUp="getItemM()" autocomplete="off"><input type="hidden" id="hdn_barang_m" name="hdn_barang_m">&nbsp;&nbsp;
Supplier&nbsp;&nbsp;
<input type="text" id="inp_supplier" name="inp_supplier" class="form-control inline-input" style="width: 200px" onKeyUp="getSupplier()" autocomplete="off"><input type="hidden" id="hdn_supplier" name="hdn_supplier">&nbsp;&nbsp;
Keyword&nbsp;&nbsp;
<input type="text" id="inp_keyword" name="inp_keyword" class="form-control inline-input" style="width: 150px" autocomplete="off">&nbsp;&nbsp;
Tanggal &nbsp;&nbsp;
<input type="text" id="inp_tglmulaipesan" name="inp_tglmulaipesan" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $today ?>">&nbsp;&nbsp;s.d.&nbsp;&nbsp;<input type="text" id="inp_tglselesaipesan" name="inp_tglselesaipesan" class="form-control inline-input tanggal" autocomplete="off" value="<?php echo $today ?>">&nbsp;&nbsp;<a class="btn btn-primary" href="#" onClick="getPOItemList()"><i class="fa fa-search"></i> Cari</a>
<div id="div-scroll" style="display: none; overflow: scroll; height: 450px; margin-top: 10px; padding: 10px 20px; background-color: #eee">
    <table id="tbl_listItems" class="table table-bordered table-hover">
        <thead>
            <tr><th rowspan="2">Kode</th><th rowspan="2">Nama Barang</th><th rowspan="2" title="Satuan Kecil">SK</th><th rowspan="2" title="Satuan Besar">SB</th><th rowspan="2">Isi</th><th colspan="3">Pengajuan</th><th colspan="4">Pemesanan</th><th colspan="13">Penerimaan</th></tr>
            <tr><th>Tanggal</th><th>No. Pengajuan</th><th>Jumlah</th><th>Tanggal</th><th>No. Pemesanan</th><th>Jumlah</th><th>Supplier</th><th>Tanggal</th><th>No. Penerimaan</th><th>No. Faktur Supplier</th><th>Jumlah</th><th>Harga Per SB</th><th>Subtotal</th><th>Diskon 1 (%)</th><th>Diskon 2 (%)</th><th>Total Diskon</th><th>Total</th><th>No. Batch</th><th>Kadaluarsa</th><th>PPN</th></tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
    function getItemM() {
        term = $("#inp_barang_m").val();
        ajax_getData("ajax_getdata.php?type=poitem&term=" + term);
    }
    function getPOItemList() {
        barang = $("#hdn_barang_m").val();
        supplier = $("#hdn_supplier").val();
        keyword = $("#inp_keyword").val();
        mulai = $("#inp_tglmulaipesan").val();
        selesai = $("#inp_tglselesaipesan").val();
        if (!mulai) {
            alert("Tanggal Mulai kosong");
            $("#inp_tglmulaipesan").focus();
        } else if (!selesai) {
            alert("Tanggal Selesai kosong");
            $("#inp_tglselesaipesan").focus();
        } else ajax_getData("ajax_getdata.php?type=poitemlist&item=" + barang + "&supplier=" + supplier + "&keyword=" + keyword + "&start=" + mulai + "&end=" + selesai);
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