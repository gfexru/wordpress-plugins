<?php
/**
 * Fired when the plugin is uninstalled.
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma <devnathverma@gmail.com>
 */

/*----------------------------------------------------------------------------*
 * If uninstall not called from WordPress, then exit.
 *-----------------------------------------------------------------------------*/

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {

    exit;
}

/**
 * If it's a multisite, loop over all the blogs where the plugin is activated and delete the options from the DB.
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma <devnathverma@gmail.com>
 * @uses 	 delete_option()
 */
if ( is_multisite() ) {
    
	global $wpdb;
    $c4wp_blogs = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A);

    if( !empty( $c4wp_blogs ) ) {
	
        foreach( $c4wp_blogs as $c4wp_blog ) {
		
            switch_to_blog( $c4wp_blog['blog_id'] );
            delete_option( 'c4wp_default_settings' );
        }
	}

} else {

    delete_option( 'c4wp_default_settings' );
}