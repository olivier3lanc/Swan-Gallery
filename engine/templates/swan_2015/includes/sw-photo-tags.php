

				<div class="sw-photo-tags">
					<span class="glyphicon icon-tags"></span>
<?php foreach($keywords as $tag => $rank) :?>
				      	<a 	href="?tag=<?php echo flatten($tag) ?>"
				      		rel="tag"
				      		title="<?php echo $tpl_lang['front.photo.tagged'].' '.$tag ?>" 
				      		class="label label-default">
				      		<?php echo $tag ?>
				      	</a> 
<?php endforeach ?>
				</div>	
