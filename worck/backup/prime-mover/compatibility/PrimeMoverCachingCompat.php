<?php
namespace Codexonics\PrimeMoverFramework\compatibility;

/*
 * This file is part of the Codexonics.PrimeMoverFramework package.
 *
 * (c) Codexonics Ltd
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Codexonics\PrimeMoverFramework\classes\PrimeMover;

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Prime Mover Cache Compatibility Class
 * Helper class for interacting with third party cache plugins
 *
 */
class PrimeMoverCachingCompat
{     
    private $prime_mover;
    private $config_utilities;
    private $import_utilities;
    
    /**
     * Construct
     * @param PrimeMover $prime_mover
     * @param array $utilities
     */
    public function __construct(PrimeMover $prime_mover, $utilities = [])
    {
        $this->prime_mover = $prime_mover;
        $this->config_utilities = $utilities['config_utilities'];
        $this->import_utilities = $utilities['import_utilities'];
    }
    
    /**
     * Get import utilities
     */
    public function getImportUtilities()
    {
        return $this->import_utilities;
    }
    
    /**
     * Get config utilities
     */
    public function getConfigUtilities()
    {
        return $this->config_utilities;
    }
    
    /**
     * Get Prime Mover instance
     * @return \Codexonics\PrimeMoverFramework\classes\PrimeMover
     */
    public function getPrimeMover()
    {
        return $this->prime_mover;
    }
    
    /**
     * Get system functions
     * @return \Codexonics\PrimeMoverFramework\classes\PrimeMoverSystemFunctions
     */
    public function getSystemFunctions()
    {
        return $this->getPrimeMover()->getSystemFunctions();
    }
    
    /**
     * Get system authorization
     * @return \Codexonics\PrimeMoverFramework\classes\PrimeMoverSystemAuthorization
     */
    public function getSystemAuthorization()
    {
        return $this->getPrimeMover()->getSystemAuthorization();
    }
        
    /**
     * Initialize hooks
     */
    public function initHooks()
    {
        add_filter('prime_mover_after_user_diff_confirmation', [$this, 'disableCachingDuringRestore'], 10, 2);  
        add_action('prime_mover_after_actual_import', [$this, 'maybeRestoreWpCacheConstant'], 10, 2);        
        add_filter('prime_mover_ajax_rendered_js_object', [$this, 'maybeBlockRestoreIfCacheEnabled'], 75, 1 );
        
        add_action('prime_mover_dosomething_freerestore_form', [$this, 'addBlockFreeRestoreCachingEnabled']);
        add_filter('prime_mover_js_backups_renderer', [$this, 'maybeBlockRestoreIfCacheEnabled'], 10, 1);
    }   
    
    /**
     * Block free restore if caching enabled
     */
    public function addBlockFreeRestoreCachingEnabled()
    {
    ?>
       <div style="display:none;" id="js-prime-mover-block-free-restore-cached-enabled" title="<?php esc_attr_e('Error!', 'prime-mover')?>"> 
           <?php echo $this->generateErrorMarkupText(); ?>  	
       </div>
    <?php 
    }
    
    /**
     * Generate error markup text
     * @return string
     */
    protected function generateErrorMarkupText()
    {
        $markup = '';
        $markup .= '<p>' .  sprintf(esc_html__('Unable to restore package because of caching enabled. Please deactivate caching plugin and
           remove %s constant in %s.', 'prime-mover'), '<code>' . 'WP_CACHE' . '</code>', '<strong>wp-config.php</strong>') . '</p>';
        
        $markup .= '<p>' . esc_html__('Once completed, please refresh this page and restore again.', 'prime-mover') . '</p>';
        return $markup;
    }
    
    /**
     * Maybe block restore if caching enabled
     * This is only used when wp-config is not writable.
     * @param array $args
     * @return array
     */
    public function maybeBlockRestoreIfCacheEnabled(array $args)
    {
        $current_filter = current_filter();
        $args['prime_mover_config_writable'] = 'writable';
        if (!$this->getSystemFunctions()->isConfigFileWritable() && defined('WP_CACHE') && WP_CACHE) {            
            if ('prime_mover_ajax_rendered_js_object' === $current_filter) {
                $args['prime_mover_caching_enabled_error'] = $this->generateErrorMarkupText();
            }           
           $args['prime_mover_config_writable'] = 'readonly'; 
        }        
        
        return $args;
    }
    
    /**
     * Restore cache constant in multisite
     * @param array $ret
     * @param number $blog_id
     * @return void
     */
    public function maybeRestoreWpCacheConstant($ret = [], $blog_id = 0)
    {
        if (!is_multisite()) {
            return;
        }
        if (!$this->getSystemAuthorization()->isUserAuthorized()) {
            return;
        }
        if (empty($ret['cache_constant_removed'])) {
            return;
        }
        
        if (!$this->getSystemAuthorization()->isUserAuthorized()) {
            return;
        }
        
        $config_transformer = $this->getConfigUtilities()->getConfigTransformer();
        if (!$config_transformer ) {
            return;
        }
        
        if ($config_transformer->exists('constant', 'WP_CACHE')) {
            $config_transformer->remove('constant', 'WP_CACHE');
            $config_transformer->add('constant', 'WP_CACHE', 'true', ["anchor" => '$table_prefix', 'raw' => true]);
        } else {
            $config_transformer->add('constant', 'WP_CACHE', 'true', ["anchor" => '$table_prefix', 'raw' => true]);
        }
        
        $this->getPrimeMover()->getSystemFunctions()->maybeResetOpCache();
    }
    
    /**
     * Disable caching during site restore
     * @param array $ret
     * @param number $blog_id
     * @return $re
     */
    public function disableCachingDuringRestore($ret = [], $blog_id = 0)
    {
        if (!$this->getSystemAuthorization()->isUserAuthorized()) {
            return $ret;
        }
        $caching = false;          
        if (defined('WP_CACHE') && WP_CACHE) {
            $caching = true;
        }
        
        if (!$caching) {
            do_action('prime_mover_log_processed_events', 'Target site does not use caching, skipping...', $blog_id, 'import', __FUNCTION__, $this);
            return $ret;            
        }
        
        if (false === $this->getImportUtilities()->maybeImportPlugins($ret)) {
            do_action('prime_mover_log_processed_events', 'Package restored does not include plugins, skipping...', $blog_id, 'import', __FUNCTION__, $this);
            return $ret;
        }
        
        $ret = $this->removeWpCacheConstant($ret, $blog_id);
        $this->maybeRemovedBCacheFile($blog_id);
        $this->maybeRemoveObjectCacheFile($blog_id);
        $this->getPrimeMover()->getSystemFunctions()->maybeResetOpCache();   
        
        return $ret;
    }
    
    /**
     * Maybe remove db.php in wp-content dir
     * @param number $blog_id
     */
    protected function maybeRemovedBCacheFile($blog_id = 0)
    {
        if (!defined('PRIME_MOVER_CACHE_DB_FILE')) {
            return;
        }
        if (!PRIME_MOVER_CACHE_DB_FILE) {
            return;
        }
        $basename = strtolower(basename(PRIME_MOVER_CACHE_DB_FILE));
        if ('db.php' !== $basename) {
            return;
        }
        if (!$this->getSystemFunctions()->nonCachedFileExists(PRIME_MOVER_CACHE_DB_FILE)) {
            return;
        }
        $result = $this->getSystemFunctions()->primeMoverDoDelete(PRIME_MOVER_CACHE_DB_FILE, true);  
        if (true === $result) {
            do_action('prime_mover_log_processed_events', 'Cache drop-in file db.php is deleted to prevent restoration conflicts.', $blog_id, 'import', __FUNCTION__, $this);
        }        
    }
    
    /**
     * Maybe remove object-cache.php in wp-content dir
     * @param number $blog_id
     */
    protected function maybeRemoveObjectCacheFile($blog_id = 0)
    {
        if (!defined('PRIME_MOVER_OBJECT_CACHE_FILE')) {
            return;
        }
        if (!PRIME_MOVER_OBJECT_CACHE_FILE) {
            return;
        }
        $basename = strtolower(basename(PRIME_MOVER_OBJECT_CACHE_FILE));
        if ('object-cache.php' !== $basename) {
            return;
        }
        if (!$this->getSystemFunctions()->nonCachedFileExists(PRIME_MOVER_OBJECT_CACHE_FILE)) {
            return;
        }
        $result = $this->getSystemFunctions()->primeMoverDoDelete(PRIME_MOVER_OBJECT_CACHE_FILE, true);
        if (true === $result) {
            do_action('prime_mover_log_processed_events', 'Cache drop-in file object-cache.php is deleted to prevent restoration conflicts.', $blog_id, 'import', __FUNCTION__, $this);
        }
    }
    
    /**
     * Remove WP_CACHE constant in wp-config.php during restore
     * @param array $ret
     * @param number $blog_id
     * @return $ret
     */
    protected function removeWpCacheConstant($ret = [], $blog_id = 0)
    {
        $removed = false;
        $config_transformer = $this->getConfigUtilities()->getConfigTransformer();
        if (!$config_transformer ) {
            return $ret;
        }
        
        if ($config_transformer->exists('constant', 'WP_CACHE')) {
            $removed = $config_transformer->remove('constant', 'WP_CACHE');
        }
        
        if (true === $removed) {
            do_action('prime_mover_log_processed_events', 'WP_CACHE constant successfully removed.', $blog_id, 'import', __FUNCTION__, $this);
            $ret['cache_constant_removed'] = $removed;
        }
        
        return $ret;
    }
}