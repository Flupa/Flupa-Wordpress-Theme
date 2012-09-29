<?php

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