<?php
/**
 * Created by IntelliJ IDEA.
 * User: apple
 * Date: 9/9/19
 * Time: 9:11 AM
 */

//create a custom taxonomy name it "type" for your posts
function etypes_custom_taxonomies()
{

    $labels = array(
        'name' => _x('Tags', 'etypes'),
        'singular_name' => _x('Tag', 'etypes'),
        'search_items' => __('Search Tags'),
        'all_items' => __('All Tags'),
        'parent_item' => __('Parent Tag'),
        'parent_item_colon' => __('Parent Tag:'),
        'edit_item' => __('Edit Tag'),
        'update_item' => __('Update Tag'),
        'add_new_item' => __('Add New Tag'),
        'new_item_name' => __('New Tag Name'),
        'menu_name' => __('Tags'),
    );

    register_taxonomy('tag', array('news', 'gallery', 'interview', 'podcast', 'video', 'review', 'curated', 'essay', 'five_toolkit', 'high_five'), array(
        'hierarchical' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,        
        'query_var' => true,
//        'rewrite' => array('slug' => 'type'),
    ));
    
    $labels = array(
        'name' => _x('Category', 'etypes'),
        'singular_name' => _x('Category', 'etypes'),
        'search_items' => __('Search Category'),
        'all_items' => __('All Category'),
        'parent_item' => __('Parent Category'),
        'parent_item_colon' => __('Parent Category:'),
        'edit_item' => __('Edit Category'),
        'update_item' => __('Update Category'),
        'add_new_item' => __('Add New Category'),
        'new_item_name' => __('New Category Name'),
        'menu_name' => __('Category'),
    );

    register_taxonomy('item_category', array('news', 'gallery', 'interview', 'podcast', 'video', 'review', 'curated', 'essay', 'five_toolkit', 'high_five'), array(
        'hierarchical' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true
//        'rewrite' => array('slug' => 'type'),
    ));

    $labels = array(
        'name' => _x('Action Type', 'etypes'),
        'singular_name' => _x('Action Type', 'etypes'),
        'search_items' => __('Search Action Type'),
        'all_items' => __('All Action Type'),
        'parent_item' => __('Parent Action Type'),
        'parent_item_colon' => __('Parent Action Type:'),
        'edit_item' => __('Edit Action Type'),
        'update_item' => __('Update Action Type'),
        'add_new_item' => __('Add New Action Type'),
        'new_item_name' => __('New Action Type Name'),
        'menu_name' => __('Action Type'),
    );

    register_taxonomy('tag_action', array('action' ), array(
        'hierarchical' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
    ));

}

add_action('init', 'etypes_custom_taxonomies', 0);


function show_taxonomies($item, $tax) {
    $terms = get_the_terms($item, $tax);
    $cat_arr = array();
    if (is_array($terms)) {
        foreach ($terms as $iterm) {
            $cat_arr[] = $iterm->name;
        }
    }
    return implode(", ", $cat_arr);
}
