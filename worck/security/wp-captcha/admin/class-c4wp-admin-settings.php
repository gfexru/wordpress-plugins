<?php
/**
 * This class defines all necessary settings for the plugin's.
 * @package WP Captcha
 * @version 1.0.0
 * @author  Devnath verma
 */
 
class C4WP_Admin_Settings {
	
	public $c4wp_plugin_options;
	public $c4wp_messages = '';
	public $c4wp_setting_messages;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $c4wp_plugin_options ) {
		
		// add "WP Captcha" Admin Menus page
		add_action( 'admin_menu', array( $this, 'c4wp_register_settings_menu' ) );
		
		$this->c4wp_plugin_options 	=  $c4wp_plugin_options;
		$this->c4wp_setting_messages = array(
			'save_settings'	 	=> 		__( 'Settings save successfully.', 'wp-captcha' ),
			'update_settings'	=> 		__( 'Settings restore successfully.', 'wp-captcha' )
		);
	}

	/**
	 * Create our " WP Captcha " page as a Admin Menus page
 	 * @package  WP Captcha
 	 * @version  1.0.0
 	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_register_settings_menu() {
		
		$c4wp_menu_page = add_menu_page(
			__( 'WP Captcha', 'wp-captcha' ), 
			__( 'WP Captcha', 'wp-captcha' ), 
			'manage_options', 
			'c4wp-settings', 
			array( &$this, 'c4wp_menu_page' ),
			'dashicons-privacy'
		);
		
		// add CSS to admin section
		add_action( 'admin_print_scripts-' . $c4wp_menu_page, array( $this, 'c4wp_admin_print_scripts') );
		
		// add JS to admin section
		add_action( 'admin_print_styles-' . $c4wp_menu_page, array( $this, 'c4wp_admin_print_styles') );
	}
	
	/**
	 * Render WP Captcha options page.
 	 * @package  WP Captcha
 	 * @version  1.0.0
 	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_menu_page() { 

		$c4wp_messages = $this->c4wp_settings_validate( $this->c4wp_messages );
		require_once C4WP_PLUGIN_ADMIN . 'view/class-c4wp-admin-wizard.php';
		C4WP_Admin_Wizard::c4wp_wizard( WP_CAPTCHA()->c4wp_get_plugin_options(), $c4wp_messages );
	}
	
	/**
	 * The function c4wp_settings_validate( ) , " save " and " restore defaults " settings.
 	 * @package  WP Captcha
 	 * @version  1.0.0
 	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_settings_validate( $c4wp_messages ) {
	
		if ( isset( $_POST['c4wp_submit'] ) ) { 
			
			$c4wp_options 	= 	array(
				'c4wp_options' 	=> 	array(
					'captcha_enable_for' 	=> 	$_POST['c4wp_options']['captcha_enable_for'],
					'mathematical_captcha_setting' 	=> 	array(
							'addition' 			=> 	isset( $_POST['c4wp_options']['mathematical_captcha_setting']['addition'] ) ? (bool) $_POST['c4wp_options']['mathematical_captcha_setting']['addition'] : false,
							'substruction' 		=> 	isset( $_POST['c4wp_options']['mathematical_captcha_setting']['substruction'] ) ? (bool) $_POST['c4wp_options']['mathematical_captcha_setting']['substruction'] : false,
							'multiplication'	=> 	isset( $_POST['c4wp_options']['mathematical_captcha_setting']['multiplication'] ) ? (bool) $_POST['c4wp_options']['mathematical_captcha_setting']['multiplication'] : false,
							'division'			=> 	isset( $_POST['c4wp_options']['mathematical_captcha_setting']['division'] ) ? (bool) $_POST['c4wp_options']['mathematical_captcha_setting']['division'] : false,
							'numbers'			=> 	isset( $_POST['c4wp_options']['mathematical_captcha_setting']['numbers'] ) ? (bool) $_POST['c4wp_options']['mathematical_captcha_setting']['numbers'] : false,
							'words'				=> 	isset( $_POST['c4wp_options']['mathematical_captcha_setting']['words'] ) ? (bool) $_POST['c4wp_options']['mathematical_captcha_setting']['words'] : false
					),
					'image_captcha_setting' => 	array(
							'widht' 				=> 	strip_tags( $_POST['c4wp_options']['image_captcha_setting']['widht'] ),
							'height' 				=> 	strip_tags( $_POST['c4wp_options']['image_captcha_setting']['height'] ),
							'characters_on_image' 	=> 	strip_tags( $_POST['c4wp_options']['image_captcha_setting']['characters_on_image'] ),	'random_dots' 			=> 	strip_tags( $_POST['c4wp_options']['image_captcha_setting']['random_dots'] ),
							'random_lines' 			=> 	strip_tags( $_POST['c4wp_options']['image_captcha_setting']['random_lines'] ),
							'text_color' 			=> 	strip_tags( $_POST['c4wp_options']['image_captcha_setting']['text_color'] ),
							'noise_color' 			=> 	strip_tags( $_POST['c4wp_options']['image_captcha_setting']['noise_color'] ),
							'character_types' 		=> 	strip_tags( $_POST['c4wp_options']['image_captcha_setting']['character_types'] ),							'text_case' 			=> 	strip_tags( $_POST['c4wp_options']['image_captcha_setting']['text_case'] ),
							'select_fonts' 			=> 	strip_tags( $_POST['c4wp_options']['image_captcha_setting']['select_fonts'] )
					),
					'icon_captcha_setting' 	=> 	array(
							'number_of_icons' 		=> 	strip_tags( $_POST['c4wp_options']['icon_captcha_setting']['number_of_icons']),
							'border_width' 			=> 	strip_tags( $_POST['c4wp_options']['icon_captcha_setting']['border_width'] ),
							'border_style' 			=> 	strip_tags( $_POST['c4wp_options']['icon_captcha_setting']['border_style'] ),
							'border_color' 			=> 	strip_tags( $_POST['c4wp_options']['icon_captcha_setting']['border_color'] ),
							'icon_color' 			=> 	strip_tags( $_POST['c4wp_options']['icon_captcha_setting']['icon_color'] )
					),
					'google_recaptcha_setting' 	=> 	array(
							'site_key' 				=> 	strip_tags( $_POST['c4wp_options']['google_recaptcha_setting']['site_key'] ),
							'secret_key' 			=> 	strip_tags( $_POST['c4wp_options']['google_recaptcha_setting']['secret_key'] ),
							'recaptcha_size' 		=> 	strip_tags( $_POST['c4wp_options']['google_recaptcha_setting']['recaptcha_size'] ),						'recaptcha_theme' 		=> 	strip_tags( $_POST['c4wp_options']['google_recaptcha_setting']['recaptcha_theme'] ),						'recaptcha_type' 		=> 	strip_tags( $_POST['c4wp_options']['google_recaptcha_setting']['recaptcha_type'] ),						'reCaptcha_language' 	=> 	strip_tags( $_POST['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'] )
					),
					'enable_form_settings'		=> 	array(
							'wp_login_form' 			=> 	isset( $_POST['c4wp_options']['enable_form_settings']['wp_login_form'] ) ? (bool) $_POST['c4wp_options']['enable_form_settings']['wp_login_form'] : false,
							'wp_registration_form' 	   	=> 	isset( $_POST['c4wp_options']['enable_form_settings']['wp_registration_form'] ) ? (bool) $_POST['c4wp_options']['enable_form_settings']['wp_registration_form'] : false,
							'wp_reset_password_form' 	=> 	isset( $_POST['c4wp_options']['enable_form_settings']['wp_reset_password_form'] ) ? (bool) $_POST['c4wp_options']['enable_form_settings']['wp_reset_password_form'] : false,
							'wp_comment_form' 			=> 	isset( $_POST['c4wp_options']['enable_form_settings']['wp_comment_form'] ) ? (bool) $_POST['c4wp_options']['enable_form_settings']['wp_comment_form'] : false,
							'contact_form7' 			=> 	isset( $_POST['c4wp_options']['enable_form_settings']['contact_form7'] ) ? (bool) $_POST['c4wp_options']['enable_form_settings']['contact_form7'] : false,
							'subscriber_form' 			=> 	isset( $_POST['c4wp_options']['enable_form_settings']['subscriber_form'] ) ? (bool) $_POST['c4wp_options']['enable_form_settings']['subscriber_form'] : false,
							'mc4wp_form' 			   	=> 	isset( $_POST['c4wp_options']['enable_form_settings']['mc4wp_form'] ) ? (bool) $_POST['c4wp_options']['enable_form_settings']['mc4wp_form'] : false,
							'jetpack_contact_form' 	   	=> 	isset( $_POST['c4wp_options']['enable_form_settings']['jetpack_contact_form'] ) ? (bool) $_POST['c4wp_options']['enable_form_settings']['jetpack_contact_form'] : false,
							'bbpress_topic_form' 	   	=> 	isset( $_POST['c4wp_options']['enable_form_settings']['bbpress_topic_form'] ) ? (bool) $_POST['c4wp_options']['enable_form_settings']['bbpress_topic_form'] : false,
							'bbpress_reply_form' 	   	=> 	isset( $_POST['c4wp_options']['enable_form_settings']['bbpress_reply_form'] ) ? (bool) $_POST['c4wp_options']['enable_form_settings']['bbpress_reply_form'] : false,
							'bd_registration_form' 	   	=> 	isset( $_POST['c4wp_options']['enable_form_settings']['bd_registration_form'] ) ? (bool) $_POST['c4wp_options']['enable_form_settings']['bd_registration_form'] : false,
							'bd_create_group_form' 	   	=> 	isset( $_POST['c4wp_options']['enable_form_settings']['bd_create_group_form'] ) ? (bool) $_POST['c4wp_options']['enable_form_settings']['bd_create_group_form'] : false,
							'aws_login_form' 	 		=> 	isset( $_POST['c4wp_options']['enable_form_settings']['aws_login_form'] ) ? (bool) $_POST['c4wp_options']['enable_form_settings']['aws_login_form'] : false,
							'aws_registration_form' 	=> 	isset( $_POST['c4wp_options']['enable_form_settings']['aws_registration_form'] ) ? (bool) $_POST['c4wp_options']['enable_form_settings']['aws_registration_form'] : false,
							'wc_login_form' 			=> 	isset( $_POST['c4wp_options']['enable_form_settings']['wc_login_form'] ) ? (bool) $_POST['c4wp_options']['enable_form_settings']['wc_login_form'] : false,
							'wc_registration_form' 	   	=> 	isset( $_POST['c4wp_options']['enable_form_settings']['wc_registration_form'] ) ? (bool) $_POST['c4wp_options']['enable_form_settings']['wc_registration_form'] : false,
							'wc_reset_password_form' 	=> 	isset( $_POST['c4wp_options']['enable_form_settings']['wc_reset_password_form'] ) ? (bool) $_POST['c4wp_options']['enable_form_settings']['wc_reset_password_form'] : false,
							'wc_checkout_form' 		   	=> 	isset( $_POST['c4wp_options']['enable_form_settings']['wc_checkout_form'] ) ? (bool) $_POST['c4wp_options']['enable_form_settings']['wc_checkout_form'] : false
					),
					'other_settings'	=> 	array(
							'captcha_title' 			=> 	isset( $_POST['c4wp_options']['other_settings']['captcha_title'] ) ? strip_tags( $_POST['c4wp_options']['other_settings']['captcha_title'] ) : '',
							'captcha_empty_messages' 	=> 	isset( $_POST['c4wp_options']['other_settings']['captcha_empty_messages'] ) ? strip_tags( $_POST['c4wp_options']['other_settings']['captcha_empty_messages'] ) : '',
							'captcha_error_messages' 	=> 	isset( $_POST['c4wp_options']['other_settings']['captcha_error_messages'] ) ? strip_tags( $_POST['c4wp_options']['other_settings']['captcha_error_messages'] ) : '',
							'hide_for_logged_users' 	=> 	isset( $_POST['c4wp_options']['other_settings']['hide_for_logged_users'] ) ? (bool) $_POST['c4wp_options']['other_settings']['hide_for_logged_users'] : false,
							'deactivation_delete' 		=> 	isset( $_POST['c4wp_options']['other_settings']['deactivation_delete'] ) ? (bool) $_POST['c4wp_options']['other_settings']['deactivation_delete'] : false
					),
				), 
			);
			 
            update_option( 'c4wp_default_settings', $c4wp_options );
            return $c4wp_messages = $this->c4wp_setting_messages['save_settings'];
			
		} else if ( isset( $_POST['c4wp_reset_submit'] ) ) {
			 
			require_once C4WP_PLUGIN_INCLUDES . 'class-c4wp-activator.php';
			C4WP_Plugin_Activator::c4wp_activate();
			return $c4wp_messages = $this->c4wp_setting_messages['update_settings'];
		}
	}
	 
	/**
	 * Register the scripts for the admin-facing side of the site.
 	 * @package  WP Captcha
 	 * @version  1.0.0
 	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_admin_print_scripts() {
		
		if( is_admin() ) { 
            
			global $wp_version;
			
			if ( 3.5 <= $wp_version ) {
			
			  	wp_enqueue_style( 'wp-color-picker' );
			  	wp_enqueue_script( 'wp-color-picker' );
			
			} else {
			
			  	wp_enqueue_style( 'farbtastic' );
			  	wp_enqueue_script( 'farbtastic' );
			}
			
			wp_enqueue_script( 'c4wp-admin', C4WP_PLUGIN_JS . 'c4wp-admin.js', array('jquery') );
		}
	}
	
	/**
	 * Register the stylesheets for the admin-facing side of the site.
 	 * @package  WP Captcha
 	 * @version  1.0.0
 	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_admin_print_styles() {
		
		wp_enqueue_style( 'c4wp-bootstrap-min', C4WP_PLUGIN_CSS . 'c4wp-bootstrap-min.css' );
		wp_enqueue_style( 'c4wp-admin', C4WP_PLUGIN_CSS . 'c4wp-admin.css' );
	}
	
	/**
	 * Get fonts from fonts dir for admin-facing side of the site.
 	 * @package  WP Captcha
 	 * @version  1.0.0
 	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_list_files( $folder = '', $levels = 100, $exclusions = array() ) {
			
		if ( empty( $folder ) ) {
			return false;
		}
	
		$folder = trailingslashit( $folder );
	
		if ( ! $levels ) {
			return false;
		}
	
		$files = array();
	
		$dir = @opendir( $folder );
		
		if ( $dir ) {
			
			while ( ( $file = readdir( $dir ) ) !== false ) {
				
				// Skip current and parent folder links.
				if ( in_array( $file, array( '.', '..' ), true ) ) {
					continue;
				}
	
				// Skip hidden and excluded files.
				if ( '.' === $file[0] || in_array( $file, $exclusions, true ) ) {
					continue;
				}
				
				$is_ttf = explode('.', $file);
				$files[$file] = $is_ttf[0];
			}

			closedir( $dir );
		}
	
		return $files;
	}
}