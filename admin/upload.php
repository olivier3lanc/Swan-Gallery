<?php 
/**
* ADMIN UPLOAD
* Backend upload page
* Swan Gallery
* @version 0.9
* @author Olivier Blanc http://www.egalise.com/swan-gallery
* @license MIT
*/

    $admin='../';
    //header("Cache-Control: no-cache, must-revalidate");
    include ('../engine/config.php');
    include ('../engine/functions.php');
    include ('config.php');
    include ('functions.php');
    admin_session();
    //Gallery Language
    if(file_exists('languages/'.$gallery_admin_language.'.php')){
        include 'languages/'.$gallery_admin_language.'.php';
    }else{
        //Default Language
        include 'languages/en_EN.php';
    }
    $script_path=dirname(getcwd());
    //Complete array of photos in the gallery directory
    $all_files = scan($script_path.'/'.$gallery_folder, '', '', '', '');
    //Get tag list
    $tags=tag_list('../'.$gallery_folder, 1000, '');
    $menu_active='upload';
?><!DOCTYPE HTML>
<html>
<head>
<!-- Force latest IE rendering engine or ChromeFrame if installed -->
<!--[if IE]>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<![endif]-->
<meta charset="utf-8">
<title><?php echo $lang['admin.upload.title'] ?></title>
<meta name="description" content="<?php echo $lang['admin.upload.description'] ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php include 'includes/sw-css.php' ?> 
</head>
<body>

<?php include 'includes/sw-navbar.php' ?>


<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-default mgtop30">
                <div class="panel-heading"><strong><?php echo $lang['admin.upload.current.parameters'] ?></strong></div>

                <ul class="list-group">
                    <li class="list-group-item">
                        <span class="pull-left"><?php echo ucfirst($lang['general.current.gallery.directory']) ?></span> 
                        <strong class="pull-right"><?php echo $gallery_folder ?></strong>
                        <span class="clearfix invisible"></span>
                    </li>
                    <li class="list-group-item">
                        <span class="pull-left"><?php echo ucfirst($lang['general.current.gallery.template']) ?></span> 
                        <strong class="pull-right"><?php echo $tpl_title ?></strong>
                        <span class="clearfix invisible"></span>
                    </li>
                    <li class="list-group-item">
                        <span class="pull-left"><?php echo ucfirst($lang['general.current.gallery.resize.method']) ?></span> 
                        <strong class="pull-right">
                        <?php if($tpl_thumb_crop == true) : ?>
                            <?php echo ucfirst($lang['general.crop']) ?>
                        <?php else : ?>
                            <?php echo ucfirst($lang['general.scale']) ?>
                        <?php endif ?>
                        </strong>
                        <span class="clearfix invisible"></span>
                    </li>
                    <li class="list-group-item">
                        <span class="pull-left"><?php echo ucfirst($lang['general.current.thumbnails.sizes']) ?></span> 
                        <strong class="pull-right"><?php echo $tpl_thumb_width ?> x <?php echo $tpl_thumb_height ?> px</strong>
                        <span class="clearfix invisible"></span>
                    </li>
                    <li class="list-group-item">
                        <span class="pull-left"><?php echo ucfirst($lang['general.current.previews.sizes']) ?></span> 
                        <strong class="pull-right"><?php echo $tpl_medium_width ?> x <?php echo $tpl_medium_height ?> px</strong>
                        <span class="clearfix invisible"></span>
                    </li>
                </ul>
            </div>  <!-- ./Summary Panel -->     

            <div class="panel panel-default mgtop30">
                <div class="panel-heading"><strong><?php echo ucfirst($lang['general.help']) ?></strong></div>
                <div class="panel-body">
                    <ul>
                        <li><?php echo $lang['admin.upload.description.addon.1'] ?></li>
                        <li><?php echo $lang['admin.upload.description.addon.2'] ?> <a href="parameters.php#templates" title="<?php echo $lang['admin.upload.description.addon.2.tooltip'] ?>"><?php echo $lang['admin.parameters.title'] ?></a>.</li>
                        <li><?php echo $lang['admin.upload.description.addon.3'] ?> <a href="./?sortby=ph_date_created&order=DESC" title="<?php echo $lang['admin.upload.description.addon.3.tooltip'] ?>"><?php echo $lang['general.swan.gallery.editor'] ?></a></li>
                    </ul>
                </div>
            </div>  <!-- ./Help Panel -->    
        </div>
        <div class="col-md-9">
            <h1 class="mgbot30"><?php echo ucfirst($lang['admin.upload.title']) ?></h1>

            <p class="mgbot30"><?php echo $lang['admin.upload.description'] ?></p>

                <!-- The file upload form used as target for the file upload widget -->
                <form id="fileupload" action="//jquery-file-upload.appspot.com/" method="POST" enctype="multipart/form-data">
                    <!-- Redirect browsers with JavaScript disabled to the origin page -->
                    <noscript><input type="hidden" name="redirect" value="./"></noscript>
                    <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                    <div class="row fileupload-buttonbar">
                        <div class="col-md-12 mgbot20">
                            <!-- The fileinput-button span is used to style the file input field as button -->
                            <span class="btn btn-success fileinput-button">
                                <i class="glyphicon glyphicon-plus"></i>
                                <span><?php echo $lang['admin.upload.button.add.files'] ?></span>
                                <input type="file" name="files[]" multiple>
                            </span>
                            <button type="submit" class="btn btn-primary start">
                                <i class="glyphicon glyphicon-upload"></i>
                                <span><?php echo $lang['admin.upload.button.start.upload'] ?></span>
                            </button>
                            <button type="reset" class="btn btn-warning cancel">
                                <i class="glyphicon glyphicon-ban-circle"></i>
                                <span><?php echo $lang['admin.upload.button.cancel.upload'] ?></span>
                            </button>
                            <button type="button" class="btn btn-danger delete" title="<?php echo $lang['admin.upload.button.delete.selected.files.tooltip'] ?>">
                                <i class="glyphicon glyphicon-trash"></i>
                                <span><?php echo $lang['admin.upload.button.delete.selected.files'] ?></span>
                            </button>
                            <input type="checkbox" title="<?php echo $lang['admin.upload.button.select.all'] ?>" class="toggle">
                            <a  href="./?sortby=ph_date_created&order=DESC" 
                                class="btn btn-default pull-right"
                                title="<?php echo $lang['admin.upload.button.view.last.uploaded.tooltip'] ?>"><?php echo $lang['admin.upload.button.view.last.uploaded'] ?></a>
                        </div>
                        <div class="col-md-12">
                            <!-- The global file processing state -->
                            <span class="fileupload-process"></span>
                        </div>
                        <!-- The global progress state -->
                        <div class="col-md-12 fileupload-progress fade mgbot20">
                            <!-- The global progress bar -->
                            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                            </div>
                            <!-- The extended global progress state -->
                            <div class="progress-extended">&nbsp;</div>
                        </div>
                    </div>
                    <!-- The table listing the files available for upload/download -->
                    <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
                </form>
                <p class="text-muted text-center mgtop50 mgbot50"><?php echo $lang['general.powered.by'] ?> <a href="<?php echo $lang['general.swan.gallery.url'] ?>" title="<?php echo $lang['general.swan.gallery.description'] ?>" class="text-muted"><?php echo $lang['general.swan.gallery.title'] ?></a></p>
            </div>
        </div><!--./row -->
</div>
<!-- The blueimp Gallery widget -->
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>


<footer>
  <div class="container-fluid">

  </div>
</footer>

<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <div class="row">
                <div class="col-sm-12 col-md-4 col-lg-3">
                    <span class="preview"></span>
                </div>
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <p class="name">{%=file.name%}</p>
                    <strong class="error text-danger"></strong>
                </div>
                <div class="col-sm-12 col-md-2 col-lg-2">
                    <p class="size"><?php echo $lang['admin.upload.processing'] ?></p>
                    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                        <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-3 text-right">
                    {% if (!i && !o.options.autoUpload) { %}
                        <button class="btn btn-primary start" disabled>
                            <i class="glyphicon glyphicon-upload"></i>
                            <span><?php echo $lang['admin.upload.button.start'] ?></span>
                        </button>
                    {% } %}
                    {% if (!i) { %}
                        <button class="btn btn-warning cancel">
                            <i class="glyphicon glyphicon-ban-circle"></i>
                            <span><?php echo $lang['admin.upload.button.cancel'] ?></span>
                        </button>
                    {% } %}
                </div>
            </div>
        </td>
    </tr>
{% } %}
</script> 


<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <div class="row">
                <div class="col-sm-12 col-md-4 col-lg-3">
                    <span class="preview">
                        {% if (file.thumbnailUrl) { %}
                            <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}" class="img-thumbnail"></a>
                        {% } %}
                    </span>
                </div>
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <p class="name">
                        {% if (file.url) { %}
                            <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                        {% } else { %}
                            <span>{%=file.name%}</span>
                        {% } %}
                    </p>
                    {% if (file.error) { %}
                        <div><span class="label label-danger"><?php echo $lang['admin.upload.error'] ?></span> {%=file.error%}</div>
                    {% } %}
                </div>
                <div class="col-sm-12 col-md-2 col-lg-2">
                    <span class="size">{%=o.formatFileSize(file.size)%}</span>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-3 text-right">
                    {% if (file.deleteUrl) { %}
                        <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                            <i class="glyphicon glyphicon-trash"></i>
                            <span><?php echo $lang['admin.upload.button.delete'] ?></span>
                        </button>
                        <input type="checkbox" name="delete" value="1" class="toggle">
                    {% } else { %}
                        <button class="btn btn-warning cancel">
                            <i class="glyphicon glyphicon-ban-circle"></i>
                            <span><?php echo $lang['admin.upload.button.cancel'] ?></span>
                        </button>
                    {% } %}
                </div>
            </div>
        </td>
    </tr>
{% } %}
</script>
<hr>


<?php include 'includes/sw-js.php' ?>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="js/load-image.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="js/canvas-to-blob.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="js/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="js/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="js/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="js/jquery.fileupload-ui.js"></script>
<!-- The main application script -->
<script src="js/main.js"></script>

</body> 
</html>
