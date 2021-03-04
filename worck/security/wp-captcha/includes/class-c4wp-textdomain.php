<?php
/**
 * Loads and defines the internationalization files for this plugin so that it is ready for translation.
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma <devnathverma@gmail.com>
 */
 
class C4WP_Plugin_Textdomain {

	/**
	 * Load the plugin text domain for translation.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public static function c4wp_textdomain() {
		
		load_plugin_textdomain(
			'wp-captcha',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages'
		);
	}
}