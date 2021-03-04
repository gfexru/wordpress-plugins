<?php
/**
 * @package   WP Captcha
 * @author    Devnath verma <devnathverma@gmail.com>
 * © Copyright 2020 Devnath verma (devnathverma@gmail.com)
 *
 * @wordpress-plugin
 * Plugin Name:  WP Captcha
 * Description:  WP Captcha prove that the visitor is a human being and not a spam robot.
 * Version:      2.0.0
 * Author:       Devnath verma
 * Author Email: devnathverma@gmail.com
 * License:      GPLv2 or later
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  wp-captcha
 * Domain Path:  /languages/
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
 
/*----------------------------------------------------------------------------*
 * If this file is called directly, abort.
 *-----------------------------------------------------------------------------*/

if( ! defined( 'ABSPATH' ) ) {
	
	die( 'You are not allowed to call this page directly.' );
}

/**
 * WP Captcha Plugin Define Constants.
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma <devnathverma@gmail.com>
 */
define( 'C4WP_PLUGIN_NAME', 'WP Captcha' );
define( 'C4WP_PHP_VERSION', '5.3' );
define( 'C4WP_WP_VERSION', '4.8' );
define( 'C4WP_PLUGIN_VERSION', '1.0.0' );	
define( 'C4WP_PLUGIN_TEXTDOMAIN', 'wp-captcha' );	
define( 'C4WP_PLUGIN_FOLDER', basename( dirname(__FILE__) ) );
define( 'C4WP_PLUGIN_PATH', plugin_dir_path(__FILE__) );
define( 'C4WP_PLUGIN_REL_PATH', dirname( plugin_basename( __FILE__ ) ) . '/' );
define( 'C4WP_PLUGIN_ADMIN', C4WP_PLUGIN_PATH.'admin'.'/' );
define( 'C4WP_PLUGIN_PUBLIC', C4WP_PLUGIN_PATH.'public'.'/' );	
define( 'C4WP_PLUGIN_INCLUDES', C4WP_PLUGIN_PATH.'includes'.'/' );
define( 'C4WP_PLUGIN_MODULES', C4WP_PLUGIN_INCLUDES.'modules'.'/' );	
define( 'C4WP_PLUGIN_LANGUAGES', C4WP_PLUGIN_PATH.'languages'.'/' );	
define( 'C4WP_PLUGIN_URL', plugin_dir_url(C4WP_PLUGIN_FOLDER).C4WP_PLUGIN_FOLDER.'/' );
define( 'C4WP_PLUGIN_CSS', C4WP_PLUGIN_URL.'/assets/css'.'/' );
define( 'C4WP_PLUGIN_JS', C4WP_PLUGIN_URL.'/assets/js'.'/' );
define( 'C4WP_PLUGIN_IMAGES', C4WP_PLUGIN_URL.'/assets/images'.'/' );
define( 'C4WP_PLUGIN_FONTS', C4WP_PLUGIN_URL.'/assets/fonts'.'/' );

/**
 * The base-class of the plugin.
 * Defines the plugin, loads the text domain, holds the PHP function handling the WP Captcha validation
 * enqueues the front-end specific stylesheet and JavaScript.
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma <devnathverma@gmail.com>
 */
class WP_CAPTCHA {
	
	/**
	 * Instance of this class.
	 * @version  1.0.0
	 * @access   public
	 * @var      string $_c4wp_instance The plugin name to be used in the WP Captcha Plugin.
	 */
	public static $_c4wp_instance;
	
	/**
	 * The options data to be used in the WP Captcha Plugin.
	 * @version  1.0.0
	 * @access   public
	 * @var      string $c4wp_plugin_options The options data to be used in the WP Captcha Plugin.
	 */
	public $c4wp_plugin_options;
	
	/**
	 * The settings class object to be used in the WP Captcha Plugin.
	 * @version  1.0.0
	 * @access   public
	 * @var      string $c4wp_return_setting_object The settings class object to be used in the WP Captcha Plugin.
	 */
	public $c4wp_return_setting_object;
	
	/**
	 * The class object to be used in the WP Captcha Plugin.
	 * @since    1.0.0
	 * @access   public
	 * @var      string $c4wp_object The class object to be used in the WP Captcha Plugin.
	 */
	public $c4wp_object;

	
	/**
	 * Return an instance of this class.
 	 * @package  WP Captcha
 	 * @version  1.0.0
 	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public static function c4wp_instance() {
		
		// If the single instance hasn't been set, set it now.
		if ( self::$_c4wp_instance === null )
		self::$_c4wp_instance = new self();

		return self::$_c4wp_instance;
	}

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct() {

		// The code that runs during plugin activation.
		register_activation_hook( __FILE__, array( $this, 'c4wp_plugin_activation' ) );
		
 		// The code that runs during plugin deactivation.
		register_deactivation_hook( __FILE__, array( $this, 'c4wp_plugin_deactivation' ) );
		
		// return plugin setting page links on install plugins
		add_action( 'plugin_action_links', array( $this, 'c4wp_plugin_setting_links' ), 10, 2 );
		
		// add CSS and JS to make sure the Captcha fits nicely
		add_action( 'wp_enqueue_scripts', array( $this, 'c4wp_public_print_scripts_styles' ) );
		
		// add CSS and JS to make sure the Captcha fits nicely
		add_action( 'login_enqueue_scripts', array( $this, 'c4wp_public_print_scripts_styles' ) );
		
		// Load the plugin text domain for translation.
		$this->c4wp_internationalization_i18n();
		
		// Load the Required files in admin section.
		$this->c4wp_admin_hooks();
		
		// Load the Required files in public section.
		$this->c4wp_public_hooks();
	}
		
	/**
	 * Register all of the hooks related to the admin area functionality.
 	 * @package  WP Captcha
 	 * @version  1.0.0
 	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_admin_hooks() { 
		
		require_once C4WP_PLUGIN_ADMIN . 'class-c4wp-admin-settings.php';
		$this->c4wp_return_setting_object = new C4WP_Admin_Settings( $this->c4wp_get_plugin_options() );
	}
	
	/**
	 * Register all of the hooks related to the public-facing functionality.
 	 * @package  WP Captcha
 	 * @version  1.0.0
 	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_public_hooks() { 
		
		if ( isset( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] ) ) {
			switch ( $this->c4wp_plugin_options['c4wp_options']['captcha_enable_for'] ) {
				case 'mathematical_captcha':
					require_once C4WP_PLUGIN_PUBLIC . 'mathematical-captcha/class-c4wp-mathematical.php';
					$this->c4wp_object = new C4WP_Mathematical( $this->c4wp_get_plugin_options() );
					break;
				case 'image_captcha':
					require_once C4WP_PLUGIN_PUBLIC . 'image-captcha/class-c4wp-image.php';
					$this->c4wp_object = new C4WP_Image( $this->c4wp_get_plugin_options() );
					break;
				case 'google_recaptcha':
					require_once C4WP_PLUGIN_PUBLIC . 'google-recaptcha/class-c4wp-recaptcha.php';
					$this->c4wp_object = new C4WP_Recaptcha( $this->c4wp_get_plugin_options() );
					break;
				case 'icon_captcha':
					require_once C4WP_PLUGIN_PUBLIC . 'icon-captcha/class-c4wp-icon.php';
					$this->c4wp_object = new C4WP_Icon( $this->c4wp_get_plugin_options() );
					break;
				default:
        			require_once C4WP_PLUGIN_PUBLIC . 'mathematical-captcha/class-c4wp-mathematical.php';
					$this->c4wp_object = new C4WP_Mathematical( $this->c4wp_get_plugin_options() );
        			break;		
			}
		}		
	}

	/**
	 * The code that runs during plugin activatation.
	 * This action is documented in includes/class-c4wp-activator.php
 	 * @package  WP Captcha
 	 * @version  1.0.0
 	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_plugin_activation() {
		
		if ( ! current_user_can( 'activate_plugins' ) ) return;

		require_once C4WP_PLUGIN_INCLUDES . 'class-c4wp-activator.php';
		C4WP_Plugin_Activator::c4wp_activate();
	}

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-c4wp-deactivator.php
 	 * @package  WP Captcha
 	 * @version  1.0.0
 	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_plugin_deactivation() {
		
		if ( ! current_user_can( 'activate_plugins' ) ) return;
		
		// Get the " deactivation_delete " option id, check if it exists and delete the option that has that id
		if( isset( $this->c4wp_plugin_options['c4wp_options']['other_settings']['deactivation_delete'] ) && $this->c4wp_plugin_options['c4wp_options']['other_settings']['deactivation_delete'] ) {
			require_once C4WP_PLUGIN_INCLUDES . 'class-c4wp-deactivator.php';
			C4WP_Plugin_Dectivator::c4wp_dectivate();
		}
	}

	/**
	 * Load the plugin text domain for translation.
	 * This action is documented in includes/class-c4wp-textdomain.php
 	 * @package  WP Captcha
 	 * @version  1.0.0
 	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_internationalization_i18n() {
		
		require_once C4WP_PLUGIN_INCLUDES . 'class-c4wp-textdomain.php';
		C4WP_Plugin_Textdomain::c4wp_textdomain();
	}

	/**
	 * Add links to settings page.
	 * @param array $c4wp_links
	 * @param string $c4wp_file
	 * @return array
 	 * @package  WP Captcha
 	 * @version  1.0.0
 	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_plugin_setting_links( $c4wp_links, $c4wp_file ) {
		
		if ( ! is_admin() || ! current_user_can( 'manage_options' ) )
		return $c4wp_links;

		static $plugin;

		$plugin = plugin_basename( __FILE__ );

		if ( $c4wp_file == $plugin ) {
			
			$settings_link = sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php' ) . '?page=c4wp-settings', __( 'Settings', 'wp-captcha' ) );
			array_unshift( $c4wp_links, $settings_link );
		}

		return $c4wp_links;
	}
			
	/**
	 * Register the scripts and stylesheets for the public-facing side of the site.
	 * This action is documented in assets/css/c4wp-public.js, assets/css/c4wp-public.css
 	 * @package  WP Captcha
 	 * @version  1.0.0
 	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_public_print_scripts_styles() {
		
		wp_enqueue_script( 'c4wp-public', C4WP_PLUGIN_JS . 'c4wp-public.js', array('jquery') );
		wp_enqueue_style( 'c4wp-public', C4WP_PLUGIN_CSS . 'c4wp-public.css' );
	}
	
	/**
	 * Retrieve the options values number of the plugin.
 	 * @package  WP Captcha
 	 * @version  1.0.0
 	 * @author   Devnath verma <devnathverma@gmail.com>
	 * @return    string    The options values of the plugin.
	 */
	public function c4wp_get_plugin_options() {
		
		if( get_option('c4wp_default_settings') )
		$this->c4wp_plugin_options = get_option('c4wp_default_settings');
		
		return $this->c4wp_plugin_options;
	}
	
	/*
	 * Function Add the CAPTCHA to the enabled form and check hide captcha for Logged in users
	 * @package  WP Captcha
	 * @version  1.0.0
 	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_needed( $enabled_form, $user_loggged_in ) {
		
		if( isset( $this->c4wp_plugin_options['c4wp_options']['enable_form_settings'][$enabled_form] ) )
		return $this->c4wp_plugin_options['c4wp_options']['enable_form_settings'][$enabled_form] && ( ! $user_loggged_in || empty( $this->c4wp_plugin_options['c4wp_options']['other_settings']['hide_for_logged_users'] ) );
	}
}

/**
 * Checks if the system requirements are met
 * @return bool True if system requirements are met, false if not
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma <devnathverma@gmail.com>
 */
function c4wp_plugin_requirements() {
	
	global $wp_version;

	if ( version_compare( PHP_VERSION, C4WP_PHP_VERSION, '<' ) ) {
		return false;
	}

	if ( version_compare( $wp_version, C4WP_WP_VERSION, '<' ) ) {
		return false;
	}

	return true;
}

/**
 * Prints an error that the system requirements weren't met.
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma <devnathverma@gmail.com>
 */
function c4wp_plugin_requirements_error() {
	
	global $wp_version;
	
	require_once C4WP_PLUGIN_INCLUDES . 'class-c4wp-requirements-error.php';
	C4WP_Requirements_Error::requirements_error();
}

/**
 * Check requirements and load main class
 * The main program needs to be in a separate file that only gets loaded if the plugin requirements are met. 
 * Otherwise older PHP installations could crash when trying to parse it.
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma <devnathverma@gmail.com>
 */
if ( c4wp_plugin_requirements() ) {
	
	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
 	 * @package  WP Captcha
 	 * @version  1.0.0
 	 * @author   Devnath verma <devnathverma@gmail.com>
	 */	
	function WP_CAPTCHA() {
		
		static $c4wp_instance;

		//first call to c4wp_instance() initializes the plugin
		if ( $c4wp_instance === null || ! ($c4wp_instance instanceof WP_CAPTCHA) )
		$c4wp_instance = WP_CAPTCHA::c4wp_instance();
		
		return $c4wp_instance;
	}
	
	WP_CAPTCHA();
	
} else {
	
	// The action responsible for adding the admin notices when the plugin is first activated.
	add_action( 'admin_notices', 'c4wp_plugin_requirements_error' );
}