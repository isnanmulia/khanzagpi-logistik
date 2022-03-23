<?php
    require_once '../config/connect_app.php';
    require_once '../config/session_check.php';
    require_once '../function/access.php';
    require_once '../function/function.php';
    if (isset($_GET["id"]) && isset($_GET["type"])) {
        if ($_GET["type"] == "M") {
            restrictAccess("surat_pemesanan_medis", true);
            mysqli_autocommit($connect_app, FALSE);
            $qApprovePO = "UPDATE surat_pemesanan_medis SET status='Proses Pesan' WHERE no_pemesanan='" . $_GET["id"] . "'";
            $approvePO = mysqli_query($connect_app, $qApprovePO);
            saveToTracker($qApprovePO, $connect_app);
            $qApprovePODtl = "UPDATE detail_surat_pemesanan_medis SET status='Proses Pesan' WHERE no_pemesanan='" . $_GET["id"] . "'";
            $approvePODtl = mysqli_query($connect_app, $qApprovePODtl);
            saveToTracker($qApprovePODtl, $connect_app);
            if ($approvePO && $approvePODtl) {
                mysqli_commit($connect_app);
                if (isset($_GET["approval"])) {
                    $act = '$("<div id=\"alert-' . $_GET["id"] . '\" class=\"alert alert-success\"><i class=\"fa fa-check\"></i> Sukses menyetujui Surat Pemesanan Medis: <strong>'. $_GET["id"] . '</strong>.</div>").hide().appendTo("#div-notif").fadeIn(500);$("#tr_header_' . $_GET["id"] . '").fadeOut();$("#tr_' . $_GET["id"] . '").fadeOut();setTimeout(function(){$("#tr_header_' . $_GET["id"] . '").remove();$("#tr_' . $_GET["id"] . '").remove();if(!$("#tbl_spm tbody").children().length)$("#tbl_spm tbody").append("<tr><td colspan=\'7\' style=\'text-align: center\'>--- Tidak ada data ---</td></tr>");},1000);closeNotif();';
                } else {
                    $act = 'alert("Sukses menyetujui Surat Pemesanan Medis: ' . $_GET["id"] . '");$("#td_status_' . $_GET["id"] . '").html("<i class=\"fa fa-shopping-cart text-purple\" title=\"Proses Pesan\"></i>");$("#td_btn_' . $_GET["id"] . '").html("<a class=\"btn btn-primary btn-sm\" href=\"#\" onclick=\"printPO(&quot;' . $_GET["id"] . '&quot;)\" title=\"Cetak\"><i class=\"fa fa-print\"></i></a>");';
                }
            } else {
                logError("approve_po.php M", "approvePO:" . $approvePO . "|approvePODtl:" . $approvePODtl);
                mysqli_rollback($connect_app);
                $act = 'alert("Gagal menyetujui Surat Pemesanan Medis");';
            }
            mysqli_autocommit($connect_app, TRUE);
            echo '<script>' . $act . '</script>';
        } else if ($_GET["type"] == "N") {
            restrictAccess("surat_pemesanan_non_medis");
            mysqli_autocommit($connect_app, FALSE);
            $qApprovePO = "UPDATE surat_pemesanan_non_medis SET status='Proses Pesan' WHERE no_pemesanan='" . $_GET["id"] . "'";
            $approvePO = mysqli_query($connect_app, $qApprovePO);
            saveToTracker($qApprovePO, $connect_app);
            $qApprovePODtl = "UPDATE detail_surat_pemesanan_non_medis SET status='Proses Pesan' WHERE no_pemesanan='" . $_GET["id"] . "'";
            $approvePODtl = mysqli_query($connect_app, $qApprovePODtl);
            saveToTracker($qApprovePODtl, $connect_app);
            if ($approvePO && $approvePODtl) {
                mysqli_commit($connect_app);
                if (isset($_GET["approval"])) {
                    $act = '$("<div id=\"alert-' . $_GET["id"] . '\" class=\"alert alert-success\"><i class=\"fa fa-check\"></i> Sukses menyetujui Surat Pemesanan Non Medis: <strong>'. $_GET["id"] . '</strong>.</div>").hide().appendTo("#div-notif").fadeIn(500);$("#tr_header_' . $_GET["id"] . '").fadeOut();$("#tr_' . $_GET["id"] . '").fadeOut();setTimeout(function(){$("#tr_header_' . $_GET["id"] . '").remove();$("#tr_' . $_GET["id"] . '").remove();if(!$("#tbl_spn tbody").children().length)$("#tbl_spn tbody").append("<tr><td colspan=\'7\' style=\'text-align: center\'>--- Tidak ada data ---</td></tr>");},1000);closeNotif();';
                } else {
                    $act = 'alert("Sukses menyetujui Surat Pemesanan Non Medis: ' . $_GET["id"] . '");$("#td_status_' . $_GET["id"] . '").html("<i class=\"fa fa-shopping-cart text-purple\" title=\"Proses Pesan\"></i>");$("#td_btn_' . $_GET["id"] . '").html("<a class=\"btn btn-primary btn-sm\" href=\"#\" onclick=\"printPO(&quot;' . $_GET["id"] . '&quot;)\" title=\"Cetak\"><i class=\"fa fa-print\"></i></a>");';
                }
            } else {
                logError("approve_po.php N", "approvePO:" . $approvePO . "|approvePODtl:" . $approvePODtl);
                mysqli_rollback($connect_app);
                $act = 'alert("Gagal menyetujui Surat Pemesanan Non Medis");';
            }
            mysqli_autocommit($connect_app, TRUE);
            echo '<script>' . $act . '</script>';
        }
    }
?>