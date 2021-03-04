<?php
/*
Plugin Name:Automatic Translate Addon For Loco Translate
Description:Auto language translator add-on for Loco Translate official plugin version 2.4+ to translate plugins and themes translation files into any language via fully automatic machine translations via Yandex Translate Widget.
Version:2.0
License:GPL2
Text Domain:loco-translate-addon
Domain Path:languages
Author:Cool Plugins
Author URI:https://coolplugins.net/
*/
namespace LocoAutoTranslateAddon;
use LocoAutoTranslateAddon\Helpers\Helpers;
 /**
 * @package Loco Automatic Translate Addon
 * @version 2.0
 */
if (!defined('ABSPATH')) {
    die('WordPress Environment Not Found!');
}

define('ATLT_FILE', __FILE__);
define('ATLT_URL', plugin_dir_url(ATLT_FILE));
define('ATLT_PATH', plugin_dir_path(ATLT_FILE));
define('ATLT_VERSION', '2.0');
update_option("LocoAutomaticTranslateAddonPro_lic_Key","B25D2E81-E1F90D83-AEA9DA8B-8F7686E0");

class LocoAutoTranslate
{
    public function __construct()
    {
        register_activation_hook( ATLT_FILE, array( $this, 'atlt_activate' ) );
        register_deactivation_hook( ATLT_FILE, array( $this, 'atlt_deactivate' ) );
        if(is_admin()){

            // Only loged in user can perform this AJAX request
            add_action('plugins_loaded', array($this, 'atlt_check_required_loco_plugin'));
            /*** Template Setting Page Link inside Plugins List */
            add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this,'atlt_settings_page_link'));
            add_action( 'admin_enqueue_scripts', array( $this,'atlt_enqueue_scripts') );
            add_action('init',array($this,'checkStatus'));
            add_action('init',array($this,'updateSettings'));
            //add notice to use latest loco translate addon
            add_action('init',array($this,'useLatestVersionNotice'));
            add_action('plugins_loaded', array($this,'include_files'));

         /*
            since version 2.0
            Yandex translate widget integration
        */
        // add no translate attribute in html tag
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'file-edit')
        {
            add_action('admin_footer', array($this,'load_ytranslate_scripts'),100);
        }

         /* since version 2.0 */
         add_filter('script_loader_tag',array($this,'custom_remove_scripts'), 11, 2);
    }
    }
    /* Remove loco translate editor js file
      since version 2.0
      Created By:-Jyoti
    */
    function custom_remove_scripts($link, $handle) {
        if( !file_exists( WP_PLUGIN_DIR . '/loco-translate/loco.php' ) ){
            return $link;
        }
        $site_url = get_site_url();
        $loco_info = get_plugin_data( WP_PLUGIN_DIR . '/loco-translate/loco.php' , false, false );
       $loco_ver =  $loco_info['Version'];
       $urls = array(
              $site_url.'/wp-content/plugins/loco-translate/pub/js/min/editor.js?ver='.$loco_ver.''
         );
         foreach ($urls as $url) {
             if (strstr($link, $url)) {$link = '';}
         }
         return $link;
     }

 /*
   |----------------------------------------------------------------------
   | Yandex Translate Widget Integartions
   |----------------------------------------------------------------------
   */
    // load google translate widget scripts
    function load_ytranslate_scripts() {
     echo"<script>document.getElementsByTagName('html')[0].setAttribute('translate', 'no');</script>";
    }

    // update settings
    public function updateSettings(){
        if(get_option( 'atlt-ratingDiv')){
            update_option('atlt-already-rated',get_option( 'atlt-ratingDiv'));
            delete_option( 'atlt-ratingDiv');
        }
    }
    /**
     * create 'settings' link in plugins page
     */
    public function atlt_settings_page_link($links){
        $links[] = '<a style="font-weight:bold" href="'. esc_url( get_admin_url(null, 'admin.php?page=loco-atlt-register') ) .'">License</a>';
        return $links;
    }

   /*
   |----------------------------------------------------------------------
   | required php files
   |----------------------------------------------------------------------
   */
   public function include_files()
   {

      if ( is_admin() ) {
            include_once ATLT_PATH .'includes/Helpers/Helpers.php';
            include_once ATLT_PATH . "includes/ReviewNotice/class.review-notice.php";
            new ALTLReviewNotice\ALTLReviewNotice();
            include_once ATLT_PATH . 'includes/Feedback/class.feedback-form.php';
            new FeedbackForm\FeedbackForm();
            include_once ATLT_PATH . 'includes/Register/LocoAutomaticTranslateAddonPro.php';
        }

   }

   /*
   |----------------------------------------------------------------------
   | check User Status
   |----------------------------------------------------------------------
   */
   public function checkStatus(){

    $key=Helpers::getLicenseKey();
    if(Helpers::validKey( $key) && Helpers::proInstalled()==false){
      add_action('admin_notices', array($this, 'atlt_pro_install_notice'));
    }
   }

    /*
   |----------------------------------------------------------------------
   | check User Status
   |----------------------------------------------------------------------
   */
  public function useLatestVersionNotice(){
    if(function_exists('loco_plugin_version')){
         $locoV=loco_plugin_version();
         if(version_compare($locoV,'2.4.0', '<'))
            {
                add_action('admin_notices', array($this, 'atlt_use_latest_admin_notice'));
            }
      }
   }

    /*
   |----------------------------------------------------------------------
   | Notice to use latest version of Loco Translate plugin
   |----------------------------------------------------------------------
   */
  public function atlt_use_latest_admin_notice()
  {
     if (current_user_can('activate_plugins')) {
        $url = 'plugin-install.php?tab=plugin-information&plugin=loco-translate&TB_iframe=true';
        $title = "Loco Translate";
        $plugin_info = get_plugin_data(__FILE__, true, true);
        echo '<div class="error"><p>' .
        sprintf(__('In order to use <strong>%s</strong> (version <strong>%s</strong>), Please update <a href="%s" class="thickbox" title="%s">%s</a> official plugin to a latest version (2.4.0 or upper)',
        'loco-translate-addon'),
         $plugin_info['Name'], $plugin_info['Version'], esc_url($url),
          esc_attr($title), esc_attr($title)) . '.</p></div>';

         }
  }
   /*
   |----------------------------------------------------------------------
   | check if required "Loco Translate" plugin is active
   | also register the plugin text domain
   |----------------------------------------------------------------------
   */
   public function atlt_check_required_loco_plugin()
   {
      if (!function_exists('loco_plugin_self')) {
         add_action('admin_notices', array($this, 'atlt_plugin_required_admin_notice'));
      }
      load_plugin_textdomain('loco-translate-addon', false, basename(dirname(__FILE__)) . '/languages/');
   }

    /*
   |----------------------------------------------------------------------
   | Install Loco Automatic Translate Addon Pro notice
   |----------------------------------------------------------------------
   */
  public function atlt_pro_install_notice()
  {
     if (current_user_can('activate_plugins')) {
        $key=Helpers::getLicenseKey();
        $url =esc_url( add_query_arg( 'license-key',$key , 'https://locoaddon.com/data/download-plugin.php' ) );
        $title = "Loco Automatic Translate Addon Pro";

        if( class_exists( 'LocoAutoTranslateAddonPro' ) ){
            // no further execution required
            return;
        }

        if( false == file_exists( WP_PLUGIN_DIR . '/loco-automatic-translate-addon-pro') ){
            echo '<div class="error loco-pro-missing" style="border:2px solid;border-color:#dc3232;"><p>' .
            sprintf('You are using <strong>%s</strong> license. Please also install and activate <strong>%s</strong> plugin to enjoy all premium featues and automatic premium updates.</p>
            <p><a href="%s" target="_blank" title="%s" class="button button-primary"><strong>Download %s plugin</strong></a> and install it, you can also download it from <a href="https://locoaddon.com/my-account/downloads/" target="_blank">https://locoaddon.com/my-account/downloads/</a>',
            esc_attr($title),esc_attr($title),esc_url($url),esc_attr($title),esc_attr($title)) . '.</p></div>';
        }else{
            echo '<div class="error loco-pro-missing" style="border:2px solid;border-color:#dc3232;"><p>' .
            sprintf('You are using <strong>%s</strong> license. Please also activate <strong>%s</strong> plugin to enjoy all premium featues and automatic premium updates.</p>',
            esc_attr($title),esc_attr($title)) . '</p></div>';
        }

     }
  }

  /*
   |----------------------------------------------------------------------
   | Notice to 'Admin' if "Loco Translate" is not active
   |----------------------------------------------------------------------
   */
  public function atlt_plugin_required_admin_notice()
  {
     if (current_user_can('activate_plugins')) {
        $url = 'plugin-install.php?tab=plugin-information&plugin=loco-translate&TB_iframe=true';
        $title = "Loco Translate";
        $plugin_info = get_plugin_data(__FILE__, true, true);
        echo '<div class="error"><p>' .
        sprintf(__('In order to use <strong>%s</strong> plugin, please install and activate the version 2.3.3 of <a href="%s" class="thickbox" title="%s">%s</a>',
        'loco-translate-addon'),
         $plugin_info['Name'], esc_url($url),
          esc_attr($title), esc_attr($title)) . '.</p></div>';

        deactivate_plugins(__FILE__);
     }
  }

  /*
   |------------------------------------------------------------------------
   |  Enqueue required JS file
   |------------------------------------------------------------------------
   */
   function atlt_enqueue_scripts(){

    wp_deregister_script('loco-js-editor');
    // copy object
    wp_add_inline_script( 'loco-translate-js-admin', 'var loco = window.locoScope;
              var target={};
              var returnedTarget = Object.assign(target, window.locoConf);
              window.conf=returnedTarget;' );

    // load yandex widget
    wp_register_script( 'atlt-yandex-widget', ATLT_URL.'assets/js/widget.js?widgetId=ytWidget&pageLang=en&widgetTheme=light&autoMode=false',array('jquery'),ATLT_VERSION, true);

    if(Helpers::userType()=="free"){
            // register styles
            wp_register_style('loco-addon-custom-css', ATLT_URL.'assets/css/custom.min.css',null,
            ATLT_VERSION,'all');
              //  wp_register_script( 'loco-js-editor', ATLT_URL.'assets/js/loco-js-editor.js', array('loco-js-min-admin'),ATLT_VERSION, true);
              wp_register_script( 'loco-addon-custom', ATLT_URL.'assets/js/custom.min.js', array('loco-translate-js-admin'),ATLT_VERSION, true);
              wp_register_script( 'custom-loco-js-editor', ATLT_URL.'assets/js/loco-js-editor.min.js', array('loco-translate-js-editor'),ATLT_VERSION, true);
            }else{
            // if PRO version is installed then load assets
            if(Helpers::proInstalled() && version_compare(ATLT_PRO_VERSION,'1.1', '>=')){
                 wp_register_style('loco-addon-custom-css', ATLT_PRO_URL.'assets/css/custom.min.css',null,
            ATLT_VERSION,'all');
                  wp_register_script( 'loco-addon-custom', ATLT_PRO_URL.'assets/js/custom.min.js', array('loco-translate-js-admin'),ATLT_PRO_VERSION, true);
                  wp_register_script( 'custom-loco-js-editor', ATLT_PRO_URL.'assets/js/loco-js-editor.min.js', array('loco-translate-js-editor'),ATLT_PRO_VERSION, true);
                  wp_register_script( 'sweet-alert', ATLT_PRO_URL.'assets/sweetalert/sweetalert.min.js', array('jquery'),ATLT_PRO_VERSION, true);
            }else{
                  //  wp_register_script( 'loco-js-editor', ATLT_URL.'assets/js/loco-js-editor.js', array('loco-js-min-admin'),ATLT_VERSION, true);
                  wp_register_script( 'loco-addon-custom', ATLT_URL.'assets/js/custom.min.js', array('loco-translate-js-admin'),ATLT_VERSION, true);
                  wp_register_script( 'custom-loco-js-editor', ATLT_URL.'assets/js/loco-js-editor.min.js', array('loco-translate-js-editor'),ATLT_VERSION, true);
            }
        }

        // load assets only on editor page
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'file-edit')
     {
        // load common assets
         wp_enqueue_script('loco-addon-custom');
         wp_enqueue_script('custom-loco-js-editor');
         wp_enqueue_script('atlt-yandex-widget');
         wp_enqueue_style('loco-addon-custom-css');

         if(Helpers::userType()=="pro"){
            wp_enqueue_script('sweet-alert');
         }

         $status=Helpers::atltVerification();

         $extraData['preloader_path']=ATLT_URL.'/assets/images/preloader.gif';
         $extraData['gt_preview']=ATLT_URL.'/assets/images/powered-by-google.png';
         $extraData['dpl_preview']=ATLT_URL.'/assets/images/powered-by-deepl.png';
         $extraData['yt_preview']=ATLT_URL.'/assets/images/powered-by-yandex.png';

         wp_localize_script('loco-addon-custom', 'extradata', $extraData);

    }
}


/*
|------------------------------------------------------
|    Plugin activation
|------------------------------------------------------
*/
   public function atlt_activate(){
       $plugin_info = get_plugin_data(__FILE__, true, true);
       update_option('atlt_version', $plugin_info['Version'] );
       update_option("atlt-installDate",date('Y-m-d h:i:s') );
       update_option("atlt-already-rated","no");
       update_option("atlt-type","free");
   }
   /*
   |-------------------------------------------------------
   |    Plugin deactivation
   |-------------------------------------------------------
   */
   public function atlt_deactivate(){
   }
}

$atlt=new LocoAutoTranslate();
