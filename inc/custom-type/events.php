<?php

// META BOXES
function add_events_metaboxes(){
    add_meta_box('wpt_events_properties', 'Event Properties', 'wpt_events_properties', 'event', 'side', 'default');
}

// META BOXES
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
    echo '<input type="text" name="_date" class="widefat" value="'.$date.'"/>';
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
        <option value="deutsch" '.selected($lang,"deutsch").' >'. _x('Allemand','antennes').'</option>
    </select>';
}

// META BOXES
function wpt_save_events_meta($post_id, $post) {
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['eventmeta_noncename'], plugin_basename(__FILE__) )) {
        return $post->ID;
    }
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))return $post->ID;
    // OK, we're authenticated: we need to find and save the data
    
    $events_meta = [];
    
    if(isset($_POST['_location'])){
    	$events_meta['_location'] = esc_html($_POST['_location']);
        $coords = get_coords(esc_html($_POST['_location']));
        if($coords!='')$events_meta['_gps'] = $coords;
    }
    $events_meta['_lang'] = esc_html($_POST['_lang']);
    $events_meta['_price'] = esc_html($_POST['_price']);
    $events_meta['_date'] = esc_html($_POST['_date']);

    // Add values of $events_meta as custom fields
    foreach ($events_meta as $key => $value) { // Cycle through the $events_meta array!
    	if( $post->post_type == 'revision' ) return; // Don't store custom data twice
		//$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
    		update_post_meta($post->ID, $key, $value);
    	} else { // If the custom field doesn't have a value
	    	add_post_meta($post->ID, $key, $value);
	    }
	    if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
    }
}

add_action('save_post', 'wpt_save_events_meta', 1, 2); // save the custom fields

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
