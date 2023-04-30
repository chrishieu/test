<?php

// Keep functions nice and clean :)

define('CURRENT_FIVE_VERSION', '5.603.07');

// Standard functions - basic functionality for every project
include(get_stylesheet_directory() . '/functions/functions.php');

include(get_stylesheet_directory() . '/functions/functions_security.php');

// ACF Block functions
include(get_stylesheet_directory() . '/functions/functions-block.php');

// Custom Image sizes
include(get_stylesheet_directory() . '/functions/customize-images.php');

// Hide/Show Custom UI in wp-admin
include(get_stylesheet_directory() . '/functions/customize-wp-admin.php');

// Custom ACF
include(get_stylesheet_directory() . '/functions/customize-acf.php');

//Taxonomies
include(get_stylesheet_directory() . '/functions/functions-taxonomies.php');

// include(get_stylesheet_directory() . '/functions/EA_Action.php');
// include(get_stylesheet_directory() . '/functions/EA_News.php');
// include(get_stylesheet_directory() . '/functions/EA_Gallery.php');
// include(get_stylesheet_directory() . '/functions/EA_Interview.php');
// include(get_stylesheet_directory() . '/functions/EA_Review.php');
// include(get_stylesheet_directory() . '/functions/EA_Video.php');
// include(get_stylesheet_directory() . '/functions/EA_Podcast.php');
// include(get_stylesheet_directory() . '/functions/EA_Theme.php');
// include(get_stylesheet_directory() . '/functions/EA_Team.php');
// include(get_stylesheet_directory() . '/functions/EA_Voting.php');
include(get_stylesheet_directory() . '/functions/functions-photo-competition-admin.php');
include(get_stylesheet_directory() . '/functions/functions-photo-competition.php');
include(get_stylesheet_directory() . '/functions/functions-instagram.php');
include(get_stylesheet_directory() . '/functions/functions-signup-newsletter.php');


function fivemedia_asset_versioning() {
    if (defined('CURRENT_FIVE_ASSET_CACHE')) {
        $cache = CURRENT_FIVE_ASSET_CACHE;
    } else {
        $cache = false;
    }
    
    if ($cache === false) {
        $date = new \DateTime(); 
        $version = $date->getTimestamp();
    } else {
        $version = CURRENT_FIVE_VERSION;
    }
    
    return $version;
}




add_filter( 'recovery_mode_email_rate_limit', 'recovery_mail_infinite_rate_limit' );
function recovery_mail_infinite_rate_limit( $rate ) {
    return 100 * YEAR_IN_SECONDS;
}

add_filter('acf/validate_post_id', 'change_post_id_for_preview', 10, 2);
function change_post_id_for_preview($post_id, $_post_id) {
    global $getOriginPostID;
    if ($getOriginPostID) {
        return $_post_id;
    }
    return $post_id;

}

// remove auto <br> and <p>
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );

add_action( 'wp_ajax_get_five_posts', 'get_five_posts' );
add_action( 'wp_ajax_nopriv_get_five_posts', 'get_five_posts' );
function get_five_posts() {
    $type = (isset($_POST['type'])) ? esc_attr($_POST['type']) : '';

    $args = array(
        'post_type' => $type,
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby'   => 'date',
        'order' => 'DESC',
    );

    $post_list_query = new WP_Query($args);
    $post_list = array();

    if($post_list_query-> have_posts())
        while ($post_list_query -> have_posts()) : $post_list_query -> the_post();
            $post_list[] = get_the_ID();
        endwhile;

    $data = array(
        'post_list' => $post_list
    );
    $html = render_item_ajax('five_post', $data, false);

    wp_send_json(array('html' => $html));
    die();
}

function render_item_ajax($file, $data, $echo = true) {
    extract($data);
    ob_start();
    $linkFile = __DIR__."/items/".$file.".php";
    if (!file_exists($linkFile)) {
        echo "file $linkFile not found";
        return;
    }

    include $linkFile;
    $content = ob_get_contents();
    ob_clean();
    if ($echo) {
        echo $content;
    } else {
        return $content;
    }
}


class Fivemedia_Sublevel_Walker extends Walker_Nav_Menu
{
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<div class='collapse'><ul class='sub-menu'>\n";
    }
    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul></div>\n";
    }
}


function fivemedia_get_category_name($post) {
    $item_category = get_the_terms( $post->ID, 'item_category' );
    $category_name = "";
    if (is_array($item_category) && count($item_category) > 0) {
        $category_name = $item_category[0]->name;
    }
    if ($category_name == "No Category") {
        return "";
    }

    return $category_name;
}

function fivemedia_get_category_link($post) {
    $item_category = get_the_terms( $post->ID, 'item_category' );
    $category_name = "";
    $url = "";
    if (is_array($item_category) && count($item_category) > 0) {
        $category_name = $item_category[0]->name;
        $page = get_field('link_page', 'item_category_'.$item_category[0]->term_id);
        $url = get_permalink($page);
        if (strtolower($category_name) == "no category") {
            $category_name = "";
            $url = "";
        }
    }

    return array( 'name' => $category_name, 'url' => $url);
}


/**
 * Get thumbnail image
 * @param type $item
 * @return type
 */
function fivemdia_get_thumbnail($item) {
    $image = array();
    if (in_array($item->post_type, array("news", "review", "interview", 'video', 'gallery', 'curated', 'essay', 'theme', 'five_toolkit', 'high_five'))) {
      $image = get_field('feature_image', $item->ID);
      if (!$image) {
          $image = get_field('article_header_image', $item->ID);
      }

    }

    if ($item->post_type == "podcast") {
      $image = get_field('feature_image', $item->ID);
    }
    
    if ($item->post_type == "contest") {
      $image = get_field('image', $item->ID);
    }
    

    return $image;
}



/**
 * Add rewrite rule
 */
function custom_rewrite_basic() {
  add_rewrite_rule( '^selected-tag/(.*)/?', 'index.php?pagename=selected-tag&tag=$matches[1]', 'top' );
  add_rewrite_tag( '%tag%', '([^&]+)' );
}

add_action( 'init', 'custom_rewrite_basic' );


function etypes_give_wp_enqueue_style() {
    echo '<link rel="stylesheet" href="'.get_stylesheet_directory_uri().'/donate.css" type="text/css" media="all" />';
}

add_action( 'give_embed_head', 'etypes_give_wp_enqueue_style', 10 );

function etypes_give_wp_enqueue_script() {
    echo '<script src="'.get_stylesheet_directory_uri().'/donate.js"></script>';
}

add_action( 'give_embed_footer', 'etypes_give_wp_enqueue_script', 30 );

add_action( 'wp_ajax_get_action_item', 'get_action_item' );
add_action( 'wp_ajax_nopriv_get_action_item', 'get_action_item' );
function get_action_item() {
    $page = (isset($_POST['page'])) ? esc_attr($_POST['page']) : '';
    $type = (isset($_POST['type'])) ? esc_attr($_POST['type']) : '';
    $post_per_page = 8;

    $tax_query = array();
    if (isset($_POST['type'])) {
        $tax_query = array(
            array(
            'taxonomy' => 'tag',
            'field' => 'slug',
            'terms' => $type,
            )
        );
    }

    $args = array(
        'post_type' => 'action',
        'post_status' => 'publish',
        'posts_per_page' => $post_per_page,
        'orderby'   => 'date',
        'order' => 'DESC',
        'suppress_filters' => true,
        'tax_query' => $tax_query,
        'paged' => $page
    );

    $post_list_query = new WP_Query($args);
    $post_list = array();

    $count_args = array(
        'post_type' => 'action',
        'post_status' => 'publish',
        'orderby'   => 'date',
        'order' => 'DESC',
        'suppress_filters' => true,
        'tax_query' => $tax_query,
    );
    $count_post_list = new WP_Query($count_args);
    $count_post_list_query = $count_post_list->found_posts;
    $total_page = ceil($count_post_list_query / $post_per_page);

    if($post_list_query-> have_posts())
        while ($post_list_query -> have_posts()) : $post_list_query -> the_post();
            $post_list[] = get_the_ID();
        endwhile;

    $data = array(
        'post_list' => $post_list
    );
    $html = render_item_ajax('take_action_item', $data, false);

    wp_send_json(array('html' => $html, 'total_page' => $total_page));
    die();
}

