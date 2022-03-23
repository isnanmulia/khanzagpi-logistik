<?php
    require_once '../template/header.php';
    require_once '../config/connect_app.php';
    require_once '../function/access.php';
    require_once '../function/function.php';
    restrictAccess("penjualan_obat");
    if (isset($_POST["form_submit"])) {
        mysqli_autocommit($connect_app, FALSE);
        $kode = generateNumber("JIT");
        $qInsertJIT = "INSERT INTO databarang (kode_brng, nama_brng, kode_satbesar, kode_sat, dasar, h_beli, ralan, kelas1, kelas2, kelas3, utama, vip, vvip, beliluar, jualbebas, karyawan, kdjns, kapasitas, `status`, kode_industri, kode_kategori, kode_golongan) VALUES ('" . $kode . "', '" . $_POST["inp_namabrg"] . "', '-', '" . $_POST["hdn_satuan"] . "', " . str_replace(".", "", $_POST["inp_hargabeli"]) . ", " . str_replace(".", "", $_POST["inp_hargabeli"]) . ", " . str_replace(".", "", $_POST["inp_hargarajal"]) . ", " . str_replace(".", "", $_POST["inp_hargaranapk1"]) . ", " . str_replace(".", "", $_POST["inp_hargaranapk2"]) . ", " . str_replace(".", "", $_POST["inp_hargaranapk3"]) . ", " . str_replace(".", "", $_POST["inp_hargaranaput"]) . ", " . str_replace(".", "", $_POST["inp_hargaranapvip"]) . ", " . str_replace(".", "", $_POST["inp_hargaranapvvip"]) . ", " . str_replace(".", "", $_POST["inp_hargaluar"]) . ", " . str_replace(".", "", $_POST["inp_hargaotc"]) . ", " . str_replace(".", "", $_POST["inp_hargakryw"]) . ", '" . $_POST["hdn_jenis"] . "', 100, '1', '-', '" . $_POST["hdn_kategori"] . "', '" . $_POST["hdn_golongan"] . "')";
        $insertJIT = mysqli_query($connect_app, $qInsertJIT);
        $qInsertStokToRiwayat = "INSERT INTO riwayat_barang_medis (kode_brng, stok_awal, masuk, keluar, stok_akhir, posisi, tanggal, jam, petugas, kd_bangsal, status, no_batch, no_faktur) VALUES ('" . $kode . "', 0, " . $_POST["inp_jumlah"] . ", 0, " . $_POST["inp_jumlah"] . ", 'Pengadaan', Date_Format(Now(), '%Y-%m-%d'), Date_Format(Now(), '%H:%i:%s'), '" . ($_SESSION["USER"]["USERNAME"] == "admin" ? "Admin Utama" : $_SESSION["USER"]["USERNAME"]) . "', 'FRMRJ', 'Simpan', '" . $_POST["inp_batch"] . "', '')";
        $insertStokToRiwayat = mysqli_query($connect_app, $qInsertStokToRiwayat);
        $qInsertStokToGudang = "INSERT INTO gudangbarang (kode_brng, kd_bangsal, stok, no_batch, no_faktur) VALUES ('" . $kode . "', 'FRMRJ', " . $_POST["inp_jumlah"] . ", '" . $_POST["inp_batch"] . "', '')";
        $insertStokToGudang = mysqli_query($connect_app, $qInsertStokToGudang);
        if ($insertJIT && $insertStokToRiwayat && $insertStokToGudang) {
            mysqli_commit($connect_app);
            saveToTracker($qInsertJIT, $connect_app);
            saveToTracker($qInsertStokToRiwayat, $connect_app);
            saveToTracker($qInsertStokToGudang, $connect_app);
            $msg = "Sukses menambahkan obat JIT";
        } else {
            logError("add_jit_medicine.php", "insertJIT:" . $insertJIT . "|insertStokToRiwayat:" . $insertStokToRiwayat . "|insertStokToGudang:" . $insertStokToGudang);
            // var_dump($qInsertJIT); var_dump($qInsertStokToGudang); var_dump($qInsertStokToRiwayat); die();
            mysqli_rollback($connect_app);
            $msg = "Gagal menambahkan obat JIT";
        }
        mysqli_autocommit($connect_app, TRUE);
        echo "<script>alert('" . $msg . "'); window.location.href='add_jit_medicine.php';</script>";
    }
    $qGetPricePctg = "SELECT ralan, kelas1, kelas2, kelas3, utama, vip, vvip, beliluar, jualbebas, karyawan FROM setpenjualanumum";
    $getPricePctg = mysqli_query($connect_app, $qGetPricePctg);
    $pricePctg = mysqli_fetch_row($getPricePctg);
?>

<form name="frmAddJIT" method="POST" target="" onSubmit="return validateForm()">
    <div style="max-width: 900px">
        <table class="table table-hover" id="tbl_add_jit">
            <tr>
                <td style="width: 120px">Kode Barang</td>
                <td style="width: 340px">JIT-YYMMDDXXXX</td>
            </tr>
            <tr>
                <td>Nama Barang <span class="span-required">*</span></td>
                <td><input type="text" id="inp_namabrg" name="inp_namabrg" class="form-control" autocomplete="off"></td>
            </tr>
            <tr>
                <td>Satuan <span class="span-required">*</span></td>
                <td><input type="text" id="inp_satuan" name="inp_satuan" class="form-control" onKeyUp="getSatuan()" autocomplete="off"><input type="hidden" id="hdn_satuan" name="hdn_satuan"></td>
                <td style="width: 100px">Jenis <span class="span-required">*</span></td>
                <td style="width: 340px"><input type="text" id="inp_jenis" name="inp_jenis" class="form-control" onKeyUp="getJenis()" autocomplete="off"><input type="hidden" id="hdn_jenis" name="hdn_jenis"></td>
            </tr>
            <tr>
                <td>Kategori <span class="span-required">*</span></td>
                <td><input type="text" id="inp_kategori" name="inp_kategori" class="form-control" onKeyUp="getKategori()" autocomplete="off"><input type="hidden" id="hdn_kategori" name="hdn_kategori"></td>
                <td>Golongan <span class="span-required">*</span></td>
                <td><input type="text" id="inp_golongan" name="inp_golongan" class="form-control" onKeyUp="getGolongan()" autocomplete="off"><input type="hidden" id="hdn_golongan" name="hdn_golongan"></td>
            </tr>
            <tr>
                <td>Harga Beli <span class="span-required">*</span></td>
                <td><input type="text" id="inp_hargabeli" name="inp_hargabeli" class="form-control inline-input num-input price-input" maxlength="15" onKeyUp="removeNonNumeric('inp_hargabeli'); setPrice();" autocomplete="off"></td>
                <td>Jumlah</td>
                <td>
                    <input type="text" id="inp_jumlah" name="inp_jumlah" class="form-control inline-input num-input price-input" maxlength="6" onKeyUp="removeNonNumeric('inp_jumlah')" style="width: 70px" autocomplete="off">
                    <span class="inline-spacer">|</span>No. Batch: <input type="text" id="inp_batch" name="inp_batch" class="form-control inline-input" style="width: 125px" autocomplete="off"></td>
            </tr>
            <tr>
                <td>Harga Jual</td>
                <td colspan="3">
                    <table class="table" style="padding: 10px">
                        <tr>
                            <td>Rawat Jalan</td>
                            <td><input type="text" id="inp_hargarajal" name="inp_hargarajal" class="form-control inline-input num-input price-input" readonly></td>
                            <td>Rawat Inap Utama/BPJS</td>
                            <td><input type="text" id="inp_hargaranaput" name="inp_hargaranaput" class="form-control inline-input num-input price-input" readonly></td>
                            <td>Ke Farmasi Lain</td>
                            <td><input type="text" id="inp_hargaluar" name="inp_hargaluar" class="form-control inline-input num-input price-input" readonly></td>
                        </tr>
                        <tr>
                            <td>Rawat Inap Kelas 1</td>
                            <td><input type="text" id="inp_hargaranapk1" name="inp_hargaranapk1" class="form-control inline-input num-input price-input" readonly></td>
                            <td>Rawat Inap VIP</td>
                            <td><input type="text" id="inp_hargaranapvip" name="inp_hargaranapvip" class="form-control inline-input num-input price-input" readonly></td>
                            <td>Jual Bebas</td>
                            <td><input type="text" id="inp_hargaotc" name="inp_hargaotc" class="form-control inline-input num-input price-input" readonly></td>
                        </tr>
                        <tr>
                            <td>Rawat Inap Kelas 2</td>
                            <td><input type="text" id="inp_hargaranapk2" name="inp_hargaranapk2" class="form-control inline-input num-input price-input" readonly></td>
                            <td>Rawat Inap VVIP</td>
                            <td><input type="text" id="inp_hargaranapvvip" name="inp_hargaranapvvip" class="form-control inline-input num-input price-input" readonly></td>
                            <td>Karyawan</td>
                            <td><input type="text" id="inp_hargakryw" name="inp_hargakryw" class="form-control inline-input num-input price-input" readonly></td>
                        </tr>
                        <tr>
                            <td>Rawat Inap Kelas 3</td>
                            <td><input type="text" id="inp_hargaranapk3" name="inp_hargaranapk3" class="form-control inline-input num-input price-input" readonly></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right">
                    <input type="hidden" id="form_submit" name="form_submit" value="1">
                    <!-- a class="btn btn-primary" href="#" onClick="clearFormJIT()">
                        <i class="fa fa-eraser"></i> Bersihkan Form
                    </a -->
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                </td>
            </tr>
        </table>
    </div>
</form>

<script>
    pricePctg = [];
    <?php for ($i=0; $i<10; $i++) echo "pricePctg.push(" . $pricePctg[$i] . ");" ?>
    function getSatuan() {
        term = $("#inp_satuan").val();
        if (term.length > 0) ajax_getData("ajax_getdata.php?type=unit&target=satuan&term=" + term);
            else removeSuggest();
    }
    function getJenis() {
        term = $("#inp_jenis").val();
        if (term.length > 0) ajax_getData("ajax_getdata.php?type=typelog&term=" + term);
            else removeSuggest();
    }
    function getKategori() {
        term = $("#inp_kategori").val();
        if (term.length > 0) ajax_getData("ajax_getdata.php?type=category&term=" + term);
            else removeSuggest();
    }
    function getGolongan() {
        term = $("#inp_golongan").val();
        if (term.length > 0) ajax_getData("ajax_getdata.php?type=grouplog&term=" + term);
            else removeSuggest();
    }
    function clearInputUnit() {
        $("#inp_satuan").val("");
        $("#hdn_satuan").val("");
    }
    function clearInputType() {
        $("#inp_jenis").val("");
        $("#hdn_jenis").val("");
    }
    function clearInputCategory() {
        $("#inp_kategori").val("");
        $("#hdn_kategori").val("");
    }
    function clearInputGroup() {
        $("#inp_golongan").val("");
        $("#hdn_golongan").val("");
    }
    function setPrice() {
        price = ($("#inp_hargabeli").val()).replace(/\./g, "");
        $("#inp_hargabeli").val(formatNumber(price));
        $("#inp_hargarajal").val(formatNumber(Math.round(price * (100 + pricePctg[0])/100)));
        $("#inp_hargaranapk1").val(formatNumber(Math.round(price * (100 + pricePctg[1])/100)));
        $("#inp_hargaranapk2").val(formatNumber(Math.round(price * (100 + pricePctg[2])/100)));
        $("#inp_hargaranapk3").val(formatNumber(Math.round(price * (100 + pricePctg[3])/100)));
        $("#inp_hargaranaput").val(formatNumber(Math.round(price * (100 + pricePctg[4])/100)));
        $("#inp_hargaranapvip").val(formatNumber(Math.round(price * (100 + pricePctg[5])/100)));
        $("#inp_hargaranapvvip").val(formatNumber(Math.round(price * (100 + pricePctg[6])/100)));
        $("#inp_hargaluar").val(formatNumber(Math.round(price * (100 + pricePctg[7])/100)));
        $("#inp_hargaotc").val(formatNumber(Math.round(price * (100 + pricePctg[8])/100)));
        $("#inp_hargakryw").val(formatNumber(Math.round(price * (100 + pricePctg[9])/100)));
    }
    function clearFormJIT() {
        $("#inp_namabrg").val("");
        $("#inp_satuan").val("");
        $("#hdn_satuan").val("");
        $("#inp_jenis").val("");
        $("#hdn_jenis").val("");
        $("#inp_kategori").val("");
        $("#hdn_kategori").val("");
        $("#inp_golongan").val("");
        $("#hdn_golongan").val("");
        $("#inp_hargabeli").val("");
        setPrice();
    }
    function validateForm() {
        namabrg = $("#inp_namabrg").val();
        unit = $("#inp_satuan").val();
        hdn_unit = $("#hdn_satuan").val();
        type = $("#inp_jenis").val();
        hdn_type = $("#hdn_jenis").val();
        category = $("#inp_kategori").val();
        hdn_category = $("#hdn_kategori").val();
        group = $("#inp_golongan").val();
        hdn_group = $("#hdn_golongan").val();
        hrgbeli = $("#inp_hargabeli").val();
        if (!namabrg) {
            alert("Nama Barang kosong");
            $("#inp_namabrg").focus();
            return false;
        } else if (!unit || !hdn_unit) {
            alert("Satuan kosong");
            $("#inp_satuan").focus();
            return false;
        } else if (!type || !hdn_type) {
            alert("Jenis kosong");
            $("#inp_jenis").focus();
            return false;
        } else if (!category || !hdn_category) {
            alert("Kategori kosong");
            $("#inp_kategori").focus();
            return false;
        } else if (!group || !hdn_group) {
            alert("Golongan kosong");
            $("#inp_golongan").focus();
            return false;
        } else if (!hrgbeli) {
            alert("Harga Beli kosong");
            $("#inp_hargabeli").focus();
            return false;
        } else if (hrgbeli == 0) {
            alert("Harga Beli tidak boleh nol");
            $("#inp_hargabeli").focus();
            return false;
        }
        return true;
    }
    setTimeout(function() { setPrice(); $("#inp_namabrg").focus(); }, 500);
</script>

<?php require_once '../template/footer.php' ?>