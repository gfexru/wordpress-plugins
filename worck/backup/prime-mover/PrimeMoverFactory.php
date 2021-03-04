<?php
namespace Codexonics;

/*
 * This file is part of the Codexonics package.
 *
 * (c) Codexonics
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Codexonics\PrimeMoverFramework\classes\PrimeMoverSystemAuthorization;
use Codexonics\PrimeMoverFramework\classes\PrimeMoverSystemInitialization;
use Codexonics\PrimeMoverFramework\classes\PrimeMoverProgressHandlers;
use Codexonics\PrimeMoverFramework\classes\PrimeMoverSystemFunctions;
use Codexonics\PrimeMoverFramework\utilities\PrimeMoverSystemUtilities;
use Codexonics\PrimeMoverFramework\utilities\PrimeMoverSystemCheckUtilities;
use Codexonics\PrimeMoverFramework\classes\PrimeMoverSystemChecks;
use Codexonics\PrimeMoverFramework\utilities\PrimeMoverUploadUtilities;
use Codexonics\PrimeMoverFramework\classes\PrimeMoverImporter;
use Codexonics\PrimeMoverFramework\classes\PrimeMoverExporter;
use Codexonics\PrimeMoverFramework\utilities\PrimeMoverExportUtilities;
use Codexonics\PrimeMoverFramework\classes\PrimeMoverSystemProcessors;
use Codexonics\PrimeMoverFramework\classes\PrimeMoverHookedMethods;
use Codexonics\PrimeMoverFramework\classes\PrimeMover;
use Codexonics\PrimeMoverFramework\utilities\PrimeMoverShutdownUtilities;
use Codexonics\PrimeMoverFramework\classes\PrimeMoverErrorHandlers;
use Codexonics\PrimeMoverFramework\utilities\PrimeMoverImportUtilities;
use Codexonics\PrimeMoverFramework\utilities\PrimeMoverDownloadUtilities;
use Codexonics\PrimeMoverFramework\utilities\PrimeMoverSearchReplaceUtilities;
use Codexonics\PrimeMoverFramework\utilities\PrimeMoverLockUtilities;
use Codexonics\PrimeMoverFramework\utilities\PrimeMoverOpenSSLUtilities;
use Codexonics\PrimeMoverFramework\classes\PrimeMoverValidationHandlers;
use Codexonics\PrimeMoverFramework\utilities\PrimeMoverValidationUtilities;
use Codexonics\PrimeMoverFramework\streams\PrimeMoverStreamFilters;
use Codexonics\PrimeMoverFramework\utilities\PrimeMoverConfigUtilities;
use Codexonics\PrimeMoverFramework\utilities\PrimeMoverComponentAuxiliary;
use Codexonics\PrimeMoverFramework\utilities\PrimeMoverFreemiusIntegration;
use Codexonics\PrimeMoverFramework\compatibility\PrimeMoverCompatibility;
use Codexonics\PrimeMoverFramework\streams\PrimeMoverResumableDownloadStream;
use Codexonics\PrimeMoverFramework\streams\PrimeMoverIterators;
use Codexonics\PrimeMoverFramework\cli\PrimeMoverCLIShellArchiver;
use Codexonics\PrimeMoverFramework\cli\PrimeMoverCLIArchive;
use Codexonics\PrimeMoverFramework\menus\PrimeMoverBackupMenus;
use Codexonics\PrimeMoverFramework\menus\PrimeMoverGearBoxScreenOptions;
use Codexonics\PrimeMoverFramework\utilities\PrimeMoverBackupUtilities;
use Codexonics\PrimeMoverFramework\classes\PrimeMoverUsers;
use Codexonics\PrimeMoverFramework\utilities\PrimeMoverUserUtilities;
use Codexonics\PrimeMoverFramework\users\PrimeMoverUserFunctions;
use Codexonics\PrimeMoverFramework\users\PrimeMoverUserQueries;
use Codexonics\PrimeMoverFramework\archiver\PrimeMoverArchiver;
use Codexonics\PrimeMoverFramework\compatibility\PrimeMoverElementorCompat;
use Codexonics\PrimeMoverFramework\compatibility\PrimeMoverCachingCompat;

if (! defined('ABSPATH')) {
    exit;
}

/**
 *  Instantiate new plugin object
 */
class PrimeMoverFactory
{

    /**
     * Mode of execution, CLI or normal
     * @var boolean
     */
    private $cli = false;
    
    /**
     * Parameters passed, $argv for CLI
     * @var array
     */
    private $parameters = [];
    
    /**
     * Constructor
     * @param boolean $cli
     * @param array $parameters
     */
    public function __construct($cli = false, $parameters = [])
    {
        $this->cli = $cli;
        $this->parameters = $parameters;
    }
    
    /**
     * Get Cli
     * @return boolean
     */
    public function getCli()
    {
        return $this->cli;
    }
    
    /**
     * Get parameters
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
    /**
     * Initialize hook
     */
    public function initHook()
    {
        add_action('init', [$this, 'composeObjects'], 0);
        add_filter( 'determine_current_user', [$this, 'setUser'], 10, 1 );
    }
    
    /**
     * Set user if needed
     * @param mixed $user
     * @return mixed
     */
    public function setUser($user)
    {
        if ($this->getCli() && defined('PRIME_MOVER_COPY_MEDIA_SHELL_USER') && defined('PRIME_MOVER_DOING_SHELL_ARCHIVE') && PRIME_MOVER_DOING_SHELL_ARCHIVE) {
            return PRIME_MOVER_COPY_MEDIA_SHELL_USER;
        }        
        return $user;        
    }
    
    /**
     * Hooked to `init`
     */
    public function composeObjects()
    {        
        $prime_mover_user = wp_get_current_user();
        $lock_utilities = new PrimeMoverLockUtilities();

        $system_authorization = new PrimeMoverSystemAuthorization($prime_mover_user);        
        $system_initialization = new PrimeMoverSystemInitialization($system_authorization);        
        
        $openssl_utilities = new PrimeMoverOpenSSLUtilities($system_initialization);
        $openssl_utilities->initHooks();
        
        $system_functions = new PrimeMoverSystemFunctions($system_initialization);  
        $system_utilities = new PrimeMoverSystemUtilities($system_functions);
        $system_utilities->initHooks();          
        
        $shutdown_utilities = new PrimeMoverShutdownUtilities($system_functions);
        
        global $pm_fs;
        
        $freemius_integration = new PrimeMoverFreemiusIntegration($shutdown_utilities, $pm_fs);
        $freemius_integration->initHooks(); 
        
        $progress_handlers = new PrimeMoverProgressHandlers($shutdown_utilities);
        $progress_handlers->initHooks();
        
        $system_check_utilities = new PrimeMoverSystemCheckUtilities($system_functions); 
        $system_check_utilities->initHooks();
        
        $system_checks = new PrimeMoverSystemChecks($system_check_utilities);        
        $upload_utilities = new PrimeMoverUploadUtilities($system_checks, $progress_handlers);            
        $upload_utilities->initHooks();
        $stream_entity = new PrimeMoverStreamFilters();      
        
        $error_handlers = new PrimeMoverErrorHandlers($shutdown_utilities);
        $error_handlers->initHooks();       
        $cli_archiver = new PrimeMoverCLIArchive($system_checks, $progress_handlers);        
        
        $user_queries = new PrimeMoverUserQueries($cli_archiver);
        $user_functions = new PrimeMoverUserFunctions($user_queries);
        $user_utilities = new PrimeMoverUserUtilities($user_functions);
        $users = new PrimeMoverUsers($user_utilities);
        $users->initHooks();
        
        $archiver = new PrimeMoverArchiver($cli_archiver, $users, $openssl_utilities);
        $archiver->initHooks();
        
        $importer = new PrimeMoverImporter($cli_archiver, $users);
        $iterators = new PrimeMoverIterators($system_functions);         
        $exporter = new PrimeMoverExporter($stream_entity, $iterators, $cli_archiver, $users);       
         
        $export_utilities = new PrimeMoverExportUtilities($exporter);
        $export_utilities->initHooks();        
      
        $system_processors = new PrimeMoverSystemProcessors($importer, $upload_utilities, $export_utilities);               
        $hooked_methods = new PrimeMoverHookedMethods($system_checks, $progress_handlers);        
        
        $prime_mover = new PrimeMover($hooked_methods, $system_processors);
        $prime_mover->primeMoverLoadHooks();         
              
        $import_utilities = new PrimeMoverImportUtilities($importer, $export_utilities, $lock_utilities);
        $import_utilities->initHooks();
        
        $config_utilities = new PrimeMoverConfigUtilities($import_utilities);
        $config_utilities->initHooks();
        
        $resume_download_stream = new PrimeMoverResumableDownloadStream($system_functions);        
        $download_utilities = new PrimeMoverDownloadUtilities($resume_download_stream);
        $download_utilities->initHooks();         
        
        $search_utilities = new PrimeMoverSearchReplaceUtilities($prime_mover);
        $search_utilities->initHooks();       
        
        $backup_utilities = new PrimeMoverBackupUtilities($prime_mover);
        $component_utilities = new PrimeMoverComponentAuxiliary($import_utilities, $download_utilities, $backup_utilities);
        $component_utilities->initHooks();
        
        $prime_mover_gearbox_screenoptions = new PrimeMoverGearBoxScreenOptions($prime_mover, $component_utilities);
        $prime_mover_gearbox_screenoptions->initHooks();
        
        $utilities = [
            'sys_utilities' => $system_utilities,
            'error_handlers' => $error_handlers,
            'import_utilities' => $import_utilities,
            'download_utilties' => $download_utilities,
            'lock_utilities' => $lock_utilities,
            'openssl_utilities' => $openssl_utilities,
            'config_utilities' => $config_utilities,
            'component_utilities' => $component_utilities,
            'freemius_integration' => $freemius_integration,
            'screen_options' => $prime_mover_gearbox_screenoptions,
            'backup_utilities' => $backup_utilities
        ];        
        
        $validation_utilities = new PrimeMoverValidationUtilities($prime_mover, $utilities);
        $validation_utilities->initHooks();
        
        $input_validator = new PrimeMoverValidationHandlers($prime_mover, $utilities, $validation_utilities);
        $input_validator->initHooks();
  
        $compatibility = new PrimeMoverCompatibility($prime_mover, $utilities);
        $compatibility->initHooks();
        
        $elementor_compat = new PrimeMoverElementorCompat($prime_mover, $utilities);
        $elementor_compat->initHooks();
        
        $caching_compat = new PrimeMoverCachingCompat($prime_mover, $utilities);
        $caching_compat->initHooks();
        
        if ($this->getCli()) {
            $parameters = $this->getParameters();
            $cli = new PrimeMoverCLIShellArchiver($prime_mover, $utilities, $parameters);
            $cli->initHooks();
        }       
               
        $backup_menu = new PrimeMoverBackupMenus($prime_mover, $utilities);
        $backup_menu->initHooks();        
        
        do_action( 'prime_mover_load_module_apps', $prime_mover, $utilities);        
    }
    
    /**
     * Uninstall must-use plugin cleanup
     */
    public function primeMoverCleanUpOnUninstall()
    {
        $mu = trailingslashit(WPMU_PLUGIN_DIR) . 'prime-mover-cli-plugin-manager.php';
        if (! file_exists($mu) ) {
            return;
        }
        /**
         * Delete must-use plugin script that comes with Prime Mover
         */
        unlink($mu);
    }
}

/**
 * Instantiate
 * @var \PrimeMoverFramework\PrimeMoverFactory $loaded_instance
 */
$cli = false;
$parameters = [];
if ("cli" === php_sapi_name()) {
    $cli = true;
    
    /** @var Type $argv Command Line arguments*/
    global $argv;
    $parameters = $argv;
}
$loaded_instance = new PrimeMoverFactory($cli, $parameters);
$loaded_instance->initHook();

pm_fs()->add_action('after_uninstall', [$loaded_instance, 'primeMoverCleanUpOnUninstall']);
