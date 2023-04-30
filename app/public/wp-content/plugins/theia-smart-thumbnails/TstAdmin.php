<?php

/*
 * Copyright 2012-2016, Theia Smart Thumbnails, WeCodePixels, http://wecodepixels.com
 */

add_action( 'admin_init', 'TstAdmin::admin_init' );
add_action( 'admin_menu', 'TstAdmin::admin_menu' );

class TstAdmin {
	public static function admin_init() {
		register_setting( 'tst_options_general', 'tst_general', 'TstAdmin::validate' );
		register_setting( 'tst_options_sizes', 'tst_sizes', 'TstAdmin::validate' );
		register_setting( 'tst_options_dashboard', 'tst_dashboard', 'TstAdmin::validate' );
	}

	public static function admin_menu() {
		if ( TST_USE_AS_STANDALONE ) {
			add_options_page( 'Theia Smart Thumbnails Settings', 'Theia Smart Thumbs', 'manage_options', 'tst', 'TstAdmin::do_page' );
		}
	}

	public static function do_page() {
		$tabs = array(
			'dashboard' => array(
				'title'       => __( "Dashboard", 'theia-smart-thumbnails' ),
				'class'       => 'Dashboard',
				'path_prefix' => ''
			),
			'general'   => array(
				'title'       => __( "General", 'theia-smart-thumbnails' ),
				'class'       => 'General',
				'path_prefix' => ''
			),
			'sizes'     => array(
				'title'       => __( "Thumbnail Sizes", 'theia-smart-thumbnails' ),
				'class'       => 'Sizes',
				'path_prefix' => ''
			)
		);
		if ( array_key_exists( 'tab', $_GET ) && array_key_exists( $_GET['tab'], $tabs ) ) {
			$current_tab = $_GET['tab'];
		} else {
			$current_tab = 'dashboard';
		}
		?>

		<div class="wrap">
			<?php
			if ( $current_tab != 'about' ) {
				?>
				<a class="theiaSmartThumbnails_adminLogo"
				   href="http://wecodepixels.com/?utm_source=theia-smart-thumbnails-for-wordpress"
				   target="_blank"><img src="<?php echo plugins_url( '/images/wecodepixels-logo.png', __FILE__ ); ?>"></a>
			<?php
			}
			?>

			<h2 class="theiaSmartThumbnails_adminTitle">
				<a href="http://wecodepixels.com/theia-smart-thumbnails-for-wordpress/?utm_source=theia-smart-thumbnails-for-wordpress"
				   target="_blank"><img src="<?php echo plugins_url( '/images/theia-smart-thumbnails-thumbnail.png', __FILE__ ); ?>"></a>
				Theia Smart Thumbnails
			</h2>

			<h2 class="nav-tab-wrapper">
				<?php
				foreach ( $tabs as $id => $tab ) {
					$class = 'nav-tab';
					if ( $id == $current_tab ) {
						$class .= ' nav-tab-active';
					}
					?>
					<a href="?page=tst&tab=<?php echo $id; ?>"
					   class="<?php echo $class; ?>"><?php echo $tab['title']; ?></a>
				<?php
				}
				?>
			</h2>
			<?php
			$class = 'TstAdmin_' . $tabs[ $current_tab ]['class'];
			require( $tabs[ $current_tab ]['path_prefix'] . $class . '.php' );
			$page = new $class;
			$page->echoPage();
			?>
		</div>
	<?php
	}

	public static function validate( $input ) {
		return $input;
	}
}