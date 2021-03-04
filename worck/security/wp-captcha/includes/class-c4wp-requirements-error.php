<?php
/**
 * Fired during plugin activation.
 * This class defines requirement error to run during the plugin's activation.
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma <devnathverma@gmail.com>
 */
 
class C4WP_Requirements_Error {
		
	/**
	 * Prints an error that the system requirements weren't met.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public static function requirements_error( ) {
		
		global $wp_version;
		
		$c4wp_requirements_error  = '';
		$c4wp_requirements_error .= '<div class="error">';
		$c4wp_requirements_error .= '<p><strong>'. C4WP_PLUGIN_NAME .'</strong></p>';
		$c4wp_requirements_error .= '<ul class="ul-disc">';
		$c4wp_requirements_error .= '<li>';
		$c4wp_requirements_error .= '<strong>ERROR : </strong>';
		$c4wp_requirements_error .= '<em>Your environment doesn"t meet all of the system requirements listed below.</em>';
		$c4wp_requirements_error .= '</li>';
		$c4wp_requirements_error .= '<li>';
		$c4wp_requirements_error .= '<strong>PHP ' .C4WP_PHP_VERSION. ' : </strong>';
		$c4wp_requirements_error .= '<em> You"re running version of PHP ' .PHP_VERSION. '</em>';
		$c4wp_requirements_error .= '</li>';
		$c4wp_requirements_error .= '<li>';
		$c4wp_requirements_error .= '<strong>WordPress ' .C4WP_WP_VERSION. ' : </strong>';
		$c4wp_requirements_error .= '<em> You"re running version of WordPress ' .esc_html( $wp_version ). '</em>';
		$c4wp_requirements_error .= '</li>';
		$c4wp_requirements_error .= '</ul>';
		$c4wp_requirements_error .= '<p>If you need to upgrade your version of PHP you can ask your hosting company for assistance, and if you need help upgrading WordPress you can refer to <a href="http://codex.wordpress.org/Upgrading_WordPress">the Codex</a>.</p>';
		$c4wp_requirements_error .= '</div>';
		echo $c4wp_requirements_error;
	}
}