<?php
/**
 * This Files Print Captcha Forms HTML of WP Captcha Plugin in admin Section.
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma <devnathverma@gmail.com>
 */

if ( is_plugin_active('contact-form-7/wp-contact-form-7.php') || is_plugin_active('contact-form-pro/contact_form_pro.php') ) {
	$cf7_disable = '';
	$cf7_opacity = '';
} else {
	$cf7_disable = 'disabled="disabled"';
	$cf7_opacity = 'style="opacity:0.5;"';
} 

if ( is_plugin_active('subscriber/subscriber.php') || is_plugin_active('subscriber-pro/subscriber-pro.php') ) {
	$subscriber_disable = '';
	$subscriber_opacity = '';
} else {
	$subscriber_disable = 'disabled="disabled"';
	$subscriber_opacity = 'style="opacity:0.5;"';
}

if ( is_plugin_active('mailchimp-for-wp/mailchimp-for-wp.php') ) {
	$mailchimp_disable = '';
	$mailchimp_opacity = '';
} else {
	$mailchimp_disable = 'disabled="disabled"';
	$mailchimp_opacity = 'style="opacity:0.5;"';
}

if ( is_plugin_active('jetpack/jetpack.php') ) {
	$jetpack_disable = '';
	$jetpack_opacity = '';
} else {
	$jetpack_disable = 'disabled="disabled"';
	$jetpack_opacity = 'style="opacity:0.5;"';
}

if ( is_plugin_active('bbpress/bbpress.php') ) {
	$bbpress_disable = '';
	$bbpress_opacity = '';
} else {
	$bbpress_disable = 'disabled="disabled"';
	$bbpress_opacity = 'style="opacity:0.5;"';
}

if ( is_plugin_active('buddypress/bp-loader.php') ) {
	$buddypress_disable = '';
	$buddypress_opacity = '';
} else {
	$buddypress_disable = 'disabled="disabled"';
	$buddypress_opacity = 'style="opacity:0.5;"';
}

if ( is_plugin_active('awesome-support/awesome-support.php') ) {
	$awesome_support_disable = '';
	$awesome_support_opacity = '';
} else {
	$awesome_support_disable = 'disabled="disabled"';
	$awesome_support_opacity = 'style="opacity:0.5;"';
}

if ( is_plugin_active('woocommerce/woocommerce.php') ) {
	$woocommerce_disable = '';
	$woocommerce_opacity = '';
} else {
	$woocommerce_disable = 'disabled="disabled"';
	$woocommerce_opacity = 'style="opacity:0.5;"';
}
?>

<div class="col-10 c4wp-captcha-forms-content">
	<div class="row">
		<div class="form-group col-md-6">
			<input id="wp_login_form" type="checkbox" name="c4wp_options[enable_form_settings][wp_login_form]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['wp_login_form'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['wp_login_form'], true ); } ?> /><label for="wp_login_form"><?php _e( 'Wordpress Login', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-4">
			<input id="wp_registration_form" type="checkbox" name="c4wp_options[enable_form_settings][wp_registration_form]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['wp_registration_form'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['wp_registration_form'], true ); } ?> /><label for="wp_registration_form"><?php _e( 'Wordpress Registration', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-6">
			<input id="wp_reset_password_form" type="checkbox" name="c4wp_options[enable_form_settings][wp_reset_password_form]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['wp_reset_password_form'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['wp_reset_password_form'], true ); } ?> /><label for="wp_reset_password_form"><?php _e( 'Wordpress Reset Password', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-4">
			<input id="wp_comment_form" type="checkbox" name="c4wp_options[enable_form_settings][wp_comment_form]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['wp_comment_form'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['wp_comment_form'], true ); } ?>/><label for="wp_comment_form"><?php _e( 'Wordpress Comments', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-6">
			<input id="contact_form7" type="checkbox" name="c4wp_options[enable_form_settings][contact_form7]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['contact_form7'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['contact_form7'], true ); } ?> <?php echo $cf7_disable; ?>/><label for="contact_form7" <?php echo $cf7_opacity; ?>><?php _e( 'Contact Form 7', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-4">
			<input id="subscriber_form" type="checkbox" name="c4wp_options[enable_form_settings][subscriber_form]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['subscriber_form'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['subscriber_form'], true ); } ?> <?php echo $subscriber_disable; ?>/><label for="subscriber_form" <?php echo $subscriber_opacity; ?>><?php _e( 'Subscriber Form by ( BestWebSoft )', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-6">
		<input id="mc4wp_form" type="checkbox" name="c4wp_options[enable_form_settings][mc4wp_form]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['mc4wp_form'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['mc4wp_form'], true ); } ?> <?php echo $mailchimp_disable; ?>/><label for="mc4wp_form" <?php echo $mailchimp_opacity; ?>><?php _e( 'Mailchimp Form', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-4">
			<input id="jetpack_contact_form" type="checkbox" name="c4wp_options[enable_form_settings][jetpack_contact_form]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['jetpack_contact_form'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['jetpack_contact_form'], true ); } ?> <?php echo $jetpack_disable; ?>/><label for="jetpack_contact_form" <?php echo $jetpack_opacity; ?>><?php _e( 'Jetpack Contact Form', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-6">
			<input id="bbpress_topic_form" type="checkbox" name="c4wp_options[enable_form_settings][bbpress_topic_form]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['bbpress_topic_form'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['bbpress_topic_form'], true ); } ?> <?php echo $bbpress_disable; ?>/><label for="bbpress_topic" <?php echo $bbpress_opacity; ?>><?php _e( 'BBPress new Topic', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-4">
			<input id="bbpress_reply_form" type="checkbox" name="c4wp_options[enable_form_settings][bbpress_reply_form]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['bbpress_reply_form'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['bbpress_reply_form'], true ); } ?> <?php echo $bbpress_disable; ?> /><label for="bbpress_reply" <?php echo $bbpress_opacity; ?>><?php _e( 'BBPress Reply', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-6">
			<input id="bd_registration_form" type="checkbox" name="c4wp_options[enable_form_settings][bd_registration_form]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['bd_registration_form'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['bd_registration_form'], true ); } ?> <?php echo $buddypress_disable; ?>/><label for="bd_registration_form" <?php echo $buddypress_opacity; ?>><?php _e( 'BuddyPress Registration', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-4">
			<input id="bd_create_group_form" type="checkbox" name="c4wp_options[enable_form_settings][bd_create_group_form]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['bd_create_group_form'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['bd_create_group_form'], true ); } ?> <?php echo $buddypress_disable; ?>/><label for="bd_create_group_form" <?php echo $buddypress_opacity; ?>><?php _e( 'BuddyPress Create Group', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-6">
			<input id="aws_login_form" type="checkbox" name="c4wp_options[enable_form_settings][aws_login_form]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['aws_login_form'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['aws_login_form'], true ); } ?> <?php echo $awesome_support_disable; ?>/><label for="aws_login_form" <?php echo $awesome_support_opacity; ?>><?php _e( 'Awesome Support Login', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-4">
			<input id="aws_registration_form" type="checkbox" name="c4wp_options[enable_form_settings][aws_registration_form]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['aws_registration_form'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['aws_registration_form'], true ); } ?> <?php echo $awesome_support_disable; ?>/><label for="aws_registration_form" <?php echo $awesome_support_opacity; ?>><?php _e( 'Awesome Support Registration', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-6">
			<input id="wc_login_form" type="checkbox" name="c4wp_options[enable_form_settings][wc_login_form]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['wc_login_form'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['wc_login_form'], true ); } ?> <?php echo $woocommerce_disable; ?>/><label for="wc_login_form" <?php echo $woocommerce_opacity; ?>><?php _e( 'WooCommerce Login', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-4">
			<input id="wc_registration_form" type="checkbox" name="c4wp_options[enable_form_settings][wc_registration_form]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['wc_registration_form'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['wc_registration_form'], true ); } ?> <?php echo $woocommerce_disable; ?>/><label for="wc_registration_form" <?php echo $woocommerce_opacity; ?>><?php _e( 'WooCommerce Registration', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-6">
			<input id="wc_reset_password_form" type="checkbox" name="c4wp_options[enable_form_settings][wc_reset_password_form]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['wc_reset_password_form'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['wc_reset_password_form'], true ); } ?> <?php echo $woocommerce_disable; ?>/><label for="wc_reset_password_form" <?php echo $woocommerce_opacity; ?>><?php _e( 'WooCommerce Reset Password', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-4">
			<input id="wc_checkout_form" type="checkbox" name="c4wp_options[enable_form_settings][wc_checkout_form]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['wc_checkout_form'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['wc_checkout_form'], true ); } ?> <?php echo $woocommerce_disable; ?>/><label for="wc_checkout_form" <?php echo $woocommerce_opacity; ?>><?php _e( 'WooCommerce Checkout', 'wp-captcha' ); ?></label>
		</div>
	</div>				
</div>