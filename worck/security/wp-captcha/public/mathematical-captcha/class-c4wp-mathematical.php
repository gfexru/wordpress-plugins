<?php 
/**
 * Class responsible for display of the Mathematical Capcta on the enabled form.
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma <devnathverma@gmail.com>
 */

class C4WP_Mathematical {
	
	// @type defaults variables
	public $c4wp_plugin_options;
	
	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $c4wp_plugin_options ) {
		
		$this->c4wp_plugin_options 	=  $c4wp_plugin_options;
		
		// Hook to the 'init' action, which is called mathematical captcha actions and filters.
		add_action( 'init', array( $this, 'c4wp_mathematical_actions_filters' ), 9 );
	}
	
	/**
	 * Apply required filters.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_mathematical_actions_filters( ) {
		
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
			
		$c4wp_mathematical_operations = array();
		$c4wp_mathematical_groups = array();
		$c4wp_asmd = array(
				'addition'		 => '&#43;',
				'subtraction'	 => '&minus;',
				'multiplication' => '&times;',
				'division'		 => '&#8260;',
		);
		
		if ( isset($this->c4wp_plugin_options['c4wp_options']['mathematical_captcha_setting']['addition'] ) && ( $this->c4wp_plugin_options['c4wp_options']['mathematical_captcha_setting']['addition'] == true ) ) {
			$c4wp_mathematical_operations[] = 'addition';
		}
		
		if ( isset($this->c4wp_plugin_options['c4wp_options']['mathematical_captcha_setting']['substruction'] ) && ( $this->c4wp_plugin_options['c4wp_options']['mathematical_captcha_setting']['substruction'] == true ) ) {
			$c4wp_mathematical_operations[] = 'subtraction';
		}
		
		if ( isset($this->c4wp_plugin_options['c4wp_options']['mathematical_captcha_setting']['multiplication'] ) && ( $this->c4wp_plugin_options['c4wp_options']['mathematical_captcha_setting']['multiplication'] == true ) ) {
			$c4wp_mathematical_operations[] = 'multiplication';
		}
		
		if ( isset($this->c4wp_plugin_options['c4wp_options']['mathematical_captcha_setting']['division'] ) && ( $this->c4wp_plugin_options['c4wp_options']['mathematical_captcha_setting']['division'] == true ) ) {
			$c4wp_mathematical_operations[] = 'division';
		}
		
		if ( isset($this->c4wp_plugin_options['c4wp_options']['mathematical_captcha_setting']['numbers'] ) && ( $this->c4wp_plugin_options['c4wp_options']['mathematical_captcha_setting']['numbers'] == true ) ) {
			$c4wp_mathematical_groups[] = 'numbers';
		}
		
		if ( isset($this->c4wp_plugin_options['c4wp_options']['mathematical_captcha_setting']['words'] ) && ( $this->c4wp_plugin_options['c4wp_options']['mathematical_captcha_setting']['words'] == true ) ) {
			$c4wp_mathematical_groups[] = 'words';
		}

		$c4wp_amt_oms = count( $c4wp_mathematical_groups );
		$c4wp_rand_ops = $c4wp_mathematical_operations[mt_rand( 0, count( $c4wp_mathematical_operations ) - 1 )];
		$c4wp_mathematical_numbers[3] = $c4wp_asmd[$c4wp_rand_ops];
		$c4wp_random_input = mt_rand( 0, 2 );
		
		switch ( $c4wp_rand_ops ) {
			
			case 'addition':
				if ( $c4wp_random_input === 0 ) {
					$c4wp_mathematical_numbers[0] = mt_rand( 1, 10 );
					$c4wp_mathematical_numbers[1] = mt_rand( 1, 89 );
				} elseif ( $c4wp_random_input === 1 ) {
					$c4wp_mathematical_numbers[0] = mt_rand( 1, 89 );
					$c4wp_mathematical_numbers[1] = mt_rand( 1, 10 );
				} elseif ( $c4wp_random_input === 2 ) {
					$c4wp_mathematical_numbers[0] = mt_rand( 1, 9 );
					$c4wp_mathematical_numbers[1] = mt_rand( 1, 10 - $c4wp_mathematical_numbers[0] );
				}

				$c4wp_mathematical_numbers[2] = $c4wp_mathematical_numbers[0] + $c4wp_mathematical_numbers[1];
				break;

			case 'subtraction':
				if ( $c4wp_random_input === 0 ) {
					$c4wp_mathematical_numbers[0] = mt_rand( 2, 10 );
					$c4wp_mathematical_numbers[1] = mt_rand( 1, $c4wp_mathematical_numbers[0] - 1 );
				} elseif ( $c4wp_random_input === 1 ) {
					$c4wp_mathematical_numbers[0] = mt_rand( 11, 99 );
					$c4wp_mathematical_numbers[1] = mt_rand( 1, 10 );
				} elseif ( $c4wp_random_input === 2 ) {
					$c4wp_mathematical_numbers[0] = mt_rand( 11, 99 );
					$c4wp_mathematical_numbers[1] = mt_rand( $c4wp_mathematical_numbers[0] - 10, $c4wp_mathematical_numbers[0] - 1 );
				}

				$c4wp_mathematical_numbers[2] = $c4wp_mathematical_numbers[0] - $c4wp_mathematical_numbers[1];
				break;

			case 'multiplication':
				if ( $c4wp_random_input === 0 ) {
					$c4wp_mathematical_numbers[0] = mt_rand( 1, 10 );
					$c4wp_mathematical_numbers[1] = mt_rand( 1, 9 );
				} elseif ( $c4wp_random_input === 1 ) {
					$c4wp_mathematical_numbers[0] = mt_rand( 1, 9 );
					$c4wp_mathematical_numbers[1] = mt_rand( 1, 10 );
				} elseif ( $c4wp_random_input === 2 ) {
					$c4wp_mathematical_numbers[0] = mt_rand( 1, 10 );
					$c4wp_mathematical_numbers[1] = ($c4wp_mathematical_numbers[0] > 5 ? 1 : ($c4wp_mathematical_numbers[0] === 4 && $c4wp_mathematical_numbers[0] === 5 ? mt_rand( 1, 2 ) : ($c4wp_mathematical_numbers[0] === 3 ? mt_rand( 1, 3 ) : ($c4wp_mathematical_numbers[0] === 2 ? mt_rand( 1, 5 ) : mt_rand( 1, 10 )))));
				}

				$c4wp_mathematical_numbers[2] = $c4wp_mathematical_numbers[0] * $c4wp_mathematical_numbers[1];
				break;

			case 'division':
				$divide = array( 1 => 99, 2 => 49, 3 => 33, 4 => 24, 5 => 19, 6 => 16, 7 => 14, 8 => 12, 9 => 11, 10 => 9 );

				if ( $c4wp_random_input === 0 ) {
					$divide = array( 2 => array( 1, 2 ), 3 => array( 1, 3 ), 4 => array( 1, 2, 4 ), 5 => array( 1, 5 ), 6 => array( 1, 2, 3, 6 ), 7 => array( 1, 7 ), 8 => array( 1, 2, 4, 8 ), 9 => array( 1, 3, 9 ), 10 => array( 1, 2, 5, 10 ) );
					$c4wp_mathematical_numbers[0] = mt_rand( 2, 10 );
					$c4wp_mathematical_numbers[1] = $divide[$c4wp_mathematical_numbers[0]][mt_rand( 0, count( $divide[$c4wp_mathematical_numbers[0]] ) - 1 )];
				} elseif ( $c4wp_random_input === 1 ) {
					$c4wp_mathematical_numbers[1] = mt_rand( 1, 10 );
					$c4wp_mathematical_numbers[0] = $c4wp_mathematical_numbers[1] * mt_rand( 1, $divide[$c4wp_mathematical_numbers[1]] );
				} elseif ( $c4wp_random_input === 2 ) {
					$c4wp_mathematical_numbers[2] = mt_rand( 1, 10 );
					$c4wp_mathematical_numbers[0] = $c4wp_mathematical_numbers[2] * mt_rand( 1, $divide[$c4wp_mathematical_numbers[2]] );
					$c4wp_mathematical_numbers[1] = (int) ($c4wp_mathematical_numbers[0] / $c4wp_mathematical_numbers[2]);
				}

				if ( ! isset( $c4wp_mathematical_numbers[2] ) )
					$c4wp_mathematical_numbers[2] = (int) ($c4wp_mathematical_numbers[0] / $c4wp_mathematical_numbers[1]);

				break;
		}

		if ( $c4wp_amt_oms === 1 && $c4wp_mathematical_groups[0] === 'words' ) {
			if ( $c4wp_random_input === 0 ) {
				$c4wp_mathematical_numbers[1] = $this->c4wp_convert_number_to_words( $c4wp_mathematical_numbers[1] );
				$c4wp_mathematical_numbers[2] = $this->c4wp_convert_number_to_words( $c4wp_mathematical_numbers[2] );
			} elseif ( $c4wp_random_input === 1 ) {
				$c4wp_mathematical_numbers[0] = $this->c4wp_convert_number_to_words( $c4wp_mathematical_numbers[0] );
				$c4wp_mathematical_numbers[2] = $this->c4wp_convert_number_to_words( $c4wp_mathematical_numbers[2] );
			} elseif ( $c4wp_random_input === 2 ) {
				$c4wp_mathematical_numbers[0] = $this->c4wp_convert_number_to_words( $c4wp_mathematical_numbers[0] );
				$c4wp_mathematical_numbers[1] = $this->c4wp_convert_number_to_words( $c4wp_mathematical_numbers[1] );
			}
			
		} elseif ( $c4wp_amt_oms === 2 ) { // numbers and words
		
			if ( $c4wp_random_input === 0 ) {
				if ( mt_rand( 1, 2 ) === 2 ) {
					$c4wp_mathematical_numbers[1] = $this->c4wp_convert_number_to_words( $c4wp_mathematical_numbers[1] );
					$c4wp_mathematical_numbers[2] = $this->c4wp_convert_number_to_words( $c4wp_mathematical_numbers[2] );
				} else
					$c4wp_mathematical_numbers[$tmp = mt_rand( 1, 2 )] = $this->c4wp_convert_number_to_words( $c4wp_mathematical_numbers[$tmp] );
			}
			elseif ( $c4wp_random_input === 1 ) {
				if ( mt_rand( 1, 2 ) === 2 ) {
					$c4wp_mathematical_numbers[0] = $this->c4wp_convert_number_to_words( $c4wp_mathematical_numbers[0] );
					$c4wp_mathematical_numbers[2] = $this->c4wp_convert_number_to_words( $c4wp_mathematical_numbers[2] );
				} else
					$c4wp_mathematical_numbers[$tmp = array_rand( array( 0 => 0, 2 => 2 ), 1 )] = $this->c4wp_convert_number_to_words( $c4wp_mathematical_numbers[$tmp] );
			}
			elseif ( $c4wp_random_input === 2 ) {
				if ( mt_rand( 1, 2 ) === 2 ) {
					$c4wp_mathematical_numbers[0] = $this->c4wp_convert_number_to_words( $c4wp_mathematical_numbers[0] );
					$c4wp_mathematical_numbers[1] = $this->c4wp_convert_number_to_words( $c4wp_mathematical_numbers[1] );
				} else
					$c4wp_mathematical_numbers[$tmp = mt_rand( 0, 1 )] = $this->c4wp_convert_number_to_words( $c4wp_mathematical_numbers[$tmp] );
			}
		}
			
		$c4wp_user_input = '<input id="c4wp_user_input_captcha" name="c4wp_user_input_captcha" class="c4wp_user_input_captcha" type="text" style="width: 45px;" autocomplete="off" />';
		
		$return  = '';
		$return .= '<p class="c4wp-display-captcha-form">';
		
		if( isset($this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_title']) && ! empty( $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_title'] ) ) {
		
		$return .= '<label for="'.$this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_title'].'">'.$this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_title'].'</label>';
		}
		
		if ( $c4wp_random_input === 0 ) {
			
			$return .= $c4wp_user_input . '&nbsp;&nbsp;' . $c4wp_mathematical_numbers[3] . '&nbsp;&nbsp;' . $c4wp_mathematical_numbers[1] . '&nbsp;&nbsp;=&nbsp;&nbsp;' . $c4wp_mathematical_numbers[2];
			$return .= '<input type="hidden" name="c4wp_random_input_captcha" value="' . $this->c4wp_captcha_encode( $c4wp_mathematical_numbers[0] ) . '" />';
		
		} else if ( $c4wp_random_input === 1 ) {
		
			$return .= $c4wp_mathematical_numbers[0] . '&nbsp;&nbsp;' . $c4wp_mathematical_numbers[3] . '&nbsp;&nbsp;' . $c4wp_user_input . '&nbsp;&nbsp;=&nbsp;&nbsp;' . $c4wp_mathematical_numbers[2];
			$return .= '<input type="hidden" name="c4wp_random_input_captcha" value="' . $this->c4wp_captcha_encode( $c4wp_mathematical_numbers[1] ) . '" />';
		
		} else if ( $c4wp_random_input === 2 ) {
		
			$return .= $c4wp_mathematical_numbers[0] . '&nbsp;&nbsp;' . $c4wp_mathematical_numbers[3] . '&nbsp;&nbsp;' . $c4wp_mathematical_numbers[1] . '&nbsp;&nbsp;=&nbsp;&nbsp;' . $c4wp_user_input;
			$return .= '<input type="hidden" name="c4wp_random_input_captcha" value="' . $this->c4wp_captcha_encode( $c4wp_mathematical_numbers[2] ) . '" />';
		}
		
		$return .= '</p>';
		
		return $return;
	}
	
	/**
	 * This function convert the numbers into alphabates.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_convert_number_to_words( $c4wp_numbers ) {
		
		$c4wp_words = array(
				1	 => __( 'one', 'wp-captcha' ),
				2	 => __( 'two', 'wp-captcha' ),
				3	 => __( 'three', 'wp-captcha' ),
				4	 => __( 'four', 'wp-captcha' ),
				5	 => __( 'five', 'wp-captcha' ),
				6	 => __( 'six', 'wp-captcha' ),
				7	 => __( 'seven', 'wp-captcha' ),
				8	 => __( 'eight', 'wp-captcha' ),
				9	 => __( 'nine', 'wp-captcha' ),
				10	 => __( 'ten', 'wp-captcha' ),
				11	 => __( 'eleven', 'wp-captcha' ),
				12	 => __( 'twelve', 'wp-captcha' ),
				13	 => __( 'thirteen', 'wp-captcha' ),
				14	 => __( 'fourteen', 'wp-captcha' ),
				15	 => __( 'fifteen', 'wp-captcha' ),
				16	 => __( 'sixteen', 'wp-captcha' ),
				17	 => __( 'seventeen', 'wp-captcha' ),
				18	 => __( 'eighteen', 'wp-captcha' ),
				19	 => __( 'nineteen', 'wp-captcha' ),
				20	 => __( 'twenty', 'wp-captcha' ),
				30	 => __( 'thirty', 'wp-captcha' ),
				40	 => __( 'forty', 'wp-captcha' ),
				50	 => __( 'fifty', 'wp-captcha' ),
				60	 => __( 'sixty', 'wp-captcha' ),
				70	 => __( 'seventy', 'wp-captcha' ),
				80	 => __( 'eighty', 'wp-captcha' ),
				90	 => __( 'ninety', 'wp-captcha' )
		);

		if ( isset( $c4wp_words[$c4wp_numbers] ) ) {
			
			return $c4wp_words[$c4wp_numbers];
		
		} else {
			
			$c4wp_reverse = false;

			switch ( get_bloginfo( 'language' ) ) {
				case 'de-DE':
					$c4wp_spacer = 'und';
					$c4wp_reverse = true;
					break;
				case 'nl-NL':
					$c4wp_spacer = 'en';
					$c4wp_reverse = true;
					break;
				default:
					$c4wp_spacer = ' ';
			}

			$first = (int) (substr( $c4wp_numbers, 0, 1 ) * 10);
			$second = (int) substr( $c4wp_numbers, -1 );

			return ($c4wp_reverse === false ? $c4wp_words[$first] . $c4wp_spacer . $c4wp_words[$second] : $c4wp_words[$second] . $c4wp_spacer . $c4wp_words[$first]);
		
		}
	}

	/**
	 * This Function for encoding number.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 * @param string $string_original
	 * @param string $key
	 */
	public function c4wp_captcha_encode( $String ) {

		$String = substr( pack( "H*", sha1( $String ) ), 0, 1 ) . $String;
		$StrLen = strlen( $String );
		$Gamma = '';

		while ( strlen( $Gamma ) < $StrLen ) {
			$Seq = pack( "H*", sha1( $Gamma ) );
			$Gamma .= substr( $Seq, 0, 8 );
		}

		return base64_encode ( $String ^ $Gamma );
	}
	
	/**
	 * This Function for decoding number.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 * @param string $string_original
	 * @param string $key
	 */
	public function c4wp_captcha_decode( $String ) {

		$StrLen = strlen( $String );
		$Gamma = '';

		while ( strlen( $Gamma ) < $StrLen ) {
			$Seq = pack( "H*", sha1( $Gamma ) );
			$Gamma .= substr( $Seq, 0, 8 );
		}

		$String = base64_decode( $String );
		$String = $String ^ $Gamma;
		$DecodedString = substr($String, 1);
		$Error = ord(substr($String, 0, 1) ^ substr(pack("H*", sha1($DecodedString)), 0, 1));
		
		return $Error ? false : $DecodedString;
	}
}