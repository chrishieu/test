<?php /* Template Name: Landingpage old */ ?>


<!DOCTYPE html>
<html lang="en">
<?php get_header(); ?>
<body class="has-bg front-page">
<div class="page">
  <?php get_header('navigation'); ?>

  <main class="content">
    <section class="scene">
        <?php echo apply_filters("the_content", $post->post_content); ?>
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
