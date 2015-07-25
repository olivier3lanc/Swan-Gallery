<?php
	/**
	* INFINITE
	* Main file of the template
	* Swan Gallery
	* @version 0.9
	* @author Olivier Blanc http://www.egalise.com/swan-gallery/
	* @license MIT
	*/

	//include $script_path.'/engine/templates/'.$gallery_template.'/'.$tpl;
	if(isset($_POST['search'])) { 
		$search = $_POST['search'];
    	$files = scan($script_path . '/' . $gallery_folder, '', '', '', $search);
    	$page_title = $tpl_parameters['lang_search_results_for'].' '.$search;
    	$page_description = $tpl_parameters['lang_search_results_for'].' '.$search;
    }
    elseif(isset($_GET['tag'])) {
    	$tag = $_GET['tag'];
	    $files = scan($script_path . '/' . $gallery_folder, '', $tag, '', '');
	    $page_title = $tpl_parameters['lang_photos_tagged'].' '.$tag;
	    $page_description = $tpl_parameters['photos_tagged'].' '.$tag;
		/*
		* PAGINATION
		*/
		// If gallery parameters allows pagination
		if ($gallery_photos_per_page != 0) {
			
			//Number of photo in the array before paginate
			$files_count = count($files);

			//Cropping files array according to the pagination
			$files = paginate($files, '', '');

			//Total number of pages
			$total_pages = ceil($files_count / $gallery_photos_per_page);

			//Inform template that pagination if ok
			$pagination_enabled = true;
		}
    }
    elseif(isset($_GET['photo'])) {
	    $photo = $_GET['photo'];
	    //Check if file exists
	    if (file_exists($script_path . '/' . $gallery_folder . '/' . $photo)) {
	        //Return file name only
	        $files = scan($script_path . '/' . $gallery_folder, '', '', $photo, '');
	    //If file does not exist, redirect to home page  
	    } else {
	        header('Location: ./');
	    }
	    $page_title = $files[0]['ph_title'];
	    if(empty($files[0]['ph_description'])) {
	    	$page_description = $gallery_description;
	    }else{
	    	$page_description = $files[0]['ph_description'];
	    }
	    //Related files
        $related = related($files);
    }
    else{
    	//Files
		$files = scan($script_path . '/' . $gallery_folder, '', '', '', '');
		//Featured photos only on home page
		$featured = featured($script_path . '/' . $gallery_folder);
		$page_title = $gallery_title;
		$page_description = $gallery_description;
		/*
		* PAGINATION
		*/
		// If gallery parameters allows pagination
		if ($gallery_photos_per_page != 0) {
			
			//Number of photo in the array before paginate
			$files_count = count($files);

			//Cropping files array according to the pagination
			$files = paginate($files, '', '');

			//Total number of pages
			$total_pages = ceil($files_count / $gallery_photos_per_page);

			//Inform template that pagination if ok
			$pagination_enabled = true;
		}
    }

    //AJAX Pagination
    //If $load variable in URL, this is triggered from "Load more" button
    //Set up the next page URL that will be set in the custom html attribute "data-url" on the "Load more" button
    if(isset($_GET['load'])) { 
    	//If $page variable is set in URL, this is paginated context, set +1 to target page
    	if(isset($_GET['page'])){
    		$target_page = $_GET['page']+1;
    	//If no $page variable in URL, this is the first page, target page is 2
    	}else{
    		$target_page = 2;
    	}
    	//Set up the next page URL on a tag page
    	if(isset($_GET['tag'])) {
	    	$tag = $_GET['tag'];
	    	$final_url = './?load&tag='.$tag.'&page='.$target_page;
	    //Set up the next page URL on the home page
	    }else{
	    	$final_url = './?load&page='.$target_page;
	    }
    	//Include the photo loop and return: The main photo section is filled up with the new photo page
    	include 'engine/templates/'.$gallery_template.'/load.php';
    	return;
    //If the $load variable is not set, this is a first page, the "load more" button is reseted
    }else{
    	$target_page = 2;
    	if(isset($_GET['tag'])) {
	    	$tag = $_GET['tag'];
	    	$final_url = './?load&tag='.$tag.'&page='.$target_page;
	    }else{
	    	$final_url = './?load&page='.$target_page;
	    }
    }

	//If tag list ordering is set to alphabetical order
	if($gallery_tag_list_ordering == 'a-z'){
		$all_tags = tag_list($script_path . '/' . $gallery_folder, 1000, '', 'a-z');
	//If tag list ordering is set to reverse alphabetical order
	}elseif($gallery_tag_list_ordering == 'z-a'){
		$all_tags = tag_list($script_path . '/' . $gallery_folder, 1000, '', 'z-a');
	//If tag list ordering is set to popularity
	}elseif($gallery_tag_list_ordering == 'popularity'){
		$all_tags = tag_list($script_path . '/' . $gallery_folder, 1000, '');
	}
	
	//Avoids to display the toggle button "All tags / Popular tags" if the total number of tags is lower than the tag limit
	$total_number_of_tags = count($all_tags);
	if($total_number_of_tags>$gallery_tag_limit){
		$toggle_button_visibility = true;
	}else{
		$toggle_button_visibility = false;
	}

	//Initializing popular tags and least tags arrays. Avoids errors if Infinite template is loaded with no photos
	$popular_tags = array();
	$least_tags = array();

	if(is_array($all_tags)){
		//Most used tags
		$popular_tags = array_slice($all_tags, 0, $gallery_tag_limit);
		//Rest of tags	
		$least_tags = array_slice($all_tags, $gallery_tag_limit, '999');
	}
	
	//Email split
	if(!empty($gallery_email)){
		$secure_email = explode('@', $gallery_email);
	}
	
?><!DOCTYPE HTML>
<html>
	<head>
		<!-- Force latest IE rendering engine or ChromeFrame if installed -->
		<!--[if IE]>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<![endif]-->
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta charset="utf-8">
		<title><?php echo $page_title ?></title>
		<meta name="description" content="<?php echo $page_description ?>">
<?php if($gallery_credit_display == '1') : ?>
		<meta name="author" content="<?php echo $gallery_credit ?>">
<?php endif ?>

		<!-- Schema.org markup for Google+ --> 
		<meta itemprop="name" content="<?php echo $page_title ?>"> 
		<meta itemprop="description" content="<?php echo $page_description ?>"> 
<?php if(isset($_GET['photo'])) : ?>
		<meta itemprop="image" content="<?php echo $files[0]['ph_medium_url'] ?>">
<?php else : ?>
	<?php if(!empty($featured)) : ?>
		<meta itemprop="image" content="<?php echo $featured[0]['ph_medium_url'] ?>">
	<?php endif ?>
<?php endif ?>

		<!-- Open Graph data --> 
		<meta property="og:title" content="<?php echo $page_title ?>"> 
		<meta property="og:type" content="website"> 
<?php if(isset($_GET['photo'])) : ?>
		<meta property="og:url" content="<?php echo $script_url.'?photo='.$files[0]['ph_file'] ?>">
<?php elseif(isset($_GET['tag'])) : ?>
		<meta property="og:url" content="<?php echo $script_url.'?tag='.$_GET['tag'] ?>">
<?php else : ?>
		<meta property="og:url" content="<?php echo $script_url ?>">
	<?php if(!empty($featured)) : ?>
		<meta property="og:image" content="<?php echo $featured[0]['ph_medium_url'] ?>">
	<?php endif ?>
<?php endif ?>
		<meta property="og:description" content="<?php echo $page_description ?>">

		<meta name="generator" content="Swan Gallery">

		<!-- STYLES -->
		<link rel="stylesheet" href="engine/templates/<?php echo $gallery_template ?>/css/bootstrap.min.css">
		<link rel="stylesheet" href="engine/templates/<?php echo $gallery_template ?>/css/common.css">
		<link rel="stylesheet" href="engine/templates/<?php echo $gallery_template ?>/css/<?php echo $tpl_parameters['skin'] ?>.css">

		<!-- HEAD JS -->
		<script type="text/javascript" src="engine/templates/<?php echo $gallery_template ?>/js/head.min.js"></script>
		<script>
			var head_conf = {
				screens: [240, 320, 480, 640, 768, 800, 1024, 1200, 1280, 1440, 1680, 1920]
			};
			head.load(	'engine/templates/<?php echo $gallery_template ?>/js/jquery-1.11.0.min.js',
			        	'engine/templates/<?php echo $gallery_template ?>/js/jquery.lazyload.min.js',
			        	'engine/templates/<?php echo $gallery_template ?>/js/bootstrap.min.js',
			        	'engine/templates/<?php echo $gallery_template ?>/js/jquery.nicescroll.min.js',
			        	'engine/templates/<?php echo $gallery_template ?>/js/scrollspy.js',
			        	'engine/templates/<?php echo $gallery_template ?>/js/afterresize.min.js');
		</script>  

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->

		<?php echo $gallery_statistics_code ?>
	</head>
	<body id="home">
		<!-- NAVBAR -->
		<div id="infinite-navbar" class="hidden-lg">
			<a href="#" id="infinite-open-btn" class="closed" title="<?php echo $tpl_parameters['lang_open_menu'] ?>">
				<span class="icon-bars"></span>
			</a>
			<a href="./">
				<span class="pdleft15"><?php echo $gallery_title ?></span>
			</a>
		</div>

		<!-- CONTAINER -->
		<div class="container-fluid">
			<div class="row">
				<!-- SIDEBAR -->
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-2" id="infinite-sidebar">
					<header>
						<nav>
							<div id="infinite-sidebar-container">
								<p class="text-right pd15 hidden-lg"><a href="#" id="infinite-close-btn"><span class="icon-cross"></span></a></p>

								<div class="pd20">
<?php if(empty($_GET) && empty($_POST)) : ?>
									<h1 id="infinite-gallery-title" class="mgbot20" title="<?php echo $tpl_parameters['lang_return_home'] ?>"><a href="./"><?php echo $gallery_title ?></a></h1>
<?php else : ?>
									<p id="infinite-gallery-title" class="mgbot20" title="<?php echo $tpl_parameters['lang_return_home'] ?>"><a href="./"><?php echo $gallery_title ?></a></p>
<?php endif ?>
									
									<p id="infinite-baseline" class="mgbot20"><?php echo $gallery_description ?></p>

<?php if(!empty($gallery_facebook_page) || !empty($gallery_twitter) || !empty($gallery_email) || $gallery_rss_entries !== 'none') : ?>
									<p id="infinite-socials" class="mgbot20">
	<?php if(!empty($gallery_facebook_page)) : ?>
										<a href="<?php echo $gallery_facebook_page ?>" title="<?php echo $tpl_parameters['lang_facebook_page'] ?>"><span class="icon-facebook"></span></a>
	<?php endif ?>
	<?php if(!empty($gallery_twitter)) : ?>
										<a href="<?php echo $gallery_twitter ?>" title="<?php echo $tpl_parameters['lang_twitter'] ?>"><span class="icon-twitter"></span></a>
	<?php endif ?>
	<?php if(!empty($gallery_email)) : ?>
										<a href="#" class="btn-email" title="<?php echo $tpl_parameters['lang_email'] ?>"><span class="icon-mail"></span></a>
	<?php endif ?>
	<?php if($gallery_rss_entries !== 'none') : ?>
										<a href="./feed/" title="<?php echo $tpl_parameters['lang_rss'] ?>"><span class="icon-feed"></span></a>
	<?php endif ?>
									</p>
<?php endif ?>

									
									<form action="./" method="post">
										<div class="input-group">
											<input type="text" name="search" class="form-control" placeholder="<?php echo $tpl_parameters['lang_search_placeholder'] ?>">
											<span class="input-group-btn">
												<button class="btn btn-default" type="submit"><span class="icon-search"></span></button>
											</span>
										</div>
									</form>
								</div>

								<ul id="infinite-tags" class="">
<?php foreach($popular_tags as $popular_tag => $the_count) : ?>
									<li class="<?php if(!empty($_GET['tag']) && $_GET['tag'] == flatten($popular_tag)) : echo 'active'; endif ?>">
										<a 	href="./?tag=<?php echo flatten($popular_tag) ?>" 
											title="<?php echo $tpl_parameters['lang_photos_tagged'] ?> <?php echo $popular_tag ?>"
											rel="tag">
											<?php echo $popular_tag ?>
										</a>
									</li>
<?php endforeach ?>	
<?php foreach($least_tags as $least_tag => $the_count) : ?>
									<li class="infinite-least-tag <?php if(!empty($_GET['tag']) && $_GET['tag'] == flatten($least_tag)) : echo 'active'; endif ?>">
										<a 	href="./?tag=<?php echo flatten($least_tag) ?>" 
											title="<?php echo $tpl_parameters['lang_photos_tagged'] ?> <?php echo $least_tag ?>"
											rel="tag"
											class="">
											<?php echo $least_tag ?>
										</a>
									</li>
<?php endforeach ?>
<?php if(($gallery_tag_limit !== '999') && $toggle_button_visibility == true) : ?>
									<li><a href="#" id="infinite-toggle-tags-btn"><span class="icon-plus"></span> <?php echo $tpl_parameters['lang_all_tags'] ?></a></li>
<?php endif ?>
								</ul>
							</div>
						</nav>
					</header>
				</div><!-- ./SIDEBAR -->
				
				<!-- CONTENT -->
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-10" id="infinite-content">
<?php if(isset($_GET['tag'])) : ?>
					<h1 class="infinite-page-header"><span class="icon-tag"></span> <?php echo $_GET['tag'] ?></h1>
<?php elseif(isset($_POST['search'])) : ?>
					<h1 class="infinite-page-header"><span class="icon-search"></span> <?php echo $tpl_parameters['lang_search_results_for'] ?> "<strong><?php echo $_POST['search'] ?></strong>"</h1>
<?php endif ?>

					<!-- CAROUSEL -->
<?php if(!empty($featured)) : ?>
					<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
	<?php if(count($featured)>1) : ?>
						<!-- Indicators -->
						<ol class="carousel-indicators">
		<?php foreach($featured as $key => $data) : ?>
							<li   	data-target="#carousel-example-generic" 
									data-slide-to="<?php echo $key ?>" 
									class="<?php if($key == 0) : echo 'active'; endif ?>">
							</li>
		<?php endforeach ?>
						</ol>
	<?php endif ?>

						<!-- Wrapper for slides -->
						<div class="carousel-inner">
		<?php foreach($featured as $key => $featured_photo) : ?>
							<div class="item <?php if($key == 0) : echo 'active'; endif ?>">
								<a  href="./?photo=<?php echo $featured_photo['ph_file'] ?>">
									<img src="<?php echo $featured_photo['ph_file_url'] ?>" alt="<?php echo $featured_photo['ph_title'] ?>">
								</a>
								<div class="carousel-caption">
									<h3 class="hidden-xs"><?php echo $featured_photo['ph_title'] ?></h3>
								</div>
							</div>
		<?php endforeach ?>
						</div>

	<?php if(count($featured)>1) : ?>
						<!-- Controls -->
						<a 	class="left carousel-control" 
							href="#carousel-example-generic" 
							role="button" 
							data-slide="prev" 
							title="Previous">
							<span class="icon-chevron-left"></span>
						</a>
						<a 	class="right carousel-control" 
							href="#carousel-example-generic" 
							role="button" 
							data-slide="next" 
							title="Next">
							<span class="icon-chevron-right"></span>
						</a>
	<?php endif ?>
					</div>
<?php endif ?>
					<!-- ./CAROUSEL -->

					<!-- PHOTOS LOOP -->
					<section>
<?php include 'engine/templates/'.$gallery_template.'/load.php' ?>
					</section>
					<!-- ./PHOTOS LOOP -->

					<footer>
						<p class="pd30 text-center">&copy; <a href="#"><?php echo $gallery_credit ?></a> - <?php echo $powered_by ?> <a href="http://www.egalise.com/swan-gallery/" title="<?php echo $swan_gallery_tooltip ?>">Swan Gallery</a></p>

					</footer>
				</div><!-- ./#infinite-content ./CONTENT -->
			</div><!-- ./row -->
		</div><!-- ./CONTAINER -->

		<a href="#home" id="go-to-top" title="<?php echo $tpl_parameters['lang_return_top'] ?>"><span class="icon-chevron-up"></span></a>

		<script>
			/*
			* FUNCTIONS
			*/

			//Menu button for xs, sm and md 
			var menuBtn = function(){
				jQuery('#infinite-open-btn').on('click', function(e){
					e.preventDefault();
					if(jQuery(this).hasClass('closed')){
						jQuery('#infinite-sidebar-container').animate({top:'0%'});
						jQuery(this).removeClass('closed').addClass('opened');
					}else{
						jQuery('#infinite-sidebar-container').animate({height:'100%'});
						jQuery(this).removeClass('opened').addClass('closed');
					}
				});
				jQuery('#infinite-close-btn').on('click', function(e){
					e.preventDefault();
					jQuery('#infinite-sidebar-container').removeAttr('style');
					jQuery('#infinite-open-btn').removeClass('opened').addClass('closed');
				});
			}

			//Toggle display the sharing zone. Select when click on the input button
			var sharingZone = function(){
				jQuery('.btn-toggle-share').on('click', function(e){
					e.preventDefault();
					var currentPhotoContainer = jQuery(this).closest('.row');
					currentPhotoContainer.find('.infinite-sharing-zone').toggle();
				});
				jQuery('.btn-click-to-copy').on('click', function(){
					jQuery(this).closest('.input-group').find('input').select();
				});
				jQuery('.infinite-sharing-zone input').on('click', function(){
					jQuery(this).select();
				})
			}

			//Only when photo is in viewport, calculate img height, window height and compare. If img height > window height, then resize img to fit in the viewport
			var photoHeightAdjust = function(){
				//Activate scrollspy
				jQuery('.infinite-img-container').scrollSpy();
				jQuery('.infinite-img-container').on('scrollSpy:enter', function() {
				    var windowHeight = jQuery(window).height();
				    var theImg = jQuery(this).find('.infinite-img');
				    var imgOriginalWidth = theImg.attr('width');
				    var theImgRatio = theImg.attr('data-ratio');
				    var imgHeight = theImg.height();
				    var imgWidth = theImg.width();				    
					var theFinalHeight = windowHeight - 30;
				    var theFinalWidth = theImgRatio * theFinalHeight;
				    //Resize if img is not a panorama and is higher than screen height
				    if(imgHeight > windowHeight && !theImg.hasClass('panorama')) {
				    	theImg.animate({height:theFinalHeight+'px', width:theFinalWidth+'px'});
				    }else{
						//theImg.attr('style', '');
			    		//theImg.css({maxWidth:imgOriginalWidth+'px'});
				    }
				});
			}
			
			//Activated when window is resized, remove custom style attributes set by scrollspy and photoHeightAdjust on img to fit to the resized window
			var resetHeight = function(){
				jQuery('.infinite-img').each(function() {
				    var theImg = jQuery(this);
				    var imgOriginalWidth = jQuery(this).attr('width');
				    theImg.attr('style', '');
		    		theImg.css({maxWidth:imgOriginalWidth+'px'});
				});
			}

			//At the end of the tag list, display a link that when clicked, display all tags or main tags
			var toggleTags = function(){
				jQuery('#infinite-toggle-tags-btn').on('click', function(e){
					e.preventDefault();
					if(jQuery(this).hasClass('opened')){
						jQuery('.infinite-least-tag').hide();
						jQuery(this).removeClass('opened');
						jQuery(this).html('<span class="icon-plus"></span> <?php echo $tpl_parameters["lang_all_tags"] ?>');
						jQuery('#infinite-sidebar-container').getNiceScroll().resize();
					}else{
						jQuery('.infinite-least-tag').show();
						jQuery(this).addClass('opened');
						jQuery(this).html('<span class="icon-minus"></span> <?php echo $tpl_parameters["lang_less_tags"] ?>');
						jQuery('#infinite-sidebar-container').getNiceScroll().resize();
					}
					
				})
			}

			//Go to top
			var goToTop = function(){
				jQuery(window).scroll(function() {
					var pos = jQuery('body').scrollTop();
					if(pos>100){
						jQuery('#go-to-top').fadeIn();
					}else{
						jQuery('#go-to-top').fadeOut();
					}
				});

			}

			//AJAX Pagination
			//Get the AJAX URL target on the button "more photos" and execute loadMore()
			var clickMore = function(){
				jQuery('.load-more').on('click', function(e){
					e.preventDefault();
					var url = jQuery(this).attr('data-url');
					jQuery(this).animate({opacity:'0'});
					loadMore(url);
				});
			}

			//AJAX Pagination
			//Load more photos
			var loadMore = function(url) {
				jQuery.ajax({
					url: url,
					success: function (data) {
						// this is executed when ajax call finished well
						jQuery('#infinite-content section').append(data);
						jQuery('.lazy').lazyload();
						sharingZone();
						clickMore();
						if (head.desktop) {
						    //When photo in viewport, adjust the img height in window height
							photoHeightAdjust();
							//Assign functions after window resize
							jQuery(window).afterResize(function() {
						        resetHeight();
						    }, true, 800 );
						}
					},
					error: function (xhr, status, error) {
						// executed if something went wrong during call
						//if (xhr.status > 0) alert('got error: ' + status); // status 0 - when load is interrupted
						alert('Error');
					}
				});
			};

<?php if(isset($secure_email)) : ?>
			//Secure email display
			var displayEmail = function(){
				var firstPart = '<?php echo $secure_email[0] ?>';
				var secondPart = '<?php echo $secure_email[1] ?>';
				jQuery('#infinite-socials a.btn-email').on('click', function(e){
					e.preventDefault();
					alert(firstPart+'@'+secondPart);					
				})
			}
<?php endif ?>

			head.ready(function () {

				/*
				* DOM READY
				*/
				//Only if no touch device
				if (head.desktop) {
				    //When photo in viewport, adjust the img height in window height
					photoHeightAdjust();
					//Assign functions after window resize
					jQuery(window).afterResize(function() {
				        resetHeight();
				    }, true, 800 );
				}

				//Lazy load images
				jQuery('.lazy').lazyload({
					//effect: 'fadeIn'
				});

				clickMore();

				//Auto scrollbars on sidebar
				jQuery('#infinite-sidebar-container').niceScroll({ 
					horizrailenabled: false,
					cursorborder: "none" 
				});

				//All tags / Most popular tags toggle button
				toggleTags();
				
				//Menu button and sidebar behavior
				menuBtn();

				//Share zone 
				sharingZone();

				//Go to top
				goToTop();

<?php if(isset($secure_email)) : ?>
				displayEmail();
<?php endif ?>

				/*
				* ON LOAD
				*/
				jQuery(window).load(function(){
					
				});

				/*
				* ON RESIZE
				*/
				jQuery(window).resize(function(){
					goToTop();
				});
			});
		</script>
	</body>
</html>