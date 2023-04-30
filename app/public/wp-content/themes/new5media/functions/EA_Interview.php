<?php
/**
 * Interview
 *
 * @package      CoreFunctionality
 * @author       Etypes
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

class EA_Interviews {

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
            'name'               => 'Interviews',
            'singular_name'      => 'Interviews',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Interviews',
            'edit_item'          => 'Edit Interviews',
            'new_item'           => 'New Interviews',
            'view_item'          => 'View Interviews',
            'search_items'       => 'Search Interviews',
            'not_found'          => 'No Interviews found',
            'not_found_in_trash' => 'No Interviews found in Trash',
            'parent_item_colon'  => 'Parent Interviews:',
            'menu_name'          => 'Interviews',
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

        register_post_type( 'interview', $args );

    }

}
new EA_Interviews();