<footer>
    <div id="sw-footer">
        <div class="container<?php if($tpl !== 'photo.php') : echo '-fluid'; endif ?>" id="sw-footer-main">
            <div class="row">
<?php if($tpl !== 'photo.php') : echo '<div class="col-md-1"></div>'; endif ?>
                <div class="col-md-<?php if($tpl !== 'photo.php') : echo '10'; else : echo '12'; endif ?>">
                    <div class="row">
                        <div class="col-md-4">
                            <h4><?php echo $gallery_title ?></h4>
                            <hr>
    <?php if(!empty($tpl_parameters['custom_input'])) : ?>
                            <p><?php echo $tpl_parameters['custom_input'] ?></p>
    <?php else : ?>
                            <p><?php echo $gallery_description ?></p>
    <?php endif ?>
                        </div>
                        <div class="col-md-4">
                            <div class="visible-sm pdbot30"></div>
                            <h4 class="hidden-xs"><?php echo $tpl_lang['front.popular.tags'] ?></h4>
                            <hr>
                            <p class="visible-xs">
                                <a  href="./?tags"
                                    title="<?php echo $tpl_lang['front.tags.tooltip'] ?>"
                                    class="btn btn-default">
                                    <span class="glyphicon icon-tags"></span>
                                    <?php echo $tpl_lang['front.tags'] ?>
                                </a>
                            </p>
                            <div class="hidden-xs">
    <?php if(!empty($tags)) : ?>
                                <ul>
        <?php foreach(array_slice($tags, 0, $gallery_tag_limit) as $tag => $key) : ?>
                                    <li>
                                        <a  href="./?tag=<?php echo flatten($tag) ?>"
                                            rel="tag"
                                            title="<?php echo $tpl_lang['front.photo.tagged'].' '.$tag ?>">
                                            <?php echo $tag ?>
                                            <span class="text-muted">(<?php echo $key ?>)</span>
                                        </a>
                                    </li>
        <?php endforeach ?>
        <?php if($gallery_tag_limit !== '999' && $all_tags_count>$gallery_tag_limit) : ?>
                                    <li><a href="./?all_tags" title="<?php echo $tpl_lang['front.all.tags.tooltip'] ?>"><?php echo $tpl_lang['front.all.tags'] ?></a></li>
        <?php endif ?>
                                </ul>
    <?php endif ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="visible-sm pdbot30"></div>
                            <h4 class="hidden-xs"><?php echo $tpl_lang['front.keep.in.touch'] ?></h4>
                            <hr>
    <?php if(!empty($gallery_facebook_page) || !empty($gallery_twitter) || !empty($gallery_rss_entries)) : ?>
                            <p class="sw-socials">
                            <?php if(!empty($gallery_facebook_page)) : ?>
                            <a  href="<?php echo $gallery_facebook_page ?>"
                            title="<?php echo $tpl_lang['front.facebook.page.tooltip'] ?>"
                            class="btn btn-default">
                            <span class="glyphicon icon-facebook"></span>
                            </a>
    <?php endif ?>
    <?php if(!empty($gallery_twitter)) : ?>
                            <a  href="<?php echo $gallery_twitter ?>"
                            title="<?php echo $tpl_lang['front.twitter.tooltip'] ?>"
                            class="btn btn-default">
                            <span class="glyphicon icon-twitter"></span>
                            </a>
    <?php endif ?>
    <?php if(!empty($gallery_email)) : ?>
                            <a  href="#"
                            title="<?php echo $tpl_lang['front.email.me.tooltip'] ?>"
                            data-toggle="modal"
                            data-target="#contact-modal"
                            class="btn btn-default"
                            id="launch-modal">
                            <span class="glyphicon icon-envelope"></span>
                            </a>
    <?php endif ?>
    <?php if(!empty($gallery_rss_entries)) : ?>
                            <a  href="./feed/"
                            title="<?php echo $tpl_lang['front.rss.feed.tooltip'] ?>"
                            class="btn btn-default">
                            <span class="glyphicon icon-feed">
                            </a>
    <?php endif ?>
                            </p>
<?php endif ?>
                        </div>
                    </div>
                </div>

                <?php if($tpl !== 'photo.php') : echo '<div class="col-md-1"></div>'; endif ?>
                <div class="col-md-12">
                    <div class="visible-sm pdbot30"></div>
                </div>
            </div>
        </div>
        <div class="container<?php if($tpl !== 'photo.php') : echo '-fluid'; endif ?>" id="sw-footer-sub">
            <div class="row">
                <?php if($tpl !== 'photo.php') : echo '<div class="col-md-1"></div>'; endif ?>
                <div class="col-md-<?php if($tpl !== 'photo.php') : echo '10'; else : echo '12'; endif ?>">
                    <hr>
                    <div class="row">
                        <div class="col-xs-6">
<?php if($gallery_credit_display == '1') : ?>
                            <p class="text-left">&copy; <a href="./" title="Homepage"><?php echo $gallery_credit ?></a></p>
<?php endif ?>
                        </div>
                        <div class="col-xs-6">
                            <p class="text-right"><?php echo $powered_by ?> <a href="http://www.egalise.com/swan-gallery/" title="<?php echo $swan_gallery_tooltip ?>">Swan Gallery</a></p>
                        </div>
                    </div>
                </div>
                <?php if($tpl !== 'photo.php') : echo '<div class="col-md-1"></div>'; endif ?>
            </div>
        </div>
    </div>
</footer>


<!-- Contact Modal -->
<div class="modal fade" id="contact-modal" tabindex="-1" role="dialog" aria-labelledby="ContactModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $tpl_lang['front.close'] ?></span></button>
                <h4 class="modal-title"><?php echo $tpl_lang['front.contact'] ?></h4>
            </div>
            <div class="modal-body">
                <p><?php echo $tpl_lang['front.contact.text'] ?></p>
                <p><strong id="contact-email"></strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $tpl_lang['front.close'] ?></button>
            </div>
        </div>
    </div>
</div>