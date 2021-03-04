<?php
/**
 * This Files Print Mathematical Captcha Settings HTML of WP Captcha Plugin in admin Section.
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma <devnathverma@gmail.com>
 */
?>

<?php if ( isset( $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] ) && $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'mathematical_captcha' ) { ?>	
<div class="col-10 c4wp-mathematical-captcha-settings-content">
<?php } else if ( isset( $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] ) && $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'image_captcha' ) { ?>
<div class="col-10 c4wp-mathematical-captcha-settings-content" style="display:none;">
<?php }	else if ( isset( $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] ) && $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'icon_captcha' ) { ?>
<div class="col-10 c4wp-mathematical-captcha-settings-content" style="display:none;">
<?php }	else if ( isset( $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] ) && $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'google_recaptcha' ) { ?>
<div class="col-10 c4wp-mathematical-captcha-settings-content" style="display:none;">
<?php }	else {  ?>
<div class="col-10 c4wp-mathematical-captcha-settings-content">
<?php } ?> 
	<div class="row">
		<div class="form-group col-md-4">
			<label for="image_captcha_widht"><?php _e( 'Airthmatic Operations', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-6">
			<input id="c4wp_mathematical_operations_addition" type="checkbox" name="c4wp_options[mathematical_captcha_setting][addition]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['mathematical_captcha_setting']['addition'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['mathematical_captcha_setting']['addition'], true ); } ?>/><label for="c4wp_mathematical_operations_addition"><?php _e( 'Addition ( &#43; )', 'wp-captcha' ); ?></label> 
			<br />
			<input id="c4wp_mathematical_operations_substruction" type="checkbox" name="c4wp_options[mathematical_captcha_setting][substruction]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['mathematical_captcha_setting']['substruction'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['mathematical_captcha_setting']['substruction'], true ); } ?> /><label for="c4wp_mathematical_operations_substruction"><?php _e( 'Substruction ( &minus; )', 'wp-captcha' ); ?></label> 
			<br />
			<input id="c4wp_mathematical_operations_multiplication" type="checkbox" name="c4wp_options[mathematical_captcha_setting][multiplication]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['mathematical_captcha_setting']['multiplication'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['mathematical_captcha_setting']['multiplication'], true ); } ?> /><label for="c4wp_mathematical_operations_multiplication"><?php _e( 'Multiplication ( &times; )', 'wp-captcha' ); ?></label> 
			<br />
			<input id="c4wp_mathematical_operations_division" type="checkbox" name="c4wp_options[mathematical_captcha_setting][division]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['mathematical_captcha_setting']['division'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['mathematical_captcha_setting']['division'], true ); } ?> /><label for="c4wp_mathematical_operations_division"><?php _e( 'Division ( &#8260; )', 'wp-captcha' ); ?></label>
		</div>
	</div>
	
	<div class="row">
		<div class="form-group col-md-4">
			<label for="image_captcha_widht"><?php _e( 'Display Captcha as', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-6">
			<input id="c4wp_display_groups_numbers" type="checkbox" name="c4wp_options[mathematical_captcha_setting][numbers]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['mathematical_captcha_setting']['numbers'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['mathematical_captcha_setting']['numbers'], true ); } ?> /><label for="c4wp_display_groups_numbers"><?php _e( 'Numbers', 'wp-captcha' ); ?></label> 
			<br />
			<input id="c4wp_display_groups_words" type="checkbox" name="c4wp_options[mathematical_captcha_setting][words]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['mathematical_captcha_setting']['words'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['mathematical_captcha_setting']['words'], true ); } ?> /><label for="c4wp_display_groups_words"><?php _e( 'Words', 'wp-captcha' ); ?></label>
		</div>
	</div>
</div>