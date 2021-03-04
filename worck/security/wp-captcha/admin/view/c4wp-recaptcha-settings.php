<?php
/**
 * This Files Print Google reCaptcha Settings HTML of WP Captcha Plugin in admin Section.
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma <devnathverma@gmail.com>
 */
?>

<?php if ( isset( $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] ) && $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'google_recaptcha' ) { ?>	
<div class="col-10 c4wp-google-recaptcha-settings-content">
<?php } else if ( isset( $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] ) && $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'mathematical_captcha' ) { ?>
<div class="col-10 c4wp-google-recaptcha-settings-content" style="display:none;">
<?php } else if ( isset( $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] ) && $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'image_captcha' ) { ?>
<div class="col-10 c4wp-google-recaptcha-settings-content" style="display:none;">
<?php } else if ( isset( $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] ) && $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'icon_captcha' ) { ?>
<div class="col-10 c4wp-google-recaptcha-settings-content" style="display:none;">
<?php } ?>	
	<div class="row">
		<div class="form-group col-md-2">
			<label for="site_key"><?php _e( 'Site key', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-10">
			<input type="text" class="form-control" id="site_key" name="c4wp_options[google_recaptcha_setting][site_key]" value="<?php echo $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['site_key']; ?>" autocomplete="off"/>
			<span class="description"><?php _e('Used for displaying the Google reCaptcha. Grab it Site Key <a href="https://www.google.com/recaptcha/admin" target="_blank">Here</a>', 'wp-captcha'); ?></span>			
		</div>  
	</div>
	<div class="row">
		<div class="form-group col-md-2">
			<label for="secret_key"><?php _e( 'Secret key', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-10">
			<input type="text" class="form-control" id="secret_key" name="c4wp_options[google_recaptcha_setting][secret_key]" value="<?php echo $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['secret_key']; ?>" autocomplete="off"/>
			<span class="description"><?php _e('Used for communication between your site and Google. Grab it Secret Key <a href="https://www.google.com/recaptcha/admin" target="_blank">Here</a>', 'wp-captcha'); ?></span>			
		</div>  
	</div>
	<div class="row">
		<div class="form-group col-md-2">
			<label for="recaptcha_size"><?php _e( 'reCaptcha Size', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-4">
			<select id="recaptcha_size" class="form-control" name="c4wp_options[google_recaptcha_setting][recaptcha_size]">
				<option value="normal" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['recaptcha_size'], "normal" ); ?>><?php _e( 'Normal', 'wp-captcha' ); ?></option>
				<option value="compact" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['recaptcha_size'], "compact" ); ?>><?php _e( 'Compact', 'wp-captcha' ); ?></option>
		</select>
		<span class="description"><?php _e( 'What size of google reCaptcha do you want to show?', 'wp-captcha' ); ?></span>
		</div>
		<div class="form-group col-md-2">
			<label for="recaptcha_theme"><?php _e( 'reCaptcha Theme', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-4">
			<select id="recaptcha_theme" class="form-control" name="c4wp_options[google_recaptcha_setting][recaptcha_theme]">
				<option value="light" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['recaptcha_theme'], "light" ); ?>><?php _e( 'Light', 'wp-captcha' ); ?></option>
				<option value="dark" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['recaptcha_theme'], "dark" ); ?>><?php _e( 'Dark', 'wp-captcha' ); ?></option>
			</select>
		<span class="description"><?php _e( 'What type of reCaptcha Theme do you want?', 'wp-captcha' ); ?></span>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-2">
			<label for="recaptcha_type"><?php _e( 'reCaptcha Type', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-4">
			<select id="recaptcha_type" class="form-control" name="c4wp_options[google_recaptcha_setting][recaptcha_type]">
				<option value="image" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['recaptcha_type'], "image" ); ?>><?php _e( 'Image', 'wp-captcha' ); ?></option>
				<option value="audio" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['recaptcha_type'], "audio" ); ?>><?php _e( 'Audio', 'wp-captcha' ); ?></option>
			</select>
			<span class="description"><?php _e( 'Do you want reCaptcha to be Image or Audio?							', 'wp-captcha' ); ?></span>
		</div>
		<div class="form-group col-md-2">
			<label for="reCaptcha_language"><?php _e( 'reCaptcha Language', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-4">
			<select id="reCaptcha_language" class="form-control" name="c4wp_options[google_recaptcha_setting][reCaptcha_language]">
				<option value="ar" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "ar" ); ?>><?php _e( 'Arabic', 'wp-captcha' ); ?></option> 
				<option value="af" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "af" ); ?>><?php _e( 'Afrikaans', 'wp-captcha' ); ?></option>
				<option value="am" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "am" ); ?>><?php _e( 'Amharic', 'wp-captcha' ); ?></option>
				<option value="hy" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "hy" ); ?>><?php _e( 'Armenian', 'wp-captcha' ); ?></option>
				<option value="az" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "az" ); ?>><?php _e( 'Azerbaijani', 'wp-captcha' ); ?></option>
				<option value="eu" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "eu" ); ?>><?php _e( 'Basque', 'wp-captcha' ); ?></option>
				<option value="bn" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "bn" ); ?>><?php _e( 'Bengali', 'wp-captcha' ); ?></option> 
				<option value="bg" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "bg" ); ?>><?php _e( 'Bulgarian', 'wp-captcha' ); ?></option> 
				<option value="ca" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "ca" ); ?>><?php _e( 'Catalan', 'wp-captcha' ); ?></option> 
				<option value="zh-HK" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "zh-HK" ); ?>><?php _e( 'Chinese (Hong Kong)', 'wp-captcha' ); ?></option>
				<option value="zh-CN" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "zh-CN" ); ?>><?php _e( 'Chinese (Simplified)', 'wp-captcha' ); ?></option>
				<option value="zh-TW" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "zh-TW" ); ?>><?php _e( 'Chinese (Traditional)', 'wp-captcha' ); ?></option> 
				<option value="hr" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "hr" ); ?>><?php _e( 'Croatian', 'wp-captcha' ); ?></option>       
				<option value="cs" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "cs" ); ?>><?php _e( 'Czech', 'wp-captcha' ); ?></option>   
				<option value="da" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "da" ); ?>><?php _e( 'Danish', 'wp-captcha' ); ?></option>  
				<option value="nl" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "nl" ); ?>><?php _e( 'Dutch', 'wp-captcha' ); ?></option> 
				<option value="en-GB" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "en-GB" ); ?>><?php _e( 'English (UK)', 'wp-captcha' ); ?></option>    
				<option value="en" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "en" ); ?>><?php _e( 'English (US)', 'wp-captcha' ); ?></option>  
				<option value="et" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "et" ); ?>><?php _e( 'Estonian', 'wp-captcha' ); ?></option> 
				<option value="fil" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "fil" ); ?>><?php _e( 'Filipino', 'wp-captcha' ); ?></option> 
				<option value="fi" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "fi" ); ?>><?php _e( 'Finnish', 'wp-captcha' ); ?></option>      
				<option value="fr" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "fr" ); ?>><?php _e( 'French', 'wp-captcha' ); ?></option>      
				<option value="fr-CA" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "fr-CA" ); ?>><?php _e( 'French (Canadian)', 'wp-captcha' ); ?></option>    
				<option value="gl" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "gl" ); ?>><?php _e( 'Galician', 'wp-captcha' ); ?></option>         			
				<option value="ka" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "ka" ); ?>><?php _e( 'Georgian', 'wp-captcha' ); ?></option>         			
				<option value="de" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "de" ); ?>><?php _e( 'German', 'wp-captcha' ); ?></option>         			
				<option value="de-AT" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "de-AT" ); ?>><?php _e( 'German (Austria)', 'wp-captcha' ); ?></option>         	
				<option value="de-CH" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "de-CH" ); ?>><?php _e( 'German (Switzerland)', 'wp-captcha' ); ?></option>    	
				<option value="el" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "el" ); ?>><?php _e( 'Greek', 'wp-captcha' ); ?></option>         			
				<option value="gu" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "gu" ); ?>><?php _e( 'Gujarati', 'wp-captcha' ); ?></option>         			
				<option value="iw" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "iw" ); ?>><?php _e( 'Hebrew', 'wp-captcha' ); ?></option>         			
				<option value="hi" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "hi" ); ?>><?php _e( 'Hindi', 'wp-captcha' ); ?></option>         			
				<option value="hu" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "hu" ); ?>><?php _e( 'Hungarain', 'wp-captcha' ); ?></option>         		
				<option value="is" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "is" ); ?>><?php _e( 'Icelandic', 'wp-captcha' ); ?></option>         		
				<option value="id" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "id" ); ?>><?php _e( 'Indonesian', 'wp-captcha' ); ?></option>         		
				<option value="it" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "it" ); ?>><?php _e( 'Italian', 'wp-captcha' ); ?></option>         			
				<option value="ja" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "ja" ); ?>><?php _e( 'Japanese', 'wp-captcha' ); ?></option>         			
				<option value="kn" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "kn" ); ?>><?php _e( 'Kannada', 'wp-captcha' ); ?></option>         			
				<option value="ko" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "ko" ); ?>><?php _e( 'Korean', 'wp-captcha' ); ?></option>         			
				<option value="lo" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "lo" ); ?>><?php _e( 'Laothian', 'wp-captcha' ); ?></option>         			
				<option value="lv" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "lv" ); ?>><?php _e( 'Latvian', 'wp-captcha' ); ?></option>         			
				<option value="lt" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "lt" ); ?>><?php _e( 'Lithuanian', 'wp-captcha' ); ?></option>         		
				<option value="ms" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "ms" ); ?>><?php _e( 'Malay', 'wp-captcha' ); ?></option>         			
				<option value="ml" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "ml" ); ?>><?php _e( 'Malayalam', 'wp-captcha' ); ?></option>         		
				<option value="mr" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "mr" ); ?>><?php _e( 'Marathi', 'wp-captcha' ); ?></option>         			
				<option value="mn" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "mn" ); ?>><?php _e( 'Mongolian', 'wp-captcha' ); ?></option>         		
				<option value="no" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "no" ); ?>><?php _e( 'Norwegian', 'wp-captcha' ); ?></option>         		
				<option value="fa" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "fa" ); ?>><?php _e( 'Persian', 'wp-captcha' ); ?></option>         			
				<option value="pl" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "pl" ); ?>><?php _e( 'Polish', 'wp-captcha' ); ?></option>         			
				<option value="pt" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "pt" ); ?>><?php _e( 'Portuguese', 'wp-captcha' ); ?></option>         		
				<option value="pt-BR" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "pt-BR" ); ?>><?php _e( 'Portuguese (Brazil)', 'wp-captcha' ); ?></option>     	
				<option value="pt-PT" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "pt-PT" ); ?>><?php _e( 'Portuguese (Portugal)', 'wp-captcha' ); ?></option>		
				<option value="ro" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "ro" ); ?>><?php _e( 'Romanian', 'wp-captcha' ); ?></option>					
				<option value="ru" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "ru" ); ?>><?php _e( 'Russian', 'wp-captcha' ); ?></option>         			
				<option value="sr" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "sr" ); ?>><?php _e( 'Serbian', 'wp-captcha' ); ?></option>         			
				<option value="si" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "si" ); ?>><?php _e( 'Sinhalese', 'wp-captcha' ); ?></option>         		
				<option value="sk" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "sk" ); ?>><?php _e( 'Slovak', 'wp-captcha' ); ?></option>         			
				<option value="sl" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "sl" ); ?>><?php _e( 'Slovenian', 'wp-captcha' ); ?></option>         		
				<option value="es" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "es" ); ?>><?php _e( 'Spanish', 'wp-captcha' ); ?></option>         			
				<option value="es-419" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "es-419" ); ?>><?php _e( 'Spanish (Latin America)', 'wp-captcha' ); ?></option>	
				<option value="sw" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "sw" ); ?>><?php _e( 'Swahili', 'wp-captcha' ); ?></option>         			
				<option value="sv" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "sv" ); ?>><?php _e( 'Swedish', 'wp-captcha' ); ?></option>         			
				<option value="ta" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "ta" ); ?>><?php _e( 'Tamil', 'wp-captcha' ); ?></option>         			
				<option value="te" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "te" ); ?>><?php _e( 'Telugu', 'wp-captcha' ); ?></option>         			
				<option value="th" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "th" ); ?>><?php _e( 'Thai', 'wp-captcha' ); ?></option>         				
				<option value="tr" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "tr" ); ?>><?php _e( 'Turkish', 'wp-captcha' ); ?></option>        			
				<option value="uk" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "uk" ); ?>><?php _e( 'Ukrainian', 'wp-captcha' ); ?></option>         		
				<option value="ur" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "ur" ); ?>><?php _e( 'Urdu', 'wp-captcha' ); ?></option>         				
				<option value="vi" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "vi" ); ?>><?php _e( 'Vietnamese', 'wp-captcha' ); ?></option>         		
				<option value="zu" <?php selected( $c4wp_plugin_options['c4wp_options']['google_recaptcha_setting']['reCaptcha_language'], "zu" ); ?>><?php _e( 'Zulu', 'wp-captcha' ); ?></option> 
			</select>
		<span class="description"><?php _e( 'In which Language you want your reCaptcha.', 'wp-captcha' ); ?></span>
		</div>  
	</div>
</div> 