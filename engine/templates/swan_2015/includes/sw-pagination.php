<?php if(!empty($pagination_enabled)) : ?>
                <ul class="pagination pagination-lg">
    <?php if(isset($_GET['tag'])) : ?>
                    <li class="<?php if(empty($_GET['page'])) : echo 'active'; endif ?>"><a href="./?tag=<?php echo $_GET['tag'] ?>">1</a></li>
    <?php else : ?>
                    <li class="<?php if(empty($_GET['page'])) : echo 'active'; endif ?>"><a href="./">1</a></li>
    <?php endif ?>
    <?php if(is_array($pages)) : ?>
        <?php foreach($pages as $page) : ?>
                    <li class="<?php echo $page['class'] ?>">
                    	<a href="<?php echo $page['url'] ?>"><?php echo $page['page'] ?></a>
                    </li>
        <?php endforeach ?>
    <?php endif ?>
                </ul>
<?php endif ?>