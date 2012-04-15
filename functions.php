<?php
/**
 * @package WordPress
 * @subpackage Flupa_Wordpress_Theme
 */


/**
 * Remove some talkative tags from source code
 */
remove_action('wp_head','wp_generator');
remove_action('wp_head','wlwmanifest_link');
remove_action('wp_head','rsd_link');

/**
 * Unactive those function users can use to destroy the universe
 */
define('DISALLOW_FILE_EDIT',true);

/**
 * And remove this ugly bar
 */ 
add_filter( 'show_admin_bar', '__return_false' );

/**
 * Login Page Logo
 * TODO : Make a logo and put it here
 */
function LogoL() {
    echo '<style type="text/css">h1 a { background-image:url('.get_bloginfo('template_directory').'/img/header-connect.png) !important;margin-bottom:10px !important; }</style>';
}
add_action('login_head', 'LogoL');

/**
 * Redirect Link for Login Page when you clicked on the logo
 */
function Url_Login() {
    return '/';
}
add_filter('login_headerurl', 'Url_Login');

/**
 * Favicon
 */
function Favicon() {
    echo '<link rel="shortcut icon" href="'.get_bloginfo('template_directory').'/img/favicon.ico" />';
    //echo '<link rel="apple-touch-icon" href="'.get_bloginfo('stylesheet_directory').'/img/apple-touch-icon.png">';
}
add_action('wp_head', 'Favicon');

/**
 * Custom THumbnail Size
 */
if ( function_exists('add_theme_support') ) { 
    add_theme_support('post-thumbnails');
    /*set_post_thumbnail_size( 100, 50, true );
    add_image_size( 'featured' , 640, 355, true );
    add_image_size( 'homepage' , 295, 170, true );
    add_image_size( 'single' , 590 , 300 , true );
    add_image_size( 'video_c' , 210, 118, true );
    add_image_size( 'video_p' , 80, 60, true);*/
}

/**
 * CUSTOMS POSTS
 */
add_action( 'init', 'create_post_type_event' );
add_action( 'init', 'create_post_type_job' );
add_action( 'init', 'create_post_type_' );


function create_post_type() {
    register_post_type( 'Events',
        array(
        'labels' => array(
            'name' => __( 'Events' ),
            'singular_name' => __( 'Event' ),
            'add_new_item' => __('Add New Event'),
            'edit_item' => __('Edit Event'),
            'new_item' => __('New Event'),
            'view_item' => __('View Event'),
            'search_items' => __('Search Events'),
            'not_found' => __('No events found'),
            'not_found_in_trash' => __('No events found in Trash')
            ),
        'description' => 'Events occured all around the world and Usability relative',
        'public' => true,
        'rewrite' => array('slug' => 'events','with_front' => FALSE),
        'menu_icon' => get_bloginfo('stylesheet_directory').'/img/events.png',
        'show_in_menu' => true,
        'menu_position' => 5,
        'supports' => array('title','editor','thumbnail','excerpt','revisions','comments','trackbacks'),
        'has_archive' => true,
        'capability_type' => 'post',
        'taxonomies' => array('post_tag')
        )
    );
}
