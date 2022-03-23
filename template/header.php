<?php
  require_once '../config/session_check.php';
  require_once '../function/access.php';
  $filename = explode("?", $URI[3])[0];
  $menu_title = ""; $group = ""; $breadcrumb = "";
  $cnt_pdftrn = 0; $cnt_ranap = 0; $cnt_lab = 0; $cnt_logmed = 0; $cnt_lognmed = 0; $cnt_keu = 0;
  switch ($filename) {
    case "index.php": $menu_title = "Beranda"; break;
    // logmed
    case "add_jit_medicine.php": $menu_title = "Tambah Obat JIT"; $group = "logmed"; $breadcrumb = "Logistik Medis|Tambah Obat JIT"; break;
    case "large_units_m.php": $menu_title = "Satuan Besar Obat & BHP"; $group = "logmed"; $breadcrumb = "Logistik Medis|Satuan Besar Obat & BHP"; break;
    case "set_large_unit_m.php": $menu_title = "Atur Satuan Besar Obat & BHP"; $group = "logmed"; $breadcrumb = "Logistik Medis|Satuan Besar Obat & BHP|Atur Satuan Besar Obat & BHP"; break;
    case "price_history_m.php": $menu_title = "Riwayat Harga Obat & BHP"; $group = "logmed"; $breadcrumb = "Logistik Medis|Riwayat Harga Obat & BHP"; break;
    case "add_price_history_m.php": $menu_title = "Tambah Riwayat Harga Obat & BHP"; $group = "logmed"; $breadcrumb = "Logistik Medis|Riwayat Harga Obat & BHP|Tambah Riwayat Harga Obat & BHP"; break;
    case "minmax_m.php": $menu_title = "Stok Minimal/Maksimal Per Unit"; $group = "logmed"; $breadcrumb = "Logistik Medis|Stok Minimal/Maksimal Per Unit"; break;
    case "add_minmax_m.php": $menu_title = "Tambah Stok Minimal/Maksimal Per Unit"; $group = "logmed"; $breadcrumb = "Logistik Medis|Stok Minimal/Maksimal Per Unit|Tambah Stok Minimal/Maksimal Per Unit"; break;
    case "purchase_order_m.php": $menu_title = "Surat Pemesanan Obat & BHP"; $group = "logmed"; $breadcrumb = "Logistik Medis|Surat Pemesanan Obat & BHP"; break;
    case "add_purchase_order_m.php": $menu_title = "Buat Surat Pemesanan Obat & BHP"; $group = "logmed"; $breadcrumb = "Logistik Medis|Surat Pemesanan Obat & BHP|Buat Surat Pemesanan Obat & BHP"; break;
    case "edit_purchase_order_m.php": $menu_title = "Ubah Surat Pemesanan Obat & BHP"; $group = "logmed"; $breadcrumb = "Logistik Medis|Surat Pemesanan Obat & BHP|Ubah Surat Pemesanan Obat & BHP"; break;
    case "po_reception_m.php": $menu_title = "Penerimaan Obat & BHP"; $group = "logmed"; $breadcrumb = "Logistik Medis|Penerimaan Obat & BHP"; break;
    case "add_po_reception_m.php": $menu_title = "Tambah Penerimaan Obat & BHP"; $group = "logmed"; $breadcrumb = "Logistik Medis|Penerimaan Obat & BHP|Tambah Penerimaan Obat & BHP"; break;
    case "edit_po_reception_m.php": $menu_title = "Ubah Penerimaan Obat & BHP"; $group = "logmed"; $breadcrumb = "Logistik Medis|Penerimaan Obat & BHP|Ubah Penerimaan Obat & BHP"; break;
    case "procurement_history_m.php": $menu_title = "Riwayat Pengadaan Obat & BHP"; $group = "logmed"; $breadcrumb = "Logistik Medis|Riwayat Pengadaan Obat & BHP"; break;
    // lognmed
    case "large_units_nm.php": $menu_title = "Satuan Besar Barang Non Medis"; $group = "lognmed"; $breadcrumb = "Logistik Non Medis|Satuan Besar Barang Non Medis"; break;
    case "set_large_unit_nm.php": $menu_title = "Atur Satuan Besar Barang Non Medis"; $group = "lognmed"; $breadcrumb = "Logistik Non Medis|Atur Satuan Besar Barang Non Medis"; break;
    case "price_history_nm.php": $menu_title = "Riwayat Harga Barang Non Medis"; $group = "lognmed"; $breadcrumb = "Logistik Non Medis|Riwayat Harga Barang Non Medis"; break;
    case "add_price_history_nm.php": $menu_title = "Tambah Riwayat Harga Barang Non Medis"; $group = "lognmed"; $breadcrumb = "Logistik Non Medis|Riwayat Harga Barang Non Medis|Tambah Riwayat Harga Barang Non Medis"; break;
    case "minmax_nm.php": $menu_title = "Stok Minimal/Maksimal Per Unit"; $group = "lognmed"; $breadcrumb = "Logistik Non Medis|Stok Minimal/Maksimal Per Unit"; break;
    case "add_minmax_nm.php": $menu_title = "Tambah Stok Minimal/Maksimal Per Unit"; $group = "lognmed"; $breadcrumb = "Logistik Non Medis|Stok Minimal/Maksimal Per Unit|Tambah Stok Minimal/Maksimal Per Unit"; break;
    case "item_request_nm.php": $menu_title = "Permintaan Barang Non Medis"; $group = "lognmed"; $breadcrumb = "Logistik Non Medis|Permintaan Barang Non Medis"; break;
    case "add_item_request_nm.php": $menu_title = "Buat Permintaan Barang Non Medis"; $group = "lognm"; $breadcrumb = "Logistik Non Medis|Permintaan Non Medis|Buat Permintaan Barang Non Medis"; break;
    case "edit_item_request_nm.php": $menu_title = "Ubah Permintaan Barang Non Medis"; $group = "lognm"; $breadcrumb = "Logistik Non Medis|Permintaan Barang Non Medis|Ubah Permintaan Barang Non Medis"; break;
    case "item_transfer_nm.php": $menu_title = "Mutasi Barang Non Medis"; $group = "lognm"; $breadcrumb = "Logistik Non Medis|Mutasi Barang Non Medis"; break;
    case "add_item_transfer_nm.php": $menu_title = "Tambah Mutasi Barang Non Medis"; $group = "lognm"; $breadcrumb = "Logistik Non Medis|Mutasi Barang Non Medis|Tambah Mutasi Barang Non Medis"; break;
    case "stock_opname_nm.php": $menu_title = "Stok Opname Non Medis"; $group = "lognmed"; $breadcrumb = "Logistik Non Medis|Stok Opname Non Medis"; break;
    case "add_stock_opname_nm.php": $menu_title = "Input Stok Opname Non Medis"; $group = "lognmed"; $breadcrumb = "Logistik Non Medis|Stok Opname Non Medis|Input Stok Opname Non Medis"; break;
    case "purchase_request_nm.php": $menu_title = "Pengajuan Barang Non Medis"; $group = "lognmed"; $breadcrumb = "Logistik Non Medis|Pengajuan Barang Non Medis"; break;
    case "add_purchase_request_nm.php": $menu_title = "Tambah Pengajuan Barang Non Medis"; $group = "lognmed"; $breadcrumb = "Logistik Non Medis|Pengajuan Barang Non Medis|Tambah Pengajuan Barang Non Medis"; break;
    case "purchase_order_nm.php": $menu_title = "Surat Pemesanan Non Medis"; $group="lognmed"; $breadcrumb = "Logistik Non Medis|Surat Pemesanan Non Medis"; break;
    case "add_purchase_order_nm.php": $menu_title = "Buat Surat Pemesanan Non Medis"; $group="lognmed"; $breadcrumb = "Logistik Non Medis|Surat Pemesanan Non Medis|Buat Surat Pemesanan Non Medis"; break;
    case "edit_purchase_order_nm.php": $menu_title = "Ubah Surat Pemesanan Non Medis"; $group = "lognmed"; $breadcrumb = "Logistik Non Medis|Surat Pemesanan Non Medis|Ubah Surat Pemesanan Non Medis"; break;
    case "po_reception_nm.php": $menu_title = "Penerimaan Barang Non Medis"; $group = "lognmed"; $breadcrumb = "Logistik Non Medis|Penerimaan Barang Non Medis"; break;
    case "add_po_reception_nm.php": $menu_title = "Tambah Penerimaan Barang Non Medis"; $group = "lognmed"; $breadcrumb = "Logistik Non Medis|Penerimaan Barang Non Medis|Tambah Penerimaan Barang Non Medis"; break;
    case "edit_po_reception_nm.php": $menu_title = "Ubah Penerimaan Barang Non Medis"; $group = "lognmed"; $breadcrumb = "Logistik Non Medis|Penerimaan Barang Non Medis|Ubah Penerimaan Barang Non Medis"; break;
    case "procurement_history_nm.php": $menu_title = "Riwayat Pengadaan Barang Non Medis"; $group = "lognmed"; $breadcrumb = "Logistik Non Medis|Riwayat Pengadaan Barang Non Medis"; break;
    // approval
    case "po_approvals.php": $menu_title = "Persetujuan Surat Pemesanan"; $group = ""; $breadcrumb = "Persetujuan Surat Pemesanan"; break;
    // report
    case "report_list.php": $menu_title = "Laporan"; $group = ""; $breadcrumb = "Laporan"; break;
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $menu_title ?> | Aplikasi Utilitas Khanza GPI</title>
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?php echo $base_url ?>/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo $base_url ?>/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo $base_url ?>/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="<?php echo $base_url ?>/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <!-- Pace style -->
    <link rel="stylesheet" href="<?php echo $base_url ?>/plugins/pace/pace.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo $base_url ?>/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
        folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo $base_url ?>/dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?php echo $base_url ?>/styles/style.css">
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <!-- jQuery 3 -->
    <script src="<?php echo $base_url ?>/bower_components/jquery/dist/jquery.min.js"></script>
  </head>
  <body class="hold-transition skin-green sidebar-mini sidebar-collapse" onClick="removeSuggest()">
    <div id="div-notif"></div>
    <div class="wrapper">
      <!-- header -->
      <header class="main-header">
        <a href="#" class="logo">
          <span class="logo-mini"><b>K</b>G</span>
          <span class="logo-lg"><b>Khanza</b> GPI</span>
        </a>
        <nav class="navbar navbar-static-top">
          <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle Navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <span class="hidden-xs"><?php echo $_SESSION["USER"]["USERNAME"] ?></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="user-header">
                    <p><?php echo $_SESSION["USER"]["USERNAME"] ?></p>
                    <p><?php echo $_SESSION["USER"]["FULLNAME"] ?></p>
                  </li>
                  <li class="user-footer"><div class="pull-right"><a href="../logout.php" class="btn btn-default btn-flat"><i class="fa fa-sign-out"></i> Keluar</a></div></li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!-- sidebar -->
      <aside class="main-sidebar">
        <section class="sidebar">
          <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MENU</li>
            <li <?php if ($filename == "index.php") { ?>class="active"<?php } ?>><a href="<?php echo $base_url ?>/app/index.php"><i class="fa fa-home"></i> <span>Beranda</span></a></li>
            <li id="hdr_logmed" class="treeview<?php if ($group == "logmed") { ?> active menu-open<?php } ?>">
              <a href="#"><i class="fa fa-medkit"></i> <span>Logistik Medis</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
              <ul class="treeview-menu">
                <?php if (showMenuByAccess("penjualan_obat")) { $cnt_logmed++; ?>
                  <li <?php if ($filename == "add_jit_medicine.php") { ?>class="active"<?php } ?>><a href="<?php echo $base_url ?>/app/add_jit_medicine.php"><i class="fa fa-circle-o"></i> Tambah Obat JIT</a></li>
                <?php } ?>
                <!-- li <?php if ($filename == "large_units_m.php") { ?>class="active"<?php } ?>><a href="<?php echo $base_url ?>/app/large_units_m.php"><i class="fa fa-circle-o"></i> Satuan Besar Obat & BHP</a></li -->
                <?php if (showMenuByAccess("obat")) { $cnt_logmed++; ?>
                  <li <?php if ($filename == "price_history_m.php") { ?>class="active"<?php } ?>><a href="<?php echo $base_url ?>/app/price_history_m.php"><i class="fa fa-circle-o"></i> Riwayat Harga Obat & BHP</a></li>
                <?php } ?>
                <?php if (showMenuByAccess("rekap_pemesanan")) { $cnt_logmed++; ?>
                  <li <?php if ($filename == "minmax_m.php") { ?>class="active"<?php } ?>><a href="<?php echo $base_url ?>/app/minmax_m.php"><i class="fa fa-circle-o"></i> Stok Minimal/Maksimal Per Unit</a></li>
                <?php } ?>
                <?php if (showMenuByAccess("surat_pemesanan_medis")) { $cnt_logmed++; ?>
                  <li <?php if ($filename == "purchase_order_m.php") { ?>class="active"<?php } ?>><a href="<?php echo $base_url ?>/app/purchase_order_m.php"><i class="fa fa-circle-o"></i> Surat Pemesanan Obat & BHP</a></li>
                <?php } ?>
                <?php if (showMenuByAccess("pemesanan_obat")) { $cnt_logmed++; ?>
                  <li <?php if ($filename == "po_reception_m.php") { ?>class="active"<?php } ?>><a href="<?php echo $base_url ?>/app/po_reception_m.php"><i class="fa fa-circle-o"></i> Penerimaan Obat & BHP</a></li>
                <?php } ?>
                <?php if (showMenuByAccess("surat_pemesanan_medis") || showMenuByAccess("pemesanan_obat")) { $cnt_logmed++; ?>
                  <li <?php if ($filename == "procurement_history_m.php") { ?>class="active"<?php } ?>><a href="<?php echo $base_url ?>/app/procurement_history_m.php"><i class="fa fa-circle-o"></i> Riwayat Pengadaan Obat & BHP</a></li>
                <?php } ?>
              </ul>
            </li>
            <li id="hdr_lognmed" class="treeview<?php if ($group == "lognmed") { ?> active menu-open<?php } ?>">
              <a href="#"><i class="fa fa-cubes"></i> <span>Logistik Non Medis</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
              <ul class="treeview-menu">
                <?php if (showMenuByAccess("ipsrs_barang")) { $cnt_lognmed++; ?>
                  <li <?php if ($filename == "large_units_nm.php") { ?>class="active"<?php } ?>><a href="<?php echo $base_url ?>/app/large_units_nm.php"><i class="fa fa-circle-o"></i> <span>Satuan Besar Barang Non Medis</span></a></li>
                <?php } ?>
                <?php if (showMenuByAccess("ipsrs_barang")) { $cnt_lognmed++; ?>
                  <li <?php if ($filename == "price_history_nm.php") { ?>class="active"<?php } ?>><a href="<?php echo $base_url ?>/app/price_history_nm.php"><i class="fa fa-circle-o"></i> <span>Riwayat Harga Barang Non Medis</span></a></li>
                <?php } ?>
                <?php if (showMenuByAccess("rekap_pemesanan_non_medis")) { $cnt_lognmed++; ?>
                  <li <?php if ($filename == "minmax_nm.php") { ?>class="active"<?php } ?>><a href="<?php echo $base_url ?>/app/minmax_nm.php"><i class="fa fa-circle-o"></i> Stok Minimal/Maksimal Per Unit</a></li>
                <?php } ?>
                <?php if (showMenuByAccess("permintaan_non_medis")) { $cnt_lognmed++; ?>
                  <li <?php if ($filename == "item_request_nm.php") { ?>class="active"<?php } ?>><a href="<?php echo $base_url ?>/app/item_request_nm.php"><i class="fa fa-circle-o"></i> <span>Permintaan Barang Non Medis</span></a></li>
                <?php } ?>
                <?php if (showMenuByAccess("rekap_permintaan_non_medis")) { $cnt_lognmed++; ?>
                  <li <?php if ($filename == "item_transfer_nm.php") { ?>class="active"<?php } ?>><a href="<?php echo $base_url ?>/app/item_transfer_nm.php"><i class="fa fa-circle-o"></i> <span>Mutasi Barang Non Medis</span></a></li>
                <?php } ?>
                <?php if (showMenuByAccess("stok_opname_logistik")) { $cnt_lognmed++; ?>
                  <li <?php if ($filename == "stock_opname_nm.php") { ?>class="active"<?php } ?>><a href="<?php echo $base_url ?>/app/stock_opname_nm.php"><i class="fa fa-circle-o"></i> <span>Stok Opname Non Medis</span></a></li>
                <?php } ?>
                <?php if (showMenuByAccess("pengajuan_barang_nonmedis")) { $cnt_lognmed++; ?>
                  <li <?php if ($filename == "purchase_request_nm.php") { ?>class="active"<?php } ?>><a href="<?php echo $base_url ?>/app/purchase_request_nm.php"><i class="fa fa-circle-o"></i> <span>Pengajuan Barang Non Medis</span></a></li>
                <?php } ?>
                <?php if (showMenuByAccess("surat_pemesanan_non_medis")) { $cnt_lognmed++; ?>
                  <li <?php if ($filename == "purchase_order_nm.php") { ?>class="active"<?php } ?>><a href="<?php echo $base_url ?>/app/purchase_order_nm.php"><i class="fa fa-circle-o"></i> <span>Surat Pemesanan Non Medis</span></a></li>
                <?php } ?>
                <?php if (showMenuByAccess("penerimaan_non_medis")) { $cnt_lognmed++; ?>
                  <li <?php if ($filename == "po_reception_nm.php") { ?>class="active"<?php } ?>><a href="<?php echo $base_url ?>/app/po_reception_nm.php"><i class="fa fa-circle-o"></i> <span>Penerimaan Barang Non Medis</span></a></li>
                <?php } ?>
                <?php if (showMenuByAccess("surat_pemesanan_non_medis") || showMenuByAccess("penerimaan_non_medis")) { $cnt_lognmed++; ?>
                  <li <?php if ($filename == "procurement_history_nm.php") { ?>class="active"<?php } ?>><a href="<?php echo $base_url ?>/app/procurement_history_nm.php"><i class="fa fa-circle-o"></i> Riwayat Pengadaan Non Medis</a></li>
                <?php } ?>
              </ul>
            </li>
            <li id="hdr_approval" class="treeview<?php if ($group == "approval") { ?> active menu-open<?php } ?>">
              <a href="#"><i class="fa fa-check-square-o"></i> <span>Persetujuan</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
              <ul class="treeview-menu">
                <?php if (showApproval()) { ?>
                  <li <?php if ($filename == "po_approvals.php") { ?>class="active"<?php } ?>><a href="<?php echo $base_url ?>/app/po_approvals.php"><i class="fa fa-circle-o"></i> <span>Persetujuan Surat Pemesanan</span></a></li>
                <?php } ?>
              </ul>
            </li>
            <li id="hdr_report" class="treeview<?php if ($group == "report") { ?> active menu-open<?php } ?>">
              <a href="#"><i class="fa fa-sticky-note"></i> <span>Laporan</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
              <ul class="treeview-menu">
                <li <?php if ($filename == "report_list.php") { ?>class="active"<?php } ?>><a href="<?php echo $base_url ?>/app/report_list.php"><i class="fa fa-circle-o"></i> <span>Daftar Laporan</span></a></li>
              </ul>
            </li>
          </ul>
        </section>
      </aside>
      <!-- main content wrapper -->
      <div class="content-wrapper">
        <section class="content-header">
          <h1><?php echo $menu_title ?></h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-home"></i> Beranda</a></li>
            <?php 
              if (strlen($breadcrumb)) {
                  $breadcrumb = explode("|", $breadcrumb);
                  for ($i=0; $i < count($breadcrumb); $i++) {
                      if ($i == count($breadcrumb) - 1) echo '<li class="active">' . $breadcrumb[$i] . '</li>';
                      else echo '<li>' . $breadcrumb[$i] . '</li>';
                  }
              }
          ?>
          </ol>
        </section>
        <section class="content">