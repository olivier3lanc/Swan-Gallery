        <div id="carousel-example-generic" class="<?php if($tpl_parameters['carousel_position'] == 0) : echo 'mgbot30'; endif ?> carousel slide" data-ride="carousel">
<?php if(count($featured)>1) : ?>
            <!-- Indicators -->
            <ol class="carousel-indicators">
    <?php foreach($featured as $key => $data) : ?>
                <li   data-target="#carousel-example-generic"
                    data-slide-to="<?php echo $key ?>"
                    class="<?php if($key == 0) : echo 'active'; endif ?>">
                </li>
    <?php endforeach ?>
            </ol>
<?php endif ?>
            <!-- Wrapper for slides -->
            <div class="carousel-inner">
<?php foreach($featured as $key => $featured_photo) : ?>
                <div class="item <?php if($key == 0) : echo 'active'; endif ?>">
                    <a  href="./?photo=<?php echo $featured_photo['ph_file'] ?>"
                    title="<?php echo $tpl_lang['front.see.photo.page.tooltip'] ?>">
    <?php if($tpl_parameters['carousel_position'] == 1) : ?>
                    <img src="<?php echo $featured_photo['ph_medium_url'] ?>" alt="<?php echo $featured_photo['ph_title'] ?>">
    <?php else : ?>
                    <img src="<?php echo $featured_photo['ph_file_url'] ?>" alt="<?php echo $featured_photo['ph_title'] ?>">
    <?php endif ?>
                    </a>
                    <div class="carousel-caption">
                        <h3 class="hidden-xs"><?php echo $featured_photo['ph_title'] ?></h3>
                    </div>
                </div>
<?php endforeach ?>
            </div>
<?php if(count($featured)>1) : ?>
            <!-- Controls -->
            <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev" title="<?php echo $tpl_lang['front.previous'] ?>">
            <span></span>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next" title="<?php echo $tpl_lang['front.next'] ?>">
            <span></span>
            </a>
<?php endif ?>
        </div>