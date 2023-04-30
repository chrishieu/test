<?php 


// Update default max width
// Max-size is default 1600
// http://aaronrutley.com/responsive-images-in-wordpress-with-acf/
function set_max_srcset_image_width() {
	return 1920;
}
add_filter('max_srcset_image_width', 'set_max_srcset_image_width', 10, 2);


/*------------------------------------*\
Image Sizes
https://alexwright.net/web-design-secrets/responsive-images-wordpress/
\*------------------------------------*/
function set_responsive_image_sizes() {
  
  // ----- Images sizes & Aspect ratios -------
  // http://paulbourke.net/dataformats/dimensions/
    add_image_size('ct_thumb', 63, 63, true);

    // Landscape Images 16:9, cropped
    add_image_size('16_9_large', 1920, 1080, true);
    add_image_size('16_9_medium', 1280, 720, true);
    add_image_size('16_9_small', 640, 360, true);
    add_image_size('16_9_thumbnail', 100, 56, true);

    // Portrait Images 4:5, cropped
    add_image_size('4_5_large', 1280, 1600, true);
    add_image_size('4_5_medium', 960, 1200, true);
    add_image_size('4_5_small', 480, 600, true);
    add_image_size('4_5_thumbnail', 80, 100, true);
    
    // Portrait Images 4:6, cropped
    add_image_size('4_6_medium', 960, 1440, true);
    add_image_size('4_6_small', 480, 720, true);

    // Portrait Images 1:1, cropped
    add_image_size('1_1_large', 1280, 1280, true);
    add_image_size('1_1_medium', 960, 960, true);
    add_image_size('1_1_small', 640, 640, true);
    add_image_size('1_1_thumbnail', 80, 100, true);

    // Flexible Images, no crop
    add_image_size('flex_height_1920', 1920);
    add_image_size('flex_height_large', 960);
    add_image_size('flex_height_medium', 640);
    add_image_size('flex_height_small', 480);
    add_image_size('flex_height_thumbnail', 100);
}
// Set image sizes! https://codex.wordpress.org/Function_Reference/remove_image_size
add_action('init', 'set_responsive_image_sizes');


// Add custom names to size ----------------------------------------
function set_image_size_custom_names( $sizes ) {
  return array_merge( $sizes, array(
      'ct_thumb' => __('Contact Thumb'),
  ) );
}
add_filter( 'image_size_names_choose', 'set_image_size_custom_names' );





function get_image_from_id($image_id) {
    $data = array('url' => wp_get_attachment_image_url( $image_id, 'full' ));
    
    $data['sizes'] = array(
        '4_6_medium' => wp_get_attachment_image_url( $image_id, '4_6_medium' ),
        '4_6_small' => wp_get_attachment_image_url( $image_id, '4_6_small' ),
    );
    
    return $data;
}