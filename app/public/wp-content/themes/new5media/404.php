<?php
global $post;
?>
<?php

$media_text = get_field('media_text','options');

?>

<!DOCTYPE html>
<html>
<?php get_header(); ?>
<body class="">
<div class="page">
    <?php
    global $header_title;
    $header_title  =  "404";
    get_header('navigation');
    ?>

    <main class="content">
        <section class="scene">
            <div class="module-article-header style-1 header-404">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-12 col-sm-24 col-md-24 wrap-text">
                            <h1 class="bold"><?php echo get_field('404_title', 'options');  ?></h1>
                            <p class="desc"><?php echo get_field('404_text', 'options'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /section.scene -->

    </main>
    <!-- /main.content -->

    <?php get_footer('global'); ?>
</div>
<!-- /.page -->

<?php get_footer(); ?>
</body>
</html>
