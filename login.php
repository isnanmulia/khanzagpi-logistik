<?php
    require_once 'config/base_url.php';
    session_set_cookie_params(0, "/khanzagpi_ol/");
    session_start();
    // session checking
    if (isset($_SESSION["USER"])) {
        $_SESSION["LAST_ACTIVITY"] = time();
        header("Location: app/index.php");
    }
?>

<head>
  <title>Masuk | Aplikasi Utilitas Khanza - GPI</title>
  <link rel="stylesheet" href="<?php echo $base_url ?>/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo $base_url ?>/bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo $base_url ?>/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="<?php echo $base_url ?>/styles/style.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <img src="images/LogoGPI.png" /> Khanza <strong>GPI</strong>
    </div>
    <div class="login-box-body">
      <p class="login-box-msg">Masuk untuk memulai sesi Anda</p>
      <form name="frmLogin" action="validate_login.php" method="POST">
        <div class="form-group has-feedback">
          <input type="password" name="inp_username" id="inp_username" placeholder="Username" class="form-control" autocomplete="off" /><span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="password" name="inp_password" id="inp_password" placeholder="Password" class="form-control" autocomplete="off" /><span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
          <div class="col-xs-8"></div>
          <div class="col-xs-4">
            <input type="hidden" id="form_submit" name="form_submit" value="1">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Masuk <i class="fa fa-arrow-circle-right"></i></button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <script src="<?php echo $base_url ?>/bower_components/jquery/dist/jquery.min.js"></script>
  <script src="<?php echo $base_url ?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <script>$("#inp_username").focus()</script>
</body>