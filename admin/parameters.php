<?php 
/**
* ADMIN PARAMETERS
* Parameters page of the gallery
* Swan Gallery
* @version 0.9
* @author Olivier Blanc http://www.egalise.com/swan-gallery
* @license MIT
*/


	$admin='../';
	//Make this page not cachable
	//header("Cache-Control: no-cache, must-revalidate");
	include ('../engine/config.php');
	include ('../engine/functions.php');
	include ('config.php');
	include ('functions.php');
	admin_session();
  //Get the list of available language files
  $languages_files = languages_list();
  //Gallery Language
  if(file_exists('languages/'.$gallery_admin_language.'.php')){
    include 'languages/'.$gallery_admin_language.'.php';
  }else{
    //Default Language
    include 'languages/en_EN.php';
  }
  //Optional Template Language
  if(file_exists('../engine/templates/'.$gallery_template.'/languages/'.$gallery_admin_language.'.php')){
    include '../engine/templates/'.$gallery_template.'/languages/'.$gallery_admin_language.'.php';
  }
	//Path to the current admin path
	$script_path=dirname(getcwd());
	//Get available templates list
	$templates = scan_tpl();
  //List of available directories in the directory where Swan Gallery is installed
  $directories = list_directories();
  //Complete array of photos in the gallery directory
  $all_files = scan($script_path.'/'.$gallery_folder, '', '', '', '');
  //Get tag list
  $tags=tag_list('../'.$gallery_folder, 1000, '');
	//Admin page 'active' class to highlight the current page in the admin menu
	$menu_active='parameters';
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<!-- Force latest IE rendering engine or ChromeFrame if installed -->
<!--[if IE]>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<![endif]-->
<meta charset="utf-8">
<title><?php echo $lang['admin.parameters.title'] ?></title> 
<meta name="description" content="<?php echo $lang['admin.parameters.description'] ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php include 'includes/sw-css.php' ?>
</head>
<body>

<?php include 'includes/sw-navbar.php' ?>

<div class="container">
    <form role="form" class="sw-save-file" action="write.php" method="post">
        <div class="row mgbot30">
              <div class="col-md-12 mgbot30">
              	<h1><?php echo $lang['admin.parameters.title'] ?> <small><?php echo $lang['admin.parameters.description'] ?></small></h1>
              </div>
              <div class="col-md-12">
              	<h2><?php echo $lang['admin.parameters.general'] ?></h2>
                <hr>
              </div>

              <!-- Photos Directory -->
              <div class="col-md-10 col-sm-6 col-xs-12">
                  <label><?php echo $lang['admin.parameters.photos.directory'] ?> (<?php echo $lang['general.mandatory'] ?>)</label>
                  <p class="text-muted"><?php echo $lang['admin.parameters.photos.directory.description'] ?></p>
              </div>
              <div class="col-md-2 col-sm-6 col-xs-12">
                  <select name="gallery_folder" class="">
                    <?php foreach ($directories as $key => $directory) : ?>
                    <option value="<?php echo $directory ?>" <?php if($gallery_folder == $directory) : echo 'selected'; endif ?>><?php echo $directory ?></option>
                    <?php endforeach ?>
                  </select>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 mgbot30"></div>


              <!-- Gallery Title -->
              <div class="col-md-8 col-sm-6 col-xs-12">
                  <label><?php echo $lang['admin.parameters.gallery.title'] ?> (<?php echo $lang['general.very.important'] ?>)</label>
                  <p class="text-muted"><?php echo $lang['admin.parameters.gallery.title.description'] ?></p>
              </div>
              <div class="col-md-4 col-sm-6 col-xs-12 text-right">
                    <input type="text" name="gallery_title" class="form-control input-lg" value="<?php echo $gallery_title ?>">
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 mgbot30"></div>


              <!-- Gallery Description -->
              <div class="col-md-8 col-sm-6 col-xs-12">
                  <label><?php echo $lang['admin.parameters.gallery.description'] ?> (<?php echo $lang['general.very.important'] ?>)</label>
                  <p class="text-muted"><?php echo $lang['admin.parameters.gallery.description.description'] ?></p>
              </div>
              <div class="col-md-4 col-sm-6 col-xs-12 text-right">
                  <textarea name="gallery_description" rows="3" class="form-control"><?php echo $gallery_description ?></textarea>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 mgbot30"></div>

              <!-- Gallery Pagination / Photos per page -->
              <div class="col-md-10 col-sm-6 col-xs-12">
                  <label><?php echo $lang['admin.parameters.pagination'] ?> (<?php echo $lang['general.very.important'] ?>)</label>
<?php if(isset($tpl_bypass['pagination'])) : ?>
                  <p class="alert alert-warning"><span class="glyphicon glyphicon-info-sign"></span> <?php echo $lang['admin.parameters.parameter.bypasss.text'] ?></p>
<?php endif ?>
                  <p class="text-muted"><?php echo $lang['admin.parameters.pagination.description'] ?> </p>
              </div>
              <div class="col-md-2 col-sm-6 col-xs-12">
                  <select name="gallery_photos_per_page" class="pdleft20">
                    <option value="999"><?php echo $lang['admin.parameters.pagination.no.pagination'] ?></option>
                    <?php for ($i = 1; $i <= 100; $i++) : ?>
                    <option value="<?php echo $i ?>" <?php if($gallery_photos_per_page == $i) : echo 'selected'; endif ?>><?php echo $i ?></option>
                    <?php endfor ?>
                  </select>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 mgbot30"></div>  
              
              <!-- Gallery Tag Limit -->
              <div class="col-md-10 col-sm-6 col-xs-12">
                  <label><?php echo $lang['admin.parameters.tag.limit'] ?> (<?php echo $lang['general.very.important'] ?>)</label>
<?php if(isset($tpl_bypass['tag_limit'])) : ?>
                  <p class="alert alert-warning"><span class="glyphicon glyphicon-info-sign"></span> <?php echo $lang['admin.parameters.parameter.bypasss.text'] ?></p>
<?php endif ?>
                  <p class="text-muted"><?php echo $lang['admin.parameters.tag.limit.description'] ?></p>
                  <ul>
                    <li class="text-muted"><?php echo $lang['admin.parameters.tag.limit.description.addon.1'] ?></li>
                    <li class="text-muted"><?php echo $lang['admin.parameters.tag.limit.description.addon.2'] ?></li>
                  </ul>
              </div>
              <div class="col-md-2 col-sm-6 col-xs-12 text-right">
                  <select name="gallery_tag_limit" class="pdleft20">
                    <option value="999"><?php echo ucfirst($lang['general.unlimited']) ?></option>
                    <?php for ($i = 1; $i <= 300; $i++) : ?>
                    <option value="<?php echo $i ?>" <?php if($gallery_tag_limit == $i) : echo 'selected'; endif ?>><?php echo $i ?></option>
                    <?php endfor ?>
                  </select>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 mgbot30"></div>   

              <!-- Gallery Frontend Tag List Ordering -->
              <div class="col-md-9 col-sm-8 col-xs-12">
                  <label><?php echo $lang['admin.parameters.frontend.tag.list.ordering'] ?> (<?php echo $lang['general.very.important'] ?>)</label>
<?php if(isset($tpl_bypass['tag_list_ordering'])) : ?>
                  <p class="alert alert-warning"><span class="glyphicon glyphicon-info-sign"></span> <?php echo $lang['admin.parameters.parameter.bypasss.text'] ?></p>
<?php endif ?>
                  <p class="text-muted"><?php echo $lang['admin.parameters.frontend.tag.list.ordering.description'] ?></p>
              </div>
              <div class="col-md-3 col-sm-4 col-xs-12 text-right">
                  <div class="btn-group pull-right" data-toggle="buttons">
                    <label class="btn btn-default <?php if($gallery_tag_list_ordering=='popularity') : echo 'active'; endif ?>">
                      <input type="radio" name="gallery_tag_list_ordering" value="popularity" <?php if($gallery_tag_list_ordering=='popularity') : echo 'checked'; endif ?>> 
                      <?php echo ucfirst($lang['general.popularity']) ?>
                    </label>
                    <label class="btn btn-default <?php if($gallery_tag_list_ordering=='a-z') : echo 'active'; endif ?>">
                      <input type="radio" name="gallery_tag_list_ordering" value="a-z" <?php if($gallery_tag_list_ordering=='a-z') : echo 'checked'; endif ?>> 
                      <?php echo $lang['general.a-z'] ?>
                    </label>
                    <label class="btn btn-default <?php if($gallery_tag_list_ordering=='z-a') : echo 'active'; endif ?>">
                      <input type="radio" name="gallery_tag_list_ordering" value="z-a" <?php if($gallery_tag_list_ordering=='z-a') : echo 'checked'; endif ?>> 
                      <?php echo $lang['general.z-a'] ?>
                    </label>
                  </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 mgbot30"></div>   
              
              <!-- Gallery Frontend Sorting -->
              <div class="col-md-9 col-sm-9 col-xs-12">
                  <label><?php echo $lang['admin.parameters.frontend.sorting.by'] ?> (<?php echo $lang['general.important'] ?>)</label>
                  <p class="text-muted"><?php echo $lang['admin.parameters.frontend.sorting.by.description'] ?></p>

                  <ul>
                    <li class="text-muted"><?php echo $lang['admin.parameters.frontend.sorting.by.description.addon.1'] ?></li>
                    <li class="text-muted"><?php echo $lang['admin.parameters.frontend.sorting.by.description.addon.2'] ?></li>
                    <li class="text-muted"><?php echo $lang['admin.parameters.frontend.sorting.by.description.addon.3'] ?></li>
                    <li class="text-muted"><?php echo $lang['admin.parameters.frontend.sorting.by.description.addon.4'] ?></li>
                  </ul>
              </div>
              <div class="col-md-3 col-sm-3 col-xs-12">
                  <select name="gallery_sorting" class="pdleft20">
                    <option value="ph_date" <?php if($gallery_sorting=='ph_date') : echo 'selected'; endif ?>><?php echo ucfirst($lang['general.date']) ?></option>
                    <option value="ph_date_created" <?php if($gallery_sorting=='ph_date_created') : echo 'selected'; endif ?>><?php echo ucfirst($lang['general.date.created']) ?></option>
                    <option value="ph_file_lowercase" <?php if($gallery_sorting=='ph_file_lowercase') : echo 'selected'; endif ?>><?php echo ucfirst($lang['general.file.name']) ?></option>
                    <option value="ph_title" <?php if($gallery_sorting=='ph_title') : echo 'selected'; endif ?>><?php echo ucfirst($lang['general.title']) ?></option>
                  </select>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 mgbot30"></div>  

              <!-- Gallery Frontend Ordering -->
              <div class="col-md-9 col-sm-8 col-xs-12">
                  <label><?php echo $lang['admin.parameters.frontend.ordering'] ?> (<?php echo $lang['general.important'] ?>)</label>
                  <p class="text-muted"><?php echo $lang['admin.parameters.frontend.ordering.description'] ?></p>
              </div>
              <div class="col-md-3 col-sm-4 ol-xs-12">
                  <div class="btn-group pull-right" data-toggle="buttons">
                    <label class="btn btn-default <?php if($gallery_ordering=='ASC') : echo 'active'; endif ?>">
                      <input type="radio" name="gallery_ordering" value="ASC" <?php if($gallery_ordering=='ASC') : echo 'checked'; endif ?>> 
                      <?php echo ucfirst($lang['general.ascending']) ?>
                    </label>
                    <label class="btn btn-default <?php if($gallery_ordering=='DESC') : echo 'active'; endif ?>">
                      <input type="radio" name="gallery_ordering" value="DESC" <?php if($gallery_ordering=='DESC') : echo 'checked'; endif ?>> 
                      <?php echo ucfirst($lang['general.descending']) ?>
                    </label>
                  </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 mgbot30"></div>

              <!-- Gallery Homepage Type -->
              <div class="col-md-9 col-sm-9 col-xs-12">
                  <label><?php echo $lang['admin.parameters.homepage.type'] ?> (<?php echo $lang['general.important'] ?>)</label>
<?php if(isset($tpl_bypass['homepage_type'])) : ?>
                  <p class="alert alert-warning"><span class="glyphicon glyphicon-info-sign"></span> <?php echo $lang['admin.parameters.parameter.bypasss.text'] ?></p>
<?php endif ?>
                  <p class="text-muted"><?php echo $lang['admin.parameters.homepage.type.description'] ?></p>
                  <ul>
                    <li class="text-muted"><?php echo $lang['admin.parameters.homepage.type.description.addon.1'] ?></li>
                    <li class="text-muted"><?php echo $lang['admin.parameters.homepage.type.description.addon.2'] ?></li>
                  </ul>
              </div>    
              <div class="col-md-3 col-sm-3 col-xs-12">
                  <div class="btn-group pull-right" data-toggle="buttons">
                    <label class="btn btn-default <?php if($gallery_homepage_type=='std') : echo 'active'; endif ?>">
                      <input type="radio" name="gallery_homepage_type" value="std" <?php if($gallery_homepage_type=='std') : echo 'checked'; endif ?>> 
                      <?php echo ucfirst($lang['general.standard']) ?>
                    </label>
                    <label class="btn btn-default <?php if($gallery_homepage_type=='tags') : echo 'active'; endif ?>">
                      <input type="radio" name="gallery_homepage_type" value="tags" <?php if($gallery_homepage_type=='tags') : echo 'checked'; endif ?>> 
                      <?php echo ucfirst($lang['general.tags']) ?>
                    </label>
                  </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 mgbot30"></div>     

              <!-- Gallery Cache -->
              <div class="col-md-9 col-sm-8 col-xs-12">
                  <label><?php echo $lang['admin.parameters.cache'] ?> (<?php echo $lang['general.important'] ?>)</label>
                  <p class="text-muted"><?php echo $lang['admin.parameters.cache.description'] ?></p>
              </div>    
              <div class="col-md-3 col-sm-4 col-xs-12">

                  <div class="btn-group pull-right" data-toggle="buttons">
                    <label class="btn btn-default <?php if($gallery_cache=='enabled') : echo 'active'; endif ?>">
                      <input type="radio" name="gallery_cache" value="enabled" <?php if($gallery_cache=='enabled') : echo 'checked'; endif ?>> 
                      <?php echo ucfirst($lang['general.enabled']) ?>
                    </label>
                    <label class="btn btn-default <?php if($gallery_cache=='disabled') : echo 'active'; endif ?>">
                      <input type="radio" name="gallery_cache" value="disabled" <?php if($gallery_cache=='disabled') : echo 'checked'; endif ?>> 
                      <?php echo ucfirst($lang['general.disabled']) ?>
                    </label>
                  </div>

                  <hr class="invisible">
                  <a href="#delete-cache-modal" class="btn btn-danger pull-right mgtop10" data-toggle="modal"><span class="glyphicon glyphicon-trash"></span> <?php echo $lang['admin.parameters.cache.delete.cache.button'] ?></a>
                  
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 mgbot30"></div>      

              <!-- Gallery Cache Expire -->
              <div class="col-md-9 col-sm-9 col-xs-12">
                  <label><?php echo $lang['admin.parameters.cache.expire'] ?> (<?php echo $lang['general.important'] ?>)</label>
                  <p class="text-muted"><?php echo $lang['admin.parameters.cache.expire.description'] ?></p>
              </div> 
              <div class="col-md-3 col-sm-3 col-xs-12">
                  <select name="gallery_cache_expire" class="pdleft20">
                    <option value="never"><?php echo $lang['admin.parameters.cache.expire.never.expires'] ?></option>
  <?php for ($i = 6; $i <= 720; $i = $i+6) : ?>
                    <option value="<?php echo $i ?>" <?php if($gallery_cache_expire == $i) : echo 'selected'; endif ?>><?php echo $i ?></option>
  <?php endfor ?>
                  </select>
              </div> 
              <div class="col-md-12 col-sm-12 col-xs-12 mgbot30"></div>        
              
              <!-- Gallery Related Behaviour -->
              <div class="col-md-8 col-sm-8 col-xs-12">
                  <label><?php echo $lang['admin.parameters.related.behaviour'] ?> (<?php echo $lang['general.optional'] ?>)</label>
<?php if(isset($tpl_bypass['related_behaviour'])) : ?>
                  <p class="alert alert-warning"><span class="glyphicon glyphicon-info-sign"></span> <?php echo $lang['admin.parameters.parameter.bypasss.text'] ?></p>
<?php endif ?>
                  <p class="text-muted"><?php echo $lang['admin.parameters.related.behaviour.description'] ?></p>
                  <ul>
                  <li class="text-muted"><?php echo $lang['admin.parameters.related.behaviour.description.addon.1'] ?></li>
                  <li class="text-muted"><?php echo $lang['admin.parameters.related.behaviour.description.addon.2'] ?></li>
                  <li class="text-muted"><?php echo $lang['admin.parameters.related.behaviour.description.addon.3'] ?></li>
                  </ul>
              </div>
              <div class="col-md-4 col-sm-4 col-xs-12">
                  <div class="btn-group pull-right" data-toggle="buttons">
                    <label class="btn btn-default <?php if($gallery_related=='titles') : echo 'active'; endif ?>">
                      <input type="radio" name="gallery_related" value="titles" <?php if($gallery_related=='titles') : echo 'checked'; endif ?>> 
                      <?php echo ucfirst($lang['general.titles']) ?>
                    </label>
                    <label class="btn btn-default <?php if($gallery_related=='tags') : echo 'active'; endif ?>">
                      <input type="radio" name="gallery_related" value="tags" <?php if($gallery_related=='tags') : echo 'checked'; endif ?>> 
                      <?php echo ucfirst($lang['general.tags']) ?> 
                    </label>
                    <label class="btn btn-default <?php if($gallery_related=='both') : echo 'active'; endif ?>">
                      <input type="radio" name="gallery_related" value="both" <?php if($gallery_related=='both') : echo 'checked'; endif ?>> 
                      <?php echo ucfirst($lang['general.both']) ?>
                    </label>
                  </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 mgbot30"></div>
              
              <!-- Gallery Related Limit -->
              <div class="col-md-10 col-sm-9 col-xs-12">
                  <label><?php echo $lang['admin.parameters.related.limit'] ?> (<?php echo $lang['general.optional'] ?>)</label>
<?php if(isset($tpl_bypass['related_limit'])) : ?>
                  <p class="alert alert-warning"><span class="glyphicon glyphicon-info-sign"></span> <?php echo $lang['admin.parameters.parameter.bypasss.text'] ?></p>
<?php endif ?>
                  <p class="text-muted"><?php echo $lang['admin.parameters.related.limit.description'] ?></p>
              </div>
              <div class="col-md-2 col-sm-3 col-xs-12">
                  <select name="gallery_related_limit"  class="pdleft20">
                    <option value="none"><?php echo $lang['admin.parameters.no.related.photos'] ?></option>
                    <?php for ($i = 1; $i <= 12; $i++) : ?>
                    <option value="<?php echo $i ?>" <?php if($gallery_related_limit == $i) : echo 'selected'; endif ?>><?php echo $i ?></option>
                    <?php endfor ?>
                  </select>
              </div> 
              <div class="col-md-12 col-sm-12 col-xs-12 mgbot30"></div>
              
              <!-- Gallery Email -->
              <div class="col-md-8 col-sm-6 col-xs-12">
                  <label><?php echo ucfirst($lang['general.email']) ?> (<?php echo $lang['general.optional'] ?>)</label>
<?php if(isset($tpl_bypass['email'])) : ?>
                  <p class="alert alert-warning"><span class="glyphicon glyphicon-info-sign"></span> <?php echo $lang['admin.parameters.parameter.bypasss.text'] ?></p>
<?php endif ?>
                  <p class="text-muted"><?php echo $lang['admin.parameters.email.description'] ?></p>
              </div>
              <div class="col-md-4 col-sm-6 col-xs-12">
                  <input type="email" name="gallery_email" class="form-control" value="<?php echo $gallery_email ?>">
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 mgbot30"></div>
              
              <!-- Gallery Photo Credit -->
              <div class="col-md-8 col-sm-6 col-xs-12">
                  <label><?php echo $lang['admin.parameters.photo.credit'] ?> (<?php echo $lang['general.optional'] ?>)</label>
<?php if(isset($tpl_bypass['credit'])) : ?>
                  <p class="alert alert-warning"><span class="glyphicon glyphicon-info-sign"></span> <?php echo $lang['admin.parameters.parameter.bypasss.text'] ?></p>
<?php endif ?>
                  <p class="text-muted"><?php echo $lang['admin.parameters.photo.credit.description'] ?></p>
              </div>
              <div class="col-md-4 col-sm-6 col-xs-12">
                  <input type="text" name="gallery_credit" class="form-control" value="<?php echo $gallery_credit ?>">
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 mgbot30"></div>
              
              <!-- Gallery Display Photo Credit -->
              <div class="col-md-10 col-sm-9 col-xs-12">
                  <label><?php echo $lang['admin.parameters.display.photo.credit'] ?> (<?php echo $lang['general.optional'] ?>)</label>
<?php if(isset($tpl_bypass['credit_display'])) : ?>
                  <p class="alert alert-warning"><span class="glyphicon glyphicon-info-sign"></span> <?php echo $lang['admin.parameters.parameter.bypasss.text'] ?></p>
<?php endif ?>
                  <p class="text-muted"><?php echo $lang['admin.parameters.display.photo.credit.description'] ?></p>
              </div>
              <div class="col-md-2 col-sm-3 col-xs-12">
                  <div class="btn-group pull-right" data-toggle="buttons">
                    <label class="btn btn-default <?php if($gallery_credit_display=='1') : echo 'active'; endif ?>">
                      <input type="radio" name="gallery_credit_display" value="1" <?php if($gallery_credit_display=='1') : echo 'checked'; endif ?>> 
                      <?php echo ucfirst($lang['general.yes']) ?>
                    </label>
                    <label class="btn btn-default <?php if($gallery_credit_display=='0') : echo 'active'; endif ?>">
                      <input type="radio" name="gallery_credit_display" value="0" <?php if($gallery_credit_display=='0') : echo 'checked'; endif ?>> 
                      <?php echo ucfirst($lang['general.no']) ?>
                    </label>
                  </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 mgbot30"></div>
              
              <!-- Gallery Analytics Tracking Code -->
              <div class="col-md-8 col-sm-6 col-xs-12">
                  <label><?php echo $lang['admin.parameters.web.analytics.tracking.code'] ?> (<?php echo $lang['general.optional'] ?>)</label>
<?php if(isset($tpl_bypass['statistics'])) : ?>
                  <p class="alert alert-warning"><span class="glyphicon glyphicon-info-sign"></span> <?php echo $lang['admin.parameters.parameter.bypasss.text'] ?></p>
<?php endif ?>
                  <p class="text-muted"><?php echo $lang['admin.parameters.web.analytics.tracking.code.description'] ?></p>
              </div>
              <div class="col-md-4 col-sm-6 col-xs-12">
                  <textarea name="gallery_statistics_code" class="form-control" rows="10"><?php echo $gallery_statistics_code ?></textarea>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 mgbot30"></div>
              
              <!-- Gallery Facebook Page -->
              <div class="col-md-8 col-sm-6 col-xs-12">
                  <label><?php echo $lang['admin.parameters.facebook.page'] ?> (<?php echo $lang['general.optional'] ?>)</label>
<?php if(isset($tpl_bypass['facebook'])) : ?>
                  <p class="alert alert-warning"><span class="glyphicon glyphicon-info-sign"></span> <?php echo $lang['admin.parameters.parameter.bypasss.text'] ?></p>
<?php endif ?>
                  <p class="text-muted"><?php echo $lang['admin.parameters.facebook.page.description'] ?></p>
              </div>
              <div class="col-md-4 col-sm-6 col-xs-12">
                  <input type="text" name="gallery_facebook_page" class="form-control" value="<?php echo $gallery_facebook_page ?>">
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 mgbot30"></div>
              
              <!-- Gallery Twitter Page -->
              <div class="col-md-8 col-sm-6 col-xs-12">
                  <label><?php echo $lang['admin.parameters.twitter'] ?> (<?php echo $lang['general.optional'] ?>)</label>
<?php if(isset($tpl_bypass['twitter'])) : ?>
                  <p class="alert alert-warning"><span class="glyphicon glyphicon-info-sign"></span> <?php echo $lang['admin.parameters.parameter.bypasss.text'] ?></p>
<?php endif ?>
                  <p class="text-muted"><?php echo $lang['admin.parameters.twitter.description'] ?></p>
              </div>
              <div class="col-md-4 col-sm-6 col-xs-12">
                  <input type="text" name="gallery_twitter" class="form-control" value="<?php echo $gallery_twitter ?>">
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 mgbot30"></div>

              <!-- Gallery RSS Feed -->
              <div class="col-md-8 col-sm-6 col-xs-12">
                  <label><?php echo $lang['admin.parameters.rss.feed'] ?> (<?php echo $lang['general.optional'] ?>)</label>
                  <p class="text-muted"><?php echo $lang['admin.parameters.rss.feed.description'] ?> <a href="../feed/" title="URL of the RSS feed of your gallery" target="_blank"><?php echo $lang['admin.parameters.here.is.your.rss.feed.url'] ?></a></p>
              </div>
              <div class="col-md-4 col-sm-6 col-xs-12">
                  <select name="gallery_rss_entries"  class="pdleft20">
                    <option value="none"><?php echo $lang['admin.parameters.no.rss.feed'] ?></option>
                    <?php for ($i = 1; $i <= 50; $i++) : ?>
                    <option value="<?php echo $i ?>" <?php if($gallery_rss_entries == $i) : echo 'selected'; endif ?>><?php echo $i ?></option>
                    <?php endfor ?>
                    <option value="999" <?php if($gallery_rss_entries == 999) : echo 'selected'; endif ?>><?php echo ucfirst($lang['general.unlimited']) ?></option>
                  </select>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 mgbot30"></div>
              
              <!-- Gallery Admin Sorting -->
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <label><?php echo $lang['admin.parameters.backend.default.sorting.by'] ?> (<?php echo $lang['general.optional'] ?>)</label>
                  <p class="text-muted"><?php echo $lang['admin.parameters.backend.default.sorting.by.description'] ?></p>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="gallery_admin_sorting" class="pdleft20">
                    <option value="ph_date" <?php if($gallery_admin_sorting=='ph_date') : echo 'selected'; endif ?>><?php echo ucfirst($lang['general.date']) ?></option>
                    <option value="ph_date_created" <?php if($gallery_admin_sorting=='ph_date_created') : echo 'selected'; endif ?>><?php echo ucfirst($lang['general.date.created']) ?></option>
                    <option value="ph_file_lowercase" <?php if($gallery_admin_sorting=='ph_file_lowercase') : echo 'selected'; endif ?>><?php echo ucfirst($lang['general.file.name']) ?></option>
                    <option value="ph_title" <?php if($gallery_admin_sorting=='ph_title') : echo 'selected'; endif ?>><?php echo ucfirst($lang['general.title']) ?></option>
                  </select>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 mgbot30"></div>
              
              <!-- Gallery Admin Ordering -->
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <label><?php echo $lang['admin.parameters.backend.default.ordering'] ?> (<?php echo $lang['general.optional'] ?>)</label>
                  <p class="text-muted"><?php echo $lang['admin.parameters.backend.default.ordering.description'] ?></p>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="btn-group pull-right" data-toggle="buttons">
                    <label class="btn btn-default <?php if($gallery_admin_ordering=='ASC') : echo 'active'; endif ?>">
                      <input type="radio" name="gallery_admin_ordering" value="ASC" <?php if($gallery_ordering=='ASC') : echo 'checked'; endif ?>> 
                      <?php echo ucfirst($lang['general.ascending']) ?>
                    </label>
                    <label class="btn btn-default <?php if($gallery_admin_ordering=='DESC') : echo 'active'; endif ?>">
                      <input type="radio" name="gallery_admin_ordering" value="DESC" <?php if($gallery_ordering=='DESC') : echo 'checked'; endif ?>> 
                      <?php echo ucfirst($lang['general.descending']) ?>
                    </label>
                  </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 mgbot30"></div>
              
              <!-- Gallery Admin Paginations/Photos per page -->
              <div class="col-md-10 col-sm-6 col-xs-12">
                  <label><?php echo $lang['admin.parameters.backend.pagination'] ?> (<?php echo $lang['general.optional'] ?>)</label>
                  <p class="text-muted"><?php echo $lang['admin.parameters.backend.pagination.description'] ?></p>
              </div>
              <div class="col-md-2 col-sm-6 col-xs-12">
                  <select name="gallery_admin_photos_per_page" class="pdleft20">
                    <?php for ($i = 1; $i <= 49; $i++) : ?>
                    <option value="<?php echo $i ?>" <?php if($gallery_admin_photos_per_page == $i) : echo 'selected'; endif ?>><?php echo $i ?></option>
                    <?php endfor ?>
                    <option value="50">50 (max)</option>
                  </select>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 mgbot30"></div>

              <!-- Gallery Admin Tag List Ordering -->
              <div class="col-md-9 col-sm-8 col-xs-12">
                  <label><?php echo $lang['admin.parameters.backend.tag.list.ordering'] ?> (<?php echo $lang['general.optional'] ?>)</label>
                  <p class="text-muted"><?php echo $lang['admin.parameters.backend.tag.list.ordering.description'] ?></p>
              </div>
              <div class="col-md-3 col-sm-4 col-xs-12 text-right">
                  <div class="btn-group pull-right" data-toggle="buttons">
                    <label class="btn btn-default <?php if($gallery_admin_tag_list_ordering=='popularity') : echo 'active'; endif ?>">
                      <input type="radio" name="gallery_admin_tag_list_ordering" value="popularity" <?php if($gallery_admin_tag_list_ordering=='popularity') : echo 'checked'; endif ?>> 
                      <?php echo ucfirst($lang['general.popularity']) ?>
                    </label>
                    <label class="btn btn-default <?php if($gallery_admin_tag_list_ordering=='a-z') : echo 'active'; endif ?>">
                      <input type="radio" name="gallery_admin_tag_list_ordering" value="a-z" <?php if($gallery_admin_tag_list_ordering=='a-z') : echo 'checked'; endif ?>> 
                      <?php echo $lang['general.a-z'] ?>
                    </label>
                    <label class="btn btn-default <?php if($gallery_admin_tag_list_ordering=='z-a') : echo 'active'; endif ?>">
                      <input type="radio" name="gallery_admin_tag_list_ordering" value="z-a" <?php if($gallery_admin_tag_list_ordering=='z-a') : echo 'checked'; endif ?>> 
                      <?php echo $lang['general.z-a'] ?>
                    </label>
                  </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 mgbot30"></div>        

              <!-- Gallery Admin Language -->
              <div class="col-md-10 col-sm-6 col-xs-12">
                  <label><?php echo $lang['admin.parameters.language'] ?> (<?php echo $lang['general.optional'] ?>)</label>
                  <p class="text-muted"><?php echo $lang['admin.parameters.language.description'] ?></p>
              </div>
              <div class="col-md-2 col-sm-6 col-xs-12">
                  <select name="gallery_admin_language" class="pdleft20">
                    <?php foreach ($languages_files as $language_file) : ?>
                    <option value="<?php echo $language_file ?>" <?php if($language_file == $gallery_admin_language) : echo 'selected'; endif ?>><?php echo $language_file ?></option>
                    <?php endforeach ?>
                  </select>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 mgbot30"></div>

              <!-- Save Parameters Button -->
              <div class="col-md-12 col-sm-12 col-xs-12">
                  <button type="submit" class="btn btn-primary btn-lg pull-right quick-save" title="<?php echo $lang['admin.parameters.save.gallery.parameters'] ?>"><span class="glyphicon glyphicon-floppy-disk"></span> <?php echo ucfirst($lang['general.save']) ?></button>
              </div>
        </div>


        <!--
            TEMPLATES 
        -->
        <div class="row mgtop30" id="templates">
          <h2><?php echo $lang['admin.parameters.templates'] ?></h2>
          <p><?php echo $lang['admin.parameters.templates.description'] ?></p>
          <p><?php echo $lang['admin.parameters.templates.description.warning.rebuild'] ?> <a href="rebuild-thumbnails.php"><?php echo lcfirst($lang['admin.navbar.rebuild.tooltip']) ?></a></p>
        </div>
        <hr>
<?php foreach($templates as $tpl_id => $tpl) : ?>
  <?php if($tpl['is_current'] == 'yes') : echo '<div class="alert alert-success">'; endif ?>
        <div class="row">
          <div class="col-md-3">
              <img src="<?php echo $tpl['tpl_preview_url'] ?>" alt="<?php echo $tpl['tpl_title'] ?>" class="img-thumbnail">
          </div>
          <div class="col-md-6">
              <h3>
  <?php if($tpl['is_current'] == 'yes') : ?>
                <span class="label label-success"><span class="glyphicon glyphicon-ok"></span></span>
  <?php endif ?>
                <?php echo $tpl['tpl_title'] ?> 
              </h3>
              <p class="text-muted">
                <?php echo $tpl['tpl_author'] ?> 
                <a href="<?php echo $tpl['tpl_author_url'] ?>" class="text-muted"><?php echo $tpl['tpl_author_url'] ?></a>
              </p>
              <p class="text-muted">
                <?php echo $tpl['tpl_description'] ?> 
              </p>
              <p class="text-muted"><span class="glyphicon glyphicon-calendar"></span> <?php echo date_formatted($tpl['tpl_date'], '.') ?></p>
              <?php if($tpl['is_current'] == 'yes') :  ?>
              <input type="hidden" name="gallery_template" value="<?php echo $tpl['tpl_dir'] ?>">
              <?php endif ?>
          </div>
          <div class="col-md-3 text-right"> 
  <?php if($tpl['is_current'] == 'yes') : //If current template ?>
    <?php if($tpl['tpl_custom'] !== 'none') : //If has parameters ?>
              <a  href="#" 
                  class="btn btn-success" 
                  title="<?php echo $lang['admin.parameters.edit.template.parameters'] ?>"
                  data-toggle="modal"
                  data-target="#tpl-parameters-modal">
                  <span class="glyphicon glyphicon-cog"></span> 
                  <?php echo $lang['admin.parameters.title'] ?>
              </a>
              
    <?php else : //Has no parameters ?>

    <?php endif //Has/Has not parameters ?>
  <?php else : //If not current template ?>
              <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-default btn-template-apply">
                <input type="radio" name="gallery_template" value="<?php echo $tpl['tpl_dir'] ?>">
                <?php echo ucfirst($lang['general.apply']) ?>
                </label>
              </div>
  <?php endif //Current template ?>
            </div>
        </div><!-- ./row -->
        <hr class="invisible">
  <?php if($tpl['is_current'] == 'yes') : echo '</div>'; endif ?>
      
<?php endforeach //templates ?> 
        <hr class="invisible">
        <p class="text-right">
          <button   type="submit" 
                    class="btn btn-primary btn-lg pull-right"
                    title="<?php echo $lang['admin.parameters.save.gallery.parameters'] ?>">
                    <span class="glyphicon glyphicon-floppy-disk"></span> 
                    <?php echo ucfirst($lang['general.save']) ?>
          </button>
        </p>
  </form>
</div><!--./container-->


<!-- Modal for template parameters -->
<div class="modal fade" id="tpl-parameters-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo ucfirst($lang['general.close']) ?></span></button>
        <h3 class="modal-title" id="myModalLabel"><?php echo $lang['admin.template.parameters'] ?></h3>
      </div>
      <div class="modal-body">
        <form role="form" class="sw-save-file" action="write.php" method="post">
          <input type="hidden" name="tpl_parameters">
<?php foreach($templates as $tpl) : ?>
  <?php if($tpl['is_current'] == 'yes' && $tpl['tpl_custom'] !== 'none') : //If current template and has custom parameters ?>
    <?php foreach($tpl['tpl_custom'] as $parameter_id => $parameter_array) : //Template parameter ?>    
              <h4 class="page-header">
                <?php 
                      if(!empty($tpl_lang[$parameter_id.'.title'])) : 
                        echo $tpl_lang[$parameter_id.'.title']; 
                      else : 
                        echo $parameter_array['title']; 
                      endif 
                ?>
              </h4>
              <p>
                <?php 
                  if(!empty($tpl_lang[$parameter_id.'.description'])) : 
                    echo $tpl_lang[$parameter_id.'.description']; 
                  else : 
                    echo $parameter_array['description']; 
                  endif 
                ?>
              </p>
      <?php if($parameter_array['type'] == 'radio') : //If radio button parameter type ?>
              <div class="btn-group" data-toggle="buttons">
        <?php foreach($parameter_array['values'] as $parameter_name => $parameter_value) : //List the different choices ?>
                <label class="btn btn-default sw-save-file <?php if($parameter_array['checked'] == $parameter_value) : echo 'active'; endif ?>">
                  <input  type="radio" 
                          name="<?php echo $parameter_id ?>" 
                          value="<?php echo $parameter_value ?>"
                          <?php if($parameter_array['checked'] == $parameter_value) : echo 'checked'; endif ?>>
                          <?php 
                            if(!empty($tpl_lang[$parameter_id.'.values.'.$parameter_name])) :
                              echo $tpl_lang[$parameter_id.'.values.'.$parameter_name];
                            else :
                              echo $parameter_name;
                            endif
                          ?>
                </label>
        <?php endforeach ?> 
              </div>
      <?php elseif($parameter_array['type'] == 'input') : //If input parameter type ?>
        <input type="text" name="<?php echo $parameter_id ?>" class="form-control" value="<?php echo $parameter_array['values'] ?>" placeholder="">

      <?php elseif($parameter_array['type'] == 'textarea') : //If textarea parameter type ?>
        <textarea name="<?php echo $parameter_id ?>" class="form-control" placeholder="" rows="4"><?php echo $parameter_array['values'] ?></textarea>

      <?php endif //Parameter type ?>
    <?php endforeach //Template parameter  ?> 
  <?php endif  ?>
<?php endforeach  ?> 
          <hr>
          <div class="text-right">
              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo ucfirst($lang['general.close']) ?></button>
              <button type="submit" class="btn btn-primary quick-save" title="<?php echo $lang['admin.parameters.save.template.parameters'] ?>"><span class="glyphicon glyphicon-floppy-disk"></span> <?php echo ucfirst($lang['general.save']) ?></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Delete Cache Modal -->
<div class="modal fade" id="delete-cache-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo $lang['admin.parameters.delete.cache.modal.title'] ?></h4>
      </div>
      <div class="modal-body">
        <p><?php echo $lang['admin.parameters.delete.cache.modal.text.confirmation'] ?></p>
        <div class="alert"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo ucfirst($lang['general.close']) ?></button>
        <button type="button" class="btn btn-danger" id="delete-cache-button"><span class="glyphicon glyphicon-trash"></span> <?php echo $lang['admin.parameters.delete.cache.modal.yes.button'] ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



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
