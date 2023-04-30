<?php
/**
 * Review
 *
 * @package      CoreFunctionality
 * @author       Etypes
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

class EA_Reviews {

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
            'name'               => 'Reviews',
            'singular_name'      => 'Reviews',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Reviews',
            'edit_item'          => 'Edit Reviews',
            'new_item'           => 'New Reviews',
            'view_item'          => 'View Reviews',
            'search_items'       => 'Search Reviews',
            'not_found'          => 'No Reviews found',
            'not_found_in_trash' => 'No Reviews found in Trash',
            'parent_item_colon'  => 'Parent Reviews:',
            'menu_name'          => 'Reviews',
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

        register_post_type( 'review', $args );

    }

}
new EA_Reviews();