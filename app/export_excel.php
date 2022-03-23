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
        require_once "../exports/" . $id . ".php";
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Ekspor ke Excel: <?php echo $title ?></title>
    </head>
    <script>
        setTimeout(function() { window.close() }, 100);
    </script>
    <body>
        <style type="text/css">
            body { font-family: sans-serif; }
            table { margin: 20px auto; border-collapse: collapse; }
            table th, table td { border: 1px solid black; padding: 3px 8px; }
            .str{ mso-number-format:\@; }
        </style>
        <?php
            header("Content-type: application/vnd-ms-excel");
            header("Content-disposition: attachment; filename=" . (strlen($title) ? str_replace(array(" ", ","), "", $title) : "output") . ".xls");
        ?>
        <h3><?php echo $title . (strlen($subtitle) ? "<br>" . $subtitle : "") ?></h3>
        <?php echo $table ?>
        <h6><?php echo "Tanggal Ekspor: " . date("Y-m-d H:i:s") ?></h6>
    </body>
</html>