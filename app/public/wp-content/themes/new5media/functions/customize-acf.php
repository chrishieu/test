<?php

// Add custom styles/CSS for ACF ---------------------------------------------------
function load_custom_wp_admin_style() {
  wp_register_style('acf_styles', get_template_directory_uri() . '/admin/css/acf-styles.css', false, '1.0.0' );
  wp_enqueue_style( 'acf_styles' );
}
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );



// Customize ACF WYSIWYG settings ----------------------------------------------
// https://www.advancedcustomfields.com/resources/customize-the-wysiwyg-toolbars/
// 
function etypes_acf_wysiwyg_setting( $toolbars ) {
  
  // Set 'BASIC'
  $toolbars['Basic'] = array();
  $toolbars['Basic'][1] = array('bold', 'link', 'unlink');

  // Set 'FULL'
  $toolbars['Full'] = array();
  $toolbars['Full'][1] = array('formatselect', 'bold', 'italic', 'bullist', 'link', 'unlink');
  
  // Two levels
  // $toolbars['Full'][1] = apply_filters('mce_buttons', array('bold', 'italic', 'strikethrough', 'bullist', 'numlist', 'blockquote', 'justifyleft', 'justifycenter', 'justifyright', 'link', 'unlink', 'wp_more', 'spellchecker', 'fullscreen', 'wp_adv' ), $editor_id);
  // $toolbars['Full'][2] = apply_filters('mce_buttons_2', array( 'formatselect', 'underline', 'justifyfull', 'forecolor', 'pastetext', 'pasteword', 'removeformat', 'charmap', 'outdent', 'indent', 'undo', 'redo', 'wp_help', 'code' ), $editor_id);

	// remove the 'Full' toolbar completely (if you want)
	// unset( $toolbars['Basic' ] );

	// IMPORTANT!
	return $toolbars;
}
add_filter('acf/fields/wysiwyg/toolbars' , 'etypes_acf_wysiwyg_setting');


// Wysiwyg -> formatselect -----------------------------------------------------
// Change dropdownlist "P, H1, H2, H3, H4, H5, H6"
// https://support.advancedcustomfields.com/forums/topic/wysiwyg-formatselect/
// https://stevegrunwell.com/blog/wordpress-tinymce-block-formats/
function etypes_wysiwyg_formatselect_setting( $init ) {
	$block_formats = array(
    'Paragraph=p',
    'Larger Headline=h2',
    'Headline=h3',
		'Paragraph header=h4',
//		'Heading Small=h5',
	);
	$init['block_formats'] = implode( ';', $block_formats );
 
	return $init;
}
add_filter( 'tiny_mce_before_init', 'etypes_wysiwyg_formatselect_setting' );


// // Google maps api key
// function my_acf_init() {
// 	acf_update_setting('google_api_key', 'AIzaSyBRvG5xxjYcVbO8Hx4DbUrfdEFhrl6dbi0');
// }
// add_action('acf/init', 'my_acf_init');



// Create color palette --------------------------------------------------------
// Choose which colors to display in the color picker 
// https://support.advancedcustomfields.com/forums/topic/color-picker-custom-pallete/
function acf_set_color_palette() {
  ?>
  <script type="text/javascript">
    (function($){

      acf.add_filter('color_picker_args', function( args, $field ){
       
        // Set standard colors
        args.palettes = ['#FE385B', '#651DCC', '#00794D', '#FD5936', '#002B7B'];
        
        // // Set unique colors for field
        // if ($field.hasClass('acf-field-5bb718dda50fb')) {
        //     args.palettes = ['#F0EBE7', '#ffffff'];
        // }

        // return
        return args;
      });

    })(jQuery);
  </script>
  <?php
}
//add_action('acf/input/admin_footer', 'acf_set_color_palette');


// Style color picker, so ONLY the palette is visible --------------------------
function acf_hide_color_picker_css() {
  ?>
  <style>
    .iris-picker.iris-border{
      width: 200px !important;
      height: 10px !important;
    }
    .iris-picker-inner,
    .wp-picker-input-wrap,
    .iris-picker .iris-slider,
    .iris-picker .iris-square {
      display:none !important;
    }
  </style>
  <?php
}
//add_action('acf/input/admin_head', 'acf_hide_color_picker_css');


// Modify the HTML displayed at the top of each flexible content layout --------
// https://www.advancedcustomfields.com/resources/acf-fields-flexible_content-layout_title/
function etypes_flexible_content_layout_title( $title, $field, $layout, $i ) {

  // Standard Blocks:
  // Block – List Automatic           - Headline  
  // Block – List Manual              - Headline  
  // Block – List Populated           - Headline  
  // Block – Mailchimp signup         - Headline  
  // Block – Promotion                - Headline  
  // Block – Quote                    - Quote
  // Block – Text editor              - Text editor
  // Block – Selfhosted video         - Poster Image
  // Block – Image                    - Image
  // Block – Embed video              - EMPTY

	// Check Subfield in BLOCK
  // Get field "Headline" from each block
	if( $promotion = get_sub_field('promotion_headline') ) {
    $text = $promotion;

	} else if( $list_automatic = get_sub_field('list_automatic_headline') ) {
    $text = $list_automatic;

  } else if( $list_manual = get_sub_field('list_manual_headline') ) {
    $text = $list_manual;

  } else if( $list_populated = get_sub_field('list_populated_headline') ) {
    $text = $list_populated;
  
  } else if( $list_populated = get_sub_field('list_populated_3col_headline') ) {
    $text = $list_populated;
    
  } else if( $list_populated_employee = get_sub_field('list_populated_employee_headline') ) {
    $text = $list_populated_employee;

  } else if( $text_editor = get_sub_field('text_editor') ) {
    $text = strip_tags($text_editor);

  } else if( $quote = get_sub_field('quote_text') ) {
    $text = $quote;

  } else if( $mailchimp = get_sub_field('newsletter_headline') ) {
    $text = $mailchimp;
  } else {
	$text = '';
	}

  // Cut string to max 50 characters
  $text = strlen($text) > 50 ? substr($text, 0, 50)."..." : $text;

  // Load sub field image
	if( $image = get_sub_field('single_image_file') ) {
		$media = '<div class="acf-thumb"><img src="' . $image['sizes']['thumbnail'] . '" alt="' . $image['alt'] . '" height="36px" /></div>';

  } else if( $image = get_sub_field('double_image_one') ) {
    $media = '<div class="acf-thumb"><img src="' . $image['sizes']['thumbnail'] . '" alt="' . $image['alt'] . '" height="36px" /></div>';
    
	} else if( $selfhosted = get_sub_field('selfhosted_poster_image') ) {
    $media = '<div class="acf-thumb"><img src="' . $selfhosted['sizes']['thumbnail'] . '" alt="' . $selfhosted['alt'] . '" height="36px" /></div>';
  } else {
		$media = '';
	}

  if ($text != '') {
    return $title.' - <strong>'. $text .'</strong>';
  }
  if ($media != '') {
    return $title.' '.$media;
  }

  return $title;  
}
add_filter('acf/fields/flexible_content/layout_title', 'etypes_flexible_content_layout_title', 10, 4);





// ACF Options page ------------------------------------------------------------
function acf_init_options_pages() {

  // Create 'option' page
  if( function_exists('acf_add_options_page') ) {

    // Employees List
    acf_add_options_page(array(
      'page_title' => __('Options', 'etypes'),
      'menu_title' => __('Options', 'etypes'),
      'menu_slug' => 'site-options',
      // 'capability' => 'edit_posts',
      // 'parent_slug' => 'edit.php?post_type=employees',
      'position' => 50,
      'redirect' => true,
      'icon_url' => 'dashicons-menu'
    ));
	}
}
add_action('acf/init', 'acf_init_options_pages');
