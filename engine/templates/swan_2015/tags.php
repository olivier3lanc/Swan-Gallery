<?php
/**
* SWAN 2015
* Tags page template
* Swan Gallery
* @version 0.9
* @author Olivier Blanc http://www.egalise.com/swan-gallery
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
        <meta name="description" content="<?php echo $page_description ?>">
<?php if($gallery_credit_display == '1') : ?>
        <meta name="author" content="<?php echo $gallery_credit ?>">
<?php endif ?>
        <meta name="generator" content="Swan Gallery">
        <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-css.php'; ?>
        
        <!-- Javascript disabled -->
        <noscript>
        <style type="text/css">
            #preloader, #spin { display: none }
        </style>
        </noscript>
        
        <!--[if gte IE 9]>
        <style type="text/css">
            .gradient {
            filter: none;
            }
        </style>
        <![endif]-->
        
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
        <div class="container-fluid sw-main-container">
            <div class="row">
                <div class="col-md-1">
                </div>
                <div class="col-md-10">
                    <h1 class="page-header"><?php echo $page_title ?></h1>
<?php if(empty($_POST) && empty($get_array) && $tpl_parameters['carousel_position'] == 0 && $tpl_parameters['carousel'] == 1) : ?>
                    <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-carousel.php'; ?>
<?php endif ?>

                    <div id="sw-main" class="row">
                        <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-tags.php'; ?>
                    </div>
                </div>
                <div class="col-md-1">
                </div>
                <div class="col-md-12">
                    <!--Separator-->
                </div>
                <div class="col-md-1">
                </div>
<?php if($gallery_tag_limit !== '999' && $all_tags_count>$gallery_tag_limit) : ?>
                <div class="col-md-10 text-center pdbot30">
                    <hr>
    <?php if(isset($_GET['all_tags'])) : ?>
                    <a href="./?tags" title="<?php echo $tpl_lang['front.popular.tags.tooltip'] ?>" class="btn btn-default"><?php echo $tpl_lang['front.popular.tags'] ?></a>
    <?php else : ?>
                    <a href="./?all_tags" title="<?php echo $tpl_lang['front.all.tags.tooltip'] ?>" class="btn btn-default"><?php echo $tpl_lang['front.all.tags'] ?></a>
    <?php endif ?>
                </div>
<?php endif ?>
                <div class="col-md-1">
                </div>
            </div>
        </div>
        <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-footer.php'; ?>
        <!-- JS files in the template js directory -->
        <?php include $script_path.'/engine/templates/'.$gallery_template.'/includes/sw-js.php'; ?>
    </body>
</html>