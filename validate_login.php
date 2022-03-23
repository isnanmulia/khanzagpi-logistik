<?php
    require_once 'config/connect_app.php';
    require_once 'config/base_url.php';
    require_once 'function/access.php';
    require_once 'function/function.php';

    if (isset($_POST["form_submit"])) {
        $username = $_POST["inp_username"];
        $password = $_POST["inp_password"];
        $username = preg_replace("/\b(ALTER|CREATE|DELETE|DROP|EXEC(UTE){0,1}|INSERT( +INTO){0,1}|MERGE|SELECT|UPDATE|UNION( +ALL){0,1})\b/i", "", $username);
        $username = preg_replace("/[?;'\"<>]/", "", $username);
        $password = preg_replace("/\b(ALTER|CREATE|DELETE|DROP|EXEC(UTE){0,1}|INSERT( +INTO){0,1}|MERGE|SELECT|UPDATE|UNION( +ALL){0,1})\b/i", "", $password);
        $password = preg_replace("/[?;'\"<>]/", "", $password);
        $username = trim(mysqli_real_escape_string($connect_app, $username));
        $password = trim(mysqli_real_escape_string($connect_app, $password));
        $qLoginAdmin = "SELECT COUNT(*) cnt FROM admin WHERE usere=AES_ENCRYPT('" . $username . "', 'nur') AND passworde=AES_ENCRYPT('" . $password . "', 'windi')";
        $loginAdmin = @mysqli_query($connect_app, $qLoginAdmin);
        $isAdmin = @mysqli_fetch_assoc($loginAdmin)["cnt"];
        $qLoginUser = "SELECT COUNT(*) cnt FROM user WHERE id_user=AES_ENCRYPT('" . $username . "', 'nur') AND password=AES_ENCRYPT('" . $password . "', 'windi')";
        $loginUser = @mysqli_query($connect_app, $qLoginUser);
        $isUser = @mysqli_fetch_assoc($loginUser)["cnt"];
        if ($isAdmin || $isUser) {
            session_set_cookie_params(0, "/khanzagpi_ol/");
            session_start();
            $qGetSetting = "SELECT approver_po, approver_prm, approver_prnm FROM gpi_setting";
            $getSetting = mysqli_query($connect_app, $qGetSetting);
            $setting = mysqli_fetch_assoc($getSetting);
            if ($isAdmin) {
                $_SESSION["USER"] = array(
                    "USERNAME" => "admin",
                    "FULLNAME" => "Admin Utama",
                    "ACCESS" => "admin",
                    "BANGSAL" => setDataBangsal("admin"),
                );
            } else if ($isUser) {
                $qGetFullName = "SELECT nama FROM petugas WHERE nip='" . $username . "'";
                $getFullName = mysqli_query($connect_app, $qGetFullName);
                $fullName = mysqli_fetch_assoc($getFullName)["nama"];
                $qGetAccess = "SELECT * FROM user WHERE id_user=AES_ENCRYPT('" . $username . "', 'nur') AND password=AES_ENCRYPT('" . $password . "', 'windi')";
                $getAccess = mysqli_query($connect_app, $qGetAccess);
                $access = mysqli_fetch_assoc($getAccess);
                // var_dump($access);
                $_SESSION["USER"] = array(
                    "USERNAME" => $username,
                    "FULLNAME" => $fullName,
                    "ACCESS" => setAccess($access),
                    "BANGSAL" => setDataBangsal($username),
                );
                // var_dump($_SESSION["USER"]); die();
            }
            $_SESSION["SETTING"] = array(
                "APPROVER_PO" => explode(",", $setting["approver_po"]),
                "APPROVER_PRM" => explode(",", $setting["approver_prm"]),
                "APPROVER_PRN" => explode(",", $setting["approver_prnm"]),
            );
            $_SESSION["START"] = time();
            $_SESSION["LAST_ACTIVITY"] = time();
            $qInsertTracker = "INSERT INTO khanzagpi_tracker (nip, tgl_login, jam_login, alamat_IP) VALUES ('" . ($isAdmin ? "Admin Utama" : $username) . "', Current_Date(), Current_Time(), '" . $_SERVER["REMOTE_ADDR"] . "')";
            $insertTracker = mysqli_query($connect_app, $qInsertTracker);
            $qInsertTrackerSQL = "INSERT INTO khanzagpi_trackersql (tanggal, sqle, usere, alamat_IP) VALUES (Now(), '" . str_replace("'", "", $qInsertTracker) . "', '" . ($isAdmin ? "Admin Utama" : $username) . "', '" . $_SERVER["REMOTE_ADDR"] . "')";
            $insertTrackerSQL = mysqli_query($connect_app, $qInsertTrackerSQL);
            header("Location: app/index.php");
        } else {
            echo "<script>alert('Username/password salah'); history.back();</script>";
            exit();
        }
    }
?>