<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Flupa Wordpress Theme
 * @since Flupa Wordpress Theme 1.0
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @since Flupa Wordpress Theme 1.0
 */
function flupa_wordpress_theme_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'flupa_wordpress_theme_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @since Flupa Wordpress Theme 1.0
 */
function flupa_wordpress_theme_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter( 'body_class', 'flupa_wordpress_theme_body_classes' );

/**
 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
 *
 * @since Flupa Wordpress Theme 1.0
 */
function flupa_wordpress_theme_enhanced_image_navigation( $url, $id ) {
	if ( ! is_attachment() && ! wp_attachment_is_image( $id ) )
		return $url;

	$image = get_post( $id );
	if ( ! empty( $image->post_parent ) && $image->post_parent != $id )
		$url .= '#main';

	return $url;
}
add_filter( 'attachment_link', 'flupa_wordpress_theme_enhanced_image_navigation', 10, 2 );

/**
 * Fonction de récupération de géopoint google maps
 */
function get_coords($a){
  // je construit une URL avec l'adresse
  $map_url = 'http://maps.google.com/maps/api/geocode/json?address='.urlencode($a).'&sensor=false';
  // je récupère ça
  $request = wp_remote_get($map_url);
  $json = wp_remote_retrieve_body($request);

  // si c'est vide, je kill...
  if(empty($json))return false;

  // je parse et je choppe la latitude et la longitude du premier element
  $json = json_decode($json);
  $lat = $json->results[0]->geometry->location->lat;
  $long = $json->results[0]->geometry->location->lng;
  // je retourne les valeurs sous forme de tableau
  return compact($lat,$long,array('lat','long'));
}