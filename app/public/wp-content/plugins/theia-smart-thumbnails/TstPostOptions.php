<?php

/*
 * Copyright 2012-2016, Theia Smart Thumbnails, WeCodePixels, http://wecodepixels.com
 */

class TstPostOptions {
	const META_POSITION = 'theiaSmartThumbnails_position';

	// Get the saved crop position of an image.
	public static function get_meta( $postId, $orig_w = null, $orig_h = null ) {
		$focus_point = get_post_meta( $postId, TstPostOptions::META_POSITION, true );

		if ( ! $focus_point ) {
			if (
				$orig_w !== null &&
				$orig_h !== null &&
				$orig_w < $orig_h &&
				TstOptions::get( 'portraitUpperByDefault' ) == true
			) {
				$focus_point = array( 0.5, 0 );
			} else {
				$focus_point = array((float)TstOptions::get('default_focal_point_x'), (float)TstOptions::get('default_focal_point_y'));
			}
		}

		return $focus_point;
	}
}