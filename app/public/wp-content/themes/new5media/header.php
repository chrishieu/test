<?php
global $post;
global $insta_post_id;
global $seo_title;
$seo_title = get_field('seo_title', $post->ID);
if ($seo_title == "") { $seo_title = $post->post_title; }
$seo_description = get_field('seo_description', $post->ID);
if(!trim($seo_description)) $seo_description = '5 is a non-profit media foundation telling strong and inspiring stories, spotlighting new perspectives and courageous profiles. Join us here.';
if(strlen($seo_description) > 160) $seo_description = mb_substr($seo_description, 0, 160) . '...';
$seo_image = get_field('seo_image', $post->ID);

$canonical_url = get_field('canonical_url', $post->ID);

$seo_site = "5 Media";

if (isset($_GET['insta'])) {

    $insta_post_id = get_post_by_insta_id($_GET['insta']);

    if ($insta_post_id) {
        $insta_post = get_post($insta_post_id);
        $seo_title = esc_attr($insta_post->post_title);

        $seo_description = esc_attr(get_post_meta($insta_post_id, "insta_caption", true));


        $seo_image = array(
            'url' => get_thumbnail_image_insta($insta_post_id)
        );
        $canonical_url = null;

    }




}


?>
<head>
  <title> <?php wp_title("|", true, 'right'); ?> 5 Media</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name=viewport content="width=device-width, initial-scale=1, viewport-fit=cover">

  <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri() ?>/www/img/favicons/favicon.ico">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_stylesheet_directory_uri() ?>/www/img/favicons/apple-touch-icon.png">
	<link rel="icon" sizes="32x32" href="<?php echo get_stylesheet_directory_uri() ?>/www/img/favicons/favicon-32x32.png">
	<link rel="icon" sizes="16x16" href="<?php echo get_stylesheet_directory_uri() ?>/www/img/favicons/favicon-16x16.png">
	<link rel="mask-icon" href="<?php echo get_stylesheet_directory_uri() ?>/www/img/favicons/safari-pinned-tab.svg">

  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="<?php echo get_stylesheet_directory_uri() ?>/www/img/favicons/ms-icon-144x144.png">
  <meta name="theme-color" content="#ffffff">
  <?php if ($canonical_url != "") : ?>
      <link rel="canonical" href="<?= $canonical_url; ?>" />
  <?php endif; ?>
  <meta name="description" content="<?= $seo_description; ?>" />

  <meta property="og:type" content="website" />
  <?php if (!$insta_post_id) : ?>
    <meta property="og:url" content="<?= get_permalink($post->ID); ?>" />
  <?php endif; ?>

  <meta property="og:title" name="title" content="<?= $seo_title; ?>" />
  <meta property="og:description" content="<?= $seo_description; ?>" />
  <meta property="og:site_name" content="<?= $seo_site; ?>" />
  <meta property="og:image" content="<?= $seo_image['url']; ?>" />

  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:site" content="<?= $seo_site; ?>">
  <meta name="twitter:title" content="<?= $seo_title; ?>">
  <meta name="twitter:description" content="<?= $seo_description; ?>">
  <meta name="twitter:image" content="<?= $seo_image['url']; ?>">

  <script id="Cookiebot" src="https://consent.cookiebot.com/uc.js" data-cbid="335abfc0-1b37-4d10-ae2f-a1a2e2717851" type="text/javascript" async></script>

  <?php  wp_head(); ?>



  <!-- build:css css/style.css -->
  <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri() ?>/www/css/style.css?ver=<?php echo fivemedia_asset_versioning(); ?>">
  <!-- endbuild -->

    <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri() ?>/donate.css?ver=<?php echo fivemedia_asset_versioning(); ?>">

  <!-- Load Facebook SDK for JavaScript -->
  <div id="fb-root"></div>
  <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
          fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));</script>

  <!-- Load Twitter SDK for JavaScript -->
  <script>window.twttr = (function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0],
              t = window.twttr || {};
          if (d.getElementById(id)) return t;
          js = d.createElement(s);
          js.id = id;
          js.src = "https://platform.twitter.com/widgets.js";
          fjs.parentNode.insertBefore(js, fjs);

          t._e = [];
          t.ready = function(f) {
              t._e.push(f);
          };

          return t;
      }(document, "script", "twitter-wjs"));</script>

  <?php
      $googleTagId = get_field('footer_google_tag_id', 'options');
      if ($googleTagId == "") {
          $googleTagId = "GTM-NNQPB7L";
      }
  ?>

  <!-- Google Tag Manager -->
  <script>
  (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','<?php echo $googleTagId; ?>');

  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  </script>
  <!-- End Google Tag Manager -->

  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-153156965-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-153156965-1');
  </script>

  <script type="text/javascript">
  // https://brm.us/100vh
  const setVh = function () {
    const vh = window.innerHeight * .01;
    document.documentElement.style.setProperty('--vh', vh + 'px');
  };

  setVh();
  window.addEventListener('load', setVh);
  window.addEventListener('resize', setVh);
  </script>
</head>
