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
add_action( 'after_setup_theme', 'create_post_type' );


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
        'menu_icon' => get_bloginfo('stylesheet_directory').'/img/custompost/events.png',
        'show_in_menu' => true,
        'menu_position' => 5,
        'supports' => array('title','editor','excerpt','revisions','comments','trackbacks'),
        'has_archive' => true,
        'capability_type' => 'post',
        'taxonomies' => array('post_tag')
        )
    );
    register_post_type( 'Jobs',
        array(
        'labels' => array(
            'name' => __( 'Jobs' ),
            'singular_name' => __( 'Job' ),
            'add_new_item' => __('Add New Job Offer'),
            'edit_item' => __('Edit Job Offer'),
            'new_item' => __('New Job Offer'),
            'view_item' => __('View Job Offer'),
            'search_items' => __('Search Job Offer'),
            'not_found' => __('No jobs found'),
            'not_found_in_trash' => __('No jobs found in Trash')
            ),
        'description' => 'Jobs Offer that partners or members send us.',
        'public' => true,
        'rewrite' => array('slug' => 'jobs','with_front' => FALSE),
        'menu_icon' => get_bloginfo('stylesheet_directory').'/img/custompost/jobs.png',
        'show_in_menu' => true,
        'menu_position' => 5,
        'supports' => array('title','editor','excerpt','revisions','trackbacks'),
        'has_archive' => true,
        'capability_type' => 'post',
        'taxonomies' => array('post_tag')
        )
    );
    register_post_type( 'Contest',
        array(
        'labels' => array(
            'name' => __( 'Giveaway' ),
            'singular_name' => __( 'Giveaway' ),
            'add_new_item' => __('Add New Giveaway'),
            'edit_item' => __('Edit Giveaway'),
            'new_item' => __('New Giveaway'),
            'view_item' => __('View Giveaway'),
            'search_items' => __('Search Giveaway Offer'),
            'not_found' => __('No giveaway found'),
            'not_found_in_trash' => __('No giveaway found in Trash')
            ),
        'description' => 'We sometimes make some giveaway.',
        'public' => true,
        'rewrite' => array('slug' => 'giveaway','with_front' => FALSE),
        'menu_icon' => get_bloginfo('stylesheet_directory').'/img/custompost/giveaway.png',
        'show_in_menu' => true,
        'menu_position' => 5,
        'supports' => array('title','editor','excerpt','revisions','comments','trackbacks'),
        'has_archive' => true,
        'capability_type' => 'post',
        'taxonomies' => array('post_tag')
        )
    );
    register_post_type( 'Book',
        array(
        'labels' => array(
            'name' => __( 'Books' ),
            'singular_name' => __( 'Book' ),
            'add_new_item' => __('Add New Book'),
            'edit_item' => __('Edit Book'),
            'new_item' => __('New Book'),
            'view_item' => __('View Book'),
            'search_items' => __('Search Book Offer'),
            'not_found' => __('No books found'),
            'not_found_in_trash' => __('No books found in Trash')
            ),
        'description' => 'Reading is Good. And there\'s some good book about Usability, UX & Design.',
        'public' => true,
        'rewrite' => array('slug' => 'books','with_front' => FALSE),
        'menu_icon' => get_bloginfo('stylesheet_directory').'/img/custompost/books.png',
        'show_in_menu' => true,
        'menu_position' => 5,
        'supports' => array('title','editor','thumbnail','excerpt','revisions','comments','trackbacks'),
        'has_archive' => true,
        'capability_type' => 'post',
        'taxonomies' => array('post_tag')
        )
    );
    register_post_type( 'Sponsor',
        array(
        'labels' => array(
            'name' => __( 'Sponsors' ),
            'singular_name' => __( 'Sponsor' ),
            'add_new_item' => __('Add New Sponsor'),
            'edit_item' => __('Edit Sponsor'),
            'new_item' => __('New Sponsor'),
            'view_item' => __('View Sponsor'),
            'search_items' => __('Search Sponsor Offer'),
            'not_found' => __('No sponsors found'),
            'not_found_in_trash' => __('No sponsors found in Trash')
            ),
        'description' => 'Because we are better togethers, let\'s promote our sponsors.',
        'public' => true,
        'rewrite' => array('slug' => 'sponsors','with_front' => FALSE),
        'menu_icon' => get_bloginfo('stylesheet_directory').'/img/custompost/sponsors.png',
        'show_in_menu' => true,
        'menu_position' => 5,
        'supports' => array('title','editor','thumbnail','excerpt','revisions','trackbacks'),
        'has_archive' => true,
        'capability_type' => 'post',
        'taxonomies' => array('post_tag')
        )
    );
    register_post_type( 'White Paper',
        array(
        'labels' => array(
            'name' => __( 'White Papers' ),
            'singular_name' => __( 'White Paper' ),
            'add_new_item' => __('Add New White Paper'),
            'edit_item' => __('Edit White Paper'),
            'new_item' => __('New White Paper'),
            'view_item' => __('View White Paper'),
            'search_items' => __('Search White Paper Offer'),
            'not_found' => __('No white paper found'),
            'not_found_in_trash' => __('No white paper found in Trash')
            ),
        'description' => 'Sometimes we create some great papers. There\'s our work.',
        'public' => true,
        'rewrite' => array('slug' => 'whitepapers','with_front' => FALSE),
        'menu_icon' => get_bloginfo('stylesheet_directory').'/img/custompost/whitepaper.png',
        'show_in_menu' => true,
        'menu_position' => 5,
        'supports' => array('title','editor','excerpt','revisions','comments','trackbacks'),
        'has_archive' => true,
        'capability_type' => 'post',
        'taxonomies' => array('post_tag')
        )
    );

}