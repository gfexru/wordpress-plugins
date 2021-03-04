<?php
$extra = "";
global $wpdb;
include_once("settings.php");
//frontend
$front_id = 0;

$lang = "LANG";
if( get_option( "red_fm_lang" ) ) {
	$lang = get_option( "red_fm_lang" );
}

if( get_option("red_fm_toolbar_text") ){
	$toolbar_text = stripslashes( get_option("red_fm_toolbar_text") );
}else{
	$toolbar_text = '["back", "forward"],
					 ["reload"],
			        ["home", "up"],
			        ["mkdir", "mkfile", "upload"],
			        ["open", "download", "getfile"],
			        ["info"],
			        ["quicklook"],
			        ["copy", "cut", "paste"],
			        ["rm"],
			        ["duplicate", "rename", "edit", "resize"],
			        ["extract", "archive"],
			        ["search"],
			        ["view"],
			        ["help"]';
}


	if( get_option("red_fm_context_text") ){
		$context_text = stripslashes( get_option("red_fm_context_text") );
	}else{
$context_text = 'navbar : ["open", "|", "duplicate", "|", "rm", "|", "info"], 
cwd : ["reload", "back", "|", "upload", "mkdir", "mkfile", "paste", "|", "info"], 
files : ["getfile", "|","open", "quicklook", "|", "download", "|", "duplicate", "|", "rm", "|", "edit", "rename", "resize", "|", "archive", "extract", "|", "info", "info2" ]';
	}


//WPML Check, overrides the built-in language switcher, 
//comment these 3 lines if you dont want this behavior
if ( function_exists('icl_object_id') ) {
     	$lang = ICL_LANGUAGE_CODE;
}
//ends wpml language switcher


//switches icons layout(icon or list)
$dview = "icons";
if( get_option( "red_fm_view" ) ) {
    $dview = get_option( "red_fm_view");
}


if(!isset($defaults)){
	$defaults = 0;
}

if(!isset($access_all)){
	$access_all = 0;
}

if(isset($red_front_end)){
	



$foldername =  $directory_temp . $foldername;

$query = "SELECT * FROM `" . $table_name . "` WHERE `folder` = '$foldername' AND `meta` = '$groups' AND `access` = '$access' ";
$results = $wpdb->get_results($query);



foreach($results as $key=>$value) {
		$front_id = $value->id;
}

$extra = "&front=user&fid=" . $front_id . "&defaults=" . $defaults. "&access_all=0";

if($access_all == 1){
	$extra = "&front=user&fid=" . $front_id . "&defaults=" . $defaults . "&access_all=1";	
}

}//end if

$blogi = site_url() . "/?red_fm_connect=true" . $extra;
$fm_string = '
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>elFinder 2.0</title>

		<!-- jQuery and jQuery UI (REQUIRED) -->
		<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="'.PLUGIN_URL.'js/jquery.min.js"></script>
		<script src="'.PLUGIN_URL.'js/jquery-ui.min.js"></script>

		<!-- elFinder CSS (REQUIRED) -->
		<link rel="stylesheet" type="text/css" media="screen" href="'.PLUGIN_URL.'css/elfinder.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="'.PLUGIN_URL.'css/theme.css">

		<!-- elFinder JS (REQUIRED) -->
		<script type="text/javascript" src="'.PLUGIN_URL.'js/elfinder.min.js"></script>
		<script type="text/javascript" src="'.PLUGIN_URL.'js/test.js"></script>

		<!-- elFinder translation (OPTIONAL) -->
		<script type="text/javascript" src="'.PLUGIN_URL.'js/i18n/elfinder.'.$lang.'.js"></script>

		<!-- elFinder initialization (REQUIRED) -->
		<script type="text/javascript" charset="utf-8"> 
			 var jq = jQuery.noConflict(true);
				
					jq(document).ready(function() { 
						jq("#elfinder").elfinder({ 
							url : "'.$blogi.'",
							 lang: "'.$lang.'",
							  defaultView: "'.$dview.'",
							   contextmenu : { 
												'.$context_text.'
								   },
								   uiOptions : {
											    toolbar : [
											    	'.$toolbar_text.'
											    ]
											}
							   }).elfinder("instance"); 
					}); 
			 
		</script>
	</head>
	<body>

		<!-- Element where elFinder will be created (REQUIRED) -->
		<div id="elfinder"></div>

	</body>
</html>';
?>