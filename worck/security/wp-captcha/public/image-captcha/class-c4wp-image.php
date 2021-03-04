<?php 
/**
 * Class responsible for display of the Image Capcta on the enabled form.
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma <devnathverma@gmail.com>
 */

class C4WP_Image {
	
	// @type defaults variables
	public $c4wp_plugin_options;
	
	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $c4wp_plugin_options ) {
		
		if ( '' === session_id() ) {
			
			@session_start();
		}
		
		$this->c4wp_plugin_options 	=  $c4wp_plugin_options;
		
		// Hook to the 'init' action, which is called Image Captcha actions and filters.
		add_action( 'init', array( $this, 'c4wp_iamge_actions_filters' ), 9 );
		add_action( 'wp_ajax_c4wp_refresh_captcha', array( $this, 'c4wp_refresh_captcha' ) );
		add_action( 'wp_ajax_nopriv_c4wp_refresh_captcha', array( $this, 'c4wp_refresh_captcha' ) );
	}
	
	/**
	 * Refresh captcha for the plugin.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_refresh_captcha( ) {
		
		if ( isset( $_REQUEST['c4wp_random_input_captcha'] ) ) {
			if ( file_exists( C4WP_PLUGIN_PUBLIC . 'image-captcha/c4wp-image-captcha-settings.php' ) ) {
				require_once C4WP_PLUGIN_PUBLIC . 'image-captcha/c4wp-image-captcha-settings.php';
			}
		}
		
		exit;
	}
	
	/**
	 * Apply required filters.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_iamge_actions_filters( ) {
		
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
		
		echo $this->c4wp_generate_captcha( );
	}
	
	/**
	 * generate captcha to Provide a public-facing view for the plugin.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_generate_captcha( ) {
		
		$create_image_url   = admin_url( 'admin-ajax.php' ) . '?action=c4wp_refresh_captcha&c4wp_random_input_captcha=';
		$return  = '';
		$return .= '<p class="c4wp-display-captcha-form">';
		
		if( isset($this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_title']) && ! empty( $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_title'] ) ) {
		
			$return .= '<label for="'.$this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_title'].'">'.$this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_title'].'</label>';
		}
		
		$return .= '<img src="'.$create_image_url . rand( 111, 99999 ).'" class="c4wp_image"/><br/>';
		$return .= '<a href="javascript:void(0);" class="refresh_captcha" data-imgsrc="'.$create_image_url . rand( 111, 99999 ) . '"><img src="'.C4WP_PLUGIN_IMAGES.'c4wp-refresh-captcha.png" class="c4wp-refresh-captcha"/></a>';
		$return .= '<br /><br /><strong>Enter Captcha Here : </strong>';
		$return .= '<input id="c4wp_user_input_captcha" name="c4wp_user_input_captcha" class="c4wp_user_input_captcha" type="text" autocomplete="off">';
		$return .= '</p>';
						
		return $return;
	}
}