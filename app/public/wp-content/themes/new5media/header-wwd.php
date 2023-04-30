<?php


$menus     = get_terms( 'nav_menu' );
$arr_menus = array();
$menu      = array();

foreach ( $menus as $menu ) {
    $arr_menu         = array();
    foreach ( wp_get_nav_menu_items( $menu->slug ) as $item ) {
        $arr_item           = array();
        $arr_item['ID']  = $item->ID;
        $arr_item['title']  = $item->title;
        $arr_item['guid']   = $item->guid;
        $arr_item['type']   = $item->object;
        $menu_post          = get_post( $item->object_id );
        $arr_item['slug']   = $menu_post->post_name;
        $arr_item['url']    = $item->url;
        $arr_menu['item'][] = $arr_item;
    }
    $arr_menus[] = $arr_menu;
}

$pod_cast_image = get_field('pod_cast_image', 'options');
$pod_cast_title = get_field('pod_cast_title', 'options');
$pod_cast_tag = get_field('pod_cast_tag', 'options');
$pod_cast_button = get_field('pod_cast_button', 'options');

$podcast = get_field('podcast', 'options');
$feature_image = get_field('feature_image', $podcast->ID);

global $post;
$block_contents = parse_blocks( $post->post_content);
$header_color_module = '';
$header_color_final = '';
foreach ($block_contents as $block) {
    if($block['blockName'] == 'acf/landing-page-hero-section') {
        $header_color_module = $block['attrs']['data']['color'];
    }
    if($block['blockName'] == 'acf/article-header') {
        switch($block['attrs']['data']['article_header_style']) {
            case 'style-1':
                $header_color_module = '';
                break;
            case 'style-2':
                $header_color_module = $block['attrs']['data']['color'];
                break;
            case 'style-3':
                $header_color_module = $block['attrs']['data']['color'];
                break;
            case 'style-4':
                $header_color_module = '';
                break;
        }
    }
}

if(in_array($post->post_type, array('theme', 'news', 'gallery', 'interview', 'podcast', 'video', 'review', 'curated', 'essay', 'five_toolkit', 'high_five'))) {
    $color = get_field('color', $post->ID);
    $article_header_style = get_field('article_header_style', $post->ID);
    switch($article_header_style) {
        case 'style-1':
            $header_color_module = '';
            break;
        case 'style-2':
            $header_color_module = $color;
            break;
        case 'style-3':
//      $header_color_module = $color;
            $header_color_module = '';
            break;
        case 'style-4':
            $header_color_module = '';
            break;
    }
}
global $header_color;
$text_color = get_field('text_color', $post);
$header_color_final = 'white';

?>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NNQPB7L"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '255515622668511');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=255515622668511&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->



<header class="<?php echo $header_color_final; ?> top">
    <div class="main-bar">
        <a class="logo" href="<?php echo get_home_url(); ?>">5</a>
    
        <div class="btn-menu"><span></span></div>
    
        <?php global $header_title; if($header_title):?>
            <div class="page-title" ><?php echo $header_title; ?></div>
        <?php endif; ?>
    </div>

    <div class="bg-active-menu"></div>
    <div class="wrap-nav wrap-nav-article">
        <div class="btn-menu on-nav"><span></span></div>
        <div class="inner-nav">
            <a class="logo" href="<?php echo get_home_url(); ?>">5</a>
            <div>
                <div class="category">
                    <h5>Categories:</h5>
                    <?php $menu_categories = get_field('menu_category', 'options'); ?>
                    <?php foreach ($menu_categories as $value) : ?>
                        <?php if (is_array($value['link'])): ?>
                        <?php $current_link = get_permalink($post); ?>
                        <a href="<?php echo $value['link']['url']; ?>" class="h2 <?php if ($current_link == $value['link']['url']): ?>active<?php endif; ?>"><?php echo $value['link']['title']; ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <hr>
                <?php wp_nav_menu(array(
                    'menu' => 'main-menu',
                    'menu_class' => 'main-nav',
                    'container' => '',
                    'link_before' => '<h2><span>',
                    'link_after' => '</span></h2><span class="collapse-btn"></span>',
                    'walker' => new Fivemedia_Sublevel_Walker()
                )); ?>
            </div>
            <div class="wrap-social-media">
                <span>Follow us:</span>
                <ul class="social-media">
                    <li class="social">
                        <a href="<?php echo get_field('footer_linked_link', 'options'); ?>" target="_blank">
                            <img src="<?php echo get_stylesheet_directory_uri() ?>/www/img/icon-linkedin.svg">
                        </a>
                    </li>
                    <li class="social">
                        <a href="<?php echo get_field('footer_facebook_link', 'options'); ?>" target="_blank">
                            <img src="<?php echo get_stylesheet_directory_uri() ?>/www/img/icon-facebook.svg">
                        </a>
                    </li>
                    <li class="social">
                        <a href="<?php echo get_field('footer_twitter_link', 'options'); ?>" target="_blank">
                            <img src="<?php echo get_stylesheet_directory_uri() ?>/www/img/icon-twitter.svg">
                        </a>
                    </li>
                    <li class="social">
                        <a href="<?php echo get_field('footer_instagram_link', 'options'); ?>" target="_blank">
                            <img src="<?php echo get_stylesheet_directory_uri() ?>/www/img/icon-instagram.svg">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
