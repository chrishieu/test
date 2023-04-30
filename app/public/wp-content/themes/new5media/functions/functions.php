<?php

// Enqueue scripts and styles --------------------------------------------------
function etypes_add_css_js()
{
	// Add css
	wp_enqueue_style('main-css', get_template_directory_uri() . '/style.css', array(), false);
	// Add js
	//wp_enqueue_script('jeu-mdernize', get_template_directory_uri() . '/assets/js/plugins/modernizr.custom.js', array(), '1', false);
	wp_localize_script( 'mainjs', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
}

add_action('wp_enqueue_scripts', 'etypes_add_css_js');


// Add menu support ------------------------------------------------------------
add_theme_support('menus');
function etypes_register_menu()
{
	register_nav_menu('main-menu', __('Main Menu'));
	register_nav_menu('sub-menu', __('Sub Menu'));
	register_nav_menu('footer-menu', __('Footer Menu'));
}

add_action('init', 'etypes_register_menu');


// SVG Support ---------------------------------------------------
function wp_allow_svg_mime_type($mimes = array())
{

	// Add as many keys/values to the $mimes Array as needed
	// Add a key and value for the CSV file type
	$mimes['svg'] = "image/svg+xml";
	return $mimes;
}
add_action('upload_mimes', 'wp_allow_svg_mime_type');

add_filter('_wp_post_revision_fields', 'add_field_debug_preview');
function add_field_debug_preview($fields){
    $fields["debug_preview"] = "debug_preview";
    return $fields;
}

add_action( 'edit_form_after_title', 'add_input_debug_preview' );
function add_input_debug_preview() {
    echo '<input type="hidden" name="debug_preview" value="debug_preview">';
}

function get_image_src( $img_id, $size = null ) {
    $img = wp_get_attachment_image_src($img_id, $size);
    $alt = get_post_meta($img_id, '_wp_attachment_image_alt', true);
    return array(
        'url' => $img[0],
        'width' => $img[1],
        'height' => $img[2],
        'alt' => $alt
    );
}

add_filter('nav_menu_css_class' , 'special_nav_class' , 10 , 2);


function etypes_get_img_srcset($image) {
    if (is_array($image)) {
        return $image['sizes']['16_9_large']." 1920w, ".$image['sizes']['16_9_medium']." 1280w, ".$image['sizes']['16_9_medium']." 640w";
    }
}

function special_nav_class ($classes, $item) {
    if (in_array('current-menu-item', $classes) ){
        $classes[] = 'active ';
    }

    return $classes;
}

function get_embed_video_id($type, $embed_string)
{
    switch ($type) {
        case 'vimeo':
            $id = get_string_between($embed_string, 'video/', '?app_id');
            break;
        case 'youtube':
            $id = get_string_between($embed_string, 'embed/', '?feature');
            break;
    }
    return $id;
}

function get_string_between($string, $start, $end)
{
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

function pagination_custom($object_lists, $paged, $total_pages = null)
{
    $max = intval( $object_lists->max_num_pages);
    if($total_pages) $max = $total_pages;

    if ( $paged >= 1 )
        $links[] = $paged;

    if ( $paged >= 3 ) {
        $links[] = $paged - 1;
        $links[] = $paged - 2;
    }

    if ( ( $paged + 2 ) <= $max ) {
        $links[] = $paged + 2;
        $links[] = $paged + 1;
    }
    $string = '';
    $string .= '<ul>';


    if ($paged != 1)
        $string .= '<li>'.'<a href="'.esc_url(get_pagenum_link($paged - 1)).'"><i class="icon-arrow thin left"></i></a>'.'</li>';


    for($i = 1; $i <= $max; $i++)
    {
        $string .= '<li>'.'<a href="'.esc_url(get_pagenum_link($i)).'"';
        $string .= ($i == $paged) ? ' class="active" ' : '';
        $string .= '>'.$i.'</a>'.'</li>';

    }
    if($paged != $max)
        $string .= '<li>'.'<a href="'.esc_url(get_pagenum_link($paged + 1)).'"><i class="icon-arrow thin"></i></a>'.'</li>';



    $string .= '</ul>';
    return $string;

}

add_action( 'wp_ajax_subscribeMailchimp', 'subscribeMailchimp' );
add_action( 'wp_ajax_nopriv_subscribeMailchimp', 'subscribeMailchimp' );
function subscribeMailchimp() {
    $apiKey = get_field('newsletter_mailchimp_id', 'options');
    $listId = get_field('newsletter_mailchimp_list_id_dan', 'options');

    $email = (isset($_POST['email'])) ? esc_attr($_POST['email']) : '';
    $lang = (isset($_POST['lang'])) ? esc_attr($_POST['lang']) : '';
    if($lang != 'da') {
        $listId = get_field('newsletter_mailchimp_list_id_ita', 'options');
    }
    $memberId = md5(strtolower($email));
    $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
    $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;

    $json = json_encode([
        'email_address' => $email,
        'status'        => 'subscribed', // "subscribed","unsubscribed","cleaned","pending"
        'merge_fields'  => [
            'FNAME'     => '',
            'LNAME'     => ''
        ]
    ]);

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $response = array(
        'http_code' => $httpCode,
    );

    echo json_encode($response);
    die();
}




// add_action('pre_get_posts', 'filter_posts_list'); 
// function filter_posts_list($query) {
//         //$pagenow holds the name of the current page being viewed
//          global $pagenow, $typenow;  
//          $post_type = $query->query['post_type'];
//         if(current_user_can('edit_posts') && ('edit.php' == $pagenow) && in_array($post_type, array('news', 'gallery', 'interview', 'podcast', 'video', 'review', 'curated', 'essay', 'five_toolkit', 'high_five')))  { 
//             //global $query's set() method for setting
//             $query->set('orderby', 'date');
//             $query->set('order', 'desc');
//         }
//     }

function etypes_get_type_name($post_type) {
    $mappingType = array(
        'news' => 'Article',
        'podcast' => 'Podcast',
        'review' => 'Review',
        'interview' => 'Interview',
        'video' => 'Video', 
        'gallery' => 'Gallery',
        'theme' => 'Theme',
        'essay' => 'Essay',
        'curated' => 'Curated',
        'five_toolkit' => '5 Toolkit',
        'high_five' => 'High 5'

    );
    
    if (isset($mappingType[$post_type])) {
        return $mappingType[$post_type];
    }

    return '';
}

function get_term_string_by_post_id($id, $term_name)
{
    $ark_term = get_the_terms($id, $term_name);
    $ark_term_str = '';
    if (is_array($ark_term)) {
        $ark_term_arr_name = array();

        foreach ($ark_term as $item) {
            $ark_term_arr_name[] = $item->name;
        }
        $ark_term_str = implode(', ', $ark_term_arr_name);
    }
    return $ark_term_str;
}