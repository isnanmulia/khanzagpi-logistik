<?php
    session_set_cookie_params(0, "/khanzagpi_ol/");
    session_start();
    unset($_SESSION["USER"]);
    session_destroy();
    header("Location: login.php");
    exit;
?>