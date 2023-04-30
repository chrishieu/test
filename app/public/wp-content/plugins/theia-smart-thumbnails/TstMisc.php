<?php

/*
 * Copyright 2012-2016, Theia Smart Thumbnails, WeCodePixels, http://wecodepixels.com
 */

add_action( 'init', 'TstMisc::init' );
add_action( 'image_resize_dimensions', 'TstMisc::image_resize_dimensions', 100, 6 );
add_action( 'wp_get_attachment_metadata', 'TstMisc::wp_get_attachment_metadata', 10, 2 );
add_action( 'add_attachment', 'TstMisc::add_attachment', 10, 1);
add_action( 'get_attached_file', 'TstMisc::get_attached_file', 10, 2 );
add_action( 'admin_enqueue_scripts', 'TstMisc::admin_enqueue_scripts' );

class TstMisc {
	/**
	 * This variable stores the last post ID used in "get_attached_file" or "wp_get_attachment_metadata".
	 * These functions are always called before "image_resize_dimensions".
	 * Using this, we can get the post's meta data in "image_resize_dimensions".
	 */
	public static $last_post_id = null;

	public static function init() {
		// Decide whether to use these files as a standalone plugin or not (i.e. integrate them in a theme).
		if ( ! defined( 'TST_USE_AS_STANDALONE' ) ) {
			define( 'TST_USE_AS_STANDALONE', true );
		}
	}

	public static function get_attached_file( $file, $attachment_id ) {
		self::$last_post_id = $attachment_id;

		return $file;
	}

	public static function wp_get_attachment_metadata( $data, $post_id ) {
		self::$last_post_id = $post_id;

		return $data;
	}

	public static function add_attachment( $post_id ) {
		self::$last_post_id = $post_id;
	}

	public static function image_resize_dimensions( $something, $orig_w, $orig_h, $dest_w, $dest_h, $crop ) {
		if ( ! $crop || self::$last_post_id === null ) {
			return null;
		}

		$aspect_ratio = $orig_w / $orig_h;

		if ( class_exists( 'Theia_Image_Editor_GD' ) && Theia_Image_Editor_GD::$enable_crop_to_fit ) {
			$s_x          = 0;
			$s_y          = 0;
			$dst_canvas_w = $new_w = $dest_w;
			$dst_canvas_h = $new_h = $dest_h;
			$crop_w       = $orig_w;
			$crop_h       = $orig_h;
			$dest_x       = 0;
			$dest_y       = 0;
			$crop_ratio   = $dest_w / $dest_h;

			if ( $aspect_ratio < $crop_ratio ) {
				// Use maximum height. Will add space on the left and right.
				$new_w  = $orig_w / $orig_h * $new_h;
				$dest_x = ( $dst_canvas_w - $new_w ) / 2;
			} else {
				// Use maximum width. Will add space on the top and bottom.
				$new_h  = $orig_h / $orig_w * $new_w;
				$dest_y = ( $dst_canvas_h - $new_h ) / 2;
			}
		} else {
			// Get focus point.
			// If Theia_Image_Editor_GD does not exist, then this method has been called by a 3rd party plugin such as Royal Slider, in which case we enable the focus point.
			if ( ! class_exists( 'Theia_Image_Editor_GD' ) || apply_filters('tst_misc_enable_focus_point', true) ) {
				$focus_point = TstPostOptions::get_meta( self::$last_post_id, $orig_w, $orig_h );
			} else {
				$focus_point = array( 0.5, 0.5 );
			}

			$dest_x = $dest_y = 0;

			if ( ! TstOptions::get( 'enlargeSmallImages' ) ) {
				$new_w = min( $dest_w, $orig_w );
				$new_h = min( $dest_h, $orig_h );
			} else {
				$new_w = $dest_w;
				$new_h = $dest_h;
			}

			if ( ! $new_w ) {
				$new_w = intval( $new_h * $aspect_ratio );
			}

			if ( ! $new_h ) {
				$new_h = intval( $new_w / $aspect_ratio );
			}

			$size_ratio = max( $new_w / $orig_w, $new_h / $orig_h );

			$crop_w = round( $new_w / $size_ratio );
			$crop_h = round( $new_h / $size_ratio );

			$s_x = floor( ( $orig_w - $crop_w ) * $focus_point[0] );
			$s_y = floor( ( $orig_h - $crop_h ) * $focus_point[1] );

			// The canvas is the same as the resulting image.
			$dst_canvas_w = $new_w;
			$dst_canvas_h = $new_h;
		}

		// If the resulting image would be the same size or larger we don't want to resize it
		if (
			$new_w >= $orig_w &&
			$new_h >= $orig_h &&
			! TstOptions::get( 'enlargeSmallImages' )
		) {
			return false;
		}

		return array(
			(int) $dest_x,
			(int) $dest_y,
			(int) $s_x,
			(int) $s_y,
			(int) $new_w,
			(int) $new_h,
			(int) $crop_w,
			(int) $crop_h,
			(int) $dst_canvas_w,
			(int) $dst_canvas_h
		);
	}

	// Enqueue JavaScript and CSS for the admin interface.
	public static function admin_enqueue_scripts( $hook_suffix ) {
		if ( $hook_suffix == 'settings_page_tst' ) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );
		}

		// Admin JS
		wp_register_script( 'theiaSmartThumbnails-admin.js', plugins_url( 'js/tst-admin.js', __FILE__ ), array( 'jquery' ), TST_VERSION, true );
		wp_enqueue_script( 'theiaSmartThumbnails-admin.js' );

		// Admin CSS
		wp_register_style( 'theiaSmartThumbnails-admin', plugins_url( 'css/admin.css', __FILE__ ), TST_VERSION );
		wp_enqueue_style( 'theiaSmartThumbnails-admin' );
	}

	// Get all thumbnails sizes used by the current theme, including the default ones defined by WordPress.
	public static function get_image_sizes( $size = '' ) {
		global $_wp_additional_image_sizes;
		$sizes                        = array();
		$get_intermediate_image_sizes = get_intermediate_image_sizes();

		// Create the full array with sizes and crop info
		foreach ( $get_intermediate_image_sizes as $_size ) {
			if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
				$sizes[ $_size ]['width']  = get_option( $_size . '_size_w' );
				$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
				$sizes[ $_size ]['crop']   = (bool) get_option( $_size . '_crop' );
			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
				$sizes[ $_size ] = array(
					'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
					'height' => $_wp_additional_image_sizes[ $_size ]['height'],
					'crop'   => $_wp_additional_image_sizes[ $_size ]['crop']
				);
			}
		}

		// Get only 1 size if found.
		if ( $size ) {
			if ( isset( $sizes[ $size ] ) ) {
				return $sizes[ $size ];
			} else {
				return false;
			}
		}

		return $sizes;
	}

	public static function hex_to_rgb( $hex ) {
		list( $r, $g, $b ) = sscanf( $hex, "#%02x%02x%02x" );

		return array( $r, $g, $b );
	}

	public static function echo_regenerate_all_thumbnails_notice() {
		?>
		<div id="poststuff">
			<div class="postbox">
				<div class="inside">
					<p>
						After changing these settings, you might want to
						<a href="https://wordpress.org/plugins/regenerate-thumbnails/">regenerate all existing thumbnails</a>.
					</p>
				</div>
			</div>
		</div>
	<?php
	}

	public static function get_pro_only_notice() {
		return '';
//		return '<br><span class="theiaSmartThumbnails_proOnly">' . (TST_IS_PRO ? 'PRO' : 'PRO only') . '</span>';
	}

	// Courtesy of http://stackoverflow.com/a/16076965/148388
	public static function get_request_scheme() {
		$isSecure = false;
		if ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ) {
			$isSecure = true;
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || ! empty( $_SERVER['HTTP_X_FORWARDED_SSL'] ) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on' ) {
			$isSecure = true;
		}

		return $isSecure ? 'https' : 'http';
	}
}
