<?php
    $URI = explode("/", $_SERVER["REQUEST_URI"]);
    $base_url = "http://" . $_SERVER["SERVER_NAME"] . "/" . $URI[1];
?>