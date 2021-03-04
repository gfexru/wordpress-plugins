<?php
/**
 * This Files Print Captcha Other Settings HTML of WP Captcha Plugin in admin Section.
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma <devnathverma@gmail.com>
 */
?>
<div class="col-10 c4wp-other-settings-content">
	<div class="row">
		<div class="form-group col-md-3">
			<label for="characters_on_image"><?php _e( 'Captcha Title', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-8">
			<input type="text" class="form-control" id="captcha_title" name="c4wp_options[other_settings][captcha_title]" value="<?php echo $c4wp_plugin_options['c4wp_options']['other_settings']['captcha_title']; ?>"/>
			<span class="description"><?php _e( 'How to entitle field with captcha?', 'wp-captcha' ); ?></span>			
		</div>  
	</div>
	<div class="row">
		<div class="form-group col-md-3">
			<label for="characters_on_image"><?php _e( 'Captcha Empty Messages', 'wp-captcha' ); ?></label>				
		</div>
		<div class="form-group col-md-8">
			<input type="text" class="form-control" id="captcha_empty_messages" name="c4wp_options[other_settings][captcha_empty_messages]" value="<?php echo $c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages']; ?>"/>
			<span class="description"><?php _e( 'Message or text to display when CAPTCHA is empty?', 'wp-captcha' ); ?></span>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-3">
			<label for="characters_on_image"><?php _e( 'Captcha Error Messages', 'wp-captcha' ); ?></label>				
		</div>
		<div class="form-group col-md-8">		
			<input type="text" class="form-control" id="captcha_error_messages" name="c4wp_options[other_settings][captcha_error_messages]" value="<?php echo $c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages']; ?>"/>
			<span class="description"><?php _e( 'Message or text to display when CAPTCHA is ignored or the test has failed?', 'wp-captcha' ); ?></span>
		</div>  
	</div>
	<div class="row">
		<div class="form-group col-md-3">
			<label for="characters_on_image"><?php _e( 'Hide for logged in users', 'wp-captcha' ); ?></label>				
		</div>
		<div class="form-group col-md-8">		
			<input type="checkbox" class="form-control" id="hide_for_logged_users" name="c4wp_options[other_settings][hide_for_logged_users]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['other_settings']['hide_for_logged_users'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['other_settings']['hide_for_logged_users'], true ); } ?>/><label for="hide_for_logged_users"><?php _e( 'Enable to hide captcha for logged in users?', 'wp-captcha' ); ?></label>
		</div>  
	</div>
	<div class="row">
		<div class="form-group col-md-3">
			<label for="deactivation_delete"><?php _e( 'Delete settings', 'wp-captcha' ); ?></label>				
		</div>
		<div class="form-group col-md-8">		
			<input type="checkbox" class="form-control" id="deactivation_delete" name="c4wp_options[other_settings][deactivation_delete]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['other_settings']['deactivation_delete'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['other_settings']['deactivation_delete'], true ); } ?>/><label for="deactivation_delete"><?php _e( 'Delete Settings on Plugin Deactivation.', 'wp-captcha' ); ?></label>
		</div>  
	</div>
</div>