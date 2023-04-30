<?php
/**
 * Photo_contest
 *
 * @package      CoreFunctionality
 * @author       Etypes
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

class EA_Photo_contest {

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
            'name'               => 'Photo contest',
            'singular_name'      => 'Photo contest',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Photo contest',
            'edit_item'          => 'Edit Photo contest',
            'new_item'           => 'New Photo contest',
            'view_item'          => 'View Photo contest',
            'search_items'       => 'Search Photo contest',
            'not_found'          => 'No Photo contest found',
            'not_found_in_trash' => 'No Photo contest found in Trash',
            'parent_item_colon'  => 'Parent Photo contest:',
            'menu_name'          => 'Photo contest',
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

        register_post_type( 'contest', $args );

    }

}
new EA_Photo_contest();