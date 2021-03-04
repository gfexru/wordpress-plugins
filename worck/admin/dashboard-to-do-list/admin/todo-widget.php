<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( !function_exists( 'ardtdw_widgetsetup' ) ) {
function ardtdw_widgetsetup() {
	wp_add_dashboard_widget('ardtdw', 'Website To-Do List', 'ardtdw_widget');
}
add_action('wp_dashboard_setup', 'ardtdw_widgetsetup');
}

if ( !function_exists( 'ardtdw_widgetupdate' ) ) {
function ardtdw_widgetupdate(){
	if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
		if( isset($_POST['ardtdw-save']) || wp_verify_nonce( $_POST['ardtdw_confirm'], 'ardtdw_update_list' ) ) {
			if(isset ($_POST['ardtdw-textarea'])) {
				update_option(
					'ardtdw-textarea',
					wp_kses($_POST['ardtdw-textarea'],
					array(
				    'a' => array(
				        'href' => array(),
				        'target' => array(),
				        'title' => array()
				    ),
				    'em' => array(),
				    'strong' => array(),
				    'b' => array(),
				    'u' => array(),
					)
				),
				'',
				'yes'
			);
			}

			if ( isset( $_POST['ardtdw-checkbox'] ) ) {
				$ardtdw_checkbox = $_POST['ardtdw-checkbox'];
			} else {
				$ardtdw_checkbox = '';
			}

			if ( isset( $_POST['ardtdw-position'] ) ) {
				$ardtdw_position = $_POST['ardtdw-position'];
			} else {
				$ardtdw_position = '';
			}

			if ($ardtdw_checkbox) {
				if (empty($_POST['ardtdw-textarea'])) { ?>
					<div class="ardtdw-message ardtdw-error">
					<p><?php _e( 'You must have at least one to-do in your list to display it on the website!','dashboard-to-do-list'); ?></p>
				</div>
				<?php
				$ardtdw_checkbox = '';
			} else { ?>
				<div class="ardtdw-message ardtdw-updated">
					<p><?php _e( 'To-Do list updated. List now shows on the website.','dashboard-to-do-list'); ?></p>
				</div>
				<?php
			}
		} else { ?>
			<div class="ardtdw-message ardtdw-updated">
				<p><?php _e( 'To-Do list updated.','dashboard-to-do-list'); ?></p>
			</div>
			<?php
		}

			update_option('ardtdw-checkbox', absint($ardtdw_checkbox));
			update_option('ardtdw-position', $ardtdw_position);

		}
	}
}
}

if ( !function_exists( 'ardtdw_widget' ) ) {
function ardtdw_widget() {
	ardtdw_widgetupdate();
	$ardtdw_callbackURL = get_site_url();
	$ardtdw_TextArea = stripslashes(get_option('ardtdw-textarea'));
	$ardtdw_CheckBox = get_option('ardtdw-checkbox');
	$ardtdw_Position = get_option('ardtdw-position');
	$ardtdw_Position = (empty($ardtdw_Position) || $ardtdw_Position == '0' || $ardtdw_Position == '' || $ardtdw_Position == 'undefined' ) ? 'right' : get_option('ardtdw-position');
	?>
	<form action='<?php echo $ardtdw_callbackURL ?>/wp-admin/index.php' method='post'>

		<textarea name='ardtdw-textarea' id='ardtdw-textarea' rows='10'><?php echo esc_html($ardtdw_TextArea) ?></textarea><p/>
		<p class='field-comment'><?php _e( 'One to-do per line. Accepts the following HTML tags: a (href, title, target), em, strong, b, u.','dashboard-to-do-list'); ?></p>
		<p><label for='ardtdw-checkbox'><input name='ardtdw-checkbox' type='checkbox' id='ardtdw-checkbox' value='1' <?php checked( esc_html($ardtdw_CheckBox), true) ?> /><?php _e( 'Show list on website','dashboard-to-do-list'); ?></label></p>
		<p>
			<strong><?php _e( 'List Position:','dashboard-to-do-list'); ?></strong></br>

			<label><input type="radio" name="ardtdw-position" value="left" <?php echo ($ardtdw_Position == 'left') ? 'checked' : ''; ?>> <?php _e( 'Left aligned', 'dashboard-to-do-list' ); ?></label>
			&nbsp;&nbsp;
			<label><input type="radio" name="ardtdw-position" value="right" <?php echo ($ardtdw_Position == 'right') ? 'checked' : ''; ?>> <?php _e( 'Right aligned', 'dashboard-to-do-list' ); ?></label>
		</p>
		<input type='submit' value='<?php _e( 'Save','dashboard-to-do-list'); ?>' class='button-primary' name='ardtdw-save'>
		<?php wp_nonce_field( 'ardtdw_update_list', 'ardtdw_confirm' ); ?>
	</form>
	<?php
}
}

if (get_option('ardtdw-checkbox') && get_option('ardtdw-textarea')) {
	if ( !function_exists( 'ardtdw_widgethtml' ) ) {
	function ardtdw_widgethtml() {
		if( current_user_can('administrator') ) {
		ardtdw_widget_html();
	}
	}
	add_action('wp_footer', 'ardtdw_widgethtml');
}
}
