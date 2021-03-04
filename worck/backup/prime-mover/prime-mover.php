<?php

/*
Plugin Name: Prime Mover
Plugin URI: https://codexonics.com/
Description: The simplest all-around WordPress migration tool/backup plugin. These support multisite backup/migration or clone WP site/multisite subsite.
Version: 1.2.8
Author: Codexonics
Author URI: https://codexonics.com/
Text Domain: prime-mover
Network: True
*/
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'pm_fs' ) ) {
    pm_fs()->set_basename( false, __FILE__ );
} else {
    
    if ( !function_exists( 'pm_fs' ) ) {
        // Create a helper function for easy SDK access.
        function pm_fs()
        {
            global  $pm_fs ;
            
            if ( !isset( $pm_fs ) ) {
                // Activate multisite network integration.
                if ( !defined( 'WP_FS__PRODUCT_3826_MULTISITE' ) ) {
                    define( 'WP_FS__PRODUCT_3826_MULTISITE', true );
                }
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $pm_fs = fs_dynamic_init( array(
                    'id'             => '3826',
                    'slug'           => 'prime-mover',
                    'premium_slug'   => 'prime-mover-pro',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_a69fd5401be20bf46608b1c38165b',
                    'is_premium'     => false,
                    'premium_suffix' => 'Pro',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'trial'          => array(
                    'days'               => 14,
                    'is_require_payment' => true,
                ),
                    'menu'           => array(
                    'slug'    => 'migration-panel-settings',
                    'network' => true,
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $pm_fs;
        }
        
        // Init Freemius.
        pm_fs();
        // Signal that SDK was initiated.
        do_action( 'pm_fs_loaded' );
    }
    
    if ( !function_exists( 'primeMoverGetConfigurationPath' ) ) {
        function primeMoverGetConfigurationPath()
        {
            
            if ( file_exists( ABSPATH . 'wp-config.php' ) ) {
                return ABSPATH . 'wp-config.php';
            } elseif ( @file_exists( dirname( ABSPATH ) . '/wp-config.php' ) && !@file_exists( dirname( ABSPATH ) . '/wp-settings.php' ) ) {
                return dirname( ABSPATH ) . '/wp-config.php';
            } else {
                return '';
            }
        
        }
    
    }
    if ( defined( 'PRIME_MOVER_PLUGIN_PATH' ) ) {
        return;
    }
    define( 'PRIME_MOVER_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
    if ( defined( 'PRIME_MOVER_PLUGIN_CORE_PATH' ) ) {
        return;
    }
    define( 'PRIME_MOVER_SHELL_ARCHIVER_FILENAME', 'prime-mover-shell-archiver.php' );
    define( 'PRIME_MOVER_MUST_USE_PLUGIN_FILENAME', 'prime-mover-cli-plugin-manager.php' );
    define( 'PRIME_MOVER_PLUGIN_CORE_PATH', dirname( PRIME_MOVER_PLUGIN_PATH ) . DIRECTORY_SEPARATOR );
    define( 'PRIME_MOVER_WPRIME_CONFIG', 'wprime-config.json' );
    define( 'PRIME_MOVER_WPRIME_CLOSED_IDENTIFIER', 'wprime-readme.txt' );
    if ( !defined( 'PRIME_MOVER_DONT_TRACK_USERIP' ) ) {
        define( 'PRIME_MOVER_DONT_TRACK_USERIP', true );
    }
    if ( defined( 'PRIME_MOVER_PLUGIN_UTILITIES_PATH' ) ) {
        return;
    }
    define( 'PRIME_MOVER_PLUGIN_UTILITIES_PATH', PRIME_MOVER_PLUGIN_PATH . 'utilities' . DIRECTORY_SEPARATOR );
    if ( defined( 'PRIME_MOVER_THEME_CORE_PATH' ) ) {
        return;
    }
    define( 'PRIME_MOVER_THEME_CORE_PATH', get_theme_root() );
    if ( !defined( 'PRIME_MOVER_PLUGIN_URI' ) ) {
        define( 'PRIME_MOVER_PLUGIN_URI', 'https://codexonics.com/' );
    }
    if ( !defined( 'CODEXONICS_CONTACT' ) ) {
        define( 'CODEXONICS_CONTACT', 'https://codexonics.com/contact/' );
    }
    if ( !defined( 'CODEXONICS_DOCUMENTATION' ) ) {
        define( 'CODEXONICS_DOCUMENTATION', 'https://codexonics.com/prime_mover/prime-mover/' );
    }
    if ( !defined( 'PRIME_MOVER_PLUGIN_FILE' ) ) {
        define( 'PRIME_MOVER_PLUGIN_FILE', basename( __FILE__ ) );
    }
    if ( !defined( 'PRIME_MOVER_SECURE_PROTOCOL' ) ) {
        define( 'PRIME_MOVER_SECURE_PROTOCOL', 'https://' );
    }
    if ( !defined( 'PRIME_MOVER_NON_SECURE_PROTOCOL' ) ) {
        define( 'PRIME_MOVER_NON_SECURE_PROTOCOL', 'http://' );
    }
    if ( !defined( 'PRIME_MOVER_UPLOADRETRY_LIMIT' ) ) {
        define( 'PRIME_MOVER_UPLOADRETRY_LIMIT', 75 );
    }
    if ( !defined( 'PRIME_MOVER_TOTAL_WAITING_ERROR_SECONDS' ) ) {
        define( 'PRIME_MOVER_TOTAL_WAITING_ERROR_SECONDS', 120 );
    }
    if ( !defined( 'PRIME_MOVER_JS_ERROR_ANALYSIS' ) ) {
        define( 'PRIME_MOVER_JS_ERROR_ANALYSIS', false );
    }
    if ( !defined( 'PRIME_MOVER_UPLOAD_REFRESH_INTERVAL' ) ) {
        define( 'PRIME_MOVER_UPLOAD_REFRESH_INTERVAL', 20000 );
    }
    if ( !defined( 'PRIME_MOVER_UPLOAD_REFRESH_INTERVAL_LOCAL' ) ) {
        define( 'PRIME_MOVER_UPLOAD_REFRESH_INTERVAL_LOCAL', 5000 );
    }
    if ( !defined( 'PRIME_MOVER_BATCH_COPY_MEDIA_ARCHIVE' ) ) {
        define( 'PRIME_MOVER_BATCH_COPY_MEDIA_ARCHIVE', 100 );
    }
    if ( !defined( 'PRIME_MOVER_DEFAULT_FREE_BASENAME' ) ) {
        define( 'PRIME_MOVER_DEFAULT_FREE_BASENAME', 'prime-mover/prime-mover.php' );
    }
    if ( !defined( 'PRIME_MOVER_DEFAULT_PRO_BASENAME' ) ) {
        define( 'PRIME_MOVER_DEFAULT_PRO_BASENAME', 'prime-mover-pro/prime-mover.php' );
    }
    if ( !defined( 'PRIME_MOVER_COPYMEDIA_SCRIPT' ) ) {
        define( 'PRIME_MOVER_COPYMEDIA_SCRIPT', PRIME_MOVER_PLUGIN_PATH . 'scripts' . DIRECTORY_SEPARATOR . PRIME_MOVER_SHELL_ARCHIVER_FILENAME );
    }
    if ( !defined( 'PRIME_MOVER_MUST_USE_PLUGIN_SCRIPT' ) ) {
        define( 'PRIME_MOVER_MUST_USE_PLUGIN_SCRIPT', PRIME_MOVER_PLUGIN_PATH . 'scripts' . DIRECTORY_SEPARATOR . PRIME_MOVER_MUST_USE_PLUGIN_FILENAME );
    }
    if ( !defined( 'PRIME_MOVER_LARGE_FILESIZE' ) ) {
        define( 'PRIME_MOVER_LARGE_FILESIZE', 104857600 );
    }
    if ( !defined( 'PRIME_MOVER_CRON_TEST_MODE' ) ) {
        define( 'PRIME_MOVER_CRON_TEST_MODE', false );
    }
    if ( !defined( 'PRIME_MOVER_CRON_DELETE_TMP_INTERVALS' ) ) {
        define( 'PRIME_MOVER_CRON_DELETE_TMP_INTERVALS', 86400 );
    }
    if ( !defined( 'PRIME_MOVER_RETRY_TIMEOUT_SECONDS' ) ) {
        define( 'PRIME_MOVER_RETRY_TIMEOUT_SECONDS', 15 );
    }
    if ( !defined( 'PRIME_MOVER_TEST_CORE_DOWNLOAD' ) ) {
        define( 'PRIME_MOVER_TEST_CORE_DOWNLOAD', false );
    }
    if ( !defined( 'PRIME_MOVER_RESTORE_URL_DOC' ) ) {
        define( 'PRIME_MOVER_RESTORE_URL_DOC', 'https://codexonics.com/prime_mover/prime-mover/how-to-export-and-restore-using-pro-version/' );
    }
    if ( !defined( 'PRIME_MOVER_PRICING_PAGE' ) ) {
        define( 'PRIME_MOVER_PRICING_PAGE', 'https://codexonics.com/prime_mover/prime-mover/pricing/' );
    }
    if ( !defined( 'PRIME_MOVER_GET_BLOGID_TUTORIAL' ) ) {
        define( 'PRIME_MOVER_GET_BLOGID_TUTORIAL', 'https://codexonics.com/prime_mover/prime-mover/how-to-get-multisite-target-blog-id/' );
    }
    if ( !defined( 'PRIME_MOVER_CLI_TIMEOUT_SECONDS' ) ) {
        define( 'PRIME_MOVER_CLI_TIMEOUT_SECONDS', 450 );
    }
    if ( !defined( 'PRIME_MOVER_PHPDUMP_BATCHSIZE' ) ) {
        define( 'PRIME_MOVER_PHPDUMP_BATCHSIZE', 500 );
    }
    if ( !defined( 'PRIME_MOVER_THRESHOLD_BYTES_MEDIA' ) ) {
        define( 'PRIME_MOVER_THRESHOLD_BYTES_MEDIA', 52428800 );
    }
    if ( !defined( 'PRIME_MOVER_THRESHOLD_BYTES_PLUGIN' ) ) {
        define( 'PRIME_MOVER_THRESHOLD_BYTES_PLUGIN', 15728640 );
    }
    if ( !defined( 'PRIME_MOVER_ENABLE_FILE_LOG' ) ) {
        define( 'PRIME_MOVER_ENABLE_FILE_LOG', true );
    }
    if ( !defined( 'PRIME_MOVER_STREAM_COPY_CHUNK_SIZE' ) ) {
        define( 'PRIME_MOVER_STREAM_COPY_CHUNK_SIZE', 1048576 );
    }
    if ( !defined( 'PRIME_MOVER_CURL_BUFFER_SIZE' ) ) {
        define( 'PRIME_MOVER_CURL_BUFFER_SIZE', 16384 );
    }
    if ( !defined( 'PRIME_MOVER_SLOW_WEB_HOST' ) ) {
        define( 'PRIME_MOVER_SLOW_WEB_HOST', true );
    }
    if ( !defined( 'PRIME_MOVER_CORE_GEARBOX_CURL_TIMEOUT' ) ) {
        define( 'PRIME_MOVER_CORE_GEARBOX_CURL_TIMEOUT', 300 );
    }
    if ( !defined( 'PRIME_MOVER_CACHE_DB_FILE' ) ) {
        define( 'PRIME_MOVER_CACHE_DB_FILE', WP_CONTENT_DIR . '/db.php' );
    }
    if ( !defined( 'PRIME_MOVER_OBJECT_CACHE_FILE' ) ) {
        define( 'PRIME_MOVER_OBJECT_CACHE_FILE', WP_CONTENT_DIR . '/object-cache.php' );
    }
    define( 'PRIME_MOVER_VERSION', '1.2.8' );
    define( 'PRIME_MOVER_PLUGIN_CODENAME', 'Prime Mover' );
    define( 'PRIME_MOVER_BACKUP_MARKUP_VERSION', '1.1.5' );
    define( 'PRIME_MOVER_MAINPLUGIN_FILE', __FILE__ );
    include dirname( __FILE__ ) . '/dependency-checks/PrimeMoverPHPVersionDependencies.php';
    include dirname( __FILE__ ) . '/dependency-checks/PrimeMoverWPCoreDepedencies.php';
    include dirname( __FILE__ ) . '/dependency-checks/PrimeMoverRequirementsCheck.php';
    include dirname( __FILE__ ) . '/dependency-checks/PrimeMoverPHPCoreFunctionDependencies.php';
    include dirname( __FILE__ ) . '/dependency-checks/PrimeMoverFileSystemDependencies.php';
    include dirname( __FILE__ ) . '/dependency-checks/PrimeMoverPluginSlugDependencies.php';
    include dirname( __FILE__ ) . '/dependency-checks/PrimeMoverCoreSaltDependencies.php';
    $phpverdependency = new PrimeMoverPHPVersionDependencies( '5.6' );
    $wpcoredependency = new PrimeMoverWPCoreDependencies( '4.9.8' );
    $phpfuncdependency = new PrimeMoverPHPCoreFunctionDependencies();
    $foldernamedependency = new PrimeMoverPluginSlugDependencies( array( PRIME_MOVER_DEFAULT_FREE_BASENAME, PRIME_MOVER_DEFAULT_PRO_BASENAME ) );
    $coresaltdependency = new PrimeMoverCoreSaltDependencies();
    $required_paths = array(
        ABSPATH,
        PRIME_MOVER_PLUGIN_CORE_PATH,
        PRIME_MOVER_PLUGIN_PATH,
        PRIME_MOVER_THEME_CORE_PATH,
        get_stylesheet_directory(),
        primeMoverGetConfigurationPath()
    );
    $wp_upload_dir = wp_upload_dir();
    if ( !empty($wp_upload_dir['basedir']) ) {
        $required_paths[] = $wp_upload_dir['basedir'];
    }
    if ( !empty($wp_upload_dir['path']) ) {
        $required_paths[] = $wp_upload_dir['path'];
    }
    $filesystem_dependency = new PrimeMoverFileSystemDependencies( $required_paths );
    $multisitemigrationcheck = new PrimeMoverRequirementsCheck(
        $phpverdependency,
        $wpcoredependency,
        $phpfuncdependency,
        $filesystem_dependency,
        $foldernamedependency,
        $coresaltdependency
    );
    if ( !$multisitemigrationcheck->passes() ) {
        return;
    }
    include dirname( __FILE__ ) . '/PrimeMoverLoader.php';
    if ( file_exists( PRIME_MOVER_PLUGIN_PATH . '/vendor/autoload.php' ) ) {
        require_once PRIME_MOVER_PLUGIN_PATH . '/vendor/autoload.php';
    }
    include dirname( __FILE__ ) . '/PrimeMoverFactory.php';
}
