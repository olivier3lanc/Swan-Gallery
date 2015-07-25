<?php 
/**
* CONFIG FILE
* Core data and parameters
* Swan Gallery
* @version 0.9.1
* @author Olivier Blanc http://www.egalise.com/swan-gallery
* @license MIT
*/




/**
* CORE VARIABLES
* Common variables used in Swan Gallery functions and templates
*/

//Thumbs directory
$ph_thumbs_dir = 'thumbs';

//Root Path
$root_path = $_SERVER['DOCUMENT_ROOT'];

//Current script path
$script_path=getcwd();

//Domain name of the current Swan Gallery instance
$server_http_host = $_SERVER['HTTP_HOST'];
$server_request_uri = $_SERVER['REQUEST_URI'];
if(strpos($server_http_host, 'www.') !== false) {
	$http_host = 'http://'.$server_http_host;
}else{
	$http_host = 'http://www.'.$server_http_host;
}

//Swan Gallery root URL
$script_url= 'http://'.$server_http_host.$server_request_uri;	

//Swan Gallery Branding
$powered_by = 'Powered by';
$swan_gallery_tooltip = 'A web photo gallery for photographers and creatives';

//Field delimiters
$delimiter1 = '__||__';
$delimiter2 = '::||::';


/**
* CORE FUNCTION parameters()
* Swan Gallery parameters 
* Retrieve the essential variables of the gallery in the jpg file of the engine/data directory
* If no jpg or blank jpg detected, applying the default parameters values
*/

function parameters($admin) {
	//Retrieving fields delimiters
	global $delimiter1;
	global $delimiter2;
	
	//Check if default parameter data.jpg exists
	if(file_exists($admin.'engine/data/data.jpg')){
		$default_file = true;
	}else{
		$default_file = false;
	}

	//Looking for the jpg config file
	$config_file =  glob($admin.'engine/data/*.jpg');
	$number_of_files = count($config_file);

	//If jpg file(s)
	if($number_of_files>0) {
		//If custom parameter file exists AND default parameter file data.jpg exists, delete data.jpg
		if($number_of_files>1 && $default_file == true){
			unlink($admin.'engine/data/data.jpg');
		}

		/**
		* Update compatibility 
		* Delete unecessary files if several jpg files detected
		* For the moment we keep only the first, but it is not necessarily the right one
		* This avoids to have several data jpg files
		*/
		if($number_of_files>1 && $default_file == false){
			for ($i = 1; $i <= $number_of_files; $i++) {
		    	unlink($config_file[$i]);
			}
		}

		//String to remove
		$removal = $admin.'engine/data/';
		$config_file = str_replace($removal,'',$config_file[0]);

		//Gallery parameters
		getimagesize($admin.'engine/data/'.$config_file,$info);
		$iptc = iptcparse($info["APP13"]);
		$gallery_title = $iptc["2#005"][0];
		$gallery_description = $iptc["2#120"][0];
		$specials_instructions = $iptc["2#040"][0];
		if(!empty($specials_instructions)){
			$specials_instructions = explode($delimiter1,$specials_instructions);
			$parameters = array();
			foreach($specials_instructions as $foo) {
				$parameter_string = explode($delimiter2,$foo);
				$parameters[$parameter_string[0]] = $parameter_string[1];
			}
			extract($parameters);
		}
	}

	//If no data file or empty variable
	if(empty($gallery_title)) {
		$gallery_title = 'Swan Gallery';
	}
	if(empty($gallery_description)) {
		$gallery_description = 'Here is a photo gallery powered by Swan Gallery';
	}
	if(empty($gallery_folder)) {
		$gallery_folder = 'folio';
	}
	if(empty($gallery_template)) {
		$gallery_template = 'swan_2015';
	}
	if(empty($gallery_tag_limit)) {
		$gallery_tag_limit = '16';
	}
	if(empty($gallery_tag_list_ordering)) {
		$gallery_tag_list_ordering = 'popularity';
	}
	if(empty($gallery_homepage_type)) {
		$gallery_homepage_type = 'std';
	}
	if(empty($gallery_cache)) {
		$gallery_cache = 'disabled';
	}
	if(empty($gallery_cache_expire)) {
		$gallery_cache_expire = '12';
	}
	if(empty($gallery_photos_per_page)) {
		$gallery_photos_per_page = '30';
	}
	if(empty($gallery_sorting)) {
		$gallery_sorting = 'ph_date';
	}
	if(empty($gallery_ordering)) {
		$gallery_ordering = 'DESC';
	}
	if(empty($gallery_related)) {
		$gallery_related = 'both';
	}
	if(empty($gallery_related_limit)) {
		$gallery_related_limit = '4';
	}
	if(empty($gallery_email)) {
		$gallery_email = '';
	}
	if(empty($gallery_credit)) {
		$gallery_credit = '';
	}
	if(empty($gallery_credit_display)) {
		$gallery_credit_display = '0';
	}
	if(empty($gallery_statistics_code)) {
		$gallery_statistics_code = '';
	}
	if(empty($gallery_facebook_page)) {
		$gallery_facebook_page = '';
	}
	if(empty($gallery_twitter)) {
		$gallery_twitter = '';
	}
	if(empty($gallery_rss_entries)) {
		$gallery_rss_entries = '10';
	}
	if(empty($gallery_google_plus)) {
		$gallery_google_plus = '';
	}
	if(empty($gallery_admin_photos_per_page)) {
		$gallery_admin_photos_per_page = '15';
	}
	if(empty($gallery_admin_sorting)) {
		$gallery_admin_sorting = 'ph_date_created';
	}
	if(empty($gallery_admin_ordering)) {
		$gallery_admin_ordering = 'DESC';
	}
	if(empty($gallery_admin_tag_list_ordering)) {
		$gallery_admin_tag_list_ordering = 'popularity';
	}
	if(empty($gallery_admin_language)) {
		$gallery_admin_language = 'en_EN';
	}
	return array(	'gallery_title' => $gallery_title,
					'gallery_description' => $gallery_description,
					'gallery_folder' => $gallery_folder,
					'gallery_template' => $gallery_template,
					'gallery_tag_limit' => $gallery_tag_limit,
					'gallery_tag_list_ordering' => $gallery_tag_list_ordering,
					'gallery_homepage_type' => $gallery_homepage_type,
					'gallery_cache' => $gallery_cache,
					'gallery_cache_expire' => $gallery_cache_expire,
					'gallery_photos_per_page' => $gallery_photos_per_page,
					'gallery_sorting' => $gallery_sorting,
					'gallery_ordering' => $gallery_ordering,
					'gallery_related' => $gallery_related,
					'gallery_related_limit' => $gallery_related_limit,
					'gallery_email' => $gallery_email,
					'gallery_credit' => $gallery_credit,
					'gallery_credit_display' => $gallery_credit_display,
					'gallery_statistics_code' => $gallery_statistics_code,
					'gallery_facebook_page' => $gallery_facebook_page,
					'gallery_twitter' => $gallery_twitter,
					'gallery_rss_entries' => $gallery_rss_entries,
					'gallery_google_plus' => $gallery_google_plus,
					'gallery_admin_photos_per_page' => $gallery_admin_photos_per_page,
					'gallery_admin_sorting' => $gallery_admin_sorting,
					'gallery_admin_ordering' => $gallery_admin_ordering,
					'gallery_admin_tag_list_ordering' => $gallery_admin_tag_list_ordering,
					'gallery_admin_language' => $gallery_admin_language,
					'gallery_config_file' => $config_file
				);
}

//Pasting gallery parameters values into an array
$parameters = parameters($admin);
//Extract each parameter array key as a single string variable
extract($parameters);

//Reading template parameters defined in the template [template_directory_name]/config.php
include $admin.'engine/templates/'.$gallery_template.'/config.php';

/**
* CORE FUNCTION tpl_parameters()
* Swan Gallery template parameters 
* Retrieve the optional template parameters coded by the template author
* Scan the optional $tpl_parameters_tree in the config.php file of the template directory
* Scan the optional data/data.jpg file in the template directory
*/

function tpl_parameters($gallery_template) {
	global $admin;
	global $delimiter1;
	global $delimiter2;
	//Retrieving the template parameters
	global $tpl_parameters_tree;
	//If there are no template parameters ($tpl_parameters_tree = '') in the [template_directory]/config.php file
	if(empty($tpl_parameters_tree)) {
		return false;
	}
	//Initializing the final template parameters array
	$tpl_default_parameters = array();
	//Building the default template parameters array
	foreach($tpl_parameters_tree as $parameter_id => $parameter_array) {
		if($parameter_array['type'] == 'radio') {
			$parameter_array_values = array_slice($parameter_array['values'], 0, 1);
			$tpl_default_parameters[$parameter_id] = array_shift($parameter_array_values);
		}else{
			$tpl_default_parameters[$parameter_id] = $parameter_array['values'];
		}
	};
	//If file exists
	if(file_exists($admin.'engine/templates/'.$gallery_template.'/data/data.jpg') !== false) {
		//data.jpg config file of the template
		$tpl_config_file =  $admin.'engine/templates/'.$gallery_template.'/data/data.jpg';
		//Read data.jpg file
		getimagesize($tpl_config_file,$info);
		$iptc = iptcparse($info["APP13"]);
		$tpl_specials_instructions = $iptc["2#040"][0];
		//Initializing the custom template parameters array
		$tpl_custom_parameters = array();
		//If data.jpg contains instructions, we use these parameters
		if(!empty($tpl_specials_instructions)) {
			//It means that user has saved template parameters at least once in the gallery admin
			$tpl_specials_instructions = explode($delimiter1,$tpl_specials_instructions);
			foreach($tpl_specials_instructions as $foo) {
				$tpl_parameter_string = explode($delimiter2,$foo);
				$tpl_custom_parameters[$tpl_parameter_string[0]] = $tpl_parameter_string[1];
			}
			//If one of the parameters is missing, use default
			foreach($tpl_default_parameters as $param => $default_value) {
				if(!isset($tpl_custom_parameters[$param])){
					$tpl_custom_parameters[$param] = $default_value;
				}
			}
			//$tpl_custom_parameters['test'] = $tpl_custom_parameters['css'];
			//Returning the template parameters array
			return $tpl_custom_parameters;
		//If data.jpg is empty, we return default parameters of config.php
		}else{
			//Reset mode. The data.jpg is blank, it means that user has not saved its own parameters yet
			return $tpl_default_parameters;
		}
	}else{
		//There is no data.jpg, then we use default parameters
		return $tpl_default_parameters;
	}
}



//Pasting template parameters values into an array
$tpl_parameters = tpl_parameters($gallery_template);

session_start();	
?>