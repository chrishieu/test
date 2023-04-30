<?php

$linksendinblue = get_field('footer_subscribe_link_form', 'options');
$footer_social_links = get_field('footer_social_links', 'options');
if ($linksendinblue == "") {
    $linksendinblue = "https://8d44db38.sibforms.com/serve/MUIEAGLvtVUTNQEr3_nb8pWJKYDWI-wyELI_qrUbxZa0YRKwaV5A1ARsx-pjWjM2pa7zNIsxyKew3Qpb9MYmDdKfMXL2hQVk3PsjAZt2hMqXxzzGZ1da4XolIW1qwX2EIsIErGmiNRihkElRuooFYum5ShBL6uaB55OeKWaQyHPdPEx_Nt4ob_R9qFEUed-7WHvlH7a13bO9yFHP";
}

$hide_signup = get_field('hide_newsletter_signup', $post->ID);

?>

<footer>
  <!-- @import partials/module-newsletter.html -->
  <?php if (!$hide_signup) : ?>
  <div class="newsletter js-newsletter">
    <div class="container-fluid">
      <div class="row">
        <div class="subscribe text-center"><?php echo get_field('footer_subscribe', 'options'); ?></div>
        <div class="subscribe-sub text-center"><?php echo get_field('footer_subscribe_sub', 'options'); ?></div>
        <!--<div class="subscribe-inputs">
          <input type="text" class="subscribe-input" placeholder="Your email address" />
          <a href="#" class="btn fill-btn"></a>
        </div>-->
          <div class="subscribe-signup">
              <!-- START - Sendinblue form -->
              <form method="POST" data-type="" action="<?php echo $linksendinblue; ?>">
                  <div class="signup-col">
                      <input class="input" maxlength="200" type="text" id="FIRSTNAME" name="FIRSTNAME" autocomplete="off" placeholder="First name" data-required="true" required />
                      <input class="input hidden" maxlength="200" type="text" id="LASTNAME" name="LASTNAME" autocomplete="off" placeholder="Last name" data-required="true" />
                  </div>
                  <div class="signup-col">
                      <input class="input" maxlength="200" type="email" id="EMAIL" name="EMAIL" autocomplete="off" placeholder="Email address" data-required="true" required />
                  </div>
                  <div class="signup-col">
                      <button type="submit">Sign up</button>
                  </div>

                  <input type="text" name="email_address_check" value="" class="hidden">
                  <input type="hidden" name="locale" value="en">
              </form>
              <!-- END - Sendinblue  -->
          </div>
          <div class="reply" >
          </div>

          <div class="subscribe-success">
              <div class="sign-up-success-form">
                  <div class="sign-up-success-form-inner">
                      <h2 class="suf-header">Thanks for joining!</h2>
                      <i class="suf-close"></i>
                      <div class="suf-content">
                          <p>
                          You have now subscribed to the 5 Letter. You will receive a welcome email from us shortly. Check your spam folder if you can't find it.
                          </p>
                          <p>
                          Add <a href="mailto:5letter@fivemedia.com">5letter@fivemedia.com</a> to your contacts to make sure all our emails land in your inbox.
                          </p>
                      </div>

                      <div class="suf-footer">
                          We appreciate you recommending 5 to friends
                          <ul class="suf-social-sharing">
                              <li class="suf-social-sharing-item">
                                  <a href="" target="_blank" class="socialSharingUrls linkedIn"><i class="icon-linked-in "></i></a>
                              </li>
                              <li class="suf-social-sharing-item">
                                  <a href="" target="_blank" class="socialSharingUrls facebook"><i class="icon-facebook"></i></a>
                              </li>
                              <li class="suf-social-sharing-item">
                                  <a href="" target="_blank" class="socialSharingUrls twitter"><i class="icon-twitter"></i></a>
                              </li>
                          </ul>
                      </div>
                  </div>
              </div>
          </div>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <div class="container-fluid">
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-10">
        <span class="logo">5</span>
          <span class="about">
              <?php echo get_field('footer_about_text', 'options'); ?>
              <?php $about_link = get_field('footer_about_link', 'options'); ?>
              <a href="<?php echo $about_link['url']; ?>"><?php echo $about_link['title']; ?></a>
          </span>
      </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-md-offset-2 offices">
            <h4><?php echo get_field('offices_title', 'options'); ?></h4>

            <?php $list_offices = get_field('list_offices', 'options'); ?>
            <div class="row">
                <?php if (is_array($list_offices)): foreach ($list_offices as $value) : ?>
                <div class="col-sm-12">
                    <?php echo $value['item']; ?>
                </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="wrap-social-media">
            <span>Follow us:</span>
              <ul class="social-media">
                <li class="social">
                  <a href="<?php echo get_field('footer_linked_link', 'options'); ?>" target="_blank">
                    <img src="<?php echo get_stylesheet_directory_uri() ?>/www/img/icon-linkedin-2-black.svg">
                  </a>
                </li>
                <li class="social">
                  <a href="<?php echo get_field('footer_facebook_link', 'options'); ?>" target="_blank">
                    <img src="<?php echo get_stylesheet_directory_uri() ?>/www/img/icon-facebook-2-black.svg">
                  </a>
                </li>
                <li class="social">
                  <a href="<?php echo get_field('footer_twitter_link', 'options'); ?>" target="_blank">
                    <img src="<?php echo get_stylesheet_directory_uri() ?>/www/img/icon-twitter-2-black.svg">
                  </a>
                </li>
                <li class="social">
                  <a href="<?php echo get_field('footer_instagram_link', 'options'); ?>" target="_blank">
                    <img src="<?php echo get_stylesheet_directory_uri() ?>/www/img/icon-instagram-2-black.svg">
                  </a>
                </li>
              </ul>
        </div>
      <ul class="footer-nav">
        <?php foreach ($footer_social_links as $item):?>
          <?php if (is_array($item['link'])) : ?>
          <li>
            <a href="<?php echo $item['link']['url']; ?>"><?php echo $item['link']['title']; ?></a>
          </li>
          <?php endif; ?>
        <?php endforeach; ?>
        <li>
            <a href="javascript: Cookiebot.renew()" class="btn-cookie-settings">Cookie settings</a>
        </li>
      </ul>
      <span class="copyright"><?php echo get_field('footer_copyright', 'options'); ?></span>
    </div>
  </div>
</footer>
