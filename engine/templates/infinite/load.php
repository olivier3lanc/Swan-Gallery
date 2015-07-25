<?php
	/**
	* INFINITE
	* AJAX load
	* Swan Gallery
	* @version 0.9.1
	* @author Olivier Blanc http://www.egalise.com/swan-gallery/
	* @license MIT
	*/
?>
<?php foreach($files as $photo) : ?>
						<div class="row mgbot100">
							<div class="col-md-12" id="<?php echo $photo['ph_id'] ?>">
								<article>
									<div class="infinite-img-container">
										<p class="text-center">
	<?php if(!isset($_GET['photo']) && $tpl_parameters['photo_link'] == 'yes') : ?>
										<a href="./?photo=<?php echo $photo['ph_file'] ?>">
	<?php endif ?>
											<img 	class="lazy infinite-img <?php 	
														if($photo['ph_file_ratio'] < 1) {
															echo 'portrait';
														}elseif($photo['ph_file_ratio'] >= 1 && $photo['ph_file_ratio'] <= 1.3) {
															echo 'square';
														}elseif($photo['ph_file_ratio'] > 1.3 && $photo['ph_file_ratio'] < 2) {
															echo 'landscape';
														}elseif($photo['ph_file_ratio'] >= 2) {
															echo 'panorama';
														}?>" 
													
													src="<?php echo $photo['ph_thumb_url'] ?>" 
													alt="<?php echo $photo['ph_title'] ?>"
													width="<?php echo $photo['ph_file_width'] ?>" 
													height="<?php echo $photo['ph_file_height'] ?>"
													data-original="<?php echo $photo['ph_file_url'] ?>" 
													data-ratio="<?php echo $photo['ph_file_ratio'] ?>"
													style="max-width:<?php echo $photo['ph_file_width'] ?>px">
	<?php if(!isset($_GET['photo']) && $tpl_parameters['photo_link'] == 'yes') : ?>
										</a>
	<?php endif ?>
											</p>
									</div>
									<div class="row">
										<div class="col-md-2">
										</div>
										<div class="col-md-8 text-center">
	<?php if(isset($_GET['photo'])) : ?>
											<!-- PHOTO TITLE PAGE -->
											<h1 class="infinite-img-title mgbot20">
	<?php else : ?>
											<!-- PHOTO TITLE GALLERY -->
											<h3 class="infinite-img-title mgbot20">
	<?php endif ?>
												<?php echo str_replace('_', ' ', $photo['ph_title']) ?>
	<?php if($tpl_parameters['sharing_zone'] == 'yes') : ?>
												<a 	href="<?php echo $script_url.'?photo='.$photo['ph_file'] ?>" 
													rel="nofollow" 
													title="<?php echo $tpl_parameters['lang_toggle_sharing_area'] ?>" 
													class="pdleft20 btn-toggle-share">
													<span class="icon-share"></span>
	<?php endif ?>
												</a>
	<?php if(isset($_GET['photo'])) : ?>
											</h1>
	<?php else : ?>
											</h3>
	<?php endif ?>

	<?php if($tpl_parameters['sharing_zone'] == 'yes') : ?>
											<!-- SHARING ZONE -->
											<div class="form-inline infinite-sharing-zone mgbot20 none">
												<div class="form-group">		
													<div class="input-group">
														<a 	class="input-group-addon btn btn-facebook" 
															href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $script_url.'?photo='.$photo['ph_file'] ?>"
															title="<?php echo $tpl_parameters['lang_share_on_facebook'] ?>"
															target="_blank">
															Facebook
														</a>
														<a 	class="input-group-addon btn btn-twitter" 
															href="https://twitter.com/intent/tweet?text=<?php echo urlencode($photo['ph_title']) ?>&button_hashtag=<?php echo flatten($gallery_title) ?>&url=<?php echo $script_url.'?photo='.$photo['ph_file'] ?>"
															title="<?php echo $tpl_parameters['lang_share_on_link'] ?>"
															target="_blank">
															Twitter
														</a>
														<input 	type="text" 
																class="input-sm form-control" 
																value="<?php echo $script_url.'?photo='.$photo[ 'ph_file'] ?>"
																title="<?php echo $tpl_parameters['lang_share_on_twitter'] ?>">
														<a 	class="input-group-addon btn btn-default btn-click-to-copy" 
															title="<?php echo $tpl_parameters['lang_share_on_link'] ?>">
															<?php echo $tpl_parameters['lang_link'] ?>
														</a>
													</div>
												</div>
											</div>
											<!-- ./SHARING ZONE -->
	<?php endif ?>
											
	<?php if($tpl_parameters['tags_in_gallery'] == 'yes' || isset($_GET['photo'])) : ?>
		<?php if(!empty($photo['ph_keywords'])) : ?>
											<!-- PHOTO TAGS -->
											<p class="text-center infinite-img-tags">
												<span class="icon-tag"></span> 
			<?php foreach($photo['ph_keywords'] as $keyword) : ?>
												<a 	href="./?tag=<?php echo flatten($keyword) ?>" 
													rel="tag" 
													class="btn btn-primary btn-sm"
													title="<?php echo $tpl_parameters['lang_photos_tagged'].' '.$keyword ?>"><?php echo $keyword ?></a>
			<?php endforeach ?>
											</p>
		<?php endif ?>
	<?php endif ?>

	<?php if($tpl_parameters['description_in_gallery'] == 'yes' || isset($_GET['photo'])) : ?>
											<!-- PHOTO DESCRIPTION -->
											<p class="infinite-img-description"><?php echo $photo['ph_description'] ?></p>
	<?php endif ?>


	<?php if(isset($_GET['photo']) && !empty($related) && $gallery_related_limit !== 'none') : ?>
											<!-- RELATED -->
											<div id="infinite-related" class="mgtop50">
												<h3><?php echo $tpl_parameters['lang_related_photos'] ?></h3>
												<div class="text-center">
		<?php foreach($related as $related_photo => $data) :?>
													<p style="display:inline-block; background:url(<?php echo $data['ph_medium_url'] ?>) center no-repeat; background-size:cover; height:150px; width:150px; margin:8px">
														<a 	href="?photo=<?php echo $related_photo ?>" 
															title="<?php echo $data['ph_title'] ?>"
															style="display:block; width:100%; height:100%"><span class="none"><?php echo $data['ph_title'] ?></span></a>
													</p>
		<?php endforeach ?>
												</div>
											</div>
											<!-- ./RELATED -->
	<?php endif ?>

	<?php if(isset($_GET['photo']) && $tpl_parameters['comments_switch'] == 'yes') : ?>
											<!-- COMMENTS -->
											<div id="infinite-comments" class="mgtop50">
												<?php echo $tpl_parameters['comments_code'] ?>
											</div>
											<!-- ./COMMENTS -->
	<?php endif ?>
										</div>
									</div>
								</article>
							</div>
						</div><!-- ./row -->
<?php endforeach ?>

<?php if(!empty($pagination_enabled)) : ?>
	<?php if($files_count>$gallery_photos_per_page && $target_page <= $total_pages ) : ?>
						<!-- AJAX PAGINATION -->
						<p class="text-center">
							<a 	href="#" 
								data-url="<?php if(!empty($final_url)) : echo $final_url; else : echo '?load'; endif ?>" 
								class="btn btn-primary btn-lg load-more">
								<?php echo $tpl_parameters['lang_load_more_photos'] ?>
							</a>
						</p>
						<!-- ./AJAX PAGINATION -->
	<?php endif ?>
<?php endif ?>

