<?php 
/**
* INFINITE
* Configuration file of the template
* Swan Gallery
* @version 0.9.1
* @author Olivier Blanc http://www.egalise.com/swan-gallery
* @license MIT
*/

/*
* START OF MANDATORY VARIABLES
* The following variables are mandatory to make the gallery work properly
*/
$tpl_thumb_width = 100;
$tpl_thumb_height = 100;
$tpl_medium_width = 200;
$tpl_medium_height = 200;
$tpl_thumb_crop = false;
$tpl_title = 'Infinite';
$tpl_description = 'A simple one page folio template with AJAX pagination and lazy loading images.';
$tpl_author = 'Olivier Blanc';
$tpl_author_url = 'http://www.egalise.com/swan-gallery/templates/infinite/';
$tpl_date = '20150204';


/*
* OPTIONAL USER NOTIFICATIONS FOR PARAMETERS BYPASS
* Simply uncomment parameters which are not used in the template.
* There is no incidence for the gallery operations.
* It allows to inform user that a parameter is not taken into account even it is still adjustable
* Displays notifications to the user in the administration 
*/

$tpl_bypass = array (	
						// 'tag_limit' => '',
						'homepage_type' => '',
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
						'photo_favorites' => '', 
						// 'photo_view' => ''
					);

/*
* OPTIONAL PARAMETERS ARRAY
* Here you can set inlimited number of parameters that will be available only in the template.
* There are 3 types of parameters:

******************
* Radio type
* Insert a form with radio buttons choices. Very useful to let the user choose between several options for this template parameter.

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
$tpl_parameters_tree  = array (

	'skin'			 			=> array(	
				                     		'title' => 'Skin',
											'description' => 'Choose the skin of the template. "Dark": Light on dark color scheme. "Light": Dark on light color scheme.',
											'type' => 'radio',
											'values' => array 	(
																	'Dark' => 'dark',
																	'Light' => 'light'
																)
										),
	'photo_link'		 		=> array(	
				                     		'title' => 'Photo link',
											'description' => 'Choose whether or not to enable link to the photo page on photo click.',
											'type' => 'radio',
										 	'values' => array 	(
																	'Enable' => 'yes',
																	'Disable' => 'no'
																)
										),
	'sharing_zone'		 		=> array(	
				                     		'title' => 'Sharing zone',
											'description' => 'Allow or disallow the share feature on each photo.',
											'type' => 'radio',
										 	'values' => array 	(
																	'Show' => 'yes',
																	'Hide' => 'no'
																)
										),
	'description_in_gallery'	=> array(	
				                     		'title' => 'Photo description in gallery',
											'description' => 'Show or hide the description under each photo in the gallery.',
											'type' => 'radio',
										 	'values' => array 	(
																	'Hide' => 'no',
																	'Show' => 'yes'
																)
										),
	'tags_in_gallery'		 	=> array(	
				                     		'title' => 'Tags in gallery',
											'description' => 'Show or hide the tags under each photo in the gallery.',
											'type' => 'radio',
										 	'values' => array 	(
																	'Show' => 'yes',
																	'Hide' => 'no'
																)
										),
	'comments_switch'		 	=> array(	
				                     		'title' => 'Comments',
											'description' => 'Display or not comments. This switch works with the comment script code.',
											'type' => 'radio',
										 	'values' => array 	(
																	'No' => 'no',
																	'Yes' => 'yes'
																)
										),
	'comments_code'		 		=> array(	
				                     		'title' => 'Comments code',
											'description' => 'Copy and paste the javascript code from your comment app (for example Disqus or Muut)',
											'type' => 'textarea',
											'values' => '&lt;!-- Paste the code here --&gt;'
										),
	'lang_search_results_for'	=> array(	'title' => 'Text for "Search results for"',
											'description' => 'Enter the text to display for this string.',
											'type' => 'input',
											'values' => 'Search results for'
										),
	'lang_tag'					=> array(	'title' => 'Text for "Tag"',
											'description' => 'Enter the text to display for this string.',
											'type' => 'input',
											'values' => 'Tag'
										),
	'lang_all_tags'				=> array(	'title' => 'Text for "All tags"',
											'description' => 'Enter the text to display for this string.',
											'type' => 'input',
											'values' => 'All tags'
										),
	'lang_less_tags'			=> array(	'title' => 'Text for "Less tags"',
											'description' => 'Enter the text to display for this string.',
											'type' => 'input',
											'values' => 'Less tags'
										),
	'lang_photos_tagged'		=> array(	'title' => 'Text for "Photos tagged"',
											'description' => 'Enter the text to display for this string.',
											'type' => 'input',
											'values' => 'Photos tagged'
										),
	'lang_return_top'			=> array(	'title' => 'Text for "Return to the top of the page" tooltip',
											'description' => 'Tooltip text for return to top of the page links. Enter the text to display for this string.',
											'type' => 'input',
											'values' => 'Return to the top of the page'
										),
	'lang_open_menu'			=> array(	'title' => 'Text for "Open menu" tooltip',
											'description' => 'Tooltip text when hovering the top left menu button on small devices. Enter the text to display for this string.',
											'type' => 'input',
											'values' => 'Open menu'
										),
	'lang_return_home'			=> array(	'title' => 'Text for "Back to homepage" tooltip',
											'description' => 'Tooltip text of the gallery title link. Enter the text to display for this string.',
											'type' => 'input',
											'values' => 'Back to homepage'
										),
	'lang_load_more_photos'		=> array(	'title' => 'Text for "Load more photos"',
											'description' => 'Text of the pagination button. Enter the text to display for this string.',
											'type' => 'input',
											'values' => 'Load more photos'
										),
	'lang_related_photos'		=> array(	'title' => 'Text for "Related photos"',
											'description' => 'Text of the related photos title section. Enter the text to display for this string.',
											'type' => 'input',
											'values' => 'Related photos'
										),
	'lang_facebook_page'		=> array(	'title' => 'Text for "Facebook page" tooltip',
											'description' => 'Tooltip text of the Facebook icon. Enter the text to display for this string.',
											'type' => 'input',
											'values' => 'Facebook page'
										),
	'lang_twitter'				=> array(	'title' => 'Text for "Twitter" tooltip',
											'description' => 'Tooltip text of the Twitter icon. Enter the text to display for this string.',
											'type' => 'input',
											'values' => 'Twitter'
										),
	'lang_email'				=> array(	'title' => 'Text for "Display email for contact" tooltip',
											'description' => 'Tooltip text of the email icon. Enter the text to display for this string.',
											'type' => 'input',
											'values' => 'Display email for contact'
										),
	'lang_rss'					=> array(	'title' => 'Text for "RSS feed" tooltip',
											'description' => 'Tooltip text of the RSS feed icon. Enter the text to display for this string.',
											'type' => 'input',
											'values' => 'RSS feed'
										),
	'lang_search_placeholder'	=> array(	'title' => 'Text for "Search"',
											'description' => 'Text for the placeholder of the search form. Enter the text to display for this string.',
											'type' => 'input',
											'values' => 'Search'
										),
	'lang_toggle_sharing_area'	=> array(	'title' => 'Text for "Toggle sharing area" tooltip',
											'description' => 'Text of the toggle button tooltip of the share tool area. Enter the text to display for this string.',
											'type' => 'input',
											'values' => 'Toggle sharing area'
										),
	'lang_share_on_facebook'	=> array(	'title' => 'Text for "Share on Facebook" tooltip',
											'description' => 'Text of the Facebook share button tooltip in the share tool area. Enter the text to display for this string.',
											'type' => 'input',
											'values' => 'Share on Facebook'
										),
	'lang_share_on_twitter'		=> array(	'title' => 'Text for "Share on Twitter" tooltip',
											'description' => 'Text of the Twitter share button tooltip in the share tool area. Enter the text to display for this string.',
											'type' => 'input',
											'values' => 'Share on Twitter'
										),
	'lang_share_on_link'		=> array(	'title' => 'Text for "Link" tooltip',
											'description' => 'Text of the Twitter share button tooltip in the share tool area. Enter the text to display for this string.',
											'type' => 'input',
											'values' => 'Link to share this photo. Click to select, then copy the link'
										),
	'lang_link'					=> array(	'title' => 'Text for "Link"',
											'description' => 'Text of the link tool in the sharing area of each photo. Enter the text to display for this string.',
											'type' => 'input',
											'values' => 'Link'
										)
                               );
								
?>