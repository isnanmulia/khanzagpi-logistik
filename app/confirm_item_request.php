<?php
    require_once "../config/connect_app.php";
    session_start();
    if ($_GET["type"] == "N") {
        $qUpdateRequest = "UPDATE permintaan_non_medis SET status='Dikonfirmasi' WHERE no_permintaan='" . $_GET["id"] . "'";
        $updateRequest = mysqli_query($connect_app, $qUpdateRequest);
        if ($updateRequest) $msg = "Sukses";
            else $msg = "Gagal";
        echo '<script>alert("' . $msg . ' mengonfirmasi permintaan"); location.reload();</script>';
    }
?>