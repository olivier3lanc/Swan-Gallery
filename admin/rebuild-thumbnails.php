<?php 
/**
* ADMIN REBUILD THUMBNAILS AND PREVIEWS
* Backend page to rebuild all thumbs and previews
* Swan Gallery
* @version 0.9
* @author Olivier Blanc http://www.egalise.com/swan-gallery
* @license MIT
*/

  $admin='../';
  //Make this page not cachable
  header("Cache-Control: no-cache, must-revalidate");
  include ('../engine/config.php');
  include ('../engine/functions.php');
  include ('config.php');
  include ('functions.php');
  admin_session();
  //Gallery Language
  if(file_exists('languages/'.$gallery_admin_language.'.php')){
    include 'languages/'.$gallery_admin_language.'.php';
  }else{
    //Default Language
    include 'languages/en_EN.php';
  }
	//Admin page 'active' class to highlight the current page in the admin menu
	$menu_active='rebuild-thumbnails';
  //Path to the current admin path
  $script_path=dirname(getcwd());
  //Complete array of photos in the gallery directory
  $all_files = scan($script_path.'/'.$gallery_folder, '', '', '', '');
  //Get tag list
  $tags=tag_list('../'.$gallery_folder, 1000, '');
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<!-- Force latest IE rendering engine or ChromeFrame if installed -->
<!--[if IE]>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<![endif]-->
<meta charset="utf-8">
<title><?php echo $lang['admin.rebuild.title'] ?></title>
<meta name="description" content="<?php echo $lang['admin.rebuild.description'] ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php include 'includes/sw-css.php' ?>
</head>
<body>

<?php include 'includes/sw-navbar.php' ?>

<div class="container">
  <h1 class="page-header"><?php echo $lang['admin.rebuild.title'] ?></h1>
  <p><?php echo $lang['admin.rebuild.message'] ?></p>
  <hr class="invisible">
  <div class="row">
    <div class="col-md-12 text-right">
      <a href="#" class="btn btn-primary" id="rebuild"><span class="glyphicon glyphicon-cog"></span> <?php echo $lang['admin.rebuild.button'] ?></a>
    </div>
  </div>
  <hr class="invisible">
  <div id="log"></div>
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


</div>
</body> 
</html>
