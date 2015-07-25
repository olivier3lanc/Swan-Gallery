<?php 
/**
* SWAN 2015
* Configuration file of the template
* Swan Gallery
* @version 0.9
* @author Olivier Blanc http://www.egalise.com/swan-gallery
* @license MIT
*/


/**
* START OF MANDATORY VARIABLES
* The following variables are mandatory to make the gallery work properly
*/
$tpl_thumb_width = 460;
$tpl_thumb_height = 540;
$tpl_medium_width = 1000;
$tpl_medium_height = 600;
$tpl_thumb_crop = false;
$tpl_title = 'Swan 2015';
$tpl_description = 'Multipurpose template based on masonry layout (Packery) with homepage slider, fits to any kind of image aspect ratio (portrait, landscape, panoramic). The layout of photo pages is automatically adjusted according to the photo aspect ratio. Custom parameters: Caroussel, carousel position, social networks, EXIF display, custom text area in the footer.';
$tpl_author = 'Olivier Blanc';
$tpl_author_url = 'http://www.egalise.com/swan-gallery/templates/swan-2015/';
$tpl_date = '20140320';


/**
* OPTIONAL USER NOTIFICATIONS FOR PARAMETERS BYPASS
* Simply uncomment parameters which are not used in the template.
* There is no incidence for the gallery operations.
* It allows to inform user that a parameter is not taken into account even it is still adjustable
* Displays notifications to the user in the administration 
*/

$tpl_bypass = array (	
						// 'tag_limit' => '',
						// 'homepage_type' => '',
						// 'pagination' => '', 
						// 'related_behaviour' => '', 
						// 'related_limit' => '', 
						// 'email' => '',
						// 'credit' => '',  
						// 'credit_display' => '', 
						// 'statistics' => '', 
						// 'facebook' => '', 
						// 'twitter' => '', 
						// 'photo_title' => '', 
						// 'photo_description' => '', 
						// 'photo_keywords' => '', 
						// 'photo_date' => '', 
						// 'photo_featured' => '', 
						// 'photo_favorites' => '', 
						// 'photo_view' => ''
					);

/*
* OPTIONAL PARAMETERS ARRAY
* Here you can set inlimited number of parameters that will be available only in the template.
* There are 3 types of parameters:
*
******************
* Radio type
* Insert a form with radio buttons choices. Very useful to let the user choose between several options for this template parameter.
*
'unique_identifier' => array 	(	'title' => 'The title displayed for this parameter',
									'description' => 'The description of this parameter',
									'type' => 'radio',
									'values' => array 	(
															'1st Choice Text' => 'value for this 1st choice', // <-- the first key defines the default choice checked
															'2nd Choice Text' => 'value for this 2nd choice',
															'3rd Choice Text' => 'value for this 3rd choice'

															//...etc
														)
								)	

*******************
* Input type
* Insert a form with an input text type. Useful to display a custom text anywhere in the template.

'unique_identifier'	=> array(	'title' => 'The title displayed for this parameter',
								'description' => 'The description of this parameter',
								'type' => 'input',
								'values' => 'Enter a default value or leave empty'
							)

*******************
* Textarea type
* Insert a form with an textarea. Useful to display a long custom text anywhere in the template.

'unique_identifier'	=> array(	'title' => 'The title displayed for this parameter',
								'description' => 'The description of this parameter',
								'type' => 'textarea',
								'values' => 'Enter a default value or leave empty'
							)
*/
$tpl_parameters_tree  = array (	'language' 			=> array(	'title' => 'Language',
																'description' => 'Choose the template language.',
																'type' => 'radio',
																'values' => array(	
																					'English' => 'en_EN', //1st key = Default parameter
																					'French' => 'fr_FR'
																				  )
															),
								'css' 				=> array(	'title' => 'Skin',
																'description' => 'Choose the skin of your template.',
																'type' => 'radio',
																'values' => array(	
																					'Dark' => 'dark', //1st key = Default parameter
																					'Light' => 'light'
																				  )
															),
															
								'carousel'			=> array(	'title' => 'Slider',
																'description' => 'Display or not the homepage slider. The slider displays all the featured photos.',
																'type' => 'radio',
																'values' => array(	
																					'Yes' => '1', //1st key = Default parameter
																					'No' => '0'
																				  )
															),
															
								'carousel_position'	=> array(	'title' => 'Slider Position',
																'description' => 'Select a position for the slider. Prefer "Separated from content" for panoramas and featured photos with different ratios. Prefer "Into the wall" for featured photos with same ratios.',
																'type' => 'radio',
																'values' => array(	
																					'Into the wall' => '1', //1st key = Default parameter
																					'Separated from content' => '0'
																				  )
															),

								'panoramas'			=> array(	'title' => 'Panoramas Thumbnails Behavior',
																'description' => 'Select the behavior for the panoramas thumbnails. If the width/height ratio is superior to 2, the photo is considered as panorama. Select the type of display of the panoramas thumbnails into the gallery. If set to "Wide", the panoramas are displayed full width into the gallery. If set to "Normal", the panoramas are displayed the same way as other thumbnails.',
																'type' => 'radio',
																'values' => array(	
																					'Normal' => 'normal', //1st key = Default parameter
																					'Wide' => 'wide'
																				  )
															),

								'transition_opacity'=> array(	'title' => 'Thumbs hover transition',
																'description' => 'Enable or not the hover fade transition on thumbs. If disabled, titles are always displayed over the thumbnails.',
																'type' => 'radio',
																'values' => array(	
																					'Enable' => 'yes', //1st key = Default parameter
																					'Disable' => 'no'
																				  )
															),

								'exif'				=> array(	'title' => 	'Display EXIF',
																'description' => 'Select wether or not you wish to display EXIF metadata on each photo.',
																'type' => 'radio',
																'values' => array(	
																					'Yes' => '1', //1st key = Default parameter
																					'No' => '0'
																				  )
															),

								'socials'			=> array(	'title' => 	'Display Social Networks',
																'description' => 'Select wether or not you wish to display social sharing icons on each photo page (Facebook, Twitter, Google+, email and much more).',
																'type' => 'radio',
																'values' => array(	
																					'No' => '0', //1st key = Default parameter
																					'Yes' => '1'
																				  )
															),
															
								'custom_input'		=> array(	'title' => 'Footer description',
																'description' => 'A custom text to display in the footer. If empty, the text displayed will be the gallery description.',
																'type' => 'input',
																'values' => ''
															),

								'comments_switch'	=> array(	'title' => 	'Display Comments',
																'description' => 'Select wether or not you wish to display comments on photo pages.',
																'type' => 'radio',
																'values' => array(	
																					'Yes' => '1', //1st key = Default parameter
																					'No' => '0'
																				  )
															),
															
								'comments'			=> array(	'title' => 'Comments',
																'description' => 'Paste the third-party Javascript code from your comments web service. Comments are displayed on photo pages.',
																'type' => 'textarea',
																'values' => ''
															)
																
								);
								
?>