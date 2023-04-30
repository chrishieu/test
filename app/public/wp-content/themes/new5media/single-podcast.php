<?php
/**
 * Created by IntelliJ IDEA.
 * User: apple
 * Date: 4/17/20
 * Time: 11:34 AM
 */
global $post;

?>



<!DOCTYPE html>
<html lang="en">
<?php get_header(); ?>
<body class="has-bg podcast-page">
<div class="page">
    <!-- @import partials/global-header.html data={"pageTitle": "5 podcast"} -->
    <?php

    global $header_title;
    $category_info = fivemedia_get_category_link($post);
    // $header_title  =  '5 podcast / '.$category_info['name'];
    $header_title  =  '5 podcast';
    get_header('navigation');

    ?>

    <main class="content">
        <section class="scene">
            <?php echo apply_filters("the_content", $post->post_content); ?>
        </section>
    </main>

    <?php get_footer('global'); ?>
</div>
<?php get_footer(); ?>
</body>
</html>

