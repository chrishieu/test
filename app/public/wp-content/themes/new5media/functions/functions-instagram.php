<?php

function get_insta_title($content, $numberStopWords = 20) {
    $temp = explode(" ", $content);
    $numberWords = count($temp);
    if ($numberWords < $numberStopWords) {
        return $content;
    } else {
        $tempwords = array();
      
        foreach ($temp as $key => $value) {
            if ($key > $numberStopWords) { break; }
            $tempwords[] = $value;
        }        
        
        return implode(" ", $tempwords)." ...";
    }
}

function get_insta_post_date($time) {
    $timeText= "";
        $position = strpos($time , "T");
        $stringTime = substr($time, 0, $position);
        $dateTime = \DateTime::createFromFormat("Y-m-d", $stringTime);
     
        $currentTime = new \DateTime();
        $diff = $currentTime->diff($dateTime);
        if ($diff->days == 1) {
            $timeText = $diff->days." day ago";
        } else if ($diff->days == 0) {
            if ($diff->h == 0) {
                if ($diff->m == 0) {
                    $timeText = "now";
                } else {
                    $timeText = $diff->m." minutes ago";
                }
            } else {
                $timeText = $diff->h." hours ago";
            }
            
        } else if ($diff->days <= 7) {
        
            $timeText = $diff->days." days ago";
        } else {
            $timeText = $dateTime->format('d M');
        }
        
    return $timeText;
}

/**
 * Get Instagram Data from API
 * @return Array
 */
function get_insta_data() {
    $insta_time = (int) get_option('instagram_time');
    $currentTime = new \DateTime();
    $currentTimeU = (int)$currentTime->format('U');
    $time_out_api = 12*60*60;
    $data = array();
    // Call API After 6 hours;
    if ($currentTimeU - $insta_time > $time_out_api) {
        
        get_insta_data_from_api();
    }
    
    $data = get_option('instagram_content');
    $insta_data = json_decode($data, true);

    return $insta_data;
}


global $listDataInsta;
$listDataInsta = [];

function get_insta_data_from_api_loop($link, $page = 1) {
    $access_token = get_field('api_instagram_token', 'options');
    
    if ($page >=5 ) return;
    $result = wp_remote_get($link);
  
    
    global $listDataInsta;
    
    
    
    if (is_array($result) && $result['response']['code'] == 200) {
        $dataArray = json_decode($result['body'], true);
        foreach ($dataArray['data'] as $key => $item) {
            if (strpos($item['caption'], 'latestfrom5') === false) {
                unset($dataArray['data'][$key]);
                continue;
            }

            if ($item['media_type'] == "CAROUSEL_ALBUM") {
                $link_insta_child_api = "https://graph.instagram.com/".$item['id']."/children?fields=id,media_url,thumbnail_url&access_token=".$access_token;
                $result_child = wp_remote_get($link_insta_child_api);
                if (is_array($result)) {
                    $dataArrayChild = json_decode($result_child['body'], true);
                    $dataArray['data'][$key]['data_child'] = $dataArrayChild;
                }
            }
        }
        
        
        $listDataInsta = array_merge($listDataInsta, $dataArray['data']);
        
        
        get_insta_data_from_api_loop($dataArray['paging']['next'], $page+1);

    }
    
    
    
}

function get_insta_data_from_api() {
  
    $access_token = get_field('api_instagram_token', 'options');
    $link_insta_api = "https://graph.instagram.com/me/media?fields=id,caption,media_url,media_type,permalink,thumbnail_url,timestamp,children,children_media_url&access_token=".$access_token;
    $currentTime = new \DateTime();
    
    
    get_insta_data_from_api_loop($link_insta_api);
    
    global $listDataInsta;
    
    if (is_array($listDataInsta) && count($listDataInsta) > 3) {
        
            $args = array(
                'post_type' => 'instagram',
                'posts_per_page' => -1,
            );
            
            $the_query = new WP_Query($args);
            $list_ids = array();
            if ($the_query->have_posts()) {
                while ($the_query->have_posts()) {
                    $the_query->the_post();
                    
                    $list_ids[$the_query->post->ID] = true;
                }
            }
          
            
            
            foreach ($listDataInsta as $value) {
                $post_id = update_post_with_insta_id($value['id'], $value['caption'], $value);
                if (isset($list_ids[$post_id])) {
                    unset($list_ids[$post_id]);
                }
            }
            
            foreach ($list_ids as $key => $item) {
                wp_trash_post($key);
            }
            
          
            update_option('instagram_content', json_encode($dataArray['data']));
            update_option('instagram_time', $currentTime->format('U'));
        
    }
}


add_action("admin_menu", "cspd_imdb_options_submenu");
function cspd_imdb_options_submenu() {
  add_submenu_page(
        'edit.php?post_type=instagram',
        'Fetch new data from Instagram',
        'Fetch new data from Instagram',
        'administrator',
        'options-instagram',
        'admin_page_get_insta_data' );
}


function admin_page_get_insta_data() {
    echo "<h2>Get new data from INSTAGRAM</h2>";
    
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        get_insta_data_from_api();
        echo "Load Data Successful<br /><br />";
    }
    
    
    ?>
<form method="post">
  <input type="submit" class="primary button" value="Load Data" />
</form>
    <?php
}



function get_list_post_instagram() {
    $args = array(
        'post_type' => 'instagram',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'order' => 'ASC',
    );
    
    $data = array();
    $the_query = new WP_Query($args);
    $currentTime = new DateTime();
    if ($the_query->have_posts()) {
        while ($the_query->have_posts()) {
            $the_query->the_post();
            
            $item_id = $the_query->post->ID;
            $title = get_field('title', $the_query->post->ID);
            $caption = get_post_meta($item_id, 'insta_caption', true);
            
            $timestamp = get_post_meta($item_id, 'insta_timestamp', true);
            $position = strpos($timestamp, "T");
            $timeCreateU = $currentTime->format('U')*2;
            if ($position > 0) {
                $time = substr($timestamp, 0, $position);
                $timeCreate = \DateTime::createFromFormat("Y-m-d", $time);
                $timeCreateU = $timeCreate->format('U');
            }
            
                        
            $data[] = array(
                'title' => ($title != "")? $title : get_insta_title($caption),
                'caption' => $caption,
                'timeCreateU' => $timeCreateU,
                'timestamp' => get_post_meta($item_id, 'insta_timestamp', true),
                'media_type' => get_post_meta($item_id, 'insta_media_type', true),
                'caption' => get_post_meta($item_id, 'insta_caption', true),
                'media_url' => get_post_meta($item_id, 'insta_media_url', true),
                'thumbnail_url' => get_post_meta($item_id, 'insta_thumbnail_url', true),
                'data_child' => get_post_meta($item_id, 'insta_data_child', true),
                'permalink' => get_post_meta($item_id, 'insta_permalink', true),
                'instagram_id' => get_post_meta($item_id, 'instagram_id', true)
            );
            
            
            
        }
    }
    wp_reset_postdata();
    
    uasort($data, function ($a, $b) {
        return $a['timeCreateU'] < $b['timeCreateU'];
    });
    
    
    return $data;
}


function get_thumbnail_image_insta($insta_post_id) {
   
    $url = "";
    $type = get_post_meta($insta_post_id, "insta_media_type", true);
    if ($type == "CAROUSEL_ALBUM") {
        return get_post_meta($insta_post_id, "insta_media_url", true);
    }
    
    return get_post_meta($insta_post_id, "insta_thumbnail_url", true);
    
}



function get_post_by_insta_id($insta_id) {
    global $wpdb;
    $row = $wpdb->get_row( "SELECT * FROM $wpdb->postmeta WHERE meta_key='instagram_id' AND meta_value = ".$insta_id );
    if ($row) {
        return $row->post_id;
    }
    
    return null;
}
remove_action('wp_head', 'rel_canonical');




function update_post_with_insta_id($insta_id, $title, $args) {
  
    global $wpdb;
    
    $title = get_insta_title($title);    
    
    $row = $wpdb->get_row( "SELECT * FROM $wpdb->postmeta WHERE meta_key='instagram_id' AND meta_value = ".$insta_id );
    
    
    if ($row) {
        $update_info = array(
          'post_title'    => wp_strip_all_tags( $title ),
          'ID' => $row->post_id,
          'post_status' => 'publish'
        );
        
        $post_id = $row->post_id;
        
        wp_update_post(array(
            'ID' => $row->post_id,
            'post_status' => 'publish'
        ));
        
    } else {
        $my_post = array(
          'post_title'    => wp_strip_all_tags( $title ),
          'post_status'   => 'publish',
          'post_type'   => 'instagram',
        );

        // Insert the post into the database
        $post_id = wp_insert_post( $my_post );
        

    }
    
    
    
    update_field('instagram_id', $insta_id, $post_id);
    update_post_meta($post_id, 'insta_timestamp', $args['timestamp']);
    update_post_meta($post_id, 'insta_media_type', $args['media_type']);
    update_post_meta($post_id, 'insta_permalink', $args['permalink']);
    update_post_meta($post_id, 'insta_media_url', $args['media_url']);
    update_post_meta($post_id, 'insta_thumbnail_url', $args['thumbnail_url']);
    update_post_meta($post_id, 'insta_caption', $args['caption']);
    update_post_meta($post_id, 'insta_data_child', $args['data_child']);
    
    return $post_id;
}




add_action( 'rest_api_init', function () {
  
  register_rest_route( 'instagram/v1', '/sync', array(
    'methods' => 'GET',
    'callback' => 'instagram_cron_sync',
  ));
  register_rest_route( 'instagram/v1', '/refreshToken', array(
    'methods' => 'GET',
    'callback' => 'instagram_cron_refresh_token',
  ));
  
});


function instagram_cron_sync() {
  if (isset($_GET['key']) && $_GET['key'] == "6e41e029a74d56d21461f112d8e216e0") {
      get_insta_data();
      wp_send_json_success(array('message' => "DONE"));
  } else {
      wp_send_json_error(array('message' => "Invalid Key"));
  }
  exit();
}


function instagram_cron_refresh_token() {
  if (isset($_GET['key']) && $_GET['key'] == "6e41e029a74d56d21461f112d8e216e0") {
      
    $access_token = get_field('api_instagram_token', 'options');
    
    $link_insta_api = "https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=".$access_token;
    $currentTime = new \DateTime();
    
    $result = wp_remote_get($link_insta_api);
    if ($result['response']['code'] == 200) {
        $dataArray = json_decode($result['body'], true);
        if (is_array($dataArray) && isset($dataArray['access_token'])) {
            $list_old_insta_token = get_option('list_insta_token', []);
            if (!is_array($list_old_insta_token)) {
                $list_old_insta_token = [];
            }
            
            $current_time = new \DateTime();
            $list_old_insta_token[] = array(
                'date_run' => $current_time->format('Y-m-d H:i:s'),
                'access_token' => $dataArray['access_token'],
                'old_token' => $access_token
            );
            update_option('list_insta_token', $list_old_insta_token);
            update_field('api_instagram_token', $dataArray['access_token'], 'options');
        }
        
    }
    
      wp_send_json_success(array('message' => "DONE"));
  } else {
      wp_send_json_error(array('message' => "Invalid Key"));
  }
  exit();
}