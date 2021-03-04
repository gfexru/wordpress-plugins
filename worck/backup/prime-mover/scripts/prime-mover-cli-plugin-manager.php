<?php
/** 
 * MUST USE PLUGIN
 * This is automatically added by Prime Mover Plugin to the 'mu-plugins' directory
 * 
 * This code will only run when doing export and import process in CLI mode with Prime Mover
 * This is automatically updated also when Prime Mover plugin is updated.
 */
if (! defined('PRIME_MOVER_DEFAULT_FREE_BASENAME')) {
    define('PRIME_MOVER_DEFAULT_FREE_BASENAME', 'prime-mover/prime-mover.php');
}

if (! defined('PRIME_MOVER_DEFAULT_PRO_BASENAME')) {
    define('PRIME_MOVER_DEFAULT_PRO_BASENAME', 'prime-mover-pro/prime-mover.php');
}

const PRIME_MOVER_CORE_EXPORT_PROCESSES = ['prime_mover_process_export', 'prime_mover_monitor_export_progress', 'prime_mover_shutdown_export_process'];

if (primeMoverMaybeLoadPluginManager()) {    
  
    add_filter('site_option_active_sitewide_plugins', 'loadOnlyPrimeMoverPlugin');    
    add_filter('option_active_plugins', 'loadOnlyPrimeMoverPlugin');     
    
    function loadOnlyPrimeMoverPlugin($plugins)
    {        
        $required = [PRIME_MOVER_DEFAULT_PRO_BASENAME, PRIME_MOVER_DEFAULT_FREE_BASENAME, 'memory-usage/memory_usage.php'];
        
        if ('site_option_active_sitewide_plugins' === current_filter()) {            
            $plugins = array_filter(
                $plugins,
                function ($key) use ($required) {
                    return in_array($key, $required);
                },
                ARRAY_FILTER_USE_KEY
                );
        }
        
        if ('option_active_plugins' === current_filter()) {
            $plugins = array_filter($plugins, function($plugin) {
                return (in_array($plugin, [PRIME_MOVER_DEFAULT_PRO_BASENAME, PRIME_MOVER_DEFAULT_FREE_BASENAME, 'memory-usage/memory_usage.php']));
            });
        }
        
        $mode = 'AJAX';
        if ('cli' == php_sapi_name()) {
            $mode = 'CLI';
        }
            
        @error_log("Filtered plugins in $mode mode: " . PRIME_MOVER_SHELL_PROCESS_MODE . PHP_EOL, 3, PRIME_MOVER_SHELL_ERROR_LOG_FILE);
        @error_log(print_r($plugins, true)  . PHP_EOL, 3, PRIME_MOVER_SHELL_ERROR_LOG_FILE);
        
        return $plugins;   
    }
}

function primeMoverMaybeLoadPluginManager()
{    
    if ('cli' == php_sapi_name() && defined('PRIME_MOVER_COPY_MEDIA_SHELL_USER') && defined('PRIME_MOVER_DOING_SHELL_ARCHIVE') && PRIME_MOVER_DOING_SHELL_ARCHIVE &&
        defined('PRIME_MOVER_SHELL_ERROR_LOG_FILE') && PRIME_MOVER_SHELL_ERROR_LOG_FILE && defined('PRIME_MOVER_SHELL_PROCESS_MODE') && PRIME_MOVER_SHELL_PROCESS_MODE) {
        return true;
    }
    $input_post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    if ( defined( 'DOING_AJAX' ) && DOING_AJAX && isset($input_post['action'] ) && in_array($input_post['action'], PRIME_MOVER_CORE_EXPORT_PROCESSES)) {
        return true;
    }
    
    return false;
}