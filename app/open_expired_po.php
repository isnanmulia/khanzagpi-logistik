<?php
    require_once '../config/connect_app.php';
    require_once '../config/session_check.php';
    require_once '../function/access.php';
    require_once '../function/function.php';
    if (isset($_GET["id"]) && isset($_GET["type"])) {
        if ($_GET["type"] == "M") {
            restrictAccess("surat_pemesanan_medis");
            mysqli_autocommit($connect_app, FALSE);
            $qCheckStatus = "SELECT status FROM surat_pemesanan_medis WHERE no_pemesanan='" . $_GET["id"] . "'";
            $checkStatus = mysqli_query($connect_app, $qCheckStatus);
            $status = mysqli_fetch_assoc($checkStatus)["status"];
            $qReopenPO = "UPDATE surat_pemesanan_medis SET kadaluarsa='0' WHERE no_pemesanan='" . $_GET["id"] . "'";
            $reopenPO = mysqli_query($connect_app, $qReopenPO);
            saveToTracker($qReopenPO, $connect_app);
            if ($reopenPO) {
                mysqli_commit($connect_app);
                if ($status == "Baru") {
                    $btnBaru = '<a class=\"btn btn-primary btn-sm\" href=\"edit_purchase_order_m.php?id=' . $_GET["id"] . '\" title=\"Ubah\"><i class=\"fa fa-edit\"></i></a> <a class=\"btn btn-success btn-sm\" href=\"#\" onclick=\"approvePO(&quot;' . $_GET["id"] . '&quot;, &quot;M&quot;)\" title=\"Setujui\"><i class=\"fa fa-check\"></i></a>';
                } else $btnBaru = "";
                $act = '$("<div id=\"alert-' . $_GET["id"] . '\" class=\"alert alert-success\"><i class=\"fa fa-check\"></i> Sukses membuka akses Surat Pemesanan Medis: <strong>'. $_GET["id"] . '</strong>.</div>").hide().appendTo("#div-notif").fadeIn(500);a=$("#td_status_' . $_GET["id"] . '").html();b=a.replace("<i class=\"fa fa-calendar-times-o text-red\" title=\"Kadaluarsa\"></i>","");$("#td_status_' . $_GET["id"] . '").html(b);a=$("#td_btn_' . $_GET["id"] . '").html();b=a.replace("<a class=\"btn btn-sm bg-purple\" href=\"#\" onclick=\"openExpiredPO(&quot;M&quot;, &quot;' . $_GET["id"] . '&quot;)\" title=\"Buka Akses\"><i class=\"fa fa-undo\"></i></a>","' . $btnBaru . '");$("#td_btn_' . $_GET["id"] . '").html(b);';
            } else {
                logError("open_expired_po.php M", "$qReopenPO:" . $qReopenPO);
                mysqli_rollback($connect_app);
                $act = '$("<div id=\"alert-' . $_GET["id"] . '\" class=\"alert alert-danger\"><i class=\"fa fa-check\"></i> Gagal membuka akses Surat Pemesanan Medis</strong>.</div>").hide().appendTo("#div-notif").fadeIn(500);';
            }
            mysqli_autocommit($connect_app, TRUE);
            echo '<script>' . $act . 'closeNotif();</script>';
        } else if ($_GET["type"] == "N") {
            restrictAccess("surat_pemesanan_non_medis");
            mysqli_autocommit($connect_app, FALSE);
            $qCheckStatus = "SELECT status FROM surat_pemesanan_non_medis WHERE no_pemesanan='" . $_GET["id"] . "'";
            $checkStatus = mysqli_query($connect_app, $qCheckStatus);
            $status = mysqli_fetch_assoc($checkStatus)["status"];
            $qReopenPO = "UPDATE surat_pemesanan_non_medis SET kadaluarsa='0' WHERE no_pemesanan='" . $_GET["id"] . "'";
            $reopenPO = mysqli_query($connect_app, $qReopenPO);
            saveToTracker($qReopenPO, $connect_app);
            if ($reopenPO) {
                mysqli_commit($connect_app);
                if ($status == "Baru") {
                    $btnBaru = '<a class=\"btn btn-primary btn-sm\" href=\"edit_purchase_order_nm.php?id=' . $_GET["id"] . '\" title=\"Ubah\"><i class=\"fa fa-edit\"></i></a> <a class=\"btn btn-success btn-sm\" href=\"#\" onclick=\"approvePO(&quot;' . $_GET["id"] . '&quot;, &quot;N&quot;)\" title=\"Setujui\"><i class=\"fa fa-check\"></i></a>';
                } else $btnBaru = "";
                $act = '$("<div id=\"alert-' . $_GET["id"] . '\" class=\"alert alert-success\"><i class=\"fa fa-check\"></i> Sukses membuka akses Surat Pemesanan Non Medis: <strong>'. $_GET["id"] . '</strong>.</div>").hide().appendTo("#div-notif").fadeIn(500);a=$("#td_status_' . $_GET["id"] . '").html();b=a.replace("<i class=\"fa fa-calendar-times-o text-red\" title=\"Kadaluarsa\"></i>","");$("#td_status_' . $_GET["id"] . '").html(b);a=$("#td_btn_' . $_GET["id"] . '").html();b=a.replace("<a class=\"btn btn-sm bg-purple\" href=\"#\" onclick=\"openExpiredPO(&quot;N&quot;, &quot;' . $_GET["id"] . '&quot;)\" title=\"Buka Akses\"><i class=\"fa fa-undo\"></i></a>","' . $btnBaru . '");$("#td_btn_' . $_GET["id"] . '").html(b);';
            } else {
                logError("open_expired_po.php N", "$qReopenPO:" . $qReopenPO);
                mysqli_rollback($connect_app);
                $act = '$("<div id=\"alert-' . $_GET["id"] . '\" class=\"alert alert-danger\"><i class=\"fa fa-check\"></i> Gagal membuka akses Surat Pemesanan Non Medis</strong>.</div>").hide().appendTo("#div-notif").fadeIn(500);';
            }
            mysqli_autocommit($connect_app, TRUE);
            echo '<script>' . $act . 'closeNotif();</script>';
        }
    }
?>