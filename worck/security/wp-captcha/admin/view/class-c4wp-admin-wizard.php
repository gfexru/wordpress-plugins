<?php
/**
 * This class Prints Admin Wizard HTML of WP Captcha Plugin in admin Section.
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma <devnathverma@gmail.com>
 */
 
class C4WP_Admin_Wizard {
		
	/**
	 * This class Prints Admin Wizard HTML of WP Captcha Plugin in admin Section.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public static function c4wp_wizard( $c4wp_plugin_options, $c4wp_messages ) {	?>

		<div class="container-fluid">
			<div class="row justify-content-center">
				<div class="pt-4 col-md-12">
					<img src="<?php echo C4WP_PLUGIN_IMAGES; ?>c4wp-logo.jpg" alt="branding" class="label-icon-default">
					<form id="c4wp-wizard-form" class="c4wp-wizard-form" action="<?php echo admin_url( 'admin.php' ); ?>?page=c4wp-settings" method="post">		
						<ul id="progressbar">
							<li class="active" id="c4wp-num1"><?php _e( 'Captcha Types', 'wp-captcha' ); ?></li>
							<li id="c4wp-num2"><?php _e( 'Captcha Settings', 'wp-captcha' ); ?></li>
							<li id="c4wp-num3"><?php _e( 'Captcha Forms', 'wp-captcha' ); ?></li>
							<li id="c4wp-num4"><?php _e( 'Other Settings', 'wp-captcha' ); ?></li>
						</ul>
						<div class="progress">
							<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100">	    </div>
						</div>
						<?php if( ! empty( $c4wp_messages ) ) : ?>
						<div id="message" class="c4wp-update"><?php echo $c4wp_messages; ?></div>	
						<?php endif; ?>
						<div class="c4wp-alert-success" style="display:none;"></div>
						<fieldset>
							<div class="form-card">
								<div class="row">
									<?php require_once C4WP_PLUGIN_ADMIN . 'view/c4wp-captcha-types.php'; ?>
								</div>
							</div>
							<input type="submit" name="c4wp_reset_submit" class="c4wp-reset action-button" value="Restore Settings" />
							<input type="button" name="next" class="next action-button" value="Next" />
						</fieldset>
						<fieldset>
							<div class="form-card">
								<div class="row">
									<?php require_once C4WP_PLUGIN_ADMIN . 'view/c4wp-mathematical-settings.php'; ?>
									<?php require_once C4WP_PLUGIN_ADMIN . 'view/c4wp-image-settings.php'; ?>
									<?php require_once C4WP_PLUGIN_ADMIN . 'view/c4wp-icon-settings.php'; ?>
									<?php require_once C4WP_PLUGIN_ADMIN . 'view/c4wp-recaptcha-settings.php'; ?>
								</div>
							</div>
							<input type="submit" name="c4wp_reset_submit" class="c4wp-reset action-button" value="Restore Settings" /> 
							<input type="button" name="next" class="next action-button" value="Next" />
							<input type="button" name="previous" class="previous action-button-previous" value="Previous" />
						</fieldset>
						<fieldset>
							<div class="form-card">
								<div class="row">
									<?php require_once C4WP_PLUGIN_ADMIN . 'view/c4wp-captcha-forms.php'; ?>
								</div>	
							</div>
							<input type="submit" name="c4wp_reset_submit" class="c4wp-reset action-button" value="Restore Settings" /> 
							<input type="button" name="next" class="next action-button" value="Next" /> 
							<input type="button" name="previous" class="previous action-button-previous" value="Previous" />
						</fieldset>
						<fieldset>
							<div class="form-card">
								<div class="row">
									<?php require_once C4WP_PLUGIN_ADMIN . 'view/c4wp-other-settings.php'; ?>
								</div>
							</div>
							<input type="submit" name="c4wp_reset_submit" class="c4wp-reset action-button" value="Restore Settings" />
							<input type="submit" name="c4wp_submit" class="c4wp-submit action-button" value="Save" /> 
							<input type="button" name="previous" class="previous action-button-previous" value="Previous" />
						</fieldset>
					</form>
				</div>
			</div>
		</div>
<?php } } ?>