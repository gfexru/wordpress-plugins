<?php
/**
 * Fired during plugin deactivation.
 * This class defines all code necessary to run during the plugin's deactivation.
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma <devnathverma@gmail.com>
 */
 
class C4WP_Plugin_Dectivator {

	/**
	 * ON plugin deactivation Remove the "Saved" settings.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 * @uses 	 delete_option()
	 */
	public static function c4wp_dectivate() {

		delete_option( 'c4wp_default_settings' );
	}
}