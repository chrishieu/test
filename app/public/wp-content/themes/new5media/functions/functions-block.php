<?php

// add_filter('use_block_editor_for_post_type', 'prefix_disable_gutenberg', 10, 2);
// function prefix_disable_gutenberg($current_status, $post_type)
// {
//     // Use your post type key instead of 'product'
//     if ($post_type === 'action') return false;
//     if ($post_type === 'instagram') return false;
//     return $current_status;
// }


function etypes_block_category($categories, $post)
{
    return array(
        array(
            'slug' => 'modules',
            'title' => __('Modules', 'etypes-block'),
        ),
    );
}

add_filter('block_categories', 'etypes_block_category', 10, 2);

function etypes_register_module_blocks()
{
    if (!function_exists('acf_register_block_type')) return;
    $block_types = array(
        array(
            'name' => 'hw-award',
            'title' => __('hw-award'),
            'category' => 'modules'
        ),
        array(
            'name' => 'hw-people',
            'title' => __('hw-people'),
            'category' => 'modules'
        ),
        array(
            'name' => 'hw-selected-projects',
            'title' => __('hw-selected-projects'),
            'category' => 'modules'
        ),
        array(
            'name' => 'hw-slider',
            'title' => __('hw-slider'),
            'category' => 'modules'
        ),
        array(
            'name' => 'hw-vision',
            'title' => __('hw-vision'),
            'category' => 'modules'
        ),
    );

    foreach ($block_types as $block_type) {
        $args = array(
            'name' => $block_type['name'],
            'title' => $block_type['title'],
            // 'keywords' => $block_type['keywords'],
            'icon' => 'admin-generic',
            'render_callback' => 'etypes_acf_block_render_callback',
            'category' => $block_type['category'],
            'mode' => 'edit',
            'supports' => array(
                'align' => false,
                'mode' => false,
            )
        );
        if (array_key_exists('post_types', $block_type)) {
            $args['post_types'] = $block_type['post_types'];
        }
        acf_register_block_type($args);
    }
}

add_action('acf/init', 'etypes_register_module_blocks');

function etypes_acf_block_render_callback($block)
{
    $name = str_replace('acf/', '', $block['name']);

    if (file_exists(get_theme_file_path("/components/block-{$name}.php"))) {
        include(get_theme_file_path("/components/block-{$name}.php"));
    }
}
