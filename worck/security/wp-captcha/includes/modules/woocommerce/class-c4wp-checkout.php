<?php
/**
 * The functionality that handles the display of the Captcha form on the Woocommerce Checkout Form.
 * @package  	WP Captcha
 * @subpackage  WP Captcha/modules/woocommerce/class-c4wp-checkout
 * @author   	Devnath verma <devnathverma@gmail.com>
 */

/**
 * Class responsible for generating the display of the Captcha on Woocommerce Checkout Form.
 * Gets called only if the "display captcha on Woocommerce Checkout Form" option is checked in the back-end
 * @package  	WP Captcha
 * @version  	1.0.0
 * @author   	Devnath verma <devnathverma@gmail.com>
 */
 
class C4WP_Woocommerce_Checkout {
	
	// @type defaults variables
	public $c4wp_plugin_options;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $c4wp_plugin_options ) {
		
		$this->c4wp_plugin_options 	=  	$c4wp_plugin_options;
		
		// adds the required HTML for the captcha to the Woocommerce Checkout Form
		add_action( 'woocommerce_after_checkout_billing_form', array( $this, 'c4wp_display_captcha' ), 10, 0 );
		
		// validate the captcha answer on Woocommerce Checkout Form
		add_action( 'woocommerce_checkout_process', array( $this, 'c4wp_validation_check' ) );	
	}

	/**
	 * Generate captcha to Provide a public-facing view for the plugin on Woocommerce Checkout Form.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_display_captcha( ) {
		
		WP_CAPTCHA()->c4wp_object->c4wp_display_captcha();
	}
	
	/**
	 * This function checks validations of the captcha posted with Woocommerce Checkout Form.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_validation_check( ) {
		
		if ( ! empty( $_POST ) ) {
			
			if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'mathematical_captcha' ) {
			
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					wc_add_notice( '<strong>' . $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages'] . '</strong>', 'error' );
					
				} else if ( $_POST['c4wp_user_input_captcha'] != WP_CAPTCHA()->c4wp_object->c4wp_captcha_decode( $_POST['c4wp_random_input_captcha'] ) ) {
					
					wc_add_notice( '<strong>' . $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages'] . '</strong>', 'error' );
				
				} else {
					
					return true;
				}
			
			} else if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'image_captcha' ) {
				
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					wc_add_notice( '<strong>' . $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages'] . '</strong>', 'error' );
					
				} else if ( !in_array( $_POST['c4wp_user_input_captcha'], $_SESSION['c4wp_random_input_captcha'], true ) ) {
					
					wc_add_notice( '<strong>' . $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages'] . '</strong>', 'error' );
				
				} else {
					
					return true;
					unset($_SESSION['c4wp_random_input_captcha']);
				}
			
			} else if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'icon_captcha' ) {
			
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					wc_add_notice( '<strong>' . $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages'] . '</strong>', 'error' );
					
				} else if ( $_POST['c4wp_user_input_captcha'] != $_POST['c4wp_random_input_captcha'] ) {
					
					wc_add_notice( '<strong>' . $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages'] . '</strong>', 'error' );
				
				} else {
					
					return true;
				}
			
			} else {
				
				if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
					
					wc_add_notice( '<strong>' . $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages'] . '</strong>', 'error' );
				
				} else if ( isset( $_POST['g-recaptcha-response'] ) && WP_CAPTCHA()->c4wp_object->c4wp_validate_captcha( $_POST['g-recaptcha-response'] ) == false ) {
					
					wc_add_notice( '<strong>' . $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages'] . '</strong>', 'error' );
				
				} else {
		
					return true;
				}
			}
		}
	}
}