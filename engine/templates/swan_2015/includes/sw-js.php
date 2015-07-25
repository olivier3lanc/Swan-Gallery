<!-- The blueimp Gallery widget -->
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <div class="slides"></div>
    <!-- <h3 class="title"></h3> -->
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <!-- <a class="play-pause"></a> -->
    <!-- <ol class="indicator"></ol> -->
</div>

<script type="text/javascript">
	/*
	* FUNCTIONS
	*/

	//Previous Next buttons over the full size photo on the photo page
	var previousNext = function(){
		if(jQuery('.sw-photo-full-size').length !== 0) {
			var photoHeight = jQuery('.sw-photo-full-size img').height();
			var photoWidth = jQuery('.sw-photo-full-size img').width();
			var photoPosition = jQuery('.sw-photo-full-size img').position();
			var buttonHeight = jQuery('.sw-previous-link').height();
			var ButtonYPosition = photoPosition.top + photoHeight / 2 - buttonHeight / 2 + 6;
			jQuery('.sw-previous-link').css({left:20+'px', top:ButtonYPosition+'px'});
			jQuery('.sw-next-link').css({right:20+'px', top:ButtonYPosition+'px'});
			jQuery('.sw-photo-layer').css({left:photoPosition.left+'px', top:photoPosition.top+'px', width:photoWidth+10+'px', height:photoHeight+10+'px'});
		}
	};

	/*
	* PRELOADER
	*/
	var opts = {
					lines: 13, // The number of lines to draw
					length: 60, // The length of each line
					width: 10, // The line thickness
					radius: 30, // The radius of the inner circle
					corners: 1, // Corner roundness (0..1)
					rotate: 0, // The rotation offset
					direction: 1, // 1: clockwise, -1: counterclockwise
					color: '#888', // #rgb or #rrggbb
					speed: 1, // Rounds per second
					trail: 60, // Afterglow percentage
					shadow: false, // Whether to render a shadow
					hwaccel: false, // Whether to use hardware acceleration
					className: 'spinner', // The CSS class to assign to the spinner
					zIndex: 2e9, // The z-index (defaults to 2000000000)
					top: 'auto', // Top position relative to parent in px
					left: 'auto' // Left position relative to parent in px
	};

</script>


<script type="text/javascript">
head.ready(function () {


	/*
	* DOM READY
	*/

	//Preloader
	var target = document.getElementById('spin');
	var spinner = new Spinner(opts).spin(target);
	
	// Packery container
	var $container = jQuery('#sw-main');

	//Slider
	jQuery('.carousel').carousel();
	
<?php if(isset($secure_email)) : ?>
	//Contact modal
	jQuery('#contact-modal').on('shown.bs.modal', function (e) {
		var firstPart = '<?php echo $secure_email[0] ?>';
		var secondPart = '<?php echo $secure_email[1] ?>';
		jQuery('#contact-email').html(firstPart+'@'+secondPart);
	});
<?php endif ?>
	
	if (head.browser.ie && head.browser.version < 10) {
		// Init Packery
		$container.packery({
			featureSelector: '.sw-isotope-block',
			gutter: 0
		});
		previousNext();
		jQuery('#preloader').animate({opacity:'0', top:'-2000px'},500);
	}else{	
		jQuery(window).load(function(){
			// Init Packery
			$container.packery({
				featureSelector: '.sw-isotope-block',
				gutter: 0
			});
			previousNext();
			jQuery('#preloader').animate({opacity:'0', top:'-2000px'},500);
		})	
	}


	/*
	* ON RESIZE
	*/
	jQuery(window).resize(function(){
		previousNext();
	});

});

</script>
