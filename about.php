<?php
    $parsedown_include_dirs = array(
        '../libraries/parsedown/Parsedown.php',
        'libraries/parsedown/Parsedown.php'
    );
    foreach ($parsedown_include_dirs as $parsedown_include_path) {
        if (@file_exists($parsedown_include_path)) {
            require_once($parsedown_include_path);
            break;
        }
    }
    $changelog = file_get_contents($base_url . "/changelog.md");
    $last_update_s = strpos($changelog, "**");
    $last_update_e = strpos($changelog, "**", $last_update_s + 2);
    $last_update = substr($changelog, $last_update_s + 2, $last_update_e - ($last_update_s + 3));
    $arr_last_update = explode(" ", $last_update);
    $version_no = $arr_last_update[0];
    $update_date_e = strpos($arr_last_update[1], ")");
    $update_date = substr($arr_last_update[1], 1, $update_date_e - 1);
    $Parsedown = new Parsedown();
?>

<style>
    #a-about { position: fixed; left: 0; bottom: 200px; padding: 10px; border-radius: 3px; background-color: rgba(1, 1, 1, 0.5); color: white; font-weight: bold; height: 40px; width: 40px; text-align: center; z-index: 900; }
    #modal-container-changelog { max-height: 500px; overflow-y: scroll; }
    #modal-container-changelog h3 { margin-top: 0px; }
</style>

<a href="#" title="Tentang Aplikasi Khanza GPI" data-toggle="modal" data-target="#modal-about" id="a-about">
    <i class="fa fa-info"></i>
</a>
<div class="modal fade" id="modal-about">
    <div class="modal-dialog" style="width: 300px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" title="Tutup" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="modal-container" name="modal-container" style="text-align: center">
                <img src="<?php echo $base_url ?>/images/LogoGPI.png"><br>
                <span style="font-size: 18pt">Aplikasi Penunjang Khanza</span><br>
                <span style="font-size: 14pt">RS Grha Permata Ibu</span><br>
                <strong><?php echo $version_no ?></strong><br>
                Pembaruan terakhir: <?php echo $update_date ?><br><br>
                <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" href="#modal-changelog">Log Perubahan</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-changelog">
    <div class="modal-dialog" style="width: 800px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" title="Tutup" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="modal-container-changelog" name="modal-container-changelog">
                <?php echo $Parsedown->text($changelog) ?>
            </div>
        </div>
    </div>
</div>
