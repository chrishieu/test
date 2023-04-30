<?php
/**
 * Gallery
 *
 * @package      CoreFunctionality
 * @author       Etypes
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

class EA_Galleries {

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
            'name'               => 'Galleries',
            'singular_name'      => 'Galleries',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Galleries',
            'edit_item'          => 'Edit Galleries',
            'new_item'           => 'New Galleries',
            'view_item'          => 'View Galleries',
            'search_items'       => 'Search Galleries',
            'not_found'          => 'No Galleries found',
            'not_found_in_trash' => 'No Galleries found in Trash',
            'parent_item_colon'  => 'Parent Galleries:',
            'menu_name'          => 'Galleries',
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

        register_post_type( 'gallery', $args );

    }

}
new EA_Galleries();