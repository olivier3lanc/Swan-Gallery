<?php 
/**
* ADMIN EDITOR
* Gallery editor
* Swan Gallery
* @version 0.9
* @author Olivier Blanc http://www.egalise.com/swan-gallery/
* @license MIT
*/


	$admin='../';

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

  //Path to the current admin path
  $script_path = dirname(getcwd());
  

	//Management of SESSION variables
	if(isset($_SESSION['sortby'])) {
		$gallery_admin_sorting = $_SESSION['sortby'];
	}

	if(isset($_SESSION['order'])) {
		$gallery_admin_ordering = $_SESSION['order'];
	}

	//Management of URL variables
	//If tag in URL
	if(isset($_GET['tag'])){ 
		$tag = $_GET['tag'];
		$no_table_filters = '';
	}else{
		//Default tag value
		$tag = '';
	}

	//If search in POST
	if(isset($_POST['search'])){ 
		$search = $_POST['search'];
	}

	//If sortby in URL
	if(isset($_GET['sortby'])){ 
		$sortby = $_GET['sortby'];
		unset($_SESSION['sortby']);
		$gallery_admin_sorting = $sortby;
		$_SESSION['sortby'] = $sortby;
	}

	//If order in URL
	if(isset($_GET['order'])){ 
		$order = $_GET['order'];
		unset($_SESSION['order']);
		$gallery_admin_ordering = $order;
		$_SESSION['order'] = $order;
	}

	//If page in URL
	if(isset($_GET['page'])){ 
		$page = $_GET['page'];
	} else { 
		//Default order value
		$page = 1;
	}

  //Complete array of photos in the gallery directory
  $all_files = scan($script_path.'/'.$gallery_folder, '', '', '', '');

	//Filter to remove current admin folder and ?tag variable from the query
	$current_dir = str_replace($script_path,'',getcwd());
	$path_filter = array($current_dir, $tag, '?tag=', '&tag=', '?sortby=', '&sortby=', '&order=', '?order=', '?page='.$page,'&page='.$page, $order, $sortby, '?featured', '?favorites_to_tags');

	//Get the array with all data ordered
	if(isset($_GET['sortby'])) {
		$files = scan($script_path.'/'.$gallery_folder, $path_filter, $tag, '', '');
		$files = sort_multi($files, $sortby, $order);

	//If search form / HTTP "search" _POST 
	}elseif(isset($_POST['search'])){ 
    if($search == 'sw-parameters') {
      header('Location: parameters.php');
    }elseif($search == 'sw-editor') {
      header('Location: ./');
    }elseif($search == 'sw-featured') {
      header('Location: ./?featured');
    }elseif($search == 'sw-favorites-to-tags') {
      header('Location: ./?favorites_to_tags');
    }elseif($search == 'sw-upload') {
      header('Location: upload.php');
    }elseif($search == 'sw-rebuild') {
      header('Location: rebuild-thumbnails.php');
    }elseif($search == 'sw-password') {
      header('Location: password.php');
    }elseif(strpos($search, 'swantag-') !== false) {
      $truncate = str_replace('swantag-', '', $search);
      header('Location: ./?tag='.$truncate);
    }elseif(strpos($search, '"') == 0) {
      $search = str_replace('"', '', $search);
    }
    $files = scan($script_path.'/'.$gallery_folder, $path_filter, '', '', $search);
		$files = sort_multi($files, $gallery_admin_sorting, $gallery_admin_ordering);
		$no_table_filters = '';

	//Else if ?favorites_to_tags in the URL to list only photos set as favorites for one or more tags
	}elseif(isset($_GET['favorites_to_tags'])){ 
		$files = scan($script_path.'/'.$gallery_folder, $path_filter, '', '', 'favorites_to_tags');
		$files = sort_multi($files, $gallery_admin_sorting, $gallery_admin_ordering, '', '');
		$no_table_filters = '';

	//Else if ?featured in the URL to list only featured photos
	}elseif(isset($_GET['featured'])){ 
		$files = scan($script_path.'/'.$gallery_folder, $path_filter, $tag, '', 'featured');
		$files = sort_multi($files, $gallery_admin_sorting, $gallery_admin_ordering);
		$no_table_filters = '';

	//Else Get the array with all data or with tag
	}else{
		$files = scan('../'.$gallery_folder, $path_filter, $tag, '', '');
		$files = sort_multi($files, $gallery_admin_sorting, $gallery_admin_ordering);
	}

	//If admin tag list ordering is set to alphabetical order
  if($gallery_admin_tag_list_ordering == 'a-z'){
    $tags = tag_list('../'.$gallery_folder, 1000, '', 'a-z');
  //If admin tag list ordering is set to reverse alphabetical order
  }elseif($gallery_admin_tag_list_ordering == 'z-a'){
    $tags = tag_list('../'.$gallery_folder, 1000, '', 'z-a');
  //If admin tag list ordering is set to popularity
  }elseif($gallery_admin_tag_list_ordering == 'popularity'){
    $tags = tag_list('../'.$gallery_folder, 1000, '');
  }


	//Number of photo in the array before paginate
	$files_count = count($files);

	//Paginate
	$files = paginate($files,$gallery_admin_photos_per_page);

	//Pagination
	$the_url = explode('?',$_SERVER["REQUEST_URI"]);
	if(isset($_GET['tag']) || isset($_GET['sortby']) || isset($_GET['featured']) || isset($_GET['favorites_to_tags'])) {
		$gets = str_replace('&page='.$page,'',$the_url[1]);
		$first_page = '?'.$gets;
		$target_page = $first_page.'&page=';
	}else{
		$target_page = '?page=';
		$first_page = './';
	}
	
	//Array with not tagged files
	$not_tagged=scan('../'.$gallery_folder, $path_filter, 'no_tag', '', '', '');
	
	//Array with featured files
	$featured=scan('../'.$gallery_folder, $path_filter, '', '', 'featured', '');
	
	//Array with files with at least one favorite to tags set
	$favorites_to_tags=scan('../'.$gallery_folder, $path_filter, '', '', 'favorites_to_tags', '');
	
	//Pagination
	$pages = pagination($files_count,$gallery_admin_photos_per_page);

  //Total number of pages
  $total_pages = count($pages)+1;

  //Current page
  if(isset($_GET['page'])) {
    $current_page = $_GET['page'];
  }else{
    $current_page = 1;
  }
	
	//Menu class
	$menu_active='editor';
?><!DOCTYPE HTML>
<html lang="en">
<head>
<!-- Force latest IE rendering engine or ChromeFrame if installed -->
<!--[if IE]>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<![endif]-->
<meta charset="utf-8">
<title><?php echo $lang['admin.editor.title'] ?></title>
<meta name="description" content="<?php echo $lang['admin.editor.description'] ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php include 'includes/sw-css.php' ?>
</head>

<body>

<?php include 'includes/sw-navbar.php' ?>

<div class="container-fluid">

  <!-- Global row -->
  <div class="row">

    <!-- Sidebar -->
    <div class="col-md-3 col-sm-3 col-xs-0 sidebar">
      <ul class="nav nav-sidebar">

        <li class="<?php if(count($_GET) == 0 && !isset($_POST['search'])) : echo 'active'; endif ?>">
          <a  href="./" 
              title="<?php echo $lang['admin.editor.sidebar.list.all.photos.tooltip'] ?>">
              <span class="glyphicon glyphicon-picture"></span>
              <?php echo ucfirst($lang['general.all.female']) ?> 
              <span class="badge badge-primary"><?php echo count_total('') ?></span>
          </a>
        </li>

<?php if(!empty($featured)) : ?>

        <li class="<?php if(isset($_GET['featured'])) : echo 'active'; endif ?>">
          <a  href="?featured" 
              title="<?php echo $lang['admin.editor.sidebar.list.featured.tooltip'] ?>">
              <span class="glyphicon glyphicon-star"></span> 
              <?php echo ucfirst($lang['general.featured.plural']) ?> 
              <span class="badge badge-primary"><?php echo count($featured) ?></span>
          </a>
        </li>

<?php else : ?>

        <li>
          <a  href="#" 
              title="<?php echo $lang['admin.editor.sidebar.list.no.featured.tooltip'] ?>">
              <span class="glyphicon glyphicon-star"></span> 
              <?php echo ucfirst($lang['general.featured']) ?>  
              <span class="badge"><?php echo count($featured) ?></span>
          </a>
        </li>

<?php endif //Featured ?>

<?php if(!empty($favorites_to_tags)) : ?>

        <li class="<?php if(isset($_GET['favorites_to_tags'])) : echo 'active'; endif ?>">
          <a  href="?favorites_to_tags" 
              title="<?php echo $lang['admin.editor.sidebar.list.favorites.to.tags.tooltip'] ?>">
              <span class="glyphicon glyphicon-tags"></span> 
              <?php echo ucfirst($lang['general.favorites.to.tags']) ?>  
              <span class="badge badge-primary"><?php echo count($favorites_to_tags) ?></span>
          </a>
        </li>

<?php else : ?>

        <li>
          <a  href="#" 
              title="<?php echo $lang['admin.editor.sidebar.list.no.favorites.to.tags.tooltip'] ?>">
              <span class="glyphicon glyphicon-tags"></span> 
              <?php echo ucfirst($lang['general.favorites.to.tags']) ?>
              <span class="badge"><?php echo count($favorites_to_tags) ?></span>
          </a>
        </li>

<?php endif //Favorites to tags ?>

<?php if(count($not_tagged) != NULL) : ?>

        <li class="<?php if(isset($_GET['tag']) && $_GET['tag']=='no_tag') : echo 'active'; endif ?>">
          <a  href="?tag=no_tag" 
              title="<?php echo $lang['admin.editor.sidebar.list.not.tagged.tooltip'] ?>">
              <span class="glyphicon glyphicon-thumbs-down"></span> 
              <?php echo ucfirst($lang['general.not.tagged']) ?> 
              <span class="badge badge-primary"><?php echo count($not_tagged) ?></span>
          </a>
        </li>

<?php else : ?>
        
        <li>
          <a  href="#">
              <span class="glyphicon glyphicon-thumbs-up"></span> 
              <?php echo ucfirst($lang['general.not.tagged']) ?> 
              <span class="badge"><?php echo count($not_tagged) ?></span>
          </a>
        </li>

<?php endif // Not tagged ?>

      </ul>

      <ul class="nav nav-sidebar">
<?php if(is_array($tags)) : ?>
  <?php foreach($tags as $tag => $value) : ?>
        <li class="<?php if(flatten($tag) == $_GET['tag']) : echo 'active'; endif ?>">
          <a  href="?tag=<?php echo flatten($tag) ?>" 
              title="<?php echo $lang['admin.editor.sidebar.list.only.tooltip'].' '.$tag.' '.$lang['general.tag'] ?>">
              <?php echo $tag ?> <span class="badge badge-primary"><?php echo $value ?></span>
          </a>
        </li>
  <?php endforeach ?>
<?php endif // Tags list ?>
      </ul>

    </div><!-- ./sidebar -->

    <div class="col-md-9 col-md-offset-3 col-sm-9 col-sm-offset-3 col-xs-12" id="admin-main-container">
      
      <div class="mgbot30"><!-- Header and analytics -->

<?php if(isset($_GET['tag'])) : ?>
  
  <?php if($_GET['tag']=='no_tag') : ?>

        <h1><?php echo ucfirst($lang['general.not.tagged']) ?>
          <small><span class="label label-primary"><?php echo $files_count ?></span></small>
          <small class="pull-right"><?php echo $lang['general.page'].' '.$current_page.' '.$lang['general.to'].' '.$total_pages ?></small>
        </h1>
        <p class="mgbot30 mgtop30 pdleft30"><?php echo $lang['admin.editor.not.tagged.description'] ?></p>

  <?php else : ?>

        <h1><?php echo $lang['general.tag'] ?> "<?php echo $_GET['tag'] ?>" 
          <small><span class="label label-primary"><?php echo $files_count ?></span></small>
          <small class="pull-right"><?php echo $lang['general.page'].' '.$current_page.' '.$lang['general.to'].' '.$total_pages ?></small>
        </h1>

  <?php endif ?>

  <?php if(isset($tpl_bypass['photo_keywords'])) : ?>
        <div class="pdleft30 mgtop30">
          <p class="alert alert-warning"><span class="glyphicon glyphicon-info-sign"></span> <?php echo $lang['admin.parameters.parameter.bypasss.text'] ?></p>
        </div>
  <?php endif ?>

<?php elseif(isset($search)) : ?>

        <h1>
          <?php echo $lang['admin.editor.search.results.for'] ?> "<?php echo $search ?>" 
          <small><span class="label label-primary"><?php echo $files_count ?></span></small>
        </h1>

<?php elseif(isset($_GET['featured'])) : ?>

        <h1><?php echo ucfirst($lang['general.featured.plural']) ?> 
          <small><span class="label label-primary"><?php echo $files_count ?></span></small>
          <small class="pull-right"><?php echo $lang['general.page'].' '.$current_page.' '.$lang['general.to'].' '.$total_pages ?></small>
        </h1>
        <p class="mgbot30 mgtop30 pdleft30"><?php echo $lang['admin.editor.featured.description'] ?></p>
  <?php if(isset($tpl_bypass['photo_featured'])) : ?>
        <div class="pdleft30 mgtop30">
          <p class="alert alert-warning"><span class="glyphicon glyphicon-info-sign"></span> <?php echo $lang['admin.parameters.parameter.bypasss.text'] ?></p>
        </div>
  <?php endif ?>

<?php elseif(isset($_GET['favorites_to_tags'])) : ?>

        <h1><?php echo ucfirst($lang['general.favorites.to.tags']) ?>  
          <small><span class="label label-primary"><?php echo $files_count ?></span></small>
          <small class="pull-right"><?php echo $lang['general.page'].' '.$current_page.' '.$lang['general.to'].' '.$total_pages ?></small>
        </h1>
        <p class="mgbot30 mgtop30 pdleft30"><?php echo $lang['admin.editor.favorites.to.tags.description'] ?></p>
  <?php if(isset($tpl_bypass['photo_favorites'])) : ?>
        <div class="pdleft30 mgtop30">
          <p class="alert alert-warning"><span class="glyphicon glyphicon-info-sign"></span> <?php echo $lang['admin.parameters.parameter.bypasss.text'] ?></p>
        </div>
  <?php endif ?>

<?php else : ?>

        <h1><?php echo $lang['general.swan.gallery.editor'] ?> 
          <small class="pull-right"><?php echo $lang['general.page'].' '.$current_page.' '.$lang['general.to'].' '.$total_pages ?></small>
        </h1>

  <?php if(count_total('') !== 0) : //If no file, no analytics ?>

        <div class="row mgtop30 mgbot30"><!-- Analytics -->

          <div class="col-md-3 col-sm-3 col-xs-12 text-center">
            <h2><?php echo $files_count ?>
              <br>
              <small>
                <span class="glyphicon glyphicon-picture"></span> 
                  <?php if($files_count > 1) {
                          echo ucfirst($lang['general.photos']);
                        }else{
                          echo ucfirst($lang['general.photo']);
                        }
                  ?>
                </small>
            </h2>
          </div>

          <div class="col-md-3 col-sm-3 col-xs-12 text-center">
            <h2><?php echo count_tag_list($tags) ?><br>
              <small>
                <span class="glyphicon glyphicon-tags"></span> 
                <?php 
                      if(empty($tags)) {
                        echo ucfirst($lang['general.tag']);
                      }else{
                        if(count($tags) == 1){
                          echo ucfirst($lang['general.tag']);
                        }else{
                          echo ucfirst($lang['general.tags']);
                        }
                      }
                ?>
              </small>
            </h2>
          </div>

          <div class="col-md-3 col-sm-3 col-xs-12 text-center">

    <?php if(count($not_tagged) != NULL) : ?>

            <h2>
              <?php echo count($not_tagged) ?> 
              <br>
              <span class="glyphicon glyphicon-thumbs-down"></span>
              <small>
                <a href="?tag=no_tag" title="<?php echo $lang['admin.editor.sidebar.list.not.tagged.tooltip'] ?>">
                  <?php if(count($not_tagged) > 1) : echo $lang['admin.editor.photos.not.tagged']; else : echo $lang['admin.editor.photo.not.tagged']; endif ?>
                </a>
              </small>
            </h2>

    <?php else : ?>

            <h2><span class="glyphicon glyphicon-thumbs-up"></span><br>
              <small><?php echo $lang['admin.editor.sidebar.list.all.tagged.tooltip'] ?></small>
            </h2>

    <?php endif ?>

          </div><!-- ./ Tagged or not -->

          <div class="col-md-3 col-sm-3 col-xs-12 text-center">
            <h2><?php echo count($featured) ?>
              <br>
              <small>
                <span class="glyphicon glyphicon-star"></span>
      <?php if(!empty($featured)) : ?>
                <a href="?featured" title="<?php echo $lang['admin.editor.sidebar.list.featured.tooltip'] ?>">
                  <?php echo ucfirst($lang['general.featured']) ?>
                </a>
      <?php else : ?>
                <?php echo ucfirst($lang['general.featured']) ?>
      <?php endif ?>
              </small>
            </h2>
          </div>

        </div><!-- ./ Analytics -->

  <?php endif //If no file, no analytics ?>

<?php endif //If no get/post parameters ?>

      </div><!-- ./ Header and analytics -->


<?php if(count_total('') == 0) : //No jpg files detected in the gallery folder ?>

      <div class="pdleft30">
        <p><?php echo $lang['admin.editor.no.jpg.in.directory'] ?> <strong>"<?php echo $gallery_folder ?>"</strong> </p>
        <hr class="invisible">
        <a href="upload.php" class="btn btn-primary btn-lg" role="button"><?php echo ucfirst($lang['general.upload']) ?></a>
      </div>

<?php else : //jpg files detected in the gallery folder ?>

  <?php if(empty($files)) : //jpg files detected but empty array ?>

      <div class="pdleft30">
        <p><?php echo $lang['admin.editor.no.search.results'] ?></p>
        <hr class="invisible">
        <form class="" action="./" method="post" role="search">
          <div class="form-group">
            <input type="text" class="form-control" name="search" <?php if(isset($search)) : echo 'value="'.$search.'"'; endif ?>>
          </div>
          <button type="submit" class="btn btn-primary btn-lg pull-right"><?php echo ucfirst($lang['general.search']) ?></button>
        </form>
      </div>

  <?php endif //empty $files ?>

<?php endif //count_total() == 0 ?>

<?php if(!empty($files)) : //jpg files array  ?>
      
  <?php if(!isset($no_table_filters)) : //No filter button for favorites to tag page, featured page and search results page ?>

      <div class="row mgbot20">
    		<div class="col-md-12">
          <div class="btn-group pull-right">
            <button   type="button" 
                      class="btn btn-default dropdown-toggle" 
                      data-toggle="dropdown">
                      <?php echo $lang['admin.editor.sort.and.order.by'] ?> 
                      <span class="caret"></span>
            </button>

            <ul class="dropdown-menu" role="menu">
                <li class="<?php if($gallery_admin_sorting=='ph_date_created' && $gallery_admin_ordering=='ASC') : echo 'active'; endif ?>">
                  <a href="?sortby=ph_date_created&order=ASC">
                    <?php echo $lang['admin.editor.date.modified.ascending'] ?> 
                  </a>
                </li>
                <li class="<?php if($gallery_admin_sorting=='ph_date_created' && $gallery_admin_ordering=='DESC') : echo 'active'; endif ?>">
                  <a href="?sortby=ph_date_created&order=DESC">
                    <?php echo $lang['admin.editor.date.modified.descending'] ?> 
                  </a> 
                </li>
                <li class="divider"></li>
                <li class="<?php if($gallery_admin_sorting=='ph_date' && $gallery_admin_ordering=='ASC') : echo 'active'; endif ?>">
                  <a href="?sortby=ph_date&order=ASC">
                    <?php echo $lang['admin.editor.date.ascending'] ?> 
                  </a>
                </li>
                <li class="<?php if($gallery_admin_sorting=='ph_date' && $gallery_admin_ordering=='DESC') : echo 'active'; endif ?>">
                  <a href="?sortby=ph_date&order=DESC">
                    <?php echo $lang['admin.editor.date.descending'] ?> 
                  </a>
                </li>
                <li class="divider"></li>
                <li class="<?php if($gallery_admin_sorting=='ph_file_lowercase' && $gallery_admin_ordering=='ASC') : echo 'active'; endif ?>">
                  <a href="?sortby=ph_file_lowercase&order=ASC">
                    <?php echo $lang['admin.editor.file.name.ascending'] ?> 
                  </a>
                </li>
                <li class="<?php if($gallery_admin_sorting=='ph_file_lowercase' && $gallery_admin_ordering=='DESC') : echo 'active'; endif ?>"> 
                  <a href="?sortby=ph_file_lowercase&order=DESC">
                    <?php echo $lang['admin.editor.file.name.descending'] ?>
                  </a>
                </li>
                <li class="divider"></li>
                <li class="<?php if($gallery_admin_sorting=='ph_title' && $gallery_admin_ordering=='ASC') : echo 'active'; endif ?>">
                  <a href="?sortby=ph_title&order=ASC">
                    <?php echo $lang['admin.editor.title.ascending'] ?>
                  </a>
                </li>
                <li class="<?php if($gallery_admin_sorting=='ph_title' && $gallery_admin_ordering=='DESC') : echo 'active'; endif ?>">
                  <a href="?sortby=ph_title&order=DESC">
                    <?php echo $lang['admin.editor.title.descending'] ?>
                  </a>
                </li>
            </ul>
          </div>
        </div>
      </div><!-- ./Filter button -->

  <?php endif //./No filter button for favorites to tag page, featured page and search results page ?>

      

	<?php foreach($files as $file) : //Start loop ?>
      <!-- Photo div -->
      <div id="<?php echo $file['ph_id'] ?>">
        <!-- Photo tabs -->
        <ul class="nav nav-tabs pdleft30" role="tablist">
          <li class="<?php if(!isset($_GET['favorites_to_tags'])) : echo 'active'; endif ?>">
            <a  href="#tab-main-<?php echo $file['ph_id'] ?>"
                title="<?php echo $lang['admin.editor.photo.tabs.main.data.tooltip'] ?>"
                role="tab"
                data-toggle="tab">
                <span class="glyphicon glyphicon-picture"></span> 
                <span class="hidden-xs hidden-sm hidden-md"><?php echo $lang['admin.editor.photo.tabs.main.data'] ?></span>
            </a>
          </li>
          <li class="<?php if(isset($_GET['favorites_to_tags'])) : echo 'active'; endif ?>">
            <a  href="#tab-favorite-to-tags-<?php echo $file['ph_id'] ?>" 
                title="<?php echo $lang['admin.editor.photo.tabs.favorite.to.tags.tooltip'] ?>"
                role="tab"
                data-toggle="tab">
                <span class="glyphicon glyphicon-tags"></span> 
                <span class="hidden-xs hidden-sm hidden-md"><?php echo $lang['admin.editor.photo.tabs.favorite.to.tags'] ?></span>
                <?php if(!empty($file['ph_favorite_to_tags'])) : echo '<sup>('.count($file['ph_favorite_to_tags']).')</sup>'; endif ?>
            </a>
          </li>
          <!-- <li>
            <a  href="#tab-map-<?php //echo $file['ph_id'] ?>"
                title="Map"
                role="tab"
                data-toggle="tab">
                <span class="glyphicon glyphicon-map"></span> 
                <span class="hidden-xs hidden-sm hidden-md">Map</span>
            </a>
          </li> -->
          <li>
            <a  href="#tab-informations-<?php echo $file['ph_id'] ?>"
                title="<?php echo $lang['admin.editor.photo.tabs.informations.tooltip'] ?>"
                role="tab"
                data-toggle="tab">
                <span class="glyphicon glyphicon-info-sign"></span> 
                <span class="hidden-xs hidden-sm hidden-md"><?php echo $lang['admin.editor.photo.tabs.informations'] ?></span>
            </a>
          </li>
          <li class="pull-right">
    <?php if(isset($tpl_bypass['photo_view'])) : ?>
            <span class="glyphicon glyphicon-share-alt text-muted"></span> 
            <span class="hidden-xs hidden-sm hidden-md text-muted"><?php echo $lang['admin.editor.photo.tabs.view.photo.page'] ?></span>
            <span class="glyphicon glyphicon-info-sign text-muted" title="<?php echo $lang['admin.parameters.parameter.bypasss.text'] ?>"></span>
    <?php else : ?>
            <a  href="../?photo=<?php echo $file['ph_file'] ?>"
                title="<?php echo $lang['admin.editor.photo.tabs.view.photo.page.tooltip'] ?>"
                target="_blank">
                <span class="glyphicon glyphicon-share-alt"></span> 
                <span class="hidden-xs hidden-sm hidden-md"><?php echo $lang['admin.editor.photo.tabs.view.photo.page'] ?></span>
            </a>
    <?php endif ?>
          </li>
        </ul>
        <div class="clearfix"></div>
        <div class="row photo-row pd30">
          <form role="form" class="sw-save-file" action="write.php" method="post">
              
              <div class="col-md-3 text-center mgbot15">
                <a  href="<?php echo $file['ph_file_url'] ?>"
                    title="<?php echo $file['ph_title'] ?>"
                    data-gallery>
                    <img src="<?php echo $file['ph_thumb_url'] ?>" alt="<?php echo $file['ph_title'] ?>" class="img-thumbnail">
                </a>
              </div>
              
              <div class="col-md-9">
                <div class="row">
                    <div class="col-md-12">
                      <!-- Photo panes -->
                      <div class="tab-content">
                        <!-- Main pane -->
                        <div class="tab-pane tab-main <?php if(!isset($_GET['favorites_to_tags'])) : echo 'active'; endif ?>" id="tab-main-<?php echo $file['ph_id'] ?>">
                          <div class="row">
                            
                            <!-- Title -->
                            <div class="col-md-12 mgbot15">
    
                              <div class="input-group <?php if(isset($tpl_bypass['photo_title'])) : echo 'has-warning has-feedback'; endif ?>">
                                <div class="input-group-addon">
                                  <?php echo ucfirst($lang['general.title']) ?>
    <?php if(isset($tpl_bypass['photo_title'])) : ?>
                                  <span class="glyphicon glyphicon-info-sign" title="<?php echo $lang['admin.parameters.parameter.bypasss.text'] ?>"></span>
    <?php endif ?>
                                </div>
                                <input  name="ph_title" 
                                        type="text" 
                                        value="<?php echo $file['ph_title'] ?>"
                                        class="form-control input-lg zindex0">
                              </div>
                              <input type="hidden" name="ph_file" value="<?php echo $file['ph_file'] ?>">
                              <input type="hidden" name="ph_id" value="<?php echo $file['ph_id'] ?>">
                            </div>

                            <!-- Description -->
                            <div class="col-md-12 mgbot15">
                              <div class="input-group <?php if(isset($tpl_bypass['photo_description'])) : echo 'has-warning has-feedback'; endif ?>">
                                <div class="input-group-addon">
                                  <?php echo ucfirst($lang['general.description']) ?>
    <?php if(isset($tpl_bypass['photo_description'])) : ?>
                                  <span class="glyphicon glyphicon-info-sign" title="<?php echo $lang['admin.parameters.parameter.bypasss.text'] ?>"></span>
    <?php endif ?>
                                </div>
                                <textarea   name="ph_description" 
                                            rows="3"
                                            class="form-control zindex0"><?php echo $file['ph_description'] ?></textarea>
                              </div>
                            </div>

                            <!-- Tags -->
                            <div class="col-md-12 mgbot15">
                              <div class="input-group <?php if(isset($tpl_bypass['photo_keywords'])) : echo 'has-warning has-feedback'; endif ?>">
                                <div class="input-group-addon">
                                  <?php echo ucfirst($lang['general.tags']) ?>
    <?php if(isset($tpl_bypass['photo_keywords'])) : ?>
                                  <span class="glyphicon glyphicon-info-sign" title="<?php echo $lang['admin.parameters.parameter.bypasss.text'] ?>"></span>
    <?php endif ?>
                                </div>
                                <input  name="ph_keywords" 
                                        value="<?php foreach(tag_popularity($file['ph_keywords'], $tags) as $keyword => $rank) { echo $keyword.","; } ?>"
                                        class="ph_keywords form-control zindex999">
                              </div>
                            </div>

                            <!-- Date -->
                            <div class="col-md-12">
                              <div class="form-group">                          
                                <div class="input-group <?php if(isset($tpl_bypass['photo_date'])) : echo 'has-warning has-feedback'; endif ?>">
                                  <div class="input-group-addon">
                                    <?php echo ucfirst($lang['general.date']) ?>
    <?php if(isset($tpl_bypass['photo_date'])) : ?>
                                    <span class="glyphicon glyphicon-info-sign" title="<?php echo $lang['admin.parameters.parameter.bypasss.text'] ?>"></span>
    <?php endif ?>
                                  </div>
                                  <input  name="ph_date"
                                          type="text" 
                                          value="<?php echo $file['ph_date'] ?>"
                                          class="form-control datepicker zindex0" 
                                          data-date-format="yyyy-mm-dd" 
                                          style="display:inline; text-align:center; width:130px">
                                  </div>
                                </div>
                            </div>

                          </div><!-- ./row -->
                        </div><!-- ./Main pane -->

                        <!-- Favorite to tags pane -->
                        <div class="tab-pane tab-favorite-to-tags <?php if(isset($_GET['favorites_to_tags'])) : echo 'active'; endif ?>" id="tab-favorite-to-tags-<?php echo $file['ph_id'] ?>">
                          <div class="row">
                            <div class="col-md-12 mgbot15 mgtop15">
      <?php if(isset($tpl_bypass['photo_favorites'])) : ?>
                              <p class="alert alert-warning mgbot20"><span class="glyphicon glyphicon-info-sign"></span> <?php echo $lang['admin.parameters.parameter.bypasss.text'] ?></p>
      <?php endif ?>
      <?php if(empty($file['ph_keywords'])) : ?>
                              <p><?php echo $lang['admin.editor.photo.panes.favorite.to.tags.no.tag'] ?></p>
      <?php else : ?>
      
        <?php if(empty($file['ph_favorite_to_tags'])) : ?>
                              <p><?php echo $lang['admin.editor.photo.panes.favorite.to.tags.no.fav'] ?></p>
        <?php else : ?>
                              <p><?php echo $lang['admin.editor.photo.panes.favorite.to.tags.is.fav'] ?></p>
        <?php endif ?>

      <?php endif ?>

      <?php foreach(tag_popularity($file['ph_keywords'], $tags) as $keyword => $rank) : ?>
                              <div class="btn-group" data-toggle="buttons">
                                <label class="btn mgbot5 <?php if(in_array($keyword, $file['ph_favorite_to_tags']) !== false) : echo 'active btn-success'; else : echo 'btn-default'; endif ?>">
                                  <input  name="ph_favorite_to_tags[]"
                                          type="checkbox" 
                                          value="<?php echo $keyword ?>" 
                                          <?php if(in_array($keyword, $file['ph_favorite_to_tags']) !== false) : echo 'checked'; endif ?>>
                                          <?php echo $keyword ?>
                                </label>
                              </div> 
      <?php endforeach ?> 
                            </div>
                          </div><!-- ./row -->
                        </div><!-- ./Favorite to tags pane -->

                        <!-- Map pane 
                        <div class="tab-pane tab-map" id="tab-map-<?php //echo $file['ph_id'] ?>">
                          <div class="row">
                            <div class="col-md-12 mgbot15 mgtop15">
                                Lat:
                                <input id="lat" name="lat" val="40.713956" />Long:
                                <input id="long" name="long" val="74.006653" />
                                <br />
                                <br />
                                <div id="map_canvas" style="width: 500px; height: 250px;"></div>
                            </div>
                          </div>
                        </div>-->

                        <!-- Information pane -->
                        <div class="tab-pane tab-informations" id="tab-informations-<?php echo $file['ph_id'] ?>">
                          <p class="text-muted mgbot20 mgtop15"><?php echo ucfirst($lang['general.file.name']) ?> <strong><?php echo $file['ph_file'] ?></strong> <?php echo $lang['general.last.saved'].' '.$file['ph_date_created'] ?></p>
                      
                          <div class="form-group">
                            <label><?php echo $lang['admin.editor.photo.panes.informations.thumbnail.url'] ?> <span class="label label-default"><?php echo $file['ph_thumb_width'].'x'.$file['ph_thumb_height'] ?></span></label>
                            <input type="text" value="<?php echo $file['ph_thumb_url'] ?>" class="form-control">
                          </div>
                          <div class="form-group">
                            <label><?php echo $lang['admin.editor.photo.panes.informations.preview.url'] ?> <span class="label label-default"><?php echo $file['ph_medium_width'].'x'.$file['ph_medium_height'] ?></span></label>
                            <input type="text" value="<?php echo $file['ph_medium_url'] ?>" class="form-control">
                          </div>
                          <div class="form-group">
                            <label><?php echo $lang['admin.editor.photo.panes.informations.full.size.url'] ?> <span class="label label-default"><?php echo $file['ph_file_width'].'x'.$file['ph_file_height'] ?></span></label>
                            <input type="text" value="<?php echo $file['ph_file_url'] ?>" class="form-control">
                          </div>
                          <div class="form-group">
                            <label><?php echo $lang['admin.editor.photo.panes.informations.main.exif'] ?></label>
                            <pre>
                              <?php print_r($file['ph_exif']) ?>
                            </pre>
                          </div>
                          <div class="form-group">
                            <label><?php echo $lang['admin.editor.photo.panes.informations.all.exif'] ?></label>
                            <pre>
                              <?php print_r($file['ph_exif_full']) ?>
                            </pre>
                          </div>
                          <div class="form-group">
                            <label><?php echo $lang['admin.editor.photo.panes.informations.all.iptc'] ?></label>
                            <pre>
                              <?php print_r($file['ph_iptc_full']) ?>
                            </pre>
                          </div>
                        </div><!-- ./Information pane -->

                      </div>
                    </div>
                </div><!-- ./row -->
              </div><!-- ./col md 9 -->


              <!-- Toolbar Save / Delete / Feature / Help -->
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-4 col-sm-6 col-xs-6">
                    <p class="text-muted text-left edit-alert none pdtop5"><span class="glyphicon glyphicon-info-sign"></span> <strong><?php echo $lang['admin.editor.photo.edited.but.not.saved'] ?></strong></p>
                  </div>

                  <div class="col-md-8 col-sm-6 col-xs-6 text-right sw-options-and-save">
                    <a  class="btn btn-default"
                        title="<?php echo ucfirst($lang['general.help.tooltip']) ?>"
                        data-toggle="modal"
                        data-target="#help-modal">
                        <span class="glyphicon glyphicon-question-sign"></span> 
                        <span class="hidden-xs hidden-sm hidden-md"><?php echo ucfirst($lang['general.help']) ?></span>
                    </a> 

                    <div class="btn-group" data-toggle="buttons">
                      <label class="btn <?php if($file['ph_featured']=='1') : echo 'active btn-success'; else : echo 'btn-default'; endif ?>" title="<?php if($file['ph_featured']=='1') : echo $lang['admin.editor.photo.toolbar.featured.tooltip']; else : echo $lang['admin.editor.photo.toolbar.feature.tooltip']; endif ?>">
                        <input  name="ph_featured" 
                                type="checkbox" 
                                value="1" 
                                <?php if($file['ph_featured']=='1') : echo 'checked'; endif ?>>
                                
                                  <span class="glyphicon glyphicon-<?php if($file['ph_featured']=='1') : echo 'star'; else : echo 'star-empty'; endif ?>"></span>
                                  <?php if($file['ph_featured']=='1') : echo ucfirst($lang['general.featured']); else : echo '<span class="hidden-xs hidden-sm hidden-md">'.$lang['admin.editor.photo.toolbar.feature'].'</span>'; endif ?>
  <?php if(isset($tpl_bypass['photo_featured'])) : ?>
                                <span class="glyphicon glyphicon-info-sign" title="<?php echo $lang['admin.parameters.parameter.bypasss.text'] ?>"></span>
<?php endif ?>
                      </label>
                    </div>

                    <a  class="btn btn-danger"
                        data-toggle="modal"
                        data-target="#<?php echo $file['ph_id'] ?>-delete"
                        title="<?php echo $lang['admin.editor.photo.toolbar.delete.tooltip'] ?>">
                        <span class="glyphicon glyphicon-trash"></span> 
                        <span class="hidden-xs hidden-sm hidden-md"><?php echo $lang['admin.editor.photo.toolbar.delete'] ?></span>
                    </a>

                    <button   type="submit" 
                              class="btn btn-primary date sw-save-photo"
                              data-toggle="tooltip"
                              data-placement="right"
                              title="<?php echo $lang['admin.editor.photo.toolbar.save.tooltip'] ?>">
                              <span class="glyphicon glyphicon-floppy-disk"></span> 
                              <span class="hidden-xs hidden-sm hidden-md"><?php echo $lang['admin.editor.photo.toolbar.save'] ?></span>
                    </button>
                    
                  </div>
                </div>
              </div><!-- ./Save and delete -->

          </form>
        </div>

        <!-- Delete modal -->
        <div class="modal fade" id="<?php echo $file['ph_id'] ?>-delete" tabindex="-1" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo $lang['admin.editor.photo.delete.modal.title'] ?></h4>
              </div>
              <div class="modal-body">
                <p class="mgbot20"><img src="<?php echo $file['ph_thumb_url'] ?>" alt="<?php echo $file['ph_title'] ?>" class="img-thumbnail"></p>
                
                <p class="mgbot20"><strong><?php echo $file['ph_file'] ?></strong></p>
                <p class="text-muted mgbot20"><?php echo ucfirst($lang['general.last.saved']).' '.$file['ph_date_created'] ?></p>
                <p class="text-muted mgbot20"><?php echo $lang['admin.editor.photo.delete.modal.message'] ?></p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo ucfirst($lang['general.cancel']) ?></button>
                <form action="includes/sw-delete.php" method="post" class="sw-delete-file pull-right mgleft10">
                    <input  name="file" 
                            type="hidden" 
                            value="<?php echo $file['ph_file'] ?>">
                    <button   type="submit" 
                              class="btn btn-danger btn-sg" 
                              data-id="<?php echo $file['ph_id'] ?>">
                              <span class="glyphicon glyphicon-trash"></span> 
                              <?php echo $lang['admin.editor.photo.delete.modal.button.confirm'] ?>
                    </button>
                </form>
              </div>
            </div>
          </div>
        </div><!-- /.delete modal -->
      </div><!-- /.Photo div -->

  <?php endforeach //End loop ?>
  <!-- 
      END LOOP
  -->

  <?php if(!isset($search)) : //Pagination excepting for search results ?>

      <div class="col-md-12 text-center mgbot50">
        <ul class="pagination pagination-lg">
          <li class="<?php if(!isset($_GET['page'])) { echo 'active'; } ?>">
            <a href="<?php echo $first_page ?>">1</a>
          </li>
          
    <?php foreach($pages as $page) :  ?>

          <li class="<?php echo $page['class'] ?>">
            <a href="<?php echo $target_page.$page['page'] ?>">
              <?php echo $page['page'] ?>
            </a>
          </li>

    <?php endforeach ?>

        </ul>
      </div>

  <?php endif //./Pagination excepting for search results ?>

      <p class="text-muted text-center mgbot50"><?php echo $lang['general.powered.by'] ?> <a href="<?php echo $lang['general.swan.gallery.url'] ?>" title="<?php echo $lang['general.swan.gallery.description'] ?>" class="text-muted"><?php echo $lang['general.swan.gallery.title'] ?></a></p>


    </div><!-- ./admin-main-container -->
  </div><!-- ./global row -->

  
<?php endif //jpg files array ?>
</div><!-- ./container-fluid -->

<!-- The blueimp Gallery widget -->
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter="">
  <div class="slides"></div>
  <h3 class="title"></h3>
  <a class="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
  <a class="next"><span class="glyphicon glyphicon-chevron-right"></span></a> 
  <a class="close"><span class="glyphicon glyphicon-remove"></span></a> 
  <a class="play-pause"></a>
  <ol class="indicator">
  </ol>
</div>


<!-- Help Modal -->
<div class="modal fade" id="help-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo $lang['admin.editor.help.modal.title'] ?></h4>
      </div>
      <div class="modal-body">
        <h5 class="page-header"><?php echo $lang['admin.editor.help.modal.photo.title'] ?></h5>
        <p><?php echo $lang['admin.editor.help.modal.photo.title.description'] ?></p>

        <h5 class="page-header"><?php echo $lang['admin.editor.help.modal.photo.description'] ?></h5>
        <p><?php echo $lang['admin.editor.help.modal.photo.description.description'] ?></p>

        <h5 class="page-header"><span class="glyphicon glyphicon-tag"></span> <?php echo $lang['admin.editor.help.modal.photo.tags'] ?></h5>
        <p><?php echo $lang['admin.editor.help.modal.photo.tags.description'] ?></p>

        <h5 class="page-header"><span class="glyphicon glyphicon-calendar"></span> <?php echo $lang['admin.editor.help.modal.photo.date'] ?></h5>
        <p><?php echo $lang['admin.editor.help.modal.photo.date.description'] ?></p>
        <p><?php echo $lang['admin.editor.help.modal.photo.date.description.addon.1'] ?></p>

        <h5 class="page-header"><span class="glyphicon glyphicon-star"></span> <?php echo $lang['admin.editor.help.modal.photo.featured'] ?></h5>
        <p><?php echo $lang['admin.editor.help.modal.photo.featured.description'] ?></p>
        
        <h5 class="page-header"><span class="glyphicon glyphicon-tags"></span> <?php echo $lang['admin.editor.help.modal.photo.favorite.to.tags'] ?></h5>
        <p><?php echo $lang['admin.editor.help.modal.photo.favorite.to.tags.description'] ?></p>

        <h5 class="page-header"><span class="glyphicon glyphicon-info-sign"></span> <?php echo $lang['admin.editor.help.modal.photo.informations'] ?></h5>
        <p><?php echo $lang['admin.editor.help.modal.photo.informations.description'] ?></p>

        <h5 class="page-header"><span class="glyphicon glyphicon-trash"></span> <?php echo $lang['admin.editor.help.modal.photo.delete'] ?></h5>
        <p><?php echo $lang['admin.editor.help.modal.photo.delete.description'] ?></p>

        <h5 class="page-header"><span class="glyphicon glyphicon-floppy-disk"></span> <?php echo $lang['admin.editor.help.modal.photo.save'] ?></h5>
        <p><?php echo $lang['admin.editor.help.modal.photo.save.description'] ?></p>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo ucfirst($lang['general.close']) ?></button>
      </div>
    </div>
  </div>
</div>
<!-- <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script> -->
<?php include 'includes/sw-js.php' ?>
<!-- <script>
  var map;

    function initialize() {
        var myLatlng = new google.maps.LatLng(47.282955732384075, 2.1286009062499716);

        var myOptions = {
            zoom: 4,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

        var marker = new google.maps.Marker({
            draggable: true,
            position: myLatlng,
            map: map,
            title: "Your location"
        });

        google.maps.event.addListener(marker, 'dragend', function (event) {


            document.getElementById("lat").value = event.latLng.lat();
            document.getElementById("long").value = event.latLng.lng();
        });
    }
    google.maps.event.addDomListener(window, "load", initialize());
</script> -->
</body>
</html>