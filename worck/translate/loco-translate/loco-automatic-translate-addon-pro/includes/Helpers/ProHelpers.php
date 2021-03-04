<?php
namespace LocoAutoTranslateAddonPro\Helpers;
/**
 * @package Loco Automatic Translate Addon
 */

class ProHelpers{

    /*
   |----------------------------------------------------------------------------|
   |         Delete or update per day translated charachter stats               |
   | @param $value int current translated character to update in database       |
   |----------------------------------------------------------------------------|
    */
    // count today translated strings
    public static function ytodayTranslated($value = 0)
    {
        $now_translated = $value;
         if (false===($today= get_transient('atlt_translation_day'))) {
            delete_option('atlt_perday_translated_chars');
            update_option('atlt_perday_translated_chars', $now_translated);
            set_transient('atlt_translation_day', 'true', DAY_IN_SECONDS);
            $today_total_translated=$now_translated;
        } else {
            $already_translated = intval(get_option('atlt_perday_translated_chars'));
            $today_total_translated = $already_translated+$now_translated;
            update_option('atlt_perday_translated_chars', $today_total_translated);
        }
        return $today_total_translated;
    }
    // count monthly translated strings
    public static function ymonthlyTranslated($value = 0){
            $now_translated=$value;
            if (false===($month= get_transient('atlt_translation_month'))) {
                delete_option('atlt_month_translated_chars');
                update_option('atlt_month_translated_chars',$now_translated);
                set_transient('atlt_translation_month', 'true', MONTH_IN_SECONDS);
                $month_total_translated=$now_translated;
            } else {
                $already_translated = intval(get_option('atlt_month_translated_chars'));
                $month_total_translated= $already_translated+$now_translated;
                update_option('atlt_month_translated_chars', $month_total_translated);
            }
            return $month_total_translated;
    }
    /* google chars stats*/
    public static function gTodayTranslated($value = 0)
    {
        $now_translated = $value;
         if (false===($today= get_transient('g_translation_day'))) {
            delete_option('g_perday_translated_chars');
            update_option('g_perday_translated_chars', $now_translated);
            set_transient('g_translation_day', 'true', DAY_IN_SECONDS);
            $today_total_translated=$now_translated;
        } else {
            $already_translated = intval(get_option('g_perday_translated_chars'));
            $today_total_translated = $already_translated+$now_translated;
            update_option('g_perday_translated_chars', $today_total_translated);
        }
        return $today_total_translated;
    }
    // count monthly translated strings
    public static function gMonthlyTranslated($value = 0){
            $now_translated=$value;
            if (false===($month= get_transient('g_translation_month'))) {
                delete_option('g_month_translated_chars');
                update_option('g_month_translated_chars',$now_translated);
                set_transient('g_translation_month', 'true', MONTH_IN_SECONDS);
                $month_total_translated=$now_translated;
            } else {
                $already_translated = intval(get_option('g_month_translated_chars'));
                $month_total_translated= $already_translated+$now_translated;
                update_option('g_month_translated_chars', $month_total_translated);
            }
            return $month_total_translated;
    }


 /* google chars stats*/
 public static function mTodayTranslated($value = 0)
 {
     $now_translated = $value;
      if (false===($today= get_transient('m_translation_day'))) {
         delete_option('m_perday_translated_chars');
         update_option('m_perday_translated_chars', $now_translated);
         set_transient('m_translation_day', 'true', DAY_IN_SECONDS);
         $today_total_translated=$now_translated;
     } else {
         $already_translated = intval(get_option('m_perday_translated_chars'));
         $today_total_translated = $already_translated+$now_translated;
         update_option('m_perday_translated_chars', $today_total_translated);
     }
     return $today_total_translated;
 }
 // count monthly translated strings
 public static function mMonthlyTranslated($value = 0){
         $now_translated=$value;
         if (false===($month= get_transient('m_translation_month'))) {
             delete_option('m_month_translated_chars');
             update_option('m_month_translated_chars',$now_translated);
             set_transient('m_translation_month', 'true', MONTH_IN_SECONDS);
             $month_total_translated=$now_translated;
         } else {
             $already_translated = intval(get_option('m_month_translated_chars'));
             $month_total_translated= $already_translated+$now_translated;
             update_option('m_month_translated_chars', $month_total_translated);
         }
         return $month_total_translated;
 }

    // check timing
    public static function checkPeriod(){
        $today=get_transient('atlt_translation_day');
        $month=get_transient('atlt_translation_month');
        if(false===$today){
            delete_option('atlt_perday_translated_chars');
        }
        if(false===$month){
            delete_option('atlt_month_translated_chars');
        }
    }
    // verifiy user limit
    public static function atltVerification(){
        $allowed='';
        $info=array();
        $info['type']=ProHelpers::userType();
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

    public static function getAvailableChars($source)
    {
        $availableChars=0;
        if($source=="yandex"){
            $a_per_day=1000000;
            $today_total_translated = get_option('atlt_perday_translated_chars', 0);
            $availableChars=$a_per_day-$today_total_translated;
        }else if($source=="microsoft"){
            $a_per_mon=2000000;
            $total_translation = get_option('m_month_translated_chars', 0);
            $availableChars=$a_per_mon-$total_translation;
        }else{
            $a_per_mon=500000;
            $total_translation = get_option('g_month_translated_chars', 0);
            $availableChars=$a_per_mon-$total_translation;
        }
        return $availableChars;
    }

    public static function getAPIkey($source){
        $key='';
        $keys_arr= get_option('atlt_register');
        if($source=="google"){
            if(isset($keys_arr['atlt_google-api-key'])){
                $key=$keys_arr['atlt_google-api-key'];
            }
        }else if($source=="microsoft"){
            if(isset($keys_arr['atlt_microsoft-api-key'])){
                $key=$keys_arr['atlt_microsoft-api-key'];
            }
        }else{
            if(isset($keys_arr['atlt_api-key'])){
                $key=$keys_arr['atlt_api-key'];
            }

        }
        return $key;
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

    // format cumber
    public static function formatNum($n) {
        // first strip any formatting;
        $n = (0+str_replace(",", "", $n));
        // is this a number?
        if (!is_numeric($n)) return false;
        // now filter it;
        if ($n > 1000000000000) return round(($n/1000000000000), 2).' trillion';
        elseif ($n > 1000000000) return round(($n/1000000000), 2).' billion';
        elseif ($n > 1000000) return round(($n/1000000), 2).' million';
        elseif ($n > 1000) return round(($n/1000), 2).' thousand';

        return number_format($n);
    }

   /*
   |----------------------------------------------------------------|
   |       return the total amount of time saved on translation     |
   | @param $characters int number of translated charachters        |
   |----------------------------------------------------------------|
   */
   public static function atlt_time_saved_on_translation( $characters ){
        $total_saved = intval( $characters ) / 1800 ;
        if($characters='' || $characters<=0){
            return;
        }
        if( $total_saved >=1 && is_float( $total_saved ) ){
            $hour = intval( $total_saved );
            $minute =  $total_saved - $hour;
            $minute = intval( $minute * 60 );
            return $hour .' hour and '. round($minute,2).' minutes';
        }else{
            $minute = floatval($total_saved) * 60;
            if( $minute <1 ){
                return round($minute * 60, 2) . ' seconds';
            }
            return round($minute,2) . ' minutes';
        }
    }

       public static function googleSLangList($langCode,$list=false)
       {
          $json_lang_list='{"af":"Afrikaans","sq":"Albanian","am":"Amharic","ar":"Arabic","hy":"Armenian","az":"Azerbaijani","eu":"Basque","be":"Belarusian","bn":"Bengali","bs":"Bosnian","bg":"Bulgarian","ca":"Catalan","ceb ":"Cebuano","zh-CN":"Chinese (Simplified)","zh-TW ":"Chinese (Traditional)","co":"Corsican","hr":"Croatian","cs":"Czech","da":"Danish","nl":"Dutch","en":"English","eo":"Esperanto","et":"Estonian","fi":"Finnish","fr":"French","fy":"Frisian","gl":"Galician","ka":"Georgian","de":"German","el":"Greek","gu":"Gujarati","ht":"Haitian Creole","ha":"Hausa","haw ":"Hawaiian","he ":"Hebrew","hi":"Hindi","hmn ":"Hmong","hu":"Hungarian","is":"Icelandic","ig":"Igbo","id":"Indonesian","ga":"Irish","it":"Italian","ja":"Japanese","jv":"Javanese","kn":"Kannada","kk":"Kazakh","km":"Khmer","ko":"Korean","ku":"Kurdish","ky":"Kyrgyz","lo":"Lao","la":"Latin","lv":"Latvian","lt":"Lithuanian","lb":"Luxembourgish","mk":"Macedonian","mg":"Malagasy","ms":"Malay","ml":"Malayalam","mt":"Maltese","mi":"Maori","mr":"Marathi","mn":"Mongolian","my":"Myanmar (Burmese)","ne":"Nepali","no":"Norwegian","ny":"Nyanja (Chichewa)","ps":"Pashto","fa":"Persian","pl":"Polish","pt":"Portuguese (Portugal, Brazil)","pa":"Punjabi","ro":"Romanian","ru":"Russian","sm":"Samoan","gd":"Scots Gaelic","sr":"Serbian","st":"Sesotho","sn":"Shona","sd":"Sindhi","si":"Sinhala (Sinhalese)","sk":"Slovak","sl":"Slovenian","so":"Somali","es":"Spanish","su":"Sundanese","sw":"Swahili","sv":"Swedish","tl":"Tagalog (Filipino)","tg":"Tajik","ta":"Tamil","te":"Telugu","th":"Thai","tr":"Turkish","uk":"Ukrainian","ur":"Urdu","uz":"Uzbek","vi":"Vietnamese","cy":"Welsh","xh":"Xhosa","yi":"Yiddish","yo":"Yoruba","zu":"Zulu"}';
          $langArr=json_decode($json_lang_list,true);

          $temp = array();
            foreach ($langArr as $key => $value) {
                $temp[trim($key)] = $value;
            }
            $langArr = $temp;

          if($list){
              return $langArr;
          }
          if(isset($langArr[$langCode])){
              return true;
          }else{
              return false;
          }
       }
       public static function yandexSLangList($langCode,$list=false){

           $json_lang_list='{"af":"Afrikaans","am":"Amharic","ar":"Arabic","az":"Azerbaijani","ba":"Bashkir","be":"Belarusian","bg":"Bulgarian","bn":"Bengali","bs":"Bosnian","ca":"Catalan","ceb":"Cebuano","cs":"Czech","cy":"Welsh","da":"Danish","de":"German","el":"Greek","en":"English","eo":"Esperanto","es":"Spanish","et":"Estonian","eu":"Basque","fa":"Persian","fi":"Finnish","fr":"French","ga":"Irish","gd":"Scottish Gaelic","gl":"Galician","gu":"Gujarati","he":"Hebrew","hi":"Hindi","hr":"Croatian","ht":"Haitian","hu":"Hungarian","hy":"Armenian","id":"Indonesian","is":"Icelandic","it":"Italian","ja":"Japanese","jv":"Javanese","ka":"Georgian","kk":"Kazakh","km":"Khmer","kn":"Kannada","ko":"Korean","ky":"Kyrgyz","la":"Latin","lb":"Luxembourgish","lo":"Lao","lt":"Lithuanian","lv":"Latvian","mg":"Malagasy","mhr":"Mari","mi":"Maori","mk":"Macedonian","ml":"Malayalam","mn":"Mongolian","mr":"Marathi","mrj":"Hill Mari","ms":"Malay","mt":"Maltese","my":"Burmese","ne":"Nepali","nl":"Dutch","no":"Norwegian","pa":"Punjabi","pap":"Papiamento","pl":"Polish","pt":"Portuguese","ro":"Romanian","ru":"Russian","si":"Sinhalese","sk":"Slovak","sl":"Slovenian","sq":"Albanian","sr":"Serbian","su":"Sundanese","sv":"Swedish","sw":"Swahili","ta":"Tamil","te":"Telugu","tg":"Tajik","th":"Thai","tl":"Tagalog","tr":"Turkish","tt":"Tatar","udm":"Udmurt","uk":"Ukrainian","ur":"Urdu","uz":"Uzbek","vi":"Vietnamese","xh":"Xhosa","yi":"Yiddish","zh":"Chinese"}';
           $langArr=json_decode($json_lang_list,true);

           $temp = array();
            foreach ($langArr as $key => $value) {
                $temp[trim($key)] = $value;
            }
            $langArr = $temp;

           if($list){
               return $langArr;
           }

           if(isset($langArr[$langCode])){
               return true;
           }else{
               return false;
           }
       }
       public static function microsoftSLangList($langCode,$list=false){
           $json_lang_list='{"af":"Afrikaans","ar":"Arabic","bg":"Bulgarian","bn":"Bangla","bs":"Bosnian","ca":"Catalan","cs":"Czech","cy":"Welsh","da":"Danish","de":"German","el":"Greek","en":"English","es":"Spanish","et":"Estonian","fa":"Persian","fi":"Finnish","fil":"Filipino","fj":"Fijian","fr":"French","ga":"Irish","he":"Hebrew","hi":"Hindi","hr":"Croatian","ht":"Haitian Creole","hu":"Hungarian","id":"Indonesian","is":"Icelandic","it":"Italian","ja":"Japanese","kn":"Kannada","ko":"Korean","lt":"Lithuanian","lv":"Latvian","mg":"Malagasy","mi":"Maori","ml":"Malayalam","ms":"Malay","mt":"Maltese","mww":"Hmong Daw","no":"Norwegian","nl":"Dutch","otq":"Quer\u00e9taro Otomi","pl":"Polish","pt":"Portuguese","pa":"Punjabi","ro":"Romanian","ru":"Russian","sk":"Slovak","sl":"Slovenian","sm":"Samoan","sr-Cyrl":"Serbian (Cyrillic)","sr-Latn":"Serbian (Latin)","sv":"Swedish","sw":"Kiswahili","ta":"Tamil","te":"Telugu","th":"Thai","tlh":"Klingon","to":"Tongan","tr":"Turkish","ty":"Tahitian","uk":"Ukrainian","ur":"Urdu","vi":"Vietnamese","yua":"Yucatec Maya","yue":"Cantonese (Traditional)","zh-Hans":"Chinese Simplified","zh-Hant":"Chinese Traditional"}';

           $langArr=json_decode($json_lang_list,true);

           $temp = array();
            foreach ($langArr as $key => $value) {
                $temp[trim($key)] = $value;
            }
            $langArr = $temp;

           if($list){
               return $langArr;
           }
           if(isset($langArr[$langCode])){
               return true;
           }else{
               return false;
           }
       }
}
