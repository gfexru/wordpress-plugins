/**
 * WP Captcha recaptcha scripts.
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma
*/

var c4wp_loadrecaptcha = function() {
    
	var captchas = document.getElementsByClassName('c4wp-google-recaptcha');
    
	for(var i = 0; i < captchas.length; i++) {
        
		grecaptcha.render(captchas[i], {
			'sitekey'   : 	C4WP.recaptcha_site_key,
			'size'     	: 	C4WP.recaptcha_size, 
			'theme'     : 	C4WP.recaptcha_theme, 
			'type'      : 	C4WP.recaptcha_type
		});
    }
};