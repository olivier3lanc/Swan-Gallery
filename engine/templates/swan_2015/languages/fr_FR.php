<?php
/**
* FRENCH LANGUAGE FOR THE SWAN 2015 TEMPLATE
* All language strings are optional.
* For each language string not set, the default language (in the template config.php) will be applied.
* Swan Gallery
* @version 0.9
* @author Olivier Blanc http://www.egalise.com/swan-gallery/
* @license MIT
*/
	$tpl_lang = array(
		'tpl.title' => 'Swan 2015',
		'tpl.description' => 'Template polyvalent en mur de briques avec réorganisation automatique et carrousel en page d\'accueil, pour tout type de format d\'images (portraits, paysages, panoramiques). Les pages photo s\'adaptent au format de la photo. Paramètres personnalisables: Carrousel, position du carrousel, réseaux sociaux, affichage des EXIF, texte pied de page.',

/*
* TEMPLATE PARAMETERS
* '[parameter id].title' => 'Parameter Title'
* '[parameter id].description' => 'Parameter Description'
* For each Radio types choice
* '[parameter id].values.[choice text]' => 'The displayed choice text'
* For each input and textarea 
* '[parameter id].values' => 'The default value that can be empty' 
*/
		'language.title' => 'Langue',
		'language.description' => 'Choisissez la langue du thème.',
		'language.values.English' => 'Anglais',
		'language.values.French' => 'Français',
		'css.title' => 'Thème',
		'css.description' => 'Choisissez le thème de votre template.',
		'css.values.Light' => 'Clair',
		'css.values.Dark' => 'Sombre',
		'carousel.title' => 'Carrousel',
		'carousel.description' => 'Afficher ou non le carrousel. La carrousel affiche les photos mises en avant.',
		'carousel.values.Yes' => 'Oui',
		'carousel.values.No' => 'Non',
		'carousel_position.title' => 'Position du Carrousel',
		'carousel_position.description' => 'Sélectionnez une position pour le carrousel. De préférence "Séparé du contenu" pour des photos panoramiques ou des photos avec des formats différents. Pour des photos de même format dans le carrousel, choisissez de préférence "Dans le mur".',
		'carousel_position.values.Into the wall' => 'Dans le mur',
		'carousel_position.values.Separated from content' => 'Séparé du contenu',
		'panoramas.title' => 'Comportement des vignettes panoramas',
		'panoramas.description' => 'Sélectionnez le comportement des vignettes de panoramas. Si le rapport largeur/hauteur est supérieur à 2, la photo est considérée comme un panorama. Sélectionnez le type d\'affichage des panoramas dans la galerie. Sélectionnez "Pleine largeur" pour afficher les vignettes de panoramas sur toute la largeur. Sélectionnez "Normal" pour afficher les panoramas comme les autre photos.',
		'panoramas.values.Normal' => 'Normal',
		'panoramas.values.Wide' => 'Pleine largeur',
		'transition_opacity.title' => 'Transition au survol des vignettes',
		'transition_opacity.description' => 'Activer ou désactiver l\'effet de transition (fondu) au survol des vignettes. Si désactivé, les titres des photos sont affichés en permanence sur les vignettes.',
		'transition_opacity.values.Enable' => 'Activer',
		'transition_opacity.values.Disable' => 'Désactiver',
		'exif.title' => 'Afficher les EXIF',
		'exif.description' => 'Afficher ou non les EXIF dans les pages photo.',
		'exif.values.Yes' => 'Oui',
		'exif.values.No' => 'Non',
		'socials.title' => 'Afficher les Réseaux Sociaux',
		'socials.description' => 'Sélectionnez si oui ou non vous souhaitez afficher les icônes des réseaux sociaux dans les pages photo (Facebook, Twitter, Google+, email et plus encore).',
		'socials.values.Yes' => 'Oui',
		'socials.values.No' => 'Non',
		'custom_input.title' => 'Description dans le pied de page',
		'custom_input.description' => 'Saisissez un texte personnalisé qui sera affiché dans le pied de page. Si vide, le texte affiché est la description de la galerie.',
		'custom_input.values' => 'Texte libre par défaut',
		'comments_switch.title' => 'Afficher les Commentaires',
		'comments_switch.description' => 'Sélectionnez si oui ou non vous souhaitez afficher les commentaires sur les pages photo.',
		'comments.title' => 'Commentaires',
		'comments_switch.values.Yes' => 'Oui',
		'comments_switch.values.No' => 'Non',
		'comments.description' => 'Coller le code Javascript tiers de l\'application web qui gère les commentaires. Les commentaires sont affichés sur chaque page photo. Laisser vide pour ne pas afficher les commentaires.',
		'comments.values' => '',
/*
* FRONTEND 
*/
		'front.tags' => 'Mots-clés',
		'front.tags.tooltip' => 'Voir les mots-clés',
		'front.all.tags' => 'Tous les mots-clés',
		'front.all.tags.tooltip' => 'Voir tous les mots-clés',
		'front.tag' => 'Mot-clé',
		'front.tag.page.description' => 'Photo(s) de la galerie avec le mot clé',
		'front.tags.page.description' => 'Mots-clés principaux de la galerie',
		'front.keep.in.touch' => 'Garder le contact',
		'front.search.results.for' => 'Résultat(s) de recherche pour',
		'front.explore' => 'Explorer',
		'front.explore.tooltip' => 'Voir toutes les photos',
		'front.see.photo.page.tooltip' => 'Voir la page photo',
		'front.to.page' => 'sur',
		'front.on' => 'sur',
		'front.by' => 'par',
		'front.email' => 'email',
		'front.previous' => 'Précédente',
		'front.next' => 'Suivante',
		'front.previous.photo' => 'Photo précédente',
		'front.next.photo' => 'Photo suivante',
		'front.toggle.size' => 'Pleine page',
		'front.page' => 'page',
		'front.expand' => 'Agrandir',
		'front.close' => 'Fermer',
		'front.popular.tags' => 'Mots-clés populaires',
		'front.popular.tags.tooltip' => 'Voir les mots-clés les plus populaires',
		'front.most.popular.tags' => 'Mots-clés les plus populaires',
		'front.related' => 'Photos similaires',
		'front.facebook.page.tooltip' => 'Suivre ma page Facebook',
		'front.twitter.tooltip' => 'Suivre mes Tweets',
		'front.email.me.tooltip' => 'Me contacter par email',
		'front.photo.tagged' => 'Photos avec le mot-clé',
		'front.rss.feed.tooltip' => 'Flux RSS',
		'front.contact' => 'Contact',
		'front.contact.text' => 'Vous pouvez me contacter par email',
		'front.search' => 'Rechercher',
		'front.toggle.navigation' => 'Navigation'
	);
?>