<?php
/**
 * The functionality that handles the display of the Captcha form on the WordPress Comments Form.
 * @package  	WP Captcha
 * @subpackage  WP Captcha/modules/wordpress/class-c4wp-comments
 * @author   	Devnath verma <devnathverma@gmail.com>
 */

/**
 * Class responsible for generating the display of the Captcha on WordPress Comments Form.
 * Gets called only if the "display captcha on WordPress Comments Form" option is checked in the back-end
 * @package  	WP Captcha
 * @version  	1.0.0
 * @author   	Devnath verma <devnathverma@gmail.com>
 */
 
class C4WP_Wordpress_Comments {
	
	// @type defaults variables
	public $c4wp_plugin_options;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $c4wp_plugin_options ) {
		
		global $wp_version;
		$this->c4wp_plugin_options 	=  	$c4wp_plugin_options;
		
		if( version_compare( $wp_version, '3', '>=' ) ) { // wp 3.0 +
			
			// adds the required HTML for the captcha to the WordPress Comments Form
			add_action( 'comment_form_after_fields', array( $this, 'c4wp3_display_captcha' ), 1 );
			add_action( 'comment_form_logged_in_after', array( $this, 'c4wp3_display_captcha' ), 1 );
		}
		
		// adds the required HTML for the captcha to the WordPress Comments Form for before WP 3.0 
		add_action( 'comment_form', array( $this, 'c4wp_display_captcha' ) );
		
		// validate the captcha answer on WordPress Comments Form
		add_filter( 'preprocess_comment', array( $this, 'c4wp_validation_check' ) );	
	}
	
	/**
	 * Generate captcha to Provide a public-facing view for the plugin on WP 3.0 + Comments Form.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp3_display_captcha( ) {
		
		WP_CAPTCHA()->c4wp_object->c4wp_display_captcha();
		remove_action( 'comment_form', array( $this, 'c4wp_display_captcha' ) );
		return true;
	}
	
	/**
	 * Generate captcha to Provide a public-facing view for the plugin Before WP 3.0 Comments Form.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_display_captcha( ) {
		
		WP_CAPTCHA()->c4wp_object->c4wp_display_captcha();
		return true;
	}
	
	/**
	 * This function checks validations of the captcha posted with Wordpress Comments Form.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_validation_check( $comment ) {
		
		// skip captcha for comment replies from the admin menu
		if ( isset( $_POST['action'] ) && $_POST['action'] == 'replyto-comment' &&
		( check_ajax_referer( 'replyto-comment', '_ajax_nonce', false ) || check_ajax_referer( 'replyto-comment', '_ajax_nonce-replyto-comment', false ) ) ) {

			return $comment;
		}
	
		// Skip captcha for trackback or pingback
		if ( $comment['comment_type'] != '' && $comment['comment_type'] != 'comment' ) {

			 return $comment;
		}
		
		if ( ! empty( $_POST ) ) {
			
			if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'mathematical_captcha' ) {
			
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					wp_die( $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages'] );
					
				} else if ( $_POST['c4wp_user_input_captcha'] != WP_CAPTCHA()->c4wp_object->c4wp_captcha_decode( $_POST['c4wp_random_input_captcha'] ) ) {
					
					wp_die( $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages'] );
				
				} else {
					
					return $comment;
				}
			
			} else if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'image_captcha' ) {
				
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					wp_die( $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages'] );
					
				} else if ( !in_array( $_POST['c4wp_user_input_captcha'], $_SESSION['c4wp_random_input_captcha'], true ) ) {
					
					wp_die( $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages'] );
				
				} else {
					
					return $comment;
					unset($_SESSION['c4wp_random_input_captcha']);
				}
			
			} else if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'icon_captcha' ) {
			
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					wp_die( $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages'] );
					
				} else if ( $_POST['c4wp_user_input_captcha'] != $_POST['c4wp_random_input_captcha'] ) {
					
					wp_die( $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages'] );
				
				} else {
					
					return $comment;
				}
			
			} else {
				
				if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
					
					wp_die( $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages'] );
				
				} else if ( isset( $_POST['g-recaptcha-response'] ) && WP_CAPTCHA()->c4wp_object->c4wp_validate_captcha( $_POST['g-recaptcha-response'] ) == false ) {
					
					wp_die( $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages'] );
				
				} else {
		
					return $comment;
				}
			}
		}	
	}
}