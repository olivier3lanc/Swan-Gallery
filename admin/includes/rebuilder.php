<?php
if ( basename(__FILE__) == basename($_SERVER['PHP_SELF']) ) exit('Direct access not permitted.');
/**
 * Dead Simple Gallery
 * Allows you to generate an instant list of image thumbnails with links to previews,
 * from a directory containing images, ideal for Lightbox - like galleries,
 * you can manage the images through FTP client, no coding skills or databases needed!
 *
 * Main features:
 * * simplicity & customizability, use it just by including this script,
 *   or if you are a power user - overwrite the defaults with your own
 *   outside of this script, for full info check the documentation
 * * create thumbnails by cropping (default) or scaling
 * * set thumbnail dimensions
 * * set preview dimensions
 * * supports JPG, GIF & PNG
 * * supports compression
 * * thumbnails are generated on the fly
 * * image caching
 * * works perfectly with JS & jQuery galleries like Lightbox or Fancybox
 * * works with image slideshows
 * * control image alt and link title attributes with image names
 * * supports both HTML & XHTML
 *
 * @author Devoth - Łukasz Mazurek - http://www.devoth.com/
 * @version 1.1.2
 * @copyright Devoth Design, 21 April, 2012
 **/

// configuring and checking for user overwrites

$dsg_output_messages = array();
$dsg_starttime;
$dsg_execution_time;

// set up gallery directory
if ( ! isset($dsg_gallery_dir) ) {
  $dsg_gallery_dir = 'ds_galleries';
}
// for script consistency we remove trailing forward slash from the end of gallery dir name
$dsg_gallery_dir = rtrim($dsg_gallery_dir, '/');

// set up thumbnails directory name
if ( ! isset($dsg_thumbs_dir) || $dsg_thumbs_dir == '' ) {
  $dsg_thumbs_dir = 'dynamic_thumbnails';
}
// for script consistency we remove trailing forward slash from the end of thumbs dir name
$dsg_thumbs_dir = trim($dsg_thumbs_dir, '/');

// set up previews directory name
if ( ! isset($dsg_previews_dir) || $dsg_previews_dir == '' ) {
  $dsg_previews_dir = 'dynamic_previews';
}
// for script consistency we remove trailing forward slash from the end of previews dir name
$dsg_previews_dir = trim($dsg_previews_dir, '/');

// initially JPG, GIF and PNG files are allowed, but user can overwrite that
$dsg_allowed_extensions = array();
if ( ! isset($dsg_allow_gif) || $dsg_allow_gif == TRUE ) {
  array_push($dsg_allowed_extensions, 'gif');
}
if ( ! isset($dsg_allow_jpg) || $dsg_allow_jpg == TRUE ) {
  array_push($dsg_allowed_extensions, 'jpg');
  array_push($dsg_allowed_extensions, 'jpeg');
}
if ( ! isset($dsg_allow_png) || $dsg_allow_png == TRUE ) {
  array_push($dsg_allowed_extensions, 'png');
}

// script can generate XHTML or HTML tags
if ( ! isset($dsg_xhtml) || $dsg_xhtml !== TRUE ) {
  $dsg_xhtml = FALSE;
}

// set up default preview width
if ( ! isset($dsg_preview_width) ) {
  $dsg_preview_width = 800;
}

// set up default preview height
if ( ! isset($dsg_preview_height) ) {
  $dsg_preview_height = 700;
}

// set up default thumbnail width
if ( ! isset($dsg_thumb_width) ) {
  $dsg_thumb_width = 210;
}

// set up default thumbnail height
if ( ! isset($dsg_thumb_height) ) {
  $dsg_thumb_height = 140;
}

// set up default operation for thumbnail generation
if ( ! isset($dsg_thumb_operation) ) {
  $dsg_thumb_operation = 'crop';
}
else {
  switch ($dsg_thumb_operation) {
    case 'scale':
      break;

    default:
      $dsg_thumb_operation = 'crop';
      break;
  }
}

// set up default thumbnail compression
if ( ! isset($dsg_compression) ) {
  $dsg_compression = 80;
}

// user can force refresh to recreate thumbnails on each page refresh
// use with caution
if ( ! isset($dsg_force_refresh) || $dsg_force_refresh == FALSE ) {
  $dsg_force_refresh = FALSE;
}
else {
  $dsg_output_messages['force_refresh'] = "Force refresh is active. In this mode <b>thumbnails</b> and <b>previews</b> are recreated on every page refresh. Avoid leaving it this way on production servers &mdash; it's meant only for testing purposes.";
}

// set default variable delimiter for extracting (exploding) values from base file name
if ( ! isset($dsg_filename_delimiter) ) {
  $dsg_filename_delimiter = '|';
}

// set default output pattern
if ( ! isset($dsg_line_pattern) ) {
  $dsg_line_pattern = '<li><a href="{PREVIEW_URL}" title="{ORIGINAL_FILENAME_HUMANIZED}" rel="ds_gallery">';
  $dsg_line_pattern.= '<img src="{THUMB_URL}" width="{THUMB_WIDTH}" height="{THUMB_HEIGHT}" alt="{ORIGINAL_FILENAME_HUMANIZED}"{SELFCLOSE}>';
  $dsg_line_pattern.= '</a></li>{NEWLINE}';
}

// put all config parameters into a nice config array
$dsg_config = array();
$dsg_config['gallery_dir'] = $dsg_gallery_dir;
$dsg_config['thumbs_dir'] = $dsg_thumbs_dir;
$dsg_config['previews_dir'] = $dsg_previews_dir;
$dsg_config['allowed_extensions'] = $dsg_allowed_extensions;
$dsg_config['xhtml'] = $dsg_xhtml;
$dsg_config['preview_width'] = $dsg_preview_width;
$dsg_config['preview_height'] = $dsg_preview_height;
$dsg_config['thumb_width'] = $dsg_thumb_width;
$dsg_config['thumb_height'] = $dsg_thumb_height;
$dsg_config['thumb_operation'] = $dsg_thumb_operation;
$dsg_config['compression'] = $dsg_compression;
$dsg_config['force_refresh'] = $dsg_force_refresh;
$dsg_config['filename_delimiter'] = $dsg_filename_delimiter;
$dsg_config['line_output_pattern'] = $dsg_line_pattern;

// functions

if( ! function_exists('dsgEchuj')):
/**
 * Prints out variables in a more readable way
 *
 * @param mixed $variable variable to be printed, most likely an Array
 * @return void
 * @author Devoth
 */
function dsgEchuj($variable) {
  echo '<pre>';
  print_r($variable);
  echo '</pre>';
}
endif;

if( ! function_exists('dsgExecutionTimeTrackingStart')):
/**
 * Starts tracking time of script execution
 *
 * @return float Returns start time for use in other functions
 * @author Devoth
 */
function dsgExecutionTimeTrackingStart() {
  $mtime = microtime();
  $mtime = explode(" ",$mtime);
  $mtime = $mtime[1] + $mtime[0];
  $starttime = $mtime;
  return $starttime;
}
endif;

if( ! function_exists('dsgExecutionTimeTrackingEnd')):
/**
 * Ends time tracking and returns elapsed time info
 * calculates the difference between start time passed as a param, and current time
 *
 * @param float $starttime Number containing start time
 * @return string Returns string with output message containing info about time difference
 * @author Devoth
 */
function dsgExecutionTimeTrackingEnd($starttime) {
  $mtime = microtime();
  $mtime = explode(" ",$mtime);
  $mtime = $mtime[1] + $mtime[0];
  $endtime = $mtime;
  $totaltime = number_format($endtime - $starttime, 6);

  return "Images were generated in " . $totaltime . " seconds";
}
endif;

if( ! function_exists('dsginfo')):
/**
 * Prints out all of the script info
 *
 * @return void
 * @author Devoth
 */
function dsginfo() {
  global $dsg_output_messages;

  foreach ($dsg_output_messages as $key => $value) {
    echo $value . '<br>';
  }
}
endif;

if( ! function_exists('dsgHumanize')):
/**
 * Converts slug-like string to human readable string
 * with underscores converted to spaces and camelCase converted to space separated words
 *
 * @param string $string string to be converted
 * @return string Returns initial string converted to human readable version
 * @author Devoth
 */
function dsgHumanize($string) {
  // replace underscores with spaces, prepend space to Big letters
  $string = preg_replace('/(([A-Z])|_|-)/', ' $2', $string);

  // replace multiple spaces with one
  $string = preg_replace('/ {2,}/', ' ', $string);

  // trim spaces from the front and the end of the string
  $string = trim($string);

  // start each word from Uppercase
  $string = ucwords($string);

  return $string;
}
endif;

if( ! function_exists('dsgGetImages')):
/**
 * Fetches images from a directory, images can be filtered by extension
 *
 * @param array $config Array containing config parameters, expects gallery_dir and allowed_extension indexes, first one containing directory string, second containing extensions Array containing allowed image file extensions
 * @return array Returns an Array of images
 * @throws Exception if directory is not set, trying to get images from an invalid directory, or there are no images in the directory
 * @author Devoth
 */
function dsgGetImages ($config) {
  $dir = $config['gallery_dir'];
  $allowed_extensions = $config['allowed_extensions'];

  if ( $dir == FALSE ) {
    throw new Exception('Please specify a directory');
  }
  if ( ! is_dir($dir) ) {
    throw new Exception('Invalid gallery directory. Please create a folder called "' . $dir . '".');
  }

  $image_files = array();

  if ( FALSE !== ($dir_o = @opendir($dir)) ) {
    while ( FALSE !== ($entry = @readdir($dir_o)) ) {
      // omnit directories
      if ( is_dir($dir . '/' . $entry) ) {
        continue;
      }
      $path = pathinfo($entry);
      $ext = $path['extension'] ;
      if( in_array(strtolower($ext), $allowed_extensions) ) {
        array_push($image_files, $path['basename']);
      }
    }
    closedir($dir_o);
  }

  // check if there were images returned
  if ( empty($image_files) ) {
    throw new Exception('Sorry, no images at the moment');
  }

  sort($image_files);
  $images_data = array();
  foreach ($image_files as $image_file)
  {
    $image_data = array();	
	$image_file_parts = explode('.', $image_file);
    $ext = array_pop($image_file_parts);
    $fullpath = $dir . '/' . $image_file;
    $size = getimagesize($fullpath);

    $image_data['basename'] = $image_file; // full file name (basename)
    $image_data['filename'] = basename($image_file, '.' . $ext ); // file name (without extension)
    $image_data['extension'] = $ext; // extension
    $image_data['dirname'] = $dir; // dir_path
    $image_data['fullpath'] = $fullpath; // fullpath
    // $image_data['size'] = $size; // size [width, height]
    $image_data['sizex'] = $size[0]; // width
    $image_data['sizey'] = $size[1]; // height
    $image_data['mime'] = $size['mime']; // mime (mime type)
    $image_data['type'] = $size[2]; // type

    $images_data[] = $image_data;
  }

  return $images_data;
}
endif;

if( ! function_exists('dsgLoadImage')):
/**
 * Loads image pixel data of a specified image file, based on image type
 *
 * @param array $image Array containing image file data (including image path)
 * @return resource Returns an image resource identifier on success
  * @throws Exception if image type is not supported
 * @author Devoth
 */
function dsgLoadImage($image) {
  switch ($image['type']) {
    case IMAGETYPE_JPEG: return imagecreatefromjpeg( $image['fullpath'] );
    case IMAGETYPE_GIF:  return imagecreatefromgif( $image['fullpath'] );
    case IMAGETYPE_PNG:  return imagecreatefrompng( $image['fullpath'] );
    default:
      throw new Exception('Unsupported image type in ' . $image['basename']);
  }
}
endif;

if( ! function_exists('dsgScaleImage')):
/**
 * Scales an image, so it contains within a supplied dimensions box,
 * preserves original dimensions ratio
 *
 * @param array $image Array containing image file data
 * @param int $box_width Width of target dimensions box
 * @param int $box_height Height of target dimensions box
 * @return resource Returns an image resource identifier on success
 * @author Devoth
 */
function dsgScaleImage($image, $box_width, $box_height) {

  $current_image = dsgLoadImage( $image );

  // check if image exceeds target box dimensions
  if ( $image['sizex'] < $box_width && $image['sizey'] < $box_height) {
    // if no - abort resizing, and pass original image instead
    return $current_image;
  }

  // ratio
  $original_ratio = $image['sizex'] / $image['sizey'];
  $target_ratio = $box_width / $box_height;

  $scale = ($original_ratio >= $target_ratio ? $box_width / $image['sizex'] : $box_height / $image['sizey']);

  // calculate target dimensions
  $dest_width = round( $image['sizex'] * $scale );
  $dest_height = round( $image['sizey'] * $scale );

  // scale the image
  $new_image = imagecreatetruecolor($dest_width, $dest_height);
  imagecopyresampled($new_image, $current_image, 0, 0, 0, 0, $dest_width, $dest_height, $image['sizex'], $image['sizey']);

  return $new_image;
}
endif;

if( ! function_exists('dsgCropImage')):
/**
 * Crops an image, so its shorter dimension (in ratio sense) contains within a supplied dimensions box,
 * cuts away excess image dimension
 *
 * @param array $image Array containing image file data
 * @param int $box_width Width of target dimensions box
 * @param int $box_height Height of target dimensions box
 * @return resource Returns an image resource identifier on success
 * @author Devoth
 */
function dsgCropImage ($image, $box_width = 100, $box_height = 100) {

  $current_image = dsgLoadImage( $image );

  // check if image exceeds target box dimensions
  if ( $image['sizex'] < $box_width && $image['sizey'] < $box_height) {
    // if no - abort resizing, and pass original image instead
    return $current_image;
  }

  // ratio
  $original_ratio = $image['sizex'] / $image['sizey'];
  $target_ratio = $box_width / $box_height;

  // calculate crop parameters
  if ( $original_ratio >= $target_ratio ) {
    $dst_w = round( $box_height * $image['sizex'] / $image['sizey'] );
    $dst_h = $box_height;
  }
  else {
    $dst_w = $box_width;
    $dst_h = round( $box_width * $image['sizey'] / $image['sizex'] );
  }
  $dst_x = round( - ($dst_w - $box_width ) / 2 );
  $dst_y = round( - ($dst_h - $box_height ) / 2 );
  $src_x = 0;
  $src_y = 0;
  $src_w = $image['sizex'];
  $src_h = $image['sizey'];

  // crop the image
  $new_image = imagecreatetruecolor($box_width, $box_height);
  imagecopyresampled($new_image, $current_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

  return $new_image;
}
endif;

if( ! function_exists('dsgSaveImage')):
/**
 * Outputs image pixels to a destination file, based on file type
 *
 * @param resource $new_image Image resource identifier
 * @param string $image_path Destination to where image has to be saved, includes image name
 * @param int $image_type integer defining image type
 * @param string $image_basename Image file name including extension
 * @param int $compression Desired image compression - applies to JPEG files only
 * @throws Exception if image type is not supported, or writing to file fails
 * @return void
 * @author Devoth
 */
function dsgSaveImage($new_image, $destination_path, $image_type, $image_basename, $compression) {
  // save image to destination, save function depends on type
  switch ($image_type) {
    case IMAGETYPE_JPEG:
      if (! @imagejpeg($new_image, $destination_path, $compression) ) {
        throw new Exception ("Writing to file failed.");
      }
      break;
    case IMAGETYPE_GIF:
        if (! @imagegif($new_image, $destination_path) ) {
          throw new Exception ("Writing to file failed.");
        }
    break;
    case IMAGETYPE_PNG:
      if (! @imagepng($new_image, $destination_path) ) {
        throw new Exception ("Writing to file failed.");
      }
    break;
    default:
      throw new Exception('Unsupported image type in ' . $image_basename);
  }
}
endif;

if( ! function_exists('dsgGenerateThumbnails')):
/**
 * Creates thumbnails from an array of images and saves them to a directory,
 * thumbnails will be either scaled or cropped based chosen $dsg_thumb_operation
 *
 * @param array $images Array of images data
 * @param array $config Array containing config parameters
 * @return array Array with two indexes, first containing Array of images supplemented with thumbs data, second containing an Array of output messages
 * @author Devoth
 */
function dsgGenerateThumbnails( $images, $config ) {
  $output_messages = array();

  // create destination directory if nonexistent
  $destination_dir = $config['gallery_dir'] . '/' . $config['thumbs_dir'];
  ! is_dir($destination_dir) ? @mkdir($destination_dir) : '';

  foreach ($images as $key => $image) {
    $thumb_path =
      $destination_dir . '/' . $image['filename'] .'.'. $image['extension'];

    $image['thumbpath'] = $thumb_path;
    $image['thumbw'] = $config['thumb_width'];
    $image['thumbh'] = $config['thumb_height'];
    $images[$key] = $image;

    // don't create a thumb if it already exists and it's newer than the original
    if ( file_exists($thumb_path) && ( filemtime($image['fullpath']) < filemtime($thumb_path) || filectime($image['fullpath']) < filectime($thumb_path) ) ) {
      // if force_refresh is in play, image will be created
      if ( ! $config['force_refresh'] ) {
        continue;
      }
    }

    if ($config['thumb_operation'] == 'scale') {
      $thumb = dsgScaleImage($image, $config['thumb_width'], $config['thumb_height']);
    }
    else {
      $thumb = dsgCropImage($image, $config['thumb_width'], $config['thumb_height']);
    }
    dsgSaveImage( $thumb, $image['thumbpath'], $image['type'], $image['basename'], $config['compression'] );
    $output_messages[] = 'Created thumb for ' . $image['basename'] . '.';
  }
  return array( $images, $output_messages );
}
endif;

if ( ! function_exists('dsgGeneratePreviews') ):
/**
 * Creates previews from an array of images and saves them to a directory,
 * previews will be scaled
 *
 * @param array $images Array of images data
 * @param array $config Array containing config parameters
 * @return array Array with two indexes, first containing Array of images supplemented with previews data, second containing an Array of output messages
 * @author Devoth
 */
function dsgGeneratePreviews( $images, $config ) {
  $output_messages = array();

  // create destination directory if nonexistent
  $destination_dir = $config['gallery_dir'] . '/' . $config['previews_dir'];
  ! is_dir($destination_dir) ? @mkdir($destination_dir) : '';

  foreach ($images as $key => $image) {
    $preview_path =
      $destination_dir . '/' . $image['filename'] .'.'.  $image['extension'];

    $image['previewpath'] = $preview_path;
    $image['previeww'] = $config['preview_width'];
    $image['previewh'] = $config['preview_height'];
    $images[$key] = $image;

    // don't create a preview if it already exists and is newer than the original
    if ( file_exists($preview_path) && ( filemtime($image['fullpath']) < filemtime($preview_path) || filectime($image['fullpath']) < filectime($preview_path) ) ) {
      // if force_refresh is in play, image will be created
      if ( ! $config['force_refresh'] ) {
        continue;
      }
    }

    $preview = dsgScaleImage($image, $config['preview_width'], $config['preview_height']);
    dsgSaveImage( $preview, $image['previewpath'], $image['type'], $image['basename'], $config['compression'] );
    $output_messages[] = 'Created preview for ' . $image['basename'] . '.';
  }
  return array( $images, $output_messages );
}
endif;

if ( ! function_exists('dsgShowImages') ):
/**
 * Creates HTML code with thumbnails gallery, each one linking to it's preview,
 * generates either HTML or XHTML compliant tags, based on $dsg_xhtml global variable
 *
 * @param array $images Array containing arrays of image data
 * @param array $config Array containing congifuration parameters
 * @return void
 * @author Devoth
 */
function dsgShowImages($images, $config) {

  // prepare placeholder variables
  $placeholder_variables = array();
  $placeholder_variables['SELFCLOSE'] = $config['xhtml'] ? ' /' : '';
  $placeholder_variables['NEWLINE'] = "\n";

  $output = '';

  foreach ($images as $key => $image)
  {
    // get placholder variable values for current image
    $line_placeholder_variables = dsgGetImagePlaceholderVariables( $image, $config['filename_delimiter'] );
    $line_placeholder_variables['COUNTER'] = (int)($key + 1);

    // merge line placeholder variables with global (for all lines) placeholder variables
    $line_placeholder_variables = array_merge($line_placeholder_variables, $placeholder_variables);

    // get placeholders into a single array
    $line_patterns = array_keys($line_placeholder_variables);

    // convert placeholder names array into patterns array
    array_walk( $line_patterns, create_function('&$v,$k', '$v = "/(?<!\{)\{$v}/";') );

    // get placeholder values (replacements) into single array
    $line_replacements = array_values($line_placeholder_variables);

    // replace placeholders with apropriate values
    $line_output = preg_replace($line_patterns, $line_replacements, $config['line_output_pattern']);

    // clean double curly brackets
    $line_output = preg_replace('/\{\{([\w]+)}}/', '{$1}', $line_output);
    $output.= $line_output;
  }

  echo $output;
}
endif;

if ( ! function_exists('dsgGetImagePlaceholderVariables') ) {
  /**
   * Return an array of placeholder values for a given image
   *
   * @param array $image_data Array containing image data like path, filename, size, etc
   * @param string $filename_delimiter String containing a delimiter used to explode parts of file name into variables
   * @return array Array of placeholder variables, placeholder name as array keys, placeholder value as array values
   * @author Devoth
   **/
  function dsgGetImagePlaceholderVariables( $image_data, $filename_delimiter ) {
    $placeholder_variables = array();

    $thumb_size = getimagesize( $image_data['thumbpath'] );
    $preview_size = getimagesize( $image_data['previewpath'] );

    $thumb_path_info = pathinfo($image_data['thumbpath']);
    $preview_path_info = pathinfo($image_data['previewpath']);

    $placeholder_variables['ORIGINAL_FILENAME_HUMANIZED'] = dsgHumanize( $image_data['filename'] );

    $placeholder_variables['ORIGINAL_URL'] = $image_data['fullpath'];
    $placeholder_variables['ORIGINAL_FILENAME'] = $image_data['filename'];
    $placeholder_variables['ORIGINAL_BASENAME'] = $image_data['basename'];
    $placeholder_variables['ORIGINAL_EXT'] = $image_data['extension'];
    $placeholder_variables['ORIGINAL_WIDTH'] = $image_data['sizex'];
    $placeholder_variables['ORIGINAL_HEIGHT'] = $image_data['sizey'];

    $placeholder_variables['THUMB_URL'] = $image_data['thumbpath'];
    $placeholder_variables['THUMB_FILENAME'] = basename( $thumb_path_info['basename'], '.' . $thumb_path_info['extension'] );
    $placeholder_variables['THUMB_BASENAME'] = $thumb_path_info['basename'];
    $placeholder_variables['THUMB_EXT'] = $thumb_path_info['extension'];
    $placeholder_variables['THUMB_BOXWIDTH'] = $image_data['thumbw'];
    $placeholder_variables['THUMB_BOXHEIGHT'] = $image_data['thumbh'];
    $placeholder_variables['THUMB_WIDTH'] = $thumb_size[0];
    $placeholder_variables['THUMB_HEIGHT'] = $thumb_size[1];

    $placeholder_variables['PREVIEW_URL'] = $image_data['previewpath'];
    $placeholder_variables['PREVIEW_FILENAME'] = basename( $preview_path_info['basename'], '.' . $thumb_path_info['extension'] );
    $placeholder_variables['PREVIEW_BASENAME'] = $preview_path_info['basename'];
    $placeholder_variables['PREVIEW_EXT'] = $preview_path_info['extension'];
    $placeholder_variables['PREVIEW_BOXWIDTH'] = $image_data['previeww'];
    $placeholder_variables['PREVIEW_BOXHEIGHT'] = $image_data['previewh'];
    $placeholder_variables['PREVIEW_WIDTH'] = $preview_size[0];
    $placeholder_variables['PREVIEW_HEIGHT'] = $preview_size[1];


    /* get variables from file name and add them to $placeholder_variables, as VAR_1 to VAR_10 */

    // extract variables from filename
    $variable_values = explode( $filename_delimiter, $image_data['filename'] );

    // let's make sure the array with variables has always 10 items
    $variable_values = $variable_values + array_fill( 0, 10, '' );

    // create keys array prefilled with 'VAR_'
    $variable_keys = array_fill( 0, 10, 'VAR_' );

    // add number to each 'VAR_' in keys array
    array_walk( $variable_keys, create_function('&$v,$k', '$v.= ($k+1);'));

    // merge $placeholder_variables array with exploaded filename vars (combined from keys & values arrays)
    $placeholder_variables += array_combine($variable_keys, $variable_values);

    return $placeholder_variables;
  }
}

// track script execution time
$dsg_starttime = dsgExecutionTimeTrackingStart();

try {
  // get images
  $images = dsgGetImages( $dsg_config );

  // check if images dir is writable
  if ( ! is_writable($dsg_gallery_dir) ) {
    throw new Exception('Please make gallery directory "' . $dsg_gallery_dir . '" writeable');
  }

  // generate thumbnails
  list ($images, $tmp_output_messages) = dsgGenerateThumbnails( $images, $dsg_config );

  // add resulting messages to global messages array
  $dsg_output_messages = array_merge( $dsg_output_messages, $tmp_output_messages );

  // generate previews
  list ($images, $tmp_output_messages) = dsgGeneratePreviews( $images, $dsg_config );

  // add resulting messages to global messages array
  $dsg_output_messages = array_merge( $dsg_output_messages, $tmp_output_messages );

  // generate and print (X)HTML output
  dsgShowImages( $images, $dsg_config );

} catch (Exception $e) {
  echo '<p class="errorMsg">' . $e->getMessage() . '</p>';
}

// calculate script execution time
$dsg_output_messages['execution_time'] = dsgExecutionTimeTrackingEnd( $dsg_starttime );

// unset variables
unset($dsg_gallery_dir);
unset($dsg_thumbs_dir);
unset($dsg_previews_dir);
unset($dsg_allow_gif);
unset($dsg_allow_jpg);
unset($dsg_allow_png);
unset($dsg_preview_width);
unset($dsg_preview_height);
unset($dsg_thumb_width);
unset($dsg_thumb_height);
unset($dsg_thumb_operation);
unset($dsg_compression);
unset($dsg_force_refresh);
unset($dsg_filename_delimiter);
unset($dsg_line_pattern);

// there is only one parameter that isn't getting unset: xhtml
// once you declare it - it will be used for all galleries in a document
// unless of course you set it to something else manually
