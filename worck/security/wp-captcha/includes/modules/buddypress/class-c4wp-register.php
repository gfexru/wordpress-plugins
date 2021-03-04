<?php

/**
 * The functionality that handles the display of the Captcha form on the Buddypress Register Form.
 * @package  	WP Captcha
 * @subpackage  WP Captcha/modules/buddypress/class-c4wp-register
 * @author   	Devnath verma <devnathverma@gmail.com>
 */

/**
 * Class responsible for generating the display of the Captcha on Buddypress Register Form.
 * Gets called only if the "display captcha on Buddypress Register Form" option is checked in the back-end
 * @package  	WP Captcha
 * @version  	1.0.0
 * @author   	Devnath verma <devnathverma@gmail.com>
 */
 
class C4WP_Buddypress_Register {
	
	// @type defaults variables
	public $c4wp_plugin_options;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $c4wp_plugin_options ) {
		
		$this->c4wp_plugin_options 	=  	$c4wp_plugin_options;
		
		// adds the required HTML for the captcha to the Buddypress Register Form
		add_action( 'bp_before_registration_submit_buttons', array( $this, 'c4wp_display_captcha' ), 10, 0 );
		
		// validate the captcha answer on Buddypress Register Form
		add_action( 'bp_signup_validate', array( $this, 'c4wp_validation_check' ) );	
	}
	
	/**
	 * Generate captcha to Provide a public-facing view for the plugin on Buddypress Register Form.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_display_captcha( ) {
		
		global $bp;
		$c4wp_return_error = '';
		if ( ! empty( $bp->signup->errors['c4wp_error_messages'] ) ) {
			
			$c4wp_return_error .= '<div class="bp-messages bp-feedback error" style="float: left; padding-right: 5px;">';
			$c4wp_return_error .= '<span class="bp-icon"></span>';
			$c4wp_return_error .= '<p>'.$bp->signup->errors['c4wp_error_messages'].'</p>';
			$c4wp_return_error .= '</div>';
		}
		echo $c4wp_return_error;
		
		WP_CAPTCHA()->c4wp_object->c4wp_display_captcha();
	}
	
	/**
	 * This function checks validations of the captcha posted with Buddypress Register Form.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_validation_check() {
		
		global $bp;
		
		if ( ! empty( $_POST ) ) {
			
			if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'mathematical_captcha' ) {
			
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					$bp->signup->errors['c4wp_error_messages'] = $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages'];
					
				} else if ( $_POST['c4wp_user_input_captcha'] != WP_CAPTCHA()->c4wp_object->c4wp_captcha_decode( $_POST['c4wp_random_input_captcha'] ) ) {
					
					$bp->signup->errors['c4wp_error_messages'] = $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages'];
				
				} else {
					
					return true;
				}
			
			} else if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'image_captcha' ) {
				
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					$bp->signup->errors['c4wp_error_messages'] = $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages'];
					
				} else if ( !in_array( $_POST['c4wp_user_input_captcha'], $_SESSION['c4wp_random_input_captcha'], true ) ) {
					
					$bp->signup->errors['c4wp_error_messages'] = $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages'];
				
				} else {
					
					unset($_SESSION['c4wp_random_input_captcha']);
					return true;
				}
			
			} else if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'icon_captcha' ) {
			
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					$bp->signup->errors['c4wp_error_messages'] = $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages'];
					
				} else if ( $_POST['c4wp_user_input_captcha'] != $_POST['c4wp_random_input_captcha'] ) {
					
					$bp->signup->errors['c4wp_error_messages'] = $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages'];
				
				} else {
					
					return true;
				}
			
			} else {
				
				if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
					
					$bp->signup->errors['c4wp_error_messages'] = $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages'];
				
				} else if ( isset( $_POST['g-recaptcha-response'] ) && WP_CAPTCHA()->c4wp_object->c4wp_validate_captcha( $_POST['g-recaptcha-response'] ) == false ) {
					
					$bp->signup->errors['c4wp_error_messages'] = $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages'];
				
				} else {
		
					return true;
				}
			}
		}
	}
}