<?php if(!empty($featured) && $tpl_parameters['carousel_position'] == 1 && $tpl_parameters['carousel'] == 1 && count($featured) !== 0) : ?>
        <div class="sw-isotope-block sw-isotope-special col-xs-12 col-sm-12 col-md-12 col-lg-6">
            <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-carousel.php'; ?>
        </div>
<?php endif ?>
<?php foreach($files as $photo) :?>
    <?php if($photo['ph_file_ratio'] >= 2 && $tpl_parameters['panoramas'] == 'wide') : ?>
        <div class="sw-isotope-block col-md-12">
            <div class="sw-item">
                <div class="sw-thumb-container">
                    <a  href="<?php echo $photo['ph_file_url'] ?>"
                        title="<?php echo $photo['ph_title'] ?>">
                        <img    src="<?php echo $photo['ph_file_url'] ?>"
                                alt="<?php echo $photo['ph_title'] ?>"
                                class="sw-the-thumb">
                    </a>
                </div>
                <div class="sw-thumb-layer gradient">
                    <p class="sw-title-over-thumb">
                        <a  href="?photo=<?php echo $photo['ph_file'] ?>"
                            title="<?php echo $photo['ph_title'] ?>">
                            <?php echo $photo['ph_title'] ?>
                        </a>
                    </p>
                    <a  href="?photo=<?php echo $photo['ph_file'] ?>"
                        title="<?php echo $photo['ph_title'] ?>"
                        class="sw-link-over-thumb">
                    </a>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="sw-isotope-block col-xs-6 col-sm-6 col-md-4 col-lg-3">
            <div class="sw-item <?php if($tpl_parameters['transition_opacity']=='yes') : echo 'transition-opacity'; endif ?>">
                <div class="sw-thumb-container">
                    <a  href="<?php echo $photo['ph_file_url'] ?>"
                        title="<?php echo $photo['ph_title'] ?>">
                        <img    src="<?php echo $photo['ph_thumb_url'] ?>"
                                alt="<?php echo $photo['ph_title'] ?>"
                                class="sw-the-thumb">
                    </a>
                </div>
                <div class="sw-thumb-layer gradient">
                    <p class="sw-title-over-thumb">
                        <a  href="?photo=<?php echo $photo['ph_file'] ?>"
                            title="<?php echo $photo['ph_title'] ?>">
                            <?php echo $photo['ph_title'] ?>
                    </a>
                    </p>
                        <a  href="?photo=<?php echo $photo['ph_file'] ?>"
                            title="<?php echo $photo['ph_title'] ?>"
                            class="sw-link-over-thumb">
                    </a>
                </div>
            </div>
        </div>
    <?php endif ?>
<?php endforeach ?>