<?php
/**
 * The functionality that handles the display of the Captcha form on the Buddypress Create Group Form.
 * @package  	WP Captcha
 * @subpackage  WP Captcha/modules/buddypress/class-c4wp-create-group
 * @author   	Devnath verma <devnathverma@gmail.com>
 */

/**
 * Class responsible for generating the display of the Captcha on Buddypress Create Group Form.
 * Gets called only if the "display captcha on Buddypress Create Group Form" option is checked in the back-end
 * @package  	WP Captcha
 * @version  	1.0.0
 * @author   	Devnath verma <devnathverma@gmail.com>
 */
 
class C4WP_Buddypress_Create_Group {
	
	// @type defaults variables
	public $c4wp_plugin_options;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $c4wp_plugin_options ) {
		
		$this->c4wp_plugin_options 	=  	$c4wp_plugin_options;
		
		// adds the required HTML for the captcha to the Buddypress Create Group Form
		add_action( 'bp_after_group_details_creation_step', array( $this, 'c4wp_display_captcha' ), 10, 0 );
		
		// validate the captcha answer on Buddypress Create Group Form
		add_action( 'groups_group_before_save', array( $this, 'c4wp_validation_check' ) );	
	}
	
	/**
	 * Generate captcha to Provide a public-facing view for the plugin on Buddypress Create Group Form.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_display_captcha( ) {
		
		WP_CAPTCHA()->c4wp_object->c4wp_display_captcha();
	}
	
	/**
	 * This function checks validations of the captcha posted with Buddypress Create Group Form.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_validation_check( $bp_group ) {
		
		if ( ! bp_is_group_creation_step( 'group-details' ) )
            return false;
		
		if ( ! empty( $_POST ) ) {
			
			if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'mathematical_captcha' ) {
			
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					bp_core_add_message( $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages'], 'error' );
                	bp_core_redirect( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/create/step/group-details/' );
					
				} else if ( $_POST['c4wp_user_input_captcha'] != WP_CAPTCHA()->c4wp_object->c4wp_captcha_decode( $_POST['c4wp_random_input_captcha'] ) ) {
					
					bp_core_add_message( $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages'], 'error' );
            		bp_core_redirect( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/create/step/group-details/' );
				
				} else {
					
					return false;
				}
			
			} else if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'image_captcha' ) {
				
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					bp_core_add_message( $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages'], 'error' );
                	bp_core_redirect( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/create/step/group-details/' );
					
				} else if ( !in_array( $_POST['c4wp_user_input_captcha'], $_SESSION['c4wp_random_input_captcha'], true ) ) {
					
					bp_core_add_message( $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages'], 'error' );
            		bp_core_redirect( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/create/step/group-details/' );
				
				} else {
					
					unset($_SESSION['c4wp_random_input_captcha']);
					return false;
				}
			
			} else if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'icon_captcha' ) {
			
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					bp_core_add_message( $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages'], 'error' );
                	bp_core_redirect( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/create/step/group-details/' );
					
				} else if ( $_POST['c4wp_user_input_captcha'] != $_POST['c4wp_random_input_captcha'] ) {
					
					bp_core_add_message( $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages'], 'error' );
            		bp_core_redirect( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/create/step/group-details/' );
				
				} else {
					
					return false;
				}
			
			} else {
				
				if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
					
					bp_core_add_message( $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages'], 'error' );
                	bp_core_redirect( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/create/step/group-details/' );
				
				} else if ( isset( $_POST['g-recaptcha-response'] ) && WP_CAPTCHA()->c4wp_object->c4wp_validate_captcha( $_POST['g-recaptcha-response'] ) == false ) {
					
					bp_core_add_message( $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages'], 'error' );
            		bp_core_redirect( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/create/step/group-details/' );
				
				} else {
		
					return false;
				}
			}
		}
	}
}