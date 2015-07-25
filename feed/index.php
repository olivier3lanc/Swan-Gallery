<?php
/**
* RSS FEED
* Swan Gallery
* @version 0.9
* @author Olivier Blanc http://www.egalise.com/swan-gallery
* @license MIT
*/
	$admin='../';
	include ('../engine/config.php');
  	include ('../engine/functions.php');
  	if($gallery_rss_entries == 'none'){
  		header('Location: ../');
  	}
	$script_path=dirname(getcwd());
	$files = scan($script_path.'/'.$gallery_folder, 'feed/', '', '', '');
	$rss = array_slice($files, 0, $gallery_rss_entries);
	$gallery_url = str_replace('feed/', '', $script_url);
	header ("Content-Type:text/xml");
	echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<title><?php echo $gallery_title ?></title>
		<link><?php echo $gallery_url ?></link>
		<description><?php echo strip_tags($gallery_description) ?></description>
		<generator>Swan Gallery</generator>
		<atom:link href="<?php echo $script_url ?>" rel="self" type="application/rss+xml" />
<?php foreach($rss as $photo) : ?>
		<item>
			<title><?php echo strip_tags($photo['ph_title']) ?></title>
			<link><?php echo $gallery_url.'?photo='.$photo['ph_file'] ?></link>
			<description>
			    <?php echo '<![CDATA[ <a href="'.$gallery_url.'?photo='.$photo['ph_file'].'"><img src="'.$photo['ph_thumb_url'].'" alt="" /></a> <p>'.substr(strip_tags($photo['ph_description']), 0, 500).' ... <a href="'.$gallery_url.'?photo='.$photo['ph_file'].'">Read more</a></p> ]]>'; ?>
			</description>
		<?php foreach($photo['ph_keywords'] as $tag) : ?>
			<category domain="<?php echo $gallery_url.'?tag='.flatten($tag); ?>"><?php echo $tag ?></category>
		<?php endforeach ?>
		</item>
<?php endforeach ?>
	</channel>
</rss>