<?php
/**
 * This Files Print Icon Captcha Settings HTML of WP Captcha Plugin in admin Section.
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma <devnathverma@gmail.com>
 */
?>

<?php if ( isset( $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] ) && $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'icon_captcha' ) { ?>	
<div class="col-10 c4wp-icon-captcha-settings-content">
<?php } else if ( isset( $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] ) && $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'mathematical_captcha' ) { ?>
<div class="col-10 c4wp-icon-captcha-settings-content" style="display:none;">
<?php }	else if ( isset( $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] ) && $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'image_captcha' ) { ?>
<div class="col-10 c4wp-icon-captcha-settings-content" style="display:none;">
<?php }	else if ( isset( $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] ) && $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'google_recaptcha' ) { ?>
<div class="col-10 c4wp-icon-captcha-settings-content" style="display:none;">
<?php } else ?>
	<div class="row">
		<div class="form-group col-md-3">
			<label for="number_of_icons"><?php _e( 'Number of Icons', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-8">
			<select id="number_of_icons" class="form-control" name="c4wp_options[icon_captcha_setting][number_of_icons]">
				<option value="1" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['number_of_icons'], "1" ); ?>><?php _e( '1', 'wp-captcha' ); ?></option>
				<option value="2" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['number_of_icons'], "2" ); ?>><?php _e( '2', 'wp-captcha' ); ?></option>
				<option value="3" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['number_of_icons'], "3" ); ?>><?php _e( '3', 'wp-captcha' ); ?></option>
				<option value="4" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['number_of_icons'], "4" ); ?>><?php _e( '4', 'wp-captcha' ); ?></option>
				<option value="5" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['number_of_icons'], "5" ); ?>><?php _e( '5', 'wp-captcha' ); ?></option>
				<option value="6" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['number_of_icons'], "6" ); ?>><?php _e( '6', 'wp-captcha' ); ?></option>
				<option value="7" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['number_of_icons'], "7" ); ?>><?php _e( '7', 'wp-captcha' ); ?></option>
				<option value="8" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['number_of_icons'], "8" ); ?>><?php _e( '8', 'wp-captcha' ); ?></option>
				<option value="9" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['number_of_icons'], "9" ); ?>><?php _e( '9', 'wp-captcha' ); ?></option>
				<option value="10" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['number_of_icons'], "10" ); ?>><?php _e( '10', 'wp-captcha' ); ?></option>
			</select>
			<span class="description"><?php _e( 'Number of Icons Display on Captcha.', 'wp-captcha' ); ?></span>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-3">
			<label for="border_width"><?php _e( 'Captcha Border', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-3">
			<select id="border_width" class="form-control" name="c4wp_options[icon_captcha_setting][border_width]">
				<option value="1px" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['border_width'], "1px" ); ?>><?php _e( '1px', 'wp-captcha' ); ?></option>
				<option value="2px" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['border_width'], "2px" ); ?>><?php _e( '2px', 'wp-captcha' ); ?></option>
				<option value="3px" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['border_width'], "3px" ); ?>><?php _e( '3px', 'wp-captcha' ); ?></option>
				<option value="4px" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['border_width'], "4px" ); ?>><?php _e( '4px', 'wp-captcha' ); ?></option>
				<option value="5px" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['border_width'], "5px" ); ?>><?php _e( '5px', 'wp-captcha' ); ?></option>
				<option value="6px" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['border_width'], "6px" ); ?>><?php _e( '6px', 'wp-captcha' ); ?></option>
				<option value="7px" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['border_width'], "7px" ); ?>><?php _e( '7px', 'wp-captcha' ); ?></option>
				<option value="8px" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['border_width'], "8px" ); ?>><?php _e( '8px', 'wp-captcha' ); ?></option>
				<option value="9px" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['border_width'], "9px" ); ?>><?php _e( '9px', 'wp-captcha' ); ?></option>
				<option value="10px" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['border_width'], "10px" ); ?>><?php _e( '10px', 'wp-captcha' ); ?></option>
			</select>
			<span class="description"><?php _e( 'Width of border in pixels.', 'wp-captcha' ); ?></span>
		</div>
		<div class="form-group col-md-3">
			<select id="border_style" class="form-control" name="c4wp_options[icon_captcha_setting][border_style]">
				<option value="none" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['border_style'], "none" ); ?>><?php _e( 'None', 'wp-captcha' ); ?></option>
				<option value="medium" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['border_style'], "medium" ); ?>><?php _e( 'Medium', 'wp-captcha' ); ?></option>
				<option value="thin" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['border_style'], "thin" ); ?>><?php _e( 'Thin', 'wp-captcha' ); ?></option>
				<option value="thik" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['border_style'], "thik" ); ?>><?php _e( 'Thik', 'wp-captcha' ); ?></option>
				<option value="dashed" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['border_style'], "dashed" ); ?>><?php _e( 'Dashed', 'wp-captcha' ); ?></option>
				<option value="dotted" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['border_style'], "dotted" ); ?>><?php _e( 'Dotted', 'wp-captcha' ); ?></option>
				<option value="double" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['border_style'], "double" ); ?>><?php _e( 'Double', 'wp-captcha' ); ?></option>
				<option value="groove" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['border_style'], "groove" ); ?>><?php _e( 'Groove', 'wp-captcha' ); ?></option>
				<option value="outset" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['border_style'], "outset" ); ?>><?php _e( 'Outset', 'wp-captcha' ); ?></option>
				<option value="solid" <?php selected( $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['border_style'], "solid" ); ?>><?php _e( 'Solid', 'wp-captcha' ); ?></option>
			</select>	
			<span class="description"><?php _e( 'Style of border in pixels.', 'wp-captcha' ); ?></span>
		</div>
		<div class="form-group col-md-3">
			<input type="text" class="c4wp-color-field" id="border_color" name="c4wp_options[icon_captcha_setting][border_color]" value="<?php echo $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['border_color']; ?>"/><br />
			<span class="description"><?php _e( 'The Color of Border.', 'wp-captcha' ); ?></span>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-3">
			<label for="text_color"><?php _e( 'Icons Color', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-3">
			<input type="text" class="c4wp-color-field" id="icon_color" name="c4wp_options[icon_captcha_setting][icon_color]" value="<?php echo $c4wp_plugin_options['c4wp_options']['icon_captcha_setting']['icon_color']; ?>"/><br />
			<span class="description"><?php _e( 'The Color of Icons.', 'wp-captcha' ); ?></span>
		</div>
	</div>
</div>