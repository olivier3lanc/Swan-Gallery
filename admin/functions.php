<?php 
/**
* ADMIN FUNCTIONS
* Backend functions
* Swan Gallery
* @version 0.9
* @author Olivier Blanc http://www.egalise.com/swan-gallery
* @license MIT
*/


function admin_session() {
	global $hash_file;
	global $is_password_page;
	global $is_login_page;
	session_start();
	
	if($is_password_page !== FALSE) {
		if($hash_file !== 'install.jpg' && isset($_SESSION['is_admin'])) {
			//return header('Location: login.php');
		}
		elseif($hash_file !== 'install.jpg' && !isset($_SESSION['is_admin'])) {
			return header('login.php');
		}
		elseif($hash_file == 'install.jpg' && isset($_SESSION['is_admin'])) {
			unset($_SESSION['is_admin']);
			$_SESSION['install'] = TRUE;
		}
		elseif($hash_file == 'install.jpg' && !isset($_SESSION['is_admin'])) {
			$_SESSION['install'] = TRUE;
		}
	}elseif($is_login_page !== FALSE) {
		if($hash_file !== 'install.jpg' && isset($_SESSION['is_admin'])) {
			return header('Location: ./');
		}
		elseif($hash_file == 'install.jpg') {
			unset($_SESSION['is_admin']);
			$_SESSION['install'] = TRUE;
			return header('Location: password.php');
		}
	}else{
		if($hash_file == 'install.jpg' && !isset($_SESSION['is_admin'])) {
			$_SESSION['install'] = TRUE;
			return header('Location: password.php');
		}
		elseif($hash_file == 'install.jpg' && isset($_SESSION['is_admin'])) {
			unset($_SESSION['is_admin']);
			return header('Location: password.php');
		}
		elseif($hash_file !== 'install.jpg' && !isset($_SESSION['is_admin'])) {
			return header('Location: login.php');
		}
	}

}

function safe_b64encode($string) {
	$data = base64_encode($string);
	$data = str_replace(array('+','/','='),array('-','_',''),$data);
	return $data;
}

function safe_b64decode($string) {
	$data = str_replace(array('-','_'),array('+','/'),$string);
	$mod4 = strlen($data) % 4;
	if ($mod4) {
		$data .= substr('====', $mod4);
	}
	return base64_decode($data);
}

function encode($value){ 
	if(!$value){return false;}
	global $skey;
	$text = $value;
	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	$crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $skey, $text, MCRYPT_MODE_ECB, $iv);
	return trim(safe_b64encode($crypttext)); 
}

function decode($value){
	if(!$value){return false;}
	global $skey;
	$crypttext = safe_b64decode($value); 
	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	$decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $skey, $crypttext, MCRYPT_MODE_ECB, $iv);
	return trim($decrypttext);
}

function generate_random($length) {
	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$count = mb_strlen($chars);

	for ($i = 0, $result = ''; $i < $length; $i++) {
		$index = rand(0, $count - 1);
		$result .= mb_substr($chars, $index, 1);
	}

	return $result;
}

/*
* FUNCTION languages_list()
* Returns the detected languages files in the language directory
*/
function languages_list() {
	$files = glob('languages/*.*');
	$files = str_replace('languages/', '', $files);
	$files = str_replace('.php', '', $files);
	return $files;
}

/*
* FUNCTION sanitize_input()
* Utility function that strip unwanted strings. For the moment, HTML tags
* $string argument is mandatory (string). The string to sanitize
*/
function sanitize_input($string) {
	//Strip all HTML tags
	$string = strip_tags($string);
	//Strips excess whitespace from a string and strip whitespace from the beginning and end of a string
	$string = trim(preg_replace('/\s\s+/', ' ', $string));
	return $string;
}
?>