<?php
    $id = $_GET["id"];
    $param = $_GET["param"];
    if ($id) {
        require_once "../config/base_url.php";
        require_once "../config/connect_app.php";
        $param_dec = explode("&", base64_decode($param));
        $params = [];
        foreach ($param_dec as $p) {
            $p2 = explode("=", $p);
            $params[$p2[0]] = $p2[1];
        }
        require_once "../reports/" . $id . ".php";
        $kop = "<div id='kop-report'><span id='nama-rs'>RS Grha Permata Ibu</span><br>
        Jl. KH. M. Usman No. 168<br>
        Kel. Kukusan, Kec. Beji, Kota Depok<br>
        Telp. (021) 777-8899, Fax. (021) 777-8998
        </div><hr id='hr-kop'>";
    ?>
        <head>
            <link rel="stylesheet" href="<?php echo $base_url ?>/styles/report.css">
            <title><?php echo $title ?></title>
        </head>
        <body>
            <img src="<?php echo $base_url ?>/images/LogoGPI_128.png" id="logo-rs">
            <?php echo $kop ?>
            <?php if (strlen($title)) echo '<div id="report-title">' . strtoupper($title) . '</div>' ?>
            <?php echo $rpt_content ?>
            <span id="print-time">Dicetak: <?php echo date("Y-m-d H:i:s") ?></span>
        </body>

<?php } ?>