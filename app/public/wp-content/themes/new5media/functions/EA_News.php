<?php
/**
 * Article
 *
 * @package      CoreFunctionality
 * @author       Etypes
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

class EA_News {

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
            'name'               => 'Articles',
            'singular_name'      => 'Article',
            'add_new'            => 'Add new',
            'add_new_item'       => 'Add new article',
            'edit_item'          => 'Edit article',
            'new_item'           => 'New article',
            'view_item'          => 'View article',
            'search_items'       => 'Search articles',
            'not_found'          => 'No articles found',
            'not_found_in_trash' => 'No articles found in Trash',
            'parent_item_colon'  => 'Parent Articles:',
            'menu_name'          => 'Articles',
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
            'rewrite'             => array( 'slug' => 'articles', 'with_front' => false ),
        );

        register_post_type( 'news', $args );
        
        $labels = array(
            'name'               => 'Curated',
            'singular_name'      => 'Curated',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Curated',
            'edit_item'          => 'Edit Curated',
            'new_item'           => 'New Curated',
            'view_item'          => 'View Curated',
            'search_items'       => 'Search Curated',
            'not_found'          => 'No Curated found',
            'not_found_in_trash' => 'No Curated found in Trash',
            'parent_item_colon'  => 'Parent Curated:',
            'menu_name'          => 'Curated',
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
            'rewrite'             => array( 'slug' => 'curated', 'with_front' => false ),
        );

        register_post_type( 'curated', $args );
        
        $labels = array(
            'name'               => 'Essay',
            'singular_name'      => 'Essay',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Essay',
            'edit_item'          => 'Edit Essay',
            'new_item'           => 'New Essay',
            'view_item'          => 'View Essay',
            'search_items'       => 'Search Essay',
            'not_found'          => 'No Essay found',
            'not_found_in_trash' => 'No Essay found in Trash',
            'parent_item_colon'  => 'Parent Essay:',
            'menu_name'          => 'Essay',
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
            'rewrite'             => array( 'slug' => 'essay', 'with_front' => false ),
        );

        register_post_type( 'essay', $args );
        
        $labels = array(
            'name'               => 'Essay',
            'singular_name'      => 'Essay',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Essay',
            'edit_item'          => 'Edit Essay',
            'new_item'           => 'New Essay',
            'view_item'          => 'View Essay',
            'search_items'       => 'Search Essay',
            'not_found'          => 'No Essay found',
            'not_found_in_trash' => 'No Essay found in Trash',
            'parent_item_colon'  => 'Parent Essay:',
            'menu_name'          => 'Essay',
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
            'rewrite'             => array( 'slug' => 'essay', 'with_front' => false ),
        );

        register_post_type( 'essay', $args );
        
        $labels = array(
            'name'               => '5 Toolkit',
            'singular_name'      => '5 Toolkit',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New 5 Toolkit',
            'edit_item'          => 'Edit 5 Toolkit',
            'new_item'           => 'New 5 Toolkit',
            'view_item'          => 'View 5 Toolkit',
            'search_items'       => 'Search 5 Toolkit',
            'not_found'          => 'No 5 Toolkit found',
            'not_found_in_trash' => 'No 5 Toolkit found in Trash',
            'parent_item_colon'  => 'Parent 5 Toolkit',
            'menu_name'          => '5 Toolkit',
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
            'rewrite'             => array( 'slug' => 'five_toolkit', 'with_front' => false ),
        );

        register_post_type( 'five_toolkit', $args );
        
        $labels = array(
            'name'               => 'High 5',
            'singular_name'      => 'High 5 ',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New High 5',
            'edit_item'          => 'Edit High 5',
            'new_item'           => 'New High 5',
            'view_item'          => 'View High 5',
            'search_items'       => 'Search High 5 ',
            'not_found'          => 'No High 5 found',
            'not_found_in_trash' => 'No High 5 found in Trash',
            'parent_item_colon'  => 'Parent High 5',
            'menu_name'          => 'High 5',
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
            'rewrite'             => array( 'slug' => 'high_five', 'with_front' => false ),
        );

        register_post_type( 'high_five', $args );


    }

}
new EA_News();