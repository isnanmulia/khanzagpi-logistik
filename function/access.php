<?php
    function setAccess($param) {
        // if ($param == "admin") {
        //     $lstAccess = ["LAB", "LOGMED", "PDFTRN"];
        // } else {
        //     $lstAccess = [];
        //     if ($param["permintaan_lab"] == "true" && $param["periksa_lab"] == "true") array_push($lstAccess, "LAB");
        //     if ($param["surat_pemesanan_medis"] == "true" && $param["rekap_permintaan_medis"] == "true") array_push($lstAccess, "LOGMED");
        //     if ($param["registrasi"] == "true" && $param["igd"] && $param["edit_registrasi"]) array_push($lstAccess, "PDFTRN");
        // }
        $lstAccess = [];
        // foreach ($param as $p) {
            // if ($p == "true") array_push($lstAccess, $p);
        // }
        $lstAccess = array_filter($param, function($p) { return $p == "true"; });
        return array_keys($lstAccess);
    }
    function getAccess($accessCode) {
        if ($_SESSION["USER"]["USERNAME"] == "admin" || in_array($accessCode, $_SESSION["USER"]["ACCESS"])) return true;
            else return false;
    }
    function restrictAccess($accessCode, $isAjax = FALSE, $isOr = TRUE) {
        $status = FALSE;
        $acsCode = explode(",", $accessCode);
        foreach ($acsCode as $a) {
            if ($isOr) {
                if ($_SESSION["USER"]["USERNAME"] == "admin" || in_array($a, $_SESSION["USER"]["ACCESS"])) {
                    $status = $status || TRUE;
                }
            } else {
                if ($_SESSION["USER"]["USERNAME"] == "admin" || in_array($a, $_SESSION["USER"]["ACCESS"])) {
                    $status = $status && TRUE;
                } else $status = $status && FALSE;
            }
        }
        if (!$status) {
            if (!$isAjax)
                echo "<script>alert('Anda tidak diizinkan untuk mengakses menu ini'); if (window.opener) window.close(); else history.back();</script>";
            exit();
        }
    }
    function isUserAdmin() {
        if ($_SESSION["USER"]["USERNAME"] == "admin") return true;
            else return false;
    }
    function denyAccess() {
        echo "<script>alert('Anda tidak diizinkan untuk mengakses menu ini'); if (window.opener) window.close(); else history.back();</script>";
        exit();
    }
    function showMenuByAccess($accessCode) {
        if ($_SESSION["USER"]["USERNAME"] == "admin" || in_array($accessCode, $_SESSION["USER"]["ACCESS"])) return true;
            else return false;
    }
    function showApproval() {
        if ($_SESSION["USER"]["USERNAME"] == "admin" || in_array($_SESSION["USER"]["USERNAME"], $_SESSION["SETTING"]["APPROVER_PO"])) return true;
            else return false;
    }
    function showPRMApproval() {
        if ($_SESSION["USER"]["USERNAME"] == "admin" || in_array($_SESSION["USER"]["USERNAME"], $_SESSION["SETTING"]["APPROVER_PRM"])) return true;
            else return false;
    }
    function showPRNApproval() {
        if ($_SESSION["USER"]["USERNAME"] == "admin" || in_array($_SESSION["USER"]["USERNAME"], $_SESSION["SETTING"]["APPROVER_PRN"])) return true;
            else return false;
    }
?>