<?php
    $S = "localhost";
    // $S = "192.168.0.200";
    $U = "root";
    $P = "";
    $D = "sik";
    $connect_app = @mysqli_connect($S, $U, $P, $D);
    if ($connect_app) {
        // echo "Tersambung dengan DB aplikasi.";
    } else {
        die("Gagal tersambung dengan DB aplikasi");
    }
?>