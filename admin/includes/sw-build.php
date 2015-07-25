<?php
  $admin='../../';
  //Make this page not cachable
  header("Cache-Control: no-cache, must-revalidate");
  include ('../../engine/config.php');
  include ('../../engine/functions.php');
  include ('../config.php');
  include ('../functions.php');
  admin_session();
  include 'rebuilder.php';
?>

