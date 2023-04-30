<?php

/*
Plugin Name: ACF Search
Plugin URI: https://example.com
Description: Use this plugin to search ACF
Version: 1.0
Author: ACF eTypes Team
Author URI: https://www.example.com/
License: GPLv2 or later
Text Domain: example
*/

global $wpdb;
define('TBL_SEARCH', $wpdb->prefix . 'acf_search');
define('DELIMETER', '   ');

function etypes_create_db()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $table_name = TBL_SEARCH;
    $sql = "CREATE TABLE $table_name (
             id INTEGER NOT NULL AUTO_INCREMENT,
             post_id TEXT NOT NULL,
             post_type TEXT NOT NULL,
             acf_value TEXT NOT NULL,
             PRIMARY KEY (id)
             ) $charset_collate;";
    dbDelta($sql);

    $include_types = array('case', 'event', 'news', 'project');
    insert_craw_data($wpdb, $table_name, $include_types);
}

register_activation_hook(__FILE__, 'etypes_create_db');

function insert_craw_data($wpdb, $table_name, $include_types)
{
    $include_str = '("' . implode('","', $include_types) . '")';
    $tbl_posts = $wpdb->prefix . "posts";
    $sql = "SELECT id, post_type FROM " . $tbl_posts . " WHERE post_type in $include_str and post_status = 'publish'";
    $all_posts = $wpdb->get_results($sql);

    if (is_array($all_posts)) {
        foreach ($all_posts as $post_obj) {
            $wpdb->insert($table_name, array(
                'post_id' => intval($post_obj->id),
                'post_type' => $post_obj->post_type,
                'acf_value' => get_acf($post_obj)
            ), array('%d', '%s', '%s'));
        }
    }
    return true;
}

function get_acf($post_obj)
{
    $id = intval($post_obj->id);
    $meta_string = strtolower(get_the_title($id)) . DELIMETER;
    $in_post = get_post($post_obj->id);
    $blocks_post_types = parse_blocks( $in_post->post_content);
    switch ($post_obj->post_type) {
        case "case":
            $description = get_field('description', $id);
            $company_name = get_field('company_name', $id);
            $caption = get_field('caption', $id);
            $text = get_field('text', $id);
            $blocks_data = get_text_value_from_acf($blocks_post_types);
            $meta_string .= $description . DELIMETER . $company_name . DELIMETER . $caption
                . DELIMETER . $text . DELIMETER . $blocks_data;
            break;
        case "event":
            $text = get_field('text', $id);
            $place = get_field('place', $id);
            $caption = get_field('caption', $id);
			$blocks_data = get_text_value_from_acf($blocks_post_types);
            $meta_string .= $text . DELIMETER . $place . DELIMETER . $caption . DELIMETER . $blocks_data;
            break;
        case "news":
            $text = get_field('text', $id);
            $blocks_data = get_text_value_from_acf($blocks_post_types);
            $meta_string .= $text . DELIMETER . $blocks_data;
            break;
        case "project":
            $description = get_field('description', $id);
            $company_name = get_field('company_name', $id);
            $caption = get_field('caption', $id);
            $text = get_field('text', $id);
            $blocks_data = get_text_value_from_acf($blocks_post_types);
            $meta_string .= $description . DELIMETER . $company_name . DELIMETER . $caption
                . DELIMETER . $text . DELIMETER . $blocks_data;
            break;
    }
    return $meta_string;
}

function etypes_remove_db()
{
    global $wpdb;
    $table_name = TBL_SEARCH;
    $sql = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query($sql);
}

register_deactivation_hook(__FILE__, 'etypes_remove_db');

function etypes_save_post($post_id)
{
    global $wpdb;
    $table_name = TBL_SEARCH;

    if (wp_is_post_revision($post_id))
        return;

    $post_obj = get_post($post_id);

    if ($post_obj->post_status != 'publish') return;

    $search_type = array('case', 'event', 'news', 'project');
    if (in_array($post_obj->post_type, $search_type)) {
        $meta_string = get_acf($post_obj);
        $result = $wpdb->get_results("SELECT post_id FROM " . $table_name . " WHERE post_id like '%" . $post_id . "%'");
        if (empty($result)) {
            $wpdb->insert($table_name, array(
                'post_id' => $post_id,
                'post_type' => $post_obj->post_type,
                'acf_value' => $meta_string
            ), array('%s', '%s', '%s'));
        } else {
            $wpdb->update(
                $table_name,
                array(
                    'post_id' => $post_id,
                    'post_type' => $post_obj->post_type,
                    'acf_value' => $meta_string
                ),
                array('post_id' => $post_id),
                array('%s', '%s', '%s'),
                array('%s')
            );
        }
    }
}

add_action('save_post', 'etypes_save_post', 1000, 3);

function trash_post($post_id)
{
    global $wpdb;
    $table_name = TBL_SEARCH;
    $wpdb->delete($table_name, array('post_id' => $post_id), array('%d'));
    return;
}

add_action('wp_trash_post', 'trash_post', 2000, 1);
add_action('trash_post', 'trash_post', 2000, 1);
add_action('delete_post', 'trash_post', 2000, 1);


function search()
{
    global $wpdb;
    $table_name = TBL_SEARCH;
    $keyword = (isset($_POST['keyword'])) ? trim(strtolower(esc_attr($_POST['keyword']))) : '';

    $sql = "SELECT post_id, post_type FROM " . $table_name . " WHERE acf_value like '%" . $keyword . "%' ";

    $count_result = $wpdb->get_results($sql);

    $result = $wpdb->get_results($sql);
    $group_posts = array();
    foreach ($result as $item) {
        $m_post = get_post($item->post_id);
        switch ($item->post_type) {
            case 'arkitekten':
                $terms = get_the_terms($m_post, 'arkitekten_category');
                $cat_arr = array();
                if (is_array($terms)) {
                    foreach ($terms as $iterm) {
                        $cat_arr[] = $iterm->name;
                    }
                }
                $image = get_field('special_feature_image', $m_post->ID);
                if (count($group_posts['arkitekten']) < 12) {
                    $group_posts['arkitekten'][] = array(
                        'id' => $m_post->ID,
                        'link' => get_permalink($m_post),
                        'title' => $m_post->post_title,
                        'image' => $image['sizes']['search'],
                        'date' => get_the_date('d.m.y', $m_post->ID),
                        'label' => implode(", ", $cat_arr)
                    );
                }
                break;
            case 'calendar':
                $terms = get_the_terms($m_post, 'calendar_category');
                $cat_arr = array();
                if (is_array($terms)) {
                    foreach ($terms as $iterm) {
                        $cat_arr[] = $iterm->name;
                    }
                }
                $image = get_field('image_image', $m_post->ID);
                if (count($group_posts['calendar']) < 12) {
                    $group_posts['calendar'][] = array(
                        'id' => $m_post->ID,
                        'link' => get_permalink($m_post),
                        'title' => $m_post->post_title,
                        'image' => $image['sizes']['search'],
                        'date' => get_field('calendar_from', $m_post->ID),
                        'label' => implode(", ", $cat_arr)
                    );
                }
                break;
            case 'course':
                $image = get_field('feature_image', $m_post->ID);
                if (count($group_posts['course']) < 12) {
                    $group_posts['course'][] = array(
                        'id' => $m_post->ID,
                        'link' => get_permalink($m_post),
                        'title' => $m_post->post_title,
                        'image' => $image['sizes']['search'],
                        'date' => get_field('calendar_from', $m_post->ID),
                        'label' => 'kursus'
                    );
                }
                break;
            case 'debat':
                $image = get_field('feature_image', $m_post->ID);
                if (count($group_posts['debat']) < 12) {
                    $group_posts['debat'][] = array(
                        'id' => $m_post->ID,
                        'link' => get_permalink($m_post),
                        'title' => $m_post->post_title,
                        'image' => $image['sizes']['search'],
                        'date' => get_the_date('d.m.y', $m_post->ID),
                        'label' => 'debat'
                    );
                }
                break;
            case 'job':
                $image = get_field('feature_image', $m_post->ID);
                if (count($group_posts['job']) < 12) {
                    $group_posts['job'][] = array(
                        'id' => $m_post->ID,
                        'link' => get_permalink($m_post),
                        'title' => $m_post->post_title,
                        'image' => $image['sizes']['search'],
                        'date' => get_the_date('d.m.y', $m_post->ID),
                        'label' => "Ansøgningsfrist"
                    );
                }
                break;
            case 'news':
                $terms = get_the_terms($m_post, 'news_category');
                $cat_arr = array();
                if (is_array($terms)) {
                    foreach ($terms as $iterm) {
                        $cat_arr[] = $iterm->name;
                    }
                }
                $image = get_field('feature_image', $m_post->ID);
                if (count($group_posts['news']) < 12) {
                    $group_posts['news'][] = array(
                        'id' => $m_post->ID,
                        'link' => get_permalink($m_post),
                        'title' => $m_post->post_title,
                        'image' => $image['sizes']['search'],
                        'date' => get_the_date('d.m.y', $m_post->ID),
                        'label' => implode(", ", $cat_arr)
                    );
                }
                break;

        }
    }

    $count_group_posts = array(
        'page' => 0
    );
    foreach ($count_result as $item) {
        switch ($item->post_type) {
            case 'arkitekten':
                $count_group_posts['arkitekten']++;
                break;
        }
    }

    $html_result = array();
    foreach ($group_posts as $key => $small_group) {
        $data = array(
            'key' => $key,
            'group' => $small_group
        );
        $html = "";
        $html .= render_item_loaded_by_ajax($data, false);
        $html_result[$key] = array(
            'html' => $html,
            'num' => $count_group_posts[$key],
            'results' => $small_group,
            'remain' => ($count_group_posts[$key] - 12 > 0) ? $count_group_posts[$key] - 12 : 0
        );
    }

    wp_send_json(
        array(
            'html' => $html_result,
            'num_arkitektforeningen' => $count_group_posts['calendar'] + $count_group_posts['course'] +
                $count_group_posts['debat'] + $count_group_posts['job'] + $count_group_posts['news'],
            'num_arkitekten' => $count_group_posts['arkitekten']
        )
    );
    exit();
}

function render_item_loaded_by_ajax($data, $echo = true) {
    extract($data);
    ob_start();
    $linkFile = __DIR__."/item.php";
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

add_action('wp_ajax_search', 'search');
add_action('wp_ajax_nopriv_search', 'search');

//
//function search_paging()
//{
//    global $wpdb;
//    $table_name = TBL_SEARCH;
//    $keyword = (isset($_POST['keyword'])) ? trim(strtolower(esc_attr($_POST['keyword']))) : '';
//    $type = (isset($_POST['type'])) ? trim(strtolower(esc_attr($_POST['type']))) : '';
//    $page = (isset($_POST['page_num'])) ? trim(strtolower(esc_attr($_POST['page_num']))) : '';
//
//    $sql = "SELECT post_id, post_type FROM " . $table_name . " WHERE acf_value like '%" . $keyword . "%'  AND  post_type = '" . $type . "'";
//
//    $count_result = $wpdb->get_results($sql);
//
//    if ($page) {
//        $sql .= " LIMIT " . ($page - 1) * 12 . ", 12"; //offset, limit
//    }
//
//    $result = $wpdb->get_results($sql);
//    $group_posts = array();
//    foreach ($result as $item) {
//        $m_post = get_post($item->post_id);
//        switch ($item->post_type) {
//            case 'arkitekten':
//                $terms = get_the_terms($m_post, 'arkitekten_category');
//                $cat_arr = array();
//                if (is_array($terms)) {
//                    foreach ($terms as $iterm) {
//                        $cat_arr[] = $iterm->name;
//                    }
//                }
//                $image = get_field('special_feature_image', $m_post->ID);
//                $group_posts['arkitekten'][] = array(
//                    'id' => $m_post->ID,
//                    'link' => get_permalink($m_post),
//                    'title' => $m_post->post_title,
//                    'image' => $image['sizes']['search'],
//                    'date' => get_the_date('d.m.y', $m_post->ID),
//                    'label' => implode(", ", $cat_arr)
//                );
//                break;
//            case 'calendar':
//                $terms = get_the_terms($m_post, 'calendar_category');
//                $cat_arr = array();
//                if (is_array($terms)) {
//                    foreach ($terms as $iterm) {
//                        $cat_arr[] = $iterm->name;
//                    }
//                }
//                $image = get_field('image_image', $m_post->ID);
//                $group_posts['calendar'][] = array(
//                    'id' => $m_post->ID,
//                    'link' => get_permalink($m_post),
//                    'title' => $m_post->post_title,
//                    'image' => $image['sizes']['search'],
//                    'date' => get_field('calendar_from', $m_post->ID),
//                    'label' => implode(", ", $cat_arr)
//                );
//                break;
//            case 'course':
//                $image = get_field('feature_image', $m_post->ID);
//                $group_posts['course'][] = array(
//                    'id' => $m_post->ID,
//                    'link' => get_permalink($m_post),
//                    'title' => $m_post->post_title,
//                    'image' => $image['sizes']['search'],
//                    'date' => get_field('calendar_from', $m_post->ID),
//                    'label' => 'kursus'
//                );
//                break;
//            case 'debat':
//                $image = get_field('feature_image', $m_post->ID);
//                $group_posts['debat'][] = array(
//                    'id' => $m_post->ID,
//                    'link' => get_permalink($m_post),
//                    'title' => $m_post->post_title,
//                    'image' => $image['sizes']['search'],
//                    'date' => get_the_date('d.m.y', $m_post->ID),
//                    'label' => 'debat'
//                );
//                break;
//            case 'job':
//                $image = get_field('feature_image', $m_post->ID);
//                $group_posts['job'][] = array(
//                    'id' => $m_post->ID,
//                    'link' => get_permalink($m_post),
//                    'title' => $m_post->post_title,
//                    'image' => $image['sizes']['search'],
//                    'date' => get_the_date('d.m.y', $m_post->ID),
//                    'label' => "Ansøgningsfrist"
//                );
//                break;
//            case 'news':
//                $terms = get_the_terms($m_post, 'news_category');
//                $cat_arr = array();
//                if (is_array($terms)) {
//                    foreach ($terms as $iterm) {
//                        $cat_arr[] = $iterm->name;
//                    }
//                }
//                $image = get_field('feature_image', $m_post->ID);
//                $group_posts['news'][] = array(
//                    'id' => $m_post->ID,
//                    'link' => get_permalink($m_post),
//                    'title' => $m_post->post_title,
//                    'image' => $image['sizes']['search'],
//                    'date' => get_the_date('d.m.y', $m_post->ID),
//                    'label' => implode(", ", $cat_arr)
//                );
//                break;
//            case 'page':
//                $page_template = get_page_template_slug($m_post);
//                $special_bivv_mma = get_field('special_bivv_mma', 'options');
//                if ($page_template == 'page-templates/section-page.php') {
//                    $group_posts['page'][] = array(
//                        'id' => $m_post->ID,
//                        'link' => get_permalink($m_post),
//                        'title' => $m_post->post_title,
//                        'image' => get_stylesheet_directory_uri().'/assets/imgs/Arkitektforeningen_Search_Fallback.jpg',
//                        'date' => get_the_date('d.m.y', $m_post->ID),
//                        'label' => 'Andet'
//                    );
//                } else if ($page_template == 'page-templates/content-page.php') {
//                    $image = get_field('image', $m_post->ID);
//                    $group_posts['page'][] = array(
//                        'id' => $m_post->ID,
//                        'link' => get_permalink($m_post),
//                        'title' => $m_post->post_title,
//                        'image' => ($image) ? $image['sizes']['search'] : get_stylesheet_directory_uri().'/assets/imgs/Arkitektforeningen_Search_Fallback.jpg',
//                        'date' => get_the_date('d.m.y', $m_post->ID),
//                        'label' => 'Andet'
//                    );
//                } else if ($page_template == 'page-templates/theme-page.php') {
//                    $theme_image = get_field('theme_image', $m_post->ID);
//                    $group_posts['page'][] = array(
//                        'id' => $m_post->ID,
//                        'link' => get_permalink($m_post),
//                        'title' => $m_post->post_title,
//                        'image' => ($theme_image) ? $theme_image['sizes']['search'] : get_stylesheet_directory_uri().'/assets/imgs/Arkitektforeningen_Search_Fallback.jpg',
//                        'date' => get_the_date('d.m.y', $m_post->ID),
//                        'label' => 'Andet'
//                    );
//                } else if ($special_bivv_mma->ID == $m_post->ID) {
//                    $group_posts['page'][] = array(
//                        'id' => $m_post->ID,
//                        'link' => get_permalink($m_post),
//                        'title' => $m_post->post_title,
//                        'image' => get_stylesheet_directory_uri().'/assets/imgs/Arkitektforeningen_Search_Fallback.jpg',
//                        'date' => get_the_date('d.m.y', $m_post->ID),
//                        'label' => 'Andet'
//                    );
//                }
//                break;
//        }
//    }
//
//    $count_group_posts = array(
//        'arkitekten' => 0,
//        'calendar' => 0,
//        'course' => 0,
//        'debat' => 0,
//        'job' => 0,
//        'news' => 0,
//        'page' => 0
//    );
//    foreach ($count_result as $item) {
//        switch ($item->post_type) {
//            case 'arkitekten':
//                $count_group_posts['arkitekten']++;
//                break;
//            case 'calendar':
//                $count_group_posts['calendar']++;
//                break;
//            case 'course':
//                $count_group_posts['course']++;
//                break;
//            case 'debat':
//                $count_group_posts['debat']++;
//                break;
//            case 'job':
//                $count_group_posts['job']++;
//                break;
//            case 'news':
//                $count_group_posts['news']++;
//                break;
//            case 'page':
//                $count_group_posts['page']++;
//                break;
//        }
//    }
//
//    $html_result = array();
//    foreach ($group_posts as $key => $small_group) {
//        $data = array(
//            'key' => $key,
//            'group' => $small_group
//        );
//        $html = "";
//        $html .= render_item_loaded_by_ajax('search_result', $data, false);
//        $html_result[$key] = array(
//            'html' => $html,
//            'results' => $small_group,
//            'num' => $count_group_posts[$key],
//            'remain' => ($count_group_posts[$key] - $page * 12 > 0) ? $count_group_posts[$key] - $page  * 12 : 0
//        );
//    }
//
//    wp_send_json(array('html' => $html_result));
//    exit();
//}
//add_action('wp_ajax_search_paging', 'search_paging');
//add_action('wp_ajax_nopriv_search_paging', 'search_paging');


function get_text_value_from_acf($blocks_post_types)
{
    $data = "";
	if (is_array($blocks_post_types)) {
		foreach ($blocks_post_types as $block) {
            $attrs_data = $block['attrs']['data'];
			switch ($block['blockName']) {
                case "acf/accordion":
                    $data .= DELIMETER . $attrs_data['image_text'];
                    break;
                case "acf/accordion-hero":
                    break;
                case "acf/contact-card":
                    $data .= DELIMETER . $attrs_data['hero_text'] . DELIMETER;
                    break;
                case "acf/hero-text":
                    $data .= DELIMETER . $attrs_data['hero_text'] . DELIMETER;
                    break;
                case "acf/hero-landing":
                    $data .= DELIMETER . $attrs_data['text_bottom'] . DELIMETER . $attrs_data['text_top'] . DELIMETER;
                    break;
                case "acf/image":
                    $data .= DELIMETER . $attrs_data['caption'] . DELIMETER;
                    break;
                case "acf/quote":
                    $data .= DELIMETER . $attrs_data['quote_author'] . DELIMETER . $attrs_data['quote_text'] . DELIMETER;
                    break;
                case "acf/text":
                    $data .= DELIMETER . $attrs_data['text'] . DELIMETER;
                    break;
                case "acf/employees-list":
                    break;
                case "acf/events-list":
                    break;
                case "acf/events-promoted":
                    $data .= DELIMETER . $attrs_data['heading'] . DELIMETER;
                    break;
                case "acf/form":
                    break;
                case "acf/heading":
                    $data .= DELIMETER . $attrs_data['tag'] . DELIMETER . $attrs_data['title'] . DELIMETER
                        . $attrs_data['text'] . DELIMETER;
                    break;
                case "acf/news-list":
                    break;
                case "acf/news-promoted":
                    $data .= DELIMETER . $attrs_data['heading'] . DELIMETER;
                    break;
                case "acf/media-text":
                    $data .= DELIMETER . $attrs_data['text'] . DELIMETER;
                    break;
                case "acf/project-list":
                    break;
                case "acf/project-promoted":
                    $data .= DELIMETER . $attrs_data['title'] . DELIMETER;
                    break;
			}
		}
	}
    return $data;
}

