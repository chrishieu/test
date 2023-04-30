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
    if($block['blockName'] == 'acf/hero-cta') {
        $header_color_module = 'primary-color';
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

if (strtolower($header_color_module) == '#ffd900') {
    $header_color_final = 'primary-color';
} else if (strtolower($header_color_module) == 'white') {
    $header_color_final = 'white';
} else if ($header_color_module == 'primary-color') {
    $header_color_final = $header_color_module;
}else {
    $header_color_final = '';
}

global $header_color;
if($header_color) {
    $header_color_final = $header_color;
}

$text_color = get_field('text_color', $post);
if ($text_color == 'text-white') {
    $header_color_final = 'white';
}
if ($text_color == 'text-yellow') {
    $header_color_final = 'primary-color';
}

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


<?php $top_menu = get_field('top_menu_category', 'options'); ?>
<header class="<?php echo $header_color_final; ?> top">
    <div class="main-bar">
        <a class="logo" href="<?php echo get_home_url(); ?>">5</a>

        <div class="btn-menu"><span></span></div>

        <?php // global $header_title; if($header_title):?>
            <!-- <div class="page-title" ><?php echo $header_title; ?></div> -->
        <?php // else :  ?>
              <ul class="page-title">
                  <?php foreach ($top_menu as $value) : ?>
                        <?php if (is_array($value['link'])): ?>
                        <li><a href="<?php echo $value['link']['url']; ?>"><?php echo $value['link']['title']; ?></a></li>
                        <?php endif; ?>
                  <?php endforeach; ?>
              </ul>
        <?php // endif; ?>
    </div>

    <div class="bg-active-menu"></div>
    <div class="wrap-nav">
        <div class="btn-menu on-nav"><span></span></div>
        <div class="inner-nav">
            <a class="logo" href="<?php echo get_home_url(); ?>">5</a>

            <ul class="category">
                 <?php $menu_categories = get_field('menu_category', 'options'); ?>
                 <?php foreach ($menu_categories as $value) : ?>
                <?php if (is_array($value['link'])): ?>
                <li>
                    <h2><a href="<?php echo $value['link']['url']; ?>" <?php if ($current_link == $value['link']['url']): ?>class="active"<?php endif; ?>><?php echo $value['link']['title']; ?></a></h2>
                </li>
                <?php endif; ?>
                <?php endforeach; ?>
            </ul>

            <?php wp_nav_menu(array(
                'menu' => 'main-menu',
                'menu_class' => 'main-nav',
                'container' => '',
                'link_before' => '<h2>',
                'link_after' => '<span class="collapse-btn"></span></h2>',
                'walker' => new Fivemedia_Sublevel_Walker()
            )); ?>

            <div class="newsletter-signup js-newsletter">
                <form method="POST" action="https://8d44db38.sibforms.com/serve/MUIEAGLvtVUTNQEr3_nb8pWJKYDWI-wyELI_qrUbxZa0YRKwaV5A1ARsx-pjWjM2pa7zNIsxyKew3Qpb9MYmDdKfMXL2hQVk3PsjAZt2hMqXxzzGZ1da4XolIW1qwX2EIsIErGmiNRihkElRuooFYum5ShBL6uaB55OeKWaQyHPdPEx_Nt4ob_R9qFEUed-7WHvlH7a13bO9yFHP">
                    <img src="<?php echo get_stylesheet_directory_uri() ?>/www/img/Sign-up 1.png" alt="">
                    <h3>Join our community</h3>
                    <p>We promise you no spam.<br>Only inspiring stories that honor our values and curiosity.</p>
                    <div class="input-group"><input class="input" maxlength="200" type="text" id="FIRSTNAME" name="FIRSTNAME" autocomplete="off" placeholder="First name" data-required="true" required /></div>
                    <div class="input-group"><input class="input" maxlength="200" type="email" id="EMAIL" name="EMAIL" autocomplete="off" placeholder="Email address" data-required="true" required /></div>
                    <div class="input-group">
                        <button type="submit">Sign up <svg width="14px" height="9px" viewBox="0 0 14 9" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Design" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="Front-Page--Header--1--XL-Copy" transform="translate(-87.000000, -297.000000)" fill="currentColor" fill-rule="nonzero"><g id="Title-Area" transform="translate(0.000000, -5.000000)"><path d="M91,302 L92,302.618693 L88.798,306 L101,306 L101,307 L88.8,307 L92,310.381307 L91,311 L87.2,307 L87,307 L87,306 L87.199,306 L87.2,306 L91,302 Z" id="Combined-Shape" transform="translate(94.000000, 306.500000) scale(-1, 1) translate(-94.000000, -306.500000) "></path></g></g></g></svg></button>
                    </div>
                    <input type="hidden" name="email_address_check">
                    <input type="hidden" name="locale" value="en">
                </form>
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
