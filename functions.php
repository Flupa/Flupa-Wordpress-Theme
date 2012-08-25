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
/**
 * META BOXES
 */
function add_events_metaboxes(){
    add_meta_box('wpt_events_properties', 'Event Properties', 'wpt_events_properties', 'event', 'side', 'default');
}
function wpt_events_properties() {
    global $post;
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' .wp_create_nonce( plugin_basename(__FILE__) ). '" />';
    // Get the location data if its already been entered
    $location = get_post_meta($post->ID, '_location', true);
    $price = get_post_meta($post->ID, '_price', true);
    $lang = get_post_meta($post->ID, '_lang', true);
    $date = get_post_meta($post->ID, '_date', true);
    $gps = get_post_meta($post->ID, '_gps', true);
    // Echo out the field
    echo '<label for="_date">'. _x( 'Date', 'antennes' ).'</label>';
    echo '<input type="text" name="date" class="widefat" value="'.$date.'"/>';
    echo '<label for="_location">'. _x( 'Adresse', 'antennes' ).'</label>';
    echo '<textarea type="text" name="_location" class="widefat">'.$location.'</textarea>';
    echo '<input type="text" name="_gps" class="widefat" value="'.$gps['lat'].' , '.$gps['long'].'" disabled="disabled" />';
    echo '<label for="_price">'. _x( 'Prix', 'antennes').'</label>';
    echo '<input type="text" name="_price" value="'.$price.'" class="widefat" />';
    echo '<label for="_lang">'. _x( 'Language', 'antennes').'</label>';
    echo '<select name="_lang" class="widefat">';
    echo '<option value="french" '.selected($lang,"french").' >'. _x('Français','antennes').'</option>
        <option value="english" '.selected($lang,"english").' >'. _x('Anglais','antennes').'</option>
        <option value="luxemburgish" '.selected($lang,"luxemburgish").' >'. _x('Luxembourgeois','antennes').'</option>
        <option value="deutch" '.selected($lang,"deutch").' >'. _x('Allemand','antennes').'</option>
    </select>';
}

function wpt_save_events_meta($post_id, $post) {
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['eventmeta_noncename'], plugin_basename(__FILE__) )) {
        return $post->ID;
    }
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))return $post->ID;
    // OK, we're authenticated: we need to find and save the data
    if(isset($_POST['_location'])){
        update_post_meta($post->ID,'_location',esc_html($_POST['_location']));
        $coords = get_coords(esc_html($_POST['_location']));
        if($coords!='')update_post_meta($post->ID,'_gps',$coords);
    }
    if(isset($_POST['_lang']))update_post_meta($post->ID,'_lang',esc_html($_POST['_lang']));
    if(isset($_POST['_price']))update_post_meta($post->ID,'_price',esc_html($_POST['_price']));
    if(isset($_POST['_date']))update_post_meta($post->ID,'_date',esc_html($_POST['_date']));
}
add_action('save_post', 'wpt_save_events_meta', 1, 2); // save the custom fields

/**
 * TAXONOMIES
 */
add_action( 'init', 'register_taxonomy_antennes' );

function register_taxonomy_antennes() {

    $labels = array( 
        'name' => _x( 'Antennes', 'antennes' ),
        'singular_name' => _x( 'Antenne', 'antennes' ),
        'search_items' => _x( 'Search Antennes', 'antennes' ),
        'popular_items' => _x( 'Popular Antennes', 'antennes' ),
        'all_items' => _x( 'All Antennes', 'antennes' ),
        'parent_item' => _x( 'Parent Antenne', 'antennes' ),
        'parent_item_colon' => _x( 'Parent Antenne:', 'antennes' ),
        'edit_item' => _x( 'Edit Antenne', 'antennes' ),
        'update_item' => _x( 'Update Antenne', 'antennes' ),
        'add_new_item' => _x( 'Add New Antenne', 'antennes' ),
        'new_item_name' => _x( 'New Antenne', 'antennes' ),
        'separate_items_with_commas' => _x( 'Separates Antennes with commas', 'antennes' ),
        'add_or_remove_items' => _x( 'Add or remove antennes', 'antennes' ),
        'choose_from_most_used' => _x( 'Choose from the most used antennes', 'antennes' ),
        'menu_name' => _x( 'Antennes', 'antennes' ),
    );
    $args = array( 
        'labels' => $labels,
        'public' => true,
        'show_in_nav_menus' => true,
        'show_ui' => true,
        'show_tagcloud' => true,
        'hierarchical' => true,
        'rewrite' => true,
        'query_var' => true
    );

    register_taxonomy( 'antennes', array('event'), $args );
}
add_action( 'init', 'register_taxonomy_types' );

function register_taxonomy_types() {

    $labels = array( 
        'name' => _x( 'Types', 'types' ),
        'singular_name' => _x( 'Type', 'types' ),
        'search_items' => _x( 'Search Types', 'types' ),
        'popular_items' => _x( 'Popular Types', 'types' ),
        'all_items' => _x( 'All Types', 'types' ),
        'parent_item' => _x( 'Parent Type', 'types' ),
        'parent_item_colon' => _x( 'Parent Type:', 'types' ),
        'edit_item' => _x( 'Edit Type', 'types' ),
        'update_item' => _x( 'Update Type', 'types' ),
        'add_new_item' => _x( 'Add New Type', 'types' ),
        'new_item_name' => _x( 'New Type', 'types' ),
        'separate_items_with_commas' => _x( 'Separate types with commas', 'types' ),
        'add_or_remove_items' => _x( 'Add or remove types', 'types' ),
        'choose_from_most_used' => _x( 'Choose from the most used types', 'types' ),
        'menu_name' => _x( 'Types', 'types' ),
    );

    $args = array( 
        'labels' => $labels,
        'public' => true,
        'show_in_nav_menus' => true,
        'show_ui' => true,
        'show_tagcloud' => true,
        'hierarchical' => true,

        'rewrite' => true,
        'query_var' => true
    );

    register_taxonomy( 'types', array('event'), $args );
}
/**
 * CUSTOMS POSTS
 */
add_action( 'after_setup_theme', 'create_post_type' );


function create_post_type() {
    /**
     * Evenements
     */
    $labels = array( 
        'name' => _x( 'Events', 'event' ),
        'singular_name' => _x( 'Event', 'event' ),
        'add_new' => _x( 'Add New', 'event' ),
        'add_new_item' => _x( 'Add New Event', 'event' ),
        'edit_item' => _x( 'Edit Event', 'event' ),
        'new_item' => _x( 'New Event', 'event' ),
        'view_item' => _x( 'View Event', 'event' ),
        'search_items' => _x( 'Search Events', 'event' ),
        'not_found' => _x( 'No events found', 'event' ),
        'not_found_in_trash' => _x( 'No events found in Trash', 'event' ),
        'parent_item_colon' => _x( 'Parent Event:', 'event' ),
        'menu_name' => _x( 'Events', 'event' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,
        'description' => 'Events occured all around the world and Usability relative',
        'supports' => array( 'title', 'editor', 'custom-fields', 'comments' ),
        'taxonomies' => array( 'post_tag'),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'menu_icon' => get_bloginfo('stylesheet_directory').'/img/custompost/events.png',
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'register_meta_box_cb' => 'add_events_metaboxes'
    );
    register_post_type( 'event', $args );
    /**
     * Jobs
     */
    
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
        'has_archive' => true
        )
    );
    /**
     * Contest
     */
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
        'capability_type' => 'post'
        )
    );
    /**
     * Books
     */
    $labels = array( 
        'name' => _x( 'Books', 'book' ),
        'singular_name' => _x( 'Book', 'book' ),
        'add_new' => _x( 'Add New', 'book' ),
        'add_new_item' => _x( 'Add New Book', 'book' ),
        'edit_item' => _x( 'Edit Book', 'book' ),
        'new_item' => _x( 'New Book', 'book' ),
        'view_item' => _x( 'View Book', 'book' ),
        'search_items' => _x( 'Search Books', 'book' ),
        'not_found' => _x( 'No books found', 'book' ),
        'not_found_in_trash' => _x( 'No books found in Trash', 'book' ),
        'parent_item_colon' => _x( 'Parent Book:', 'book' ),
        'menu_name' => _x( 'Books', 'book' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,
        'description' => 'Reading is Good. And there\'s some good book about Usability, UX & Design.',
        'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ),
        'taxonomies' => array( 'category', 'post_tag' ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'menu_icon' => get_bloginfo('stylesheet_directory').'/img/custompost/books.png',
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'book', $args );
    /**
     * Sponsor
     */
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