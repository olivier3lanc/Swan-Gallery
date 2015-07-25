<?php if(!empty($files[0]['ph_exif']) && $tpl_parameters['exif'] == '1') :?>
                <div class="sw-photo-exif">
                    <p>
    <?php if(!empty($files[0]['ph_date'])) :?>
                    <span class="glyphicon icon-calendar"></span> <?php echo date_formatted($files[0]['ph_date'],'-') ?> |
    <?php endif ?>
                    <span class="glyphicon glyphicon-camera"></span>
    <?php if(!empty($files[0]['ph_exif']['ExposureTime'])) :?>
                    <span><?php echo $files[0]['ph_exif']['ExposureTime'] ?> sec</span> |
    <?php endif ?>

    <?php if(!empty($files[0]['ph_exif']['FNumber'])) :?>
                    <span>f<?php echo $files[0]['ph_exif']['FNumber'] ?></span> |
    <?php endif ?>

    <?php if(!empty($files[0]['ph_exif']['ISOSpeedRatings'])) :?>
                    <span>ISO <?php echo $files[0]['ph_exif']['ISOSpeedRatings'] ?></span> |
    <?php endif ?>

    <?php if(!empty($files[0]['ph_exif']['FocalLength'])) :?>
                    <span>Focal length <?php echo $files[0]['ph_exif']['FocalLength'] ?>mm</span> |
    <?php endif ?>

    <?php if(!empty($files[0]['ph_exif']['Model'])) :?>
                    <span><?php echo $files[0]['ph_exif']['Model'] ?></span> |
    <?php endif ?>

    <?php if(!empty($files[0]['ph_exif']['Lens'])) :?>
                    <span>Lens <?php echo $files[0]['ph_exif']['Lens'] ?></span>
    <?php endif ?>
                    </p>
                </div>
<?php endif ?>