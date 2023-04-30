
<?php 
    global $post_id, $post_title; 
    $mp3_file = get_field('mp3_file', $post_id); 
?>
<div class="article-player container-fluid">
  <div class="row">
    <div class="col-md-6 hidden-xs hidden-sm left article-player-label"><?php echo $post_title; ?></div>
    <div class="col-sm-16 col-sm-push-4 col-md-12 col-md-push-0">
      <div class="wrapper">
        <audio
          src="<?php echo $mp3_file['url']; ?>"
          preload="metadata"
        ></audio>
        <button class="btn-rewind" type="button"><img src="<?php echo get_stylesheet_directory_uri() ?>/www/img/rewind.svg" alt="rewind"></button>
        <button class="btn-play" type="button"></button>
        <button class="btn-forward" type="button"><img src="<?php echo get_stylesheet_directory_uri() ?>/www/img/forward.svg" alt="forward"></button>
        <span class="current-time">0:00</span>
        <input class="seeker" type="range" value=0 step=0.01>
        <span class="remaining"></span>
        <button class="btn-close is-mobile" type="button"></button>
      </div>
    </div>
    <div class="col-md-6 hidden-xs hidden-sm right"><button class="btn-close is-desktop" type="button"></button></div>
  </div>
</div>

<script src="https://www.google.com/recaptcha/api.js?render=6Ldhc5IiAAAAAAiJOoB1sler-qxJ7glQSgExWzwX"></script>
<input type="hidden" id="inputRecaptchaSiteKey" value="6Ldhc5IiAAAAAAiJOoB1sler-qxJ7glQSgExWzwX">

<!-- build:js js/libs.js -->
<script src="<?php echo get_stylesheet_directory_uri() ?>/www/js/libs.js?ver=<?php echo fivemedia_asset_versioning(); ?>"></script>
<!-- endbuild -->

<!-- build:js js/globals.js -->
<script src="<?php echo get_stylesheet_directory_uri() ?>/www/js/globals.js?ver=<?php echo fivemedia_asset_versioning(); ?>"></script>
<!-- endbuild -->

<!-- build:js js/modules.js -->
<script src="<?php echo get_stylesheet_directory_uri() ?>/www/js/modules.js?ver=<?php echo fivemedia_asset_versioning(); ?>"></script>
<!-- endbuild -->

<?php wp_footer(); ?>