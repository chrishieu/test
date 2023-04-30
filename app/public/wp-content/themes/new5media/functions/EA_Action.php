<?php
/**
 * Photo_contest
 *
 * @package      CoreFunctionality
 * @author       Etypes
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

class EA_Action {

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
            'name'               => 'Action',
            'singular_name'      => 'Action',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Action',
            'edit_item'          => 'Edit Action',
            'new_item'           => 'New Action',
            'view_item'          => 'View Action',
            'search_items'       => 'Search Action',
            'not_found'          => 'No Action found',
            'not_found_in_trash' => 'No Action found in Trash',
            'parent_item_colon'  => 'Parent Action:',
            'menu_name'          => 'Action',
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
            'query_var'           => false,
            'can_export'          => true,
        );

        register_post_type( 'action', $args );

    }

}
new EA_Action();