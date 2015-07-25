<?php 
/**
* ADMIN CONFIG FILE
* Core data for the gallery backend
* Swan Gallery
* @version 0.9
* @author Olivier Blanc http://www.egalise.com/swan-gallery/
* @license MIT
*/


/**
* List all jpg files in an array
* Should have only one jpg file
* install.jpg for fresh install / password reset
* [random_hashx32].jpg if password is set
*/
$hash_file =  glob('data/*.jpg');
$number_of_admin_files = count($hash_file);

//Check if install.jpg exists
if(file_exists('data/install.jpg')){
	$install_file = true;
}else{
	$install_file = false;
}

//If no jpg file, we go back to the frontend
//WARNING: Make "rebuild thumbnails" fail
//To fix ...
if($number_of_admin_files == 0) {
	//return header('Location: ../');
}

/**
* Update compatibility
* If install file exists AND there are more than 1 jpg file, delete install.jpg
*/
if($number_of_admin_files>1 && $install_file == true){
	unlink('data/install.jpg');
}

/**
*  Delete unecessary files if several jpg files detected
*  For the moment we keep only the first, but it is not necessarily the right one
*  This avoids to have several jpg files
*/
if($number_of_admin_files>1 && $install_file == false){
	for ($i = 1; $i <= $number_of_admin_files; $i++) {
    	unlink($hash_file[$i]);
	}
}

$removal = array('data/');
$hash_file = str_replace($removal,'',$hash_file[0]);
$is_password_page = strpos($_SERVER["REQUEST_URI"],'password.php');
$is_login_page = strpos($_SERVER["REQUEST_URI"],'login.php');
$skey = $_SERVER['DOCUMENT_ROOT'];


/**
* REBUILD THUMBNAILS AND PREVIEWS VARIABLES
* Variables used to force the rebuild of the thumbnails and the previews
*/

//Matching thumbnails directory
$dsg_thumbs_dir = $ph_thumbs_dir;
//Matching previews directory
$dsg_previews_dir = 'medium';
//Matching gallery directory (containing the photos)
$dsg_gallery_dir = '../../'.$gallery_folder;
$dsg_allow_jpg = TRUE;
//Matching previews width
$dsg_preview_width = $tpl_medium_width;
//Matching previews height
$dsg_preview_height = $tpl_medium_height;
//Matching thumbnails width
$dsg_thumb_width = $tpl_thumb_width;
//Matching thumbnails height
$dsg_thumb_height = $tpl_thumb_height;
//Matching crop or scale resize
if($tpl_thumb_crop == false) {
	$dsg_thumb_operation = 'scale';
}else{
	$dsg_thumb_operation = 'crop';
}
//Force rebuild of thumbnails and previews
$dsg_force_refresh = TRUE;
//HTML pattern displayed when rebuild is completed
$dsg_line_pattern = '<div class="row">{NEWLINE}
						<div class="col-md-3">{NEWLINE}
								<img src="../'.$gallery_folder.'/'.$dsg_thumbs_dir.'/{ORIGINAL_BASENAME}" width="{THUMB_WIDTH}" height="{THUMB_HEIGHT}" class="img-thumbnail" alt="{ORIGINAL_BASENAME}">{NEWLINE}
						</div>{NEWLINE}
						<div class="col-md-9">{NEWLINE}
							<h3>{ORIGINAL_BASENAME}</h3>{NEWLINE}

							<ul>{NEWLINE}
								<li><span class="label label-default">{THUMB_WIDTH} x {THUMB_HEIGHT}px '.$dsg_thumb_operation.'</span> Thumbnail{NEWLINE}
								</li>{NEWLINE}
								<li><span class="label label-default">{PREVIEW_WIDTH} x {PREVIEW_HEIGHT}px</span> Preview{NEWLINE}
								</li>{NEWLINE}
								<li><span class="label label-default">{ORIGINAL_WIDTH} x {ORIGINAL_HEIGHT}px</span> Original{NEWLINE}
								</li>{NEWLINE}
							</ul>{NEWLINE}
						</div>{NEWLINE}

						<div class="col-md-12">{NEWLINE}
							<hr>{NEWLINE}
						</div>{NEWLINE}
					</div>{NEWLINE}';

?>