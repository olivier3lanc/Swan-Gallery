            <nav>
                <div class="navbar navbar-inverse" role="navigation">
                    <div class="container<?php if($tpl !== 'photo.php') : echo '-fluid'; endif ?>">
                        <div class="row">
                            <?php if($tpl !== 'photo.php') : echo '<div class="col-md-1"></div>'; endif ?>
                            <div class="col-md-<?php if($tpl !== 'photo.php') : echo '10'; else : echo '12'; endif ?>">
                                <div class="navbar-header">
                                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#collapsed">
                                        <span class="sr-only"><?php echo $tpl_lang['front.toggle.navigation'] ?></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button>
                                    <a class="navbar-brand" href="./"><?php echo $gallery_title ?></a>
                                </div>
                                <div class="collapse navbar-collapse" id="collapsed">
                                    <form class="navbar-form navbar-right" action="./" method="post" role="search">
                                        <input type="text" name="search" class="form-control" placeholder="<?php echo $tpl_lang['front.search'] ?>...">
                                    </form>
                                    <ul class="nav navbar-nav navbar-right mgright20">
<?php if($gallery_homepage_type !== 'tags') : ?>
    <?php if(!empty($tags)) : ?>
                                        <li><a href="./?tags" title="<?php echo $tpl_lang['front.tags.tooltip'] ?>"><?php echo $tpl_lang['front.tags'] ?></a></li>
    <?php endif ?>
    <?php else : ?>
                                        <li><a href="./?gallery" title="<?php echo $tpl_lang['front.explore.tooltip'] ?>"><?php echo $tpl_lang['front.explore'] ?></a></li>
<?php endif ?>
                                    </ul>
                                </div>
                            </div>
                            <?php if($tpl !== 'photo.php') : echo '<div class="col-md-1"></div>'; endif ?>
                        </div>
                    </div><!-- ./container-fluid -->
                </div>
            </nav>