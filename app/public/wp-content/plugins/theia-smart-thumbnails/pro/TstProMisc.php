<?php

/*
 * Copyright 2012-2016, Theia Smart Thumbnails, WeCodePixels, http://wecodepixels.com
 */

add_action( 'wp_get_attachment_metadata', 'TstProMisc::wp_get_attachment_metadata', 10, 2 );
add_filter( 'tst_misc_enable_focus_point', 'TstProMisc::tst_misc_enable_focus_point', 10, 1 );

class TstProMisc {
	public static function wp_get_attachment_metadata( $data, $post_id ) {
		// Do cache-busting for thumbnails.
		if (
			TstProPostOptions::is_compatible_post( get_post( $post_id ) ) &&
			TstOptions::get( 'cacheBusting' ) &&
			is_array( $data ) &&
			array_key_exists( 'sizes', $data ) &&
			array_key_exists( 'tst_thumbnail_version', $data )
		) {
			foreach ( $data['sizes'] as &$size ) {
				$size['file'] .= '?v=' . $data['tst_thumbnail_version'];
			}
		}

		return $data;
	}

	public static function tst_misc_enable_focus_point($enable) {
		if (Theia_Image_Editor_GD::$sizes_options === null) {
			if (false == TstOptions::get('allowOtherPlugins')) {
				return false;
			}
			else {
				return true;
			}
		}

		return Theia_Image_Editor_GD::$sizes_options['use_focus_point_mode'] == TstOptions::USE_FOCUS_POINT_MODE_YES;
	}
}
