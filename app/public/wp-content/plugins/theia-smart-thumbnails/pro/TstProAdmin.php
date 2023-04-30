<?php

/*
 * Copyright 2012-2016, Theia Smart Thumbnails, WeCodePixels, http://wecodepixels.com
 */

add_action( 'admin_init', 'TstProAdmin::admin_init' );
add_action( 'admin_menu', 'TstAdmin::admin_menu' );

class TstProAdmin {
	public static function admin_init() {
	}

	public static function admin_menu() {
		if ( TST_USE_AS_STANDALONE ) {
			add_options_page( 'Theia Smart Thumbnails Settings', 'Theia Smart Thumbs', 'manage_options', 'tst', 'TstAdmin::do_page' );
		}
	}
}