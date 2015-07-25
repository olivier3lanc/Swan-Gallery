                <div class="sw-photo-full-size img-responsive" id="sw-photo-full-size">
                    <a  href="<?php echo $files[0]['ph_file_url'] ?>"
                        title="<?php echo $files[0]['ph_title'] ?>">
                        <img    src="<?php echo $files[0]['ph_file_url'] ?>"
                                alt="<?php echo $files[0]['ph_title'] ?>"
                                class="img-thumbnail">
                    </a>
                    <div class="sw-photo-layer">
                        <a  href="<?php echo $files[0]['ph_file_url'] ?>"
                            title="<?php echo $tpl_lang['front.toggle.size'] ?>"
                            class="sw-toggle-size"
                            data-gallery>
                            <span class="glyphicon icon-expand"></span>
                        </a>
<?php if(!empty($previous)) : ?>
                        <a  href="./?photo=<?php echo $previous ?>"
                            title="<?php echo $tpl_lang['front.previous.photo'] ?>"
                            class="sw-previous-link">
                            <span class="glyphicon icon-arrow-left2"></span>
                        </a>
<?php endif ?>
<?php if(!empty($next)) : ?>
                        <a  href="./?photo=<?php echo $next ?>"
                            title="<?php echo $tpl_lang['front.next.photo'] ?>"
                            class="sw-next-link">
                            <span class="glyphicon icon-arrow-right2"></span>
                        </a>
<?php endif ?>
                    </div>
                </div>