<?php
/*
Plugin Name: Theia Smart Thumbnails
Plugin URI: http://wecodepixels.com/theia-smart-thumbnails-for-wordpress/?utm_source=theia-smart-thumbnails
Description: Gain full control over your thumbnails by customizing the cropping zone for each one of them.
Author: WeCodePixels
Author URI: http://wecodepixels.com/?utm_source=theia-smart-thumbnails
Version: 1.6.4
Copyright: WeCodePixels
*/

/*
 * Copyright 2012-2016, Theia Smart Thumbnails, WeCodePixels, http://wecodepixels.com
 */

/*
 * Plugin version. Used to forcefully invalidate CSS and JavaScript caches by appending the version number to the
 * filename (e.g. "style.css?ver=TST_VERSION").
 */
define( 'TST_VERSION', '1.6.4' );
define( 'TST_IS_PRO', true );

// Include other files.
include( dirname( __FILE__ ) . '/TstMisc.php' );
include( dirname( __FILE__ ) . '/TstOptions.php' );
include( dirname( __FILE__ ) . '/TstPostOptions.php' );
include( dirname( __FILE__ ) . '/TstAdmin.php' );
if ( TST_IS_PRO ) {
	include( dirname( __FILE__ ) . '/pro/TstProOptions.php' );
	include( dirname( __FILE__ ) . '/pro/TstProPostOptions.php' );
	include( dirname( __FILE__ ) . '/pro/TstProClassTheiaImageEditor.php' );
	include( dirname( __FILE__ ) . '/pro/TstProMisc.php' );
	include( dirname( __FILE__ ) . '/pro/TstProAdmin.php' );
}

// Add our custom image editor.
add_filter( 'wp_image_editors', 'theia_smart_thumbnails_wp_image_editors', 100, 1 );
function theia_smart_thumbnails_wp_image_editors( $args = array() ) {
	// Include the editor source code.
	require_once( dirname( __FILE__ ) . '/class-theia-image-editor-gd.php' );

	// Add our editor first in line.
	array_unshift( $args, 'Theia_Image_Editor_GD' );

	return $args;
}