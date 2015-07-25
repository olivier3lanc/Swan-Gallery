<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">

  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".menu-xs">
        <span class="sr-only"><?php echo $lang['general.toggle.navigation'] ?></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

      <a  class="navbar-brand" 
          href="./" 
          title="<?php echo $lang['admin.navbar.editor.tooltip'] ?>">
          <img alt="Swan Gallery logo" src="img/logo-swan-navbar.png">
          <span class="hidden-xs"><?php echo $lang['general.swan.gallery.editor'] ?></span> 
          <span class="badge badge-primary"><?php echo count_total('') ?></span>
      </a>

      <form class="navbar-form navbar-left op0" role="search" action="./" method="POST">
        <select class="" id="omnisearch" name="search" title="<?php echo $lang['admin.navbar.omnisearch.tooltip'] ?>">
          <option></option>
          <optgroup label="<?php echo $lang['admin.navbar.admin.pages'] ?>">
            <option value="sw-editor"><?php echo ucfirst($lang['general.editor']) ?></option>
            <option value="sw-featured"><?php echo ucfirst($lang['general.featured']) ?></option>
            <option value="sw-favorites-to-tags"><?php echo ucfirst($lang['general.favorites.to.tags']) ?></option>
            <option value="swantag-no_tag"><?php echo ucfirst($lang['general.not.tagged']) ?></option>
            <option value="sw-upload"><?php echo $lang['admin.navbar.upload.and.delete'] ?></option>
            <option value="sw-parameters"><?php echo $lang['admin.parameters.title'] ?></option>
            <option value="sw-rebuild"><?php echo $lang['admin.rebuild.title'] ?></option>
            <option value="sw-password"><?php echo $lang['admin.password.title'] ?></option>
          </optgroup>
          <optgroup label="<?php echo ucfirst($lang['general.tags']) ?>">
          <?php foreach($tags as $tag => $value) : ?>
            <option value="swantag-<?php echo flatten($tag) ?>"><?php echo $tag ?></option>
          <?php endforeach ?>
          </optgroup>
          <optgroup label="<?php echo ucfirst($lang['general.titles']) ?>">
          <?php foreach($all_files as $file) : ?>
            <option value="<?php echo $file['ph_title'] ?>"><?php echo $file['ph_title'] ?></option>
          <?php endforeach ?>
          </optgroup>
        </select>
        <button type="submit" class="btn btn-default quick-save none"><?php echo ucfirst($lang['general.submit']) ?></button>
      </form>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse menu-xs">
      
      <ul class="nav navbar-nav visible-xs menu-xs">
        <li class="dropdown">
          <a  href="#" 
              class="dropdown-toggle" 
              data-toggle="dropdown">
              <span class="glyphicon glyphicon-edit"></span> 
              <span><?php echo ucfirst($lang['general.editor']) ?> </span> 
              <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" role="menu">
            <li class="visible-xs <?php if($menu_active == 'editor') : echo 'active'; endif ?>">
              <a  href="./" 
                  title="<?php echo $lang['admin.navbar.editor.tooltip'] ?>">
                  <span class="glyphicon glyphicon-picture"></span> 
                  <?php echo $lang['general.all.female'] ?> 
                  <span class="badge"><?php echo count_total('') ?></span>
              </a>
            </li>

            <li class="visible-xs <?php if(isset($_GET['featured'])) : echo 'active'; endif ?>">
              <a  href="?featured" 
                  title="<?php echo $lang['admin.navbar.featured.tooltip'] ?>">
                  <span class="glyphicon glyphicon-star"></span> 
                  <?php echo $lang['general.featured'] ?> 
                  <span class="badge badge-primary"><?php echo count($featured) ?></span>
              </a>
            </li>

            <li class="visible-xs <?php if(isset($_GET['favorites_to_tags'])) : echo 'active'; endif ?>">
              <a  href="?favorites_to_tags" 
                  title="<?php echo $lang['admin.navbar.favorites.to.tags.tooltip'] ?>">
                  <span class="glyphicon glyphicon-tags"></span> 
                  <?php echo $lang['general.favorites.to.tags'] ?> 
                  <span class="badge badge-primary"><?php echo count($favorites_to_tags) ?></span>
              </a>
            </li>

            <?php if(count($not_tagged) != NULL) : ?>

            <li class="<?php if(isset($_GET['tag']) && $_GET['tag']=='no_tag') : echo 'active'; endif ?>">
              <a  href="?tag=no_tag" 
                  title="<?php echo $lang['admin.navbar.not.tagged.tooltip'] ?> ">
                  <span class="glyphicon glyphicon-thumbs-down"></span> 
                  <?php echo $lang['general.not.tagged'] ?> 
                  <span class="badge badge-primary"><?php echo count($not_tagged) ?></span>
              </a>
            </li>

            <?php endif ?>
          </ul>
        </li>
      </ul>


      <ul class="nav navbar-nav">
        <li class="<?php if($menu_active=='upload'){ echo 'active'; } ?>">
          <a  href="upload.php" 
              title="<?php echo $lang['admin.navbar.upload.and.delete.tooltip'] ?>">
              <span class="glyphicon glyphicon-cloud-upload"></span> 
              <span class="hidden-sm hidden-md"><?php echo $lang['admin.navbar.upload.and.delete'] ?></span>
          </a>
        </li>
        <li>
          <a  href="../" 
              target="_blank" 
              title="<?php echo $lang['admin.navbar.view.gallery.tooltip'] ?>">
              <span class="glyphicon glyphicon-share-alt"></span> 
              <span class="hidden-sm hidden-md"><?php echo $lang['admin.navbar.view.gallery'] ?></span>
          </a>
        </li>
      </ul>

      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a  href="#" 
              class="dropdown-toggle" 
              data-toggle="dropdown"
              title="<?php echo $lang['admin.navbar.setup.tooltip'] ?>">
              <span class="glyphicon glyphicon-cog"></span> 
              <span class="hidden-sm hidden-md"><?php echo $lang['admin.navbar.setup'] ?></span>
              <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" role="menu">
            <li class="<?php if($menu_active=='parameters'){ echo 'active'; } ?>">
              <a  href="parameters.php"
                  title="<?php echo $lang['admin.parameters.description'] ?>">
                  <span class="glyphicon glyphicon-cog"></span> 
                  <?php echo $lang['admin.parameters.title'] ?>
              </a>
            </li>
            <li>
              <a  href="parameters.php#templates"
                  title="<?php echo $lang['admin.parameters.description'] ?>">
                  <span class="glyphicon glyphicon-th-list"></span> 
                  <?php echo $lang['admin.parameters.templates'] ?>
              </a>
            </li>
            <li class="<?php if($menu_active=='rebuild-thumbnails'){ echo 'active'; } ?>">
              <a  href="rebuild-thumbnails.php"
                  title="<?php echo $lang['admin.navbar.rebuild.tooltip'] ?>">
                  <span class="glyphicon glyphicon-picture"></span> 
                  <?php echo $lang['admin.rebuild.title'] ?>
              </a>
            </li>
            <li class="<?php if($menu_active=='password'){ echo 'active'; } ?>">
              <a  href="password.php"
                  title="<?php echo $lang['admin.navbar.password.tooltip'] ?>">
                  <span class="glyphicon glyphicon-lock"></span> 
                  <?php echo $lang['admin.password.title'] ?>
              </a>
            </li>
            <li class="divider"></li>
            <li>
              <a  href="login.php?logout"
                  title="<?php echo $lang['admin.navbar.log.out.tooltip'] ?>">
                  <span class="glyphicon glyphicon-log-out"></span> 
                  <?php echo $lang['admin.navbar.log.out'] ?>
              </a>
            </li>
          </ul>
        </li>
        
        
        
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>