        </section>
      </div>
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          Template powered by <a href="https://adminlte.io" target="_blank">AdminLTE</a>.
        </div>
        <strong>&copy; 2019 <?php if (date("Y") > 2019) echo "- " . date("Y") ?> <a href="#">RS Grha Permata Ibu</a>.</strong> All rights reserved.
      </footer>
    </div>
    <?php include_once '../about.php' ?>
    <!-- Bootstrap 3.3.7 -->
    <script src="<?php echo $base_url ?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <script src="<?php echo $base_url ?>/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo $base_url ?>/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <!-- bootstrap datepicker -->
    <script src="<?php echo $base_url ?>/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo $base_url ?>/bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.id.min.js"></script>
    <!-- PACE -->
    <script src="<?php echo $base_url ?>/bower_components/PACE/pace.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo $base_url ?>/dist/js/adminlte.min.js"></script>
    <script src="<?php echo $base_url ?>/scripts/script.js"></script>
    <script>
      <?php
        if (!$cnt_logmed) echo '$("#hdr_logmed").remove();';
        if (!$cnt_lognmed) echo '$("#hdr_lognmed").remove();';
        if (!showApproval() && !showPRMApproval() && !showPRNApproval()) echo '$("#hdr_approval").remove();';
      ?>
    </script>
  </body>
</html>