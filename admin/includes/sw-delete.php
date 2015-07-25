<?php
	$admin='../../';
	include ('../../engine/config.php');
	if(isset($_POST['file'])) {
		$file = $_POST['file'];
		unlink('../../'.$gallery_folder.'/'.$file);
		unlink('../../'.$gallery_folder.'/'.$ph_thumbs_dir.'/'.$file);
		unlink('../../'.$gallery_folder.'/medium/'.$file);
		echo '../../'.$gallery_folder.'/'.$ph_thumbs_dir.'/'.$file;
	} else {
		header('Location: ./../');
	}
	
?>