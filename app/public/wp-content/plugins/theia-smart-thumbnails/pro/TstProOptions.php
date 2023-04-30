<?php

/*
 * Copyright 2012-2016, Theia Smart Thumbnails, WeCodePixels, http://wecodepixels.com
 */

add_filter( 'tst_init_options_defaults', 'TstProOptions::tst_init_options_defaults', 10, 1 );

class TstProOptions {
	public static function tst_init_options_defaults( $defaults ) {
		return $defaults;
	}
}
