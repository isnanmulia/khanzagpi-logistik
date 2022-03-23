function ajax_getData(url, return_element) {
    if (url == "") return "";
    else {
        if (window.XMLHttpRequest) xmlhttp = new XMLHttpRequest();
        else xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                resp = this.responseText;
                if (return_element) {
                    retelm = document.getElementById(return_element);
                    retelm.innerHTML = resp;
                } else if (resp.indexOf("<script>") > -1) {
                    resp = (resp.replace("<script>", "")).replace("</script>", "");
                    eval(resp);
                } else eval(resp);
            }
        };
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
        Pace.restart();
    }
}
function ajax_postData(url, thedata, return_element) {
    if (url == "") return "";
    else {
        if (window.XMLHttpRequest) xmlhttp = new XMLHttpRequest();
        else xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                resp = this.responseText();
                if (return_element) {
                    retelm = document.getElementById(return_element);
                    retelm.innerHTML = resp;
                } else if (resp.indexOf("<script>") > -1) {
                    resp = (resp.replace("<script>", "")).replace("</script>", "");
                    eval(resp);
                } else eval(resp);
            }
        };
        xmlhttp.open("POST", url, true);
        xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
        xmlhttp.send(thedata);
        Pace.restart();
    }
}
function removeSuggest() {
    $(".suggestion").remove();
}
function clearInputExam() {
    $("#inp_cari_pemeriksaan").val("");
}
function removeNonNumeric(elmid) {
	elm = $("#" + elmid);
    elm.val((elm.val()).replace(/[^+0-9.,]/g, ''));
}
function getStockDtl(id, type) {
    window.open("stock_detail.php?id=" + id + "&type=" + type, "_blank", "width=600, height=375");
}
function clearInput(id) {
    $("#inp_" + id).val("");
    $("#hdn_" + id).val("");
}
function appendItems(type, items) {
    x = $("#hdn_" + type + "items").val();
    if (x == "") $("#hdn_" + type + "items").val(items);
        else $("#hdn_" + type + "items").val(x + "," + items);
}
function getBangsal() {
    term = $("#inp_bangsal").val();
    if (term.length > 0) ajax_getData("ajax_getdata.php?type=bangsal&term=" + term);
        else removeSuggest();
}
function getSupplier() {
    term = $("#inp_supplier").val();
    if (term.length > 0) ajax_getData("ajax_getdata.php?type=supplier&term=" + term);
        else removeSuggest();
}
function getSupplierNM() {
    term = $("#inp_supplier").val();
    if (term.length > 0) ajax_getData("ajax_getdata.php?type=suppliernm&term=" + term);
        else removeSuggest();
}
function getGuarantor() {
    term = $("#inp_guarantor").val();
    if (term.length > 0) ajax_getData("ajax_getdata.php?type=guarantor&term=" + term);
        else removeSuggest();
}
function getServiceUnit(where="") {
    term = $("#inp_serviceunit" + where).val();
    if (term.length > 0) ajax_getData("ajax_getdata.php?type=serviceunit&where=" + where + "&term=" + term);
        else removeSuggest();
}
function setPackagePrice() {
    price = ($("#inp_hargapaket").val() ? toNumber($("#inp_hargapaket").val()) : 0);
    $("#inp_hargapaket").val(formatNumber(price));
}
function openExpiredPO(type="M", PO_no) {
    ajax_getData("open_expired_po.php?id=" + PO_no + "&type=" + type);
}
function recalculatePO(type="M") {
    items = $("#hdn_POitems").val().split(",");
    ppnpct = ($("#inp_ppn").val() ? toNumber($("#inp_ppn").val()) : 0);
    meterai = ($("#inp_meterai").val() ? toNumber($("#inp_meterai").val()) : 0);
    total1 = 0; totdisc = 0; total2 = 0;
    if (items[0] != ""){
        items.forEach(function(x) {
            if (type == "M") jml = ($("#inp_jml_" + x).val() ? toNumber($("#inp_jml_" + x).val()) : 0);
            else if (type == "N") {
                xjml = $("#inp_jml_" + x).val();
                jml = (xjml ? toFloat(xjml) : 0);
            }
            h_unit = ($("#inp_hpesan_" + x).val() ? toNumber($("#inp_hpesan_" + x).val()) : 0);
            xdisc1 = $("#inp_disc1_" + x).val();
            disc1 = (xdisc1 ? toFloat(xdisc1) : 0);
            xdisc2 = $("#inp_disc2_" + x).val();
            disc2 = (xdisc2 ? toFloat(xdisc2) : 0);
            hpesan = jml * h_unit;
            disc = disc1 + ((100 - disc1)*disc2/100);
            ndisc = Math.round(hpesan * disc/100);
            total = hpesan - ndisc;
            total1 += hpesan;
            totdisc += ndisc;
            if (type == "M") $("#inp_jml_" + x).val(formatNumber(jml));
            else if (type == "N") {
                xxjml = formatNumber(jml.toString().replace(/\./g, ','));
                if (xjml!=xxjml) $("#inp_jml_" + x).val(xjml);
                    else $("#inp_jml_" + x).val(xxjml);
            }
            $("#inp_hpesan_" + x).val(formatNumber(h_unit));
            $("#span_subtotal_" + x).html(formatNumber(hpesan));
            $("#hdn_subtotal_" + x).val(hpesan);
            xxdisc1 = formatNumber(disc1.toString().replace(/\./g, ','));
            if (xdisc1!=xxdisc1) $("#inp_disc1_" + x).val(xdisc1);
                else $("#inp_disc1_" + x).val(xxdisc1);
            xxdisc2 = formatNumber(disc2.toString().replace(/\./g, ','));
            if (xdisc2!=xxdisc2) $("#inp_disc2_" + x).val(xdisc2);
                else $("#inp_disc2_" + x).val(xxdisc2);
            $("#inp_disc2_" + x).val(formatNumber(disc2));
            $("#span_ndisc_" + x).html(formatNumber(ndisc));
            $("#hdn_ndisc_" + x).val(ndisc);
            $("#span_total_" + x).html(formatNumber(total));
            $("#hdn_total_" + x).val(total);
        });
    }
    total2 = total1 - totdisc;
    ppn = Math.round(total2 * ppnpct/100);
    jml_tagihan = total2 + ppn + meterai;
    $("#span_total1").html(formatNumber(total1));
    $("#hdn_total1").val(total1);
    $("#span_potongan").html(formatNumber(totdisc));
    $("#hdn_potongan").val(totdisc);
    $("#span_total2").html(formatNumber(total2));
    $("#hdn_total2").val(total2);
    $("#inp_ppn").val(formatNumber(ppnpct));
    $("#span_ppn").html(formatNumber(ppn));
    $("#hdn_ppn").val(ppn);
    $("#inp_meterai").val(formatNumber(meterai));
    $("#span_jml_tagihan").html(formatNumber(jml_tagihan));
    $("#hdn_jml_tagihan").val(jml_tagihan);
}
function recalculateRcp(type="M") {
    items = $("#hdn_Rcpitems").val().split(",");
    ppnpct = ($("#inp_ppn").val() ? toNumber($("#inp_ppn").val()) : 0);
    meterai = ($("#inp_meterai").val() ? toNumber($("#inp_meterai").val()) : 0);
    total1 = 0; totdisc = 0; total2 = 0;
    if (items[0] != "") {
        items.forEach(function(x) {
            h_unit = ($("#inp_hpesan_" + x).val() ? toNumber($("#inp_hpesan_" + x).val()) : 0);
            if (type == "M") terima = ($("#inp_diterima_" + x).val() ? toNumber($("#inp_diterima_" + x).val()) : 0);
            else if (type == "N") {
                xterima = $("#inp_diterima_" + x).val();
                terima = (xterima ? toFloat(xterima) : 0);
            }
            disc1 = ($("#span_disc1_" + x).html() ? toFloat($("#span_disc1_" + x).html().replace(/\./g, ',')) : 0);
            disc2 = ($("#span_disc2_" + x).html() ? toFloat($("#span_disc2_" + x).html().replace(/\./g, ',')) : 0);
            disc = disc1 + ((100 - disc1)*disc2/100);
            hpesan = h_unit * terima;
            ndisc = Math.round(hpesan * disc/100);
            total = hpesan - ndisc;
            total1 += hpesan;
            totdisc += ndisc;
            $("#inp_hpesan_" + x).val(formatNumber(h_unit));
            if (type == "M") $("#inp_diterima_" + x).val(formatNumber(terima));
            else if (type == "N") {
                xxterima = formatNumber(terima.toString().replace(/\./g, ','));
                if (xterima!=xxterima) $("#inp_diterima_" + x).val(xterima);
                    else $("#inp_diterima_" + x).val(xxterima);
            }
            $("#span_subtotal_" + x).html(formatNumber(hpesan));
            $("#hdn_subtotal_" + x).val(hpesan);
            $("#span_ndisc_" + x).html(formatNumber(ndisc));
            $("#hdn_ndisc_" + x).val(ndisc);
            $("#span_total_" + x).html(formatNumber(total));
            $("#hdn_total_" + x).val(total);
        });
    }
    total2 = total1 - totdisc;
    ppn = Math.round(total2 * ppnpct/100);
    jml_tagihan = total2 + ppn + meterai;
    $("#span_total1").html(formatNumber(total1));
    $("#hdn_total1").val(total1);
    $("#span_potongan").html(formatNumber(totdisc));
    $("#hdn_potongan").val(totdisc);
    $("#span_total2").html(formatNumber(total2));
    $("#hdn_total2").val(total2);
    $("#inp_ppn").val(formatNumber(ppnpct));
    $("#span_ppn").html(formatNumber(ppn));
    $("#hdn_ppn").val(ppn);
    $("#inp_meterai").val(formatNumber(meterai));
    $("#span_jml_tagihan").html(formatNumber(jml_tagihan));
    $("#hdn_jml_tagihan").val(jml_tagihan);
}
function recalculateInvoice() {
    items = $("#hdn_invoiceitems").val().split(",");
    total = 0;
    if (items[0] != "") {
        items.forEach(function(x) {
            piutang = toNumber($("#hdn_jmlpiutang_" + x).val());
            total += piutang;
        });
    }
    $("#span_jml_tagihan").html(formatNumber(total));
    $("#hdn_jml_tagihan").val(total);
}
function recheckRequest(type="M") {
    items = $("#hdn_Requestitems").val().split(",");
    if (items[0] != "") {
        items.forEach(function(x) {
            if (type == "M") {
                jml = ($("#inp_jml_" + x).val() ? toNumber($("#inp_jml_" + x).val()) : 0);
                $("#inp_jml_" + x).val(formatNumber(jml));
            } else if (type == "N") {
                xjml = $("#inp_jml_" + x).val();
                jml = (xjml ? toFloat(xjml) : 0);
                xxjml = formatNumber(jml.toString().replace(/\./g, ','));
                if (xjml!=xxjml) $("#inp_jml_" + x).val(xjml);
                    else $("#inp_jml_" + x).val(xxjml);
            }
        });
    }
}
function recheckTransfer(type="M") {
    items = $("#hdn_Transferitems").val().split(",");
    if (items[0] != "") {
        items.forEach(function(x) {
            if (type == "M") {
                jml = ($("#inp_jml_" + x).val() ? toNumber($("#inp_jml_" + x).val()) : 0);
                $("#inp_jml_" + x).val(formatNumber(jml));
            } else if (type == "N") {
                xjml = $("#inp_jml_" + x).val();
                jml = (xjml ? toFloat(xjml) : 0);
                xxjml = formatNumber(jml.toString().replace(/\./g, ','));
                if (xjml!=xxjml) $("#inp_jml_" + x).val(xjml);
                    else $("#inp_jml_" + x).val(xxjml);
            }
        });
    }
}
function recalculatePR(type="M") {
    items = $("#hdn_PRitems").val().split(",");
    total = 0;
    if (items[0] != "") {
        items.forEach(function(x) {
            if (type == "M") jml = ($("#inp_jml_" + x).val() ? toNumber($("#inp_jml_" + x).val()) : 0);
            else if (type == "N") {
                xjml = $("#inp_jml_" + x).val();
                jml = (xjml ? toFloat(xjml) : 0);
            }
            h_unit = ($("#span_hpesan_" + x).html() ? toNumber($("#span_hpesan_" + x).html()) : 0);
            hpesan = h_unit * jml;
            total += hpesan;
            if (type == "M") $("#inp_jml_" + x).val(formatNumber(jml));
            else if (type == "N") {
                xxjml = formatNumber(jml.toString().replace(/\./g, ','));
                if (xjml!=xxjml) $("#inp_jml_" + x).val(xjml);
                    else $("#inp_jml_" + x).val(xxjml);
            }
            $("#span_subtotal_" + x).html(formatNumber(hpesan));
            $("#hdn_subtotal_" + x).val(hpesan);
        });
    }
    $("#span_total").html(formatNumber(total));
    $("#hdn_total").val(total);
}
function recheckSO() {
    items = $("#hdn_SOitems").val().split(",");
    tothil = 0;
    totleb = 0;
    if (items[0] != "") {
        items.forEach(function(x) {
            xjml = $("#inp_jml_" + x).val();
            jml = (xjml ? toFloat(xjml) : 0);
            harga = $("#hdn_dasar_" + x).val();
            stok = $("#hdn_stokasal_" + x).val();
            selisih = 0; lebih = 0; nomhilang = 0; nomlebih = 0;
            if (jml > stok) {
                lebih = ((jml*100) - (stok*100))/100;
                nomlebih = lebih * harga;
                totleb += nomlebih;
            } else if (jml < stok) {
                selisih = ((stok*100) - (jml*100))/100;
                nomhilang = selisih * harga;
                tothil += nomhilang;
            }
            console.log(stok+" "+jml+" "+selisih+" "+lebih);
            $("#span_selisih_" + x).html(formatNumber(selisih.toString().replace(/\./g, ',')));
            $("#hdn_selisih_" + x).val(selisih);
            $("#span_lebih_" + x).html(formatNumber(lebih.toString().replace(/\./g, ',')));
            $("#hdn_lebih_" + x).val(lebih);
            $("#span_nomhilang_" + x).html(formatNumber(nomhilang.toString().replace(/\./g, ',')));
            $("#hdn_nomhilang_" + x).val(nomhilang);
            $("#span_nomlebih_" + x).html(formatNumber(nomlebih.toString().replace(/\./g, ',')));
            $("#hdn_nomlebih_" + x).val(nomlebih);
            xxjml = formatNumber(jml.toString().replace(/\./g, ','));
            if (xjml!=xxjml) $("#inp_jml_" + x).val(xjml);
                else $("#inp_jml_" + x).val(xxjml);
        });
    }
    $("#span_nom_hilang").html(formatNumber(tothil.toString().replace(/\./g, ',')));
    $("#inp_nom_hilang").val(tothil);
    $("#span_nom_lebih").html(formatNumber(totleb.toString().replace(/\./g, ',')));
    $("#inp_nom_lebih").val(totleb);

}
function recheckMinMax(type) {
    items = $("#hdn_MinMaxitems").val().split(",");
    idx = 0;
    if (items[0] != "") {
        items.forEach(function(x) {
            if ($("#inp_min_" + x).length) {
                if ($("#inp_min_" + x).css("display")!="none") idx++;
                min = ($("#inp_min_" + x).val() ? toNumber($("#inp_min_" + x).val()) : 0);
                $("#inp_min_" + x).val(formatNumber(min));
                max = ($("#inp_max_" + x).val() ? toNumber($("#inp_max_" + x).val()) : 0);
                $("#inp_max_" + x).val(formatNumber(max));
            }
        });
    }
    if (idx) $("#btn_submit").prop("disabled", "");
        else $("#btn_submit").prop("disabled", "disabled");
}
function recheckPRApproval(PR_no, type="M") {
    items = $("#hdn_PRDtl_" + PR_no).val().split("|");
    lstafter = "";
    items.forEach(function(x) {
        itm = x.split(":");
        if (type == "M") {
            jml = ($("#inp_disetujui_" + itm[0] + "_" + PR_no).val() ? toNumber($("#inp_disetujui_" + itm[0] + "_" + PR_no).val()) : 0);
            $("#inp_disetujui_" + itm[0] + "_" + PR_no).val(formatNumber(jml));
        } else if (type == "N") {
            xjml = $("#inp_disetujui_" + itm[0] + "_" + PR_no).val();
            jml = (xjml ? toFloat(xjml) : 0);
            xxjml = formatNumber(jml.toString().replace(/\./g, ','));
            if (xjml!=xxjml) $("#inp_disetujui_" + itm[0] + "_" + PR_no).val(xjml);
                else $("#inp_disetujui_" + itm[0] + "_" + PR_no).val(xxjml);
        }
        lstafter += (lstafter.length ? "|" : "") + itm[0] + ":" + itm[1] + ":" + jml;
    });
    $("#hdn_PRDtl_" + PR_no).val(lstafter);
}
function resizeDivScroll() {
    wdw = window.innerWidth;
    sdbr = $(".main-sidebar").width();
    $("#div-scroll").width(wdw - (sdbr + 150 + 50));
}
function resizeSpacer() {
    divscroll = $("#div-scroll").width();
    width = Math.floor((divscroll - 950)/10);
    $(".inline-spacer").css("margin","0 "+width+"px");
}
function formatDateYMD(date) {
    dt = new Date(date);
    y = '' + dt.getFullYear();
    m = '' + (dt.getMonth() + 1);
    d = '' + dt.getDate();
    if (m.length < 2) m = '0' + m;
    if (d.length < 2) d = '0' + d;
    return [y, m, d].join("-");
}
function closeNotif() {
    setTimeout(function() {
        $(".alert").fadeOut(500, function() {
            $(this).remove();
        });
    }, 5000);
}
/* function rcpItemsQtyCount(id) {
    arrive = $("#inp_diterima_" + id).val();
    total = $("#span_jml_" + id).html();
    received = $("#span_sdhditerima_" + id).html();
    remain = total - received;
    if (arrive > remain) {
        alert("Jumlah Barang Datang tidak boleh lebih dari " + remain);
        $("#inp_diterima_" + id).focus();
        return false;
    }
    return true;
} */

// src: https://blog.abelotech.com/posts/number-currency-formatting-javascript/
function formatNumber(num) {
    return num.toString().replace(/\./g, ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
}
// https://css-tricks.com/snippets/javascript/get-url-variables/
function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i=0;i<vars.length;i++) {
            var pair = vars[i].split("=");
            if(pair[0] == variable){ return pair[1]; }
    }
    return(false);
}
function toNumber(strnum) {
    return parseInt(strnum.replace(/\./g, ''))
}
function toFloat(strnum) {
    return parseFloat(strnum.replace(/\./g, '').replace(/\,/g, '.'));
}
function disableForm() {
    $(":input").prop("disabled", true);
}