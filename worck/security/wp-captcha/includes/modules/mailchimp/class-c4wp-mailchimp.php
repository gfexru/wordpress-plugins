<?php
/**
 * The functionality that handles the display of the Captcha form on the Mailchimp Form.
 * @package  	WP Captcha
 * @subpackage  WP Captcha/modules/mailchimp/class-c4wp-mailchimp
 * @author   	Devnath verma <devnathverma@gmail.com>
 */

/**
 * Class responsible for generating the display of the Captcha on Mailchimp Form.
 * Gets called only if the "display captcha on Mailchimp Form" option is checked in the back-end
 * @package  	WP Captcha
 * @version  	1.0.0
 * @author   	Devnath verma <devnathverma@gmail.com>
 */
 
class C4WP_Mailchimp {
	
	// @type defaults variables
	public $c4wp_plugin_options;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $c4wp_plugin_options ) {
		 
		$this->c4wp_plugin_options 	=  	$c4wp_plugin_options;
		
		// adds the required HTML for the captcha to the Mailchimp Form
		add_filter( 'mc4wp_form_content', array( $this, 'c4wp_mailchimp_display_captcha' ), 10, 3 );
		
		// adds the error messages for the captcha to the Mailchimp Form
		add_filter( 'mc4wp_form_messages', array( $this, 'c4wp_mailchimp_check_message' ), 10, 2 );
		
		// validate the captcha answer on Mailchimp Form
		add_filter( 'mc4wp_valid_form_request', array( $this, 'c4wp_mailchimp_check' ), 10, 2 );	
	}
	
	/**
	 * Generate captcha to Provide a public-facing view for the plugin on Mailchimp Form.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_mailchimp_display_captcha( $content = '', $form = '', $element = '' ) {
		
		$content = str_replace( '<input type="submit"', WP_CAPTCHA()->c4wp_object->c4wp_generate_captcha() . '<input type="submit"', $content );
		return $content;
	}
	
	/**
	 * Return Error messages of the captcha posted with Mailchimp Form
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_mailchimp_check_message( $messages ) {
		
		$messages['c4wp_captcha_empty_messages'] = array(
			'type' => 'error',
			'text' => $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages']
		);
		
		$messages['c4wp_captcha_error_messages'] = array(
			'type' => 'error',
			'text' => $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages']
		);
	
		return $messages;
	}
	
	/**
	 * This function checks validations of the captcha posted with Mailchimp Form.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_mailchimp_check( $errors = '' ) {
		
		if ( ! empty( $_POST ) ) { 
			
			if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'mathematical_captcha' ) {
			
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					return 'c4wp_captcha_empty_messages';
					
				} else if ( $_POST['c4wp_user_input_captcha'] != WP_CAPTCHA()->c4wp_object->c4wp_captcha_decode( $_POST['c4wp_random_input_captcha'] ) ) {
					
					return 'c4wp_captcha_error_messages';
				
				} else {
					
					return true;
				}
			
			} else if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'image_captcha' ) {
				
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					return 'c4wp_captcha_empty_messages';
					
				} else if ( !in_array( $_POST['c4wp_user_input_captcha'], $_SESSION['c4wp_random_input_captcha'], true ) ) {
					
					return 'c4wp_captcha_error_messages';
				
				} else {
					
					unset($_SESSION['c4wp_random_input_captcha']);
					return true;
				}
			
			} else if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'icon_captcha' ) {
			
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					return 'c4wp_captcha_empty_messages';
					
				} else if ( $_POST['c4wp_user_input_captcha'] != $_POST['c4wp_random_input_captcha'] ) {
					
					return 'c4wp_captcha_error_messages';
				
				} else {
					
					return true;
				}
			
			} else {
				
				if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
					
					return 'c4wp_captcha_empty_messages';
				
				} else if ( isset( $_POST['g-recaptcha-response'] ) && WP_CAPTCHA()->c4wp_object->c4wp_validate_captcha( $_POST['g-recaptcha-response'] ) == false ) {
					
					return 'c4wp_captcha_error_messages';
				
				} else {
		
					return true;
				}
			}
		}
	}
}