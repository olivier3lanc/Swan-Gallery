<?php if(!empty($featured) && $tpl_parameters['carousel_position'] == 1 && $tpl_parameters['carousel'] == 1 && count($featured) !== 0) : ?>
            <div class="sw-isotope-block sw-isotope-special col-md-12 col-lg-6">
                <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-carousel.php'; ?>
            </div>
<?php endif ?>
<?php foreach($files as $the_tag => $photo) :?>
            <div class="sw-isotope-block col-xs-6 col-sm-6 col-md-4 col-lg-3">
                <div class="sw-item">
                    <div class="sw-thumb-container">
                        <a  href="?tag=<?php echo flatten($the_tag); ?>"
                            rel="tag"
                            title="<?php echo $tpl_lang['front.photo.tagged'].' '.$the_tag ?>">
                            <img src="<?php echo $photo['ph_thumb_url'] ?>" alt="<?php echo $photo['ph_title'] ?>" class="sw-the-thumb">
                        </a>
                    </div>
                    <div class="sw-thumb-layer gradient">
                        <p class="sw-title-over-thumb">
                            <a  href="?tag=<?php echo flatten($the_tag); ?>"
                                rel="tag"
                                title="<?php echo $tpl_lang['front.photo.tagged'].' '.$the_tag ?>">
                                <?php echo $the_tag ?>
                            </a>
                            <span class="badge badge-default"><?php echo $all_tags[$the_tag] ?></span>
                        </p>
                        <a  href="?tag=<?php echo flatten($the_tag); ?>"
                            title="<?php echo $tpl_lang['front.photo.tagged'].' '.$the_tag ?>"
                            class="sw-link-over-thumb">
                        </a>
                    </div>
                </div>
            </div>
<?php endforeach ?>