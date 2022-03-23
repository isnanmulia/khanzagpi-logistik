<?php
    require_once '../template/header.php';
    require_once '../config/connect_app.php';
    require_once '../function/access.php';
    restrictAccess("obat");
    $qGetMedLog = "SELECT kode_brng, nama_brng, SK.satuan AS satuan_kecil, SB.satuan AS satuan_besar, isi FROM databarang B INNER JOIN kodesatuan SK ON SK.kode_sat=B.kode_sat INNER JOIN kodesatuan SB ON B.kode_satbesar=SB.kode_sat WHERE B.status='1' AND kode_brng NOT LIKE '%JIT%' ORDER BY kode_brng";
    $getMedLog = mysqli_query($connect_app, $qGetMedLog);
    $lstMedLog = "";
    while ($data = mysqli_fetch_assoc($getMedLog)) {
        $lstMedLog .= "<tr><td></td><td>" . $data["kode_brng"] . "</td><td>" . $data["nama_brng"] . "</td><td>" . $data["satuan_kecil"] . "</td><td>" . $data["satuan_besar"] . "</td><td>" . $data["isi"] . "</td><td><a href='add_price_history_m.php?id=" . $data["kode_brng"] . "' title='Tambah' class='btn btn-primary btn-sm'><i class='fa fa-plus'></i></a> <a href='#' title='Cek Riwayat' class='btn btn-primary btn-sm' onClick='checkHistory(\"" . $data["kode_brng"] . "\")'><i class='fa fa-history'></i></a></td></tr>";
    }
    if (!strlen($lstMedLog)) $lstMedlog = "<tr><td colspan='7' style='text-align: center'>--- Tidak ada data ---</td></tr>";
    $lstMedLog = "<thead><tr><th>No</th><th>Kode</th><th>Nama Barang</th><th>Satuan Kecil</th><th>Satuan Besar</th><th>Isi</th><th>Aksi</th></tr></thead><tbody>" . $lstMedLog . "</tbody>";
?>

<table id="tbl_medlog" name="tbl_medlog" class="table table-hover">
    <?php echo $lstMedLog ?>
</table>

<script>
    function checkHistory(id) {
        window.open("check_price_history.php?id=" + id + "&type=M", "_blank", "width=850, height=400");
    }
    setTimeout(function() {
        $(function() {
            var t = $("#tbl_medlog").DataTable({
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
                t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();
        });
    }, 500);
</script>

<?php require_once '../template/footer.php' ?>