<?php
/**
 * Team
 *
 * @package      CoreFunctionality
 * @author       Etypes
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

class EA_Team {

    /**
     * Initialize all the things
     *
     * @since 1.2.0
     */
    function __construct() {

        // Actions
        add_action( 'init', array( $this, 'register_cpt' ) );
    }

    /**
     * Register the custom post type
     *
     * @since 1.2.0
     */
    function register_cpt() {

        $labels = array(
            'name'               => 'Team',
            'singular_name'      => 'Team',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Team',
            'edit_item'          => 'Edit Team',
            'new_item'           => 'New Team',
            'view_item'          => 'View Team',
            'search_items'       => 'Search Team',
            'not_found'          => 'No Team found',
            'not_found_in_trash' => 'No Team found in Trash',
            'parent_item_colon'  => 'Parent Team:',
            'menu_name'          => 'Team',
        );

        $args = array(
            'labels'              => $labels,
            'hierarchical'        => true,
            'supports'            => array('title', 'editor'),
            'menu_icon'           => 'dashicons-welcome-widgets-menus',
            'public'              => true,
            'show_ui'             => true,
            'show_in_rest'        => true,
            'publicly_queryable'  => true,
            'exclude_from_search' => true,
            'has_archive'         => false,
            'query_var'           => true,
            'can_export'          => true,
            // 'rewrite'             => array( 'slug' => 'theme', 'with_front' => false ),
        );

        register_post_type( 'team', $args );
        
        $labels = array(
            'name'               => 'Profile',
            'singular_name'      => 'Profile',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Profile',
            'edit_item'          => 'Edit Profile',
            'new_item'           => 'New Profile',
            'view_item'          => 'View Profile',
            'search_items'       => 'Search Profile',
            'not_found'          => 'No Profile found',
            'not_found_in_trash' => 'No Profile found in Trash',
            'parent_item_colon'  => 'Parent Profile:',
            'menu_name'          => 'Profile',
        );

        $args = array(
            'labels'              => $labels,
            'hierarchical'        => true,
            'supports'            => array('title', 'editor'),
            'menu_icon'           => 'dashicons-welcome-widgets-menus',
            'public'              => true,
            'show_ui'             => true,
            'show_in_rest'        => true,
            'publicly_queryable'  => false,
            'exclude_from_search' => true,
            'has_archive'         => false,
            'query_var'           => true,
            'can_export'          => true,
            // 'rewrite'             => array( 'slug' => 'theme', 'with_front' => false ),
        );

        register_post_type( 'profile', $args );
        
        $labels = array(
            'name'               => 'Partner',
            'singular_name'      => 'Partner',
            'add_new'            => 'Add Partner',
            'add_new_item'       => 'Add New Partner',
            'edit_item'          => 'Edit Partner',
            'new_item'           => 'New Partner',
            'view_item'          => 'View Partner',
            'search_items'       => 'Search Partner',
            'not_found'          => 'No Partner found',
            'not_found_in_trash' => 'No Partner found in Trash',
            'parent_item_colon'  => 'Parent Partner:',
            'menu_name'          => 'Partner',
        );

        $args = array(
            'labels'              => $labels,
            'hierarchical'        => true,
            'supports'            => array('title', 'editor'),
            'menu_icon'           => 'dashicons-welcome-widgets-menus',
            'public'              => true,
            'show_ui'             => true,
            'show_in_rest'        => true,
            'publicly_queryable'  => true,
            'exclude_from_search' => true,
            'has_archive'         => false,
            'query_var'           => true,
            'can_export'          => true,
            // 'rewrite'             => array( 'slug' => 'theme', 'with_front' => false ),
        );

        register_post_type( 'partner', $args );
       
        
        $labels = array(
            'name'               => 'Instagram',
            'singular_name'      => 'Instagram',
            'add_new'            => 'Add Instagram',
            'add_new_item'       => 'Add New Instagram',
            'edit_item'          => 'Edit Instagram',
            'new_item'           => 'New Instagram',
            'view_item'          => 'View Instagram',
            'search_items'       => 'Search Instagram',
            'not_found'          => 'No Instagram found',
            'not_found_in_trash' => 'No Instagram found in Trash',
            'parent_item_colon'  => 'Parent Instagram:',
            'menu_name'          => 'Instagram',
        );

        $args = array(
            'labels'              => $labels,
            'hierarchical'        => true,
            'supports'            => array('title', 'editor'),
            'menu_icon'           => 'dashicons-welcome-widgets-menus',
            'public'              => true,
            'show_ui'             => true,
            'show_in_rest'        => true,
            'publicly_queryable'  => true,
            'exclude_from_search' => true,
            'has_archive'         => false,
            'query_var'           => true,
            'can_export'          => true,
            // 'rewrite'             => array( 'slug' => 'theme', 'with_front' => false ),
        );

        register_post_type( 'instagram', $args );

    }

}
new EA_Team();


// Admin columns
function custom_columns_instagram($columns) {
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => 'Title',
        'alt-title' => 'Alt. title',
        'date' => 'Date'
    );
    return $columns;
}
function custom_columns_instagram_data($column, $post_id) {
    switch ($column) {
        case 'alt-title' :
            $alt_title = get_field('title');
            echo $alt_title;
            break;
    }
}
if (function_exists('add_theme_support')) {
    add_filter('manage_instagram_posts_columns', 'custom_columns_instagram');
    add_action('manage_instagram_posts_custom_column', 'custom_columns_instagram_data', 10, 2);
}