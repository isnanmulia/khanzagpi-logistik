<?php
    require_once '../template/header.php';
    require_once '../config/connect_app.php';
    require_once '../function/access.php';
    require_once '../function/function.php';
    restrictAccess("ipsrs_barang");
    if (isset($_POST["form_submit"])) {
        mysqli_autocommit($connect_app, FALSE);
        $base_price = $_POST["hdn_harga_dasar"];
        $price_sk = str_replace(".", "", $_POST["inp_harga_sat_kecil"]);
        $price_sb = str_replace(".", "", $_POST["inp_harga_sat_besar"]);
        $no_ref = generateNumber("PHN");
        $qInsertPriceHistory = "INSERT INTO gpi_riwayat_harga_nonmedis (no_ref, kode_brng, tanggal_efektif, harga_sat_kecil, harga_sat_besar, dibuat_oleh, dibuat_pada) VALUES ('" . $no_ref . "', '" . $_POST["hdn_id"] . "', '" . $_POST["inp_tanggal_efektif"] . "', " . $price_sk . ", " . $price_sb . ", '" . $_SESSION["USER"]["USERNAME"] . "', Now())";
        $insertPriceHistory = mysqli_query($connect_app, $qInsertPriceHistory);
        $qUpdatePrice = "UPDATE ipsrsbarang SET dasar=" . $base_price . ", harga=" . $price_sk . " WHERE kode_brng='" . $_POST["hdn_id"] . "'";
        $updatePrice = mysqli_query($connect_app, $qUpdatePrice);
        if ($insertPriceHistory && $updatePrice) {
            mysqli_commit($connect_app);
            saveToTracker($qInsertPriceHistory, $connect_app);
            saveToTracker($qUpdatePrice, $connect_app);
            $msg = "Sukses menyimpan riwayat harga";
        } else {
            mysqli_rollback($connect_app);
            logError("add_price_history_nm.php", "insertPriceHistory:" . $insertPriceHistory . "|updatePrice:" . $updatePrice);
            $msg = "Gagal menyimpan riwayat harga";
        }
        mysqli_autocommit($connect_app, TRUE);
        echo "<script>alert('" . $msg . "'); window.location.href='price_history_nm.php'</script>";
    } else if (!isset($_GET["id"]) || $_GET["id"] == "") {
        denyAccess();
    } else {
        $qGetNMedLog = "SELECT B.kode_brng, nama_brng, SK.satuan AS satuan_kecil, SB.satuan AS satuan_besar, isi, no_ref, tanggal_efektif, harga_sat_kecil, harga_sat_besar FROM ipsrsbarang B INNER JOIN kodesatuan SK ON B.kode_sat=Sk.kode_sat INNER JOIN kodesatuan SB ON B.kode_satbesar=SB.kode_sat LEFT JOIN gpi_riwayat_harga_nonmedis RN ON B.kode_brng=RN.kode_brng WHERE B.kode_brng='" . $_GET["id"] . "' ORDER BY tanggal_efektif DESC LIMIT 1";
        $getNMedLog = mysqli_query($connect_app, $qGetNMedLog);
        $data = mysqli_fetch_assoc($getNMedLog);
    }
?>

<form name="frmAddPriceHistory" action="" onSubmit="return validateForm()" method="POST">
    <div style="max-width: 700px">
        <table class="table table-hover">
            <tr>
                <td style="width: 150px">No. Referensi</td>
                <td>PHN-YYMMXXX</td>
            </tr>
            <tr>
                <td>Kode Barang</td>
                <td><?php echo $data["kode_brng"] ?><input type="hidden" id="hdn_id" name="hdn_id" value="<?php echo $data["kode_brng"] ?>"></td>
            </tr>
            <tr>
                <td>Nama Barang</td>
                <td><?php echo $data["nama_brng"] ?></td>
            </tr>
            <tr>
                <td></td>
                <td>Satuan Kecil: <?php echo $data["satuan_kecil"] ?><span class="inline-spacer">|</span>Satuan Besar: <?php echo $data["satuan_besar"] ?><span class="inline-spacer">|</span>Isi: <?php echo $data["isi"] ?><input type="hidden" id="hdn_isi" name="hdn_isi" value="<?php echo $data["isi"] ?>"></td>
            </tr>
            <tr>
                <td>Harga Terakhir</td>
                <td>
                    <table>
                        <tr><td style="width: 170px">Harga Satuan Kecil (+ PPN)</td><td>:&nbsp;</td><td class="num-input"><?php echo (strlen($data["harga_sat_kecil"]) ? number_format($data["harga_sat_kecil"], 0, ",", ".") : "-") ?></td></tr>
                        <tr><td>Harga Satuan Besar</td><td>:&nbsp;</td><td class="num-input"><?php echo (strlen($data["harga_sat_besar"]) ? number_format($data["harga_sat_besar"], 0, ",", ".") : "-") ?></td></tr>
                        <tr><td>Tanggal Efektif</td><td>:&nbsp;</td><td><?php echo (strlen($data["tanggal_efektif"]) ? $data["tanggal_efektif"] : "-") ?></td></tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>Harga Baru</td>
                <td>
                    <table>
                        <tr><td style="width: 170px">Harga Satuan Kecil (+ PPN)</td><td><input type="text" id="inp_harga_sat_kecil" name="inp_harga_sat_kecil" class="form-control inline-input num-input price-input" readonly></td></tr>
                        <tr><td>Harga Satuan Besar <span class="span-required">*</span></td><td><input type="text" id="inp_harga_sat_besar" name="inp_harga_sat_besar" class="form-control iline-input num-input price-input" onKeyUp="removeNonNumeric('inp_harga_sat_besar'); setPriceSK()" maxlength="15" autocomplete="off"><input type="hidden" id="hdn_harga_dasar" name="hdn_harga_dasar"></td></tr>
                        <tr><td>Tanggal Efektif</td><td><input type="text" id="inp_tanggal_efektif" name="inp_tanggal_efektif" class="form-control inline-input" value="<?php echo $today ?>" readonly></td></tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: right">
                    <input type="hidden" id="form_submit" name="form_submit" value="1">
                    <a class="btn btn-primary" href="#" onClick="history.back()">
                        <i class="fa fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                </td>
            </tr>
        </table>
    </div>
</form>

<script>
    function setPriceSK() {
        isi = $("#hdn_isi").val();
        price_sb = ($("#inp_harga_sat_besar").val() ? toNumber($("#inp_harga_sat_besar").val()) : 0);
        base_price = price_sb/isi;
        price_sk = Math.round(base_price)*11/10;
        $("#hdn_harga_dasar").val(base_price);
        $("#inp_harga_sat_besar").val(formatNumber(price_sb));
        $("#inp_harga_sat_kecil").val(formatNumber(price_sk));
    }
    function validateForm() {
        price_sb = $("#inp_harga_sat_besar").val();
        if (!price_sb) {
            alert("Harga Satuan Besar kosong");
            $("#inp_harga_sat_besar").focus();
            return false;
        } else if (price_sb == 0) {
            alert("Harga Satuan Besar tidak boleh nol");
            $("#inp_harga_sat_besar").focus();
            return false;
        }
        return true;
    }
</script>

<?php require_once '../template/footer.php' ?>