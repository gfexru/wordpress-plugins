<?php
/**
 * This Files Print Image Captcha Settings HTML of WP Captcha Plugin in admin Section.
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma <devnathverma@gmail.com>
 */	
?>

<?php if ( isset( $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] ) && $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'image_captcha' ) { ?>	
<div class="col-10 c4wp-image-captcha-settings-content">
<?php } else if ( isset( $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] ) && $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'mathematical_captcha' ) { ?>
<div class="col-10 c4wp-image-captcha-settings-content" style="display:none;">
<?php }	else if ( isset( $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] ) && $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'icon_captcha' ) { ?>
<div class="col-10 c4wp-image-captcha-settings-content" style="display:none;">
<?php } else if ( isset( $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] ) && $c4wp_plugin_options['c4wp_options']['captcha_enable_for'] == 'google_recaptcha' ) { ?>
<div class="col-10 c4wp-image-captcha-settings-content" style="display:none;">
<?php } ?>
	<div class="row">
		<div class="form-group col-md-2">
			<label for="image_captcha_widht"><?php _e( 'Width (px)', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-4">
			<input type="text" class="form-control" id="image_captcha_widht" name="c4wp_options[image_captcha_setting][widht]" value="<?php echo $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['widht']; ?>"/>
			<span class="description"><?php _e( 'The Width of Image Captcha in Pixels.', 'wp-captcha' ); ?></span>
		</div>
		<div class="form-group col-md-2">
			<label for="image_captcha_height"><?php _e( 'Height (px)', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-4">
			<input type="text" class="form-control" id="image_captcha_height" name="c4wp_options[image_captcha_setting][height]" value="<?php echo $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['height']; ?>"/>
			<span class="description"><?php _e( 'The Height of Image Captcha in Pixels.', 'wp-captcha' ); ?></span>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-2">
			<label for="random_lines"><?php _e( 'Random Lines', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-4">
			<input type="text" class="form-control" id="random_lines" name="c4wp_options[image_captcha_setting][random_lines]" value="<?php echo $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['random_lines']; ?>"/>
			<span class="description"><?php _e( 'Random Number of Lines Display on Image Captcha.', 'wp-captcha' ); ?></span>
		</div>
		<div class="form-group col-md-2">
			<label for="random_dots"><?php _e( 'Random Dots', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-4">
			<input type="text" class="form-control" id="random_dots" name="c4wp_options[image_captcha_setting][random_dots]" value="<?php echo $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['random_dots']; ?>"/>
			<span class="description"><?php _e( 'Random Number of Dots Display on Image Captcha.', 'wp-captcha' ); ?></span>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-2">
			<label for="text_color"><?php _e( 'Text Color', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-4">
			<input type="text" class="c4wp-color-field" id="text_color" name="c4wp_options[image_captcha_setting][text_color]" value="<?php echo $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['text_color']; ?>"/><br />
			<span class="description"><?php _e( 'The Color of Text used in Image Captcha.', 'wp-captcha' ); ?></span>
		</div>
		<div class="form-group col-md-2">
			<label for="noise_color"><?php _e( 'Noise Color', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-4">
			<input type="text" class="c4wp-color-field" id="noise_color" name="c4wp_options[image_captcha_setting][noise_color]" value="<?php echo $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['noise_color']; ?>"/><br />
			<span class="description"><?php _e( 'The Color of Noise used in Image Captcha.', 'wp-captcha' ); ?></span>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-2">
			<label for="character_types"><?php _e( 'Character Types', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-4">
			<select id="character_types" class="form-control" name="c4wp_options[image_captcha_setting][character_types]">
				<option value="only_numbers" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['character_types'], "only_numbers" ); ?>><?php _e( 'Only Numbers', 'wp-captcha' ); ?></option>
				<option value="only_alphabets" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['character_types'], "only_alphabets" ); ?>><?php _e( 'Only Alphabets', 'wp-captcha' ); ?></option>
				<option value="alphabets_and_numbers" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['character_types'], "alphabets_and_numbers" ); ?>><?php _e( 'Alphabets And Digits', 'wp-captcha' ); ?></option>
			</select>
			<span class="description"><?php _e( 'Different Types available for creating Image Captcha.', 'wp-captcha' ); ?></span>
		</div>
		<div class="form-group col-md-2">
			<label for="text_case"><?php _e( 'Text Case', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-4">
			<select id="text_case" class="form-control" name="c4wp_options[image_captcha_setting][text_case]">
				<option value="lower_case" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['text_case'], "lower_case" ); ?>><?php _e( 'Lower Case', 'wp-captcha' ); ?></option>
				<option value="upper_case" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['text_case'], "upper_case" ); ?>><?php _e( 'Upper Case', 'wp-captcha' ); ?></option>
				<option value="mixed" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['text_case'], "mixed" ); ?>><?php _e( 'Mixed (Lower & Upper Case)', 'wp-captcha' ); ?></option>
			</select>
			<span class="description"><?php _e( 'Text Case available for Image Captcha.', 'wp-captcha' ); ?></span>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-2">
			<label for="characters_on_image"><?php _e( 'Number of Characters', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-4">
			<select id="characters_on_image" class="form-control" name="c4wp_options[image_captcha_setting][characters_on_image]">
				<option value="1" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['characters_on_image'], "1" ); ?>><?php _e( '1', 'wp-captcha' ); ?></option>
				<option value="2" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['characters_on_image'], "2" ); ?>><?php _e( '2', 'wp-captcha' ); ?></option>
				<option value="3" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['characters_on_image'], "3" ); ?>><?php _e( '3', 'wp-captcha' ); ?></option>
				<option value="4" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['characters_on_image'], "4" ); ?>><?php _e( '4', 'wp-captcha' ); ?></option>
				<option value="5" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['characters_on_image'], "5" ); ?>><?php _e( '5', 'wp-captcha' ); ?></option>
				<option value="6" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['characters_on_image'], "6" ); ?>><?php _e( '6', 'wp-captcha' ); ?></option>
				<option value="7" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['characters_on_image'], "7" ); ?>><?php _e( '7', 'wp-captcha' ); ?></option>
				<option value="8" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['characters_on_image'], "8" ); ?>><?php _e( '8', 'wp-captcha' ); ?></option>
				<option value="9" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['characters_on_image'], "9" ); ?>><?php _e( '9', 'wp-captcha' ); ?></option>
				<option value="10" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['characters_on_image'], "10" ); ?>><?php _e( '10', 'wp-captcha' ); ?></option>
			</select>
			<span class="description"><?php _e( 'Number of Characters Display on Image Captcha.', 'wp-captcha' ); ?></span>
		</div>
		<div class="form-group col-md-2">
			<label for="select_fonts"><?php _e( 'Select Fonts', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-4">
			<select id="select_fonts" class="form-control" name="c4wp_options[image_captcha_setting][select_fonts]">
				<?php 
				$c4wp_folder_path = dirname( dirname( dirname( __FILE__ ) ) ) .'/assets/fonts/';
				$c4wp_list_files = WP_CAPTCHA()->c4wp_return_setting_object->c4wp_list_files( $c4wp_folder_path );								
				if( !empty($c4wp_list_files) ) {
					foreach( $c4wp_list_files as $key => $c4wp_list_file ) {
				?>
						<option value="<?php echo $key; ?>" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['select_fonts'], $key ); ?>><?php _e( $c4wp_list_file, 'wp-captcha' ); ?></option>
				<?php } } ?>	
			</select>
			<span class="description"><?php _e( 'Select font which you want Display on Image Captcha.', 'wp-captcha' ); ?></span>
		</div>
	</div>
</div>