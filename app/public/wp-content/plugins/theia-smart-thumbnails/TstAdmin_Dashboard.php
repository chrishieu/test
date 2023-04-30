<?php

/*
 * Copyright 2012-2016, Theia Smart Thumbnails, WeCodePixels, http://wecodepixels.com
 */

class TstAdmin_Dashboard {
	public $showPreview = true;

	public function echoPage() {
		settings_fields( 'theia_smart_thumbnails_license_key' );
		?>
		<h3><?php _e( "Version", 'theia-smart-thumbnails' ); ?></h3>

		<p>
			You are using
			<a href="http://wecodepixels.com/theia-smart-thumbnails-for-wordpress/?utm_source=theia-smart-thumbnails-for-wordpress"
			   target="_blank"><b>Theia Smart Thumbnails</b></a>
			version <b class="theiaSmartThumbnails_adminVersion"><?php echo TST_VERSION; ?></b>, developed
			by
			<a href="http://wecodepixels.com/?utm_source=theia-smart-thumbnails-for-wordpress"
			   target="_blank"><b>WeCodePixels</b></a>.
			<br>
		</p>
		<br>

		<h3><?php _e( "Support", 'theia-smart-thumbnails' ); ?></h3>

		<p>
			1. If you have any problems or questions, you should first check
			<a href="http://wecodepixels.com/theia-smart-thumbnails-for-wordpress/docs/?utm_source=theia-smart-thumbnails-for-wordpress"
			   class="button"
			   target="_blank">
				The Documentation
			</a>
		</p>

		<form method="post" action="options.php">
			<?php settings_fields( 'tst_options_dashboard' ); ?>

			<p>
				2. If the plugin is misbehaving, try to <input name="tst_dashboard[reset_to_defaults]"
				                                               type="submit"
				                                               class="button"
				                                               value="Reset to Default Settings"
				                                               onclick="if(!confirm('Are you sure you want to reset all settings to their default values?')) return false;">
			</p>
		</form>

		<p>
			3. Deactivate all plugins. If the issue is solved, then re-activate them one-by-one to pinpoint the
			exact cause.
		</p>

		<p>
			4. If your issue persists, please proceed to
			<a <?php echo TST_IS_PRO ? 'href="http://wecodepixels.com/theia-smart-thumbnails-for-wordpress/support/?utm_source=theia-smart-thumbnails-for-wordpress"' : ''; ?>
				class="button"
				target="_blank" <?php echo TST_IS_PRO ? '' : 'disabled'; ?>>Submit a Ticket</a>
			<?php echo TstMisc::get_pro_only_notice(); ?>
		</p>
		<br>

		<h3><?php _e( "Updates and Announcements", 'theia-smart-thumbnails' ); ?></h3>
		<iframe class="theiaSmartThumbnails_news"
		        src="<?php echo TstMisc::get_request_scheme(); ?>://wecodepixels.com/theia-smart-thumbnails-for-wordpress/news"></iframe>
	<?php
	}
}
