<?php
/**
* ROOT FILE
* Gathers all the necessary data to make the gallery working
* Swan Gallery
* @version 0.9
* @author Olivier Blanc http://www.egalise.com/swan-gallery
* @license MIT
*/

//Relative path
$admin = '';

//All GET variables, important for the cache
$get_array = $_GET;

//Swan Gallery config file
include 'engine/config.php';

//Cache only if enabled in the gallery parameters
if($gallery_cache !== 'disabled') {
	include 'engine/includes/cache.php';
}

//Swan Gallery core functions
include 'engine/functions.php';

//Template include
include 'engine/templates/' . $gallery_template . '/index.php';
?>
