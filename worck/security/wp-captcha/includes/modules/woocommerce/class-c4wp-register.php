<?php
/**
 * The functionality that handles the display of the Captcha form on the Woocommerce Register Form.
 * @package  	WP Captcha
 * @subpackage  WP Captcha/modules/woocommerce/class-c4wp-register
 * @author   	Devnath verma <devnathverma@gmail.com>
 */

/**
 * Class responsible for generating the display of the Captcha on Woocommerce Register Form.
 * Gets called only if the "display captcha on Woocommerce Register Form" option is checked in the back-end
 * @package  	WP Captcha
 * @version  	1.0.0
 * @author   	Devnath verma <devnathverma@gmail.com>
 */

class C4WP_Woocommerce_Register {
	
	// @type defaults variables
	public $c4wp_plugin_options;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $c4wp_plugin_options ) {
		
		$this->c4wp_plugin_options 	=  	$c4wp_plugin_options;
		
		// adds the required HTML for the captcha to the Woocommerce Register Form
		add_action( 'woocommerce_register_form', array( $this, 'c4wp_display_captcha' ), 10, 0 );
		
		// validate the captcha answer on Woocommerce Register Form
		add_filter( 'woocommerce_process_registration_errors', array( $this, 'c4wp_validation_check' ), 10 , 3 );	
	}
	
	/**
	 * Generate captcha to Provide a public-facing view for the plugin on Woocommerce Register Form.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_display_captcha( ) {
		
		WP_CAPTCHA()->c4wp_object->c4wp_display_captcha();
	}
	
	/**
	 * This function checks validations of the captcha posted with Woocommerce Register Form.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_validation_check( $validation_errors ) {
		
		if ( ! empty( $_POST ) ) {
			
			if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'mathematical_captcha' ) {
			
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					$validation_errors->add( 'c4wp_error_messages', $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages'], 'wp-captcha' );
					
				} else if ( $_POST['c4wp_user_input_captcha'] != WP_CAPTCHA()->c4wp_object->c4wp_captcha_decode( $_POST['c4wp_random_input_captcha'] ) ) {
					
					$validation_errors->add( 'c4wp_error_messages', $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages'],'wp-captcha' );
				
				} else {
					
					return $validation_errors;
				}
			
			} else if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'image_captcha' ) {
				
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					$validation_errors->add( 'c4wp_error_messages', $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages'], 'wp-captcha' );
					
				} else if ( !in_array( $_POST['c4wp_user_input_captcha'], $_SESSION['c4wp_random_input_captcha'], true ) ) {
					
					$validation_errors->add( 'c4wp_error_messages', $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages'],'wp-captcha' );
				
				} else {
					
					unset($_SESSION['c4wp_random_input_captcha']);
					return $validation_errors;
				}
			
			} else if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'icon_captcha' ) {
			
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					$validation_errors->add( 'c4wp_error_messages', $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages'], 'wp-captcha' );
					
				} else if ( $_POST['c4wp_user_input_captcha'] != $_POST['c4wp_random_input_captcha'] ) {
					
					$validation_errors->add( 'c4wp_error_messages', $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages'],'wp-captcha' );
				
				} else {
					
					return $validation_errors;
				}
			
			} else {
				
				if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
					
					$validation_errors->add( 'c4wp_error_messages', $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages'],'wp-captcha' );
				
				} else if ( isset( $_POST['g-recaptcha-response'] ) && WP_CAPTCHA()->c4wp_object->c4wp_validate_captcha( $_POST['g-recaptcha-response'] ) == false ) {
					
					$validation_errors->add( 'c4wp_error_messages', $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages'], 'wp-captcha' );
				
				} else {
		
					return $validation_errors;
				}
			}
		}	
		
		return $validation_errors;
	}
}