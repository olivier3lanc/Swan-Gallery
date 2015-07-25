<?php 
	$admin='../../';
	//Tag list for autocomplete
	include ('../../engine/config.php');
	include ('../../engine/functions.php');
	include ('../config.php');
	include ('../functions.php');
	admin_session();
	//$script_path=dirname(getcwd());
	$tags=tag_list('../../'.$gallery_folder,999, '');
	foreach($tags as $tag => $key){
	  echo $tag."\n";
	}
?>