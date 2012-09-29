<?php

function add_Books_metaboxes(){
    add_meta_box('wpt_books_properties', 'Books Properties', 'wpt_books_properties', 'book', 'side', 'default');
}
// META BOX
function wpt_books_properties() {
    global $post;
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="bookmeta_noncename" id="bookmeta_noncename" value="' .wp_create_nonce( plugin_basename(__FILE__) ). '" />';
    // Get the location data if its already been entered
    $author = get_post_meta($post->ID, '_author', true);
    $publish_year = get_post_meta($post->ID, '_publish_year', true);
    $editor = get_post_meta($post->ID, '_editor', true);
    $editor_link = get_post_meta($post->ID, '_editor_link', true);
    $amazon_link = get_post_meta($post->ID, '_amazon_link', true);
    $lang = get_post_meta($post->ID, '_lang', true);
    $nb_page = get_post_meta($post->ID, '_nb_page', true);
    
    // Echo out the field
    echo '<label for="_author">'. _x( 'Auteur', 'books' ).'</label>';
    echo '<input type="text" name="_author" class="widefat" value="'.$author.'"/>';
    
    echo '<label for="_publish_year">'. _x( 'Année de Publication', 'books' ).'</label>';
    echo '<input type="number" name="_publish_year" class="widefat" value'.$publish_year.'"/>';
    
    echo '<label for="_editor">'. _x( 'Editeur', 'books' ).'</label>';
    echo '<input type="text" name="_editor" class="widefat" value="'.$editor.'" />';
    
    echo '<label for="_editor_link">'. _x( 'Lien de l\'éditeur', 'books').'</label>';
    echo '<input type="text" name="_editor_link" value="'.$editor_link.'" class="widefat" />';
    
    echo '<label for="_amazon_link">'. _x( 'Lien vers Amazon', 'books').'</label>';
    echo '<input type="text" name="_amazon_link" value="'.$amazon_link.'" class="widefat" />';
    
    echo '<label for="_lang">'. _x( 'Language', 'antennes').'</label>';
    echo '<select name="_lang" class="widefat">';
    echo '<option value="french" '.selected($lang,"french").' >'. _x('Français','antennes').'</option>
        <option value="english" '.selected($lang,"english").' >'. _x('Anglais','antennes').'</option>
        <option value="luxemburgish" '.selected($lang,"luxemburgish").' >'. _x('Luxembourgeois','antennes').'</option>
        <option value="deutch" '.selected($lang,"deutch").' >'. _x('Allemand','antennes').'</option>
    </select>';
    
    echo '<label for="_nb_page">'. _x( 'Nombre de pages', 'books').'</label>';
    echo '<input type="number" name="_nb_page" value="'.$nb_page.'" class="widefat" />';
    
}

function wpt_save_books_meta($post_id, $post) {
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['bookmeta_noncename'], plugin_basename(__FILE__) )) {
        return $post->ID;
    }
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))return $post->ID;
    // OK, we're authenticated: we need to find and save the data
    
    // Add values of $books_meta as custom fields
    foreach ($books_meta as $key => $value) { // Cycle through the $books_meta array!
         if( $post->post_type == 'revision' ) return; // Don't store custom data twice
         $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
         if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
             update_post_meta($post->ID, $key, $value);
         } else { // If the custom field doesn't have a value
             add_post_meta($post->ID, $key, $value);
         }
         if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
    }
}
add_action('save_post', 'wpt_save_books_meta', 1, 2); // save the custom fields


$labels = array( 
    'name' => _x( 'Books', 'book' ),
    'singular_name' => _x( 'book', 'book' ),
    'add_new' => _x( 'Add New', 'book' ),
    'add_new_item' => _x( 'Add New book', 'book' ),
    'edit_item' => _x( 'Edit book', 'book' ),
    'new_item' => _x( 'New book', 'book' ),
    'view_item' => _x( 'View book', 'book' ),
    'search_items' => _x( 'Search Books', 'book' ),
    'not_found' => _x( 'No Books found', 'book' ),
    'not_found_in_trash' => _x( 'No Books found in Trash', 'book' ),
    'parent_item_colon' => _x( 'Parent book:', 'book' ),
    'menu_name' => _x( 'Books', 'book' ),
);

$args = array( 
    'labels' => $labels,
    'hierarchical' => false,
    'description' => 'Books that are recommanded by FLUPA & talking about UX, Usability, ...',
    'supports' => array( 'title', 'editor', 'custom-fields', 'comments' ),
    'taxonomies' => array( 'post_tag'),
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
    'capability_type' => 'post',
    'register_meta_box_cb' => 'add_books_metaboxes'
);

register_post_type( 'book', $args );