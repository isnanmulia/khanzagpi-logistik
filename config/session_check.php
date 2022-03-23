<?php
    require_once 'base_url.php';
    $sess_timeout = 3600;
    $today = date("Y-m-d");
    date_default_timezone_set("Asia/Jakarta");
    session_set_cookie_params(0, "/khanzagpi_ol/");
    session_start();
    if (isset($_SESSION) && (isset($_SESSION["LAST_ACTIVITY"]) && time() - $_SESSION["LAST_ACTIVITY"] < $sess_timeout)) {
        $_SESSION["LAST_ACTIVITY"] = time();
    } else {
        $msg = "";
        if (isset($_SESSION["LAST_ACTIVITY"])) $msg = "alert('Sesi Anda telah berakhir. Silakan masuk untuk melanjutkan');";
        session_unset();
        session_destroy();
        echo '<script>' . $msg . '
                url = window.location.href;
                if (window.opener) {
                    window.opener.location.href="' . $base_url . '/login.php";
                    window.close();
                } else window.location.href="' . $base_url . '/login.php";
            </script>';
        exit;
    }
?>