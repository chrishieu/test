<?php
/**
 * Article
 *
 * @package      CoreFunctionality
 * @author       Etypes
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

class EA_Theme {

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
            'name'               => 'Theme',
            'singular_name'      => 'Theme',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Theme',
            'edit_item'          => 'Edit Theme',
            'new_item'           => 'New Theme',
            'view_item'          => 'View Theme',
            'search_items'       => 'Search Theme',
            'not_found'          => 'No Theme found',
            'not_found_in_trash' => 'No Theme found in Trash',
            'parent_item_colon'  => 'Parent Theme:',
            'menu_name'          => 'Theme',
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
            'rewrite'             => array( 'slug' => 'theme', 'with_front' => false ),
        );

        register_post_type( 'theme', $args );

    }

}
new EA_Theme();