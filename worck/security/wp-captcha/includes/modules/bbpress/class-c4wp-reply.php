<?php
/**
 * The functionality that handles the display of the Captcha on the BBPress Reply Form.
 * @package  	WP Captcha
 * @subpackage  WP Captcha/modules/bbpress/class-c4wp-reply
 * @author   	Devnath verma <devnathverma@gmail.com>
 */

/**
 * Class responsible for generating the display of the Captcha on BBPress Reply Form.
 * Gets called only if the "display captcha on BBPress Reply Form" option is checked in the back-end
 * @package  	WP Captcha
 * @version  	1.0.0
 * @author   	Devnath verma <devnathverma@gmail.com>
 */
 
class C4WP_BBPress_Reply {
	
	// @type defaults variables
	public $c4wp_plugin_options;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $c4wp_plugin_options ) {
		
		$this->c4wp_plugin_options 	=  	$c4wp_plugin_options;
		
		// adds the required HTML for the captcha to the BBPress Reply Form
		add_action('bbp_theme_after_reply_form_content', array( $this, 'c4wp_display_captcha' ) );
		
		// validate the captcha answer on BBPress Reply Form
		add_action('bbp_new_reply_pre_extras', array( $this, 'c4wp_validation_check' ) );
	}
	
	/**
	 * Generate captcha to Provide a public-facing view for the plugin on BBPress Reply Form.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_display_captcha( ) {
		
		WP_CAPTCHA()->c4wp_object->c4wp_display_captcha();
	}
	
	/**
	 * This function checks validations of the captcha posted with BBPress Reply Form.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_validation_check( ) {
			
		if ( ! empty( $_POST ) ) {
			
			$c4wp_empty_messages  = '<strong>' . __( 'Error', 'wp-captcha' ) . '</strong> : '.$this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages'];
			$c4wp_errors_messages = '<strong>' . __( 'Error', 'wp-captcha' ) . '</strong> : '.$this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages'];
			
			if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'mathematical_captcha' ) {
			
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					bbp_add_error( 'c4wp_error_messages', $c4wp_empty_messages );
					
				} else if ( $_POST['c4wp_user_input_captcha'] != WP_CAPTCHA()->c4wp_object->c4wp_captcha_decode( $_POST['c4wp_random_input_captcha'] ) ) {
					
					bbp_add_error( 'c4wp_error_messages', $c4wp_errors_messages );
				
				} else {
					
					return true;
				}
			
			} else if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'image_captcha' ) {
				
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					bbp_add_error( 'c4wp_error_messages', $c4wp_empty_messages );
					
				} else if ( !in_array( $_POST['c4wp_user_input_captcha'], $_SESSION['c4wp_random_input_captcha'], true ) ) {
					
					bbp_add_error( 'c4wp_error_messages', $c4wp_errors_messages );
				
				} else {
					
					unset($_SESSION['c4wp_random_input_captcha']);
					return true;
				}
			
			} else if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'icon_captcha' ) {
			
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					bbp_add_error( 'c4wp_error_messages', $c4wp_empty_messages );
					
				} else if ( $_POST['c4wp_user_input_captcha'] != $_POST['c4wp_random_input_captcha'] ) {
					
					bbp_add_error( 'c4wp_error_messages', $c4wp_errors_messages );
				
				} else {
					
					return true;
				}
			
			} else {
				
				if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
					
					bbp_add_error( 'c4wp_error_messages', $c4wp_empty_messages );
				
				} else if ( isset( $_POST['g-recaptcha-response'] ) && WP_CAPTCHA()->c4wp_object->c4wp_validate_captcha( $_POST['g-recaptcha-response'] ) == false ) {
					
					bbp_add_error( 'c4wp_error_messages', $c4wp_errors_messages );
				
				} else {
		
					return true;
				}
			}
		}
	}
}