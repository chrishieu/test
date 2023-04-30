<?php
global $post;
global $pageDefault;
$pageDefault = true;
?>

<!DOCTYPE html>
<html lang="en">
<?php get_header(); ?>
<body class="page-default">
    <style>
        .page-default .module-article-header .tag.size-lg {
            display: none;
        }
    </style>
<div class="page">
  <?php get_header('navigation'); ?>

  <main class="content">
    <section class="scene">
      <div class="article-content">
          <?php echo apply_filters("the_content", $post->post_content); ?>
      </div>
    </section>
    <!-- /section.scene -->
  </main>
  <!-- /main.content -->

  <?php get_footer('global'); ?>
</div>
<?php get_footer(); ?>
</body>
</html>

