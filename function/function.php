<?php
    function generateNumber($kode) {
        try {
            require '../config/connect_app.php';
            $qGetFormat = "SELECT format_nomor, periode_reset FROM gpi_master_penomoran WHERE kode='" . $kode . "'";
            $getFormat = mysqli_query($connect_app, $qGetFormat);
            $format = mysqli_fetch_assoc($getFormat);
            switch ($format["periode_reset"]) {
                case "H": $periode_reset = "AND tahun=Year(Now()) AND bulan=Month(Now()) AND tanggal=Day(Now())"; break;
                case "B": $periode_reset = "AND tahun=Year(Now()) AND bulan=Month(Now())"; break;
                case "T": $periode_reset = "AND tahun=Year(Now())"; break;
            }
            $qGetLastNo = "SELECT no_terakhir FROM gpi_log_penomoran WHERE kode='" . $kode . "' " . $periode_reset;
            $getLastNo = mysqli_query($connect_app, $qGetLastNo);
            $lastNo = mysqli_fetch_assoc($getLastNo);
            if ($lastNo) {
                $noUrut = $lastNo["no_terakhir"]+1;
                $qUpdateLogNo = "UPDATE gpi_log_penomoran SET no_terakhir=no_terakhir+1, diupdate_oleh='" . $_SESSION["USER"]["USERNAME"] . "', diupdate_pada=Now() WHERE kode='" . $kode . "' " . $periode_reset;
                $logNo = mysqli_query($connect_app, $qUpdateLogNo);
            } else {
                $noUrut = 1;
                $qAddLogNo = "INSERT INTO gpi_log_penomoran (kode, tahun, bulan, tanggal, no_terakhir, dibuat_oleh, dibuat_pada, diupdate_oleh, diupdate_pada) VALUES ('" . $kode . "', Year(Now()), " . ($format["periode_reset"] != "T" ? "Month(Now())" : "0") . ", " . ($format["periode_reset"] == "H" ? "Day(Now())" : "0") . ", 1, '" . $_SESSION["USER"]["USERNAME"] . "', Now(), '" . $_SESSION["USER"]["USERNAME"] . "', Now())";
                $logNo = mysqli_query($connect_app, $qAddLogNo);
            }
            if ($logNo) {
                $genNo = $format["format_nomor"];
                $nXs = substr_count($genNo, "X");
                if (strpos($genNo, "YY") > -1) $genNo = str_replace("YY", date("y"), $genNo);
                if (strpos($genNo, "MM") > -1) $genNo = str_replace("MM", date("m"), $genNo);
                if (strpos($genNo, "DD") > -1) $genNo = str_replace("DD", date("d"), $genNo);
                $Xs = "";
                for ($i=0; $i<$nXs; $i++) $Xs .= "X";
                $genNo = str_replace($Xs, str_pad($noUrut, $nXs, "0", STR_PAD_LEFT), $genNo);
                return $genNo;
            } else {
                logError("generateNumber(" . $kode . ")", mysqli_error($connect_app));
                return false;
            }
        } catch (Exception $e) {
            logError("catch_generateNumber(" . $kode . ")", $e->getMessage());
            return false;
        }
    }
    function amount2Words($x) {
        // $strAmount = "";
        // $angka = array (
        //     "1" => "Satu", "2" => "Dua", "3" => "Tiga", "4" => "Empat", "5" => Lima

        // );
    }
    function saveToTracker($sql, $connect_app) {
        try {
            if ($_SESSION["USER"]["USERNAME"] == "admin") $user = "Admin Utama";
                else $user = $_SESSION["USER"]["USERNAME"];
            foreach (explode("~", $sql) as $s) {
                $qInsertToTracker = "INSERT INTO khanzagpi_trackersql (tanggal, sqle, usere, alamat_IP) VALUES (Now(), '" . str_replace("'", "", $s) . "', '" . $user ."', '" . $_SERVER["REMOTE_ADDR"] . "')";
                $insertToTracker = mysqli_query($connect_app, $qInsertToTracker);
                if (!$insertToTracker) {
                    logError("saveToTracker(" . $sql . ")", mysqli_error($connect_app));
                }
            }
        } catch (Exception $e) {
            logError("catch_saveToTracker(" . $sql . ")", $e->getMessage());
        }
    }
    function setDataBangsal($user){
        require 'config/connect_app.php';
        if ($user == "admin") {
            $qGetBangsal = "SELECT GROUP_CONCAT(DISTINCT kd_bangsal ORDER BY kd_bangsal SEPARATOR ',') AS a FROM bangsal";
            $getBangsal = mysqli_query($connect_app, $qGetBangsal);
            $bangsal = mysqli_fetch_assoc($getBangsal);
            return $bangsal["a"];
        } else {
            $qGetBangsal = "SELECT * FROM gpi_userbangsal WHERE id_user='" . $user . "'";
            $getBangsal = mysqli_query($connect_app, $qGetBangsal);
            $lstBangsal = "";
            $n = mysqli_num_fields($getBangsal);
            while ($data = mysqli_fetch_assoc($getBangsal)) {
                foreach ($data as $key => $value) {
                    if ($key != "id_user" && $value == "true") $lstBangsal .= (strlen($lstBangsal) ? "," : "") . $key;
                }
            }
            return $lstBangsal;
        }
    }
    function logError($func, $errmsg) {
        $msg = date("Y-m-d H:i:s") . " - " . $_SERVER["REMOTE_ADDR"] . " - " . (isset($_SESSION["USER"]["USERNAME"]) ? $_SESSION["USER"]["USERNAME"] : "") . " - " . $func . " - " . $errmsg . "\n";
        file_put_contents("../errlogs/errlog_" . date("Y-m-d") . ".txt", $msg, FILE_APPEND);
    }
?>