/**
 * WP Captcha general backend-end scripts.
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma
*/

jQuery(document).ready(function($) {

	var current_fs, next_fs, previous_fs; //fieldsets
	var opacity;
	var current = 1;
	var steps = $("fieldset").length;

	setProgressBar(current);

	$(".next").click(function() {
		current_fs = $(this).parent();
		next_fs = $(this).parent().next();
		$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
		next_fs.show();
	
		current_fs.animate({opacity: 0}, {
		
				step: function(now) {
		
				opacity = 1 - now;
				
					current_fs.css({
								   
							'display': 'none',
							'position': 'relative'
					});
		
					next_fs.css({'opacity': opacity});
				},

				duration: 500
		});

		setProgressBar(++current);
	});

	$(".previous").click(function() {
		current_fs = $(this).parent();
		previous_fs = $(this).parent().prev();
		$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
		previous_fs.show();

		current_fs.animate({opacity: 0}, {
		
		step: function(now) {
		
				opacity = 1 - now;
		
				current_fs.css({
					
						'display': 'none',
						'position': 'relative'
				});
			
				previous_fs.css({'opacity': opacity});
		},
			
				duration: 500
		});
	
		setProgressBar(--current);
	});

	function setProgressBar(curStep) {
		var percent = parseFloat(100 / steps) * curStep;
		percent = percent.toFixed();
		$(".progress-bar").css("width",percent+"%")
	}
	
	$(".c4wp-reset").click(function() {
		
		if (confirm('Do you want to Restore Settings?')) {
			
			return true;
		
		} else {
			
			return false;
		}
	});	
	
	
	$(".c4wp-submit").click(function() {
		
		if ($('.c4wp-google-recaptcha-settings-content').css('display') == 'none') {
			
			return true;
		
		} else {
			
			var error_message = '';
			
			if(!$("#site_key").val()) {
				error_message+="<strong>Error : </strong>Please enter Site key.<br>";
			}
			
			if(!$("#secret_key").val()) {
				error_message+="<strong>Error : </strong>Please enter Secret key.";
			}
			
			if(error_message) {
				$('.c4wp-alert-success').show();
				$('.c4wp-alert-success').html(error_message);
				$('.c4wp-alert-success').delay(2500).hide(2500);
				return false;
			} else {
				return true;	
			}
		}	
	});
	
	$(".c4wp-color-field").wpColorPicker();
	$('.c4wp-update').delay(1500).hide(1500); 
	
	$("#c4wp_mathematical_captcha").click(function(){
		$(".c4wp-mathematical-captcha-settings-content").css("display", "block");
		$(".c4wp-image-captcha-settings-content").css("display", "none");
		$(".c4wp-icon-captcha-settings-content").css("display", "none");
		$(".c4wp-google-recaptcha-settings-content").css("display", "none");
	});
	
	$("#c4wp_image_captcha").click(function() {
		$(".c4wp-image-captcha-settings-content").css("display", "block");
		$(".c4wp-mathematical-captcha-settings-content").css("display", "none");
		$(".c4wp-icon-captcha-settings-content").css("display", "none");
		$(".c4wp-google-recaptcha-settings-content").css("display", "none");
	});
	
	$("#c4wp_icon_captcha").click(function() {
		$(".c4wp-icon-captcha-settings-content").css("display", "block");
		$(".c4wp-mathematical-captcha-settings-content").css("display", "none");
		$(".c4wp-image-captcha-settings-content").css("display", "none");
		$(".c4wp-google-recaptcha-settings-content").css("display", "none");
	});
	
	$("#c4wp_google_recaptcha").click(function() {
		$(".c4wp-google-recaptcha-settings-content").css("display", "block");
		$(".c4wp-mathematical-captcha-settings-content").css("display", "none");
		$(".c4wp-image-captcha-settings-content").css("display", "none");
		$(".c4wp-icon-captcha-settings-content").css("display", "none");
	});	
});