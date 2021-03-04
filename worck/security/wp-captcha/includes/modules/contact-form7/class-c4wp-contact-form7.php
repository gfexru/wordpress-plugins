<?php
/**
 * The functionality that handles the display of the Captcha form on the contact form 7.
 * @package  	WP Captcha
 * @subpackage  WP Captcha/modules/contact-form7/class-c4wp-contact-form7
 * @author   	Devnath verma <devnathverma@gmail.com>
 */

/**
 * Class responsible for generating the display of the Captcha on contact form 7.
 * Gets called only if the "display captcha on contact form 7" option is checked in the back-end
 * @package  	WP Captcha
 * @version  	1.0.0
 * @author   	Devnath verma <devnathverma@gmail.com>
 */
 
class C4WP_Contact_Form7 {
	
	// @type defaults variables
	public $c4wp_plugin_options;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $c4wp_plugin_options ) {
		
		$this->c4wp_plugin_options 	=  	$c4wp_plugin_options;
				
		// adds the required HTML for the captcha to the contact form 7		
		add_action( 'wpcf7_init', array( $this, 'wpcf7_add_shortcode_wpcaptcha') );
		
		// adds the Tag to Contact form 7 plugin
		add_action( 'admin_init', array( $this, 'wpcf7_add_tag_generator_wpcaptcha' ), 45 );
		
		// validate the captcha answer on contact form 7
		add_filter( 'wpcf7_validate_wpcaptcha', array( $this, 'wpcf7_wpcaptcha_validation_filter' ), 10, 2 );
		
		// adds the error messages fields for the captcha to the contact form 7 plugin
		add_filter( 'wpcf7_messages', array( $this, 'wpcf7_wpcaptcha_messages' ) );
	}
	
	/**
	 * Create shortcode for contact form 7 plugin.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function wpcf7_add_shortcode_wpcaptcha() {
				
        if (function_exists('wpcf7_add_form_tag'))
           wpcf7_add_form_tag( 'wpcaptcha', array( $this, 'wpcf7_wpcaptcha_shortcode_handler' ), true );
        else if (function_exists('wpcf7_add_shortcode'))
           wpcf7_add_shortcode( 'wpcaptcha', array( $this, 'wpcf7_wpcaptcha_shortcode_handler' ), true );
        else
           throw new Exception( 'functions wpcf7_add_form_tag and wpcf7_add_shortcode not found.' );
    }
	
	/**
	 * Generate captcha to Provide a public-facing view for the plugin on contact form 7.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function wpcf7_wpcaptcha_shortcode_handler( $tag ) {
			
		$tag = new WPCF7_FormTag( $tag );

		if ( empty( $tag->name ) )
		return '';

		$validation_error = wpcf7_get_validation_error( $tag->name );
		$class = wpcf7_form_controls_class( $tag->type );

		if ( $validation_error )
		$class .= ' wpcf7-not-valid';

		$atts = array();
		$atts['size'] = 2;
		$atts['maxlength'] = 2;
		$atts['class'] = $tag->get_class_option( $class );
		$atts['id'] = $tag->get_option( 'id', 'id', true );
		$atts['tabindex'] = $tag->get_option( 'tabindex', 'int', true );
		$atts['aria-required'] = 'true';
		$atts['type'] = 'text';
		$atts['name'] = $tag->name;
		$atts['value'] = '';
		$atts = wpcf7_format_atts( $atts );
		
		return sprintf( '<span class="wpcf7-form-control-wrap %1$s">' . WP_CAPTCHA()->c4wp_object->c4wp_generate_captcha() . '%3$s</span><input type="hidden" name="' . $tag->name . '-sn" />', $tag->name, $atts, $validation_error );
	}
	
	/**
	 * Create WP Captcha " Tag " in Contact Form 7 Plugin.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function wpcf7_add_tag_generator_wpcaptcha() {
		
		if ( ! function_exists( 'wpcf7_add_tag_generator' ) )
		return;
		
		wpcf7_add_tag_generator( 'wpcaptcha', __( 'WP Captcha', 'wp-captcha' ), 'wpcf7-wpcaptcha', array( $this, 'wpcf7_tg_pane_wpcaptcha' ) );
	
	}
	
	/**
	 * Create WP Captcha " Tag attributes" in contact form 7 Plugin.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function wpcf7_tg_pane_wpcaptcha( $contact_form ) { ?>
		
		<div class="control-box">
			<fieldset>
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">
								<label for="tag-generator-panel-c4wp-name"><?php _e( 'Name', 'contact-form-7' ); ?></label>
							</th>
							<td>
								<input type="text" name="name" class="tg-name oneline" id="tag-generator-panel-c4wp-name" />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="tag-generator-panel-c4wp-id"><?php _e( 'Id attribute', 'contact-form-7' ); ?></label>
							</th>
							<td>
								<input type="text" name="id" class="idvalue oneline option" id="tag-generator-panel-c4wp-id" />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="tag-generator-panel-c4wp-class"><?php _e( 'Class attribute', 'contact-form-7' ); ?></label>
							</th>
							<td>
								<input type="text" name="class" class="classvalue oneline option" id="tag-generator-panel-c4wp-class" />
							</td>
						</tr>
					</tbody>
				</table>
			</fieldset>
		</div>
		<div class="insert-box">
			<input type="text" name="wpcaptcha" class="tag code" readonly="readonly" onfocus="this.select();">
			<div class="submitbox">
				<input type="button" class="button button-primary insert-tag" value="<?php _e( 'Insert Tag', 'contact-form-7' ); ?>">
			</div>
			<br class="clear">
		</div>
	
	<?php }
	
	/**
	 * This function checks validations of the captcha posted with contact form 7.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function wpcf7_wpcaptcha_validation_filter( $result, $tag ) {
		
		$tag = new WPCF7_FormTag( $tag );
		
		if( !empty( $_POST ) ) {
		
			if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'mathematical_captcha' ) {
			
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					$result->invalidate( $tag, wpcf7_get_message( 'c4wp_fill_captcha' ) );
					
				} else if ( $_POST['c4wp_user_input_captcha'] != WP_CAPTCHA()->c4wp_object->c4wp_captcha_decode( $_POST['c4wp_random_input_captcha'] ) ) {
					
					$result->invalidate( $tag, wpcf7_get_message( 'c4wp_wrong_captcha' ) );
				
				} else {
					
					$result['valid'] = true;
				}
			
			} else if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'image_captcha' ) {
				
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					$result->invalidate( $tag, wpcf7_get_message( 'c4wp_fill_captcha' ) );
					
				} else if ( !in_array( $_POST['c4wp_user_input_captcha'], $_SESSION['c4wp_random_input_captcha'], true ) ) {
					
					$result->invalidate( $tag, wpcf7_get_message( 'c4wp_wrong_captcha' ) );
				
				} else {
					
					unset($_SESSION['c4wp_random_input_captcha']);
					$result['valid'] = true;
				}
			
			} else if ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'icon_captcha' ) {
			
				if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
			
					$result->invalidate( $tag, wpcf7_get_message( 'c4wp_fill_captcha' ) );
					
				} else if ( $_POST['c4wp_user_input_captcha'] != $_POST['c4wp_random_input_captcha'] ) {
					
					$result->invalidate( $tag, wpcf7_get_message( 'c4wp_wrong_captcha' ) );
				
				} else {
					
					$result['valid'] = true;
				}
			
			} else {
				
				if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
					
					$result->invalidate( $tag, wpcf7_get_message( 'c4wp_fill_captcha' ) );
				
				} else if ( isset( $_POST['g-recaptcha-response'] ) && WP_CAPTCHA()->c4wp_object->c4wp_validate_captcha( $_POST['g-recaptcha-response'] ) == false ) {
					
					$result->invalidate( $tag, wpcf7_get_message( 'c4wp_wrong_captcha' ) );
				
				} else {
		
					$result['valid'] = true;
				}
			}
		}
		
		return $result;
	}
	
	/**
	 * This function return messages fields in contact form 7 plugin backend.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function wpcf7_wpcaptcha_messages( $messages ) {
		
		return array_merge(
			$messages,
			array(
				'c4wp_fill_captcha'	 => array(
					'description'	 => __( 'Your captcha is empty. Please try again.', 'wp-captcha' ),
					'default'		 => wp_strip_all_tags( $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages'], true )
				),
				'c4wp_wrong_captcha'	 => array(
					'description'	 => __( 'You have entered an incorrect CAPTCHA. Please try again.', 'wp-captcha' ),
					'default'		 => wp_strip_all_tags( $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages'], true )
				)
			)
		);
	}
}