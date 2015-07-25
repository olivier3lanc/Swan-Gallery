<?php
//PHP Simple Cache
//http://codecanyon.net/item/php-simple-cache/4169137
// Location and prefix for cache files // preferably use absolute path to cache directory
// while using mod rewrite relative path to cache directory might not work - so use absolute path
// absolute path to cache directory
// define('CACHE_PATH', $_SERVER["DOCUMENT_ROOT"]."/siteCache/");
define('CACHE_PATH', $script_path . "/engine/cache/");
// relative path to cache directory
// define('CACHE_PATH', "siteCache/");
// get page name to use in the cached file name
// $pagepath = basename($_SERVER['PHP_SELF']);
// $pagename = basename($pagepath);
// $pagename = basename($pagepath, ".php");
// define('PAGE_NAME', $pagename);
$gets = str_replace('=', '-', http_build_query($get_array,'','_'));
define('PAGE_NAME2', $gets);
// return location and name for cache file
function cache_file() {
    return CACHE_PATH . "cache_" . PAGE_NAME2 . ".html";
}
$file = cache_file();

//If emptycache is set in the URL, delete the URL cache file
if(isset($_GET['emptycache']) && file_exists($file)){
    unlink($file);

//Else
}elseif ($_SERVER["REQUEST_METHOD"] != 'POST') {
    // Time to keep the cache files in hours
    define('CACHE_TIME', $gallery_cache_expire);
    // display cached file if present and not expired
    function cache_display() {
        $file = cache_file();
        // check that cache file exists and is not too old
        if (!file_exists($file)) return;
        //if(filemtime($file) < time() - CACHE_TIME) return;
        if(CACHE_TIME !== 'never') {
            if(filemtime($file) < time() - CACHE_TIME * 3600) return;
        }
        // if so, display cache file and stop processing
        readfile($file);
        exit;
    }
    // write to cache file
    function cache_page($content) {
        if (false !== ($f = @fopen(cache_file(), 'w'))) {
            fwrite($f, $content);
            fclose($f);
        }
        return $content;
    }

    // execution stops here if valid cache file found
    cache_display();
    // enable output buffering and create cache file
    ob_start('cache_page');
}
?>