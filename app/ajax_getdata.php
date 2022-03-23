<?php
    require_once '../config/connect_app.php';
    require_once '../config/session_check.php';
    require_once '../function/access.php';
    $type = $_GET["type"];
    if ($type) {
        switch ($type) {
            case "unit":
                $target = $_GET["target"];
                if (isset($_GET["term"])) {
                    $qGetUnit = "SELECT kode_sat, satuan FROM kodesatuan WHERE kode_sat LIKE '%" . $_GET["term"] . "%' OR satuan LIKE '%" . $_GET["term"] . "%' ORDER BY satuan LIMIT 10";
                    $getUnit = mysqli_query($connect_app, $qGetUnit);
                    $lstUnit = ""; $i = 0;
                    while ($data = mysqli_fetch_assoc($getUnit)) {
                        $i++;
                        $onClickAct = 'ajax_getData(\"ajax_getdata.php?type=unit&target=' . $target . '&id=' . $data["kode_sat"] . '\")';
                        $lstUnit .= "<li onClick='" . $onClickAct . "'>" . $data["satuan"] . "</li>";
                    }
                    if (strlen($lstUnit)) {
                        $lstUnit = "<div id='suggest-unit' class='suggestion'><ul>" . $lstUnit . "</ul></div>";
                    } else {
                        $lstUnit = "<div id='suggest-unit' class='suggestion'><ul><li onClick='clearInput(\"satbesar\"); removeSuggest()'>Tidak ada saran</li></ul></div>";
                        $i = 1;
                    }
                    echo '<script>
                        removeSuggest();
                        elm = $("#inp_' . $target . '");
                        posX = elm.position().left;
                        posY = elm.position().top + elm.outerHeight();
                        zIndex = 100;
                        listUnit = "' . $lstUnit . '";
                        $(".content-wrapper").append(listUnit);
                        $("#suggest-unit").css({"top":posY, "left":posX, "height":' . ($i*24 + 2) . ', "z-index":zIndex});
                    </script>';
                } else if (isset($_GET["id"])) {
                    $qGetUnit = "SELECT satuan FROM kodesatuan WHERE kode_sat='" . $_GET["id"] . "'";
                    $getUnit = mysqli_query($connect_app, $qGetUnit);
                    $unit = mysqli_fetch_assoc($getUnit);
                    echo "<script>
                        $('#inp_" . $target . "').val('" . $unit["satuan"] . "');
                        $('#hdn_" . $target . "').val('" . $_GET["id"] . "');
                    </script>";
                }
                break;
            case "typelog":
                if (isset($_GET["term"])) {
                    $term = $_GET["term"];
                    $qGetType = "SELECT kdjns, nama FROM jenis WHERE kdjns LIKE '%" . $term . "%' OR nama LIKE '%" . $term . "%'";
                    $getType = mysqli_query($connect_app, $qGetType);
                    $lstType = ""; $i = 0;
                    while ($data = mysqli_fetch_assoc($getType)) {
                        $i++;
                        $onClickAct = 'ajax_getData(\"ajax_getdata.php?type=typelog&id=' . $data["kdjns"] . '\")';
                        $lstType .= "<li onClick='" . $onClickAct . "'>" . $data["nama"] . "</li>";
                    }
                    if (strlen($lstType)) {
                        $lstType = "<div id='suggest-type' class='suggestion'><ul>" . $lstType . "</ul></div>";
                    } else {
                        $lstType = "<div id='suggest-type' class='suggestion'><ul><li onClick='clearInputType(); removeSuggest()'>Tidak ada saran</li></ul></div>";
                        $i = 1;
                    }
                    echo '<script>
                        removeSuggest();
                        elm = $("#inp_jenis");
                        posX = elm.position().left;
                        posY = elm.position().top + elm.outerHeight();
                        zIndex = 100;
                        listType = "' . $lstType . '";
                        $(".content-wrapper").append(listType);
                        $("#suggest-type").css({"top":posY, "left":posX, "height":' . ($i*24 + 2) . ', "z-Index":zIndex});
                    </script>';
                } else if (isset($_GET["id"])) {
                    $qGetType = "SELECT nama FROM jenis WHERE kdjns='" . $_GET["id"] . "'";
                    $getType = mysqli_query($connect_app, $qGetType);
                    $type = mysqli_fetch_assoc($getType);
                    echo "<script>
                        $('#inp_jenis').val('" . $type["nama"] . "');
                        $('#hdn_jenis').val('" . $_GET["id"] . "');
                    </script>";
                }
                break;
            case "category":
                if (isset($_GET["term"])) {
                    $term = $_GET["term"];
                    $qGetCategory = "SELECT kode, nama FROM kategori_barang WHERE kode LIKE '%" . $term . "%' OR nama LIKE '%" . $term . "%'";
                    $getCategory = mysqli_query($connect_app, $qGetCategory);
                    $lstCategory = ""; $i = 0;
                    while ($data = mysqli_fetch_assoc($getCategory)) {
                        $i++;
                        $onClickAct = 'ajax_getData(\"ajax_getdata.php?type=category&id=' . $data["kode"] . '\")';
                        $lstCategory .= "<li onClick='" . $onClickAct . "'>" . $data["nama"] . "</li>";
                    }
                    if (strlen($lstCategory)) {
                        $lstCategory = "<div id='suggest-category' class='suggestion'><ul>" . $lstCategory . "</ul></div>";
                    } else {
                        $lstCategory = "<div id='suggest-category' class='suggestion'><ul><li onClick='clearInputCategory(); removeSuggest()'>Tidak ada saran</li></ul></div>";
                        $i = 1;
                    }
                    echo '<script>
                        removeSuggest();
                        elm = $("#inp_kategori");
                        posX = elm.position().left;
                        posY = elm.position().top + elm.outerHeight();
                        zIndex = 100;
                        listCategory = "' . $lstCategory . '";
                        $(".content-wrapper").append(listCategory);
                        $("#suggest-category").css({"top":posY, "left":posX, "height":' . ($i*24 + 2) . ', "zIndex":zIndex});
                    </script>';
                } else if (isset($_GET["id"])) {
                    $qGetCategory = "SELECT nama FROM kategori_barang WHERE kode='" . $_GET["id"] . "'";
                    $getCategory = mysqli_query($connect_app, $qGetCategory);
                    $category = mysqli_fetch_assoc($getCategory);
                    echo "<script>
                        $('#inp_kategori').val('" . $category["nama"] . "');
                        $('#hdn_kategori').val('" . $_GET["id"] . "');
                    </script>";
                }
                break;
            case "grouplog":
                if (isset($_GET["term"])) {
                    $term = $_GET["term"];
                    $qGetGroup = "SELECT kode, nama FROM golongan_barang WHERE kode LIKE '%" . $term . "%' OR nama LIKE '%" . $term . "%'";
                    $getGroup = mysqli_query($connect_app, $qGetGroup);
                    $lstGroup = ""; $i = 0;
                    while ($data = mysqli_fetch_assoc($getGroup)) {
                        $i++;
                        $onClickAct = 'ajax_getData(\"ajax_getdata.php?type=grouplog&id=' . $data["kode"] . '\")';
                        $lstGroup .= "<li onClick='" . $onClickAct . "'>" . $data["nama"] . "</li>";
                    }
                    if (strlen($lstGroup)) {
                        $lstGroup = "<div id='suggest-group' class='suggestion'><ul>" . $lstGroup . "</ul></div>";
                    } else {
                        $lstGroup = "<div id='suggest-group' class='suggestion'><ul><li onClick='clearInputGroup(); removeSuggest()'>Tidak ada saran</li></ul></div>";
                        $i = 1;
                    }
                    echo '<script>
                        removeSuggest();
                        elm = $("#inp_golongan");
                        posX = elm.position().left;
                        posY = elm.position().top + elm.outerHeight();
                        zIndex = 100;
                        listGroup = "' . $lstGroup . '";
                        $(".content-wrapper").append(listGroup);
                        $("#suggest-group").css({"top":posY, "left":posX, "height": ' . ($i*24 + 2) . ', "zIndex":zIndex});
                    </script>';
                } else if (isset($_GET["id"])) {
                    $qGetGroup = "SELECT nama FROM golongan_barang WHERE kode='" . $_GET["id"] . "'";
                    $getGroup = mysqli_query($connect_app, $qGetGroup);
                    $group = mysqli_fetch_assoc($getGroup);
                    echo "<script>
                        $('#inp_golongan').val('" . $group["nama"] . "');
                        $('#hdn_golongan').val('" . $_GET["id"] . "');
                    </script>";
                }
                break;
            case "prlist":
                $start = $_GET["start"];
                $end = $_GET["end"];
                $arg = ""; $hdr = ""; $act = "";
                if (isset($_GET["approval"])) {
                    $status = "Proses Pengajuan";
                    $field = "tanggal";
                    $target = "tbl_pm";
                    $hdr = "<th style='width: 60px'>Aksi</th>";
                    $arg = ", &quot;M&quot;";
                    $colspan = "6";
                } else {
                    $status = "Pengajuan";
                    $field = "tanggal_disetujui";
                    $target = "tbl_purchaserequest";
                    $colspan = "5";
                }
                $qGetPR = "SELECT no_pengajuan, P.nama, " . $field . ", status, keterangan FROM pengajuan_barang_medis PBM INNER JOIN pegawai P ON PBM.nip=P.nik WHERE " . $field . " BETWEEN '" . $start . "' AND '" . $end . "' AND PBM.status='" . $status . "' ORDER BY " . $field . " DESC, no_pengajuan DESC";
                $getPR = mysqli_query($connect_app, $qGetPR);
                $lstPR = "";
                while ($data = mysqli_fetch_assoc($getPR)) {
                    if (isset($_GET["approval"])) {
                        $act = "<td><a class='btn btn-success btn-sm' href='#' onClick='approvePR(&quot;" . $data["no_pengajuan"] . "&quot;, &quot;M&quot;)' title='Setujui'><i class='fa fa-check'></i></a></td>";
                    }
                    switch ($data["status"]) {
                        case "Proses Pengajuan": $status = "<i class='fa fa-list text-blue' title='Proses Pengajuan'></i>"; break;
                        case "Pengajuan": $status = "<i class='fa fa-angle-double-right text-blue' title='Pengajuan'></i>"; break;
                        case "Disetujui": $status = "<i class='fa fa-check text-green' title='Disetujui'></i>"; break;
                        case "Ditolak": $status = "<i class='fa fa-times text-red' title='Ditolak'></i>"; break;
                        default: $status = ""; break;
                    }
                    $qGetPRDtl = "SELECT kode_brng, jumlah FROM detail_pengajuan_barang_medis WHERE no_pengajuan='" . $data["no_pengajuan"] . "'";
                    $getPRDtl = mysqli_query($connect_app, $qGetPRDtl);
                    $PRDtl = "";
                    while ($dtl = mysqli_fetch_assoc($getPRDtl)) {
                        $PRDtl .= (strlen($PRDtl) ? "|" : "") . $dtl["kode_brng"] . ":" . $dtl["jumlah"] . ":" . $dtl["jumlah"];
                    }
                    $lstPR .= "<tr id='tr_header_" . $data["no_pengajuan"] . "'><td>" . $data["no_pengajuan"] . " <span id='chevron_" . $data["no_pengajuan"] . "' class='chevron' title='Klik untuk menampilkan/menyembunyikan detail pengajuan' onClick='togglePRDetail(&quot;" . $data["no_pengajuan"] . "&quot;" . $arg . ")'><i class='fa fa-chevron-circle-down'></i></span></td><td>" . $data[$field] . "</td><td>" . $data["nama"] . "</td><td>" . $data["keterangan"] . "</td><td>" . $status . "<input type='hidden' id='hdn_PRDtl_" . $data["no_pengajuan"] . "' name='hdn_PRDtl_" . $data["no_pengajuan"] . "' value='" . $PRDtl . "'></td>" . $act . "</tr><tr id='tr_" . $data["no_pengajuan"] . "' style='display: none'><td id='td_" . $data["no_pengajuan"] . "' class='td-detail' colspan='" . $colspan . "'>-</td></tr>";
                }
                if (!strlen($lstPR)) $lstPR = "<tr><td colspan='5' style='text-align: center'>--- Tidak ada data ---</td></tr>";
                $lstPR = "<thead><tr><th style='width: 150px'>No. Pengajuan</th><th style='width: 100px'>" . (isset($_GET["approval"]) ? "Tanggal" : "Tgl. Disetujui") . "</th><th style='width: 250px'>Yang Mengajukan</th><th>Catatan</th><th style='width: 60px'>Status</th>" . $hdr . "</tr></thead><tbody>" . $lstPR . "</tbody>";
                echo '<script>$("#' . $target . '").html("' . $lstPR . '")</script>';
                break;
            case "prdetail":
                if (isset($_GET["id"])) {
                    if (isset($_GET["data"]))
                        $selItems = base64_decode($_GET["data"]);
                    else $selItems = "";
                    $qGetPRDetail = "SELECT DPBM.kode_brng, nama_brng, satuan, jumlah, DPBM.status, jumlah_disetujui FROM detail_pengajuan_barang_medis DPBM INNER JOIN databarang B ON DPBM.kode_brng=B.kode_brng INNER JOIN kodesatuan S ON DPBM.kode_sat=S.kode_sat WHERE no_pengajuan='" . $_GET["id"] . "'" . (isset($_GET["select"]) ? " AND DPBM.status<>'Ditolak'" : "");
                    $getPRDetail = mysqli_query($connect_app, $qGetPRDetail);
                    $PRDetail = ""; $i = 0;
                    while ($data = mysqli_fetch_assoc($getPRDetail)) {
                        switch ($data["status"]) {
                            case "Proses Pengajuan": $status = "<i class='fa fa-list text-blue' title='Proses Pengajuan'></i>"; break;
                            case "Disetujui": $status = "<i class='fa fa-check text-green' title='Disetujui'></i>"; break;
                            case "Ditolak": $status = "<i class='fa fa-times-circle-o text-red' title='Ditolak'></i>"; break;
                        }
                        $PRDetail .= "<tr><td>" . ++$i . "</td><td>" . $data["kode_brng"] . "</td><td>" . str_replace('"', '\"', $data["nama_brng"]) . "</td><td>" . $data["satuan"] . "</td>" . (isset($_GET["approval"]) ? "<td class='num-input'>" . number_format($data["jumlah"], 0, ",", ".") . " " . $data["satuan"] . "</td><td class='num-input' style='width: 60px'><input type='text' id='inp_disetujui_" . $data["kode_brng"] . "_" . $_GET["id"] . "' name='inp_disetujui_" . $data["kode_brng"] . "_" . $_GET["id"] . "' class='form-control num-input' style='width: 75px' maxlength='7' value='" . number_format($data["jumlah_disetujui"], 0, ",", ".") . "' onKeyUp='removeNonNumeric(&quot;inp_disetujui_" . $data["kode_brng"] . "_" . $_GET["id"] . "&quot;); recheckPRApproval(&quot;" . $_GET["id"] . "&quot;)'></td><td style='width: 100px'>" . $data["satuan"] . "</td>" : "<td class='num-input'>" . number_format($data["jumlah_disetujui"], 0, ",", ".") . " " . $data["satuan"] . "</td>") . "<td>" . $status . "</td>" . (!isset($_GET["approval"]) ? "<td>" . ($data["status"] != "Disetujui" ? (strpos($selItems, $data["kode_brng"] . "_" . $_GET["id"]) === FALSE ? "<input type='checkbox' id='chk_" . $data["kode_brng"] . "_" . $_GET["id"] . "_" . $data["jumlah"] . "' name='chk_" . $data["kode_brng"] . "_" . $_GET["id"] . "_" . $data["jumlah"] . "' onClick='activateBtnPilih()'>" : "<i class='fa fa-check-square-o text-green' title='Sudah ditambahkan'></i>") : "") . "</td>" : "") . "</tr>";
                    }
                    $PRDetail = "<table class='table table-hover'><tr><th style='min-width: 30px'>No</th><th style='min-width: 60px'>Kode</th><th style='min-width: 475px'>Nama Barang</th><th style='min-width: 95px'>Satuan Besar</th>" . (isset($_GET["approval"]) ? "<th style='min-width: 105px'>Jumlah Diajukan</th><th colspan='2' style='min-width: 105px'>Jumlah Disetujui</th>" : "<th style='min-width: 60px'>Jumlah</th>") . "<th style='min-width: 60px'>Status</th>" . (!isset($_GET["approval"]) ? "<th style='min-width: 60px'>Aksi</th>" : "") . "</tr>" . $PRDetail . "</table>";
                    echo '<script>$("#td_' . $_GET["id"] . '").html("' . $PRDetail . '"); ' . (!isset($_GET["approval"]) ? 'activateBtnPilih();' : '') . '</script>';
                }
                break;
            case "minmaxitemm":
                if (isset($_GET["term"])) {
                    $term = $_GET["term"];
                    $qGetItem = "SELECT kode_brng, nama_brng FROM databarang WHERE (kode_brng LIKE '%" . $term . "%' OR nama_brng LIKE '%" . $term . "%') AND kode_brng NOT LIKE 'JIT%' AND status='1' LIMIT 10";
                    $getItem = mysqli_query($connect_app, $qGetItem);
                    $lstItem = ""; $i = 0;
                    while ($data = mysqli_fetch_assoc($getItem)) {
                        $i++;
                        $onClickAct = 'ajax_getData(\"ajax_getdata.php?type=minmaxitemm&id=' . $data["kode_brng"] . '\")';
                        $lstItem .= "<li onClick='" . $onClickAct . "'>" . str_replace('"', '\"', $data["nama_brng"]) . " [" . $data["kode_brng"] . "]</li>";
                    }
                    if (strlen($lstItem)) {
                        $lstItem = "<div id='suggest-item' class='suggestion'><ul>" . $lstItem . "</ul></div>";
                    } else {
                        $lstItem = "<div id='suggest-item' class='suggestion'><ul><li onClick='clearInputItem(&quot;barang_m&quot;); removeSuggest()'>Tidak ada saran</li></ul></div>";
                        $i = 1;
                    }
                    echo '<script>
                        removeSuggest();
                        elm = $("#inp_barang_m");
                        posX = elm.position().left;
                        posY = elm.position().top + elm.outerHeight();
                        zIndex = 100;
                        listItem = "' . $lstItem . '";
                        $(".content-wrapper").append(listItem);
                        $("#suggest-item").css({"top":posY, "left":posX, "height":' . ($i*24 + 2) . ', "zIndex":zIndex});
                    </script>';
                } else if (isset($_GET["id"])) {
                    $qGetItem = "SELECT kode_brng, nama_brng, satuan FROM databarang B INNER JOIN kodesatuan S ON B.kode_sat=S.kode_sat WHERE kode_brng='" . $_GET["id"] . "'";
                    $getItem = mysqli_query($connect_app, $qGetItem);
                    $item = mysqli_fetch_assoc($getItem);
                    $qGetUnits = "SELECT nm_bangsal, min_stok, max_stok FROM gpi_minmax_obat M INNER JOIN bangsal B ON M.kd_bangsal=B.kd_bangsal WHERE kode_brng='" . $_GET["id"] . "'";
                    $getUnits = mysqli_query($connect_app, $qGetUnits);
                    $lstUnits = "";
                    while ($data = mysqli_fetch_assoc($getUnits)) {
                        $lstUnits .= "<tr><td>" . $data["nm_bangsal"] . "</td><td>" . number_format($data["min_stok"], 0, ",", ".") . " " . $item["satuan"] . "</td><td>" . number_format($data["max_stok"], 0, ",", ".") . " " . $item["satuan"] . "</td><td>Aksi?</td></tr>";
                    }
                    if (!strlen($lstUnits)) $lstUnits = "<tr><td colspan=\'4\' style=\'text-align: center\'>--- Tidak ada data ---</td></tr>";
                    $lstUnits = "<thead><tr><th>Unit</th><th>Stok Minimal</th><th>Stok Maksimal</th><th>Aksi</th></tr></thead><tbody>" . $lstUnits . "</tbody>";
                    echo "<script>
                        $('#inp_barang_m').val('" . $item["nama_brng"] . "');
                        $('#hdn_barang_m').val('" . $item["kode_brng"] . "');
                        $('#div_lstUnit').slideUp(500);
                        setTimeout(function() {
                            $('#h4_item').html('" . $item["nama_brng"] . " [" . $_GET["id"] . "] (Satuan: " . $item["satuan"] . ")');
                            $('#tbl_lstUnit').html('" . $lstUnits . "');
                            $('#div_lstUnit').slideDown(500);
                        }, 500);
                    </script>";
                }
                break;
            case "minmaxmunit":
                if (isset($_GET["term"])) {
                    $term = $_GET["term"];
                    $qGetUnit = "SELECT kd_bangsal, nm_bangsal FROM bangsal WHERE (kd_bangsal LIKE '%" . $term . "%' OR nm_bangsal LIKE '%" . $term . "%') AND status='1' LIMIT 10";
                    $getUnit = mysqli_query($connect_app, $qGetUnit);
                    $lstUnit = ""; $i = 0;
                    while ($data = mysqli_fetch_assoc($getUnit)) {
                        $i++;
                        $onClickAct = 'ajax_getData(\"ajax_getdata.php?type=minmaxmunit&id=' . $data["kd_bangsal"] . '\")';
                        $lstUnit .= "<li onClick='" . $onClickAct . "'>" . str_replace('"', '\"', $data["nm_bangsal"]) . " [" . $data["kd_bangsal"] . "]</li>";
                    }
                    if (strlen($lstUnit)) {
                        $lstUnit = "<div id='suggest-unit' class='suggestion'><ul>" . $lstUnit . "</ul></div>";
                    } else {
                        $lstItem = "<div id='suggest-unit' class='suggestion'><ul><li onClick='clearInput(&quot;bangsal&quot;); removeSuggest()'>Tidak ada saran</li></ul></div>";
                        $i = 1;
                    }
                    echo '<script>
                        removeSuggest();
                        elm = $("#inp_bangsal");
                        posX = elm.position().left;
                        posY = elm.position().top + elm.outerHeight();
                        zIndex = 100;
                        listUnit = "' . $lstUnit . '";
                        $(".content-wrapper").append(listUnit);
                        $("#suggest-unit").css({"top":posY, "left":posX, "height":' . ($i*24 + 2) . ', "zIndex":zIndex});
                    </script>';
                } else if (isset($_GET["id"])) {
                    $qGetUnit = "SELECT nm_bangsal FROM bangsal WHERE kd_bangsal='" . $_GET["id"] . "'";
                    $getUnit = mysqli_query($connect_app, $qGetUnit);
                    $unit = mysqli_fetch_assoc($getUnit);
                    $qGetItems = "SELECT B.kode_brng, nama_brng, satuan, min_stok, max_stok, stok FROM gpi_minmax_obat M INNER JOIN databarang B ON M.kode_brng=B.kode_brng INNER JOIN kodesatuan S ON B.kode_sat=S.kode_sat LEFT JOIN gudangbarang G ON B.kode_brng=G.kode_brng AND M.kd_bangsal=G.kd_bangsal WHERE M.kd_bangsal='" . $_GET["id"] . "'";
                    $getItems = mysqli_query($connect_app, $qGetItems);
                    $lstItems = ""; $lstItem = [];
                    while ($data = mysqli_fetch_assoc($getItems)) {
                        $lstItems .= "<tr id=\'tr_" . $data["kode_brng"] . "\'><td>" . $data["nama_brng"] . " [" . $data["kode_brng"] . "]</td><td><span id=\'span_min_" . $data["kode_brng"] . "\'>" . number_format($data["min_stok"], 0, ",", ".") . "</span><input type=\'text\' id=\'inp_min_" . $data["kode_brng"] . "\' name=\'inp_min_" . $data["kode_brng"] . "\' class=\'form-control inline-input\' value=\'" . number_format($data["min_stok"], 0, ",", ".") . "\' maxlength=\'6\' style=\'width: 65px; display: none\' onKeyUp=\'removeNonNumeric(&quot;inp_min_" . $data["kode_brng"] . "&quot;);recheckMinMax(&quot;M&quot;);\'><input type=\'hidden\' id=\'hdn_min_" . $data["kode_brng"] . "\' name=\'hdn_min_" . $data["kode_brng"] . "\' value=\'" . $data["min_stok"] . "\'> " . $data["satuan"] . "</td><td><span id=\'span_max_" . $data["kode_brng"] . "\'>" . number_format($data["max_stok"], 0, ",", ".") . "</span><input type=\'text\' id=\'inp_max_" . $data["kode_brng"] . "\' name=\'inp_max_" . $data["kode_brng"] . "\' class=\'form-control inline-input\' value=\'" . number_format($data["max_stok"], 0, ",", ".") . "\' maxlength=\'6\' style=\'width: 65px; display: none\' onKeyUp=\'removeNonNumeric(&quot;inp_max_" . $data["kode_brng"] . "&quot;);recheckMinMax(&quot;M&quot;);\'><input type=\'hidden\' id=\'hdn_max_" . $data["kode_brng"] . "\' name=\'hdn_max_" . $data["kode_brng"] . "\' value=\'" . $data["max_stok"] . "\'> " . $data["satuan"] . "</td><td>" . number_format($data["stok"], 0, ",", ".") . " " . $data["satuan"] . "</td><td><button type=\'button\' id=\'btnEdit_" . $data["kode_brng"] . "\' class=\'btn btn-sm btn-primary\' title=\'Ubah\' onClick=\'editMinMax2(&quot;" . $data["kode_brng"] . "&quot;)\' " . (!getAccess("mutasi_barang") ? "disabled" : "") . "><i class=\'fa fa-edit\'></i></button><a id=\'btnBatal_" . $data["kode_brng"] . "\' class=\'btn btn-sm btn-danger\' href=\'#\' title=\'Batal\' style=\'display: none\' onClick=\'cancelEditMinMax(&quot;" . $data["kode_brng"] . "&quot;)\'><i class=\'fa fa-times\'></i></a></td></tr>";
                        array_push($lstItem, $data["kode_brng"]);
                    }
                    if (!strlen($lstItems)) $lstItems = "<tr><td colspan=\'4\' style=\'text-align: center\'>--- Tidak ada data ---</td></tr>";
                    $lstItems = "<thead><tr><th style=\'width: 500px\'>Barang</th><th style=\'width: 130px\'>Stok Minimal</th><th style=\'width: 130px\'>Stok Maksimal</th><th style=\'width: 100px\'>Stok Tersedia</th><th style=\'width: 50px\'>Aksi</th></tr></thead><tbody>" . $lstItems . "</tbody>";
                    echo "<script>
                        $('#inp_bangsal').val('" . $unit["nm_bangsal"] . "');
                        $('#inp_bangsal').prop('disabled', 'disabled');
                        $('#hdn_bangsal').val('" . $_GET["id"] . "');
                        $('#div_unitDtl').slideUp(500);
                        setTimeout(function() {
                            $('#h4_unit').html('" . $unit["nm_bangsal"] . " [" . $_GET["id"] . "]');
                            $('#hdn_MinMaxitems').val('" . implode(",", $lstItem) . "');
                            $('#tbl_lstItem').html('" . $lstItems . "');
                            $('#div_unitDtl').slideDown(500);
                            $('#btn_clearBangsal').prop('disabled', '');
                        }, 500);
                    </script>";
                } else if (isset($_GET["clear"])) {
                    echo "<script>
                        $('#div_unitDtl').slideUp(500);
                        setTimeout(function() {
                            clearInput('bangsal');
                            $('#inp_bangsal').prop('disabled', '');
                            $('#h4_unit').html('');
                            $('#btn_submit').prop('disabled', 'disabled');
                            $('#tbl_lstItem tbody').empty();
                            $('#btn_clearBangsal').prop('disabled', 'disabled');
                        }, 500);
                    </script>";
                }
                break;
            case "minmaxmadditem":
                if (isset($_GET["term"])) {
                    $term = $_GET["term"];
                    $data = base64_decode($_GET["data"]);
                    $qGetItem = "SELECT kode_brng, nama_brng FROM databarang WHERE kode_brng NOT IN (SELECT kode_brng FROM gpi_minmax_obat WHERE kd_bangsal='" . $_GET["unit"] . "') AND (kode_brng LIKE '%" . $term . "%' OR nama_brng LIKE '%" . $term . "%') AND status='1' LIMIT 10";
                    $getItem = mysqli_query($connect_app, $qGetItem);
                    $lstItem = ""; $i = 0;
                    while ($data = mysqli_fetch_assoc($getItem)) {
                        $i++;
                        $onClickAct = 'ajax_getData(\"ajax_getdata.php?type=minmaxmadditem&from=' . $_GET["from"] . '&id=' . $data["kode_brng"] . '&unit=' . $_GET["unit"] . '\")';
                        $lstItem .= "<li onClick='" . $onClickAct . "'>" . str_replace('"', '\"', $data["nama_brng"]) . " [" . $data["kode_brng"] . "]</li>";
                    }
                    if (strlen($lstItem)) {
                        $lstItem = "<div id='suggest-item' class='suggestion'><ul>" . $lstItem . "</ul></div>";
                    } else {
                        $lstItem = "<div id='suggest-item' class='suggestion'><ul><li onClick='clearInput(&quot;" . $_GET["from"] . "&quot;); removeSuggest()'>Tidak ada saran</li></ul></div>";
                        $i = 1;
                    }
                    echo '<script>
                        removeSuggest();
                        elm = $("#inp_' . $_GET["from"] . '");
                        posX = elm.position().left;
                        posY = elm.position().top + elm.outerHeight();
                        zIndex = 100;
                        listItem = "' . $lstItem . '";
                        $(".content-wrapper").append(listItem);
                        $("#suggest-item").css({"top":posY, "left":posX, "height":' . ($i*24 + 2) . ', "zIndex":zIndex});
                    </script>';
                } else if (isset($_GET["id"])) {
                    $qGetItem = "SELECT nama_brng, satuan, IfNull(stok, 0) AS stok FROM databarang B INNER JOIN kodesatuan S ON B.kode_sat=S.kode_sat LEFT JOIN gudangbarang G ON B.kode_brng=G.kode_brng AND kd_bangsal='" . $_GET["unit"] . "' WHERE B.kode_brng='" . $_GET["id"] . "'";
                    $getItem = mysqli_query($connect_app, $qGetItem);
                    $item = mysqli_fetch_assoc($getItem);
                    $rowItem = "$('#tr_" . $_GET["id"] . "').html('<td>" . $item["nama_brng"] . " [" . $_GET["id"] . "]</td><td><input type=\'text\' id=\'inp_min_" . $_GET["id"] . "\' name=\'inp_min_" . $_GET["id"] . "\' class=\'form-control inline-input\' value=\'0\' maxlength=\'6\' style=\'width: 65px\' onKeyUp=\'removeNonNumeric(&quot;inp_min_" . $_GET["id"] . "&quot;);recheckMinMax(&quot;M&quot;);\'> " . $item["satuan"] . "</td><td><input type=\'text\' id=\'inp_max_" . $_GET["id"] . "\' name=\'inp_max_" . $_GET["id"] . "\' class=\'form-control inline-input\' value=\'0\' maxlength=\'6\' style=\'width: 65px\' onKeyUp=\'removeNonNumeric(&quot;inp_max_" . $_GET["id"] . "&quot;);recheckMinMax(&quot;M&quot;);\'> " . $item["satuan"] . "</td><td>" . number_format($item["stok"], 0, ",", ".") . " " . $item["satuan"] . "</td><td><a class=\'btn btn-danger btn-sm\' title=\'Hapus\' href=\'#\' onClick=\'if (confirm(&quot;Apakah Anda yakin ingin menghapus data ini?&quot;)) removeItem(&quot;" . $_GET["id"] . "&quot;)\'><i class=\'fa fa-trash\'></i></a></td>');";
                    echo "<script>
                        $('#tr_" . $_GET["from"] . "').attr('id', 'tr_" . $_GET["id"] . "');" . $rowItem . "appendItems('MinMax', '" . $_GET["id"] . "');
                    </script>";
                }
                break;
            case "polist":
                require_once "../function/access.php";
                $start = $_GET["start"];
                $end = $_GET["end"];
                $supplier = (isset($_GET["supplier"]) ? $_GET["supplier"] : "");
                $mode = (isset($_GET["mode"]) ? $_GET["mode"] : "");
                $tblhead = "";
                $status = "";
                if ($mode == "popup") {
                    $colspan = 6;
                    $status = "'Proses Pesan'";
                } else {
                    $colspan = 7;
                    $tblhead = "<th style='width: 125px'>Aksi</th>";
                    if (isset($_GET["approval"])) $status = "'Baru'";
                        else $status = "'Proses Pesan','Baru','Sudah Datang'";
                }
                $qGetPO = "SELECT no_pemesanan, tanggal, nama_suplier, P.nama, catatan, SPM.status, kadaluarsa FROM surat_pemesanan_medis SPM INNER JOIN datasuplier S ON SPM.kode_suplier=S.kode_suplier INNER JOIN pegawai P ON SPM.nip=P.nik WHERE tanggal BETWEEN '" . $start . "' AND '" . $end . "'" . (strlen($supplier) ? " AND SPM.kode_suplier='" . $supplier . "'" : "") . " AND SPM.status IN (" . $status . ") ORDER BY tanggal, no_pemesanan";
                $getPO = mysqli_query($connect_app, $qGetPO);
                $lstPO = ""; $act = "";
                while ($data = mysqli_fetch_assoc($getPO)) {
                    switch ($data["status"]) {
                        case "Baru": $status = "<i class=\'fa fa-plus-square text-blue\' title=\'Baru\'></i>"; break;
                        case "Proses Pesan": $status = "<i class=\'fa fa-shopping-cart text-purple\' title=\'Proses Pesan\'></i>"; break;
                        case "Sudah Datang": $status = "<i class=\'glyphicon glyphicon-saved text-green\' title=\'Sudah Datang\'></i>"; break;
                        default: $status = ""; break;
                    }
                    if ($data["kadaluarsa"] == "1")
                        $status .= "&nbsp;<i class='fa fa-calendar-times-o text-red' title='Kadaluarsa'></i>";
                    if ($mode == "popup") $btnAct = "";
                        else $btnAct = "<td id='td_btn_" . $data["no_pemesanan"] . "'>" . (($data["status"] == "Baru") ? ($data["kadaluarsa"] == "0" ? (isset($_GET["approval"]) ? "" : "<a class='btn btn-primary btn-sm' href='edit_purchase_order_m.php?id=" . $data["no_pemesanan"] . "' title='Ubah'><i class='fa fa-edit'></i></a>") . (showApproval() ? " <a class='btn btn-success btn-sm' href='#' onClick='approvePO(&quot;" . $data["no_pemesanan"] . "&quot;, &quot;M&quot;)' title='Setujui'><i class='fa fa-check'></i></a>" : "") : "") : "<a class='btn btn-primary btn-sm' href='#' onClick='printPO(&quot;" . $data["no_pemesanan"] . "&quot;)' title='Cetak'><i class='fa fa-print'></i></a>") . ($data["kadaluarsa"] == "1" ? " <a class='btn btn-sm bg-purple' href='#' onClick='openExpiredPO(&quot;M&quot;, &quot;" . $data["no_pemesanan"] . "&quot;)' title='Buka Akses'><i class='fa fa-undo'></i></a>" : "") . "</td>";
                    $lstPO .= "<tr id='tr_header_" . $data["no_pemesanan"] . "'><td>" . $data["no_pemesanan"] . " <span id='chevron_" . $data["no_pemesanan"] . "' class='chevron' title='Klik untuk menampilkan/menyembunyikan detail pemesanan' onClick='togglePODetail(&quot;" . $data["no_pemesanan"] . "&quot;, &quot;M&quot;)'><i class='fa fa-chevron-circle-down'></i></span></td><td>" . $data["tanggal"] . "</td><td>" . $data["nama_suplier"] . "</td><td>" . $data["nama"] . "</td><td>" . addslashes(str_replace(array("\r\n", "\n", "<br>", "\n\r"), " ", $data["catatan"])) . "</td><td id='td_status_" . $data["no_pemesanan"] . "'>" . $status . "</td>" . $btnAct . "</tr><tr id='tr_" . $data["no_pemesanan"] . "' style='display: none'><td id='td_" . $data["no_pemesanan"] . "' class='td-detail' colspan='" . $colspan . "'>-</td></tr>";
                    $act .= ' togglePODetail("' . $data["no_pemesanan"] . '");';
                }
                if (!strlen($lstPO)) $lstPO = "<tr><td colspan='" . $colspan . "' style='text-align: center'>--- Tidak ada data ---</td></tr>";
                $lstPO = "<thead><tr><th style='width: 120px'>No. Pemesanan</th><th style='width: 85px'>Tanggal</th><th style='width: 200px'>Supplier</th><th style='width: 250px'>Petugas</th><th>Catatan</th><th style='width: 60px'>Status</th>" . $tblhead . "</tr></thead><tbody>" . $lstPO . "</tbody>";
                if (isset($_GET["approval"])) $target = "tbl_spm";
                    else $target = "tbl_purchaseorder";
                echo '<script>$("#' . $target . '").html("' . $lstPO . '");' . ($mode == "popup" ? $act : '') . '</script>';
                break;
            case "podetail":
                if (isset($_GET["id"])) {
                    if (isset($_GET["data"]))
                        $selItems = base64_decode($_GET["data"]);
                    else $selItems = "";
                    $mode = (isset($_GET["mode"]) ? $_GET["mode"] : "");
                    if ($mode == "popup") {
                        $tblhead1 = "";
                        $tblhead2 = "<th>Aksi</th>";
                    } else {
                        $tblhead1 = "<th>Harga Per SB</th><th>Subtotal</th><th>Diskon 1 (%)</th><th>Diskon 2 (%)</th><th>Total Diskon</th><th>Total</th><th>Stok RS</th>";
                        $tblhead2 = "";
                    }
                    $qCheckExpiry = "SELECT kadaluarsa FROM surat_pemesanan_medis WHERE no_pemesanan='" . $_GET["id"] . "'";
                    $checkExpiry = mysqli_query($connect_app, $qCheckExpiry);
                    $expiry = mysqli_fetch_assoc($checkExpiry)["kadaluarsa"];
                    $qGetPODetail = "SELECT DSPM.kode_brng, nama_brng, no_pr_ref, SK.satuan AS satuan_kecil, SB.satuan AS satuan_besar, DSPM.isi, jumlah, h_pesan, subtotal, dis, dis2, besardis, total, IfNull(Sum(stok), 0) AS stok, DSPM.status FROM detail_surat_pemesanan_medis DSPM INNER JOIN databarang B ON DSPM.kode_brng=B.kode_brng INNER JOIN kodesatuan SK ON DSPM.kode_sat=SK.kode_sat INNER JOIN kodesatuan SB ON DSPM.kode_satbesar=SB.kode_sat LEFT JOIN gudangbarang G ON G.kode_brng=B.kode_brng WHERE no_pemesanan='" . $_GET["id"] . "' GROUP BY kode_brng, no_pr_ref ORDER BY kode_brng";
                    $getPODetail = mysqli_query($connect_app, $qGetPODetail);
                    $PODetail = ""; $i = 0;
                    while ($data = mysqli_fetch_assoc($getPODetail)) {
                        switch ($data["status"]) {
                            case "Baru": $status = "<i class=\'fa fa-plus-square text-blue\' title=\'Baru\'></i>"; break;
                            case "Proses Pesan": $status = "<i class=\'fa fa-shopping-cart text-purple\' title=\'Proses Pesan\'></i>"; break;
                            case "Sudah Datang": $status = "<i class=\'glyphicon glyphicon-saved text-green\' title=\'Sudah Datang\'></i>"; break;
                            default: $status = ""; break;
                        }
                        if ($mode == "popup") {
                            $tbldata1 = "";
                            $tbldata2 = "<td>" . ($data["status"] != "Sudah Datang" ? (strpos($selItems, $data["kode_brng"] . "_" . $_GET["id"] . "_" . $data["jumlah"] . "_" . str_replace(".", "", $data["dis"]) . "_" . $data["no_pr_ref"]) === FALSE ? "<input type='checkbox' id='chk_" . $data["kode_brng"] . "_" . $_GET["id"] . "_" . $data["jumlah"] . "_" . str_replace(".", "", $data["dis"]) . "' name='chk_" . $data["kode_brng"] . "_" . $_GET["id"] . "_" . $data["jumlah"] . "_" . str_replace(".", "", $data["dis"]) . "_" . $data["no_pr_ref"] . "' onClick='activateBtnPilih()' " . ($expiry == "1" ? "disabled" : "") . ">" : "<i class='fa fa-check-square-o text-green' title='Sudah ditambahkan'></i>") : "") . "</td>";
                        } else {
                            $tbldata1 = "<td class='num-input'>" . number_format($data["h_pesan"], 0, ",", ".") . "</td><td class='num-input'>" . number_format($data["subtotal"], 0, ",", ".") . "</td><td class='num-input'>" . str_replace(".", ",", $data["dis"]) . "</td><td class='num-input'>" . str_replace(".", ",", $data["dis2"]) . "</td><td class='num-input'>" . number_format($data["besardis"], 0, ",", ".") . "</td><td class='num-input'>" . number_format($data["total"], 0, ",", ".") . "</td><td><a class='btn btn-primary btn-sm' onClick='getStockDtl(&quot;" . $data["kode_brng"] . "&quot;, &quot;M&quot;)' title='Klik untuk menampilkan detail stok barang per bangsal'><i class='fa fa-eye'></i></a></td>";
                            $tbldata2 = "";
                        }
                        $PODetail .= "<tr><td>" . ++$i . "</td><td>" . $data["kode_brng"] . "</td><td>" . str_replace('"', '\"', $data["nama_brng"]) . "</td><td>" . ($data["no_pr_ref"] ? $data["no_pr_ref"] : "-") . "</td><td>" . $data["satuan_kecil"] . "</td><td>" . $data["satuan_besar"] . "</td><td class='num-input'>" . $data["isi"] . "</td><td class='num-input'>" . number_format($data["jumlah"], 0, ",", ".") . " " . $data["satuan_besar"] . "</td>" . $tbldata1 . "<td>" . $status . "</td>" . $tbldata2 . "</tr>";
                    }
                    $PODetail = "<table class='table'><tr><th>No</th><th>Kode</th><th>Nama Barang</th><th>No. Pengajuan</th><th title='Satuan Kecil'>SK</th><th title='Satuan Besar'>SB</th><th>Isi</th><th>Jumlah Pesan</th>" . $tblhead1 . "<th>Status</th>" . $tblhead2 . "</tr>" . $PODetail . "</table>";
                    echo '<script>$("#td_' . $_GET["id"] . '").html("' . $PODetail . '")</script>';
                }
                break;
            case "itemnonpr":
                if (isset($_GET["term"])) {
                    $term = $_GET["term"];
                    $data = base64_decode($_GET["data"]);
                    $qGetItem = "SELECT kode_brng, nama_brng FROM databarang WHERE kode_brng NOT IN ('" . str_replace(",", "','", $data) . "') AND (kode_brng LIKE '%" . $term . "%' OR nama_brng LIKE '%" . $term . "%') AND kode_brng NOT LIKE 'JIT%' AND status='1' LIMIT 10";
                    $getItem = mysqli_query($connect_app, $qGetItem);
                    $lstItem = ""; $i = 0;
                    while ($data = mysqli_fetch_assoc($getItem)) {
                        $i++;
                        $onClickAct = 'ajax_getData(\"ajax_getdata.php?type=itemnonpr&from=' . $_GET["from"] . '&id=' . $data["kode_brng"] . '\")';
                        $lstItem .= "<li onClick='" . $onClickAct . "'>" . str_replace('"', '\"', $data["nama_brng"]) . " [" . $data["kode_brng"] . "]</li>";
                    }
                    if (strlen($lstItem)) {
                        $lstItem = "<div id='suggest-item' class='suggestion'><ul>" . $lstItem . "</ul></div>";
                    } else {
                        $lstItem = "<div id='suggest-item' class='suggestion'><ul><li onClick='clearInputItem(&quot;" . $_GET["from"] . "&quot;); removeSuggest()'>Tidak ada saran</li></ul></div>";
                        $i = 1;
                    }
                    echo '<script>
                        removeSuggest();
                        elm = $("#inp_' . $_GET["from"] . '");
                        posX = elm.position().left;
                        posY = elm.position().top + elm.outerHeight();
                        zIndex = 100;
                        listItem = "' . $lstItem . '";
                        $(".content-wrapper").append(listItem);
                        $("#suggest-item").css({"top":posY, "left":posX, "height":' . ($i*24 + 2) . ', "zIndex":zIndex});
                    </script>';
                } else if (isset($_GET["id"])) {
                    $qGetItem = "SELECT B.kode_brng, nama_brng, SK.kode_sat, SK.satuan AS satuan_kecil, kode_satbesar, SB.satuan AS satuan_besar, isi, dasar*isi AS harga_beli, IfNull(Sum(stok), 0) AS stok FROM databarang B INNER JOIN kodesatuan SK ON B.kode_sat=SK.kode_sat INNER JOIN kodesatuan SB ON B.kode_satbesar=SB.kode_sat LEFT JOIN gudangbarang G ON B.kode_brng=G.kode_brng WHERE B.kode_brng='" . $_GET["id"] . "' GROUP BY B.kode_brng";
                    $getItem = mysqli_query($connect_app, $qGetItem);
                    $item = mysqli_fetch_assoc($getItem);
                    // $rowItem = "$('#tr_" . $item["kode_brng"] . "').html('<td>" . $item["kode_brng"] . "</td><td>" . $item["nama_brng"] . "</td><td class=\'num-input\'>-</td><td>" . $item["satuan_kecil"] . "<input type=\'hidden\' id=\'hdn_sk_" . $item["kode_brng"] . "\' name=\'hdn_sk_" . $item["kode_brng"] . "\' value=\'" . $item["kode_sat"] . "\'></td><td class=\'num-input\'>" . $item["faktor_konversi"] . "<input type=\'hidden\' id=\'hdn_fk_" . $item["kode_brng"] . "\' name=\'hdn_fk_" . $item["kode_brng"] . "\' value=\'" . $item["faktor_konversi"] . "\'></td><td><input type=\'text\' id=\'inp_jml_" . $item["kode_brng"] . "\' name=\'inp_jml_" . $item["kode_brng"] . "\' class=\'form-control num-input\' value=\'0\' onKeyUp=\'removeNonNumeric(&quot;inp_jml_" . $item["kode_brng"] . "&quot;); recalculatePO()\' maxlength=\'5\' autocomplete=\'off\' style=\'width: 60px\'></td><td>" . $item["satuan_besar"] . "<input type=\'hidden\' id=\'hdn_sb_" . $item["kode_brng"] . "\' name=\'hdn_sb_" . $item["kode_brng"] . "\' value=\'" . $item["kode_sat_besar"] . "\'></td><td class=\'num-input\'><input type=\'text\' id=\'inp_hpesan_" . $item["kode_brng"] . "\' name=\'inp_hpesan_" . $item["kode_brng"] . "\' class=\'form-control num-input\' style=\'width: 100px\' value=\'" . number_format($item["h_beli_satbesar"], 0, ",", ".") . "\' onKeyUp=\'removeNonNumeric(&quot;inp_hpesan_" . $item["kode_brng"] . "&quot;); recalculatePO()\' autocomplete=\'off\' maxlength=\'11\'></td><td class=\'num-input\'><span id=\'span_subtotal_" . $item["kode_brng"] . "\' name=\'span_subtotal_" . $item["kode_brng"] . "\'>" . number_format(0, 0, ",", ".") . "</span><input type=\'hidden\' id=\'hdn_subtotal_" . $item["kode_brng"] . "\' name=\'hdn_subtotal_" . $item["kode_brng"] . "\' value=\'0\'></td><td><input type=\'text\' id=\'inp_disc1_" . $item["kode_brng"] . "\' name=\'inp_disc1_" . $item["kode_brng"] . "\' class=\'form-control num-input\' value=\'0\' maxlength=\'2\' onKeyUp=\'removeNonNumeric(&quot;inp_disc1_" . $item["kode_brng"] . "&quot;); recalculatePO()\' autocomplete=\'off\'></td><td><input type=\'text\' id=\'inp_disc2_" . $item["kode_brng"] . "\' name=\'inp_disc2_" . $item["kode_brng"] . "\' class=\'form-control num-input\' value=\'0\' maxlength=\'2\' onKeyUp=\'removeNonNumeric(&quot;inp_disc2_" . $item["kode_brng"] . "&quot;); recalculatePO()\' autocomplete=\'off\'></td><td class=\'num-input\'><span id=\'span_ndisc_" . $item["kode_brng"] . "\' name=\'span_ndisc_" . $item["kode_brng"] . "\'>0</span><input type=\'hidden\' id=\'hdn_ndisc_" . $item["kode_brng"] . "\' name=\'hdn_ndisc_" . $item["kode_brng"] . "\'></td><td class=\'num-input td-total\'><span id=\'span_total_" . $item["kode_brng"] . "\' name=\'span_total_" . $item["kode_brng"] . "\'>" . number_format(0, 0, ",", ".") . "</span><input type=\'hidden\' id=\'hdn_total_" . $item["kode_brng"] . "\' name=\'hdn_total_" . $item["kode_brng"] . "\' value=\'0\'></td><td class=\'num-input\'><a class=\'a-text\' onClick=\'getStockDtl(&quot;" . $item["kode_brng"] . "&quot;, &quot;M&quot;)\'>" . number_format($item["stok"], 0, ",", ".") . "</a></td><td>-</td><td><a class=\'btn btn-danger btn-sm\' title=\'Hapus\' href=\'#\' onClick=\'if (confirm(&quot;Apakah Anda yakin ingin menghapus data ini?&quot;)) removeItem(&quot;" . $item["kode_brng"] . "&quot;)\'><i class=\'fa fa-trash\'></i></a></td>');";
                    $rowItem = "$('#tr_" . $item["kode_brng"] . "').html('<td>" . $item["kode_brng"] . "</td><td>" . $item["nama_brng"] . "</td><td>" . $item["satuan_kecil"] . "<input type=\'hidden\' id=\'hdn_sk_" . $item["kode_brng"] . "\' name=\'hdn_sk_" . $item["kode_brng"] . "\' value=\'" . $item["kode_sat"] . "\'></td><td>" . $item["satuan_besar"] . "<input type=\'hidden\' id=\'hdn_sb_" . $item["kode_brng"] . "\' name=\'hdn_sb_" . $item["kode_brng"] . "\' value=\'" . $item["kode_satbesar"] . "\'></td> <td class=\'num-input\'>" . $item["isi"] . "<input type=\'hidden\' id=\'hdn_fk_" . $item["kode_brng"] . "\' name=\'hdn_fk_" . $item["kode_brng"] . "\' value=\'" . $item["isi"] . "\'></td><td class=\'num-input\'>-</td><td style=\'width: 60px\'><input type=\'text\' id=\'inp_jml_" . $item["kode_brng"] . "\' name=\'inp_jml_" . $item["kode_brng"] . "\' class=\'form-control num-input\' value=\'0\' style=\'width: 60px\' onKeyUp=\'removeNonNumeric(&quot;inp_jml_" . $item["kode_brng"] . "&quot;); recalculatePO()\' maxlength=\'5\' autocomplete=\'off\'></td><td style=\'width: 100px\'>" . $item["satuan_besar"] . "</td><td class=\'num-input\'><input type=\'text\' id=\'inp_hpesan_" . $item["kode_brng"] . "\' name=\'inp_hpesan_" . $item["kode_brng"] . "\' class=\'form-control num-input\' style=\'width: 100px\' value=\'" . number_format($item["harga_beli"], 0, ",", ".") . "\' onKeyUp=\'removeNonNumeric(&quot;inp_hpesan_" . $item["kode_brng"] . "&quot;); recalculatePO()\' autocomplete=\'off\' maxlength=\'11\'></td><td class=\'num-input\'><span id=\'span_subtotal_" . $item["kode_brng"] . "\' name=\'span_subtotal_" . $item["kode_brng"] . "\'>" . number_format(0, 0, ",", ".") . "</span><input type=\'hidden\' id=\'hdn_subtotal_" . $item["kode_brng"] . "\' name=\'hdn_subtotal_" . $item["kode_brng"] . "\' value=\'0\'></td><td><input type=\'text\' id=\'inp_disc1_" . $item["kode_brng"] . "\' name=\'inp_disc1_" . $item["kode_brng"] . "\' class=\'form-control num-input\' value=\'0\' maxlength=\'5\' onKeyUp=\'removeNonNumeric(&quot;inp_disc1_" . $item["kode_brng"] . "&quot;); recalculatePO()\' autocomplete=\'off\'></td><td><input type=\'text\' id=\'inp_disc2_" . $item["kode_brng"] . "\' name=\'inp_disc2_" . $item["kode_brng"] . "\' class=\'form-control num-input\' value=\'0\' maxlength=\'5\' onKeyUp=\'removeNonNumeric(&quot;inp_disc2_" . $item["kode_brng"] . "&quot;); recalculatePO()\' autocomplete=\'off\'></td><td class=\'num-input\'><span id=\'span_ndisc_" . $item["kode_brng"] . "\' name=\'span_ndisc_" . $item["kode_brng"] . "\'>0</span><input type=\'hidden\' id=\'hdn_ndisc_" . $item["kode_brng"] . "\' name=\'hdn_ndisc_" . $item["kode_brng"] . "\'></td><td class=\'num-input td-total\'><span id=\'span_total_" . $item["kode_brng"] . "\' name=\'span_total_" . $item["kode_brng"] . "\'>" . number_format(0, 0, ",", ".") . "</span><input type=\'hidden\' id=\'hdn_total_" . $item["kode_brng"] . "\' name=\'hdn_total_" . $item["kode_brng"] . "\' value=\'0\'></td><td class=\'num-input\'>" . number_format($item["stok"], 0, ",", ".") . " " . $item["satuan_kecil"] . " <a class=\'btn btn-primary btn-sm\' onClick=\'getStockDtl(&quot;" . $item["kode_brng"] . "&quot;, &quot;M&quot;)\' title=\'Klik untuk menampilkan detail stok barang per bangsal\'><i class=\'fa fa-eye\'></i></a></td><td>-</td><td><a class=\'btn btn-danger btn-sm\' title=\'Hapus\' href=\'#\' onClick=\'if (confirm(&quot;Apakah Anda yakin ingin menghapus data ini?&quot;)) removeItem(&quot;" . $item["kode_brng"] . "&quot;)\'><i class=\'fa fa-trash\'></i></a></td>');";
                    echo "<script>
                        $('#tr_" . $_GET["from"] . "').attr('id', 'tr_" . $item["kode_brng"] . "');" . $rowItem . "
                        appendItems('PO', '" . $item["kode_brng"] . "');
                        recalculatePO();
                    </script>";
                }
                break;
            case "poitem":
                if (isset($_GET["term"])) {
                    $term = $_GET["term"];
                    $qGetItem = "SELECT kode_brng, nama_brng FROM databarang WHERE kode_brng NOT LIKE 'JIT%' AND (kode_brng LIKE '%" . $term . "%' OR nama_brng LIKE '%" . $term . "%') LIMIT 10";
                    $getItem = mysqli_query($connect_app, $qGetItem);
                    $lstItem = ""; $i = 0;
                    while ($data = mysqli_fetch_assoc($getItem)) {
                        $i++;
                        $onClickAct = 'ajax_getData(\"ajax_getdata.php?type=poitem&id=' . $data["kode_brng"] . '\")';
                        $lstItem .= "<li onClick='" . $onClickAct . "'>" . $data["nama_brng"] . " [" . $data["kode_brng"] . "]</li>";
                    }
                    if (strlen($lstItem)) {
                        $lstItem = "<div id='suggest-item' class='suggestion'><ul>" . $lstItem . "</ul></div>";
                    } else {
                        $lstItem = "<div id='suggest-item' class='suggestion'><ul><li onClick='clearInput(&quot;barang_m&quot;); removeSuggest()'>Tidak ada saran</li></ul></div>";
                        $i = 1;
                    }
                    echo '<script>
                        removeSuggest();
                        elm = $("#inp_barang_m");
                        posX = elm.position().left;
                        posY = elm.position().top + elm.outerHeight();
                        zIndex = 100;
                        listItem = "' . $lstItem . '";
                        $(".content-wrapper").append(listItem);
                        $("#suggest-item").css({"top":posY, "left":posX, "height": ' . ($i*24 + 2) . ', "zIndex":zIndex});
                    </script>';
                } else if (isset($_GET["id"])) {
                    $qGetItem = "SELECT nama_brng FROM databarang WHERE kode_brng='" . $_GET["id"] . "'";
                    $getItem = mysqli_query($connect_app, $qGetItem);
                    $item = mysqli_fetch_assoc($getItem);
                    echo "<script>
                        $('#inp_barang_m').val('" . $item["nama_brng"] . " [" . $_GET["id"] . "]');
                        $('#hdn_barang_m').val('" . $_GET["id"] . "');
                    </script>";
                }
                break;
            case "poitemlist":
                $brng = $_GET["item"];
                $supplier = $_GET["supplier"];
                $keyword = $_GET["keyword"];
                $start = $_GET["start"];
                $end = $_GET["end"];
                if (strlen($brng) && strlen($supplier)) $cond = "DSPM.kode_brng='" . $brng . "' AND SPM.kode_suplier='" . $supplier . "'";
                else {
                    if (strlen($brng)) $cond = "DSPM.kode_brng='" . $brng . "'";
                    else if (strlen($supplier)) $cond = "SPM.kode_suplier='" . $supplier . "'";
                    else if (strlen($keyword)) $cond = "DSPM.kode_brng LIKE '%" . $keyword . "%' OR nama_brng LIKE '%" . $keyword . "%' OR SPM.kode_suplier LIKE '%" . $keyword . "%' OR nama_suplier LIKE '%" . $keyword . "%'";
                    else $cond = "1=1";
                }
                $qGetPOItem = "SELECT B.kode_brng, nama_brng, SK.satuan AS satuan_kecil, SB.satuan AS satuan_besar, B.isi, PBM.tanggal AS tgl_pengajuan, no_pr_ref, DPBM.jumlah AS jml_pengajuan, PBM.tanggal_disetujui, DPBM.jumlah_disetujui, SPM.tanggal AS tgl_pesan, SPM.no_pemesanan, DSPM.jumlah AS jml_pesan, nama_suplier, P.tgl_pesan AS tgl_penerimaan, P.no_faktur, no_faktur_supplier, DP.jumlah AS jml_penerimaan, DP.h_pesan, DP.subtotal, DP.dis, DP.dis2, DP.besardis, DP.total, no_batch, DP.kadaluarsa, CASE WHEN P.ppn>0 THEN 1 WHEN P.ppn=0 THEN 0 ELSE '' END AS is_ppn FROM surat_pemesanan_medis SPM INNER JOIN detail_surat_pemesanan_medis DSPM ON SPM.no_pemesanan=DSPM.no_pemesanan INNER JOIN databarang B ON DSPM.kode_brng=B.kode_brng INNER JOIN datasuplier S ON SPM.kode_suplier=S.kode_suplier INNER JOIN kodesatuan SK ON B.kode_sat=SK.kode_sat INNER JOIN kodesatuan SB ON B.kode_satbesar=SB.kode_sat LEFT JOIN pengajuan_barang_medis PBM ON DSPM.no_pr_ref=PBM.no_pengajuan LEFT JOIN detail_pengajuan_barang_medis DPBM ON PBM.no_pengajuan=DPBM.no_pengajuan AND DPBM.kode_brng=DSPM.kode_brng LEFT JOIN detailpesan DP ON SPM.no_pemesanan=DP.no_pemesanan AND DP.kode_brng=DSPM.kode_brng LEFT JOIN pemesanan P ON DP.no_faktur=P.no_faktur WHERE (" . $cond . ") AND ((SPM.tanggal BETWEEN '" . $start . "' AND '" . $end . "') OR (PBM.tanggal BETWEEN '" . $start . "' AND '" . $end . "') OR (P.tgl_pesan BETWEEN '" . $start . "' AND '" . $end . "'))";
                $getPOItem = mysqli_query($connect_app, $qGetPOItem);
                $lstPOItem = "";
                while ($data = mysqli_fetch_assoc($getPOItem)) {
                    $lstPOItem .= "<tr><td>" . $data["kode_brng"] . "</td><td>" . addslashes($data["nama_brng"]) . "</td><td>" . $data["satuan_kecil"] . "</td><td>" . $data["satuan_besar"] . "</td><td class=\'num-input\'>" . number_format($data["isi"],0,",",".") . "</td><td>" . (strlen($data["tgl_pengajuan"]) ? $data["tgl_pengajuan"] : "-") . "</td><td>" . (strlen($data["no_pr_ref"]) ? $data["no_pr_ref"] : "-") . "</td><td>" . (strlen($data["jml_pengajuan"]) ? number_format($data["jml_pengajuan"],0,",",".") . " " . $data["satuan_besar"] : "-") . "</td><td>" . (strlen($data["tanggal_disetujui"]) ? $data["tanggal_disetujui"] : "-") . "</td><td>" . (strlen($data["jumlah_disetujui"]) ? number_format($data["jumlah_disetujui"], 0, ",", ".") . " " . $data["satuan_besar"] : "-") . "</td><td>" . $data["tgl_pesan"] . "</td><td>" . $data["no_pemesanan"] . "</td><td>" . number_format($data["jml_pesan"],0,",",".") . " " . $data["satuan_besar"] . "</td><td>" . $data["nama_suplier"] . "</td><td>" . (strlen($data["tgl_penerimaan"]) ? $data["tgl_penerimaan"] : "-") . "</td><td>" . (strlen($data["no_faktur"]) ? $data["no_faktur"] : "-") . "</td><td>" . (strlen($data["no_faktur_supplier"]) ? $data["no_faktur_supplier"] : "-") . "</td><td>" . (strlen($data["jml_penerimaan"]) ? number_format($data["jml_penerimaan"],0,",",".") . " " . $data["satuan_besar"] : "-") . "</td><td class=\'num-input\'>" . (strlen($data["h_pesan"]) ? number_format($data["h_pesan"],0,",",".") : "-") . "</td><td class=\'num-input\'>" . (strlen($data["subtotal"]) ? number_format($data["subtotal"],0,",",".") : "-") . "</td><td class=\'num-input\'>" . (strlen($data["dis"]) ? number_format($data["dis"],0,",",".") : "-") . "</td><td class=\'num-input\'>" . (strlen($data["dis2"]) ? number_format($data["dis2"],0,",",".") : "-") . "</td><td class=\'num-input\'>" . (strlen($data["besardis"]) ? number_format($data["besardis"],0,",",".") : "-") . "</td><td class=\'num-input\'>" . (strlen($data["total"]) ? number_format($data["total"],0,",",".") : "-") . "</td><td>" . (strlen($data["no_batch"]) ? $data["no_batch"] : "-") . "</td><td>" . (strlen($data["kadaluarsa"]) ? $data["kadaluarsa"] : "-") . "</td><td>" . (strlen($data["is_ppn"]) ? ($data["is_ppn"] == "1" ? "Ya" : "Tidak") : "-") . "</td></tr>";
                }
                if (!strlen($lstPOItem)) $lstPOItem = "<tr><td colspan=\'27\' style=\'text-align: center\'>--- Tidak ada data ---</td></tr>";
                $lstPOItem = "<thead><tr><th rowspan=\'2\' style=\'min-width: 80px\'>Kode</th><th rowspan=\'2\' style=\'min-width: 200px\'>Nama Barang</th><th rowspan=\'2\' title=\'Satuan Kecil\' style=\'min-width: 70px\'>SK</th><th rowspan=\'2\' title=\'Satuan Besar\' style=\'min-width: 70px\'>SB</th><th rowspan=\'2\' style=\'min-width: 40px\'>Isi</th><th class=\'alternate-hdr\' colspan=\'5\'>Pengajuan</th><th colspan=\'4\'>Pemesanan</th><th class=\'alternate-hdr\' colspan=\'13\'>Penerimaan</th></tr><tr><th class=\'alternate-hdr\' style=\'min-width: 85px\'>Tanggal</th><th class=\'alternate-hdr\' style=\'min-width: 120px\'>No. Pengajuan</th><th class=\'alternate-hdr\' style=\'min-width: 90px\'>Jumlah</th><th class=\'alternate-hdr\' style=\'min-width: 85px\'>Tanggal Disetujui</th><th class=\'alternate-hdr\' style=\'min-width: 90px\'>Jumlah Disetujui</th><th style=\'min-width: 85px\'>Tanggal</th><th style=\'min-width: 120px\'>No. Pemesanan</th><th style=\'min-width: 90px\'>Jumlah</th><th style=\'min-width: 200px\'>Supplier</th><th class=\'alternate-hdr\' style=\'min-width: 85px\'>Tanggal</th><th class=\'alternate-hdr\' style=\'min-width: 120px\'>No. Penerimaan</th><th class=\'alternate-hdr\' style=\'min-width: 140px\'>No. Faktur Supplier</th><th class=\'alternate-hdr\' style=\'min-width: 90px\'>Jumlah</th><th class=\'alternate-hdr\' style=\'min-width: 90px\'>Harga Per SB</th><th class=\'alternate-hdr\' style=\'min-width: 90px\'>Subtotal</th><th class=\'alternate-hdr\' style=\'min-width: 70px\'>Diskon 1 (%)</th><th class=\'alternate-hdr\' style=\'min-width: 70px\'>Diskon 2 (%)</th><th class=\'alternate-hdr\' style=\'min-width: 90px\'>Total Diskon</th><th class=\'alternate-hdr\' style=\'min-width: 90px\'>Total</th><th class=\'alternate-hdr\' style=\'min-width: 100px\'>No. Batch</th><th class=\'alternate-hdr\' style=\'min-width: 70px\'>Kadaluarsa</th><th class=\'alternate-hdr\' style=\'min-width: 40px\'>PPN</th></tr></thead><tbody>" . $lstPOItem . "</tbody>";
                echo "<script>
                    $('#div-scroll').slideUp(500);
                    setTimeout(function() {
                        $('#tbl_listItems').html('" . $lstPOItem . "');
                        $('#div-scroll').slideDown(500);
                    }, 500);
                </script>";
                break;
            case "rcpmlist":
                $supplier = $_GET["supplier"];
                $start = $_GET["start"];
                $end = $_GET["end"];
                $qGetReception = "SELECT no_faktur, no_faktur_supplier, nama_suplier, tgl_pesan, tgl_faktur, PT.nama, P.status, P.catatan FROM pemesanan P INNER JOIN datasuplier S ON P.kode_suplier=S.kode_suplier INNER JOIN petugas PT ON P.nip=PT.nip WHERE tgl_pesan BETWEEN '" . $start . "' AND '" . $end . "'" . (strlen($supplier) ? " AND P.kode_suplier='" . $supplier . "'" : "") . " ORDER BY tgl_pesan, no_faktur";
                $getReception = mysqli_query($connect_app, $qGetReception);
                $lstReception = "";
                while ($data = mysqli_fetch_assoc($getReception)) {
                    switch ($data["status"]) {
                        case "Belum Dibayar": $status = "<i class=\'fa fa-star-o text-green\' title=\'Belum Dibayar\'></i>"; break;
                        case "Belum Lunas": $status = "<i class=\'fa fa-star-half-o text-green\' title=\'Belum Lunas\'></i>"; break;
                        case "Sudah Lunas": $status = "<i class=\'fa fa-star text-green\' title=\'Sudah Lunas\'></i>"; break;
                        default: $status = ""; break;
                    }
                    $lstReception .= "<tr><td>" . $data["no_faktur"] . " <span id=\'chevron_" . $data["no_faktur"] . "\' class=\'chevron\' title=\'Klik untuk menampilkan/menyembunyikan detail penerimaan\' onClick=\'toggleRcpDetail(&quot;" . $data["no_faktur"] . "&quot;)\'><i class=\'fa fa-chevron-circle-down\'></i></span></td><td>" . $data["tgl_pesan"] . "</td><td>" . $data["no_faktur_supplier"] . "</td><td>" . $data["nama_suplier"] . "</td><td>" . $data["tgl_faktur"] . "</td><td>" . $data["nama"] . "</td><td>" . str_replace("\n", "", str_replace("\r", "", $data["catatan"])) . "</td><td>" . $status . "</td><td><a class=\'btn btn-primary btn-sm\' title=\'Ubah\' href=\'edit_po_reception_m.php?id=" . $data["no_faktur"] . "\'><i class=\'fa fa-edit\'></i></a></td></tr><tr id=\'tr_" . $data["no_faktur"] . "\' style=\'display: none\'><td id=\'td_" . $data["no_faktur"] . "\' class=\'td-detail\' colspan=\'9\'>-</td></tr>";
                }
                if (!strlen($lstReception)) $lstReception = "<tr><td colspan=\'9\' style=\'text-align: center\'>--- Tidak ada data ---</td></tr>";
                $lstReception = "<thead><tr><th>No. Penerimaan</th><th>Tanggal Penerimaan</th><th>No. Faktur</th><th>Supplier</th><th>Tanggal Faktur</th><th>Petugas</th><th>Catatan</th><th>Status</th><th>Aksi</th></tr></thead><tbody>" . $lstReception . "</tbody>";
                echo "<script>$('#tbl_reception').html('" . $lstReception . "');</script>";
                break;
            case "rcpmdetail":
                if (isset($_GET["id"])) {
                    $qGetRcpDetail = "SELECT DP.kode_brng, nama_brng, no_pemesanan, SK.satuan AS satuan_kecil, SB.satuan AS satuan_besar, DP.isi, jumlah, h_pesan, subtotal, dis, dis2, besardis, total, no_batch, kadaluarsa FROM detailpesan DP INNER JOIN databarang B ON DP.kode_brng=B.kode_brng INNER JOIN kodesatuan SK ON DP.kode_sat=SK.kode_sat INNER JOIN kodesatuan SB ON DP.kode_satbesar=SB.kode_sat WHERE no_faktur='" . $_GET["id"] . "'";
                    $getRcpDetail = mysqli_query($connect_app, $qGetRcpDetail);
                    $rcpDetail = ""; $i = 0;
                    while ($data = mysqli_fetch_assoc($getRcpDetail)) {
                        $rcpDetail .= "<tr><td>" . ++$i . "</td><td>" . $data["kode_brng"] . "</td><td>" . $data["nama_brng"] . "</td><td>" . $data["no_pemesanan"] . "</td><td>" . $data["satuan_kecil"] . "</td><td>" . $data["satuan_besar"] . "</td><td class=\'num-input\'>" . $data["isi"] . "</td><td class=\'num-input\'>" . number_format($data["jumlah"], 0, ",", ".") . " " . $data["satuan_besar"] . "</td><td class=\'num-input\'>" . number_format($data["h_pesan"], 0, ",", ".") . "</td><td class=\'num-input\'>" . number_format($data["subtotal"], 0, ",", ".") . "</td><td class=\'num-input\'>" . number_format($data["dis"], 0, ",", ".") . "</td><td class=\'num-input\'>" . number_format($data["dis2"], 0, ",", ".") . "</td><td class=\'num-input\'>" . number_format($data["besardis"], 0, ",", ".") . "</td><td class=\'num-input\'>" . number_format($data["total"], 0, ",", ".") . "</td><td>" . $data["no_batch"] . "</td><td>" . $data["kadaluarsa"] . "</td></tr>";
                    }
                    $rcpDetail = "<table class=\'table\'><tr><th>No</th><th>Kode</th><th>Nama Barang</th><th>No. Pemesanan</th><th title=\'Satuan Kecil\'>SK</th><th title=\'Satuan Besar\'>SB</th><th>Isi</th><th>Jumlah Datang</th><th>Harga Per SB</th><th>Subtotal</th><th>Diskon 1 (%)</th><th>Diskon 2 (%)</th><th>Total Diskon</th><th>Total</th><th>No. Batch</th><th>Kadaluarsa</th></tr>" . $rcpDetail . "</table>";
                    echo "<script>$('#td_" . $_GET["id"] . "').html('" . $rcpDetail . "');</script>";
                }
                break;
            case "reqnmlist":
                require_once "../function/access.php";
                $start = $_GET["start"];
                $end = $_GET["end"];
                $from = (isset($_GET["from"]) ? $_GET["from"] : "");
                $to = (isset($_GET["to"]) ? $_GET["to"] : "");
                $mode = (isset($_GET["mode"]) ? $_GET["mode"] : "");
                if ($mode == "popup") {
                    $tblhead1 = "";
                    $tblhead2 = "";
                    $colspan = 4;
                } else {
                    $tblhead1 = "<th style='width: 160px'>Asal Permintaan</th><th style='width: 160px'>Ditujukan Ke</th>";
                    $tblhead2 = "<th style='width: 85px'>Aksi</th>";
                    $colspan = 7;
                }
                $qGetRequest = "SELECT no_permintaan, P.nip, P.nama, tanggal, BD.nm_bangsal AS bangsaldari, BK.nm_bangsal AS bangsalke, PNM.status FROM permintaan_non_medis PNM INNER JOIN petugas P ON PNM.nip=P.nip INNER JOIN bangsal BD ON PNM.kd_bangsal=BD.kd_bangsal INNER JOIN bangsal BK ON PNM.kd_bangsaltujuan=BK.kd_bangsal WHERE tanggal BETWEEN '" . $start . "' AND '" . $end . "'" . (strlen($from) ? " AND PNM.kd_bangsal='" . $from . "'" : "") . (strlen($to) ? " AND kd_bangsaltujuan='" . $to . "'" : "") . (!getAccess("rekap_permintaan_non_medis") ? " AND PNM.nip='" . $_SESSION["USER"]["USERNAME"] . "'" : "") . " ORDER BY no_permintaan";
                $getRequest = mysqli_query($connect_app, $qGetRequest);
                $lstRequest = "";
                while ($data = mysqli_fetch_assoc($getRequest)) {
                    $btnEdit = ""; $btnAct = ""; $btnKonfirmasi = "";
                    switch ($data["status"]) {
                        case "Baru":
                            $status = "<i class=\'fa fa-plus-square text-blue\' title=\'Baru\'></i>";
                            $btnEdit = "<a class=\'btn btn-primary btn-sm\' href=\'edit_item_request_nm.php?id=" . $data["no_permintaan"] . "\' title=\'Ubah\'><i class=\'fa fa-edit\'></i></a> ";
                            $btnAct = "<a class=\'btn btn-success btn-sm\' href=\'add_item_transfer_nm.php?req=" . $data["no_permintaan"] . "\' title=\'Mutasi\'><i class=\'fa fa-arrow-right\'></i></a>";
                            $btnKonfirmasi = "<a class=\'btn btn-sm bg-purple\' href=\'#\' onClick=\'confirmRequest(&quot;" . $data["no_permintaan"] . "&quot;)\' title='Dikonfirmasi'><i class='fa fa-check-square-o'></i></a> ";
                        break;
                        case "Dikonfirmasi":
                            $status = "<i class='fa fa-check-square-o text-purple' title='Dikonfirmasi'></i>";
                            $btnAct = "<a class='btn btn-success btn-sm' href='add_item_transfer_nm.php?req=" . $data["no_permintaan"] . "' title='Mutasi'><i class='fa fa-arrow-right'></i></a>";
                            break;
                        case "Disetujui Sebagian":
                            $status = "<i class=\'fa fa-list text-blue\' title=\'Disetujui Sebagian\'></i>";
                            $btnAct = "<a class=\'btn btn-success btn-sm\' href=\'add_item_transfer_nm.php?req=" . $data["no_permintaan"] . "\' title=\'Mutasi\'><i class=\'fa fa-arrow-right\'></i></a>";
                            break;
                        case "Disetujui": $status = "<i class=\'fa fa-check text-green\' title=\'Disetujui\'></i>"; break;
                        case "Tidak Disetujui": $status = "<i class=\'fa fa-minus-circle text-red\' title=\'Tidak Disetujui\'></i>"; break;
                        default: $status = ""; break;
                    }
                    if ($mode == "popup") {
                        $tbldata1 = "";
                        $tbldata2 = "";
                    } else {
                        $tbldata1 = "<td>" . $data["bangsaldari"] . "</td><td>" . $data["bangsalke"] . "</td>";
                        $tbldata2 = "<td>" . ($data["nip"] == $_SESSION["USER"]["USERNAME"] ? $btnEdit : "") . (getAccess("rekap_permintaan_non_medis") ? $btnKonfirmasi .  $btnAct : "") . "</td>";
                    }
                    $lstRequest .= "<tr><td>" . $data["no_permintaan"] . " <span id='chevron_" . $data["no_permintaan"] . "' class='chevron' title='Klik untuk menampilkan/menyembunyikan detail permintaan' onClick='toggleRequestDetail(&quot;" . $data["no_permintaan"] . "&quot;)'><i class='fa fa-chevron-circle-down'></i></span></td><td>" . $data["tanggal"] . "</td>" . $tbldata1 . "<td>" . $data["nama"] . "</td><td>" . $status . "</td>" . $tbldata2 . "</tr><tr id='tr_" . $data["no_permintaan"] . "' style='display: none'><td id='td_" . $data["no_permintaan"] . "' class='td-detail' colspan='" . $colspan . "'>-</td></tr>";
                }
                if (!strlen($lstRequest)) $lstRequest = "<tr><td colspan='" . $colspan . "' style='text-align: center'>--- Tidak ada data ---</td></tr>";
                $lstRequest = "<thead><th style='width: 115px'>No. Permintaan</th><th style='width: 85px'>Tanggal</th>" . $tblhead1 . "<th style='width: 200px'>Petugas</th><th style='width: 60px'>Status</th>" . $tblhead2 . "</thead><tbody>" . $lstRequest . "</tbody>";
                echo '<script>$("#tbl_itemrequest").html("' . $lstRequest . '")</script>';
                break;
            case "reqnmdtl":
                if (isset($_GET["id"])) {
                    if (isset($_GET["data"])) $selItems = base64_decode($_GET["data"]);
                        else $selItems = "";
                    $mode = (isset($_GET["mode"]) ? $_GET["mode"] : "");
                    if ($mode == "popup")
                        $tblhead = "<th style='width: 45px'>Aksi</th>";
                    else $tblhead = "";
                    $qGetRequestDtl = "SELECT DPNM.kode_brng, nama_brng, satuan, jumlah, keterangan, DPNM.status FROM detail_permintaan_non_medis DPNM INNER JOIN permintaan_non_medis PNM ON DPNM.no_permintaan=PNM.no_permintaan INNER JOIN ipsrsbarang B ON DPNM.kode_brng=B.kode_brng INNER JOIN kodesatuan S ON DPNM.kode_sat=S.kode_sat WHERE PNM.no_permintaan='" . $_GET["id"] . "' ORDER BY DPNM.kode_brng";
                    $getRequestDtl = mysqli_query($connect_app, $qGetRequestDtl);
                    $requestDtl = ""; $i = 0;
                    while ($data = mysqli_fetch_assoc($getRequestDtl)) {
                        switch ($data["status"]) {
                            case "Proses Permintaan": $status = "<i class=\'fa fa-spinner text-blue\' title=\'Proses Permintaan\'></i>"; break;
                            case "Sudah Diproses": $status = "<i class=\'fa fa-check text-green\' title=\'Sudah Diproses\'></i>"; break;
                            default: $status = ""; break;
                        }
                        if ($mode == "popup")
                            $tbldata = "<td>" . ($data["status"] != "Sudah Diproses" ? (strpos($selItems, $data["kode_brng"] . "_" . $_GET["id"]) === FALSE ? "<input type='checkbox' id='chk_" . $data["kode_brng"] . "_" . $_GET["id"] . "_" . $data["jumlah"] . "' name='chk_" . $data["kode_brng"] . "_" . $_GET["id"] . "_" . $data["jumlah"] . "' onClick='activateBtnPilih()'>" : "<i class='fa fa-check-square-o text-green' title='Sudah ditambahkan'></i>") : "") . "</td>";
                        else $tbldata = "";
                        $requestDtl .= "<tr><td>" . ++$i . "</td><td>" . $data["kode_brng"] . "</td><td>" . str_replace('"', '\"', $data["nama_brng"]) . "</td><td>" . number_format($data["jumlah"], 2, ",", ".") . " " . $data["satuan"] . "</td><td>" . $data["keterangan"] . "</td><td>" . $status . "</td>" . $tbldata . "</tr>";
                    }
                    $requestDtl = "<table class='table'><tr><th style='width: 35px'>No</th><th style='width: 75px'>Kode</th><th style='width: 250px'>Nama Barang</th><th style='width: 100px'>Jumlah</th><th style='width: 150px'>Keterangan</th><th style='width: 60px'>Status</th>" . $tblhead . "</tr>" . $requestDtl . "</table>";
                    echo '<script>$("#td_' . $_GET["id"] . '").html("' . $requestDtl . '")</script>';
                }
                break;
            case "reqnmadd":
                if (isset($_GET["term"])) {
                    $term = $_GET["term"];
                    $data = base64_decode($_GET["data"]);
                    $qGetItem = "SELECT kode_brng, nama_brng FROM ipsrsbarang WHERE kode_brng NOT IN ('" . str_replace(",", "','", $data) . "') AND (kode_brng LIKE '%" . $term . "%' OR nama_brng LIKE '%" . $term . "%') LIMIT 10";
                    $getItem = mysqli_query($connect_app, $qGetItem);
                    $lstItem = ""; $i = 0;
                    while ($data = mysqli_fetch_assoc($getItem)) {
                        $i++;
                        $onClickAct = 'ajax_getData(\"ajax_getdata.php?type=reqnmadd&from=' . $_GET["from"] . '&frward=' . $_GET["frward"] . '&id=' . $data["kode_brng"] . '\")';
                        $lstItem .= "<li onClick='" . $onClickAct . "'>" . str_replace('"', '\"', $data["nama_brng"]) . " [" . $data["kode_brng"] . "]</li>";
                    }
                    if (strlen($lstItem))
                        $lstItem = "<div id='suggest-item' class='suggestion'><ul>" . $lstItem . "</ul></div>";
                    else {
                        $lstItem = "<div id='suggest-item' class='suggestion'><ul><li onClick='clearInput(&quot&" . $_GET["from"] . "quot;); removeSuggest()'>Tidak ada saran</li></ul></div>";
                        $i = 1;
                    }
                    echo '<script>
                        removeSuggest();
                        elm = $("#inp_' . $_GET["from"] . '");
                        posX = elm.position().left;
                        posY = elm.position().top + elm.outerHeight();
                        zIndex = 100;
                        listItem = "' . $lstItem . '";
                        $(".content-wrapper").append(listItem);
                        $("#suggest-item").css({"top":posY, "left":posX, "height":' . ($i*24 + 2) . ', "zIndex":zIndex});
                    </script>';
                } else if (isset($_GET["id"])) {
                    $qGetItem = "SELECT B.kode_brng, nama_brng, B.kode_sat, satuan, nm_jenis, IfNull(Sum(G.stok), 0) AS stok_asal FROM ipsrsbarang B INNER JOIN kodesatuan S ON B.kode_sat=S.kode_sat INNER JOIN ipsrsjenisbarang J ON B.jenis=J.kd_jenis LEFT JOIN gpi_gudangbarangipsrs G ON B.kode_brng=G.kode_brng WHERE B.kode_brng='" . $_GET["id"] . "' AND kd_bangsal='" . $_GET["frward"] . "'";
                    $getItem = mysqli_query($connect_app, $qGetItem);
                    $item = mysqli_fetch_assoc($getItem);
                    $rowItem = "$('#tr_" . $item["kode_brng"] . "').html('<td>" . $item["kode_brng"] . "</td><td>" . addslashes($item["nama_brng"]) . "</td><td>" . $item["nm_jenis"] . "</td><td style=\'width: 60px\'><input type=\'text\' id=\'inp_jml_" . $item["kode_brng"] . "\' name=\'inp_jml_" . $item["kode_brng"] . "\' class=\'form-control num-input\' value=\'0\' onKeyUp=\'removeNonNumeric(&quot;inp_jml_" . $item["kode_brng"] . "&quot;); recheckRequest(&quot;N&quot;)\' maxlength=\'5\' autocomplete=\'off\' style=\'width: 60px\'></td><td>" . $item["satuan"] . "<input type=\'hidden\' id=\'hdn_sat_" . $item["kode_brng"] . "\' name=\'hdn_sat_" . $item["kode_brng"] . "\' value=\'" . $item["kode_sat"] . "\'></td><td><input type=\'text\' id=\'inp_keterangan_" . $item["kode_brng"] . "\' name=\'inp_keterangan_" . $item["kode_brng"] . "\' class=\'form-control\' autocomplete=\'off\' style=\'width: 150px\'></td><td class=\'num-input\'>" . $item["stok_asal"] . "</td><td><a class=\'btn btn-danger btn-sm\' title=\'Hapus\' href=\'#\' onClick=\'if(confirm(&quot;Apakah Anda yakin ingin menghapus data ini?&quot;)) removeItem(&quot;" . $item["kode_brng"] . "&quot;)\'><i class=\'fa fa-trash\'></i></a></td>');";
                    echo "<script>
                        $('#tr_" . $_GET["from"] . "').attr('id', 'tr_" . $item["kode_brng"] . "');" . $rowItem . "
                        appendItems('Request', '" . $item["kode_brng"] . "');
                    </script>";
                }
                break;
            case "transfernmlist":
                $from = $_GET["from"];
                $to = $_GET["to"];
                $start = $_GET["start"];
                $end = $_GET["end"];
                $qGetTransfer = "SELECT M.kode_brng, nama_brng, satuan, jml, BD.nm_bangsal AS bangsaldari, BK.nm_bangsal AS bangsalke, tanggal, keterangan FROM gpi_mutasibarangipsrs M INNER JOIN ipsrsbarang B ON M.kode_brng=B.kode_brng INNER JOIN bangsal BD ON BD.kd_bangsal=M.kd_bangsaldari INNER JOIN bangsal BK ON BK.kd_bangsal=M.kd_bangsalke INNER JOIN kodesatuan S ON B.kode_sat=S.kode_sat WHERE tanggal between '" . $start . " 00:00:00' AND '" . $end . " 23:59:59'" . (strlen($from) ? " AND kd_bangsaldari='" . $from . "'" : "") . (strlen($to) ? " AND kd_bangsalke='" . $to . "'" : "") . " ORDER BY tanggal, bangsaldari, M.kode_brng";
                $getTransfer = mysqli_query($connect_app, $qGetTransfer);
                $lstTransfer = "";
                while ($data = mysqli_fetch_assoc($getTransfer)) {
                    $lstTransfer .= "<tr><td>" . $data["tanggal"] . "</td><td>" . $data["bangsaldari"] . "</td><td>" . $data["bangsalke"] . "</td><td>" . $data["kode_brng"] . "</td><td>" . addslashes($data["nama_brng"]) . "</td><td>" . number_format($data["jml"], 2, ",", ".") . " " . $data["satuan"] . "</td><td>" . $data["keterangan"] . "</td></tr>";
                }
                if (!strlen($lstTransfer)) $lstTransfer = "<tr><td colspan=\'7\' style=\'text-align: center\'>--- Tidak ada data ---</td></tr>";
                $lstTransfer = "<thead><tr><th style=\'width: 132px\'>Tanggal</th><th style=\'width: 160px\'>Asal Mutasi</th><th style=\'width: 160px\'>Tujuan Mutasi</th><th style=\'width: 100px\'>Kode</th><th>Nama Barang</th><th style=\'width: 100px\'>Jumlah</th><th style=\'width: 175px\'>Keterangan</th></tr></thead><tbody>" . $lstTransfer . "</tbody>";
                echo "<script>$('#tbl_itemtransfer').html('" . $lstTransfer . "');</script>";
                break;
            case "transfernmadd":
                if (isset($_GET["term"])) {
                    $term = $_GET["term"];
                    $data = base64_decode($_GET["data"]);
                    $qGetItem = "SELECT kode_brng, nama_brng FROM ipsrsbarang WHERE kode_brng NOT IN ('" . str_replace(",", "','", $data) . "') AND (kode_brng LIKE '%" . $term . "%' OR nama_brng LIKE '%" . $term . "%') LIMIT 10";
                    $getItem = mysqli_query($connect_app, $qGetItem);
                    $lstItem = ""; $i = 0;
                    while ($data = mysqli_fetch_assoc($getItem)) {
                        $i++;
                        $onClickAct = 'ajax_getData(\"ajax_getdata.php?type=transfernmadd&from=' . $_GET["from"] . '&frward=' . $_GET["frward"] . '&toward=' . $_GET["toward"] . '&id=' . $data["kode_brng"] . '\")';
                        $lstItem .= "<li onClick='" . $onClickAct . "'>" . str_replace('"', '\"', $data["nama_brng"]) . " [" . $data["kode_brng"] . "]</li>";
                    }
                    if (strlen($lstItem))
                        $lstItem = "<div id='suggest-item' class='suggestion'><ul>" . $lstItem . "</ul></div>";
                    else {
                        $lstItem = "<div id='suggest-item' class='suggestion'><ul><li onClick='clearInput(&quot;" . $_GET["from"] . "&quot;); removeSuggest()'>Tidak ada saran</li></ul></div>";
                        $i = 1;
                    }
                    echo '<script>
                        removeSuggest();
                        elm = $("#inp_' . $_GET["from"] . '");
                        posX = elm.position().left;
                        posY = elm.position().top + elm.outerHeight();
                        zIndex = 100;
                        listItem = "' . $lstItem . '";
                        $(".content-wrapper").append(listItem);
                        $("#suggest-item").css({"top":posY, "left":posX, "height":' . ($i*24 + 2) . ', "zIndex":zIndex});
                    </script>';
                } else if (isset($_GET["id"])) {
                    $qGetItem = "SELECT B.kode_brng, nama_brng, SK.satuan AS satuan_kecil, SB.satuan AS satuan_besar, B.isi, dasar, IfNull(Sum(GA.stok), 0) AS stok_asal, IfNull(Sum(GT.stok), 0) AS stok_tujuan FROM ipsrsbarang B INNER JOIN kodesatuan SK ON B.kode_sat=SK.kode_sat INNER JOIN kodesatuan SB ON B.kode_satbesar=SB.kode_sat LEFT JOIN gpi_gudangbarangipsrs GA ON B.kode_brng=GA.kode_brng AND GA.kd_bangsal='" . $_GET["frward"] . "' LEFT JOIN gpi_gudangbarangipsrs GT ON B.kode_brng=GT.kode_brng AND GT.kd_bangsal='" . $_GET["toward"] . "' WHERE B.kode_brng='" . $_GET["id"] . "'";
                    $getItem = mysqli_query($connect_app, $qGetItem);
                    $item = mysqli_fetch_assoc($getItem);
                    $rowItem = "$('#tr_" . $item["kode_brng"] . "').html('<td>" . $item["kode_brng"] . "</td><td>" . addslashes($item["nama_brng"]) . "<input type=\'hidden\' id=\'hdn_dasar_" . $item["kode_brng"] . "\' name=\'hdn_dasar_" . $item["kode_brng"] . "\' value=\'" . $item["dasar"] . "\'></td><td>" . $item["satuan_kecil"] . "</td><td>" . $item["satuan_besar"] . "</td><td class=\'num-input\'>" . number_format($item["isi"], 0, ",", ".") . "</td><td class=\'num-input\'>" . number_format($item["stok_asal"], 2, ",", ".") . " " . $item["satuan_kecil"] . "<input type=\'hidden\' id=\'hdn_stokasal_" . $item["kode_brng"] . "\' name=\'hdn_stokasal_" . $item["kode_brng"] . "\' value=\'" . $item["stok_asal"] . "\'></td><td class=\'num-input\'>" . number_format($item["stok_tujuan"], 2, ",", ".") . " " . $item["satuan_kecil"] . "<input type=\'hidden\' id=\'hdn_stoktujuan_" . $item["kode_brng"] . "\' name=\'hdn_stoktujuan_" . $item["kode_brng"] . "\' value=\'" . $item["stok_tujuan"] . "\'></td><td style=\'width: 60px\'><input type=\'text\' id=\'inp_jml_" . $item["kode_brng"] . "\' name=\'inp_jml_" . $item["kode_brng"] . "\' class=\'form-control num-input\' value=\'0\' onKeyUp=\'removeNonNumeric(&quot;inp_jml_" . $item["kode_brng"] . "&quot;); recheckTransfer(&quot;N&quot;)\' maxlength=\'5\' autocomplete=\'off\' style=\'width: 60px\'></td><td style=\'width: 100px\'>" . $item["satuan_kecil"] . "</td><td>-</td><td><a class=\'btn btn-danger btn-sm\' title=\'Hapus\' href=\'#\' onClick=\'if(confirm(&quot;Apakah Anda yakin ingin menghapus data ini?&quot;)) removeItem(&quot;" . $item["kode_brng"] . "&quot;)\'><i class=\'fa fa-trash\'></i></a></td>')";
                    echo "<script>
                        $('#tr_" . $_GET["from"] . "').attr('id', 'tr_" . $item["kode_brng"] . "');" . $rowItem . "
                        appendItems('Transfer', '" . $item["kode_brng"] . "');
                    </script>";
                }
                break;
            case "sonmlist":
                $loc = $_GET["where"];
                $start = $_GET["start"];
                $end = $_GET["end"];
                $qGetSO = "SELECT tanggal, nm_bangsal, O.kode_brng, nama_brng, satuan, O.stok, O.real, selisih, lebih, keterangan FROM ipsrsopname O INNER JOIN ipsrsbarang B ON O.kode_brng=B.kode_brng INNER JOIN bangsal BS ON O.kd_bangsal=BS.kd_bangsal INNER JOIN kodesatuan S ON S.kode_sat=B.kode_sat WHERE tanggal BETWEEN '" . $start . "' AND '" . $end . "' ORDER BY tanggal, O.kd_bangsal, O.kode_brng";
                $getSO = mysqli_query($connect_app, $qGetSO);
                $lstSO = "";
                while ($data = mysqli_fetch_assoc($getSO)) {
                    $lstSO .= "<tr><td>" . $data["tanggal"] . "</td><td>" . $data["nm_bangsal"] . "</td><td>" . $data["kode_brng"] . "</td><td>" . $data["nama_brng"] . "</td><td>" . number_format($data["stok"], 2, ",", ".") . " " . $data["satuan"] . "</td><td>" . number_format($data["real"], 2, ",", ".") . " " . $data["satuan"] . "</td><td>" . number_format($data["selisih"], 2, ",", ".") . " " . $data["satuan"] . "</td><td>" . number_format($data["lebih"], 2, ",", ".") . " " . $data["satuan"] . "</td><td>" . $data["keterangan"] . "</td></tr>";
                }
                if (!strlen($lstSO)) $lstSO = "<tr><td colspan='9' style='text-align: center'>--- Tidak ada data ---</td></tr>";
                $lstSO = "<thead><tr><th style=\'width: 85px\'>Tanggal</td><th style=\'width: 160px\'>Lokasi</td><th style=\'width: 100px\'>Kode</td><th style=\'width: 250px\'>Nama Barang</td><th style=\'width: 70px\'>Stok</td><th style=\'width: 70px\'>Real</td><th style=\'width: 70px\'>Selisih</td><th style=\'width: 70px\'>Lebih</td><th style=\'width: 150px\'>Keterangan</td></tr></thead><tbody>" . $lstSO . "</tbody>";
                echo "<script>$('#tbl_stockopname').html('" . $lstSO . "')</script>";
                break;
            case "sonmadd":
                if (isset($_GET["term"])) {
                    $term = $_GET["term"];
                    $data = base64_decode($_GET["data"]);
                    $qGetItem = "SELECT kode_brng, nama_brng FROM ipsrsbarang WHERE kode_brng NOT IN ('" . str_replace(",", "','", $data) . "') AND (kode_brng LIKE '%" . $term . "%' OR nama_brng LIKE '%" . $term . "%') LIMIT 10";
                    $getItem = mysqli_query($connect_app, $qGetItem);
                    $lstItem = ""; $i = 0;
                    while ($data = mysqli_fetch_assoc($getItem)) {
                        $i++;
                        $onClickAct = 'ajax_getData(\"ajax_getdata.php?type=sonmadd&from=' . $_GET["from"] . '&loc=' . $_GET["loc"] . '&id=' . $data["kode_brng"] . '\")';
                        $lstItem .= "<li onClick='" . $onClickAct . "'>" . str_replace('"', '\"', $data["nama_brng"]) . " [" . $data["kode_brng"] . "]</li>";
                    }
                    if (strlen($lstItem))
                        $lstItem = "<div id='suggest-item' class='suggestion'><ul>" . $lstItem . "</ul></div>";
                    else {
                        $lstItem = "<div id='suggest-item' class='suggestion'><ul><li onClick='clearInput(&quot&" . $_GET["from"] . "quot;); removeSuggest()'>Tidak ada saran</li></ul></div>";
                        $i = 1;
                    }
                    echo '<script>
                        removeSuggest();
                        elm = $("#inp_' . $_GET["from"] . '");
                        posX = elm.position().left;
                        posY = elm.position().top + elm.outerHeight();
                        zIndex = 100;
                        listItem = "' . $lstItem . '";
                        $(".content-wrapper").append(listItem);
                        $("#suggest-item").css({"top":posY, "left":posX, "height":' . ($i*24 + 2) . ', "zIndex":zIndex});
                    </script>';
                } else if (isset($_GET["id"])) {
                    $qGetItem = "SELECT B.kode_brng, nama_brng, B.kode_sat, satuan, dasar, nm_jenis, IfNull(G.stok, 0) AS stok_asal FROM ipsrsbarang B INNER JOIN kodesatuan S ON B.kode_sat=S.kode_sat INNER JOIN ipsrsjenisbarang J ON B.jenis=J.kd_jenis LEFT JOIN gpi_gudangbarangipsrs G ON B.kode_brng=G.kode_brng AND kd_bangsal='" . $_GET["loc"] . "' WHERE B.kode_brng='" . $_GET["id"] . "'";
                    $getItem = mysqli_query($connect_app, $qGetItem);
                    $item = mysqli_fetch_assoc($getItem);
                    $qCheckJadwalSO = "SELECT tgl_mulai, tgl_selesai, daf_bangsal FROM gpi_jadwal_stokopname_nonmedis WHERE tgl_mulai<=CURDATE() AND tgl_selesai>=CURDATE() AND daf_bangsal LIKE '%" . $_GET["loc"] . "%' ORDER BY tgl_mulai LIMIT 1";
                    $checkJadwalSO = mysqli_query($connect_app, $qCheckJadwalSO);
                    $jadwalSO = mysqli_fetch_assoc($checkJadwalSO);
                    $qCheckInputSO = "SELECT 1 AS isinput FROM ipsrsopname WHERE kode_brng='" . $item["kode_brng"] . "' AND kd_bangsal='" . $_GET["loc"] . "' AND tanggal BETWEEN '" . $jadwalSO["tgl_mulai"] . "' AND '" . $jadwalSO["tgl_selesai"] . "'";
                    $checkInputSO = mysqli_query($connect_app, $qCheckInputSO);
                    $inputSO = mysqli_fetch_assoc($checkInputSO);
                    if ($inputSO["isinput"] == "1") {
                        $notif = "&nbsp;&nbsp;<span class=\'text-red\' title=\'Sudah ada input stok untuk barang ini\'><i class=\'fa fa-exclamation\'></i></span>";
                        $disabled = "disabled";
                    } else {
                        $notif = "";
                        $disabled = "";
                    }
                    $rowItem = "$('#tr_" . $item["kode_brng"] . "').html('<td>" . $item["kode_brng"] . "</td><td><span id=\'span_name_" . $item["kode_brng"] . "\'>" . addslashes($item["nama_brng"]) . "</span>" . $notif . "<input type=\'hidden\' id=\'hdn_isinput_" . $item["kode_brng"] . "\' name=\'hdn_isinput_" . $item["kode_brng"] . "\' value=\'" . ($inputSO["isinput"] == "1" ? "1" : "0") . "\'></td><td>" . $item["satuan"] . "</td><td class=\'num-input\'>" . number_format($item["dasar"], 0, ",", ".") . "<input type=\'hidden\' id=\'hdn_dasar_" . $item["kode_brng"] . "\' name=\'hdn_dasar_" . $item["kode_brng"] . "\' value=\'" . $item["dasar"] . "\'></td><td class=\'num-input\'>" . number_format($item["stok_asal"], 2, ",", ".") . " " . $item["satuan"] . "<input type=\'hidden\' id=\'hdn_stokasal_" . $item["kode_brng"] . "\' name=\'hdn_stokasal_" . $item["kode_brng"] . "\' value=\'" . $item["stok_asal"] . "\'></td><td style=\'width: 60px\'><input type=\'text\' id=\'inp_jml_" . $item["kode_brng"] . "\' name=\'inp_jml_" . $item["kode_brng"] . "\' class=\'form-control inline-input num-input\' style=\'width: 60px\' maxlength=\'5\' onKeyUp=\'removeNonNumeric(&quot;inp_jml_" . $item["kode_brng"] . "&quot;); recheckSO()\' autocomplete=\'off\' value=\'0\' " . $disabled . "></td><td>" . $item["satuan"] . "</td><td class=\'num-input\'><span id=\'span_selisih_" . $item["kode_brng"] . "\' name=\'span_selisih_" . $item["kode_brng"] . "\'>0</span> " . $item["satuan"] . "<input type=\'hidden\' id=\'hdn_selisih_" . $item["kode_brng"] . "\' name=\'hdn_selisih_" . $item["kode_brng"] . "\'></td><td class=\'num-input\'><span id=\'span_lebih_" . $item["kode_brng"] . "\' name=\'span_lebih_" . $item["kode_brng"] . "\'>0</span> " . $item["satuan"] . "<input type=\'hidden\' id=\'hdn_lebih_" . $item["kode_brng"] . "\' name=\'hdn_lebih_" . $item["kode_brng"] . "\'></td><td class=\'num-input\'><span id=\'span_nomhilang_" . $item["kode_brng"] . "\' name=\'span_nomhilang_" . $item["kode_brng"] . "\'>0</span><input type=\'hidden\' id=\'hdn_nomhilang_" . $item["kode_brng"] . "\' name=\'hdn_nomhilang_" . $item["kode_brng"] . "\'></td><td class=\'num-input\'><span id=\'span_nomlebih_" . $item["kode_brng"] . "\' name=\'span_nomlebih_" . $item["kode_brng"] . "\'>0</span><input type=\'hidden\' id=\'hdn_nomlebih_" . $item["kode_brng"] . "\' name=\'hdn_nomlebih_" . $item["kode_brng"] . "\'></td><td><a class=\'btn btn-danger btn-sm\' title=\'Hapus\' href=\'#\' onClick=\'if (confirm(&quot;Apakah Anda yakin ingin menghapus data ini?&quot;)) removeItem(&quot;" . $item["kode_brng"] . "&quot;)\'><i class=\'fa fa-trash\'></i></a></td>')";
                    echo "<script>
                        $('#tr_" . $_GET["from"] . "').attr('id', 'tr_" . $item["kode_brng"] . "');" . $rowItem . "
                        appendItems('SO', '" . $item["kode_brng"] . "');
                        recheckSO();
                    </script>";
                }
                break;
            case "prnmlist":
                $start = $_GET["start"];
                $end = $_GET["end"];
                $arg = ""; $act = ""; $hdr = "";
                $target = "tbl_purchaserequest";
                $mode = (isset($_GET["mode"]) ? $_GET["mode"] : "");
                if ($mode == "popup") {
                    $colspan = "5";
                    $status = "'Pengajuan'";
                    $field = "tanggal_disetujui";
                } else {
                    $field = "tanggal";
                    if (isset($_GET["approval"])) {
                        $colspan = "6";
                        $status = "'Proses Pengajuan'";
                        $arg = ", &quot;N&quot;";
                        $hdr = "<th style='width: 60px'>Aksi</th>";
                        $target = "tbl_pn";
                    } else {
                        $colspan = "5";
                        $status = "'Proses Pengajuan','Disetujui','Ditolak','Pengajuan'";
                    }
                }
                $qGetPR = "SELECT no_pengajuan, P.nama, " . $field . ", status, keterangan FROM pengajuan_barang_nonmedis PBN INNER JOIN pegawai P ON PBN.nip=P.nik WHERE " . $field . " BETWEEN '" . $start . "' AND '" . $end . "' AND PBN.status IN (" . $status . ") ORDER BY " . $field . " DESC, no_pengajuan DESC";
                $getPR = mysqli_query($connect_app, $qGetPR);
                $lstPR = "";
                while ($data = mysqli_fetch_assoc($getPR)) {
                    if (isset($_GET["approval"])) {
                        $act = "<td><a class='btn btn-success btn-sm' href='#' onClick='approvePR(&quot;" . $data["no_pengajuan"] . "&quot;, &quot;N&quot;)' title='Setujui'><i class='fa fa-check'></i></a></td>";
                    }
                    switch ($data["status"]) {
                        case "Proses Pengajuan": $status = "<i class='fa fa-list text-blue' title='Proses Pengajuan'></i>"; break;
                        case "Pengajuan": $status = "<i class='fa fa-angle-double-right text-blue' title='Pengajuan'></i>"; break;
                        case "Disetujui": $status = "<i class='fa fa-check text-green' title='Disetujui'></i>"; break;
                        case "Ditolak": $status = "<i class='fa fa-times text-red' title='Ditolak'></i>"; break;
                        default: $status = ""; break;
                    }
                    $qGetPRDtl = "SELECT kode_brng, jumlah FROM detail_pengajuan_barang_nonmedis WHERE no_pengajuan='" . $data["no_pengajuan"] . "'";
                    $getPRDtl = mysqli_query($connect_app, $qGetPRDtl);
                    $PRDtl = "";
                    while ($dtl = mysqli_fetch_assoc($getPRDtl)) {
                        $PRDtl .= (strlen($PRDtl) ? "|" : "") . $dtl["kode_brng"] . ":" . $dtl["jumlah"] . ":" . $dtl["jumlah"];
                    }
                    $lstPR .= "<tr id='tr_header_" . $data["no_pengajuan"] . "'><td>" . $data["no_pengajuan"] . " <span id='chevron_" . $data["no_pengajuan"] . "' class='chevron' title='Klik untuk menampilkan/menyembunyikan detail pengajuan' onClick='togglePRDetail(&quot;" . $data["no_pengajuan"] . "&quot;" . $arg . ")'><i class='fa fa-chevron-circle-down'></i></span></td><td>" . $data[$field] . "</td><td>" . $data["nama"] . "</td><td>" . addslashes($data["keterangan"]) . "</td><td>" . $status . "<input type='hidden' id='hdn_PRDtl_" . $data["no_pengajuan"] . "' name='hdn_PRDtl_" . $data["no_pengajuan"] . "' value='" . $PRDtl . "'></td>" . $act . "</tr><tr id='tr_" . $data["no_pengajuan"] . "' style='display: none'><td id='td_" . $data["no_pengajuan"] . "' class='td-detail' colspan='" . $colspan . "'>-</td></tr>";
                }
                if (!strlen($lstPR)) $lstPR = "<tr><td colspan='5' style='text-align: center'>--- Tidak ada data ---</td></tr>";
                $lstPR = "<thead><tr><th style='width: 150px'>No. Pengajuan</th><th style='width: 100px'>" . ($mode == "popup" ? "Tgl. Disetujui" : "Tanggal") . "</th><th style='width: 250px'>Yang Mengajukan</th><th>Catatan</th><th style='width: 60px'>Status</th>" . $hdr . "</tr></thead><tbody>" . $lstPR . "</tbody>";
                echo '<script>$("#' . $target . '").html("' . $lstPR . '")</script>';
                break;
            case "prnmdetail":
                if (isset($_GET["id"])) {
                    if (isset($_GET["data"])) $selItems = base64_decode($_GET["data"]);
                        else $selItems = "";
                    $mode = (Isset($_GET["mode"]) ? $_GET["mode"] : "");
                    $tblhead = (isset($_GET["approval"]) ? "<th style='min-width: 105px'>Jumlah Diajukan</th><th colspan='2' style='min-width: 105px'>Jumlah Disetujui</th>" : "<th style='min-width: 60px'>Jumlah</th>");
                    $tblhead2 = ($mode == "popup" ? "<th style='min-width: 60px'>Aksi</th>" : "");
                    $qGetPRDetail = "SELECT DPBN.kode_brng, nama_brng, satuan, jumlah, DPBN.status, jumlah_disetujui FROM detail_pengajuan_barang_nonmedis DPBN INNER JOIN ipsrsbarang B ON DPBN.kode_brng=B.kode_brng INNER JOIN kodesatuan S ON DPBN.kode_sat=S.kode_sat WHERE no_pengajuan='" . $_GET["id"] . "'";
                    $getPRDetail = mysqli_query($connect_app, $qGetPRDetail);
                    $PRDetail = ""; $i = 0;
                    while ($data = mysqli_fetch_assoc($getPRDetail)) {
                        if ($mode == "popup") {
                            $tbldata = "<td class='num-input'>" . number_format($data["jumlah_disetujui"], 2, ",", ".") . " " . $data["satuan"] . "</td>";
                        } else if (isset($_GET["approval"])) {
                            $tbldata = "<td class='num-input'>" . number_format($data["jumlah"], 2, ",", ".") . " " . $data["satuan"] . "</td><td class='num-input' style='width: 60px'><input type='text' id='inp_disetujui_" . $data["kode_brng"] . "_" . $_GET["id"] . "' name='inp_disetujui_" . $data["kode_brng"] . "_" . $_GET["id"] . "' class='form-control num-input' style='width: 75px' maxlength='7' value='" . number_format($data["jumlah_disetujui"], 2, ",", ".") . "' onKeyUp='removeNonNumeric(&quot;inp_disetujui_" . $data["kode_brng"] . "_" . $_GET["id"] . "&quot;); recheckPRApproval(&quot;" . $_GET["id"] . "&quot;, &quot;N&quot;)'></td><td style='width: 100px'>" . $data["satuan"] . "</td>";
                        } else {
                            $tbldata = "<td>" . number_format($data["jumlah"], 2, ",", ".") . " " . $data["satuan"] . "</td>";
                        }
                        switch ($data["status"]) {
                            case "Proses Pengajuan": $status = "<i class='fa fa-list text-blue' title='Proses Pengajuan'></i>"; break;
                            case "Disetujui": $status = "<i class='fa fa-check text-green' title='Disetujui'></i>"; break;
                            case "Ditolak": $status = "<i class='fa fa-times-circle-o text-red' title='Ditolak'></i>"; break;
                        }
                        $PRDetail .= "<tr><td>" . ++$i . "</td><td>" . $data["kode_brng"] . "</td><td>" . str_replace('"', '\"', $data["nama_brng"]) . "</td><td>" . $data["satuan"] . "</td>" . $tbldata . "<td>" . $status . "</td>" . ($mode == "popup" ? "<td>" . ($data["status"] == "Disetujui" ? "" : (strpos($selItems, $data["kode_brng"] . "_" . $_GET["id"]) === FALSE ? "<input type='checkbox' id='chk_" . $data["kode_brng"] . "_" . $_GET["id"] . "_" . $data["jumlah"] . "' name='chk_" . $data["kode_brng"] . "_" . $_GET["id"] . "_" . $data["jumlah"] . "' onClick='activateBtnPilih()'>" : "<i class='fa fa-check-square-o text-green' title='Sudah ditambahkan'></i>")) . "</td>" : "") . "</tr>";
                    }
                    $PRDetail = "<table class='table'><tr><th style='min-width: 30px'>No</th><th style='min-width: 60px'>Kode</th><th style='min-width: 475px'>Nama Barang</th><th style='min-width: 95px'>Satuan Besar</th>" . $tblhead . "<th style='min-width: 60px'>Status</th>" . $tblhead2 . "</tr>" . $PRDetail . "</table>";
                    echo '<script>$("#td_' . $_GET["id"] . '").html("' . $PRDetail . '"); /* activateBtnPilih(); */</script>';
                }
                break;
            case "prnmadd":
                if (isset($_GET["term"])) {
                    $term = $_GET["term"];
                    $data = base64_decode($_GET["data"]);
                    $qGetItem = "SELECT kode_brng, nama_brng FROM ipsrsbarang WHERE kode_brng NOT IN ('" . str_replace(",", "','", $data) . "') AND (kode_brng LIKE '%" . $term . "%' OR nama_brng LIKE '%" . $term . "%') LIMIT 10";
                    $getItem = mysqli_query($connect_app, $qGetItem);
                    $lstItem = ""; $i = 0;
                    while ($data = mysqli_fetch_assoc($getItem)) {
                        $i++;
                        $onClickAct = 'ajax_getData(\"ajax_getdata.php?type=prnmadd&from=' . $_GET["from"] . '&id=' . $data["kode_brng"] . '\")';
                        $lstItem .= "<li onClick='" . $onClickAct . "'>" . str_replace('"', '\"', $data["nama_brng"]) . " [" . $data["kode_brng"] . "]</li>";
                    }
                    if (strlen($lstItem))
                        $lstItem = "<div id='suggest-item' class='suggestion'><ul>" . $lstItem . "</ul></div>";
                    else {
                        $lstItem = "<div id='suggest-item' class='suggestion'><ul><li onClick='clearInput(&quot&" . $_GET["from"] . "quot;); removeSuggest()'>Tidak ada saran</li></ul></div>";
                        $i = 1;
                    }
                    echo '<script>
                        removeSuggest();
                        elm = $("#inp_' . $_GET["from"] . '");
                        posX = elm.position().left;
                        posY = elm.position().top + elm.outerHeight();
                        zIndex = 100;
                        listItem = "' . $lstItem . '";
                        $(".content-wrapper").append(listItem);
                        $("#suggest-item").css({"top":posY, "left":posX, "height":' . ($i*24 + 2) . ', "zIndex":zIndex});
                    </script>';
                } else if (isset($_GET["id"])) {
                    $qGetItem = "SELECT B.kode_brng, nama_brng, B.kode_satbesar, SK.satuan AS satuan_kecil, SB.satuan AS satuan_besar, isi, dasar*isi AS harga_sb, IfNull(Sum(G.stok), 0) AS stok_gudang FROM ipsrsbarang B INNER JOIN kodesatuan SK ON B.kode_sat=SK.kode_sat INNER JOIN kodesatuan SB ON B.kode_satbesar=SB.kode_sat LEFT JOIN gpi_gudangbarangipsrs G ON B.kode_brng=G.kode_brng WHERE B.kode_brng='" . $_GET["id"] . "' AND kd_bangsal='GUDNM'";
                    $getItem = mysqli_query($connect_app, $qGetItem);
                    $item = mysqli_fetch_assoc($getItem);
                    $rowItem = "$('#tr_" . $item["kode_brng"] . "').html('<td>" . $item["kode_brng"] . "</td><td>" . addslashes($item["nama_brng"]) . "</td><td>" . $item["satuan_kecil"] . "</td><td>" . $item["satuan_besar"] . "<input type=\'hidden\' id=\'hdn_satbesar_" . $item["kode_brng"] . "\' name=\'hdn_satbesar_" . $item["kode_brng"] . "\' value=\'" . $item["kode_satbesar"] . "\'></td><td class=\'num-input\'>" . number_format($item["isi"], 0, ",", ".") . "</td><td style=\'width: 60px\'><input type=\'text\' id=\'inp_jml_" . $item["kode_brng"] . "\' name=\'inp_jml_" . $item["kode_brng"] . "\' class=\'form-control num-input\' value=\'0\' onKeyUp=\'removeNonNumeric(&quot;inp_jml_" . $item["kode_brng"] . "&quot;); recalculatePR(&quot;N&quot;)\' maxlength=\'5\' autocomplete=\'off\' style=\'width: 60px\'></td><td>" . $item["satuan_besar"] . "</td><td class=\'num-input\'><span id=\'span_hpesan_" . $item["kode_brng"] . "\' name=\'span_hpesan_" . $item["kode_brng"] . "\'>" . number_format($item["harga_sb"], 0, ",", ".") . "</span><input type=\'hidden\' id=\'hdn_hpesan_" . $item["kode_brng"] . "\' name=\'hdn_hpesan_" . $item["kode_brng"] . "\' value=\'" . $item["harga_sb"] . "\'></td><td class=\'num-input\'><span id=\'span_subtotal_" . $item["kode_brng"] . "\' name=\'span_subtotal_" . $item["kode_brng"] . "\'>0</span><input type=\'hidden\' id=\'hdn_subtotal_" . $item["kode_brng"] . "\' name=\'hdn_subtotal_" . $item["kode_brng"] . "\'></td><td class=\'num-input\'>" . number_format($item["stok_gudang"], 0, ",", ".") . "</td><td><a class=\'btn btn-danger btn-sm\' title=\'Hapus\' href=\'#\' onClick=\'if (confirm(&quot;Apakah Anda yakin ingin menghapus data ini?&quot;)) removeItem(&quot;" . $item["kode_brng"] . "&quot;)\'><i class=\'fa fa-trash\'></i></a></td>')";
                    echo "<script>
                        $('#tr_" . $_GET["from"] . "').attr('id', 'tr_" . $item["kode_brng"] . "');" . $rowItem . "
                        appendItems('PR', '" . $item["kode_brng"] . "');
                        recalculatePR('N');
                    </script>";
                }
                break;
            case "minmaxnmunit":
                if (isset($_GET["term"])) {
                    $term = $_GET["term"];
                    $qGetUnit = "SELECT kd_bangsal, nm_bangsal FROM bangsal WHERE (kd_bangsal LIKE '%" . $term . "%' OR nm_bangsal LIKE '%" . $term . "%') AND status='1' LIMIT 10";
                    $getUnit = mysqli_query($connect_app, $qGetUnit);
                    $lstUnit = ""; $i = 0;
                    while ($data = mysqli_fetch_assoc($getUnit)) {
                        $i++;
                        $onClickAct = 'ajax_getData(\"ajax_getdata.php?type=minmaxnmunit&id=' . $data["kd_bangsal"] . '\")';
                        $lstUnit .= "<li onClick='" . $onClickAct . "'>" . str_replace('"', '\"', $data["nm_bangsal"]) . " [" . $data["kd_bangsal"] . "]</li>";
                    }
                    if (strlen($lstUnit)) {
                        $lstUnit = "<div id='suggest-unit' class='suggestion'><ul>" . $lstUnit . "</ul></div>";
                    } else {
                        $lstItem = "<div id='suggest-unit' class='suggestion'><ul><li onClick='clearInput(&quot;bangsal&quot;); removeSuggest()'>Tidak ada saran</li></ul></div>";
                        $i = 1;
                    }
                    echo '<script>
                        removeSuggest();
                        elm = $("#inp_bangsal");
                        posX = elm.position().left;
                        posY = elm.position().top + elm.outerHeight();
                        zIndex = 100;
                        listUnit = "' . $lstUnit . '";
                        $(".content-wrapper").append(listUnit);
                        $("#suggest-unit").css({"top":posY, "left":posX, "height":' . ($i*24 + 2) . ', "zIndex":zIndex});
                    </script>';
                } else if (isset($_GET["id"])) {
                    $qGetUnit = "SELECT nm_bangsal FROM bangsal WHERE kd_bangsal='" . $_GET["id"] . "'";
                    $getUnit = mysqli_query($connect_app, $qGetUnit);
                    $unit = mysqli_fetch_assoc($getUnit);
                    $qGetItems = "SELECT B.kode_brng, nama_brng, satuan, min_stok, max_stok, G.stok FROM gpi_minmax_nonmedis M INNER JOIN ipsrsbarang B ON M.kode_brng=B.kode_brng INNER JOIN kodesatuan S ON B.kode_sat=S.kode_sat LEFT JOIN gpi_gudangbarangipsrs G ON B.kode_brng=G.kode_brng AND M.kd_bangsal=G.kd_bangsal WHERE M.kd_bangsal='" . $_GET["id"] . "'";
                    $getItems = mysqli_query($connect_app, $qGetItems);
                    $lstItems = ""; $lstItem = [];
                    while ($data = mysqli_fetch_assoc($getItems)) {
                        $lstItems .= "<tr id=\'tr_" . $data["kode_brng"] . "\'><td>" . $data["nama_brng"] . " [" . $data["kode_brng"] . "]</td><td><span id=\'span_min_" . $data["kode_brng"] . "\'>" . number_format($data["min_stok"], 0, ",", ".") . "</span><input type=\'text\' id=\'inp_min_" . $data["kode_brng"] . "\' name=\'inp_min_" . $data["kode_brng"] . "\' class=\'form-control inline-input\' value=\'" . number_format($data["min_stok"], 0, ",", ".") . "\' maxlength=\'6\' style=\'width: 65px; display: none\' onKeyUp=\'removeNonNumeric(&quot;inp_min_" . $data["kode_brng"] . "&quot;);recheckMinMax(&quot;M&quot;);\'><input type=\'hidden\' id=\'hdn_min_" . $data["kode_brng"] . "\' name=\'hdn_min_" . $data["kode_brng"] . "\' value=\'" . $data["min_stok"] . "\'> " . $data["satuan"] . "</td><td><span id=\'span_max_" . $data["kode_brng"] . "\'>" . number_format($data["max_stok"], 0, ",", ".") . "</span><input type=\'text\' id=\'inp_max_" . $data["kode_brng"] . "\' name=\'inp_max_" . $data["kode_brng"] . "\' class=\'form-control inline-input\' value=\'" . number_format($data["max_stok"], 0, ",", ".") . "\' maxlength=\'6\' style=\'width: 65px; display: none\' onKeyUp=\'removeNonNumeric(&quot;inp_max_" . $data["kode_brng"] . "&quot;);recheckMinMax(&quot;M&quot;);\'><input type=\'hidden\' id=\'hdn_max_" . $data["kode_brng"] . "\' name=\'hdn_max_" . $data["kode_brng"] . "\' value=\'" . $data["max_stok"] . "\'> " . $data["satuan"] . "</td><td>" . number_format($data["stok"], 0, ",", ".") . " " . $data["satuan"] . "</td><td><button type=\'button\' id=\'btnEdit_" . $data["kode_brng"] . "\' class=\'btn btn-sm btn-primary\' href=\'#\' title=\'Ubah\' onClick=\'editMinMax2(&quot;" . $data["kode_brng"] . "&quot;)\' " . (!getAccess("rekap_permintaan_non_medis") ? "disabled" : "") . "><i class=\'fa fa-edit\'></i></button><a id=\'btnBatal_" . $data["kode_brng"] . "\' class=\'btn btn-sm btn-danger\' href=\'#\' title=\'Batal\' style=\'display: none\' onClick=\'cancelEditMinMax(&quot;" . $data["kode_brng"] . "&quot;)\'><i class=\'fa fa-times\'></i></a></td></tr>";
                        array_push($lstItem, $data["kode_brng"]);
                    }
                    if (!strlen($lstItems)) $lstItems = "<tr><td colspan=\'4\' style=\'text-align: center\'>--- Tidak ada data ---</td></tr>";
                    $lstItems = "<thead><tr><th style=\'width: 500px\'>Barang</th><th style=\'width: 130px\'>Stok Minimal</th><th style=\'width: 130px\'>Stok Maksimal</th><th style=\'width: 100px\'>Stok Tersedia</th><th style=\'width: 50px\'>Aksi</th></tr></thead><tbody>" . $lstItems . "</tbody>";
                    echo "<script>
                        $('#inp_bangsal').val('" . $unit["nm_bangsal"] . "');
                        $('#inp_bangsal').prop('disabled', 'disabled');
                        $('#hdn_bangsal').val('" . $_GET["id"] . "');
                        $('#div_unitDtl').slideUp(500);
                        setTimeout(function() {
                            $('#h4_unit').html('" . $unit["nm_bangsal"] . " [" . $_GET["id"] . "]');
                            $('#hdn_MinMaxitems').val('" . implode(",", $lstItem) . "');
                            $('#tbl_lstItem').html('" . $lstItems . "');
                            $('#div_unitDtl').slideDown(500);
                            $('#btn_clearBangsal').prop('disabled', '');
                        }, 500);
                    </script>";
                } else if (isset($_GET["clear"])) {
                    echo "<script>
                        $('#div_unitDtl').slideUp(500);
                        setTimeout(function() {
                            clearInput('bangsal');
                            $('#inp_bangsal').prop('disabled', '');
                            $('#h4_unit').html('');
                            $('#btn_submit').prop('disabled', 'disabled');
                            $('#tbl_lstItem tbody').empty();
                            $('#btn_clearBangsal').prop('disabled', 'disabled');
                        }, 500);
                    </script>";
                }
                break;
            case "minmaxnmadditem":
                if (isset($_GET["term"])) {
                    $term = $_GET["term"];
                    $data = base64_decode($_GET["data"]);
                    $qGetItem = "SELECT kode_brng, nama_brng FROM ipsrsbarang WHERE kode_brng NOT IN (SELECT kode_brng FROM gpi_minmax_nonmedis WHERE kd_bangsal='" . $_GET["unit"] . "') AND (kode_brng LIKE '%" . $term . "%' OR nama_brng LIKE '%" . $term . "%') AND status='1' LIMIT 10";
                    $getItem = mysqli_query($connect_app, $qGetItem);
                    $lstItem = ""; $i = 0;
                    while ($data = mysqli_fetch_assoc($getItem)) {
                        $i++;
                        $onClickAct = 'ajax_getData(\"ajax_getdata.php?type=minmaxnmadditem&from=' . $_GET["from"] . '&id=' . $data["kode_brng"] . '&unit=' . $_GET["unit"] . '\")';
                        $lstItem .= "<li onClick='" . $onClickAct . "'>" . str_replace('"', '\"', $data["nama_brng"]) . " [" . $data["kode_brng"] . "]</li>";
                    }
                    if (strlen($lstItem)) {
                        $lstItem = "<div id='suggest-item' class='suggestion'><ul>" . $lstItem . "</ul></div>";
                    } else {
                        $lstItem = "<div id='suggest-item' class='suggestion'><ul><li onClick='clearInput(&quot;" . $_GET["from"] . "&quot;); removeSuggest()'>Tidak ada saran</li></ul></div>";
                        $i = 1;
                    }
                    echo '<script>
                        removeSuggest();
                        elm = $("#inp_' . $_GET["from"] . '");
                        posX = elm.position().left;
                        posY = elm.position().top + elm.outerHeight();
                        zIndex = 100;
                        listItem = "' . $lstItem . '";
                        $(".content-wrapper").append(listItem);
                        $("#suggest-item").css({"top":posY, "left":posX, "height":' . ($i*24 + 2) . ', "zIndex":zIndex});
                    </script>';
                } else if (isset($_GET["id"])) {
                    $qGetItem = "SELECT nama_brng, satuan, IfNull(G.stok, 0) AS stok FROM ipsrsbarang B INNER JOIN kodesatuan S ON B.kode_sat=S.kode_sat LEFT JOIN gpi_gudangbarangipsrs G ON B.kode_brng=G.kode_brng AND kd_bangsal='" . $_GET["unit"] . "' WHERE B.kode_brng='" . $_GET["id"] . "'";
                    $getItem = mysqli_query($connect_app, $qGetItem);
                    $item = mysqli_fetch_assoc($getItem);
                    $rowItem = "$('#tr_" . $_GET["id"] . "').html('<td>" . $item["nama_brng"] . " [" . $_GET["id"] . "]</td><td><input type=\'text\' id=\'inp_min_" . $_GET["id"] . "\' name=\'inp_min_" . $_GET["id"] . "\' class=\'form-control inline-input\' value=\'0\' maxlength=\'6\' style=\'width: 65px\' onKeyUp=\'removeNonNumeric(&quot;inp_min_" . $_GET["id"] . "&quot;);recheckMinMax(&quot;N&quot;);\'> " . $item["satuan"] . "</td><td><input type=\'text\' id=\'inp_max_" . $_GET["id"] . "\' name=\'inp_max_" . $_GET["id"] . "\' class=\'form-control inline-input\' value=\'0\' maxlength=\'6\' style=\'width: 65px\' onKeyUp=\'removeNonNumeric(&quot;inp_max_" . $_GET["id"] . "&quot;);recheckMinMax(&quot;N&quot;);\'> " . $item["satuan"] . "</td><td>" . number_format($item["stok"], 0, ",", ".") . " " . $item["satuan"] . "</td><td><a class=\'btn btn-danger btn-sm\' title=\'Hapus\' href=\'#\' onClick=\'if (confirm(&quot;Apakah Anda yakin ingin menghapus data ini?&quot;)) removeItem(&quot;" . $_GET["id"] . "&quot;)\'><i class=\'fa fa-trash\'></i></a></td>');";
                    echo "<script>
                        $('#tr_" . $_GET["from"] . "').attr('id', 'tr_" . $_GET["id"] . "');" . $rowItem . "appendItems('MinMax', '" . $_GET["id"] . "');
                    </script>";
                }
                break;
            case "ponmlist":
                require_once "../function/access.php";
                $start = $_GET["start"];
                $end = $_GET["end"];
                $supplier = (isset($_GET["supplier"]) ? $_GET["supplier"] : "");
                $mode = (Isset($_GET["mode"]) ? $_GET["mode"] : "");
                $tblhead = "";
                $status = "";
                if ($mode == "popup") {
                    $colspan = 6;
                    $status = "'Proses Pesan'";
                } else {
                    $colspan = 7;
                    $tblhead = "<th style='width: 125px'>Aksi</th>";
                    if (isset($_GET["approval"])) $status = "'Baru'";
                        else $status = "'Proses Pesan','Baru','Sudah Datang'";
                }
                $qGetPO = "SELECT no_pemesanan, tanggal, nama_suplier, P.nama, catatan, SPNM.status, kadaluarsa FROM surat_pemesanan_non_medis SPNM INNER JOIN ipsrssuplier S ON SPNM.kode_suplier=S.kode_suplier INNER JOIN pegawai P ON SPNM.nip=P.nik WHERE tanggal BETWEEN '" . $start . "' AND '" . $end . "'" . (strlen($supplier) ? " AND SPNM.kode_suplier='" . $supplier . "'" : "") . " AND SPNM.status IN (" . $status . ") ORDER BY tanggal, no_pemesanan";
                $getPO = mysqli_query($connect_app, $qGetPO);
                $lstPO = "";
                while ($data = mysqli_fetch_assoc($getPO)) {
                    switch ($data["status"]) {
                        case "Baru": $status = "<i class=\'fa fa-plus-square text-blue\' title=\'Baru\'></i>"; break;
                        case "Proses Pesan": $status = "<i class=\'fa fa-shopping-cart text-purple\' title=\'Proses Pesan\'></i>"; break;
                        case "Sudah Datang": $status = "<i class=\'glyphicon glyphicon-saved text-green\' title=\'Sudah Datang\'></i>"; break;
                        default: $status = ""; break;
                    }
                    if ($data["kadaluarsa"] == "1")
                        $status .= "&nbsp;<i class='fa fa-calendar-times-o text-red' title='Kadaluarsa'></i>";
                    if ($mode == "popup") $btnAct = "";
                        else $btnAct = "<td id='td_btn_" . $data["no_pemesanan"] . "'>" . ($data["status"] == "Baru" ? ($data["kadaluarsa"] == "0" ? (isset($_GET["approval"]) ? "" : "<a class='btn btn-primary btn-sm' href='edit_purchase_order_nm.php?id=" . $data["no_pemesanan"] . "' title='Ubah'><i class='fa fa-edit'></i></a>") . (showApproval() ? " <a class='btn btn-success btn-sm' href='#' onClick='approvePO(&quot;" . $data["no_pemesanan"] . "&quot;, &quot;N&quot;)' title='Setujui'><i class='fa fa-check'></i></a>" : "") : "") : "<a class='btn btn-primary btn-sm' href='#' onClick='printPO(&quot;" . $data["no_pemesanan"] . "&quot;)' title='Cetak'><i class='fa fa-print'></i></a>") . ($data["kadaluarsa"] == "1" ? " <a class='btn btn-sm bg-purple' href='#' onClick='openExpiredPO(&quot;N&quot;, &quot;" . $data["no_pemesanan"] . "&quot;)' title='Buka Akses'><i class='fa fa-undo'></i></a>" : "") . "</td>";
                    $lstPO .= "<tr id='tr_header_" . $data["no_pemesanan"] . "'><td>" . $data["no_pemesanan"] . " <span id='chevron_" . $data["no_pemesanan"] . "' class='chevron' title='Klik untuk menampilkan/menyembunyikan detail pemesanan' onClick='togglePODetail(&quot;" . $data["no_pemesanan"] . "&quot;, &quot;N&quot;)'><i class='fa fa-chevron-circle-down'></i></span></td><td>" . $data["tanggal"] . "</td><td>" . $data["nama_suplier"] . "</td><td>" . $data["nama"] . "</td><td>" . addslashes(str_replace(array("\r\n", "\n", "<br>", "\n\r"), " ", $data["catatan"])) . "</td><td id='td_status_" . $data["no_pemesanan"] . "'>" . $status . "</td>" . $btnAct . "</tr><tr id='tr_" . $data["no_pemesanan"] . "' style='display: none'><td id='td_" . $data["no_pemesanan"] . "' class='td-detail' colspan='" . $colspan . "'>-</td></tr>";                    
                }
                if (!strlen($lstPO)) $lstPO = "<tr><td colspan='7' style='text-align: center'>--- Tidak ada data ---</td></tr>";
                $lstPO = "<thead><tr><th style='width: 120px'>No. Pemesanan</th><th style='width: 85px'>Tanggal</th><th style='width: 200px'>Supplier</th><th style='width: 250px'>Petugas</th><th>Catatan</th><th style='width: 60px'>Status</th>" . $tblhead . "</tr></thead><tbody>" . $lstPO . "</tbody>";
                if (isset($_GET["approval"])) $target = "tbl_spn";
                    else $target = "tbl_purchaseorder";
                echo '<script>$("#' . $target . '").html("' . $lstPO . '")</script>';
                break;
            case "ponmdetail":
                if (isset($_GET["id"])) {
                    if (isset($_GET["data"])) $selItems = base64_decode($_GET["data"]);
                        else $selItems = "";
                    $mode = (isset($_GET["mode"]) ? $_GET["mode"] : "");
                    if ($mode == "popup") {
                        $tblhead1 = "";
                        $tblhead2 = "<th>Aksi</th>";
                    } else {
                        $tblhead1 = "<th>Harga Per SB</th><th>Subtotal</th><th>Diskon 1 (%)</th><th>Diskon 2 (%)</th><th>Total Diskon</th><th>Total</th><th>Stok RS</th>";
                        $tblhead2 = "";
                    }
                    $qCheckExpiry = "SELECT kadaluarsa FROM surat_pemesanan_non_medis WHERE no_pemesanan='" . $_GET["id"] . "'";
                    $checkExpiry = mysqli_query($connect_app, $qCheckExpiry);
                    $expiry = mysqli_fetch_assoc($checkExpiry)["kadaluarsa"];
                    $qGetPODetail = "SELECT DSPN.kode_brng, nama_brng, no_pr_ref, SK.satuan AS satuan_kecil, SB.satuan AS satuan_besar, DSPN.isi, jumlah, h_pesan, subtotal, dis, dis2, besardis, total, IfNull(Sum(G.stok), 0) AS stok, DSPN.status FROM detail_surat_pemesanan_non_medis DSPN INNER JOIN ipsrsbarang B On DSPN.kode_brng=B.kode_brng INNER JOIN kodesatuan SK ON DSPN.kode_sat=SK.kode_sat INNER JOIN kodesatuan SB ON DSPN.kode_satbesar=SB.kode_sat LEFT JOIN gpi_gudangbarangipsrs G ON G.kode_brng=B.kode_brng WHERE no_pemesanan='" . $_GET["id"] . "' GROUP BY kode_brng, no_pr_ref ORDER BY kode_brng";
                    $getPODetail = mysqli_query($connect_app, $qGetPODetail);
                    $PODetail = ""; $i = 0;
                    while ($data = mysqli_fetch_assoc($getPODetail)) {
                        switch ($data["status"]) {
                            case "Baru": $status = "<i class=\'fa fa-plus-square text-blue\' title=\'Baru\'></i>"; break;
                            case "Proses Pesan": $status = "<i class=\'fa fa-shopping-cart text-purple\' title=\'Proses Pesan\'></i>"; break;
                            case "Sudah Datang": $status = "<i class=\'glyphicon glyphicon-saved text-green\' title=\'Sudah Datang\'></i>"; break;
                            default: $status = ""; break;
                        }
                        if ($mode == "popup") {
                            $tbldata1 = "";
                            $tbldata2 = "<td>" . ($data["status"] != "Sudah Datang" ? (strpos($selItems, $data["kode_brng"] . "_" . $_GET["id"]) === FALSE ? "<input type='checkbox' id='chk_" . $data["kode_brng"] . "_" . $_GET["id"] . "_" . $data["jumlah"] . "' name='chk_" . $data["kode_brng"] . "_" . $_GET["id"] . "_" . $data["jumlah"] . "' onClick='activateBtnPilih()' " . ($expiry == "1" ? "disabled" : "") . ">" : "<i class='fa fa-check-square-o text-green' title='Sudah ditambahkan'></i>") : "") . "</td>";
                        } else {
                            $tbldata1 = "<td class='num-input'>" . number_format($data["h_pesan"], 0, ",", ".") . "</td><td class='num-input'>" . number_format($data["subtotal"], 0, ",", ".") . "</td><td class='num-input'>" . str_replace(".", ",", $data["dis"]) . "</td><td class='num-input'>" . str_replace(".", ",", $data["dis2"]) . "</td><td class='num-input'>" . number_format($data["besardis"], 0, ",", ".") . "</td><td class='num-input'>" . number_format($data["total"], 0, ",", ".") . "</td><td><a class='btn btn-primary btn-sm' onClick='getStockDtl(&quot;" . $data["kode_brng"] . "&quot;, &quot;N&quot;)' title='Klik untuk menampilkan detail stok barang per bangsal'><i class='fa fa-eye'></i></a></td>";
                            $tbldata2 = "";
                        }
                        $PODetail .= "<tr><td>" . ++$i . "</td><td>" . $data["kode_brng"] . "</td><td>" . str_replace('"', '\"', $data["nama_brng"]) . "</td><td>" . ($data["no_pr_ref"] ? $data["no_pr_ref"] : "-") . "</td><td>" . $data["satuan_kecil"] . "</td><td>" . $data["satuan_besar"] . "</td><td class='num-input'>" . $data["isi"] . "</td><td class='num-input'>" . number_format($data["jumlah"], 2, ",", ".") . " " . $data["satuan_besar"] . "</td>" . $tbldata1 . "<td>" . $status . "</td>" . $tbldata2 . "</tr>";
                    }
                    $PODetail = "<table class='table'><tr><th>No</th><th>Kode</th><th>Nama Barang</th><th>No. Pengajuan</th><th title='Satuan Kecil'>SK</th><th title='Satuan Besar'>SB</th><th>Isi</th><th>Jumlah Pesan</th>" . $tblhead1 . "<th>Status</th>" . $tblhead2 . "</tr>" . $PODetail . "</table>";
                    echo '<script>$("#td_' . $_GET["id"] . '").html("' . $PODetail . '")</script>';
                }
                break;
            case "ponmnonpr":
                if (isset($_GET["term"])) {
                    $term = $_GET["term"];
                    $data = base64_decode($_GET["data"]);
                    $qGetItem = "SELECT kode_brng, nama_brng FROM ipsrsbarang WHERE kode_brng NOT IN ('" . str_replace(",", "','", $data) . "') AND (kode_brng LIKE '%" . $term . "%' OR nama_brng LIKE '%" . $term . "%') LIMIT 10";
                    $getItem = mysqli_query($connect_app, $qGetItem);
                    $lstItem = ""; $i = 0;
                    while ($data = mysqli_fetch_assoc($getItem)) {
                        $i++;
                        $onClickAct = 'ajax_getData(\"ajax_getdata.php?type=ponmnonpr&from=' . $_GET["from"] . '&id=' . $data["kode_brng"] . '\")';
                        $lstItem .= "<li onClick='" . $onClickAct . "'>" . str_replace('"', '\"', $data["nama_brng"]) . " [" . $data["kode_brng"] . "]</li>";
                    }
                    if (strlen($lstItem)) {
                        $lstItem = "<div id='suggest-item' class='suggestion'><ul>" . $lstItem . "</ul></div>";
                    } else {
                        $lstItem = "<div id='suggest-item' class='suggestion'><ul><li onClick='clearInputItem(&quot;" . $_GET["from"] . "&quot;); removeSuggest()'>Tidak ada saran</li></ul></div>";
                        $i = 1;
                    }
                    echo '<script>
                        removeSuggest();
                        elm = $("#inp_' . $_GET["from"] . '");
                        posX = elm.position().left;
                        posY = elm.position().top + elm.outerHeight();
                        zIndex = 100;
                        listItem = "' . $lstItem . '";
                        $(".content-wrapper").append(listItem);
                        $("#suggest-item").css({"top":posY, "left":posX, "height":' . ($i*24 + 2) . ', "zIndex":zIndex});
                    </script>';
                } else if (isset($_GET["id"])) {
                    $qGetItem = "SELECT B.kode_brng, nama_brng, SK.kode_sat, SK.satuan AS satuan_kecil, kode_satbesar, SB.satuan AS satuan_besar, isi, dasar*isi AS harga_beli, IfNull(Sum(G.stok), 0) AS stok FROM ipsrsbarang B INNER JOIN kodesatuan SK ON B.kode_sat=SK.kode_sat INNER JOIN kodesatuan SB ON B.kode_satbesar=SB.kode_sat LEFT JOIN gpi_gudangbarangipsrs G ON B.kode_brng=G.kode_brng WHERE B.kode_brng='" . $_GET["id"] . "' GROUP BY B.kode_brng";
                    $getItem = mysqli_query($connect_app, $qGetItem);
                    $item = mysqli_fetch_assoc($getItem);
                    $rowItem = "$('#tr_" . $item["kode_brng"] . "').html('<td>" . $item["kode_brng"] . "</td><td>" . addslashes($item["nama_brng"]) . "</td><td>" . $item["satuan_kecil"] . "<input type=\'hidden\' id=\'hdn_sk_" . $item["kode_brng"] . "\' name=\'hdn_sk_" . $item["kode_brng"] . "\' value=\'" . $item["kode_sat"] . "\'></td><td>" . $item["satuan_besar"] . "<input type=\'hidden\' id=\'hdn_sb_" . $item["kode_brng"] . "\' name=\'hdn_sb_" . $item["kode_brng"] . "\' value=\'" . $item["kode_satbesar"] . "\'></td><td class=\'num-input\'>" . $item["isi"] . "<input type=\'hidden\' id=\'hdn_isi_" . $item["kode_brng"] . "\' name=\'hdn_isi_" . $item["kode_brng"] . "\' value=\'" . $item["isi"] . "\'></td><td class=\'num-input\'>-</td><td style=\'width: 60px\'><input type=\'text\' id=\'inp_jml_" . $item["kode_brng"] . "\' name=\'inp_jml_" . $item["kode_brng"] . "\' class=\'form-control num-input\' value=\'0\' style=\'width: 60px\' onKeyUp=\'removeNonNumeric(&quot;inp_jml_" . $item["kode_brng"] . "&quot;); recalculatePO(&quot;N&quot;)\' maxlength=\'5\' autocomplete=\'off\'></td><td style=\'width: 100px\'>" . $item["satuan_besar"] . "</td><td class=\'num-input\'><input type=\'text\' id=\'inp_hpesan_" . $item["kode_brng"] . "\' name=\'inp_hpesan_" . $item["kode_brng"] . "\' class=\'form-control num-input\' style=\'width: 100px\' value=\'" . number_format($item["harga_beli"], 0, ",", ".") . "\' onKeyUp=\'removeNonNumeric(&quot;inp_hpesan_" . $item["kode_brng"] . "&quot;); recalculatePO(&quot;N&quot;)\' autocomplete=\'off\' maxlength=\'11\'></td><td class=\'num-input\'><span id=\'span_subtotal_" . $item["kode_brng"] . "\' name=\'span_subtotal_" . $item["kode_brng"] . "\'>" . number_format(0, 0, ",", ".") . "</span><input type=\'hidden\' id=\'hdn_subtotal_" . $item["kode_brng"] . "\' name=\'hdn_subtotal_" . $item["kode_brng"] . "\' value=\'0\'></td><td><input type=\'text\' id=\'inp_disc1_" . $item["kode_brng"] . "\' name=\'inp_disc1_" . $item["kode_brng"] . "\' class=\'form-control num-input\' value=\'0\' maxlength=\'5\' onKeyUp=\'removeNonNumeric(&quot;inp_disc1_" . $item["kode_brng"] . "&quot;); recalculatePO(&quot;N&quot;)\' autocomplete=\'off\'></td><td><input type=\'text\' id=\'inp_disc2_" . $item["kode_brng"] . "\' name=\'inp_disc2_" . $item["kode_brng"] . "\' class=\'form-control num-input\' value=\'0\' maxlength=\'5\' onKeyUp=\'removeNonNumeric(&quot;inp_disc2_" . $item["kode_brng"] . "&quot;); recalculatePO(&quot;N&quot;)\' autocomplete=\'off\'></td><td class=\'num-input\'><span id=\'span_ndisc_" . $item["kode_brng"] . "\' name=\'span_ndisc_" . $item["kode_brng"] . "\'>0</span><input type=\'hidden\' id=\'hdn_ndisc_" . $item["kode_brng"] . "\' name=\'hdn_ndisc_" . $item["kode_brng"] . "\'></td><td class=\'num-input td-total\'><span id=\'span_total_" . $item["kode_brng"] . "\' name=\'span_total_" . $item["kode_brng"] . "\'>" . number_format(0, 0, ",", ".") . "</span><input type=\'hidden\' id=\'hdn_total_" . $item["kode_brng"] . "\' name=\'hdn_total_" . $item["kode_brng"] . "\' value=\'0\'></td><td class=\'num-input\'>" . number_format($item["stok"], 0, ",", ".") . " " . $item["satuan_kecil"] . " <a class=\'btn btn-primary btn-sm\' onClick=\'getStockDtl(&quot;" . $item["kode_brng"] . "&quot;, &quot;N&quot;)\' title=\'Klik untuk menampilkan detail stok barang per bangsal\'><i class=\'fa fa-eye\'></i></a></td><td>-</td><td><a class=\'btn btn-danger btn-sm\' title=\'Hapus\' href=\'#\' onClick=\'if (confirm(&quot;Apakah Anda yakin ingin menghapus data ini?&quot;)) removeItem(&quot;" . $item["kode_brng"] . "&quot;)\'><i class=\'fa fa-trash\'></i></a></td>');";
                    echo "<script>
                        $('#tr_" . $_GET["from"] . "').attr('id', 'tr_" . $item["kode_brng"] . "');" . $rowItem . "
                        appendItems('PO', '" . $item["kode_brng"] . "');
                        recalculatePO('N');
                    </script>";
                }
                break;
            case "ponmitem":
                if (isset($_GET["term"])) {
                    $term = $_GET["term"];
                    $qGetItem = "SELECT kode_brng, nama_brng FROM ipsrsbarang WHERE kode_brng LIKE '%" . $term . "%' OR nama_brng LIKE '%" . $term . "%' LIMIT 10";
                    $getItem = mysqli_query($connect_app, $qGetItem);
                    $lstItem = ""; $i = 0;
                    while ($data = mysqli_fetch_assoc($getItem)) {
                        $i++;
                        $onClickAct = 'ajax_getData(\"ajax_getdata.php?type=ponmitem&id=' . $data["kode_brng"] . '\")';
                        $lstItem .= "<li onClick='" . $onClickAct . "'>" . $data["nama_brng"] . " [" . $data["kode_brng"] . "]</li>";
                    }
                    if (strlen($lstItem)) {
                        $lstItem = "<div id='suggest-item' class='suggestion'><ul>" . $lstItem . "</ul></div>";
                    } else {
                        $lstItem = "<div id='suggest-item' class='suggestion'><ul><li onClick='clearInput(&quot;barang_nm&quot;); removeSuggest()'>Tidak ada saran</li></ul></div>";
                        $i = 1;
                    }
                    echo '<script>
                        removeSuggest();
                        elm = $("#inp_barang_nm");
                        posX = elm.position().left;
                        posY = elm.position().top + elm.outerHeight();
                        zIndex = 100;
                        listItem = "' . $lstItem . '";
                        $(".content-wrapper").append(listItem);
                        $("#suggest-item").css({"top":posY, "left":posX, "height": ' . ($i*24 + 2) . ', "zIndex":zIndex});
                    </script>';
                } else if (isset($_GET["id"])) {
                    $qGetItem = "SELECT nama_brng FROM ipsrsbarang WHERE kode_brng='" . $_GET["id"] . "'";
                    $getItem = mysqli_query($connect_app, $qGetItem);
                    $item = mysqli_fetch_assoc($getItem);
                    echo "<script>
                        $('#inp_barang_nm').val('" . $item["nama_brng"] . " [" . $_GET["id"] . "]');
                        $('#hdn_barang_nm').val('" . $_GET["id"] . "');
                    </script>";
                }
                break;
            case "ponmitemlist":
                $brng = $_GET["item"];
                $supplier = $_GET["supplier"];
                $keyword = $_GET["keyword"];
                $start = $_GET["start"];
                $end = $_GET["end"];
                if (strlen($brng) && strlen($supplier)) $cond = "DSPN.kode_brng='" . $brng . "' AND SPN.kode_suplier='" . $supplier . "'";
                else {
                    if (strlen($brng)) $cond = "DSPN.kode_brng='" . $brng . "'";
                    else if (strlen($supplier)) $cond = "SPN.kode_suplier='" . $supplier . "'";
                    else if (strlen($keyword)) $cond = "DSPN.kode_brng LIKE '%" . $keyword . "%' OR nama_brng LIKE '%" . $keyword . "%' OR SPN.kode_suplier LIKE '%" . $keyword . "%' OR nama_suplier LIKE '%" . $keyword . "%'";
                    else $cond = "1=1";
                }
                $qGetPOItem = "SELECT B.kode_brng, nama_brng, SK.satuan AS satuan_kecil, SB.satuan AS satuan_besar, B.isi, PBN.tanggal AS tgl_pengajuan, no_pr_ref, DPBN.jumlah AS jml_pengajuan, PBN.tanggal_disetujui, DPBN.jumlah_disetujui, SPN.tanggal AS tgl_pesan, SPN.no_pemesanan, DSPN.jumlah AS jml_pesan, nama_suplier, P.tgl_pesan AS tgl_penerimaan, P.no_faktur, no_faktur_supplier, DP.jumlah AS jml_penerimaan, DP.harga, DP.subtotal, DP.dis, DP.dis2, DP.besardis, DP.total, CASE WHEN P.ppn>0 THEN 1 WHEN P.ppn=0 THEN 0 ELSE '' END AS is_ppn FROM surat_pemesanan_non_medis SPN INNER JOIN detail_surat_pemesanan_non_medis DSPN ON SPN.no_pemesanan=DSPN.no_pemesanan INNER JOIN ipsrsbarang B ON DSPN.kode_brng=B.kode_brng INNER JOIN ipsrssuplier S ON SPN.kode_suplier=S.kode_suplier INNER JOIN kodesatuan SK ON B.kode_sat=SK.kode_sat INNER JOIN kodesatuan SB ON B.kode_satbesar=SB.kode_sat LEFT JOIN pengajuan_barang_nonmedis PBN ON DSPN.no_pr_ref=PBN.no_pengajuan LEFT JOIN detail_pengajuan_barang_nonmedis DPBN ON PBN.no_pengajuan=DPBN.no_pengajuan AND DPBN.kode_brng=DSPN.kode_brng LEFT JOIN ipsrsdetailpesan DP ON SPN.no_pemesanan=DP.no_pemesanan AND DP.kode_brng=DSPN.kode_brng LEFT JOIN ipsrspemesanan P ON DP.no_faktur=P.no_faktur WHERE (" . $cond . ") AND ((SPN.tanggal BETWEEN '" . $start . "' AND '" . $end . "') OR (PBN.tanggal BETWEEN '" . $start . "' AND '" . $end . "') OR (P.tgl_pesan BETWEEN '" . $start . "' AND '" . $end . "'))";
                $getPOItem = mysqli_query($connect_app, $qGetPOItem);
                $lstPOItem = "";
                while ($data = mysqli_fetch_assoc($getPOItem)) {
                    $lstPOItem .= "<tr><td>" . $data["kode_brng"] . "</td><td>" . addslashes($data["nama_brng"]) . "</td><td>" . $data["satuan_kecil"] . "</td><td>" . $data["satuan_besar"] . "</td><td class=\'num-input\'>" . number_format($data["isi"],0,",",".") . "</td><td>" . (strlen($data["tgl_pengajuan"]) ? $data["tgl_pengajuan"] : "-") . "</td><td>" . (strlen($data["no_pr_ref"]) ? $data["no_pr_ref"] : "-") . "</td><td>" . (strlen($data["jml_pengajuan"]) ? number_format($data["jml_pengajuan"],0,",",".") . " " . $data["satuan_besar"] : "-") . "</td><td>" . (strlen($data["tanggal_disetujui"]) ? $data["tanggal_disetujui"] : "-") . "</td><td>" . (strlen($data["jumlah_disetujui"]) ? number_format($data["jumlah_disetujui"], 0, ",", ".") . " " . $data["satuan_besar"] : "-") . "</td><td>" . $data["tgl_pesan"] . "</td><td>" . $data["no_pemesanan"] . "</td><td>" . number_format($data["jml_pesan"],0,",",".") . " " . $data["satuan_besar"] . "</td><td>" . $data["nama_suplier"] . "</td><td>" . (strlen($data["tgl_penerimaan"]) ? $data["tgl_penerimaan"] : "-") . "</td><td>" . (strlen($data["no_faktur"]) ? $data["no_faktur"] : "-") . "</td><td>" . (strlen($data["no_faktur_supplier"]) ? $data["no_faktur_supplier"] : "-") . "</td><td>" . (strlen($data["jml_penerimaan"]) ? number_format($data["jml_penerimaan"],0,",",".") . " " . $data["satuan_besar"] : "-") . "</td><td class=\'num-input\'>" . (strlen($data["harga"]) ? number_format($data["harga"],0,",",".") : "-") . "</td><td class=\'num-input\'>" . (strlen($data["subtotal"]) ? number_format($data["subtotal"],0,",",".") : "-") . "</td><td class=\'num-input\'>" . (strlen($data["dis"]) ? number_format($data["dis"],0,",",".") : "-") . "</td><td class=\'num-input\'>" . (strlen($data["dis2"]) ? number_format($data["dis2"],0,",",".") : "-") . "</td><td class=\'num-input\'>" . (strlen($data["besardis"]) ? number_format($data["besardis"],0,",",".") : "-") . "</td><td class=\'num-input\'>" . (strlen($data["total"]) ? number_format($data["total"],0,",",".") : "-") . "</td><td>" . (strlen($data["is_ppn"]) ? ($data["is_ppn"] == "1" ? "Ya" : "Tidak") : "-") . "</td></tr>";
                }
                if (!strlen($lstPOItem)) $lstPOItem = "<tr><td colspan=\'25\' style=\'text-align: center\'>--- Tidak ada data ---</td></tr>";
                $lstPOItem = "<thead><tr><th rowspan=\'2\' style=\'min-width: 80px\'>Kode</th><th rowspan=\'2\' style=\'min-width: 200px\'>Nama Barang</th><th rowspan=\'2\' title=\'Satuan Kecil\' style=\'min-width: 70px\'>SK</th><th rowspan=\'2\' title=\'Satuan Besar\' style=\'min-width: 70px\'>SB</th><th rowspan=\'2\' style=\'min-width: 40px\'>Isi</th><th class=\'alternate-hdr\' colspan=\'5\'>Pengajuan</th><th colspan=\'4\'>Pemesanan</th><th class=\'alternate-hdr\' colspan=\'11\'>Penerimaan</th></tr><tr><th class=\'alternate-hdr\' style=\'min-width: 85px\'>Tanggal</th><th class=\'alternate-hdr\' style=\'min-width: 120px\'>No. Pengajuan</th><th class=\'alternate-hdr\' style=\'min-width: 90px\'>Jumlah</th><th class=\'alternate-hdr\' style=\'min-width: 85px\'>Tanggal Disetujui</th><th class=\'alternate-hdr\' style=\'min-width: 90px\'>Jumlah Disetujui</th><th style=\'min-width: 85px\'>Tanggal</th><th style=\'min-width: 120px\'>No. Pemesanan</th><th style=\'min-width: 90px\'>Jumlah</th><th style=\'min-width: 200px\'>Supplier</th><th class=\'alternate-hdr\' style=\'min-width: 85px\'>Tanggal</th><th class=\'alternate-hdr\' style=\'min-width: 120px\'>No. Penerimaan</th><th class=\'alternate-hdr\' style=\'min-width: 140px\'>No. Faktur Supplier</th><th class=\'alternate-hdr\' style=\'min-width: 90px\'>Jumlah</th><th class=\'alternate-hdr\' style=\'min-width: 90px\'>Harga Per SB</th><th class=\'alternate-hdr\' style=\'min-width: 90px\'>Subtotal</th><th class=\'alternate-hdr\' style=\'min-width: 70px\'>Diskon 1 (%)</th><th class=\'alternate-hdr\' style=\'min-width: 70px\'>Diskon 2 (%)</th><th class=\'alternate-hdr\' style=\'min-width: 90px\'>Total Diskon</th><th class=\'alternate-hdr\' style=\'min-width: 90px\'>Total</th><th class=\'alternate-hdr\' style=\'min-width: 40px\'>PPN</th></tr></thead><tbody>" . $lstPOItem . "</tbody>";
                echo "<script>
                    $('#div-scroll').slideUp(500);
                    setTimeout(function() {
                        $('#tbl_listItems').html('" . $lstPOItem . "');
                        $('#div-scroll').slideDown(500);
                    }, 500);
                </script>";
                break;
            case "rcpnmlist":
                $supplier = $_GET["supplier"];
                $start = $_GET["start"];
                $end = $_GET["end"];
                $qGetReception = "SELECT no_faktur, no_faktur_supplier, nama_suplier, tgl_pesan, tgl_faktur, PT.nama, P.status, P.catatan FROM ipsrspemesanan P INNER JOIN ipsrssuplier S ON P.kode_suplier=S.kode_suplier INNER JOIN petugas PT ON P.nip=PT.nip WHERE tgl_pesan BETWEEN '" . $start . "' AND '" . $end . "'" . (strlen($supplier) ? " AND P.kode_suplier='" . $supplier . "'" : "") . " ORDER BY tgl_pesan, no_faktur";
                $getReception = mysqli_query($connect_app, $qGetReception);
                $lstReception = "";
                while ($data = mysqli_fetch_assoc($getReception)) {
                    switch ($data["status"]) {
                        case "Belum Dibayar": $status = "<i class=\'fa fa-star-o text-green\' title=\'Belum Dibayar\'></i>"; break;
                        case "Belum Lunas": $status = "<i class=\'fa fa-star-half-o text-green\' title=\'Belum Lunas\'></i>"; break;
                        case "Sudah Lunas": $status = "<i class=\'fa fa-star text-green\' title=\'Sudah Lunas\'></i>"; break;
                        default: $status = ""; break;
                    }
                    $lstReception .= "<tr><td>" . $data["no_faktur"] . " <span id=\'chevron_" . $data["no_faktur"] . "\' class=\'chevron\' title=\'Klik untuk menampilkan/menyembunyikan detail penerimaan\' onClick=\'toggleRcpDetail(&quot;" . $data["no_faktur"] . "&quot;)\'><i class=\'fa fa-chevron-circle-down\'></i></span></td><td>" . $data["tgl_pesan"] . "</td><td>" . $data["no_faktur_supplier"] . "</td><td>" . $data["nama_suplier"] . "</td><td>" . $data["tgl_faktur"] . "</td><td>" . $data["nama"] . "</td><td>" . str_replace("\n", "", str_replace("\r", "", $data["catatan"])) . "</td><td>" . $status . "</td><td><a class=\'btn btn-primary btn-sm\' title=\'Ubah\' href=\'edit_po_reception_nm.php?id=" . $data["no_faktur"] . "\'><i class=\'fa fa-edit\'></i></a></td></tr><tr id=\'tr_" . $data["no_faktur"] . "\' style=\'display: none\'><td id=\'td_" . $data["no_faktur"] . "\' class=\'td-detail\' colspan=\'9\'>-</td></tr>";
                }
                if (!strlen($lstReception)) $lstReception = "<tr><td colspan=\'9\' style=\'text-align: center\'>--- Tidak ada data ---</td></tr>";
                $lstReception = "<thead><tr><th>No. Penerimaan</th><th>Tanggal Penerimaan</th><th>No. Faktur</th><th>Supplier</th><th>Tanggal Faktur</th><th>Petugas</th><th>Catatan</th><th>Status</th><th>Aksi</th></tr></thead><tbody>" . $lstReception . "</tbody>";
                echo "<script>$('#tbl_reception').html('" . $lstReception . "');</script>";
                break;
            case "rcpnmdetail":
                if (isset($_GET["id"])) {
                    $qGetRcpDetail = "SELECT DP.kode_brng, nama_brng, no_pemesanan, SK.satuan AS satuan_kecil, SB.satuan AS satuan_besar, DP.isi, jumlah, DP.harga, subtotal, dis, dis2, besardis, total FROM ipsrsdetailpesan DP INNER JOIN ipsrsbarang B ON DP.kode_brng=B.kode_brng INNER JOIN kodesatuan SK ON DP.kode_sat=SK.kode_sat INNER JOIN kodesatuan SB ON DP.kode_satbesar=SB.kode_sat WHERE no_faktur='" . $_GET["id"] . "'";
                    $getRcpDetail = mysqli_query($connect_app, $qGetRcpDetail);
                    $rcpDetail = ""; $i = 0;
                    while ($data = mysqli_fetch_assoc($getRcpDetail)) {
                        $rcpDetail .= "<tr><td>" . ++$i . "</td><td>" . $data["kode_brng"] . "</td><td>" . addslashes($data["nama_brng"]) . "</td><td>" . $data["no_pemesanan"] . "</td><td>" . $data["satuan_kecil"] . "</td><td>" . $data["satuan_besar"] . "</td><td class=\'num-input\'>" . number_format($data["isi"], 0, ",", ".") . "</td><td class=\'num-input\'>" . number_format($data["jumlah"], 2, ",", ".") . " " . $data["satuan_besar"] . "</td><td class=\'num-input\'>" . number_format($data["harga"], 0, ",", ".") . "</td><td class=\'num-input\'>" . number_format($data["subtotal"], 0, ",", ".") . "</td><td class=\'num-input\'>" . number_format($data["dis"], 0, ",", ".") . "</td><td class=\'num-input\'>" . number_format($data["dis2"], 0, ",", ".") . "</td><td class=\'num-input\'>" . number_format($data["besardis"], 0, ",", ".") . "</td><td class=\'num-input\'>" . number_format($data["total"], 0, ",", ".") . "</td></tr>";
                    }
                    $rcpDetail = "<table class=\'table\'><tr><th>No</th><th>Kode</th><th>Nama Barang</th><th>No. Pemesanan</th><th title=\'Satuan Kecil\'>SK</th><th title=\'Satuan Besar\'>SB</th><th>Isi</th><th>Jumlah Datang</th><th>Harga Per SB</th><th>Subtotal</th><th>Diskon 1 (%)</th><th>Diskon 2 (%)</th><th>Total Diskon</th><th>Total</th></tr>" . $rcpDetail . "</table>";
                    echo "<script>$('#td_" . $_GET["id"] . "').html('" . $rcpDetail . "');</script>";
                }
                break;
            case "serviceunit":
                $where = $_GET["where"];
                if (isset($_GET["term"])) {
                    $term = $_GET["term"];
                    $qGetServiceUnit = "SELECT kd_bangsal, nm_bangsal FROM bangsal WHERE status='1' AND (kd_bangsal LIKE '%" . $term . "%' OR  nm_bangsal LIKE '%" . $term . "%') AND kd_bangsal IN ('" . str_replace(",", "','", str_replace("_", "-", $_SESSION["USER"]["BANGSAL"])) . "') LIMIT 10";
                    $getServiceUnit = mysqli_query($connect_app, $qGetServiceUnit);
                    $lstServiceUnit = ""; $i = 0;
                    while ($data = mysqli_fetch_assoc($getServiceUnit)) {
                        $i++;
                        $onClickAct = 'ajax_getData(\"ajax_getdata.php?type=serviceunit&where=' . $where . '&id=' . $data["kd_bangsal"] . '\")';
                        $lstServiceUnit .= "<li onClick='" . $onClickAct . "'>" . $data["nm_bangsal"] . "</li>";
                    }
                    if (strlen($lstServiceUnit))
                        $lstServiceUnit = "<div id='suggest-serviceunit' class='suggestion'><ul>" . $lstServiceUnit . "</ul></div>";
                    else {
                        $lstServiceUnit = "<div id='suggest-serviceunit' class='suggestion'><ul><li onClick='clearInput(&quot;serviceunit" . $where . "&quot;); removeSuggest()'>Tidak ada saran</li></ul></div>";
                        $i = 1;
                    }
                    echo '<script>
                        removeSuggest();
                        elm = $("#inp_serviceunit' . $where . '");
                        posX = elm.position().left;
                        posY = elm.position().top + elm.outerHeight();
                        zIndex = 100;
                        listServiceUnit = "' . $lstServiceUnit . '";
                        $(".content-wrapper").append(listServiceUnit);
                        $("#suggest-serviceunit").css({"top":posY, "left":posX, "height": ' . ($i*24 + 2) . ', "zIndex":zIndex});
                    </script>';
                } else if (isset($_GET["id"])) {
                    $qGetServiceUnit = "SELECT nm_bangsal FROM bangsal WHERE kd_bangsal='" . $_GET["id"] . "'";
                    $getServiceUnit = mysqli_query($connect_app, $qGetServiceUnit);
                    $serviceUnit = mysqli_fetch_assoc($getServiceUnit);
                    echo "<script>
                        $('#inp_serviceunit" . $where . "').val('" . $serviceUnit["nm_bangsal"] . "');
                        $('#inp_serviceunit" . $where . "').attr('disabled', true);
                        $('#hdn_serviceunit" . $where . "').val('" . $_GET["id"] . "');
                    </script>";
                }
                break;
            case "supplier":
                if (isset($_GET["term"])) {
                    $term = $_GET["term"];
                    $qGetSupplier = "SELECT kode_suplier, nama_suplier FROM datasuplier WHERE kode_suplier LIKE '%" . $term . "%' OR nama_suplier LIKE '%" . $term . "%' LIMIT 10";
                    $getSupplier = mysqli_query($connect_app, $qGetSupplier);
                    $lstSupplier = ""; $i = 0;
                    while ($data = mysqli_fetch_assoc($getSupplier)) {
                        $i++;
                        $onClickAct = 'ajax_getData(\"ajax_getdata.php?type=supplier&id=' . $data["kode_suplier"] . '\")';
                        $lstSupplier .= "<li onClick='" . $onClickAct . "'>" . $data["nama_suplier"] . "</li>";
                    }
                    if (strlen($lstSupplier)) {
                        $lstSupplier = "<div id='suggest-supplier' class='suggestion'><ul>" . $lstSupplier . "</ul></div>";
                    } else {
                        $lstSupplier = "<div id='suggest-supplier' class='suggestion'><ul><li onClick='clearInput(&quot;supplier&quot;); removeSuggest()'>Tidak ada saran</li></ul></div>";
                        $i = 1;
                    }
                    echo '<script>
                        removeSuggest();
                        elm = $("#inp_supplier");
                        posX = elm.position().left;
                        posY = elm.position().top + elm.outerHeight();
                        zIndex = 100;
                        listSupplier = "' . $lstSupplier . '";
                        $(".content-wrapper").append(listSupplier);
                        $("#suggest-supplier").css({"top":posY, "left":posX, "height": ' . ($i*24 + 2) . ', "zIndex":zIndex});
                    </script>';
                } else if (isset($_GET["id"])) {
                    $qGetSupplier = "SELECT nama_suplier FROM datasuplier WHERE kode_suplier='" . $_GET["id"] . "'";
                    $getSupplier = mysqli_query($connect_app, $qGetSupplier);
                    $supplier = mysqli_fetch_assoc($getSupplier);
                    echo "<script>
                        $('#inp_supplier').val('" . $supplier["nama_suplier"] . "');
                        $('#hdn_supplier').val('" . $_GET["id"] . "');
                    </script>";
                }
                break;
            case "suppliernm":
                if (isset($_GET["term"])) {
                    $term = $_GET["term"];
                    $qGetSupplier = "SELECT kode_suplier, nama_suplier FROM ipsrssuplier WHERE kode_suplier LIKE '%" . $term . "%' OR nama_suplier LIKE '%" . $term . "%' LIMIT 10";
                    $getSupplier = mysqli_query($connect_app, $qGetSupplier);
                    $lstSupplier = ""; $i = 0;
                    while ($data = mysqli_fetch_assoc($getSupplier)) {
                        $i++;
                        $onClickAct = 'ajax_getData(\"ajax_getdata.php?type=suppliernm&id=' . $data["kode_suplier"] . '\")';
                        $lstSupplier .= "<li onClick='" . $onClickAct . "'>" . $data["nama_suplier"] . "</li>";
                    }
                    if (strlen($lstSupplier)) {
                        $lstSupplier = "<div id='suggest-supplier' class='suggestion'><ul>" . $lstSupplier . "</ul></div>";
                    } else {
                        $lstSupplier = "<div id='suggest-supplier' class='suggestion'><ul><li onClick='clearInput(&quot;supplier&quot;); removeSuggest()'>Tidak ada saran</li></ul></div>";
                        $i = 1;
                    }
                    echo '<script>
                        removeSuggest();
                        elm = $("#inp_supplier");
                        posX = elm.position().left;
                        posY = elm.position().top + elm.outerHeight();
                        zIndex = 100;
                        listSupplier = "' . $lstSupplier . '";
                        $(".content-wrapper").append(listSupplier);
                        $("#suggest-supplier").css({"top":posY, "left":posX, "height": ' . ($i*24 + 2) . ', "zIndex":zIndex});
                    </script>';
                } else if (isset($_GET["id"])) {
                    $qGetSupplier = "SELECT nama_suplier FROM ipsrssuplier WHERE kode_suplier='" . $_GET["id"] . "'";
                    $getSupplier = mysqli_query($connect_app, $qGetSupplier);
                    $supplier = mysqli_fetch_assoc($getSupplier);
                    echo "<script>
                        $('#inp_supplier').val('" . $supplier["nama_suplier"] . "');
                        $('#hdn_supplier').val('" . $_GET["id"] . "');
                    </script>";
                }
                break;
            case "guarantor":
                if (isset($_GET["term"])) {
                    $term = $_GET["term"];
                    $qGetGuarantor = "SELECT kd_pj, png_jawab FROM penjab WHERE kd_pj LIKE '%" . $term . "%' OR png_jawab LIKE '%" . $term . "%'";
                    $getGuarantor = mysqli_query($connect_app, $qGetGuarantor);
                    $lstGuarantor = ""; $i = 0;
                    while ($data = mysqli_fetch_assoc($getGuarantor)) {
                        $i++;
                        $onClickAct = 'ajax_getData(\"ajax_getdata.php?type=guarantor&id=' . $data["kd_pj"] . '\")';
                        $lstGuarantor .= "<li onClick='" . $onClickAct . "'>" . $data["png_jawab"] . "</li>";
                    }
                    if (strlen($lstGuarantor)) {
                        $lstGuarantor = "<div id='suggest-guarantor' class='suggestion'><ul>" . $lstGuarantor . "</ul></div>";
                    } else {
                        $lstGuarantor = "<div id='suggest-guarantor' class='suggestion'><ul><li onClick='clearInput(&quot;guarantor&quot;); removeSuggest()'>Tidak ada saran</li></ul></div>";
                        $i = 1;
                    }
                    echo '<script>
                        removeSuggest();
                        elm = $("#inp_guarantor");
                        posX = elm.position().left;
                        posY = elm.position().top + elm.outerHeight();
                        zIndex = 100;
                        listGuarantor = "' . $lstGuarantor . '";
                        $(".content-wrapper").append(listGuarantor);
                        $("#suggest-guarantor").css({"top":posY, "left":posX, "height": ' . ($i*24 + 2) . ', "zIndex":zIndex});
                    </script>';
                } else if (isset($_GET["id"])) {
                    $qGetGuarantor = "SELECT png_jawab FROM penjab WHERE kd_pj='" . $_GET["id"] . "'";
                    $getGuarantor = mysqli_query($connect_app, $qGetGuarantor);
                    $guarantor = mysqli_fetch_assoc($getGuarantor);
                    echo "<script>
                        $('#inp_guarantor').val('" . $guarantor["png_jawab"] . "');
                        $('#hdn_guarantor').val('" . $_GET["id"] . "');
                    </script>";
                }
                break;
        }
    }
?> 