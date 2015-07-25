<?php
/**
* FUNCTIONS
* Contains all necessary functions
* Swan Gallery
* @version 0.9.1
* @author Olivier Blanc http://www.egalise.com/swan-gallery
* @license MIT
*/


/*
* FUNCTION count_total()
* Returns the number of files in the selected gallery directory
* $data argument is optional
*/
function count_total($data) {
	//Variables recall
	global $script_path;
	global $gallery_folder;
	if(empty($data)) {
		//Counting the number of entries in the glob array containing all the jpg files paths
		$result=count(glob($script_path.'/'.$gallery_folder.'/*.[jJ][pP][gG]'));
		//Returning results
		return $result;
	}else{
		return count($data);
	}
}

/*
* FUNCTION date_formatted()
* Returns a well formatted string for the date
* $date argument is mandatory and must be formatted YYYYMMDD
* $separator argument is optional 
*/
function date_formatted($date,$separator) {
	$year=substr($date,0,4);
	$month=substr($date,4,2);
	$day=substr($date,6,2);
	if(isset($separator)) {
		return $year.$separator.$month.$separator.$day;
	}else {
		return $year.'.'.$month.'.'.$day;
	}
}


/*
* FUNCTION the_tags()
* Returns an array containing a photo for each tag according to the tag list ordering
* If a photo is assigned as favorite for the tag, this photo is used
* If several photos are assigned as favorite for the tag, the first photo, accordinto the gallery order, is used
* $files argument is optional (array)
* $tag_limit argument is optional (number)
*/
function the_tags($files, $tag_limit) {
	//Retrieving variables
	global $gallery_folder;
	global $script_path;
	global $gallery_tag_limit;
	global $gallery_tag_list_ordering;
	//If $tag_limit is  set
	if(!empty($tag_limit)) {
		$custom_tag_limit = $tag_limit;
	//If $tag_limit is not set, use default
	}else{
		$custom_tag_limit = $gallery_tag_limit;
	}
	$path = $script_path.'/'.$gallery_folder;
	//If $files is empty, build the files array
	if(empty($files)) {
		$files = scan($path, '', '', '', '');
	}


	//Build the tag list
	//If tag list ordering is set to alphabetical order
	if($gallery_tag_list_ordering == 'a-z'){
		$tag_list = tag_list($path, $custom_tag_limit, $files, 'a-z');
	//If tag list ordering is set to reverse alphabetical order
	}elseif($gallery_tag_list_ordering == 'z-a'){
		$tag_list = tag_list($path, $custom_tag_limit, $files, 'z-a');
	//If tag list ordering is set to popularity
	}elseif($gallery_tag_list_ordering == 'popularity'){
		$tag_list = tag_list($path, $custom_tag_limit, $files);
	}


	//Initializing an temp array
	$foo = array();
	//Building an temporary array with 'the tag' => array of all the files with that tag
	if(is_array($tag_list)) {
		foreach($tag_list as $tag => $nb) {
			foreach($files as $file) {
				if(is_array($file['ph_keywords'])) {
					//If the tag exists in the file metadata keywords array, insert it into the $foo temp array
					if(in_array($tag, $file['ph_keywords'])) {
						$foo[$tag][] = $file;
					}
				}
			}
		}
	}


	//Initializing the final array
	$final = array();
	foreach($foo as $files_with_that_tag => $value) {
		foreach($value as $subkey => $subvalue) {
			//If at least one photo of these with that tag is assigned as favorite, use it
			if(in_array($files_with_that_tag, $subvalue['ph_favorite_to_tags']) !== false) {
				$final[$files_with_that_tag] = $subvalue;
				break;
			//If no favorite to tag, use a random one
			}else{
				$tagged_nb = count($value);
				$random = rand(0, $tagged_nb-1);
				$final[$files_with_that_tag] = $foo[$files_with_that_tag][$random];
			}
		}
	}
	return $final;
}

/*
* FUNCTION list_directories()
* Returns an array with all the directories found in the Swan Gallery root directory (where it is installed)
* Filtered directories: 'engine', 'feed' and the current admin directory
*/
function list_directories() {
	//Retrieve global path
	global $script_path;
	//Get the name of the current admin directory
	$current_dir = basename(getcwd());
	//Array containing strings to remove
	$filter = array('engine', 'feed', $current_dir);
	//Scan all directories in the Swan Gallery directory
	$data = glob($script_path.'/*',GLOB_ONLYDIR);
	//Initialize final array
	$final = array();
	//Build the new array with all the directory names excluding the ones in $filter array
	foreach ($data as $key => $path) {
		$dirname = basename($path);
		if (in_array($dirname, $filter) !== true) {
			$final[] = $dirname;
		}
	}
	return $final;
}

/*
* FUNCTION scan()
* The main function of Swan Gallery
* Returns an array with all the jpg files informations found in the Swan Gallery photo directory ('folio' by default)
* $path argument is mandatory (the path to the photo directory)
* $path_filter argument is optional, it is used to remove unwanted strings from the paths (can be an array or a string)
* $tag argument is optional (string). If set, the function returns only the files with that tag
* $single argument is optional (string). If set, returns only the file set
* $search argument is optional (string). If set, returns files containing this string into title, description or keywords
*/
function scan($path, $path_filter, $tag, $single, $search) {
	//Variables recall
	global $ph_thumbs_dir;
	global $gallery_folder;
	global $gallery_sorting;
	global $gallery_ordering;
	global $ph_thumbs_dir;
	global $tpl_thumb_width;
	global $tpl_thumb_height;
	global $script_url;
	global $script_path;
	global $delimiter1;
	//If pagination get page number or page = 1 by default
	if(isset($_GET['page'])) { $filter_page = 'page='.$_GET['page']; }
	//URL filter
	if(!empty($path_filter)) {
		//If path filter not empty, remove $path_filter string from URL
		$script_url=str_replace($path_filter,'',$script_url);
	}else{
		//If not, remove all these stings from URL
		$split = explode('?',$script_url);
		$script_url = $split[0];
	}
	//Single file or list?
	//If single file scan
	if(!empty($single)) {
		$file_list_path = array($path.'/'.$single);
	//If folder scan
	}else{
		//Images list
		$file_list_path = glob($path.'/*.[jJ][pP][gG]');
	}
	//Image list without path
	$file_list = str_replace($path.'/','',$file_list_path);
	//Filter mask
	$filter_mask_filename_only_no_ext = array ('.jpg', '.JPG', $path.'/');
	$filter_mask_filename_only = array ($path.'/');
	//Creating the array containing all data
	$stack = array();
	//Scan IPTC for each file
	foreach ($file_list_path as $f) {
		//File name with no extension
		$file_no_ext = str_replace($filter_mask_filename_only_no_ext,'',$f);
		//Full file name with extension and case sensitive
		$file = str_replace($filter_mask_filename_only,'',$f);
		//File name to lower case (useful for ordering purpose)
		$ph_file_lowercase = strtolower($file_no_ext);
		//Retrieving IPTC data
		getimagesize($f,$info);
		$iptc = iptcparse($info["APP13"]);
		$ph_title = $iptc["2#005"][0];
		$ph_date = $iptc["2#055"][0];
		$ph_description = $iptc["2#120"][0];
		$ph_featured = $iptc["2#010"][0];
		//Keywords management
		$ph_saved_keywords = $iptc["2#040"][0];
		//Initializing favorite to tags array
		$ph_favorite_to_tags = array();
		//If 2#040 Special Instruction filled
		if(!empty($ph_saved_keywords)) {
			//If string contains 'SwanGData:' it means that this file was saved in Swan Gallery
			if(strpos($ph_saved_keywords,'SwanGData:') !== false){
				$ph_saved_keywords = str_replace('SwanGData:', '', $ph_saved_keywords);
				$ph_special_data = unserialize($ph_saved_keywords);
				
				if(!empty($ph_special_data['keywords'][0])){
					$ph_keywords_array = $ph_special_data['keywords'];
				}else{
					$ph_keywords_array = array();
				}
			//Backward compatibility
			}else{
				$ph_keywords_array = explode($delimiter1, $ph_saved_keywords);
			}
			//Retrieving favorites to tags
			if(is_array($ph_keywords_array)){
				foreach($ph_keywords_array as $the_tag) {
					if(strpos($the_tag, '|1') !== FALSE) {
						$ph_favorite_to_tags[] = str_replace('|1','',$the_tag);
					}
				}
			}
			//Remove favorites to tags marker
			$ph_keywords = str_replace('|1','',$ph_keywords_array);
		}else{
			//If Photo Editor keywords detected
			$ph_keywords = $iptc["2#025"];
		}
		//Thumbnail dimensions
		list($ph_thumb_width, $ph_thumb_height) = getimagesize($path.'/'.$ph_thumbs_dir.'/'.$file);
		//Medium dimensions
		list($ph_medium_width, $ph_medium_height) = getimagesize($path.'/medium/'.$file);
		//File dimensions
		list($ph_file_width, $ph_file_height) = getimagesize($path.'/'.$file);
		//File ratio
		$ph_file_ratio = $ph_file_width / $ph_file_height;
		//If IPTC title set, use it
		if(!empty($ph_title)){
			//UTF 8 conversion safe
			$ph_title = ConvertToUTF8($ph_title);
		}else{
			//If IPTC title empty, use file name with no ext as title
			$ph_title = ucfirst($file_no_ext);
		}
		//Assign an ID
		$ph_id = flatten($file_no_ext);
		//Making simple tags string for indexing and searching purpose
		if(is_array($ph_keywords)){
			$ph_keywords_flat = implode('_',$ph_keywords);
		}else{
			$ph_keywords_flat = $ph_saved_keywords;
		}
		
		//Flatten flat keywords string
		$ph_keywords_flat = flatten($ph_keywords_flat);
		//Make convenient tags string to search into
		$ph_keywords_flat_tags = '|'.str_replace('_','|',$ph_keywords_flat).'|';
		//Time stamp of the jpg file created on the server. Format year.month.day hour:minute:second -> example 2014.01.01 14:28:59
		$ph_date_created=date ("Y.m.d G:i:s", filemtime($path.'/'.$file));
		
		//Read EXIF data for GPS
		$the_exif = exif_read_data($path.'/'.$file);
		//Read EXIF data for file
		$ph_exif = exif_read_data($path.'/'.$file, COMPUTED, 1);

		$ph_exif_full = $ph_exif;
		//Adjust exposure time display
		$ph_exif_exposure_temp = $ph_exif['EXIF']['ExposureTime'];
		$ph_exif_exposure_temp = explode('/',$ph_exif_exposure_temp);
		//$ph_exif_exposure = $ph_exif_exposure_temp[0].'---'.$ph_exif_exposure_temp[1];
		if($ph_exif_exposure_temp[0] == 1) {
			$ph_exif_exposure = '1/'.$ph_exif_exposure_temp[1];
		}else{
			$ph_exif_exposure = $ph_exif_exposure_temp[0];
		}
		//Adjust f number display
		$ph_exif_fnumber_temp = $ph_exif['EXIF']['FNumber'];
		$ph_exif_fnumber_temp = explode('/',$ph_exif_fnumber_temp);
		if($ph_exif_fnumber_temp[1] == 10){
			$ph_exif_fnumber = $ph_exif_fnumber_temp[0] / 10;
		}else{
			$ph_exif_fnumber = $ph_exif_fnumber_temp[0];
		}
		
		//Adjust focal length display
		$ph_exif_focal_temp = $ph_exif['EXIF']['FocalLength'];
		$ph_exif_focal_temp = explode('/',$ph_exif_focal_temp);
		if($ph_exif_focal_temp[1] > 0){
			$ph_exif_focal = $ph_exif_focal_temp[0] / $ph_exif_focal_temp[1];
		}else{
			$ph_exif_focal = $ph_exif_focal_temp[0];
		}
		

		//GPS Data
		if(array_key_exists('GPSLongitude', $the_exif) && array_key_exists('GPSLatitude', $the_exif)) {
		    $ph_gps_lng = getGps($the_exif["GPSLongitude"], $the_exif['GPSLongitudeRef']);
		    $ph_gps_lat = getGps($the_exif["GPSLatitude"], $the_exif['GPSLatitudeRef']);
		}

		//Final EXIF array
		$ph_file_exif = array(
									'DateTimeOriginal' => $ph_exif['EXIF']['DateTimeOriginal'],
									'ExposureTime' => $ph_exif_exposure,
									'FNumber' => $ph_exif_fnumber,
									'ISOSpeedRatings' => $ph_exif['EXIF']['ISOSpeedRatings'],
									'FocalLength' => $ph_exif_focal,
									'Model' => $ph_exif['IFD0']['Model'],
									'Lens' => $ph_exif['EXIF']['UndefinedTag:0xA434']
								);
		//Remove empty fields from the EXIF array
		$ph_exif = array();
		foreach($ph_file_exif as $exif_key => $exif_data) {
			if(!empty($exif_data)) {
				$ph_exif[$exif_key] = $exif_data;
			}
		}
		//Add data into the array
		$data = array(	'ph_date' => $ph_date,
						'ph_date_created' => $ph_date_created,
						'ph_id' => $ph_id,
						'ph_title' => $ph_title,
						'ph_description' => $ph_description,
						'ph_featured' => $ph_featured,
						'ph_file' => $file,
						'ph_file_no_ext' => $file_no_ext,
						'ph_file_lowercase' => $ph_file_lowercase,
						'ph_thumb_url' => $script_url.$gallery_folder.'/'.$ph_thumbs_dir.'/'.$file,
						'ph_medium_url' => $script_url.$gallery_folder.'/medium/'.$file,
						'ph_file_url' => $script_url.$gallery_folder.'/'.$file,
						'ph_keywords' => $ph_keywords,
						'ph_favorite_to_tags' => $ph_favorite_to_tags,
						'ph_keywords_flat' => $ph_keywords_flat,
						'ph_keywords_flat_tags' => $ph_keywords_flat_tags,
						'ph_gps_lng' => $ph_gps_lng,
						'ph_gps_lat' => $ph_gps_lat,
						'ph_thumb_width' => $ph_thumb_width,
						'ph_thumb_height' => $ph_thumb_height,
						'ph_medium_width' => $ph_medium_width,
						'ph_medium_height' => $ph_medium_height,
						'ph_file_width' => $ph_file_width,
						'ph_file_height' => $ph_file_height,
						'ph_file_ratio' => $ph_file_ratio,
						'ph_exif' => $ph_exif,
						'ph_exif_full' => $ph_exif_full,
						'ph_iptc_full' => $iptc
					);
		//Add $data to the $stack array
		//array_push($stack, $data);
		$stack[$file]=$data;
	}
	//Sorting final array with all data. By default, is sorted according to the very first array key ($ph_date in this case)
	$stack=sort_multi($stack, $gallery_sorting, $gallery_ordering);
	
	//If search query is entered in search form or special query in $search argument of scan function
	if(!empty($search)) {
		
		if($search=='featured'){
			//Create new array
			$result=array();
			//Insert every photo into which $ph_featured value = 1 into $result array
			foreach($stack as $key) {
				if ($key['ph_featured'] == '1') {
					$result[$key['ph_file']]=$key;
				}
			}
			return $result;
		
		//If $search argument is set to 'favorite_to_tags', we look for photos where $ph_favorite_to_tags is not empty
		}elseif($search=='favorites_to_tags'){
			//Create new array
			$result=array();
			//Insert every photo into which $favorite_to_tags is not empty
			foreach($stack as $key) {
				if (!empty($key['ph_favorite_to_tags'])) {
					$result[$key['ph_file']]=$key;
				}
			}
			return $result;
		
		//If not a related search nor featured search, it is a search form query
		}else{
			//Search query term
			$original_search = $search;
			//Lowercase search query term
			$flattened_search = strtolower($search);
			//Create new array
			$result=array();
			//Insert every photo containing this search string into $result array
			foreach($stack as $key) {
				//If search string found in ph_keywords_flat, ph_title or ph_description strings
				$complete_search_string = ' '.str_replace('_', ' ', strtolower($key['ph_title'])).' '.$key['ph_description'].' '.$key['ph_keywords_flat'];
				//If the query is detected into the search string containing: photo title or description or tags
				if (strpos($complete_search_string, $flattened_search) >0 || in_array($original_search, $key)) {
					//Insert the photo array in the result array
					$result[$key['ph_file']]=$key;
				}
			}
			return $result;
		}
	}
	//If tag only return array with photos containing tag
	if(isset($tag) && !empty($tag)){
		//If tag=untagged (list only photos with no tag)
		if($tag=='no_tag') {
		//Create new array
		$result=array();
		//Insert every photo not tagged into $result array
		foreach($stack as $key) {
			//If double pipe || found in ph_keywords_flat string, it means there is no tag
			if (empty($key['ph_keywords']) !== false) {
				$result[$key['ph_file']]=$key;
			}
		}
		//Otherwise it is an existing/true tag
		}else{
		//Create new array
		$result=array();
		//Insert every photo containing this keywords into $result array
		foreach($stack as $key) {
			//If tag found in ph_keywords_flat string
			if (strpos($key['ph_keywords_flat_tags'],'|'.$tag.'|') !== false) {
				$result[$key['ph_file']]=$key;
			}
		}
		}
		return $result;
	}else{
		return $stack;
	}
}



/*
* FUNCTION previous_next()
* According to the $file in input, returns an array with the next file name and the previous file name 
* based on the gallery sorting and ordering parameters
* $file argument is mandatory (string). File name
*/
function previous_next($file) {
	//Retrieve variables
	global $script_path;
	global $gallery_folder;
	$foo = array();
	$data = scan($script_path.'/'.$gallery_folder, '', '', '', '');
	foreach($data as $key => $value) {
		$foo[] = $value['ph_file'];
	}
	$file_key = array_search($file, $foo);
	$previous_key = $file_key - 1;
	$next_key = $file_key + 1;
	$previous_file = $foo[$previous_key];
	$next_file = $foo[$next_key];
	$previous_next = array($previous_file, $next_file);
	return $previous_next;
}

/*
* FUNCTION featured()
* A simple function to filter featured photos. Related to scan() function, returns an array with only featured photos
* $data argument is optional (string). $data is the path to the current gallery directory
*/
function featured($data) {
	//Retrieve variables
	global $script_path;
	global $gallery_folder;
	if(empty($data)){
		$foo=scan($script_path.'/'.$gallery_folder,'','','','featured');
	}else{
		$foo=scan($data,'','','','featured');
	}
	$featured=array();
	foreach($foo as $featured_photo) {
		$featured[]=$featured_photo;
	}
	return $featured;
}

/*
* FUNCTION related()
* Related to scan(), returns an array with with files related to a single file
* $data argument is mandatory (array). $data is the array of one file
*/
function related($data) {
	//Retrieve variables
	global $script_path;
	global $gallery_folder;
	global $gallery_related;
	global $gallery_related_limit;
	//Scan the entire gallery folder, $stack is sorted and ordered according to gallery parameters
	$stack = scan($script_path.'/'.$gallery_folder, '', '', '', '');
	//The $stack array has automatic keys, we replace each key by the file name
	$stack_with_id = array();
	foreach($stack as $key) {
		$stack_with_id[$key['ph_file']] = $key;
	}
	//Get title and keywords data from the $data array
	$title_original = $data[0]['ph_title'];
	$file_name = $data[0]['ph_file'];
	$keywords = $data[0]['ph_keywords'];
	//Get the number of keywords into this photo
	$nb_of_keywords = count($keywords);
	//Remove this photo from the $stack array to avoid seeing it in the final results
	unset($stack_with_id[$file_name]);
	//Use space as separator to build an array from the title
	$title = explode(' ',$title_original);
	$title_keywords = array();
	//Keeping only words longer than 4 characters in the title
	foreach($title as $foo) {
		if(strlen($foo) > 4) {
			$title_keywords[] = $foo;
		}
	}
	//Getting the array containing occurrences of at least one of each keyword based on title
	$title_result = array();
	foreach($stack_with_id as $key => $value) {
		foreach($title_keywords as $title_key => $title_sample) {
			if(strpos($value['ph_title'], $title_sample) !== false) {
				$title_result[$value['ph_file']] = $value;
			}
		}
	}
	//Getting tag list
	$tags_array = tag_list($script_path.'/'.$gallery_folder,1000,$stack_with_id);
	//Ordering this photo keywords by popularity
	$ph_keywords_ordered = tag_popularity($keywords, $tags_array);
	//Re ordering the photo keywords from the less poppular to the most popular
	array_multisort($ph_keywords_ordered, SORT_DESC);
	//Build an array with all the photos for each tag
	$keywords_result = array();
	$with_that_tag = array();
	foreach($ph_keywords_ordered as $tag => $value) {
		$tag = flatten($tag);
		$with_that_tag[] = scan($script_path.'/'.$gallery_folder,'',$tag, '', '');
	}
	//Reorganize the array with file name as key
	//Remove duplicates
	foreach($with_that_tag as $key => $data) {
		foreach($data as $sub_data) {
			$keywords_result[$sub_data['ph_file']] = $sub_data;
		}
	}
	//Remove the photo from the results
	unset($keywords_result[$file_name]);
	unset($title_result[$file_name]);
	//Apply the final array to gallery parameters
	$final_result = array();
	if($gallery_related == 'titles') {
		$final_result = $title_result;
	}elseif($gallery_related == 'tags') {
		$final_result = $keywords_result;
	}else{
		// If 'both' case is set, merge results with title result first
		$final_result = array_merge($title_result, $keywords_result);
	}
	//Return the first $gallery_related_limit results
	return array_slice($final_result, 0, $gallery_related_limit);
}

/*
* FUNCTION paginate()
* Returns a truncated $files array according to the page requested
* $files argument is mandatory (array). $files is the complete photos files array from the scan() function
* $custom_photos_per_page argument is optional (number). Number or files to return
*/
function paginate($files,$custom_photos_per_page) {
	global $gallery_photos_per_page;
	if(!empty($custom_photos_per_page)) {
		$photos_per_page = $custom_photos_per_page;
	}else{
		$photos_per_page = $gallery_photos_per_page;
	}
	if(isset($_GET['page'])) {
		$page = $_GET['page'];
	}else{
		$page = 1;
	}
	$paginated_files = array();
	$paginated_files = array_slice($files,($page-1)*$photos_per_page,$photos_per_page);
	return $paginated_files;
}

/*
* FUNCTION pagination()
* Returns an array with all the necessary strings for pagination links
* $nb argument is mandatory (number). $nb is the total number of files
* $custom_photos_per_page argument is mandatory (number). Number or photos per page
*/
function pagination($nb,$custom_photos_per_page) {
	global $gallery_photos_per_page;
	if(!empty($custom_photos_per_page)) {
		$photos_per_page = $custom_photos_per_page;
	}else{
		$photos_per_page = $gallery_photos_per_page;
	}
	$total_pages = ceil($nb / $photos_per_page);
	if(isset($_GET['page'])) {
		$page = $_GET['page'];
		$next_page = $_GET['page']+1;
		$previous_page = $_GET['page']-1;
	}else{
		$page = 1;
		$next_page = '';
		$previous_page = '';
	}
	if(isset($_GET['tag'])) {
		$url = '?tag='.$_GET['tag'].'&page=';
		$url_next = '?tag='.$_GET['tag'].'&page='.$next_page;
		$url_previous = '?tag='.$_GET['tag'].'&page='.$previous_page;
	}else{
		$url = '?page=';
		$url_next = '&page='.$next_page;
		$url_previous = '&page='.$previous_page;
	}
	$result = array();
	for ($i = 2; $i <= $total_pages; $i++) {
		if($i == $page) {
			$css_class = 'active';
		}else{
			$css_class = '';
		}
		$result[$i] = array(	
								'page' => $i, 
								'url' => $url.$i,
								'class' => $css_class,
								'next' => $url_next,
								'previous' => $url_previous
							);
	}
	return $result;
}

/*
* FUNCTION ConvertToUTF8()
* Utility function that converts hexadecimal characters to UTF8
* $data argument is mandatory (string). String to convert
*/
function ConvertToUTF8($data) {
	//Convert all strings to UTF8
		$unwanted_array = array( 	'&#x20;'=>' ', '&#x21;'=>'!', '&#x22;'=>'"', '&#x23;'=>'#', '&#x24;'=>'$', '&#x25;'=>'%', '&#x26;'=>'&', '&#x27;'=>'\'', '&#x28;'=>'(',
								'&#x29;'=>')', '&#x2a;'=>'*', '&#x2b;'=>'+', '&#x2c;'=>',', '&#x2d;'=>'-', '&#x2e;'=>'.', '&#x2f;'=>'/', '&#x30;'=>'0', '&#x31;'=>'1',
								'&#x32;'=>'2', '&#x33;'=>'3', '&#x34;'=>'4', '&#x35;'=>'5', '&#x36;'=>'6', '&#x37;'=>'7', '&#x38;'=>'8', '&#x39;'=>'9', '&#x3a;'=>':',
								'&#x3b;'=>';', '&#x3d;'=>'=', '&#x3f;'=>'?', '&#x40;'=>'@', '&#x41;'=>'A', '&#x42;'=>'B', '&#x43;'=>'C', '&#x44;'=>'D', '&#x45;'=>'E',
								'&#x46;'=>'F', '&#x47;'=>'G', '&#x48;'=>'H', '&#x49;'=>'I', '&#x4a;'=>'J', '&#x4b;'=>'K', '&#x4c;'=>'L', '&#x4d;'=>'M', '&#x4e;'=>'N',
								'&#x4f;'=>'O', '&#x50;'=>'P', '&#x51;'=>'Q', '&#x52;'=>'R', '&#x53;'=>'S', '&#x54;'=>'T', '&#x55;'=>'U', '&#x56;'=>'V', '&#x57;'=>'W',
								'&#x58;'=>'X', '&#x59;'=>'Y', '&#x5a;'=>'Z', '&#x5b;'=>'[', '&#x5d;'=>']', '&#x5e;'=>'^', '&#x5f;'=>'_', '&#x60;'=>'`', '&#x61;'=>'a',
								'&#x62;'=>'b', '&#x63;'=>'c', '&#x64;'=>'d', '&#x65;'=>'e', '&#x66;'=>'f', '&#x67;'=>'g', '&#x68;'=>'h', '&#x69;'=>'i', '&#x6a;'=>'j',
								'&#x6b;'=>'k', '&#x6c;'=>'l', '&#x6d;'=>'m', '&#x6e;'=>'n', '&#x6f;'=>'o', '&#x70;'=>'p', '&#x71;'=>'q', '&#x72;'=>'r', '&#x73;'=>'s',
								'&#x74;'=>'t', '&#x75;'=>'u', '&#x76;'=>'v', '&#x77;'=>'w', '&#x78;'=>'x', '&#x79;'=>'y', '&#x7a;'=>'z', '&#x7b;'=>'{', '&#x7c;'=>'|',
								'&#x7d;'=>'}', '&#x7e;'=>'~', '&#x7f;'=>'', '&#x80;'=>'€', '&#x81;'=>'', '&#x82;'=>'‚', '&#x83;'=>'ƒ', '&#x84;'=>'„', '&#x85;'=>'…',
								'&#x86;'=>'†', '&#x87;'=>'‡', '&#x88;'=>'ˆ', '&#x89;'=>'‰', '&#x8a;'=>'Š', '&#x8b;'=>'‹', '&#x8c;'=>'Œ', '&#x8d;'=>'', '&#x8e;'=>'Ž',
								'&#x8f;'=>'', '&#x90;'=>'', '&#x91;'=>'‘', '&#x92;'=>'’', '&#x93;'=>'“', '&#x94;'=>'”', '&#x95;'=>'•', '&#x96;'=>'–', '&#x97;'=>'—',
								'&#x98;'=>'˜', '&#x99;'=>'™', '&#x9a;'=>'š', '&#x9b;'=>'›', '&#x9c;'=>'œ', '&#x9d;'=>'', '&#x9e;'=>'ž', '&#x9f;'=>'Ÿ', '&#xa1;'=>'¡',
								'&#xa2;'=>'¢', '&#xa3;'=>'£', '&#xa4;'=>'¤', '&#xa5;'=>'¥', '&#xa6;'=>'¦', '&#xa7;'=>'§', '&#xa8;'=>'¨', '&#xa9;'=>'©', '&#xaa;'=>'ª',
								'&#xab;'=>'«', '&#xac;'=>'¬', '&#xad;'=>'­', '&#xae;'=>'®', '&#xaf;'=>'¯', '&#xb0;'=>'°', '&#xb1;'=>'±', '&#xb2;'=>'²', '&#xb3;'=>'³',
								'&#xb4;'=>'´', '&#xb5;'=>'µ', '&#xb6;'=>'¶', '&#xb7;'=>'·', '&#xb8;'=>'¸', '&#xb9;'=>'¹', '&#xba;'=>'º', '&#xbb;'=>'»', '&#xbc;'=>'¼',
								'&#xbd;'=>'½', '&#xbe;'=>'¾', '&#xbf;'=>'¿', '&#xc0;'=>'À', '&#xc1;'=>'Á', '&#xc2;'=>'Â', '&#xc3;'=>'Ã', '&#xc4;'=>'Ä', '&#xc5;'=>'Å',
								'&#xc6;'=>'Æ', '&#xc7;'=>'Ç', '&#xc8;'=>'È', '&#xc9;'=>'É', '&#xca;'=>'Ê', '&#xcb;'=>'Ë', '&#xcc;'=>'Ì', '&#xcd;'=>'Í', '&#xce;'=>'Î',
								'&#xcf;'=>'Ï', '&#xd0;'=>'Ð', '&#xd1;'=>'Ñ', '&#xd2;'=>'Ò', '&#xd3;'=>'Ó', '&#xd4;'=>'Ô', '&#xd5;'=>'Õ', '&#xd6;'=>'Ö', '&#xd7;'=>'×',
								'&#xd8;'=>'Ø', '&#xd9;'=>'Ù', '&#xda;'=>'Ú', '&#xdb;'=>'Û', '&#xdc;'=>'Ü', '&#xdd;'=>'Ý', '&#xde;'=>'Þ', '&#xdf;'=>'ß', '&#xe0;'=>'à',
								'&#xe1;'=>'á', '&#xe2;'=>'â', '&#xe3;'=>'ã', '&#xe4;'=>'ä', '&#xe5;'=>'å', '&#xe6;'=>'æ', '&#xe7;'=>'ç', '&#xe8;'=>'è', '&#xe9;'=>'é',
								'&#xea;'=>'ê', '&#xeb;'=>'ë', '&#xec;'=>'ì', '&#xed;'=>'í', '&#xee;'=>'î', '&#xef;'=>'ï', '&#xf0;'=>'ð', '&#xf1;'=>'ñ', '&#xf2;'=>'ò',
								'&#xf3;'=>'ó', '&#xf4;'=>'ô', '&#xf5;'=>'õ', '&#xf6;'=>'ö', '&#xf7;'=>'÷', '&#xf8;'=>'ø', '&#xf9;'=>'ù', '&#xfa;'=>'ú', '&#xfb;'=>'û',
								'&#xfc;'=>'ü', '&#xfd;'=>'ý', '&#xfe;'=>'þ', '&#xff;'=>'ÿ' );
	$data = strtr($data, $unwanted_array);
	return $data;
}

/*
* FUNCTION flatten()
* Utility function that converts unwanted characters to slug or ID
* $data argument is mandatory (string). String to convert
*/
function flatten ($data) {
	//Make an ID from string
	$unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
								'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
								'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
								'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
								'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', ' '=>'-', '&#xe9;'=>'e', '&#xe8;'=>'e', '\''=>'',
								'&#xe0;'=>'a', '&#xe2;'=>'a', '&#xe7;'=>'c', '&#xea;'=>'e', '&#xeb;'=>'e', '&#xee;'=>'i', '&#xef;'=>'i', '&#xf9;'=>'u', '&#xfb;'=>'u',
								'&#x27;'=>'', '('=>'_', ')'=>'_', '['=>'_', ']'=>'_', '{'=>'_', '}'=>'_', '.'=>'_', '@'=>'_');
								$data = strtr($data, $unwanted_array);
								return strtolower($data);
}

/*
* FUNCTION iptc_make_tag()
* Prepare data to be written into the file
* iptc_make_tag() function by Thies C. Arntzen
*/
function iptc_make_tag($rec, $data, $value) {
	$length = strlen($value);
	$retval = chr(0x1C) . chr($rec) . chr($data);
	if($length < 0x8000)
	{
	$retval .= chr($length >> 8) .  chr($length & 0xFF);
	}
	else
	{
	$retval .= chr(0x80) .
	chr(0x04) .
	chr(($length >> 24) & 0xFF) .
	chr(($length >> 16) & 0xFF) .
	chr(($length >> 8) & 0xFF) .
	chr($length & 0xFF);
	}
	return $retval . $value;
}

/*
* FUNCTION iptc_write()
* Write data to the jpg file
* $path argument is mandatory (string). Path to the file to write
* $ph_stack argument is mandatory (array). Array data to write 
*/
function iptc_write($path,$ph_stack) {
	// Set the IPTC tags
	$iptc = array(
		'2#005' => $ph_stack['title'],
		'2#120' => $ph_stack['description'],
		'2#055' => $ph_stack['date'],
		'2#040' => $ph_stack['specials_instructions'],
		'2#010' => $ph_stack['featured']
	);
	// Convert the IPTC tags into binary code
	$data = '';
	
	foreach($iptc as $tag => $string)
	{
		$tag = substr($tag, 2);
		$data .= iptc_make_tag(2, $tag, $string);
	}
	
	// Embed the IPTC data
	$content = iptcembed($data, $path);
	
	// Write the new image data out to the file.
	$fp = fopen($path, "wb");
	fwrite($fp, $content);
	fclose($fp);
	return $content;
}

/*
* FUNCTION scan_tpl()
* Returns an array with the the available templates detected
*/
function scan_tpl() {
//Variables recall
global $script_path;
global $script_url;
global $gallery_admin_language;
global $tpl_parameters;
global $gallery_template;
//Get current dir name
$current_dir=str_replace(dirname(getcwd()),'',getcwd());
//List all templates folders names in the engine/templates directory
$tpl_folder_list = str_replace($script_path.'/engine/templates/','',glob($script_path.'/engine/templates/*',GLOB_ONLYDIR));
foreach($tpl_folder_list as $tpl_dir){
	//Get template parameters if config file exists and if yes, include it
	if(file_exists($script_path.'/engine/templates/'.$tpl_dir.'/config.php')){
		//Reset the $tpl_parameters_tree (important)
		$tpl_parameters_tree = array();
		include ($script_path.'/engine/templates/'.$tpl_dir.'/config.php');
		//All these variables are mandatory to be recognized as a valid template
		if(		isset($tpl_thumb_width) &&
				isset($tpl_thumb_height) &&
				isset($tpl_medium_width) &&
				isset($tpl_medium_height) &&
				isset($tpl_thumb_crop) &&
				isset($tpl_title) &&
				isset($tpl_description) &&
				isset($tpl_author) &&
				isset($tpl_author_url) &&
				isset($tpl_date)) {
				//If all variables ok, check if optional template language file exists and include it.
				//Language is based on the gallery language
				if(file_exists($script_path.'/engine/templates/'.$tpl_dir.'/languages/'.$gallery_admin_language.'.php')){
					include ($script_path.'/engine/templates/'.$tpl_dir.'/languages/'.$gallery_admin_language.'.php');
				}
				//Template title: If custom language set, replace by the translation
				if(!empty($tpl_lang['tpl.title'])) {
					$tpl_title = $tpl_lang['tpl.title'];
				}
				//Template description: If custom language set, replace by the translation
				if(!empty($tpl_lang['tpl.description'])) {
					$tpl_description = $tpl_lang['tpl.description'];
				}
				//If template parameters are set AND the data.jpg exists in the template directory/data
				if(!empty($tpl_parameters_tree) && file_exists($script_path.'/engine/templates/'.$tpl_dir.'/data/data.jpg')) {
					//Initialize the new optional template parameters array
					$final_parameters = array();
					//Filling the template parameters array
					foreach($tpl_parameters_tree as $parameter_id => $parameter_array) {
						//Title of the parameter: If custom language set, replace by the translation
						if(!empty($tpl_lang[$parameter_id.'.title'])) {
							$final_parameters[$parameter_id]['title'] = $tpl_lang[$parameter_id.'.title'];
						}else{
							$final_parameters[$parameter_id]['title'] = $parameter_array['title'];
						}
						//Description of the parameter: If custom language set, replace by the translation
						if(!empty($tpl_lang[$parameter_id.'.description'])) {
							$final_parameters[$parameter_id]['description'] = $tpl_lang[$parameter_id.'.description'];
						}else{
							$final_parameters[$parameter_id]['description'] = $parameter_array['description'];
						}
						//Type of template parameter
						$final_parameters[$parameter_id]['type'] = $parameter_array['type'];
						
						//If radio button form type
						if($parameter_array['type'] == 'radio') {
							//Filling an array with all the texts and values for this radio button parameter: If custom language set, replace field text by the translation
							foreach($parameter_array['values'] as $param_name => $param_value) {
								if(!empty($tpl_lang[$parameter_id.'.values.'.$param_name])) {
									$final_parameters[$parameter_id]['values'][$tpl_lang[$parameter_id.'.values.'.$param_name]] = $param_value;
								}else{
									$final_parameters[$parameter_id]['values'][$param_name] = $param_value;
								}
							}
							$final_parameters[$parameter_id]['checked'] = $tpl_parameters[$parameter_id];
						//If input form type
						}elseif($parameter_array['type'] == 'input') {
							if(!empty($tpl_parameters[$parameter_id])) {
								$final_parameters[$parameter_id]['values'] = $tpl_parameters[$parameter_id];
							}else{
								$final_parameters[$parameter_id]['values'] = $parameter_array['values'];
							}
							
						//If textarea form type
						}elseif($parameter_array['type'] == 'textarea') {
							if(!empty($tpl_parameters[$parameter_id])) {
								$final_parameters[$parameter_id]['values'] = $tpl_parameters[$parameter_id];
							}else{
								$final_parameters[$parameter_id]['values'] = $parameter_array['values'];
							}
						}
					}
				}else{
					$final_parameters = 'none';
				}
				//Check if this template is currently used by the gallery
				if($gallery_template == $tpl_dir) {
					$is_current = 'yes';
				}else{
					$is_current = 'no';
				}
				//Create template preview image URL
				$tpl_preview_url = str_replace($current_dir.'/'.basename($_SERVER['PHP_SELF']),'',$script_url).'/engine/templates/'.$tpl_dir.'/preview.jpg';
				//For each template folder, create this array
				$tpl_list[$tpl_dir] = array	(
																		'is_current' => $is_current,
																		'tpl_date' => $tpl_date,
																		'tpl_dir' => $tpl_dir,
																		'tpl_title' => $tpl_title,
																		'tpl_author' => $tpl_author,
																		'tpl_author_url' => $tpl_author_url,
																		'tpl_description' => $tpl_description,
																		'tpl_preview_url' => $tpl_preview_url,
																		'tpl_thumb_width' => $tpl_thumb_width,
																		'tpl_thumb_height' => $tpl_thumb_height,
																		'tpl_medium_width' => $tpl_medium_width,
																		'tpl_medium_height' => $tpl_medium_height,
																		'tpl_thumb_crop' => $tpl_thumb_crop,
																		'tpl_custom' => $final_parameters
																		);
		}
	}
}
//Sorting final array with all data. By default, is sorted according to the very first array key ($tpl_date in this case)
array_multisort ($tpl_list, SORT_DESC);
return $tpl_list;
}

/*
* FUNCTION tag_list()
* Returns an array with the the list of tag and the number of photo with that tag
* $path argument is mandatory (string). 
* $limit (number) argument is optional. If set, the function uses this tag limit, otherwise the gallery tag limit
* $array_to_use is optional. Must be the result of the scan() function
* $order_by (string) is optional. Set the order of the tag list ('popularity', 'a-z', 'z-a')
*/
function tag_list($path,$limit,$array_to_use,$order_by='popularity') {
	//Retrieving variables
	global $gallery_tag_limit;
	global $gallery_tag_list_ordering;
	//Get complete photo data array
	//If $array_to_use (optional) is set, it means we don't have to build the global scan files array, we treat $array_to_use
	if(!empty($array_to_use)) {
		$data = $array_to_use;
	//Else we run a complete scan
	}else{
		$data = scan($path, '', '', '', '');
	}
	//Create new array
	$result=array();
	//Insert every keywords into $result array
	foreach($data as $photo) {
		//Getting every keyword contained in the keywords array
		if(is_array($photo['ph_keywords'])){
			foreach($photo['ph_keywords'] as $keyword) {
				$result[] = $keyword;
				//Inserting result into $result array
				//$result[] = $keyword;
			}		
		}
	}

	//Remove duplicates and counting number of each keywords occurence in a $count array 'keyword' => number of occurences
	$count=array_count_values($result);
	//Sorting descending $count array from most used keywords to less used
	array_multisort($count,SORT_DESC);
	//Adding results to a final array $tag_list with [tag] => [occurrences]
	foreach ($count as $tag => $val) {
		$tag_list[$tag]=$val;
	}
	//$count and $tag_list must be an array, otherwise it may cause parse error
	if(!is_array($tag_list) || !is_array($count)){
		return false;
	}


	if($order_by == 'popularity'){
		//If $limit is set, only return the first $limit tags
		if(!empty($limit)){
			return array_slice($tag_list,0,$limit);
		//Otherwise return the first $gallery_tag_limit tags (default)	
		}else{
			return array_slice($tag_list,0,$gallery_tag_limit);
		}
	}elseif($order_by == 'a-z'){
		ksort($count);
		//If $limit is set, only return the first $limit tags
		if(!empty($limit)){
			return array_slice($count,0,$limit);
		//Otherwise return the first $gallery_tag_limit tags (default)	
		}else{
			return array_slice($count,0,$gallery_tag_limit);
		}
	}elseif($order_by == 'z-a'){
		krsort($count);
		//If $limit is set, only return the first $limit tags
		if(!empty($limit)){
			return array_slice($count,0,$limit);
		//Otherwise return the first $gallery_tag_limit tags (default)	
		}else{
			return array_slice($count,0,$gallery_tag_limit);
		}
	}

}

/*
* FUNCTION count_tag_list()
* Simple utility that returns the number of different tags in the gallery directory
* $tag_list argument is mandatory. It must be the result of the tag_list() function.
*/
function count_tag_list($tag_list) {
	//Fix the empty result (count(empty array) returns 1 instead of 0). These lines fix this
  if(empty($tag_list)) {
    return '0';
  }else{
    if(count($tag_list) == 1){
      return '1';
    }else{
      return count($tag_list);
    }
  }
}

/*
* FUNCTION tag_popularity()
* Returns an array with the keywords ordered by popularity
* $photo_tags argument is mandatory (array). It is a 1 dimension array of keywords, generally extracted from the jpg file, which has to * be ordered by popularity
* $complete_tag_list argument is optional (array). It is the result of the tag_list() function
*/
function tag_popularity($photo_tags, $complete_tag_list) {
	//Variables recall
	global $gallery_folder;
	global $script_path;
	//If $complete_tag_list (optional) is set, it means we don't have to build the tag list array, we treat $complete_tag_list
	if(!empty($complete_tag_list)) {
		$tags_array = $complete_tag_list;
	//Else we run a complete scan og the tag list
	}else{
		$tags_array = tag_list($script_path.'/'.$gallery_folder, 999, '');
	}
	$tag_list = array();
	if(is_array($tags_array)){
		foreach($tags_array as $tag => $occurrencies) {
			$tag_list[] = $tag;
		}
	}
	//Ordering the photo keywords by popularity
	$ph_keywords_ordered = array();
	if(is_array($photo_tags)) {
		foreach($photo_tags as $key => $value) {
			$rank = array_search($value, $tag_list);
			$ph_keywords_ordered[$value] = $rank;
		}
	}
	//Re ordering the photo keywords from the less poppular to the most popular
	array_multisort($ph_keywords_ordered, SORT_ASC);
	return $ph_keywords_ordered;
}

/*
* FUNCTION sort_multi()
* Utility function that sort and order an array according to a key
* $array argument is mandatory (array). It is the array to sort and order
* $key argument is mandatory (string). Sort according to this key
* $order argument is mandatory (string). Typically 'DESC' or 'ASC'
*/
function sort_multi($array, $key, $order) {
	$keys = array();
	for ($i=1;$i<func_num_args();$i++) {
		$keys[$i-1] = func_get_arg($i);
	}
	if ($order!=='DESC') {
		//Create a custom search function to pass to usort ascending
		$func = function ($a, $b) use ($keys) {
		for ($i=0;$i<count($keys);$i++) {
		if ($a[$keys[$i]] != $b[$keys[$i]]) {
			return ($a[$keys[$i]] < $b[$keys[$i]]) ? -1 : 1;
		}
	}
	return 0;
	};
	}else{
	//Create a custom search function to pass to usort descending
	$func = function ($a, $b) use ($keys) {
	for ($i=0;$i<count($keys);$i++) {
	if ($a[$keys[$i]] != $b[$keys[$i]]) {
	return ($a[$keys[$i]] > $b[$keys[$i]]) ? -1 : 1;
	}
	}
	return 0;
	};
	}
	usort($array, $func);
	return $array;
}



function getGps($exifCoord, $hemi) {
    $degrees = count($exifCoord) > 0 ? gps2Num($exifCoord[0]) : 0;
    $minutes = count($exifCoord) > 1 ? gps2Num($exifCoord[1]) : 0;
    $seconds = count($exifCoord) > 2 ? gps2Num($exifCoord[2]) : 0;
    $flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;
    return $flip * ($degrees + $minutes / 60 + $seconds / 3600);
}
 
function gps2Num($coordPart) {
    $parts = explode('/', $coordPart);
    if (count($parts) <= 0)
        return 0;
    if (count($parts) == 1)
        return $parts[0];
    return floatval($parts[0]) / floatval($parts[1]);
}


?>