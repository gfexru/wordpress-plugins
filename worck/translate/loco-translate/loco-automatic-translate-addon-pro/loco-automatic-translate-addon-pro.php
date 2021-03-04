<?php
/*
Plugin Name:Loco Automatic Translate Addon PRO
Description:Activate premium features for Loco Automatic Translate Addon - Use Translate APIs (Google, Microsoft) and get premium support.
Version:1.2
License:GPLv3
Text Domain:loco-translate-addon
Domain Path:languages
Author:Cool Plugins
Author URI:https://coolplugins.net/
 */
namespace LocoAutoTranslateAddonPro;
use LocoAutoTranslateAddonPro\Helpers\ProHelpers;

 /**
 * @package Loco Automatic Translate Addon PRO
 * @version 1.2
 */
if (!defined('ABSPATH')) {
    die('WordPress Environment Not Found!');
}
define('ATLT_PRO_FILE', __FILE__);
define('ATLT_PRO_PATH', plugin_dir_path(__FILE__));
define('ATLT_PRO_URL', plugin_dir_url(ATLT_PRO_FILE));
define('ATLT_PRO_VERSION', '1.2');

class LocoAutoTranslateAddonPro
{
    public function __construct()
    {
        register_activation_hook( ATLT_PRO_FILE, array( $this, 'atlt_activate' ) );
        register_deactivation_hook( ATLT_PRO_FILE, array( $this, 'atlt_deactivate' ) );
        if(is_admin()){
      //  add_action('plugins_loaded', array($this, 'atlt_check_required_loco_plugin'));
        add_action('wp_ajax_pro_autotranslate_handler',array($this,'atlt_pro_autotranslate_handler'), 100);
        add_action('wp_ajax_pro_test_api_provider',array($this,'atlt_pro_test_api_provider'));
        add_action('plugins_loaded', array($this,'include_files'));
        add_action('init',array($this,'onInit'));
        add_filter('script_loader_tag',array($this,'custom_remove_scripts'), 11, 2);

        /*
            since version 1.1
            google translate widget integration
        */
            if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'file-edit')
            {
                add_filter('admin_body_class',array($this,'add_custom_class'));
                add_action('admin_footer', array($this,'load_gtranslate_scripts'),100);
            }
        add_action( 'init', array($this,'set_gtranslate_cookie'));

        /*
            since version 1.2
            Deepl assets
        */
        add_action( 'admin_enqueue_scripts',
        array( $this,'atlt_enqueue_deepl_scripts') );
    }
    }
    /* Remove loco translate editor js file
      since version 2.0
      Created By:-Jyoti
    */
    function custom_remove_scripts($link, $handle) {
        $site_url = get_site_url();
       $loco_ver =  loco_plugin_version();
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
   | required php files
   |----------------------------------------------------------------------
   */
   public function include_files()
   {
      if( is_admin()){
            include_once ATLT_PRO_PATH . 'includes/MicrosoftTranslator.php';
            include_once ATLT_PRO_PATH . 'includes/Helpers/ProHelpers.php';
            include_once ATLT_PRO_PATH . 'includes/LocoAutomaticTranslateAddonProUpdaterBase.php';
        }
   }

   public function onInit(){
   if (defined('ATLT_FILE')==false) {
    add_action('admin_notices', array($this,'atlt_free_install_notice'));
    }
    if(defined('ATLT_VERSION') && version_compare(ATLT_VERSION,'1.8', '<=') )
    {
        add_action('admin_notices', array($this,'atlt_free_install_notice'));
    }
    $key=ProHelpers::getLicenseKey();
    if(defined('ATLT_FILE')==true && ProHelpers::validKey($key)==false){
      add_action('admin_notices', array($this, 'atlt_add_license_notice'));
    }

//    new LocoAutomaticTranslateAddonProUpdaterBase\LocoAutomaticTranslateAddonProUpdaterBase(__FILE__);

   }
    /*
   |----------------------------------------------------------------------
   | Install Loco Automatic Translate Addon Free notice
   |----------------------------------------------------------------------
   */
  public function atlt_free_install_notice()
  {
    if (current_user_can('activate_plugins')) {
        $url = 'plugin-install.php?tab=plugin-information&plugin=automatic-translator-addon-for-loco-translate&TB_iframe=true';
        $title = "Loco Automatic Translate Addon";
        $plugin_info = get_plugin_data(__FILE__, true, true);
        echo '<div class="error loco-pro-missing" style="border:2px solid;border-color:#dc3232;"><p>' .
        sprintf(__('In order to activate and use <strong>%s</strong> plugin, please first install and activate the latest version of <a href="%s" class="thickbox" title="%s">%s</a> free plugin', 'loco-translate-addon'),
        $plugin_info['Name'],
         esc_url($url), esc_attr($title),
          esc_attr($title)) . '.</p></div>';
        deactivate_plugins(__FILE__);
     }
  }



  public function atlt_add_license_notice()
  {
    $settings_page_link=esc_url( get_admin_url(null, 'admin.php?page=loco-atlt-register') );
    $notice=__('<strong>Loco Automatic Translate Addon Pro</strong> - License key is missing! Please add your License key in the settings panel to activate all premium features.', 'loco-translate-addon');
    echo '<div class="error loco-pro-missing" style="border:2px solid;border-color:#dc3232;"><p>'.$notice.'</p>
        <p><a class="button button-primary" href="'.$settings_page_link.'">'.__('Add License Key').'</a> (You can find license key inside order purchase email or visit <a href="https://locotranslate.com/my-account/orders/" target="_blank">https://locotranslate.com/my-account/orders/</a>)</p></div>';

  }

   /*
   |----------------------------------------------------------------------
   | Ajax callback handler
   |----------------------------------------------------------------------
   */
  public function atlt_pro_autotranslate_handler()
  {
      // verify request
    if ( ! wp_verify_nonce($_REQUEST['nonce'], 'atlt_nonce' ) ) {
        echo  $this->errorResponse('Request Time Out. Please refresh your browser window.');
        die();
        } else {
           // get request vars
           if (empty($_REQUEST['data'])) {
            echo  $this->errorResponse('No String Found');
            die();
           }
       if(isset($_REQUEST['data'])){
           $responseArr=array();
           $response=array();

           $requestData = $_REQUEST['data'];
           $targetLang=$_REQUEST['targetLan'];
           $sourceLang=$_REQUEST['sourceLan'];
           if($targetLang=="nb" || $targetLang=="nn"){
               $targetLang="no";
           }

           $request_chars  = $_REQUEST['requestChars'];
           $totalChars  = $_REQUEST['totalCharacters'];
           $requestType=$_REQUEST['strType'];
           $apiType=$_REQUEST['apiType'];
           $stringArr= json_decode(stripslashes($requestData),true);

        if($apiType=="google"){
                $g_api_key= ProHelpers::getAPIkey("google");
                if(empty($g_api_key)||$g_api_key==""){
                    echo  $this->errorResponse('You have not Entered Google Translate API Key');
                    die();
                }
                $apiKey = $g_api_key;
                if(ProHelpers::googleSLangList($targetLang)==false){
                    echo  $this->errorResponse('Google Translator Does not support this language');
                    die();
                }
                if(is_array( $stringArr)&& !empty($stringArr))
                {
                $response=$this->translate_array($stringArr,$targetLang,$sourceLang, $apiKey);
                if(is_array($response)&& isset($response['error']))
                 {
                    $responseArr['code']=$response['error']->code;
                    $responseArr['error']=$response['error']->message;
                }else{
                    $improvedRs=$this->fixSpacingIssue($response);
                    $responseArr['translatedString']=$improvedRs;
                    $responseArr['code']=200;
                     // grab translation count data
                    $responseArr['stats']= $this->saveStringsCount($request_chars,$totalChars,$apiType);
                }

               }
            }else if($apiType=="microsoft"){
              $m_api_key= ProHelpers::getAPIkey("microsoft");
                if(empty($m_api_key)||$m_api_key==""){
                    echo  $this->errorResponse('You have not Entered Microsoft Subscription API Key');
                    die();
                }
                $apiKey = $m_api_key;
                if(ProHelpers::microsoftSLangList($targetLang)==false){
                    echo  $this->errorResponse('Microsoft Translator Does not support this language');
                    die();
                }

                if(is_array( $stringArr)&& !empty($stringArr))
                {
                    $args['key']= $apiKey;
                    $args['from']=$sourceLang;
                    $args['to']=$targetLang;
                    $args['text']=$stringArr;
                    $mt= new MicrosoftTranslator\MicrosoftTranslator();
                    $response=$mt->translate($args);
                     if( $response==null){
                        $responseArr['code']=$response['error']['code'];
                        $responseArr['error']=$response['error']['message'];
                       }
                    else if(is_array($response) && isset($response['error']))
                     {
                         $responseArr['code']=$response['error']['code'];
                         $responseArr['error']=$response['error']['message'];
                     }else{
                         $responseArr['code']=200;
                         $improvedRs=$this->fixSpacingIssue($response);
                         $responseArr['translatedString']=$improvedRs;
                         $responseArr['stats']= $this->saveStringsCount($request_chars,$totalChars,$apiType);
                     }
                 }
            }

            die(json_encode($responseArr, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
       }

    }

 }

   /*
   |----------------------------------------------------------------------
   | check User Status
   |----------------------------------------------------------------------
   */
    // fix extra spacing issue
     public function fixSpacingIssue($stringArr){
        $updatedArr=[];
        if(empty($stringArr)){
            return false;
        }
        $arr = array("% s" => "%s", "% d" => "%d","d %"=>"d%","s %"=>"s%");
        if(is_array($stringArr)){
         foreach($stringArr as $key=>$value){
            $updatedArr[]=strtr($value,$arr);
         }
         return $updatedArr;
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
         echo '<div class="error"><p>' . sprintf(__('In order to use <strong>%s</strong> plugin, please install and activate the latest version of <a href="%s" class="thickbox" title="%s">%s</a>', 'loco-translate-addon'), $plugin_info['Name'], esc_url($url), esc_attr($title), esc_attr($title)) . '.</p></div>';
         deactivate_plugins(__FILE__);
      }
   }

   /*
   |----------------------------------------------------------------------
   | Verify API's working or not
   |----------------------------------------------------------------------
   */
   public function atlt_pro_test_api_provider(){

    if ( ! wp_verify_nonce($_REQUEST['nonce'], 'atlt_nonce' ) ) {
        die(json_encode(array('code' =>500, 'message' => 'Request Time Out. Please refresh your browser window.')));
    } else {
       $text = $_REQUEST['text'];
       $targetLang=$_REQUEST['target'];
       $sourceLang=$_REQUEST['source'];
       $apikey=$_REQUEST['apikey'];
       $apiType=$_REQUEST['apiprovider'];
       $strArr[]=$text;
        if($apiType=="google"){
            $response=$this->translate_array($strArr,$targetLang,$sourceLang, $apikey);
            if(is_array($response)&& isset($response['error']))
            {
                $responseArr['code']=$response['error']->code;
                $responseArr['error']=$response['error']->message;
            }else{
                $responseArr['code']=200;
                $responseArr['translatedString']=$response;
            }
        }else if($apiType=="microsoft"){
            $args['key']= $apikey;
            $args['from']=$sourceLang;
            $args['to']=$targetLang;
            $args['text']=$strArr;
            $mt= new MicrosoftTranslator\MicrosoftTranslator();
            $response=$mt->translate($args);
          //  $responseArr['response']=$response;
            if(is_array($response)&& isset($response['error']))
            {
                $responseArr['code']=$response['error']['code'];
                $responseArr['error']=$response['error']['message'];
            }else{
                $responseArr['code']=200;
                $responseArr['translatedString']=$response;
            }
        }
        die(json_encode($responseArr, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
   }
}



 /*
   |----------------------------------------------------------------------
   | error response creator
   |----------------------------------------------------------------------
   */
  public function errorResponse($message){
    $error=[];
        if($message){
            $error['error']['code']=800;
            $error['error']['message']=$message;
        }
        return json_encode($error);
    }

   /*
   |----------------------------------------------------------------------
   | Save string usage
   |----------------------------------------------------------------------
   */
    public function saveStringsCount($request_chars,$totalChars,$apiType)
    {
        if($apiType=="google"){
            $today_translated = ProHelpers::gTodayTranslated( $request_chars);
            $monthly_translated = ProHelpers::gMonthlyTranslated( $request_chars);
        }else if($apiType=="microsoft"){
            $today_translated = ProHelpers::mTodayTranslated( $request_chars);
            $monthly_translated = ProHelpers::mMonthlyTranslated( $request_chars);
        }else{
        $today_translated = ProHelpers::ytodayTranslated( $request_chars);
        $monthly_translated = ProHelpers::ymonthlyTranslated( $request_chars);
        }
        /** Calculate the total time save on translation */
        $session_time_saved = ProHelpers::atlt_time_saved_on_translation( $totalChars);
        $total_time_saved = ProHelpers::atlt_time_saved_on_translation($totalChars);
        // create response array
        $stats=array(
                        'todays_translation'=>$today_translated,
                        'total_translation'=>$monthly_translated,
                        'time_saved'=> $session_time_saved,
                        'total_time_saved'=>$total_time_saved,
                        'totalChars'=>$totalChars
                    );
        return $stats;
    }
  /*
   |------------------------------------------------------
   |   Send Request to API
   |------------------------------------------------------
  */
   /**
     * @param array $strings_array          Array of string to translate
     * @return array|WP_Error               Response
     */
    public function send_request( $source_language, $target_language, $strings_array,$apiKey ){

        /* build our translation request */
        $translation_request = 'key='.$apiKey;

        $translation_request .= '&source='.$source_language;
        $translation_request .= '&target='.$target_language;
        foreach( $strings_array as $new_string ){
            $translation_request .= '&q='.rawurlencode($new_string);
        }
        /* Due to url length restrictions we need so send a POST request faked as a GET request and send the strings in the body of the request and not in the URL */
        $response = wp_remote_post( "https://www.googleapis.com/language/translate/v2", array(
                'headers' => array(
                    'X-HTTP-Method-Override' => 'GET', //this fakes a GET request
                //    'Referer'                => $referer
                ),
                'body' => $translation_request,
            )
        );
        return $response;
    }

   /*
   |------------------------------------------------------
   |   Translate Array
   |------------------------------------------------------
   */

    /**
     * Returns an array with the API provided translations of the $new_strings array.
     */
    public function translate_array($new_strings, $target_language_code, $source_language_code,$api_key ){

        if( empty( $new_strings ) )
            return array();

        $source_language =$source_language_code;
        $target_language = $target_language_code;

        $translated_strings = array();

        /* split our strings that need translation in chunks of maximum 128 strings because Google Translate has a limit of 128 strings */
        $new_strings_chunks = array_chunk( $new_strings, 128, true );
        /* if there are more than 128 strings we make multiple requests */
        foreach( $new_strings_chunks as $new_strings_chunk ){
            $response = $this->send_request( $source_language, $target_language, $new_strings_chunk,$api_key );
            /* analyze the response */
            if (is_wp_error($response)) {
                return $response->get_error_message(); // Bail early
            }
            if ( is_array( $response ) && ! is_wp_error( $response ) ) {

                /* decode it */
                $translation_response = json_decode( $response['body'] );
                if( !empty( $translation_response->error ) ){
                    return array("error"=> $translation_response->error); // return an empty array if we encountered an error. This means we don't store any translation in the DB
                }
                else{
                    /* if we have strings build the translation strings array and make sure we keep the original keys from $new_string */
                    $translations = $translation_response->data->translations;
                    $i = 0;
                    foreach( $new_strings_chunk as $key => $old_string ){
                        if( !empty( $translations[$i]->translatedText ) ) {
                            $translated_strings[$key] = $translations[$i]->translatedText;
                        }
                        $i++;
                    }
                }
                return $translated_strings;
            }

        }

        // will have the same indexes as $new_string or it will be an empty array if something went wrong

    }

    /*
   |------------------------------------------------------------------------
   |  Enqueue Deepl JS file
   |------------------------------------------------------------------------
   */

   function atlt_enqueue_deepl_scripts(){
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'file-edit')
        {
            wp_enqueue_script('doc_index',"https://unpkg.com/docx@5.0.2/build/index.js",array('jquery'));
            wp_enqueue_script('filesaver',"https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.8/FileSaver.js",array('jquery'));
            wp_enqueue_script('docxtemplater',"https://cdnjs.cloudflare.com/ajax/libs/docxtemplater/3.1.9/docxtemplater.js",array('jquery'));
            wp_enqueue_script('jszip',"https://cdnjs.cloudflare.com/ajax/libs/jszip/2.6.1/jszip.js",array('jquery'));
        }
   }
    /*
   |----------------------------------------------------------------------
   | Google Translate Widget Integartions
   |----------------------------------------------------------------------
   */
    // set default option in google translate widget using cookie
    function set_gtranslate_cookie() {
        //setting your cookies there
        if (!isset($_COOKIE['googtrans'])) {
            setcookie('googtrans', '/en/Select Language',2147483647);
        }
    }
    // add no translate class in admin body to disable whole page translation
    function add_custom_class( $classes ) {
        return "$classes notranslate";
    }
    // load google translate widget scripts
    function load_gtranslate_scripts() {

     echo "<script>
        function googleTranslateElementInit() {
            var defaultcode = window.locoConf.locale.lang?window.locoConf.locale.lang:null;
            switch(defaultcode){
                case 'bel':
                defaultlang='be';
                break;
                case 'he':
                    defaultlang='iw';
                    break;
                case'snd':
                    defaultlang='sd';
                break;
                case 'jv':
                    defaultlang='jw';
                    break;
                default:
                defaultlang=defaultcode;
            break;
            return defaultlang;
            }
           new google.translate.TranslateElement(
                {
                pageLanguage: 'en',
                includedLanguages: defaultlang,
                defaultLanguage: defaultlang,
                multilanguagePage: true
                },
                'google_translate_element'
            );
        }
        </script>
        <script src='https://translate.google.com/translate_a/element.js'></script>
        ";
    }

   /*
   |------------------------------------------------------
   |    Plugin activation
   |------------------------------------------------------
    */
   public function atlt_activate(){
       $plugin_info = get_plugin_data(__FILE__, true, true);
       update_option('atlt-pro-version', $plugin_info['Version'] );
       update_option("atlt-type","PRO");
       update_option("LocoAutomaticTranslateAddonPro_lic_Key","B25D2E81-E1F90D83-AEA9DA8B-8F7686E0");
   }
   /*
   |-------------------------------------------------------
   |    Plugin deactivation
   |-------------------------------------------------------
   */
   public function atlt_deactivate(){

   }

}

new LocoAutoTranslateAddonPro();
