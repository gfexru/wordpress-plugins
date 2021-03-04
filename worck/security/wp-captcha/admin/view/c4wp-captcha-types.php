<?php
/**
 * This Files Prints Captcha Types HTML of WP Captcha Plugin in admin Section.
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma <devnathverma@gmail.com>
 */	
?>
<div class="col-12 purpose-radios-wrapper">
	<div class="purpose-radio form-group col-md-3">
		<input id="c4wp_mathematical_captcha" class="purpose-radio-input" type="radio" name="c4wp_options[captcha_enable_for]" value="mathematical_captcha" <?php if( isset( $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['captcha_enable_for'], 'mathematical_captcha'); } ?> />
		<label for="c4wp_mathematical_captcha" class="purpose-radio-label">
		<img src="<?php echo C4WP_PLUGIN_IMAGES; ?>mathematical-captcha.JPG" alt="branding" class="label-icon-default">
		<span class="label-text"><?php _e( 'Mathematical Captcha', 'wp-captcha' ); ?></span>
		</label>
	</div>
	<div class="purpose-radio form-group col-md-3">
		<input id="c4wp_image_captcha" class="purpose-radio-input" type="radio" name="c4wp_options[captcha_enable_for]" value="image_captcha" <?php if( isset( $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['captcha_enable_for'], 'image_captcha'); } ?> />
		<label for="c4wp_image_captcha" class="purpose-radio-label">
		<img src="<?php echo C4WP_PLUGIN_IMAGES; ?>image-captcha.JPG" alt="branding" class="label-icon-default">
		<span class="label-text"><?php _e( 'Image Captcha', 'wp-captcha' ); ?></span>
		</label>
	</div>
	<div class="purpose-radio form-group col-md-3">
		<input id="c4wp_icon_captcha" class="purpose-radio-input" type="radio" name="c4wp_options[captcha_enable_for]" value="icon_captcha" <?php if( isset( $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['captcha_enable_for'], 'icon_captcha'); } ?> />
		<label for="c4wp_icon_captcha" class="purpose-radio-label">
		<img src="<?php echo C4WP_PLUGIN_IMAGES; ?>icon-captcha.JPG" alt="branding" class="label-icon-default">
		<span class="label-text"><?php _e( 'Icon Captcha', 'wp-captcha' ); ?></span>
		</label>
	</div>
	<div class="purpose-radio form-group col-md-3">
		<input id="c4wp_google_recaptcha" class="purpose-radio-input" type="radio" name="c4wp_options[captcha_enable_for]" value="google_recaptcha" <?php if( isset( $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['captcha_enable_for'], 'google_recaptcha'); } ?> />
		<label for="c4wp_google_recaptcha" class="purpose-radio-label">
		<img src="<?php echo C4WP_PLUGIN_IMAGES; ?>google-recaptcha.JPG" alt="branding" class="label-icon-default">
		<span class="label-text"><?php _e( 'Google Recaptcha ( V2 )', 'wp-captcha' ); ?></span>
		</label>
	</div>
</div>