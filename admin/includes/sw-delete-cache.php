<?php
  $admin='../../';
  include ('../../engine/config.php');
  include ('../../engine/functions.php');
  include ('../config.php');
  include ('../functions.php');
  admin_session();
  $cache_files = glob('../../engine/cache/*');
  foreach ($cache_files as $key => $file) {
  	unlink($file);
  }
?>