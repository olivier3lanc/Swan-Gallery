
<!-- jQuery -->
<script src="js/jquery-1.11.0.min.js"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<script src="js/bootstrap.min.js"></script>
<!-- blueimp Gallery script -->
<script src="js/jquery.blueimp-gallery.min.js"></script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="js/vendor/jquery.ui.widget.js"></script>
<!-- Tag input -->
<script src="js/jquery.tagsinput.min.js"></script>
<!-- jQuery Masked Input -->
<script src="js/jquery.maskedinput.min.js"></script>
<!-- Bootstrap datepicker / Adding days, months and years variables for backend language -->
<script>
//Days full
var langSunday = "<?php echo ucfirst($lang['general.date.sunday']) ?>";
var langMonday = "<?php echo ucfirst($lang['general.date.monday']) ?>";
var langTuesday = "<?php echo ucfirst($lang['general.date.tuesday']) ?>";
var langWednesday = "<?php echo ucfirst($lang['general.date.wednesday']) ?>";
var langThursday = "<?php echo ucfirst($lang['general.date.thursday']) ?>";
var langFriday = "<?php echo ucfirst($lang['general.date.friday']) ?>";
var langSaturday = "<?php echo ucfirst($lang['general.date.saturday']) ?>";

//Days short
var langSun = "<?php echo ucfirst($lang['general.date.sun']) ?>";
var langMon = "<?php echo ucfirst($lang['general.date.mon']) ?>";
var langTue = "<?php echo ucfirst($lang['general.date.tue']) ?>";
var langWed = "<?php echo ucfirst($lang['general.date.wed']) ?>";
var langThu = "<?php echo ucfirst($lang['general.date.thu']) ?>";
var langFri = "<?php echo ucfirst($lang['general.date.fri']) ?>";
var langSat = "<?php echo ucfirst($lang['general.date.sat']) ?>";

//Days min
var langSu = "<?php echo ucfirst($lang['general.date.su']) ?>";
var langMo = "<?php echo ucfirst($lang['general.date.mo']) ?>";
var langTu = "<?php echo ucfirst($lang['general.date.tu']) ?>";
var langWe = "<?php echo ucfirst($lang['general.date.we']) ?>";
var langTh = "<?php echo ucfirst($lang['general.date.th']) ?>";
var langFr = "<?php echo ucfirst($lang['general.date.fr']) ?>";
var langSa = "<?php echo ucfirst($lang['general.date.sa']) ?>";

//Months full
var langJanuary = "<?php echo ucfirst($lang['general.date.january']) ?>";
var langFebruary = "<?php echo ucfirst($lang['general.date.february']) ?>";
var langMarch = "<?php echo ucfirst($lang['general.date.march']) ?>";
var langApril = "<?php echo ucfirst($lang['general.date.april']) ?>";
var langMay = "<?php echo ucfirst($lang['general.date.may']) ?>";
var langJune = "<?php echo ucfirst($lang['general.date.june']) ?>";
var langJuly = "<?php echo ucfirst($lang['general.date.july']) ?>";
var langAugust = "<?php echo ucfirst($lang['general.date.august']) ?>";
var langSeptember = "<?php echo ucfirst($lang['general.date.september']) ?>";
var langOctober = "<?php echo ucfirst($lang['general.date.october']) ?>";
var langNovember = "<?php echo ucfirst($lang['general.date.november']) ?>";
var langDecember = "<?php echo ucfirst($lang['general.date.december']) ?>";

//Months short
var langJan = "<?php echo ucfirst($lang['general.date.jan']) ?>";
var langFeb = "<?php echo ucfirst($lang['general.date.feb']) ?>";
var langMar = "<?php echo ucfirst($lang['general.date.mar']) ?>";
var langApr = "<?php echo ucfirst($lang['general.date.apr']) ?>";
var langMay = "<?php echo ucfirst($lang['general.date.may']) ?>";
var langJun = "<?php echo ucfirst($lang['general.date.jun']) ?>";
var langJul = "<?php echo ucfirst($lang['general.date.jul']) ?>";
var langAug = "<?php echo ucfirst($lang['general.date.aug']) ?>";
var langSep = "<?php echo ucfirst($lang['general.date.sep']) ?>";
var langOct = "<?php echo ucfirst($lang['general.date.oct']) ?>";
var langNov = "<?php echo ucfirst($lang['general.date.nov']) ?>";
var langDec = "<?php echo ucfirst($lang['general.date.dec']) ?>";
</script>
<script src="js/bootstrap-datepicker.js"></script>
<!-- jQuery Form -->
<script src="js/jquery.form.min.js"></script>
<!-- Alertify -->
<script src="js/alertify.js"></script>
<!-- jQuery autocomplete -->
<script src="js/jquery.autocomplete.min.js"></script>
<!-- Nice scrollbars -->
<script src="js/jquery.nicescroll.min.js"></script>
<!-- jQuery Redirect -->
<script src="js/jquery.redirect.js"></script>
<!-- Select2 -->
<script src="js/select2.min.js"></script>
<!-- Hotkeys -->
<script src="js/jquery.hotkeys.js"></script>
<script type="text/javascript">
/*
* FUNCTIONS
*/

//Rebuild thumbnails processing notifications and results
var theRebuild = function() {
  jQuery('#rebuild').toggle();
  jQuery('#log').html('<div class="alert alert-warning" role="alert"><img src="img/rebuilding.gif" alt="Loader"> <?php echo $lang["admin.rebuild.alert.rebuilding"] ?></div>');
  jQuery.ajax({
    url: 'includes/sw-build.php',
    success: function (data) {
      // this is executed when ajax call finished well
      jQuery('#log').html(data);
      jQuery('#log').prepend('<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-ok"></span> <?php echo $lang["admin.rebuild.alert.rebuild.success"] ?></div>');
      jQuery('#rebuild').toggle();
    },
    error: function (xhr, status, error) {
      // executed if something went wrong during call
      //if (xhr.status > 0) alert('got error: ' + status); // status 0 - when load is interrupted
      alert('Error');
    }
  });
};

//Delete cache
var deleteCache = function() {
  jQuery.ajax({
    url: 'includes/sw-delete-cache.php',
    success: function (data) {
      // this is executed when ajax call finished well
      jQuery('#delete-cache-modal .modal-body .alert').removeClass('alert-warning').addClass('alert-success').html('<span class="glyphicon glyphicon-ok"></span> <?php echo $lang["admin.parameters.delete.cache.modal.alert.emptied"] ?>');
    },
    error: function (xhr, status, error) {
      alert('Error');
    }
  });
};

//Show edit notification on the edited photo row 
var editAlert = function(theRow) {
  jQuery(theRow).find('.edit-alert').show();
};

/*
* DOCUMENT READY 
*/
jQuery(document).ready(function() { 

  //Rebuild thumbnails and previews button click
  jQuery("#rebuild").on('click', theRebuild);

  //Delete cache modal: Empty cache on button click
  jQuery('#delete-cache-button').on('click', function(){
    jQuery('#delete-cache-modal .modal-body .alert').show().removeClass('alert-success').addClass('alert-warning').html('<img src="img/rebuilding.gif" alt="Loader"> <?php echo $lang["admin.parameters.delete.cache.modal.alert.emptying"] ?>');
    deleteCache();
  });

  //Delete cache modal: Hide cache delete alert on modal exit
  jQuery('#delete-cache-modal').on('hidden.bs.modal', function (e) {
    jQuery('#delete-cache-modal .modal-body .alert').hide();
  });

  //Justify height of photo tabs
  jQuery('.photo-row').each(function(){
    var mainHeight = jQuery(this).find('.tab-main').height();
    jQuery(this).find('.tab-favorite-to-tags, .tab-informations').height(mainHeight+15);
  });

  //Select2 select form beautifier
  jQuery("select").select2({
    dropdownAutoWidth: true
  });

  //Open omnisearch on key combination
  jQuery(document).bind('keydown', 'ctrl+alt', function(){
    jQuery(".navbar select").select2('open');
  });
  

  //Multiple search box in the navbar
  jQuery("#omnisearch").select2({
    placeholder: "<?php echo $lang['admin.navbar.omnisearch.placeholder'] ?>",
    //If entered text does not match to anything, send POST['search'] data to the index.php page
    formatNoMatches: function(term) {
      //When "enter" is stroken, validate and send POST data
      jQuery('.select2-input').keyup(function(e) {
        if(e.keyCode == 13) {
            //jQuery redirect send POST['search'] data
            jQuery.redirect('./', {'search': term});
        }
      });
      return "<?php echo $lang['admin.navbar.omnisearch.no.match'] ?>";
    }
  });

  //Auto enter when a choice is made in the select menu
  //Auto save on input button click
  jQuery("select, .btn-group input").change(function() {
    var origin = jQuery(this).closest('form');
    origin.find('button.quick-save').click();
  });

  //Tags input
	jQuery(function() {
		jQuery('.ph_keywords').tagsInput({
			autocomplete_url:'includes/sw-tag-list.php',
			autocomplete:{selectFirst:true, width:'auto', autoFill:false},
			width: 'auto',
			height: 'auto',
      defaultText: '<?php echo $lang["admin.editor.add.a.tag"] ?>'
		});
	});

  //Toggle display of favorites to tags
  jQuery('.btn-trig').on('click', function(){
    var theTarget = jQuery(this).attr('data-target');
    jQuery(theTarget).toggle();
  });
  
  //jQuery Masked Input
  jQuery(".datepicker").mask("9999-99-99");

  //Bootstrap datepicker
  jQuery('.datepicker').datepicker();
  
	//jQuery Form Save File
	jQuery('.sw-save-file').ajaxForm(function() { 
		alertify.success("<?php echo $lang['admin.editor.photo.saved'] ?>");
	}); 

  //On photo save button
  jQuery('.sw-save-photo').on('click', function(){
    jQuery(this).closest('.photo-row').find('.edit-alert').hide();
  })

	//jQuery Form Delete File
	jQuery('.sw-delete-file').ajaxForm(function() { 
		alertify.success("<?php echo $lang['admin.editor.photo.deleted'] ?>");
		jQuery('.modal').modal('hide');
	}); 

  //Hide the row of the file that was just deleted
  jQuery('.sw-delete-file button').on('click', function(){
    var id = jQuery(this).attr('data-id');
    jQuery('#'+id).hide();
  });

  //When click on "apply" template, reload the page
  jQuery('.btn-template-apply').on('click', function(){
        jQuery.redirect('parameters.php#templates');
  });

	//Auto scrollbars
	jQuery(".sidebar").niceScroll({ horizrailenabled: false });

  //Lock scroll in delected div
  jQuery('.tab-informations').bind( 'mousewheel DOMMouseScroll', function ( e ) {
    var e0 = e.originalEvent,
        delta = e0.wheelDelta || -e0.detail;
    this.scrollTop += ( delta < 0 ? 1 : -1 ) * 30;
    e.preventDefault();
  });

  //On edit change, show notification
  jQuery('.photo-row input, .photo-row textarea').on('change', function(){
    jQuery(this).closest('.photo-row').find('.edit-alert').show();
  });

});

/*
* WHEN LOAD COMPLETED
*/
jQuery(window).load(function() {
    jQuery('.navbar-form').animate({opacity:'1'},300);
});
</script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!--[if (gte IE 8)&(lt IE 10)]>
<script src="js/cors/jquery.xdr-transport.js"></script>
<![endif]-->