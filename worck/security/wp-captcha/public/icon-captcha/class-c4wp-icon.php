<?php 
/**
 * Class responsible for display of the Icon Captcha on the enabled form.
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma <devnathverma@gmail.com>
 */

class C4WP_Icon {
	
	// @type defaults variables
	public $c4wp_plugin_options;
	
	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $c4wp_plugin_options ) {
		
		$this->c4wp_plugin_options 	=  $c4wp_plugin_options;
		
		// Hook to the 'init' action, which is called Icon Captcha actions and filters.
		add_action( 'init', array( $this, 'c4wp_icon_actions_filters' ), 9 );
	}
	
	/**
	 * Apply required filters.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_icon_actions_filters( ) {
		
		$c4wp_user_loggged_in = is_user_logged_in();
		
		// IF captcha enabled for " Wordpress Login Form " 
		if ( isset( $this->c4wp_plugin_options['c4wp_options']['enable_form_settings']['wp_login_form'] ) && $this->c4wp_plugin_options['c4wp_options']['enable_form_settings']['wp_login_form'] ) {
			
			require_once C4WP_PLUGIN_MODULES . 'wordpress/class-c4wp-login.php';
			new C4WP_Wordpress_Login( $this->c4wp_plugin_options );
		}
		
		// IF captcha enabled for " Wordpress Register Form " 
		if( isset( $this->c4wp_plugin_options['c4wp_options']['enable_form_settings']['wp_registration_form'] ) && $this->c4wp_plugin_options['c4wp_options']['enable_form_settings']['wp_registration_form'] ) {
			
			require_once C4WP_PLUGIN_MODULES . 'wordpress/class-c4wp-register.php';
			new C4WP_Wordpress_Register( $this->c4wp_plugin_options );			
		}
		
		// IF captcha enabled for " Wordpress Comments Form " 
		if ( WP_CAPTCHA()->c4wp_needed( 'wp_comment_form', $c4wp_user_loggged_in ) ) {
		
			require_once C4WP_PLUGIN_MODULES . 'wordpress/class-c4wp-comments.php';
			new C4WP_Wordpress_Comments( $this->c4wp_plugin_options );
		}
		
		// IF captcha enabled for " Contact Form 7 " 
		if ( WP_CAPTCHA()->c4wp_needed( 'contact_form7', $c4wp_user_loggged_in ) ) {
			
			require_once C4WP_PLUGIN_MODULES . 'contact-form7/class-c4wp-contact-form7.php';
			new C4WP_Contact_Form7( $this->c4wp_plugin_options );
		}
		
		// IF captcha enabled for " Subscriber Form by ( BestWebSoft ) " 
		if ( WP_CAPTCHA()->c4wp_needed( 'subscriber_form', $c4wp_user_loggged_in ) ) {
			
			require_once C4WP_PLUGIN_MODULES . 'subscriber/class-c4wp-subscriber.php';
			new C4WP_Subscriber( $this->c4wp_plugin_options );
		}
		
		// IF captcha enabled for " Mailchimp Form " 
		if ( WP_CAPTCHA()->c4wp_needed( 'mc4wp_form', $c4wp_user_loggged_in ) ) {
			
			require_once C4WP_PLUGIN_MODULES . 'mailchimp/class-c4wp-mailchimp.php';
			new C4WP_Mailchimp( $this->c4wp_plugin_options );
		}
		
		// IF captcha enabled for " Jetpack Contact Form " 
		if ( WP_CAPTCHA()->c4wp_needed( 'jetpack_contact_form', $c4wp_user_loggged_in ) ) {
			
			require_once C4WP_PLUGIN_MODULES . 'jetpack-forms/class-c4wp-jetpack.php';
			new C4WP_Jetpack( $this->c4wp_plugin_options );
		}
		
		// IF captcha enabled for " BBpress New Topic Form " 
		if ( WP_CAPTCHA()->c4wp_needed( 'bbpress_topic_form', $c4wp_user_loggged_in ) ) {
			
			require_once C4WP_PLUGIN_MODULES . 'bbpress/class-c4wp-new-topic.php';
			new C4WP_BBPress_New_Topic( $this->c4wp_plugin_options );	
		}
		
		// IF captcha enabled for " BBpress Replay Form " 
		if ( WP_CAPTCHA()->c4wp_needed( 'bbpress_reply_form', $c4wp_user_loggged_in ) ) {
			
			require_once C4WP_PLUGIN_MODULES . 'bbpress/class-c4wp-reply.php';
			new C4WP_BBPress_Reply( $this->c4wp_plugin_options );	
		}
		
		// IF captcha enabled for " Buddypress Registration Form " 
		if ( isset( $this->c4wp_plugin_options['c4wp_options']['enable_form_settings']['bd_registration_form'] ) && $this->c4wp_plugin_options['c4wp_options']['enable_form_settings']['bd_registration_form'] ) {
			
			require_once C4WP_PLUGIN_MODULES . 'buddypress/class-c4wp-register.php';
			new C4WP_Buddypress_Register( $this->c4wp_plugin_options );	
		}
		
		// IF captcha enabled for " Buddypress Create Group Form " 
		if ( isset( $this->c4wp_plugin_options['c4wp_options']['enable_form_settings']['bd_create_group_form'] ) && $this->c4wp_plugin_options['c4wp_options']['enable_form_settings']['bd_create_group_form'] ) {
			
			require_once C4WP_PLUGIN_MODULES . 'buddypress/class-c4wp-create-group.php';
			new C4WP_Buddypress_Create_Group( $this->c4wp_plugin_options );	
		}
		
		// IF captcha enabled for " Awesome Support Login Form " 
		if ( isset( $this->c4wp_plugin_options['c4wp_options']['enable_form_settings']['aws_login_form'] ) && $this->c4wp_plugin_options['c4wp_options']['enable_form_settings']['aws_login_form']  ) {
			
			require_once C4WP_PLUGIN_MODULES . 'awesome-support/class-c4wp-login.php';
			new C4WP_Awesome_Support_Login( $this->c4wp_plugin_options );	
		}
		
		// IF captcha enabled for " Awesome Support Registration Form " 
		if ( isset( $this->c4wp_plugin_options['c4wp_options']['enable_form_settings']['aws_registration_form'] ) && $this->c4wp_plugin_options['c4wp_options']['enable_form_settings']['aws_registration_form'] ) {
			
			require_once C4WP_PLUGIN_MODULES . 'awesome-support/class-c4wp-register.php';
			new C4WP_Awesome_Support_Register( $this->c4wp_plugin_options );	
		}
		
		// IF captcha enabled for " Woocommerce login Form " 
		if( isset( $this->c4wp_plugin_options['c4wp_options']['enable_form_settings']['wc_login_form'] ) && $this->c4wp_plugin_options['c4wp_options']['enable_form_settings']['wc_login_form'] ) {
			 
			require_once C4WP_PLUGIN_MODULES . 'woocommerce/class-c4wp-login.php';
			new C4WP_Woocommerce_Login( $this->c4wp_plugin_options );	
		}
		
		// IF captcha enabled for " Woocommerce Registration Form " 
		if( isset( $this->c4wp_plugin_options['c4wp_options']['enable_form_settings']['wc_registration_form'] ) && $this->c4wp_plugin_options['c4wp_options']['enable_form_settings']['wc_registration_form'] ) {
			 
			require_once C4WP_PLUGIN_MODULES . 'woocommerce/class-c4wp-register.php';
			new C4WP_Woocommerce_Register( $this->c4wp_plugin_options );	
		}
		
		// IF captcha enabled for " Woocommerce Reset Password Form " 
		if( isset( $this->c4wp_plugin_options['c4wp_options']['enable_form_settings']['wc_reset_password_form'] ) && $this->c4wp_plugin_options['c4wp_options']['enable_form_settings']['wc_reset_password_form'] ) {
			 
			require_once C4WP_PLUGIN_MODULES . 'woocommerce/class-c4wp-reset-password.php';
			new C4WP_Woocommerce_Reset_Password( $this->c4wp_plugin_options );	
		}
		
		// IF captcha enabled for " Woocommerce Checkout Form " 
		if ( WP_CAPTCHA()->c4wp_needed( 'wc_checkout_form', $c4wp_user_loggged_in ) ) {
			
			require_once C4WP_PLUGIN_MODULES . 'woocommerce/class-c4wp-checkout.php';
			new C4WP_Woocommerce_Checkout( $this->c4wp_plugin_options );	
		}
		
		// IF captcha enabled for " Wordpress Reset Password Form " 
		if( isset( $this->c4wp_plugin_options['c4wp_options']['enable_form_settings']['wp_reset_password_form'] ) && $this->c4wp_plugin_options['c4wp_options']['enable_form_settings']['wp_reset_password_form'] ) {
			
			require_once C4WP_PLUGIN_MODULES . 'wordpress/class-c4wp-reset-password.php';
			new C4WP_Wordpress_Reset_Password( $this->c4wp_plugin_options );				
		}
	}

	/**
	 * Display captcha to Provide a public-facing view for the plugin.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_display_captcha( ) {
		
		echo $this->c4wp_generate_captcha();
	}
	
	/**
	 * generate captcha to Provide a public-facing view for the plugin.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_generate_captcha( ) {
		
		$num = 1;
		$c4wp_get_icons = $this->c4wp_get_icons();
		$c4wp_term = $this->c4wp_array_random($c4wp_get_icons['c4wp_term']);
		$c4wp_icon_result = $this->c4wp_tid_notin_array($c4wp_get_icons['c4wp_captcha_icons'], $c4wp_term['id']);
		$number_of_icons = $this->c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['number_of_icons'] - $num;
		$c4wp_icon_result = $this->c4wp_array_random($c4wp_icon_result, $number_of_icons);
		$c4wp_random_icon_result = $this->c4wp_tid_in_array($c4wp_get_icons['c4wp_captcha_icons'], $c4wp_term['id']);
		$c4wp_captcha_output = array_merge($c4wp_icon_result, $c4wp_random_icon_result);
			
			$return  = '';
    	if ( !empty( $c4wp_get_icons ) ) {
			
			$return .= '<p class="c4wp-display-captcha-form">';
			$return .= '<div class="c4wp-icon-captcha-container" style="border:'.$this->c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['border_width'].' '.$this->c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['border_style'].' '.$this->c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['border_color'].'">';
			$return .= '<div class="c4wp-icon-name">';
			$return .= '<label for="'.$this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_title'].'">';
			if( isset($this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_title']) && ! empty( $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_title'] ) ) {
				$return .= $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_title'];
			}
			$return .= '&nbsp;<strong style="color:'.$this->c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['icon_color'].'">' . $c4wp_term['name'] . '</strong>';
			$return .= '</label>';
			$return .= '</div>';
			$return .= '<input type="hidden" name="c4wp_random_input_captcha" value="'.$c4wp_term['slug'].'">';
			shuffle($c4wp_captcha_output);
			if (! empty($c4wp_captcha_output)) {
				foreach ($c4wp_captcha_output as $value) {
					$return .= '<div class="c4wp-svg-padding">';
					$return .= '<div class="c4wp-svg" style="color:'.$this->c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['icon_color'].'">';
					$return .= $value['captcha_icon'];
					$return .= '<input type="hidden" class="c4wp-icons" value="'.$value['slug'].'">';
					$return .= '</div>';
					$return .= '</div>';
			  	}
			}
			$return .= '</div>';
			$return .= '<input type="hidden" name="c4wp_user_input_captcha" id="c4wp_user_input_captcha" value="">';
			$return .= '</p>';	
		}
		
		return $return;
	}
	
	/**
	 * Return of icons in aaray for the plugin.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_get_icons( ) {
	
		$c4wp_captchas = array (
				'c4wp_captcha_icons' 	=>	array (
						'1'	=>	array (
							'term_id'	=>	'1',
							'slug'	=>	'cup', 
							'captcha_icon'	=>	'<svg width="20px" height="20px" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path fill="currentColor" d="M192 384h192c53 0 96-43 96-96h32c70.6 0 128-57.4 128-128S582.6 32 512 32H120c-13.3 0-24 10.7-24 24v232c0 53 43 96 96 96zM512 96c35.3 0 64 28.7 64 64s-28.7 64-64 64h-32V96h32zm47.7 384H48.3c-47.6 0-61-64-36-64h583.3c25 0 11.8 64-35.9 64z"></path></svg>'
						),
						'2'	=>	array (
							'term_id'	=>	'2',
							'slug'	=>	'star', 
							'captcha_icon'	=>	'<svg width="20px" height="20px" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>'
						),
						'3'	=>	array (
							'term_id'	=>	'3',
							'slug'	=>	'tree',
							'captcha_icon'	=>	'<svg width="20px" height="20px" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M377.33 375.429L293.906 288H328c21.017 0 31.872-25.207 17.448-40.479L262.79 160H296c20.878 0 31.851-24.969 17.587-40.331l-104-112.003c-9.485-10.214-25.676-10.229-35.174 0l-104 112.003C56.206 134.969 67.037 160 88 160h33.21l-82.659 87.521C24.121 262.801 34.993 288 56 288h34.094L6.665 375.429C-7.869 390.655 2.925 416 24.025 416H144c0 32.781-11.188 49.26-33.995 67.506C98.225 492.93 104.914 512 120 512h144c15.086 0 21.776-19.069 9.995-28.494-19.768-15.814-33.992-31.665-33.995-67.496V416h119.97c21.05 0 31.929-25.309 17.36-40.571z"></path></svg>'
						),
						'4'	=>	array (
							'term_id'	=>	'4',
							'slug'	=>	'truck',
							'captcha_icon'	=>	'<svg width="20px" height="20px" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path fill="currentColor" d="M624 352h-16V243.9c0-12.7-5.1-24.9-14.1-33.9L494 110.1c-9-9-21.2-14.1-33.9-14.1H416V48c0-26.5-21.5-48-48-48H48C21.5 0 0 21.5 0 48v320c0 26.5 21.5 48 48 48h16c0 53 43 96 96 96s96-43 96-96h128c0 53 43 96 96 96s96-43 96-96h48c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zM160 464c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm320 0c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm80-208H416V144h44.1l99.9 99.9V256z"></path></svg>'),
						'5'	=>	array (
							'term_id'	=>	'5',
							'slug'	=>	'key',
							'captcha_icon'	=>	'<svg width="20px" height="20px" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M512 176.001C512 273.203 433.202 352 336 352c-11.22 0-22.19-1.062-32.827-3.069l-24.012 27.014A23.999 23.999 0 0 1 261.223 384H224v40c0 13.255-10.745 24-24 24h-40v40c0 13.255-10.745 24-24 24H24c-13.255 0-24-10.745-24-24v-78.059c0-6.365 2.529-12.47 7.029-16.971l161.802-161.802C163.108 213.814 160 195.271 160 176 160 78.798 238.797.001 335.999 0 433.488-.001 512 78.511 512 176.001zM336 128c0 26.51 21.49 48 48 48s48-21.49 48-48-21.49-48-48-48-48 21.49-48 48z"></path></svg>'
						),
						'6'	=>	array (
							'term_id'	=>	'6',
							'slug'	=>	'car',
							'captcha_icon'	=>	'<svg width="20px" height="20px" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M499.991 168h-54.815l-7.854-20.944c-9.192-24.513-25.425-45.351-46.942-60.263S343.651 64 317.472 64H194.528c-26.18 0-51.391 7.882-72.908 22.793-21.518 14.912-37.75 35.75-46.942 60.263L66.824 168H12.009c-8.191 0-13.974 8.024-11.384 15.795l8 24A12 12 0 0 0 20.009 216h28.815l-.052.14C29.222 227.093 16 247.997 16 272v48c0 16.225 6.049 31.029 16 42.309V424c0 13.255 10.745 24 24 24h48c13.255 0 24-10.745 24-24v-40h256v40c0 13.255 10.745 24 24 24h48c13.255 0 24-10.745 24-24v-61.691c9.951-11.281 16-26.085 16-42.309v-48c0-24.003-13.222-44.907-32.772-55.86l-.052-.14h28.815a12 12 0 0 0 11.384-8.205l8-24c2.59-7.771-3.193-15.795-11.384-15.795zm-365.388 1.528C143.918 144.689 168 128 194.528 128h122.944c26.528 0 50.61 16.689 59.925 41.528L391.824 208H120.176l14.427-38.472zM88 328c-17.673 0-32-14.327-32-32 0-17.673 14.327-32 32-32s48 30.327 48 48-30.327 16-48 16zm336 0c-17.673 0-48 1.673-48-16 0-17.673 30.327-48 48-48s32 14.327 32 32c0 17.673-14.327 32-32 32z"></path></svg>'
						),
						'7'	=>	array (
							'term_id'	=>	'7',
							'slug'	=>	'heart',
							'captcha_icon'	=>	'<svg width="20px" height="20px" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M414.9 24C361.8 24 312 65.7 288 89.3 264 65.7 214.2 24 161.1 24 70.3 24 16 76.9 16 165.5c0 72.6 66.8 133.3 69.2 135.4l187 180.8c8.8 8.5 22.8 8.5 31.6 0l186.7-180.2c2.7-2.7 69.5-63.5 69.5-136C560 76.9 505.7 24 414.9 24z"></path></svg>'
						),
						'8'	=>	array (
							'term_id'	=>	'8',
							'slug'	=>	'house', 
							'captcha_icon'	=>	'<svg width="20px" height="20px" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M488 312.7V456c0 13.3-10.7 24-24 24H348c-6.6 0-12-5.4-12-12V356c0-6.6-5.4-12-12-12h-72c-6.6 0-12 5.4-12 12v112c0 6.6-5.4 12-12 12H112c-13.3 0-24-10.7-24-24V312.7c0-3.6 1.6-7 4.4-9.3l188-154.8c4.4-3.6 10.8-3.6 15.3 0l188 154.8c2.7 2.3 4.3 5.7 4.3 9.3zm83.6-60.9L488 182.9V44.4c0-6.6-5.4-12-12-12h-56c-6.6 0-12 5.4-12 12V117l-89.5-73.7c-17.7-14.6-43.3-14.6-61 0L4.4 251.8c-5.1 4.2-5.8 11.8-1.6 16.9l25.5 31c4.2 5.1 11.8 5.8 16.9 1.6l235.2-193.7c4.4-3.6 10.8-3.6 15.3 0l235.2 193.7c5.1 4.2 12.7 3.5 16.9-1.6l25.5-31c4.2-5.2 3.4-12.7-1.7-16.9z"></path></svg>'
						),
						'9'	=>	array (
							'term_id'	=>	'9',
							'slug'	=>	'flag',
							'captcha_icon'	=>	'<svg width="20px" height="20px" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M349.565 98.783C295.978 98.783 251.721 64 184.348 64c-24.955 0-47.309 4.384-68.045 12.013a55.947 55.947 0 0 0 3.586-23.562C118.117 24.015 94.806 1.206 66.338.048 34.345-1.254 8 24.296 8 56c0 19.026 9.497 35.825 24 45.945V488c0 13.255 10.745 24 24 24h16c13.255 0 24-10.745 24-24v-94.4c28.311-12.064 63.582-22.122 114.435-22.122 53.588 0 97.844 34.783 165.217 34.783 48.169 0 86.667-16.294 122.505-40.858C506.84 359.452 512 349.571 512 339.045v-243.1c0-23.393-24.269-38.87-45.485-29.016-34.338 15.948-76.454 31.854-116.95 31.854z"></path></svg>'
						),
						'10'	=>	array (
							'term_id'	=>	'10',
							'slug'	=>	'plane',
							'captcha_icon'	=>	'<svg width="20px" height="20px" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M472 200H360.211L256.013 5.711A12 12 0 0 0 245.793 0h-57.787c-7.85 0-13.586 7.413-11.616 15.011L209.624 200H99.766l-34.904-58.174A12 12 0 0 0 54.572 136H12.004c-7.572 0-13.252 6.928-11.767 14.353l21.129 105.648L.237 361.646c-1.485 7.426 4.195 14.354 11.768 14.353l42.568-.002c4.215 0 8.121-2.212 10.289-5.826L99.766 312h109.858L176.39 496.989c-1.97 7.599 3.766 15.011 11.616 15.011h57.787a12 12 0 0 0 10.22-5.711L360.212 312H472c57.438 0 104-25.072 104-56s-46.562-56-104-56z"></path></svg>'
						),
						'11'	=>	array (
							'term_id'	=>	'11',
							'slug'	=>	'paint',
							'captcha_icon'	=>	'<svg width="20px" height="20px" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M416 128V32c0-17.67-14.33-32-32-32H32C14.33 0 0 14.33 0 32v96c0 17.67 14.33 32 32 32h352c17.67 0 32-14.33  32-32zm32-64v128c0 17.67-14.33 32-32 32H256c-35.35 0-64 28.65-64 64v32c-17.67 0-32 14.33-32 32v128c0 17.67 14.33 32 32 32h64c17.67 0 32-14.33 32-32V352c0-17.67-14.33-32-32-32v-32h160c53.02 0 96-42.98 96-96v-64c0-35.35-28.65-64-64-64z"></path></svg>'
						),
						'12'	=>	array (
							'term_id'	=>	'12',
							'slug'	=>	'batteries',
							'captcha_icon'	=>	'<svg width="20px" height="20px" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M480 128h-32V80c0-8.84-7.16-16-16-16h-96c-8.84 0-16 7.16-16 16v48H192V80c0-8.84-7.16-16-16-16H80c-8.84 0-16 7.16-16 16v48H32c-17.67 0-32 14.33-32 32v256c0 17.67 14.33 32 32 32h448c17.67 0 32-14.33 32-32V160c0-17.67-14.33-32-32-32zM192 264c0 4.42-3.58 8-8 8H72c-4.42 0-8-3.58-8-8v-16c0-4.42 3.58-8 8-8h112c4.42 0 8 3.58 8 8v16zm256 0c0 4.42-3.58 8-8 8h-40v40c0 4.42-3.58 8-8 8h-16c-4.42 0-8-3.58-8-8v-40h-40c-4.42 0-8-3.58-8-8v-16c0-4.42 3.58-8 8-8h40v-40c0-4.42 3.58-8 8-8h16c4.42 0 8 3.58 8 8v40h40c4.42 0 8 3.58 8 8v16z"></path></svg>'
						),
						'13'	=>	array (
							'term_id'	=>	'13',
							'slug'	=>	'rocket-fuel',
							'captcha_icon'	=>	'<svg width="20px" height="20px" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M505.12019,19.09375c-1.18945-5.53125-6.65819-11-12.207-12.1875C460.716, 0,435.507,0,410.40747,0,307.17523,0,245.26909,55.20312,199.05238,128H94.83772c-16.34763.01562-35.55658,
					11.875-42.88664,26.48438L2.51562,253.29688A28.4,28.4,0,0,0,0,264a24.00867,24.00867,0,0,0,24.00582,
					24H127.81618l-22.47457,22.46875c-11.36521,11.36133-12.99607,32.25781,0,45.25L156.24582,406.625c11.15623,
					11.1875,32.15619,13.15625,45.27726,0l22.47457-22.46875V488a24.00867,24.00867,0,0,0,24.00581,24,28.55934,
					28.55934,0,0,0,10.707-2.51562l98.72834-49.39063c14.62888-7.29687,26.50776-26.5,26.50776-42.85937V312.79688c72.59753-46.3125,
					128.03493-108.40626,128.03493-211.09376C512.07526,76.5,512.07526,51.29688,505.12019,19.09375ZM384.04033,168A40,40,0,1,1,424.05,128,
					40.02322,40.02322,0,0,1,384.04033,168Z"></path></svg>'
						),
						'14'	=>	array (
							'term_id'	=>	'14',
							'slug'	=>	'soft-drinks',
							'captcha_icon'	=>	'<svg width="20px" height="20px" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 288 512"><path fill="currentColor" d="M216 464h-40V346.81c68.47-15.89 118.05-79.91 111.4-154.16l-15.95-178.1C270.71 6.31 263.9 0 255.74 0H32.26c-8.15 0-14.97 6.31-15.7 14.55L.6 192.66C-6.05 266.91 43.53 330.93 112 346.82V464H72c-22.09 0-40 17.91-40 40 0 4.42 3.58 8 8 8h208c4.42 0 8-3.58 8-8 0-22.09-17.91-40-40-40z"></path></svg>'
						),
				),
				'c4wp_term'	=>	array (
						'1'	=>	array (
							'id'	=>	'1',
							'name'	=>	'Cup', 
							'slug'	=>	'cup'
						),
						'2'	=>	array (
							'id'	=>	'2',
							'name'	=>	'Star', 
							'slug'	=>	'star'
						),
						'3'	=>	array (
							'id'	=>	'3',
							'name'	=>	'Tree', 
							'slug'	=>	'tree'
						),
						'4'	=>	array (
							'id'	=>	'4',
							'name'	=>	'Truck', 
							'slug'	=>	'truck'
						),
						'5'	=>	array (
							'id'	=>	'5',
							'name'	=>	'Key', 
							'slug'	=>	'key'
						),
						'6'	=>	array (
							'id'	=>	'6',
							'name'	=>	'Car', 
							'slug'	=>	'car'
						),
						'7'	=>	array (
							'id'	=>	'7',
							'name'	=>	'Heart', 
							'slug'	=>	'heart'
						),
						'8'	=>	array (
							'id'	=>	'8',
							'name'	=>	'House', 
							'slug'	=>	'house'
						),
						'9'	=>	array (
							'id'	=>	'9',
							'name'	=>	'Flag', 
							'slug'	=>	'flag'
						),
						'10'	=>	array (
							'id'	=>	'10',
							'name'	=>	'Plane', 
							'slug'	=>	'plane'
						),
						'11'	=>	array (
							'id'	=>	'11',
							'name'	=>	'Paint', 
							'slug'	=>	'paint'
						),
						'12'	=>	array (
							'id'	=>	'12',
							'name'	=>	'Batteries', 
							'slug'	=>	'batteries'
						),
						'13'	=>	array (
							'id'	=>	'13',
							'name'	=>	'Rocket Fuel', 
							'slug'	=>	'rocket-fuel'
						),
						'14'	=>	array (
							'id'	=>	'14',
							'name'	=>	'Soft Drinks', 
							'slug'	=>	'soft-drinks'
						),
				),
			);	
				
		return $c4wp_captchas;		
	}
	
	/**
	 * Return random no of icons for the plugin.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_array_random($array, $number = null) {
	
		$requested = ($number === null) ? 1 : $number;
		$count = count($array);
	
		if ($requested > $count) {
			throw new \RangeException(
				"You requested {$requested} items, but there are only {$count} items available."
			);
		}
	
		if ($number === null) {
			return $array[array_rand($array)];
		}
	
		if ((int) $number === 0) {
			return [];
		}
	
		$keys = (array) array_rand($array, $number);
	
		$results = [];
		foreach ($keys as $key) {
			$results[] = $array[$key];
		}
	
		return $results;
	}
	
	/**
	 * Return term id exists in array for the plugin.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_tid_in_array($array, $term) {
        
		$matches = array();
        
		foreach($array as $a) {
            
			if($a['term_id'] == $term)
            $matches[]=$a;
        }
		
        return $matches;
    }
	
	/**
	 * Return term id not exists in array for the plugin.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_tid_notin_array($array, $term) {
        
		$matches = array();
        
		foreach($array as $a) {
            
			if($a['term_id'] != $term)
            $matches[]=$a;
        }
		
        return $matches;
    }
}