<?php
    require_once '../template/header.php';
    require_once '../config/connect_app.php';
    require_once '../function/access.php';
    restrictAccess("ipsrs_barang");
    $qGetNMedLog = "SELECT kode_brng, nama_brng, SK.satuan AS satuan_kecil, SB.satuan AS satuan_besar, isi, (SELECT COUNT(*) FROM gpi_riwayat_harga_nonmedis RHN WHERE RHN.kode_brng=B.kode_brng) AS x FROM ipsrsbarang B INNER JOIN kodesatuan SK ON B.kode_sat=SK.kode_sat INNER JOIN kodesatuan SB ON B.kode_satbesar=SB.kode_sat WHERE B.status='1' ORDER BY kode_brng";
    $getNMedLog = mysqli_query($connect_app, $qGetNMedLog);
    $lstNMedLog = "";
    while ($data = mysqli_fetch_assoc($getNMedLog)) {
        $lstNMedLog .= "<tr><td></td><td>" . $data["kode_brng"] . "</td><td>" . $data["nama_brng"] . "</td><td>" . $data["satuan_kecil"] . "</td><td>" . $data["satuan_besar"] . "</td><td>" . $data["isi"] . "</td><td>" . ($data["x"] ? "" : "<a href='set_large_unit_nm.php?id=" . $data["kode_brng"] . "' title='Ubah' class='btn btn-primary btn-sm'><i class='fa fa-edit'></i></a>") . "</td></tr>";
    }
    if (!strlen($lstNMedLog)) $lstNMedLog = "<tr><td colspan='7' style='text-align: center'>--- Tidak ada data ---</td></tr>";
    $lstNMedLog = "<thead><th>No</th><th>Kode Barang</th><th>Nama Barang</th><th>Satuan Kecil</th><th>Satuan Besar</th><th>Isi</th><th>Aksi</th></thead><tbody>" . $lstNMedLog . "</tbody>";
?>

<table id="tbl_nmedlog" name="nmedlog" class="table table-hover">
    <?php echo $lstNMedLog ?>
</table>

<script>
    setTimeout(function () {
        $(function () {
            var t = $("#tbl_nmedlog").DataTable({
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