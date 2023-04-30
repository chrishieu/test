<?php
/**
 * Video
 *
 * @package      CoreFunctionality
 * @author       Etypes
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

class EA_Videos {

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
            'name'               => 'Videos',
            'singular_name'      => 'Videos',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Videos',
            'edit_item'          => 'Edit Videos',
            'new_item'           => 'New Videos',
            'view_item'          => 'View Videos',
            'search_items'       => 'Search Videos',
            'not_found'          => 'No Videos found',
            'not_found_in_trash' => 'No Videos found in Trash',
            'parent_item_colon'  => 'Parent Videos:',
            'menu_name'          => 'Videos',
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
        );

        register_post_type( 'video', $args );

    }

}
new EA_Videos();