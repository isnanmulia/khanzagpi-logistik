<?php
    $URI = explode("/", $_SERVER["REQUEST_URI"]);
    $base_url = "http://" . $_SERVER["SERVER_NAME"] . "/" . $URI[1];
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Aplikasi Utilitas Khanza - GPI</title>
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?php echo $base_url ?>/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo $base_url ?>/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Pace style -->
    <link rel="stylesheet" href="<?php echo $base_url ?>/plugins/pace/pace.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo $base_url ?>/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
        folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo $base_url ?>/dist/css/skins/_all-skins.min.css">
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  </head>
  <body class="hold-transition skin-green sidebar-mini">
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
                  <span class="hidden-xs">Username</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="user-header"><p>Username</p></li>
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
            <li><a href="#"><i class="fa fa-home"></i> <span>Beranda</span></a></li>
            <li><a href="#"><i class="fa fa-note"></i> <span>Template Laboratorium</span></a></li>
          </ul>
        </section>
      </aside>
      <!-- main content wrapper -->
      <div class="content-wrapper">
        <section class="content-header">
          <h1>Menu Title</h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-home"></i> Beranda</a></li>
            <?php // if ($menu_title != "Beranda") { ?>
              <li class="active"><?php // echo $menu_title ?></li>
            <?php // } ?>
          </ol>
        </section>
        <section class="content">
        <!-- content goes here -->
        </section>
      </div>
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          Template powered by <a href="https://adminlte.io" target="_blank">AdminLTE</a>.
        </div>
        <strong>&copy; 2019 <?php if (date("Y") > 2019) echo "- " . date("Y") ?> <a href="#">RS Grha Permata Ibu</a>.</strong> All rights reserved.
      </footer>
    </div>
    <!-- jQuery 3 -->
    <script src="<?php echo $base_url ?>/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="<?php echo $base_url ?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- PACE -->
    <script src="<?php echo $base_url ?>/bower_components/PACE/pace.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo $base_url ?>/dist/js/adminlte.min.js"></script>
  </body>
</html>