<?php 
/**
* ADMIN WRITE IPTC
* Write IPTC data into photos, gallery parameters and template parameters
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

	//If query is sent from gallery parameters
	if(isset($_POST['gallery_title'])) {
		admin_session();
		$gallery_title = sanitize_input($_POST['gallery_title']);
		$gallery_description = sanitize_input($_POST['gallery_description']);
		$gallery_folder = $_POST['gallery_folder'];
		$gallery_tag_limit = $_POST['gallery_tag_limit'];
		$gallery_tag_list_ordering = $_POST['gallery_tag_list_ordering'];
		$gallery_homepage_type = $_POST['gallery_homepage_type'];
		$gallery_cache = $_POST['gallery_cache'];
		$gallery_cache_expire = $_POST['gallery_cache_expire'];
		$gallery_photos_per_page = $_POST['gallery_photos_per_page'];
		$gallery_sorting = $_POST['gallery_sorting'];
		$gallery_ordering = $_POST['gallery_ordering'];
		$gallery_related = $_POST['gallery_related'];
		$gallery_related_limit = $_POST['gallery_related_limit'];
		$gallery_email = sanitize_input($_POST['gallery_email']);
		$gallery_credit = sanitize_input($_POST['gallery_credit']);
		$gallery_credit_display = $_POST['gallery_credit_display'];
		$gallery_statistics_code = $_POST['gallery_statistics_code'];
		$gallery_facebook_page = sanitize_input($_POST['gallery_facebook_page']);
		$gallery_twitter = sanitize_input($_POST['gallery_twitter']);
		$gallery_rss_entries = $_POST['gallery_rss_entries'];
		$gallery_template = $_POST['gallery_template'];
		$gallery_admin_photos_per_page = $_POST['gallery_admin_photos_per_page'];
		$gallery_admin_sorting = $_POST['gallery_admin_sorting'];
		$gallery_admin_ordering = $_POST['gallery_admin_ordering'];
		$gallery_admin_tag_list_ordering = $_POST['gallery_admin_tag_list_ordering'];
		$gallery_admin_language = $_POST['gallery_admin_language'];
		//Concatenate variables into another variable with double underscore separator
		$specials_instructions=	'gallery_folder'.$delimiter2.$gallery_folder.
								$delimiter1.'gallery_template'.$delimiter2.$gallery_template.
								$delimiter1.'gallery_tag_limit'.$delimiter2.$gallery_tag_limit.
								$delimiter1.'gallery_tag_list_ordering'.$delimiter2.$gallery_tag_list_ordering.
								$delimiter1.'gallery_homepage_type'.$delimiter2.$gallery_homepage_type.
								$delimiter1.'gallery_cache'.$delimiter2.$gallery_cache.
								$delimiter1.'gallery_cache_expire'.$delimiter2.$gallery_cache_expire.
								$delimiter1.'gallery_photos_per_page'.$delimiter2.$gallery_photos_per_page.
								$delimiter1.'gallery_sorting'.$delimiter2.$gallery_sorting.
								$delimiter1.'gallery_ordering'.$delimiter2.$gallery_ordering.
								$delimiter1.'gallery_related'.$delimiter2.$gallery_related.
								$delimiter1.'gallery_related_limit'.$delimiter2.$gallery_related_limit.
								$delimiter1.'gallery_email'.$delimiter2.$gallery_email.
								$delimiter1.'gallery_credit'.$delimiter2.$gallery_credit.
								$delimiter1.'gallery_credit_display'.$delimiter2.$gallery_credit_display.
								$delimiter1.'gallery_statistics_code'.$delimiter2.$gallery_statistics_code.
								$delimiter1.'gallery_facebook_page'.$delimiter2.$gallery_facebook_page.
								$delimiter1.'gallery_twitter'.$delimiter2.$gallery_twitter.
								$delimiter1.'gallery_rss_entries'.$delimiter2.$gallery_rss_entries.
								$delimiter1.'gallery_admin_photos_per_page'.$delimiter2.$gallery_admin_photos_per_page.
								$delimiter1.'gallery_admin_sorting'.$delimiter2.$gallery_admin_sorting.
								$delimiter1.'gallery_admin_ordering'.$delimiter2.$gallery_admin_ordering.
								$delimiter1.'gallery_admin_tag_list_ordering'.$delimiter2.$gallery_admin_tag_list_ordering.
								$delimiter1.'gallery_admin_language'.$delimiter2.$gallery_admin_language;
		//Create arrays to send to the function iptc write
		$ph_stack=array(	'title' => $gallery_title,
							'description' => $gallery_description, 
							'specials_instructions' => $specials_instructions
						);
		//Paths to the jpg files to write
		$ph_path='../engine/data/'.$gallery_config_file;
		//Write to the file
		iptc_write($ph_path,$ph_stack);
		//Generate a random 32 characters key
		$random_engine_filename = generate_random(32);
		//Rename the file for security reasons
		rename($ph_path, '../engine/data/'.$random_engine_filename.'.jpg');
		
	//From template parameters
	}elseif(isset($_POST['tpl_parameters'])) {
		admin_session();
		//Get all the POST parameters into an array
		$posts = $_POST;
		//Initialise a temporary array
		$tpl_parameters_temp = array();
		//Build a temporary array to separate parameter/value groups that will be imploded into a string afterwards
		foreach($posts as $parameter_name => $parameter_data) {
			if($parameter_name !== 'tpl_parameters') {
				$tpl_parameters_temp[] = $parameter_name.$delimiter2.$parameter_data;
			}
		}
		//Final template parameters string that will be recorded into the data jpg file of the template
		$tpl_parameters = implode($delimiter1,$tpl_parameters_temp);
		//Array to write into data.jpg
		$tpl_stack = array('specials_instructions' => $tpl_parameters);
		//Paths to the template data jpg files to write
		$tpl_ph_path='../engine/templates/'.$gallery_template.'/data/data.jpg';
		iptc_write($tpl_ph_path,$tpl_stack);
		
	//From password.php
	}elseif(isset($_POST['gallery_admin_pwd'])) {
		if(isset($_SESSION['install']) || isset($_SESSION['is_admin'])) {
			$gallery_admin_pwd = $_POST['gallery_admin_pwd'];
			$gallery_admin_pwd_check = $_POST['gallery_admin_pwd_check'];
			if($gallery_admin_pwd == $gallery_admin_pwd_check) {
				$password = encode($gallery_admin_pwd);
				$data = array('specials_instructions' => $password);
				$random_filename = generate_random(32);
				$random_filename2 = generate_random(32);
				iptc_write('data/'.$hash_file,$data);
				rename('data/'.$hash_file,'data/'.$random_filename.'.jpg');
				rename('../engine/data/'.$gallery_config_file, '../engine/data/'.$random_filename2.'.jpg');
				header('Location: ./login.php?installed');
			}else{
				header('Location: ./password.php?mismatch');
			}
		}else{
			header('Location: http://www.google.fr');
		}
	

	//From image file                                                          
	}elseif(isset($_POST['ph_file'])) { 
		admin_session();
		//Retrieving POST variables
		$ph_file = $_POST['ph_file'];
		$ph_title = sanitize_input($_POST['ph_title']);
		$ph_description = sanitize_input($_POST['ph_description']);
		$ph_date = $_POST['ph_date'];
		$ph_year = $_POST['ph_year'];
		$ph_month = $_POST['ph_month'];
		$ph_day = $_POST['ph_day'];
		$ph_featured = $_POST['ph_featured'];
		$ph_keywords = sanitize_input($_POST['ph_keywords']);
		
		if(!empty($_POST['ph_favorite_to_tags'])) {
			//Retrieving the favorite to tags array
			$ph_favorite_to_tags = $_POST['ph_favorite_to_tags'];
			$ph_keywords = explode(',', $ph_keywords);
			$ph_keywords_pre = array();
			foreach($ph_keywords as $keyword) {
				foreach($ph_favorite_to_tags as $favorite_tag) {
					if($favorite_tag == $keyword) {
					  $ph_keywords_pre[] = $keyword.'|1';
					}else{
					  $ph_keywords_pre[] = $keyword;
					}
				}
			}
			$ph_keywords = $ph_keywords_pre;
			//$ph_keywords = implode($delimiter1,$ph_keywords_pre);
			
		}else{
			//Build an array from data to save into Special Instructions
			$ph_keywords = explode(',', $ph_keywords);
			//$ph_keywords = str_replace(',',$delimiter1,$ph_keywords);
		}

		//Initialize the special instructions array
		$ph_special_data = array(
									'keywords' => $ph_keywords,
									'gps' => ''
								);

		//Serialize Swan Gallery special data into IPTC 040 Special Instructions
		$ph_serialized_special_data = 'SwanGData:'.serialize($ph_special_data);

		//Remove date separator
		$ph_date = str_replace('-','',$ph_date);

		//Path of the jpg file to write
		$ph_path = '../'.$gallery_folder.'/'.$ph_file;
		
		//Array data to write into the jpg file
		$ph_stack=array(	
							'date' => $ph_date, 
							'title' => $ph_title, 
							'description' => $ph_description, 
							'specials_instructions' => $ph_serialized_special_data, 
							'featured' => $ph_featured
						);
		
		//Write into the jpg file
		iptc_write($ph_path,$ph_stack);
	}else{
		header('Location: http://www.google.fr');
	}
?>
