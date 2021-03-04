<?php
namespace LocoAutoTranslateAddon\Helpers;
/**
 * @package Loco Automatic Translate Addon
 */
class Helpers{
    public static function proInstalled(){
        if (defined('ATLT_PRO_FILE')) {
            return true;
        }else{
            return false;
        }
    }
    // verifiy user limit
    public static function atltVerification(){
        $allowed='';
        $info=array();
        $info['type']=Helpers::userType();
        $today_timing = get_transient('atlt_translation_day');
        $monthly_timing = get_transient('atlt_translation_day');
        $all_translated_chars = intval(get_option('atlt_month_translated_chars',0));
        $info['total']= $all_translated_chars;
        if($today_timing===false){
            if($all_translated_chars>10000000){
                $allowed='no';
            }else{
                $allowed='yes';
            }
        }else{
            $today_chars=(int)get_option('atlt_perday_translated_chars');
            $info['today']= $today_chars;
            if($today_chars>300000){
               $allowed='no';
            }else if($all_translated_chars>10000000){
                $allowed='no';
            }else{
               $allowed='yes';
            }
        }
       $info['allowed']= $allowed;
        return $info;
    }
    // return user type
    public static function userType(){
          return $type='pro';
    }

    // validate key
    public static function validKey($key){
    if (preg_match("/^([A-Z0-9]{8})-([A-Z0-9]{8})-([A-Z0-9]{8})-([A-Z0-9]{8})$/",$key)){
         return true;
        }else{
            return false;
        }
    }
    //grab key
    public static function getLicenseKey(){
        $licenseKey=get_option("LocoAutomaticTranslateAddonPro_lic_Key","");
        if($licenseKey==''||$licenseKey==false){
            return false;
        }else{
            return $licenseKey;
          }
    }



}
