<?php
/**
 * The functionality that handles the display of the Captcha form on the Jetpack Form.
 * @package  	WP Captcha
 * @subpackage  WP Captcha/modules/jetpack-forms/class-c4wp-jetpack
 * @author   	Devnath verma <devnathverma@gmail.com>
 */

/**
 * Class responsible for generating the display of the Captcha on Jetpack Form.
 * Gets called only if the "display captcha on Jetpack Form" option is checked in the back-end
 * @package  	WP Captcha
 * @version  	1.0.0
 * @author   	Devnath verma <devnathverma@gmail.com>
 */
 
class C4WP_Jetpack {
	
	// @type defaults variables
	public $c4wp_plugin_options;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $c4wp_plugin_options ) {
		 
		$this->c4wp_plugin_options 	=  	$c4wp_plugin_options;
		
		// adds the required HTML for the captcha to the Jetpack Form
		add_filter( 'the_content', array( $this, 'c4wp_jetpack_cf_display' ) );
		
		// adds the shortcode for display captcha to the Jetpack Form
		add_shortcode( 'jetpack_c4wp', array( $this, 'c4wp_shortcode_display' ) );
		
		// adds the required HTML for the captcha to the Jetpack Form
		add_filter( 'widget_text', array( $this, 'c4wp_jetpack_cf_display' ), 0 );
		
		// adds the required HTML for the captcha to the Jetpack Form
		add_filter( 'widget_text', array( $this, 'shortcode_unautop' ) );
		
		// adds the required HTML for the captcha to the Jetpack Form
		add_filter( 'widget_text', array( $this, 'do_shortcode' ) );
		
		// validate the captcha answer on Jetpack Form
		add_filter( 'jetpack_contact_form_is_spam', array( $this, 'c4wp_jetpack_cf_check' ), 11, 2 );
		
		// adds the required HTML for the captcha to the Jetpack Form
		add_filter( 'render_block', array( $this, 'c4wp_render_block' ), 10, 2 );	
	}
	
	/**
	 * Generate captcha to Provide a public-facing view for the plugin on Jetpack Form.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_shortcode_display( $c4wp_captcha = '') {
		
		$c4wp_captcha = WP_CAPTCHA()->c4wp_object->c4wp_generate_captcha();
		return apply_filters( 'c4wp_captcha_content', $c4wp_captcha );
	}
	
	/**
	 * Display captcha to Provide a public-facing view for the plugin on Jetpack Forms.
	 * @package WP Captcha
	 * @version 1.0.0
	 * @author  Devnath verma
	 */
    public function c4wp_jetpack_cf_display( $content ) {
		
		return preg_replace_callback(
            '~(\[contact-form([\s\S]*)?\][\s\S]*)(\[\/contact-form\])~U',
            array( $this, 'c4wp_jetpack_cf_callback' ),
            $content
        );
    }
	
	/**
	 * This function checks shortcode on contents and return captcha.
	 * @package WP Captcha
	 * @version 1.0.0
	 * @author  Devnath verma
	 */
    public function c4wp_jetpack_cf_callback( $matches ) {
		
		if ( ! preg_match( "~\[jetpack_c4wp\]~", $matches[0] ) ) {
            
			return $matches[1] . "[jetpack_c4wp]" . $matches[3];
        }
		
        return $matches[0];
    }
	
	/**
	 * This function checks shortcode on contents and return captcha.
	 * @package WP Captcha
	 * @version 1.0.0
	 * @author  Devnath verma
	 */
	public function c4wp_render_block( $block_content, $block ) {
		
		if ( 'jetpack/field-textarea' === $block['blockName'] ) {
		
			preg_match( '/\n*<(?P<level>h[1-6])/si', $block['innerHTML'], $matches );
			$wrapped_block_content = sprintf(
				'%s[jetpack_c4wp]',
				$block_content
			);
		} 
				
		return ! empty( $wrapped_block_content ) ? $wrapped_block_content : $block_content;
	}
	
	/**
	 * This function checks validations of the captcha posted with Jetpack Form.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
    public function c4wp_jetpack_cf_check( $is_spam = false ) {
		
		if ( ! empty( $_POST ) ) {
			
			$c4wp_empty_messages  = '<strong>' . __( 'Error', 'wp-captcha' ) . '</strong> : '.$this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages'];
			$c4wp_errors_messages = '<strong>' . __( 'Error', 'wp-captcha' ) . '</strong> : '.$this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages'];
		
			if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'mathematical_captcha' ) {
			
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					$is_spam = new WP_Error();
					$is_spam->add( 'c4wp_error_messages', $c4wp_empty_messages );
					add_filter( 'c4wp_captcha_content', array( $this, 'c4wp_empty_messages' ), 10, 1 );
					return $is_spam;
					
				} else if ( $_POST['c4wp_user_input_captcha'] != WP_CAPTCHA()->c4wp_object->c4wp_captcha_decode( $_POST['c4wp_random_input_captcha'] ) ) {
					
					$is_spam = new WP_Error();
					$is_spam->add( 'c4wp_error_messages', $c4wp_errors_messages );
					add_filter( 'c4wp_captcha_content', array( $this, 'c4wp_error_messages' ), 10, 1 );
					return $is_spam;
				
				} else {
					
					return true;
				}
			
			} else if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'image_captcha' ) {
				
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					$is_spam = new WP_Error();
					$is_spam->add( 'c4wp_error_messages', $c4wp_empty_messages );
					add_filter( 'c4wp_captcha_content', array( $this, 'c4wp_empty_messages' ), 10, 1 );
					return $is_spam;
					
				} else if ( !in_array( $_POST['c4wp_user_input_captcha'], $_SESSION['c4wp_random_input_captcha'], true ) ) {
					
					$is_spam = new WP_Error();
					$is_spam->add( 'c4wp_error_messages', $c4wp_errors_messages );
					add_filter( 'c4wp_captcha_content', array( $this, 'c4wp_error_messages' ), 10, 1 );
					return $is_spam;
				
				} else {
					
					unset($_SESSION['c4wp_random_input_captcha']);
					return true;
				}
			
			} else if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'icon_captcha' ) {
			
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					$is_spam = new WP_Error();
					$is_spam->add( 'c4wp_error_messages', $c4wp_empty_messages );
					add_filter( 'c4wp_captcha_content', array( $this, 'c4wp_empty_messages' ), 10, 1 );
					return $is_spam;
					
				} else if ( $_POST['c4wp_user_input_captcha'] != $_POST['c4wp_random_input_captcha'] ) {
					
					$is_spam = new WP_Error();
					$is_spam->add( 'c4wp_error_messages', $c4wp_errors_messages );
					add_filter( 'c4wp_captcha_content', array( $this, 'c4wp_error_messages' ), 10, 1 );
					return $is_spam;
				
				} else {
					
					return true;
				}
			
			} else {
				
				if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
					
					$is_spam = new WP_Error();
					$is_spam->add( 'c4wp_error_messages', $c4wp_empty_messages );
					add_filter( 'c4wp_captcha_content', array( $this, 'c4wp_empty_messages' ), 10, 1 );
					return $is_spam;
				
				} else if ( isset( $_POST['g-recaptcha-response'] ) && WP_CAPTCHA()->c4wp_object->c4wp_validate_captcha( $_POST['g-recaptcha-response'] ) == false ) {
					
					$is_spam = new WP_Error();
					$is_spam->add( 'c4wp_error_messages', $c4wp_errors_messages );
					add_filter( 'c4wp_captcha_content', array( $this, 'c4wp_error_messages' ), 10, 1 );
					return $is_spam;
				
				} else {
		
					return true;
				}
			}
		}
    }
	
	/**
	 * This function return empty messages of the captcha posted with Jetpack Form.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_empty_messages( $c4wp_empty_messages = '' ) {
	
        $c4wp_empty_messages = sprintf('<strong>' . __( 'Error', 'wp-captcha' ) . '</strong> : '.$this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages']) . $c4wp_empty_messages;
        return $c4wp_empty_messages;
    }
	
	/**
	 * This function return error messages of the captcha posted with Jetpack Form.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_error_messages( $c4wp_error_messages = '' ) {
	
        $c4wp_error_messages = sprintf('<strong>' . __( 'Error', 'wp-captcha' ) . '</strong> : '.$this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages']) . $c4wp_error_messages;
        return $c4wp_error_messages;
    }
}