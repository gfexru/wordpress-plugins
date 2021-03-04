<?php
/**
 * This class defines all necessary settings for image Captcha.
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma <devnathverma@gmail.com>
 */
 
include '../../../../../wp-load.php';
include dirname(__FILE__) . '/class-c4wp-create-image-captcha.php';
$c4wp_options = get_option('c4wp_default_settings');
$c4wp_captcha_type	= $c4wp_options['c4wp_options']['image_captcha_setting']['character_types'];
$c4wp_text_case		= $c4wp_options['c4wp_options']['image_captcha_setting']['text_case'];
switch ( $c4wp_captcha_type ) {
	
	case 'only_numbers':
		
		$c4wp_possible_letters = '0123456789';
		
		break;

	case 'only_alphabets':
		
		if ( 'mixed' == $c4wp_text_case ) {
			
			$c4wp_possible_letters = 'ABCDEFGHKLMNPRSTUVWYZabcdefghklmnprstuvwyz';
		
		} else if ( 'upper_case' == $c4wp_text_case ) {
		
			$c4wp_possible_letters = 'ABCDEFGHKLMNPRSTUVWYZ';
		
		} else {
		
			$c4wp_possible_letters = 'abcdefghklmnprstuvwyz';
		}
		
		break;

	case 'alphabets_and_numbers':
		
		if ( 'mixed' == $c4wp_text_case ) {
		
			$c4wp_possible_letters = 'ABCDEFGHKLMNPRSTUVWYZabcdefghklmnprstuvwyz0123456789';
		
		} else if ( 'upper_case' == $c4wp_text_case ) {
		
			$c4wp_possible_letters = 'ABCDEFGHKLMNPRSTUVWYZ0123456789';
		
		} else {
		
			$c4wp_possible_letters = 'abcdefghklmnprstuvwyz0123456789';
		}
		
		break;
}

$c4wp_imgcaptcha_paramenters['c4wp_image_width'] 	  = $c4wp_options['c4wp_options']['image_captcha_setting']['widht'];
$c4wp_imgcaptcha_paramenters['c4wp_image_height'] 	  = $c4wp_options['c4wp_options']['image_captcha_setting']['height'];
$c4wp_imgcaptcha_paramenters['c4wp_fonts']			  = dirname( dirname( dirname( __FILE__ ) ) ) .'/assets/fonts/'.$c4wp_options['c4wp_options']['image_captcha_setting']['select_fonts'];
$c4wp_imgcaptcha_paramenters['c4wp_char_on_image'] 	  = $c4wp_options['c4wp_options']['image_captcha_setting']['characters_on_image'];
$c4wp_imgcaptcha_paramenters['c4wp_random_dots'] 	  = $c4wp_options['c4wp_options']['image_captcha_setting']['random_dots'];
$c4wp_imgcaptcha_paramenters['c4wp_random_lines'] 	  = $c4wp_options['c4wp_options']['image_captcha_setting']['random_lines'];
$c4wp_imgcaptcha_paramenters['c4wp_text_color'] 	  = $c4wp_options['c4wp_options']['image_captcha_setting']['text_color'];
$c4wp_imgcaptcha_paramenters['c4wp_noice_color'] 	  = $c4wp_options['c4wp_options']['image_captcha_setting']['noise_color'];
$c4wp_imgcaptcha_paramenters['c4wp_possible_letters'] = $c4wp_possible_letters;
$c4wp_return_imgobj = new C4WP_Create_Image_Captcha( $c4wp_imgcaptcha_paramenters );
$c4wp_return_imgobj->createCaptcha();