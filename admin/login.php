<?php 
/**
* ADMIN LOG IN PAGE
* Log in page of the backend
* Swan Gallery
* @version 0.9
* @author Olivier Blanc http://www.egalise.com/swan-gallery
* @license MIT
*/


	$admin = '../';
	//Make this page not cachable
	//header("Cache-Control: no-cache, must-revalidate");
	include ('../engine/config.php');
	include ('../engine/functions.php');
	include ('config.php');
	include ('functions.php');
  //Gallery Language
  if(file_exists('languages/'.$gallery_admin_language.'.php')){
    include 'languages/'.$gallery_admin_language.'.php';
  }else{
    //Default Language
    include 'languages/en_EN.php';
  }
	$script_path=dirname(getcwd());
	admin_session();
	if(isset($_POST['password'])) {
		$entered_password = $_POST['password'];
		$entered_password_encoded = encode($entered_password);
		getimagesize('data/'.$hash_file,$info);
		$iptc = iptcparse($info["APP13"]);
		$password = $iptc["2#040"][0];
		if($entered_password_encoded == $password) {
			$_SESSION['is_admin'] = true;
			return header('Location: ./');
		}else{
			$wrong_password = true;
		}
	}
	if(isset($_GET['logout'])) {
		unset($_SESSION['is_admin'],$_SESSION['order'],$_SESSION['sortby']);
	}
	if(isset($_GET['installed'])) {
		unset($_SESSION['install']);
	}
	$menu_active='login';
?><!DOCTYPE HTML>
<html lang="en">
<head>
<!-- Force latest IE rendering engine or ChromeFrame if installed -->
<!--[if IE]>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<![endif]-->
<meta charset="utf-8">
<title><?php echo $lang['admin.log.in.title'] ?></title>
<meta name="description" content="<?php echo $lang['admin.log.in.description'] ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php include 'includes/sw-css.php' ?>
<noscript>
<style type="text/css">
.panel { display: block}
</style>
</noscript>
</head>
<body style="background:#EEEEEE">

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">

  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only"><?php echo $lang['general.toggle.navigation'] ?></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="../" target="_blank"><span class="glyphicon glyphicon-share-alt"></span> <?php echo $lang['admin.log.in.back.to.the.gallery'] ?></a></li>
      </ul>

      <ul class="nav navbar-nav navbar-right">
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <p class="text-center mgbot30">
        <img src="img/logo-swan-login.png" alt="Swan Gallery Logo">
      </p>
    </div>
    <div class="col-md-12">
      <div class="panel panel-default none" style="display:none; max-width:500px; margin:auto; padding:0px 30px 20px 30px">
        <div class="panel-body">
          <form class="form-signin"  role="form" action="login.php" method="post">
            
            <h1 class="form-signin-heading"><?php echo $lang['admin.log.in.title'] ?></h1>
            <?php if(isset($_GET['installed'])) : ?>
            <div class="alert alert-success"><strong><?php echo $lang['admin.log.in.install.successful.title'] ?>:</strong> <?php echo $lang['admin.log.in.install.successful.description'] ?></div>
            <?php endif ?>
            <p><?php echo $lang['admin.log.in.description'] ?></p>
            <?php if(isset($wrong_password)) : ?>
            <div class="alert alert-danger"><strong><?php echo $lang['admin.log.in.wrong.password.title'] ?></strong> <?php echo $lang['admin.log.in.wrong.password.description'] ?></div>
            <?php endif ?>
            <div class="mgbot15">
              <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
                <input type="password" name="password" class="form-control input-lg" placeholder="<?php echo $lang['admin.log.in.password.placeholder'] ?>" required autofocus>
              </div>
            </div><!-- /input-group -->
            <div class="text-right">
              <button class="btn btn-primary btn-lg" type="submit"><span class="glyphicon glyphicon-log-in"></span> <?php echo $lang['admin.log.in.button'] ?></button>
            </div>
          </form>
    </div>
  </div>
  <footer>
    <p class="text-muted text-center mgtop30"><?php echo $lang['general.powered.by'] ?> <a href="<?php echo $lang['general.swan.gallery.url'] ?>" title="<?php echo $lang['general.swan.gallery.description'] ?>" class="text-muted"><?php echo $lang['general.swan.gallery.title'] ?></a></p>
  </footer>
</div>




<?php include 'includes/sw-js.php' ?>
<script>
jQuery(window).load(function(){
  jQuery('.panel').fadeIn()
})
</script>
</body> 
</html>
