<?php
/**
 * Fired during plugin activation.
 * This class defines all code necessary to run during the plugin's activation.
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma <devnathverma@gmail.com>
 */
 
class C4WP_Plugin_Activator {
		
	/**
	 * On plugin activation "Saved" default settings.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 * @uses 	 add_option()
	 */
	public static function c4wp_activate() {
		
		$c4wp_options 	= 	array(
			'c4wp_options' 	=> 	array(
				'captcha_enable_for' 	=> 	'mathematical_captcha',
				'mathematical_captcha_setting' 	=> 	array(
						'addition' 			=> 	true,
						'substruction' 		=> 	false,
						'multiplication'	=> 	false,
						'division'			=> 	false,
						'numbers'			=> 	true,
						'words'				=> 	false
				),
				'image_captcha_setting' => 	array(
						'widht' 				=> 	'200',
						'height' 				=> 	'50',
						'characters_on_image' 	=> 	'4',
						'random_dots' 			=> 	'5',
						'random_lines' 			=> 	'10',
						'text_color' 			=> 	'#0071a1',
						'noise_color' 			=> 	'#3498DB',
						'character_types' 		=> 	'only_numbers',
						'text_case' 			=> 	'lower_case',
						'select_fonts' 			=> 	'TimesNewRoman.ttf'
				),
				'icon_captcha_setting' 	=> 	array(
						'number_of_icons' 		=> 	'5',
						'border_width' 			=> 	'1px',
						'border_style' 			=> 	'solid',
						'border_color' 			=> 	'#3498DB',
						'icon_color' 			=> 	'#3498DB',
				),
				'google_recaptcha_setting' 	=> 	array(
						'site_key' 				=> 	'',
						'secret_key' 			=> 	'',
						'recaptcha_size' 		=> 	'normal',
						'recaptcha_theme' 		=> 	'light',
						'recaptcha_type' 		=> 	'image',
						'reCaptcha_language' 	=> 	'en'
				),
				'enable_form_settings'		=> 	array(
						'wp_login_form' 			=> 	true,
						'wp_registration_form' 	   	=> 	true,
						'wp_reset_password_form' 	=> 	true,
						'wp_comment_form' 			=> 	false,
						'contact_form7' 			=> 	false,
						'subscriber_form' 			=> 	false,
						'mc4wp_form' 			   	=> 	false,
						'jetpack_contact_form' 	   	=> 	false,
						'bbpress_topic_form'	   	=> 	false,
						'bbpress_reply_form' 	   	=> 	false,
						'bd_registration_form' 	   	=> 	false,
						'bd_create_group_form' 	   	=> 	false,
						'aws_login_form' 	 		=> 	false,
						'aws_registration_form' 	=> 	false,
						'wc_login_form' 			=> 	false,
						'wc_registration_form' 	   	=> 	false,
						'wc_reset_password_form' 	=> 	false,
						'wc_checkout_form' 		   	=> 	false
				),
				'other_settings'	=> 	array(
						'captcha_title' 			=> 	'Solve Captcha*',
						'captcha_empty_messages' 	=> 	trim('Your captcha is empty. Please try again.'),
						'captcha_error_messages' 	=> 	trim('You have entered an incorrect CAPTCHA. Please try again.'),
						'hide_for_logged_users' 	=> 	false,
						'deactivation_delete' 		=> 	false
				),
			), 
		);
		
		if( get_option( 'c4wp_default_settings' ) ) {
		
			update_option( 'c4wp_default_settings', $c4wp_options );
		
		} else {
		
			add_option( 'c4wp_default_settings', $c4wp_options, '', true );   
		}
	}
}