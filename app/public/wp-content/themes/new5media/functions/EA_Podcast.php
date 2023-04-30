<?php
/**
 * Article
 *
 * @package      CoreFunctionality
 * @author       Etypes
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

class EA_Podcast {

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
            'name'               => 'Podcast',
            'singular_name'      => 'Podcast',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Podcast',
            'edit_item'          => 'Edit Podcast',
            'new_item'           => 'New Podcast',
            'view_item'          => 'View Podcast',
            'search_items'       => 'Search Podcast',
            'not_found'          => 'No Podcast found',
            'not_found_in_trash' => 'No Podcast found in Trash',
            'parent_item_colon'  => 'Parent Podcast:',
            'menu_name'          => 'Podcast',
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
            'rewrite'             => array( 'slug' => 'podcast', 'with_front' => false ),
        );

        register_post_type( 'podcast', $args );

    }

}
new EA_Podcast();