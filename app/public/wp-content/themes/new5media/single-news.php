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
<body class="">

<?php 

// Special interactive article
if (is_single(7414)) :
    
    include get_stylesheet_directory()."/page-templates/bespoke-tpa.php";

elseif (is_single(10076)) :
    
    include get_stylesheet_directory()."/page-templates/bespoke-tpa-2.php";

else : ?>

    <div class="page">
        <?php

        global $header_title;
        $category_info = fivemedia_get_category_link($post);
        $header_title  =  'Article';
        get_header('navigation');

        ?>

        <main class="content">
            <section class="scene">
                <!-- @import partials/module-article-header-theme-1.html -->

                <?php

                $style = get_field('article_header_style', $post->ID);
                $text = get_field('article_header_text', $post->ID);
                $photo = get_field('article_header_photo', $post->ID);
                $photos = get_field('article_header_photos', $post->ID);
                $description = get_field('article_header_description', $post->ID);
                $image = get_field('article_header_image', $post->ID);
                $style_otp = get_field('style', $post->ID);
                $colorX = get_field('color', $post->ID);
                $color_options = get_field('color_options', $post->ID);
                $color_bg = get_field('color_bg', $post->ID);
                $video = get_field('video', $post->ID);
                $caption = get_field('caption', $post->ID);
                $color = "";
                
                ?>

                <?php include get_stylesheet_directory()."/components/partial/article-header.php"; ?>
                
                <div class="article-content">
                    <?php include get_stylesheet_directory()."/components/partial/follow-us.php"; ?>
                    <?php echo apply_filters("the_content", $post->post_content); ?>
                </div>                
                    
            </section>
        </main>
        <?php get_footer('global'); ?>
    </div>

<?php endif; ?>

<?php get_footer(); ?>
</body>
</html>