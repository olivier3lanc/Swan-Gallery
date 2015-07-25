<?php
	/**
	* SWAN 2015
	* Main file of the template
	* Swan Gallery
	* @version 0.9
	* @author Olivier Blanc http://www.egalise.com/swan-gallery/
	* @license MIT
	*/

	//Include language parameter
	include $script_path.'/engine/templates/'.$gallery_template.'/languages/'.$tpl_parameters['language'].'.php';
	//If tag in URL
	if(isset($_GET['tag'])) { 
	    $tag = $_GET['tag'];
	    $files = scan($script_path . '/' . $gallery_folder, '', $tag, '', '');
		$page_title = $tpl_lang['front.tag'].' "'.$tag.'"';
		$page_description = $tpl_lang['front.tag.page.description'].' '.$tag;
		
		/*
		* PAGINATION
		*/
		// If gallery parameters allows pagination
		if ($gallery_photos_per_page != 0) {
			//Number of photo in the array before paginate
			$files_count = count($files);
			//Cropping files array according to the pagination
			$files = paginate($files, '', '');
			//Pagination engine
			$pages = pagination($files_count, '');
			//Count number of pages
			$pages_count = count($pages) + 1;
			//Inform template that pagination if ok
			$pagination_enabled = true;
		}

		//Associated template
		$tpl = 'tag.php';			

	//If search in POST
	}elseif(isset($_POST['search'])) { 
		$search = $_POST['search'];
    	$files = scan($script_path . '/' . $gallery_folder, '', '', '', $search);
		$page_title = count($files).' '.$tpl_lang['front.search.results.for'].' "'.$search.'"';
		//Associated template
		$tpl = 'home.php';

	//If photo page
	}elseif(isset($_GET['photo'])) { 
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
        //Previous and next photo
        $prev_next = previous_next($files[0]['ph_file']);
        //Previous photo
        $previous = $prev_next[0];
        //Next photo
        $next = $prev_next[1];
        //Related files
        $related = related($files);
        //Order photo tags by popularity
        $keywords = tag_popularity($files[0]['ph_keywords'], '');
		//Associated template
		$tpl = 'photo.php';

	//If tags page
	}elseif(isset($_GET['tags'])) { 
		$files = the_tags('', '');
		$page_title = $tpl_lang['front.tags'];
		$page_description = $tpl_lang['front.tags.page.description'];
		//Associated template
		$tpl = 'tags.php';

	//If all tags page
	}elseif(isset($_GET['all_tags'])) { 
	    $new_gallery_tag_limit = 999;
	    $files = the_tags('', $new_gallery_tag_limit);
		$page_title = $tpl_lang['front.all.tags'];
		//Associated template
		$tpl = 'tags.php';

	//Displaying the gallery when parameters are set to "tags" homepage type
	}elseif(isset($_GET['gallery']) && $gallery_homepage_type == 'tags') { 
		$files = scan($script_path . '/' . $gallery_folder, '', '', '', '');
		$page_title = $gallery_title;

		/*
		* PAGINATION
		*/
		// If gallery parameters allows pagination
		if ($gallery_photos_per_page != 0) {
			//Number of photo in the array before paginate
			$files_count = count($files);
			//Cropping files array according to the pagination
			$files = paginate($files, '');
			//Pagination engine
			$pages = pagination($files_count, '');
			//Count number of pages
			$pages_count = count($pages) + 1;
			//Inform template that pagination if ok
			$pagination_enabled = true;
		}

		//Associated template
		$tpl = 'home.php';

	//Land in the tags on homepage when parameters are set to "tags" homepage type
	}elseif(empty($_GET) && $gallery_homepage_type == 'tags') { 
		$files = the_tags('', '');
		$page_title = $gallery_title;
		//Featured photos
		$featured = featured($script_path . '/' . $gallery_folder);
		//Associated template
		$tpl = 'tags.php';

	//If none of the cases above, display the standard homepage, sorted and ordered according to gallery parameters
	}else{
		$files = scan($script_path . '/' . $gallery_folder, '', '', '', '');
		$page_title = $gallery_title;
		if(empty($_GET['page'])) {
			//Featured photos only on home page
			$featured = featured($script_path . '/' . $gallery_folder);
		}

		/*
		* PAGINATION
		*/
		// If gallery parameters allows pagination
		if ($gallery_photos_per_page != 0) {
			//Number of photo in the array before paginate
			$files_count = count($files);
			//Cropping files array according to the pagination
			$files = paginate($files, '');
			//Pagination engine
			$pages = pagination($files_count, '');
			//Count number of pages
			$pages_count = count($pages) + 1;
			//Inform template that pagination if ok
			$pagination_enabled = true;
		}

		//Associated template
		$tpl = 'home.php';
	}

	//All tags
	$all_tags = tag_list($script_path . '/' . $gallery_folder, 1000, '');
	//Number of tags
	$all_tags_count = count($all_tags);

	//If tag list ordering is set to alphabetical order
	if($gallery_tag_list_ordering == 'a-z'){
		$tags = tag_list($script_path . '/' . $gallery_folder, $gallery_tag_limit, '', 'a-z');
	//If tag list ordering is set to reverse alphabetical order
	}elseif($gallery_tag_list_ordering == 'z-a'){
		$tags = tag_list($script_path . '/' . $gallery_folder, $gallery_tag_limit, '', 'z-a');
	//If tag list ordering is set to popularity
	}elseif($gallery_tag_list_ordering == 'popularity'){
		$tags = tag_list($script_path . '/' . $gallery_folder, $gallery_tag_limit, '');
	}

	//Pagination text display (plain text "page [i] to [n]")
	if(isset($_GET['page'])) {
		$pages_text = $tpl_lang['front.page'].' '.$_GET['page'].' '.$tpl_lang['front.to.page'].' '.$pages_count;
	}else{
		$pages_text = '';
	}

	//Email split
	if(!empty($gallery_email)){
		$secure_email = explode('@', $gallery_email);
	}

	//Page type include
	include $script_path.'/engine/templates/'.$gallery_template.'/'.$tpl;


?>