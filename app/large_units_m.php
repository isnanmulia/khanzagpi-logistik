<?php
    require_once '../template/header.php';
    require_once '../config/connect_app.php';
    require_once '../function/access.php';
    restrictAccess("LOGMED");
    $qGetMedLog = "SELECT kode_brng, nama_brng, SK.kode_sat, SK.satuan AS satuan_kecil, kode_satbesar, SB.satuan AS satuan_besar, isi, (SELECT COUNT(*) FROM gpi_riwayat_harga_obat RHO WHERE RHO.kode_brng=B.kode_brng) AS x FROM databarang B INNER JOIN kodesatuan SK ON B.kode_sat=SK.kode_sat INNER JOIN kodesatuan SB ON B.kode_satbesar=SB.kode_sat WHERE B.status='1' AND kode_brng NOT LIKE '%JIT%' ORDER BY kode_brng";
    $getMedLog = mysqli_query($connect_app, $qGetMedLog);
    $lstMedLog = "";
    while ($medLog = mysqli_fetch_assoc($getMedLog)) {
        $lstMedLog .= "<tr><td></td><td>" . $medLog["kode_brng"] . "</td><td>" . $medLog["nama_brng"] . "</td><td>" . $medLog["satuan_kecil"] . "</td><td>" . $medLog["satuan_besar"] . "</td><td>" . $medLog["isi"] . "</td><td>" . ($medLog["x"] ? "" : "<a href='set_large_unit_m.php?id=" . $medLog["kode_brng"] . "' title='Ubah' class='btn btn-primary btn-sm'><i class='fa fa-edit'></i></a>") . "</td></tr>";
    }
    if (!strlen($lstMedLog)) $lstMedLog = "<tr><td colspan='7' style='text-align: center'>--- Tidak ada data ---</td></tr>";
    $lstMedLog = "<thead><tr><th>No</th><th>Kode Barang</th><th>Nama Barang</th><th>Satuan Kecil</th><th>Satuan Besar</th><th>Faktor Konversi</th><th>Aksi</th></tr></thead><tbody>" . $lstMedLog . "</tbody>";
?>

<style> .popover { width: 220px; } </style>

<table id="tbl_medlog" name="tbl_medlog" class="table table-hover">
    <?php echo $lstMedLog ?>
</table>

<script>
    setTimeout(function () {
        $(function () {
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
        $('[data-toggle="popover"]').popover(); 
    }, 500);
</script>

<?php require_once '../template/footer.php' ?>