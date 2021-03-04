<?php
/**
 * The functionality that handles the display of the Captcha form on the Subscriber Form.
 * @package  	WP Captcha
 * @subpackage  WP Captcha/modules/subscriber/class-c4wp-subscriber
 * @author   	Devnath verma <devnathverma@gmail.com>
 */

/**
 * Class responsible for generating the display of the Captcha on Subscriber Form.
 * Gets called only if the "display captcha on Subscriber Form" option is checked in the back-end
 * @package  	WP Captcha
 * @version  	1.0.0
 * @author   	Devnath verma <devnathverma@gmail.com>
 */
 
class C4WP_Subscriber {
	
	// @type defaults variables
	public $c4wp_plugin_options;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $c4wp_plugin_options ) {
		
		$this->c4wp_plugin_options 	=  	$c4wp_plugin_options;
		
		// adds the required HTML for the captcha to the Subscriber Form
		add_filter( 'sbscrbr_add_field', array( $this, 'c4wp_display_captcha' ), 10, 0 );
		
		// validate the captcha answer on Subscriber Form
		add_filter( 'sbscrbr_check', array( $this, 'c4wp_validation_check' ) );	
	}
	
	/**
	 * Generate captcha to Provide a public-facing view for the plugin on Subscriber Form.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_display_captcha( ) {
		
		$c4wp_return_captcha = WP_CAPTCHA()->c4wp_object->c4wp_generate_captcha();
		return $c4wp_return_captcha;
	}
	
	/**
	 * This function checks validations of the captcha posted with Subscriber Form.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_validation_check( $c4wp_errors = true ) {
			
		if ( ! empty( $_POST ) ) {
		
			if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'mathematical_captcha' ) {
			
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
					
					return $c4wp_errors  = '<strong>' . __( 'Error', 'wp-captcha' ) . '</strong> : '.$this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages'];
					
				} else if ( $_POST['c4wp_user_input_captcha'] != WP_CAPTCHA()->c4wp_object->c4wp_captcha_decode( $_POST['c4wp_random_input_captcha'] ) ) {
					
					return $c4wp_errors = '<strong>' . __( 'Error', 'wp-captcha' ) . '</strong> : '.$this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages'];
				
				} else {
					
					return true;
				}
			
			} else if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'image_captcha' ) {
				
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					return $c4wp_errors  = '<strong>' . __( 'Error', 'wp-captcha' ) . '</strong> : '.$this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages'];
					
				} else if ( !in_array( $_POST['c4wp_user_input_captcha'], $_SESSION['c4wp_random_input_captcha'], true ) ) {
					
					return $c4wp_errors = '<strong>' . __( 'Error', 'wp-captcha' ) . '</strong> : '.$this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages'];
				
				} else {
					
					unset($_SESSION['c4wp_random_input_captcha']);
					return true;
				}
			
			} else if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'icon_captcha' ) {
			
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					return $c4wp_errors  = '<strong>' . __( 'Error', 'wp-captcha' ) . '</strong> : '.$this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages'];
					
				} else if ( $_POST['c4wp_user_input_captcha'] != $_POST['c4wp_random_input_captcha'] ) {
					
					return $c4wp_errors = '<strong>' . __( 'Error', 'wp-captcha' ) . '</strong> : '.$this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages'];
				
				} else {
					
					return true;
				}
			
			} else {
				
				if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
					
					return $c4wp_errors  = '<strong>' . __( 'Error', 'wp-captcha' ) . '</strong> : '.$this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages'];
				
				} else if ( isset( $_POST['g-recaptcha-response'] ) && WP_CAPTCHA()->c4wp_object->c4wp_validate_captcha( $_POST['g-recaptcha-response'] ) == false ) {
					
					return $c4wp_errors = '<strong>' . __( 'Error', 'wp-captcha' ) . '</strong> : '.$this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages'];
				
				} else {
		
					return true;
				}
			}
		}
	}
}