			<div class="row">
			    <div class="col-md-12">
			        <h2><?php echo $tpl_lang['front.related'] ?></h2>
			    </div>
<?php foreach($related as $related_photo => $data) :?>
			    <div class="col-md-3 col-sm-6 col-xs-3 pdright0 pdbot10">
			        <p class="sw-related-thumb">
			        	<a href="?photo=<?php echo $related_photo ?>" title="<?php echo $data['ph_title'] ?>"><img src="<?php echo $data['ph_thumb_url'] ?>" alt="<?php echo $data['ph_title'] ?>" class="img-thumbnail"></a>
			        </p>
			    </div>
<?php endforeach ?>
			</div>