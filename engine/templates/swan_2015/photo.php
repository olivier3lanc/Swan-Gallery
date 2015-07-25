<?php
/**
* SWAN 2015
* Photo page template
* Swan Gallery
* @version 0.9
* @author Olivier Blanc http://www.egalise.com/swan-gallery/
* @license MIT
*/
?><!DOCTYPE HTML>
<html lang="<?php echo substr($tpl_parameters['language'], 0, 2) ?>">
    <head>
        <!-- Force latest IE rendering engine or ChromeFrame if installed -->
        <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
        <title><?php echo $page_title ?></title>
        <meta name="description" content="<?php echo substr($files[0]['ph_description'], 0, 150).' ...'; ?>">
<?php if($gallery_credit_display == '1') : ?>
        <meta name="author" content="<?php echo $gallery_credit ?>">
<?php endif ?>
        <meta name="generator" content="Swan Gallery">

        <!-- Schema.org markup for Google+ -->
        <meta itemprop="name" content="<?php echo $page_title ?>">
        <meta itemprop="description" content="<?php echo substr($files[0]['ph_description'], 0, 150).' ...'; ?>">
        <meta itemprop="image" content="<?php echo $files[0]['ph_file_url'] ?>">
        
        <!-- Open Graph data -->
        <meta property="og:title" content="<?php echo $page_title ?>">
        <meta property="og:type" content="website">
        <meta property="og:url" content="<?php echo $script_url.'?photo='.$files[0]['ph_file'] ?>">
        <meta property="og:image" content="<?php echo $files[0]['ph_medium_url'] ?>">
        <meta property="og:description" content="<?php echo substr($files[0]['ph_description'], 0, 150).' ...'; ?>">
        <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-css.php'; ?>
        
        <!-- Javascript disabled -->
        <noscript>
            <style type="text/css">
                #preloader, #spin { display: none }
            </style>
        </noscript>

        <!-- HEAD JS -->
        <script type="text/javascript" src="engine/templates/<?php echo $gallery_template ?>/js/head.min.js"></script>
        <script>
        head.load(  'engine/templates/<?php echo $gallery_template ?>/js/jquery-1.11.0.min.js',
                    'engine/templates/<?php echo $gallery_template ?>/js/spin.min.js',
                    'engine/templates/<?php echo $gallery_template ?>/js/packery.pkgd.min.js',
                    'engine/templates/<?php echo $gallery_template ?>/js/bootstrap.min.js',
                    'engine/templates/<?php echo $gallery_template ?>/js/jquery.blueimp-gallery.min.js');
        </script>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <?php echo $gallery_statistics_code ?>
    </head>
    <body>
        <div id="preloader">
            <div id="spin"></div>
        </div>
        <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-navbar.php'; ?>
        <div class="sw-main-container pdbot30">
<?php if($files[0]['ph_file_ratio'] < 1) : //If portrait ?>
            <div class="container">
                <div class="row pdtop30">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-photo.php'; ?>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <h1 class="page-header"><?php echo $page_title ?></h1>
                        <div class="row">
    <?php if(!empty($keywords)) : ?>
                            <div class="col-md-12 sw-photo-tags">
                                <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-photo-tags.php'; ?>
                                <hr>
                            </div>
    <?php endif //Keywords ?>
    <?php if(!empty($files[0]['ph_description'])) : ?>
                            <div class="col-md-12">
                                <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-description.php'; ?>
                                <hr>
                            </div>
    <?php endif //Description ?>
    <?php if($tpl_parameters['socials'] == '1') :?>
                            <div class="col-md-12">
                                <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-socials.php'; ?>
                                <hr>
                            </div>
    <?php endif //Socials ?>
                            <div class="col-md-12">
                                <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-exif.php'; ?>
                            </div>
    <?php if(!empty($related) && $gallery_related_limit !== 'none') : ?>
                            <div class="col-md-12">
                                <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-related.php'; ?>
                            </div>
    <?php endif ?>
    <?php if($tpl_parameters['comments_switch'] == '1') : ?>
                            <div class="col-md-12 mgtop30">
                                <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-comments.php'; ?>
                            </div>
    <?php endif //Comments ?>
                        </div><!--./row-->
                    </div><!--./col-md-6-->
                </div><!--./row-->
            </div><!--./container-->

<?php elseif($files[0]['ph_file_ratio'] > 2) : //If panorama ?>
            
            <div class="container-fluid">
                <div class="row pdtop15">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-photo.php'; ?>
                            </div>
                        </div><!--./row-->
                    </div><!--./col-md-12-->
                </div><!--./row-->
            </div><!--./container-fluid-->

            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header"><?php echo $page_title ?></h1>
                    </div>
    <?php if(!empty($keywords)) : ?>
                    <div class="col-md-12 sw-photo-tags">
                        <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-photo-tags.php'; ?>
                        <hr>
                    </div>
    <?php endif //Keywords ?>
                    <div class="<?php if(!empty($related) && $gallery_related_limit !== 'none') : echo 'col-md-6 col-sm-6'; else : echo 'col-md-12 col-sm-12'; endif ?> col-xs-12">
    <?php if(!empty($files[0]['ph_description'])) : ?>
                        <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-description.php'; ?>
                        <hr>
    <?php endif //Description ?>
    <?php if($tpl_parameters['socials'] == '1') :?>
                        <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-socials.php'; ?>
                        <hr>
    <?php endif //Socials ?>
                        <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-exif.php'; ?>
    <?php if($tpl_parameters['comments_switch'] == '1') : ?>
                        <div class="mgtop30">
                            <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-comments.php'; ?>
                        </div>
    <?php endif //Comments ?>
                    </div><!--./first column -->
    <?php if(!empty($related) && $gallery_related_limit !== 'none') : ?>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-related.php'; ?>
                        </div>
    <?php endif ?>
                </div><!--./row-->
            </div><!--./container-->
    
<?php else : //If landscape ?>
            <div class="container">
                <div class="row pdtop15">
                    <div class="col-md-12 text-center">
                        <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-photo.php'; ?>
                    </div>
                    <div class="col-md-12">
                        <h1 class="page-header"><?php echo $page_title ?></h1>
                    </div>
    <?php if(!empty($keywords)) : ?>
                    <div class="col-md-12 sw-photo-tags">
                        <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-photo-tags.php'; ?>
                        <hr>
                    </div>
    <?php endif //Keywords ?>
                    <div class="<?php if(!empty($related)) : echo 'col-md-6 col-sm-6'; else : echo 'col-md-12 col-sm-12'; endif ?> col-xs-12">
    <?php if(!empty($files[0]['ph_description'])) : ?>
                        <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-description.php'; ?>
                        <hr>
    <?php endif //Description ?>
    <?php if($tpl_parameters['socials'] == '1') :?>
                        <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-socials.php'; ?>
                        <hr>
    <?php endif //Socials ?>
                        <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-exif.php'; ?>

    <?php if($tpl_parameters['comments_switch'] == '1') : ?>
                        <div class="mgtop30">
                            <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-comments.php'; ?>
                        </div>
    <?php endif //Comments ?>
                    </div>
    <?php if(!empty($related) && $gallery_related_limit !== 'none') : ?>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-related.php'; ?>
                    </div>
    <?php endif ?>
                </div><!--./row-->
            </div><!--./container-->
<?php endif ?>
        </div><!--./sw-container-->
        
        <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-footer.php'; ?>
        <!-- JS files in the template js directory -->
        <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-js.php'; ?>
    </body>
</html>