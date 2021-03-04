<?php
namespace Codexonics\PrimeMoverFramework\utilities;

/*
 * This file is part of the Codexonics.PrimeMoverFramework package.
 *
 * (c) Codexonics Ltd
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Codexonics\PrimeMoverFramework\classes\PrimeMoverSystemFunctions;

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Prime Mover System Utilities
 * Helper functionality for System functions
 *
 */
class PrimeMoverSystemUtilities
{
    private $system_functions;

    /**
     *
     * @param PrimeMoverSystemFunctions $system_functions
     */
    public function __construct(PrimeMoverSystemFunctions $system_functions)
    {
        $this->system_functions = $system_functions;
    }
    
    /**
     * Given a blog id, delete all backup files created and clean up the options table.
     * @param number $blog_id
     * @tested PrimeMoverFramework\Tests\TestPrimeMoverSystemUtilities::itCleansBackupDirectoryUponRequests()
     * @tested PrimeMoverFramework\Tests\TestPrimeMoverSystemUtilities::itCleansAllBackupDirectoryFilesUponRequests()
     */
    public function cleanBackupDirectoryUponRequest($blog_id = 0, $clean_all = false) 
    {
        if ( ! $this->getSystemFunctions()->getSystemAuthorization()->isUserAuthorized()) {
            return;
        }
        $backups = $this->getSystemFunctions()->getFilesToRestore($blog_id);
        foreach ($backups as $backup) {
            if (empty($backup['filepath'])) {
                continue;
            }
            $filepath = $backup['filepath'];
            $delete_result = $this->getSystemFunctions()->primeMoverDoDelete($filepath);
            if ($delete_result) {
                $this->cleanOptionsRelatedToThisBackup($filepath, $blog_id);
            }
        }
        
        $errorlog_option = $this->getSystemFunctions()->getSystemInitialization()->getErrorLogOptionOfBlog($blog_id);
        delete_option($errorlog_option);
        
        if ($clean_all) {
            $dir_path = $this->getSystemInitialization()->getExportDirectoryPath($blog_id);
            $this->getSystemFunctions()->primeMoverDoDelete($dir_path); 
        }
        do_action('prime_mover_after_allzipfiles_delete', $blog_id);
    }
    
    /**
     * Clean options when a backup is deleted
     * @param string $filepath
     * @param number $blog_id
     * @tested PrimeMoverFramework\Tests\TestPrimeMoverSystemUtilities::itCleansBackupDirectoryUponRequests()
     * @tested PrimeMoverFramework\Tests\TestPrimeMoverSystemUtilities::itCleansAllBackupDirectoryFilesUponRequests() 
     */
    public function cleanOptionsRelatedToThisBackup($filepath = '', $blog_id = 0)
    {
        if ( ! $filepath || ! $blog_id ) {
            return;
        }
        if ( ! $this->getSystemFunctions()->getSystemAuthorization()->isUserAuthorized()) {
            return;
        }
        $option_name = $this->getSystemFunctions()->getSystemInitialization()->generateZipDownloadOptionName(sanitize_html_class(basename($filepath)), $blog_id);
        $option_value = $this->getSystemFunctions()->getSiteOption($option_name, false, true, true);
        if ( ! $option_value || ! isset($option_value['hash'])) {
            return;
        }
        
        $hash = $option_value['hash'];
        $this->getSystemFunctions()->deleteSiteOption($hash, true);
        $this->getSystemFunctions()->deleteSiteOption($option_name, true);
        $this->getSystemFunctions()->deleteSiteOption($hash . '_filename', true);
    }
    
    /**
     *
     * Get system functions
     * @return \Codexonics\PrimeMoverFramework\classes\PrimeMoverSystemFunctions
     * @compatible 5.6
     */
    public function getSystemFunctions()
    {
        return $this->system_functions;
    }
    
    /**
     * Initialized hooks
     * @compatible 5.6
     * @tested Codexonics\PrimeMoverFramework\Tests\TestPrimeMoverSystemUtilities::itAddsInitHooks()
     * @tested Codexonics\PrimeMoverFramework\Tests\TestPrimeMoverSystemUtilities::itChecksIfHooksAreOutdated()
     */
    public function initHooks()
    {
        add_filter('multsite_migration_targetplugin_status', [ $this, 'computeTargetPluginStatus'], 10, 3);
        add_filter('prime_mover_filter_theme_diff', [ $this, 'analyzeThemeDiff' ], 10, 1);
        add_filter('prime_mover_filter_target_childtheme_msg', [ $this, 'ifNotUsingChildDoesItExist'], 10, 2);
        add_action('prime_mover_after_generating_download_url', [$this, 'deleteAllTmpFiles'], 100, 5);
    }
    
    /**
     * Delete tmp files in tmp directory created by wp_tempnam
     * @param string $results
     * @param string $hash
     * @param number $blogid_to_export
     * @param boolean $export_directory_on
     * @param array $ret
     */
    public function deleteAllTmpFiles($results = '', $hash = '', $blogid_to_export = 0, $export_directory_on = false, $ret = [])
    {
        if ( ! empty($ret['copymedia_shell_tmp_list'] ) && $this->getSystemFunctions()->nonCachedFileExists($ret['copymedia_shell_tmp_list']) ) {
            $this->getSystemFunctions()->primeMoverDoDelete($ret['copymedia_shell_tmp_list'], true);
        }      
    }
    
    /**
     * Check if target site is not using child theme, double check that its installed
     * @param string $msg
     * @param array $diff
     * @compatible 5.6
     * @tested Codexonics\PrimeMoverFramework\Tests\TestPrimeMoverSystemUtilities::itReturnsNoErrorMsgIfChildThemeExist() 
     * @tested Codexonics\PrimeMoverFramework\Tests\TestPrimeMoverSystemUtilities::itReturnsErrorIfChildThemeDoesNotExist()
     * @tested Codexonics\PrimeMoverFramework\Tests\TestPrimeMoverSystemUtilities::itReturnsEmptylIfNotUsingChildTheme() 
     */
    public function ifNotUsingChildDoesItExist($msg = '', array $diff)
    {
        if (empty($diff['themes']['child']['source_child'])) {
            return $msg;
        }
        $source_child_theme = $diff['themes']['child']['source_child'];
        $theme_object = wp_get_theme($source_child_theme);
        if (! $theme_object->exists()) {
            $msg = esc_html__('Not installed.', 'prime-mover');
        }
        
        return $msg;
    }
    
    /**
     * Checks if theme exist
     * @param string $source_parent_theme
     * @param string $source_child_theme
     * @param string $source_isUsingChildTheme
     * @return boolean[]
     * @compatible 5.6
     */
    protected function isThemeExist($source_parent_theme = '', $source_child_theme = '', $source_isUsingChildTheme = '')
    {
        $ret = [];
        $exist = false;
        $parent_exist = false;
        $child_exist = false;
        
        $parenttheme_object_target = wp_get_theme($source_parent_theme);
        if ($parenttheme_object_target->exists()) {
            $parent_exist = true;
        }
        
        if ('yes' === $source_isUsingChildTheme) {
            $childtheme_object_target = wp_get_theme($source_child_theme);
            if ($childtheme_object_target->exists()) {
                $child_exist = true;
            }
        }
        
        //Checks
        if ('no' === $source_isUsingChildTheme && $parent_exist) {
            $exist = true;
        }
        
        if ('yes' === $source_isUsingChildTheme && $child_exist && $parent_exist) {
            $exist = true;
        }
        
        $using_child_theme = false;
        if ('yes' === $source_isUsingChildTheme) {
            $using_child_theme = true;
        }
        $ret['exist'] = $exist;
        $ret['parent_exist'] = $parent_exist;
        $ret['child_exist'] = $child_exist;
        $ret['using_child_theme'] = $using_child_theme;
        
        return $ret;
    }
    
    /**
     * analyze Theme Diff
     * @param array $theme_diff
     * @compatible 5.6
     */
    public function analyzeThemeDiff(array $theme_diff)
    {
        $version_diff = [];
        if (! $this->areThemeSetforSourceDiffCheck($theme_diff)) {
            return $theme_diff;
        }
        
        $source_isUsingChildTheme = $theme_diff['using_child_theme']['source'];
        if (! $source_isUsingChildTheme) {
            return $theme_diff;
        }
        
        $source_parent_theme = $theme_diff['parent']['source_parent'];
        $source_parent_version = $theme_diff['parent']['source_version'];
        
        $source_child_theme = $theme_diff['child']['source_child'];
        $source_child_version = $theme_diff['child']['source_version'];
        
        /**
         * Check #1: If source theme does not exist or installed in target site
         */
        $theme_exist_check = $this->isThemeExist($source_parent_theme, $source_child_theme, $source_isUsingChildTheme);
        $theme_diff = $this->updateThemeDiff('theme_exist_check', $theme_diff, $theme_exist_check);
        
        /**
         * Check #2: If source theme exist in target site but using different version.
         */
        $version_diff = $this->isThemeVersionTheSame($source_parent_theme, $source_parent_version, $source_child_theme, $source_child_version, $theme_exist_check, $source_isUsingChildTheme);
        $theme_diff = $this->updateThemeDiff('theme_version_check', $theme_diff, $version_diff);
        
        /**
         * Only show the exact differences,
         * Remove entries that are already the same
         */
        $theme_diff = $this->outputTrueDiffs($theme_diff, $version_diff);
        return $theme_diff;
    }
    
    /**
     * output True diffs
     * @param array $theme_diff
     * @param array $version_diff
     * @return array[]
     * @compatible 5.6
     */
    protected function outputTrueDiffs(array $theme_diff, array $version_diff)
    {
        if (empty($version_diff)) {
            return $theme_diff;
        }
        if (empty($theme_diff)) {
            return $theme_diff;
        }
   
        if (! empty($version_diff['same_parent']) && true === $version_diff['same_parent']) {
            //Parent elements the same, remove from diff
            $theme_diff['parent'] = [];
        }
        
        if (isset($version_diff['using_child_theme']) && isset($version_diff['same_child']) &&
            'yes' === $version_diff['using_child_theme'] && true === $version_diff['same_child']) {
            $theme_diff['child'] = [];
        }
        
        return $theme_diff;
    }

    /**
     * Checks if all theme diff requisites are checked for source
     * @param array $theme_diff
     * @return boolean
     * @compatible 5.6
     */
    protected function areThemeSetforSourceDiffCheck(array $theme_diff)
    {
        if (empty($theme_diff)) {
            return false;
        }
        if (empty($theme_diff['using_child_theme']['source'])) {
            return false;
        }
        
        if (empty($theme_diff['parent']['source_parent'])) {
            return false;
        }
        
        if (empty($theme_diff['parent']['source_version'])) {
            return false;
        }
        
        if (empty($theme_diff['child']['source_child'])) {
            return false;
        }
        
        if (empty($theme_diff['child']['source_version'])) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Checks if all theme diff requisites are checked for target
     * @param array $theme_diff
     * @return boolean
     * @compatible 5.6
     */
    protected function areThemeSetforTargetDiffCheck(array $theme_diff)
    {
        if (empty($theme_diff)) {
            return false;
        }
        if (empty($theme_diff['using_child_theme']['target'])) {
            return false;
        }
        
        if (empty($theme_diff['parent']['target_parent'])) {
            return false;
        }
        
        if (empty($theme_diff['parent']['target_version'])) {
            return false;
        }
        
        if (empty($theme_diff['child']['target_child'])) {
            return false;
        }
        
        if (empty($theme_diff['child']['target_version'])) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Theme diff version check
     * @param string $source_parent_theme
     * @param string $source_parent_version
     * @param string $source_child_theme
     * @param string $source_child_version
     * @param array $theme_exist_check
     * @param string $source_isUsingChildTheme
     * @return boolean[]
     * @compatible 5.6
     */
    protected function isThemeVersionTheSame(
        $source_parent_theme = '',
        $source_parent_version = '',
        $source_child_theme = '',
        $source_child_version = '',
        array $theme_exist_check,
        $source_isUsingChildTheme = ''
    ) 
    {
        $ret = [];
        $same = false;
        $same_parent_version = false;
        $same_child_version = false;
        $installed_version = '';
        $child_installed_version = '';
        
        $parent_exist = false;
        if (isset($theme_exist_check['parent_exist']) && $theme_exist_check['parent_exist']) {
            $parent_exist = true;
        }
        
        //Target parent version check, target parent SHOULD exist at this point.
        if ($parent_exist) {
            $parenttheme_object_target = wp_get_theme($source_parent_theme);
            $installed_version = $parenttheme_object_target->get('Version');
            if (0 === version_compare($installed_version, $source_parent_version)) {
                $same_parent_version = true;
            }
        }
        
        //Target child version check, check if child exist
        $child_exist = false;
        if (isset($theme_exist_check['child_exist']) && $theme_exist_check['child_exist']) {
            $child_exist = true;
        }
        $child_installed_version = '';
        if ($child_exist) {
            $childtheme_object_target = wp_get_theme($source_child_theme);
            $child_installed_version = $childtheme_object_target->get('Version');
            if (0 === version_compare($child_installed_version, $source_child_version)) {
                $same_child_version = true;
            }
        }
        
        //Checks
        if ('no' === $source_isUsingChildTheme && $same_parent_version) {
            $same = true;
        }
        
        if ('yes' === $source_isUsingChildTheme && $child_exist && $same_child_version && $same_parent_version) {
            $same = true;
        }
        
        $ret['same'] = $same;
        $ret['same_parent'] = $same_parent_version;
        $ret['same_child'] = $same_child_version;
        $ret['target_installed_parent_version'] = $installed_version;
        $ret['target_installed_child_version'] = $child_installed_version;
        $ret['using_child_theme'] = $source_isUsingChildTheme;
        
        return $ret;
    }
    
    /**
     * Update theme diff
     * @param string $mode
     * @param array $theme_diff
     * @param array $input_data
     * @return string
     * @compatible 5.6
     */
    protected function updateThemeDiff($mode = '', array $theme_diff, array $input_data)
    {
        if ('theme_exist_check' === $mode) {
            if (isset($input_data['parent_exist']) && ! $input_data['parent_exist']) {
                //Source parent theme does not exist in target site, update diff
                $theme_diff['parent']['target_parent'] = esc_html__('Not installed.', 'prime-mover');
                $theme_diff['child']['target_child'] = esc_html__('Not installed.', 'prime-mover');
                
                //Remove version because its not needed
                unset($theme_diff['parent']['target_version']);
                unset($theme_diff['child']['target_version']);
            }
            
            if (isset($input_data['child_exist']) && ! $input_data['child_exist']) {
                
                //Source child theme does not exist in target site, update diff
                $theme_diff['child']['target_child'] = esc_html__('Not installed.', 'prime-mover');
                unset($theme_diff['child']['target_version']);
            }
        }
        
        if ('theme_version_check' === $mode) {
            if (isset($input_data['same']) && true === $input_data['same']) {
                $theme_diff = [];
                return $theme_diff;
            }
            
            if (isset($input_data['same_parent']) && false === $input_data['same_parent'] && ! empty($input_data['target_installed_parent_version'])) {
                //Target parent theme differs from source parent theme, update diff
                $theme_diff['parent']['target_parent'] = $theme_diff['parent']['source_parent'];
                $theme_diff['parent']['target_version'] = $input_data['target_installed_parent_version'];
            }
            if (isset($input_data['using_child_theme']) && isset($input_data['same_child']) &&
                'yes' === $input_data['using_child_theme'] && ! $input_data['same_child'] && ! empty($input_data['target_installed_child_version'])) {
                //Using child theme and it differs in source version, update diff
                $theme_diff['child']['target_child'] = $theme_diff['child']['source_child'];
                $theme_diff['child']['target_version'] = $input_data['target_installed_child_version'];
            }
            if (isset($input_data['using_child_theme']) && 'no' === $input_data['using_child_theme'] &&
                 isset($theme_diff['child']) && isset($theme_diff['using_child_theme']['target'])) {
                //Source is not using child theme but target is. Remove all child diffs.
                unset($theme_diff['child']);
                unset($theme_diff['using_child_theme']['target']);
            }
        }
        
        return $theme_diff;
    }
    
    /**
     * Get System initialization
     * @compatible 5.6
     */
    public function getSystemInitialization()
    {
        return $this->getSystemFunctions()->getSystemInitialization();
    }
        
    /**
     *
     * @param string $source_plugin_name
     * @return string
     * @compatible 5.6
     * @tested Codexonics\PrimeMoverFramework\Tests\TestPrimeMoverSystemUtilities::itGetsPluginAbsolutePath()
     */
    public function getPluginAbsolutePath($source_plugin_name = '')
    {
        return PRIME_MOVER_PLUGIN_CORE_PATH . $source_plugin_name;
    }
    
    /**
     *
     * @param string $source_plugin_name
     * @return string
     * @compatible 5.6
     * @tested Codexonics\PrimeMoverFramework\Tests\TestPrimeMoverSystemUtilities::itGetsAbsoluteThemePath() 
     */
    public function getThemeAbsolutePath($source_theme = '')
    {
        return PRIME_MOVER_THEME_CORE_PATH . $source_theme;
    }
    
    /**
     * Compute target plugin status based on different case scenarios
     * Handling case #4, case #5 and case #6
     * @param string $status
     * @param string $source_plugin_name
     * @param string $source_version
     * @compatible 5.6
     * @tested Codexonics\PrimeMoverFramework\Tests\TestPrimeMoverSystemUtilities::itComputesTargetPluginStatus()
     */
    public function computeTargetPluginStatus($status = '', $source_plugin_name = '', $source_version = '')
    {
        global $wp_filesystem;
        if (! $source_plugin_name || ! $source_version) {
            return $status;
        }
        if (! $wp_filesystem->exists($this->getPluginAbsolutePath($source_plugin_name))) {
            //Case #6, update diff status
            $status = esc_html__('Not installed.', 'prime-mover');
        } else {
            //At this point, plugin is installed on target site but we are not sure of its version
            $version_check = $this->isVersionDifferent($source_plugin_name, $source_version);
            if ($version_check) {
                //Case #5, different version, update diff status
                $status = sprintf(esc_html__('%s', 'prime-mover'), $version_check);
            }
        }
        
        return $status;
    }
    
    /**
     * Returns TRUE if version is different
     * @param string $source_plugin_name
     * @param string $source_version
     * @compatible 5.6
     */
    protected function isVersionDifferent($source_plugin_name = '', $source_version = '')
    {
        $target_plugin_data = get_plugin_data($this->getPluginAbsolutePath($source_plugin_name));
        $target_plugin_version = esc_html__('unknown', 'prime-mover');
        
        if (! empty($target_plugin_data['Version'])) {
            $target_plugin_version = $target_plugin_data['Version'];
        }
        
        if (0 !== version_compare($target_plugin_version, $source_version)) {
            //Differing versions
            return $target_plugin_version;
        } else {
            //Version the same
            $target_plugin_version = '';
        }
        
        return $target_plugin_version;
    }
    
    /**
     * Checks if clean real path
     * @param string $input_data
     * @return boolean
     */
    private function isCleanRealPathHelper($input_data = '')
    {
        $realpath = realpath($input_data);
        $userpath = rtrim($input_data, DIRECTORY_SEPARATOR);
        if ( false === $realpath || $realpath !== $userpath) {
            return false;
        }
        return true;
    }

    /**
     * Check if provided path is clean
     * @param string $input_data
     * @param boolean $make_dir
     * @return boolean
     */
    public function isCleanRealPath($input_data = '', $make_dir = true)
    {
        if ( ! $input_data ) {
            return false;
        }        
        if ($make_dir) {
            /**
             * Make directory if not exist
             */
            if (wp_mkdir_p($input_data)) {
                return $this->isCleanRealPathHelper($input_data);
            }
        } else {
            return $this->isCleanRealPathHelper($input_data);            
        }        
        return false;
    }
}
