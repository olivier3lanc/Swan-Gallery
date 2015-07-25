<?php 
/**
* ADMIN PASSWORD CHANGE
* Change the backend password
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
	//check_install();
	admin_session();
	//Gallery Language
	if(file_exists('languages/'.$gallery_admin_language.'.php')){
		include 'languages/'.$gallery_admin_language.'.php';
	}else{
		//Default Language
		include 'languages/en_EN.php';
	}
	//Path to the current admin path
	$script_path=dirname(getcwd());
	//Complete array of photos in the gallery directory
  	$all_files = scan($script_path.'/'.$gallery_folder, '', '', '', '');
  	//Get tag list
	$tags=tag_list('../'.$gallery_folder, 1000, '');
	//Admin page 'active' class to highlight the current page in the admin menu
	$menu_active='password';
	//Page title
	$page_title = $lang['admin.password.title'];
	if(isset($_SESSION['install'])) {
		$page_title = $lang['admin.install.title'];
	}
	//Initializing the verifying password process
	$verified_password = '';
	//Retype password process
	if(isset($_POST['verify_password'])) {
		$entered_password = $_POST['verify_password'];
		$entered_password_encoded = encode($entered_password);
		getimagesize('data/'.$hash_file,$info);
		$iptc = iptcparse($info["APP13"]);
		$password = $iptc["2#040"][0];
		if($entered_password_encoded == $password) {
			$verified_password = true;
			$page_title = $lang['admin.password.enter.new'];
		}else{
			$verified_password = false;
			return header('Location: ./password.php?mismatch');
			$page_title = $lang['admin.password.mismatch'];
		}
	}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<!-- Force latest IE rendering engine or ChromeFrame if installed -->
<!--[if IE]>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<![endif]-->
<meta charset="utf-8">
<title><?php echo $page_title ?></title>
<meta name="description" content="<?php echo $lang['admin.password.description'] ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php include 'includes/sw-css.php' ?>
</head>
<body>

<?php if(!isset($_SESSION['install'])) : include 'includes/sw-navbar.php'; endif ?>

<div class="container">
<?php if($verified_password == true || isset($_SESSION['install'])) : ?>    
	<form class="form-signin"  role="form" action="write.php" method="post" style="max-width:500px; margin:auto">
	<?php if(isset($_SESSION['install'])) : ?>
	<h2 class="form-signin-heading"><?php echo $lang['admin.install.installation'] ?></h2>
		<?php if(isset($_GET['mismatch'])) : ?>
	    <div class="alert alert-danger">
	    	<strong><?php echo $lang['admin.password.mismatch'] ?>!</strong> <?php echo $lang['admin.password.mismatch.description'] ?>	
    	</div>
	    <?php else : ?>
	    <div class="alert alert-info">
	    	<strong><?php echo $lang['admin.install.installation.mode'] ?>:</strong> <?php echo $lang['admin.install.installation.mode.description'] ?>
	    </div>	
	    <?php endif ?>
	<?php elseif(isset($_SESSION['is_admin'])) : ?>
	<h2 class="form-signin-heading"><?php echo $lang['admin.password.change.the.admin.password'] ?></h2>
		<?php if(isset($_GET['mismatch'])) : ?>
	    <div class="alert alert-danger">
	    	<strong><?php echo $lang['admin.password.mismatch'] ?>!</strong> <?php echo $lang['admin.password.mismatch.description'] ?>
	    </div>
	    <?php endif ?>
	<?php endif ?>
	<p><?php echo $lang['admin.password.change.the.admin.password.description'] ?></p>
	<div class="form-group">
	    <label><?php echo $lang['admin.password.enter.a.password'] ?></label>
	    <div class="input-group">
		    <div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
		    <input type="password" name="gallery_admin_pwd" class="form-control input-lg" placeholder="" required autofocus>
	    </div>
	</div>
	<div class="form-group">
	    <label><?php echo $lang['admin.password.repeat.the.password'] ?></label>
	    <div class="input-group">
	    	<div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
	    	<input type="password" name="gallery_admin_pwd_check" class="form-control input-lg" placeholder="" required>
	    </div>
	</div>
	<button class="btn btn-primary pull-right btn-lg" type="submit"><span class="glyphicon glyphicon-floppy-disk"></span> <?php echo ucfirst($lang['general.submit']) ?></button>
	</form>

<?php else : ?>
	<form class="form-signin"  role="form" action="./password.php" method="post" style="max-width:500px; margin:auto">
		<h2 class="form-signin-heading"><?php echo $lang['admin.password.enter.current.password'] ?></h2>
		<?php if(isset($_GET['mismatch'])) : ?>
        <div class="alert alert-danger"><strong><?php echo $lang['admin.password.mismatch'] ?>!</strong> <?php echo $lang['admin.password.re.enter.password'] ?></div>
        <?php endif ?>
        <div class="form-group">
	        <label><?php echo $lang['admin.password.enter.current.password.description'] ?></label>
	        <div class="input-group">
	        	<div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
	        	<input type="password" name="verify_password" class="form-control input-lg" placeholder="" required autofocus>
	        </div>
        </div>
        <button class="btn btn-primary pull-right btn-lg" type="submit"><?php echo ucfirst($lang['general.submit']) ?></button>
	</form>
<?php endif ?>

</div>

<footer>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <p class="text-muted text-center mgtop50 mgbot50"><?php echo $lang['general.powered.by'] ?> <a href="<?php echo $lang['general.swan.gallery.url'] ?>" title="<?php echo $lang['general.swan.gallery.description'] ?>" class="text-muted"><?php echo $lang['general.swan.gallery.title'] ?></a></p>
      </div>
    </div>
  </div>
</footer>

<?php include 'includes/sw-js.php' ?>
</body> 
</html>
