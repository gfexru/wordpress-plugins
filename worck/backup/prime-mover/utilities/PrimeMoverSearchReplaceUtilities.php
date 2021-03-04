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

use Codexonics\PrimeMoverFramework\classes\PrimeMover;

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Prime Mover Search and Replace
 * Handles edge cases in search and replace functionality
 *
 */
class PrimeMoverSearchReplaceUtilities
{
    private $prime_mover;    
    private $slashed_replaceables;
    private $missing_backlash;
    
    
    /**
     * Constructor
     * @param PrimeMover $prime_mover
     */
    public function __construct(PrimeMover $prime_mover)
    {
        $this->prime_mover = $prime_mover;
        $this->slashed_replaceables = [
            'wpupload_path',
            'wpupload_url',
            'alternative_wpupload_url',
            'wpupload_url_alternative', 
            'wproot_slash_appended',
            'removed_trailing_slash_wproot',
            'scheme_replace',
            /**
             * EMERSON EXPERIMENT,
             * THE FOLLOWING ARE EXPERIMENTALS REPLACEMENT FOR LEGACY MULTISITES
             * USING PAGE BUILDERS
             */
            'legacybase_url',
            'httpblogsdir_url',
            'httpfiles_url',
            'wpupload_url_mixed_content'
        ];
        
        $this->missing_backlash = ['alternative_wpupload_url', 'wpupload_url'];
    }
    
    /**
     * Get slashed replaceables
     * @return string[]
     */
    public function getSlashedReplaceables()
    {
        return $this->slashed_replaceables;
    }
    
    /**
     * Get missing slash vars
     * @return string[]
     */
    public function getMissingSlashVars()
    {
        return $this->missing_backlash;
    }
    
    /**
     * Get Prime Mover
     * @return \Codexonics\PrimeMoverFramework\classes\PrimeMover
     */
    public function getPrimeMover()
    {
        return $this->prime_mover;
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
     * Get System Initialization
     * @return \Codexonics\PrimeMoverFramework\classes\PrimeMoverSystemInitialization
     */
    public function getSystemInitialization()
    {
        return $this->getPrimeMover()->getSystemInitialization();
    }
    
    /**
     * Get system check utilities
     * @return \Codexonics\PrimeMoverFramework\utilities\PrimeMoverSystemCheckUtilities
     */
    public function getSystemCheckUtilities()
    {
        return $this->getPrimeMover()->getSystemChecks()->getSystemCheckUtilities();    
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
     * Init hooks
     * @compatible 5.6
     * @tested Codexonics\PrimeMoverFramework\Tests\TestPrimeMoverSearchReplaceUtilities::itAddsInitHooks() 
     * @tested Codexonics\PrimeMoverFramework\Tests\TestPrimeMoverSearchReplaceUtilities::itChecksIfHooksAreOutdated() 
     */
    public function initHooks()
    {
        add_filter('prime_mover_filter_replaceables', [$this, 'adjustLegacySSLReplace'], 999, 2);
        add_filter('prime_mover_skip_search_replace', [$this, 'skipSearchReplaceWhenSiteParametersSame'], 9999, 4);
        
        add_filter('prime_mover_filter_export_footprint', [$this, 'addAlternativeUploadInfoToSystemFootprint'], 1000, 1);
        add_filter('prime_mover_filter_upload_phrase', [$this, 'maybeAdjustForMixedContent'], 1, 4);
        
        add_filter('prime_mover_filter_upload_phrase', [$this, 'adjustLegacyRootSearchReplace'], 999, 5);
        add_filter('prime_mover_filter_final_replaceables', [$this, 'pageBuildersCompatibility'], 10, 4);
        
        add_filter('prime_mover_append_edge_builder_replaceables', [$this, 'handleMissingBackSlash'], 10, 4);
        add_filter('prime_mover_input_footprint_package_array', [$this, 'normalizeUploadInformationPath'], 10, 1);
    }
    
    /**
     * Normalize upload information path
     * @param array $system_footprint_package_array
     * @return array
     */
    public function normalizeUploadInformationPath($system_footprint_package_array = [])
    {
        if (empty($system_footprint_package_array['upload_information_path'])) {
            return $system_footprint_package_array;
        }
        
        $path = wp_normalize_path($system_footprint_package_array['upload_information_path']);        
        $system_footprint_package_array['upload_information_path'] = $path;
        
        return $system_footprint_package_array;        
    }
    
    /**
     * Missing back slash workaround
     * @param array $updated
     * @param string $key
     * @param array $replaceables
     * @param number $counter
     * @return array
     */
    public function handleMissingBackSlash($updated = [], $key = '', $replaceables = [], $counter = 0)
    {
        if (!$this->getSystemAuthorization()->isUserAuthorized() || empty($updated) || !$key) {
            return $updated;
        }
        
        if (!in_array($key, $this->getMissingSlashVars(), true)) {
            /**
             * EMERSON EXPERIMENT
             * This variable does not need missing slash adjustment, skip..
             */            
            return $updated;
        }
        $slashed_varkey = $key . '_slashedvar';
        if (empty($replaceables['domain_replace']['search'])) {
            return $updated;
        }
        if (empty($updated[$slashed_varkey])) {
            return $updated;
        }
        if (empty($updated[$slashed_varkey]['search']) || empty($updated[$slashed_varkey]['replace'])) {
            return $updated;
        }
                
        /**
         * EMERSON EXPERIMENT
         * WE WANT TO KNOW IF DOMAIN HAS PATHS
         */
        $source_site = $replaceables['domain_replace']['search'];        
        $dummy = 'http://' . $source_site;
        
        /**
         * MAKE SURE ITS NORMALIZED AND NO TRAILING SLASH
         */
        $dummy = untrailingslashit(wp_normalize_path($dummy));
        
        /**
         * DOES THE DOMAIN HAVE PATHS ON THEM?
         */
        $parsed = parse_url($dummy);
        if (empty($parsed['path'])) {
            /**
             * PURE DOMAIN, IT DOES NOT APPLY HERE
             * SKIPPED
             */
            return $updated;
        }
        
        /**
         * EMERSON EXPERIMENT
         * THE PACKAGE IS AFFECTED WITH THIS ISSUE
         * WE REALLY WANT TO SEARCH
         * test100.local\/subsite-twenty
         * 
         * IN alternative_wpupload_url_slashedvar
         * AND REPLACE IT WITH
         * test100.local/subsite-twenty
         * 
         * AND ADD IT AS NEW PROTOCOL
         * 
         * [alternative_wpupload_url_{$key}] => Array
        (
            [search] => http:\/\/test100.local/subsite-twenty\/wp-content\/uploads\/sites\/20
            [replace] => https:\/\/subdomaintest.tld\/wp-content\/uploads
        )
         */
        $target_srch = str_replace('/', '\/', $source_site);
        $slashedvar = $updated[$slashed_varkey]['search'];        
        $res = str_replace($target_srch, $source_site, $slashedvar);
        if ($res === $slashedvar) {
            /**
             * EMERSON EXPERIMENT
             * ACTUALLY NOTHING CHANGES HERE
             * SO WE DONT NEED TO ADD NEW PROTOCOL AS IT WAS JUST BE A WASTE OF RESOURCES OR PROCESSING TIME
             * WE SIMPLY RETURN
             */
            return $updated;
        }
        $rplc_slashed_edged = $updated[$slashed_varkey]['replace'];        
        $protocol = ['search' => $res, 'replace' => $rplc_slashed_edged];
        $new_key = $key . '_' . $counter;
        
        $updated[$new_key] = $protocol;  
        
        return $updated;
    }
    
    /**
     * EMERSON EXPERIMENT - BASIC FIX
     * General search replace page builder compatibility
     * @param array $replaceables
     * @param array $ret
     * @param boolean $retries
     * @param number $blogid_to_import
     * @return array
     */
    public function pageBuildersCompatibility($replaceables = [], $ret = [], $retries = false, $blogid_to_import = 0)
    {
        if (!$this->getSystemAuthorization()->isUserAuthorized() || !$blogid_to_import || empty($replaceables)) {
            /**
             * User is not authorized OR blog ID not set OR its empty
             */
            return $replaceables;
        }
        
        /**
         * Empty updated array values
         * @var array $updated
         */
        $updated = [];
        
        /**
         * Get list of slashed replaceable variables
         * Let's have this values filterable for flexible reasons
         */
        $slashed_vars = apply_filters('prime_mover_filter_slashed_replaceables', $this->getSlashedReplaceables(), $ret, $blogid_to_import);
        
        /**
         * Let's loop over replaceables and find the slashed vars
         */
        $counter = 0;
        foreach ($replaceables as $key => $protocol) {
            /**
             * Initially append the original value
             */
            $updated[$key] = $protocol;   
            
            /**
             * Is this key a slashed replaceable?
             */
            if (!in_array($key, $slashed_vars)) {
                /**
                 * Not slashed replaceable.
                 */                 
                continue;
            }
            if (!isset($protocol['search']) || !isset($protocol['replace'])) {
                /**
                 * Requisite data not set
                 */
                continue;
            }
            
            /**
             * We have slashed replaceable here
             */
            $search = $protocol['search'];
            $replace = $protocol['replace'];
            
            $srch_slashed = str_replace('/', '\/', $search);
            $rplc_slashed = str_replace('/', '\/', $replace);
            
            $protocol = ['search' => $srch_slashed, 'replace' => $rplc_slashed]; 
            $new_key = $key . '_slashedvar';            
            $updated[$new_key] = $protocol;              
            
            $updated = apply_filters('prime_mover_append_edge_builder_replaceables', $updated, $key, $replaceables, $counter);
            $counter++;
        }
        
        if (!empty($updated)) {
            return $updated;
        }
        
        return $replaceables;
    }    
    
    /**
     * Adjust legacy root URLs search and replace edge case
     * @param array $upload_phrase
     * @param array $ret
     * @param array $replaceables
     * @param array $basic_parameters
     * @param number $blog_id
     * @return array
     */
    public function adjustLegacyRootSearchReplace($upload_phrase = [], $ret = [], $replaceables = [], $basic_parameters = [], $blog_id = 0)
    {
        if (! $this->getSystemAuthorization()->isUserAuthorized() || !$blog_id ) {
            return $upload_phrase;
        }
        
        if (!is_multisite()) {
            return $upload_phrase;            
        }
       
        if (!$this->getSystemCheckUtilities()->isLegacyMultisiteBaseURL($blog_id)) { 
            return $upload_phrase; 
        }
        
        if ($this->getSystemFunctions()->isMultisiteMainSite($blog_id)) {
            return $upload_phrase;
        }     
       
        if (empty($upload_phrase['wpupload_url']['search']) || empty($upload_phrase['wpupload_url']['replace']) ) {
            return $upload_phrase;
        }
       
        if (parse_url($upload_phrase['wpupload_url']['search'], PHP_URL_HOST) !== parse_url($upload_phrase['wpupload_url']['replace'], PHP_URL_HOST)) {
            return $upload_phrase;            
        }
       
        if (empty($ret['origin_site_url']) || empty($ret['target_site_url'])) {
            return $upload_phrase;
        }
        
        $search = $ret['origin_site_url'];
        $replace = $ret['target_site_url'];
        $subject = $replace;        
        $test = str_replace($search, $replace, $subject);
        if ($test === $replace) {
            return $upload_phrase;
        }
        
        $prev_one = $upload_phrase['wpupload_url']['replace'];
        $upload_phrase['wpupload_url']['replace'] = str_replace($subject, $search, $prev_one);         
        
        $prev_two = '';
        if (!empty($upload_phrase['wpupload_url_mixed_content']['replace'])) {
            $prev_two = $upload_phrase['wpupload_url_mixed_content']['replace'];
            $upload_phrase['wpupload_url_mixed_content']['replace'] = str_replace($subject, $search, $prev_two);
        }
        
        return $upload_phrase;
    }
    
 
    /**
     * Adjust for mixed content URLs
     * @param array $upload_phrase
     * @param array $ret
     * @param array $replaceables
     * @param array $basic_parameters
     * @return array
     * @tested Codexonics\PrimeMoverFramework\Tests\TestPrimeMoverSearchReplaceUtilities::itAdjustsForMixedContent()
     */
    public function maybeAdjustForMixedContent($upload_phrase = [], $ret = [], $replaceables = [], $basic_parameters = [])
    {
        if (! $this->getSystemAuthorization()->isUserAuthorized()) {
            return $upload_phrase;
        }
        if (empty($ret['imported_package_footprint']['upload_information_url'])) {
            return $upload_phrase;
        }
        if (empty($basic_parameters)) {
            return $upload_phrase;
        }
        
        /** @var Type $target_site_upload_path Target site upload path*/
        /** @var Type $source_site_upload_path Source site upload path*/
        list($target_site_upload_url, $target_site_upload_path, $source_site_upload_url, $source_site_upload_path) = $basic_parameters;
        
        $origin_scheme = '';
        if ( ! empty( $ret['imported_package_footprint']['scheme'] ) ) {
            $origin_scheme = $ret['imported_package_footprint']['scheme'];
        }
        
        $scheme_search = parse_url($source_site_upload_url, PHP_URL_SCHEME);       
        $compat_search = '';
        if ( 'http' === $scheme_search && 'https://' === $origin_scheme) {
            $compat_search = str_replace('http://', 'https://', $source_site_upload_url);
            $upload_phrase['wpupload_url_compat']['search'] = $compat_search;
            $upload_phrase['wpupload_url_compat']['replace'] = $target_site_upload_url;
        }
        
        $mixed_search = '';
        if ('https://' === $origin_scheme) {
            $mixed_search = str_replace('https://', 'http://', $source_site_upload_url);
            $upload_phrase['wpupload_url_mixed_content']['search'] = $mixed_search;
            $upload_phrase['wpupload_url_mixed_content']['replace'] = $target_site_upload_url;            
        }
        
        $alternative_mixed_search = '';
        if ($mixed_search && !empty($upload_phrase['alternative_wpupload_url'])) {
            $alternative_mixed_search = str_replace('https://', 'http://', $upload_phrase['alternative_wpupload_url']['search']);
            $upload_phrase['wpupload_url_alt_mixed_content']['search'] = $alternative_mixed_search;
            $upload_phrase['wpupload_url_alt_mixed_content']['replace'] = $target_site_upload_url;  
        }
        
        return $upload_phrase;
    }
    
    /**
     * Add alternative upload url info to footprint for multisites only
     * @param array $footprint
     * @return array
     * @since 1.0.6
     */
    public function addAlternativeUploadInfoToSystemFootprint($footprint = [])
    {
        if (!is_multisite() ) {
            return $footprint;
        }        
        
        if (empty($footprint['site_url']) || empty($footprint['upload_information_url']) || empty($footprint['scheme'])) {
            return $footprint;
        }
        
        $subsite_url = $footprint['site_url'];
        $upload_information_url = $footprint['upload_information_url'];
        $main_site_url = network_site_url();
        
        $scheme = $footprint['scheme'];
        $source_site_url = trailingslashit($scheme . $subsite_url);
        
        /**
         * EMERSON EXPERIMENT
         * OK HERE SUPPOSING YOU WANT TO SEARCH FOR:
         * https://ref.wp-types.tld/
         * 
         * AND REPLACE WITH:
         * https://ref.wp-types.tld/storefront/
         * 
         * FOR THIS GIVEN UPLOAD INFORMATION URL:
         * https://ref.wp-types.tld/storefront/wp-content/blogs.dir/72/files/
         * 
         * THE RESULT IS:
         * https://ref.wp-types.tld/storefront/storefront/wp-content/blogs.dir/72/files
         * 
         * THESE ALL SOUNDS FUNNY RIGHT?
         */
        
        /**
         * WHAT WE REALLY WANT IS CHECK FIRST IF $upload_information_url
         * IS ACTUALLY CONTAINS THE PATH TO BE REPLACED, IT IS INDEED.
         * NO NEED TO RUN THE STR_REPLACE, AND ACTUALLY NO ALTERNATIVE UPLOAD INFORMATION URL
         * SIMPLY RETURN THE FOOTPRINT
         */
        $pos = strpos($upload_information_url, $subsite_url);
        $search = $main_site_url;
        $replace = $source_site_url;
        
        if (false !== $pos) {
            /**
             * BASICALLY THE UPLOAD INFORMATION URL IS ALREADY
             * HAS THE FORM OF ALTERNATIVE UPLOAD INFORMATION URL LONG PATH ALREADY
             */
            $search = $source_site_url;
            $replace = $main_site_url;
        } 
        
        /**
         * NOT FOUND WE MIGHT WANT TO RUN FURTHER PROCESSING SUCH AS STR REPLACE AS THIS WONT CAUSE DOUBLE URLS
         */      
        
        $alternative_uploads_url = str_replace($search, $replace, $upload_information_url);
        if ($alternative_uploads_url === $upload_information_url) {
            return $footprint;
        }
        $footprint['alternative_upload_information_url'] = $alternative_uploads_url;        
        return $footprint;
    }
    
    /**
     * Skip search replace when site parameters the same
     * @param boolean $return
     * @param array $ret
     * @param number $blogid_to_import
     * @param array $replaceables
     * @since 1.0.6
     * @tested Codexonics\PrimeMoverFramework\Tests\TestPrimeMoverSearchReplaceUtilities::itSkipsSearchReplaceWhenSiteParametersTheSame()
     * @tested Codexonics\PrimeMoverFramework\Tests\TestPrimeMoverSearchReplaceUtilities::itDoesNotSkipSearchReplaceParametersUnequal()
     */
    public function skipSearchReplaceWhenSiteParametersSame($return = false, $ret = [], $blogid_to_import = 0, $replaceables = [])
    {
        if (true === $return) {
            return $return;
        }
        
        if ( ! isset($replaceables['wpupload_path']['search']) ||  ! isset($replaceables['wpupload_path']['replace'] ) ) {
            return $return;
        }
        
        if ( ! isset($replaceables['wpupload_url']['search']) ||  ! isset($replaceables['wpupload_url']['replace'] ) ) {
            return $return;
        }
  
        if ( ! isset($replaceables['domain_replace']['search']) ||  ! isset($replaceables['domain_replace']['replace'] ) ) {
            return $return;
        }
        
        return ($replaceables['wpupload_path']['search'] === $replaceables['wpupload_path']['replace'] && $replaceables['wpupload_url']['search'] === $replaceables['wpupload_url']['replace'] && 
            $replaceables['domain_replace']['search'] === $replaceables['domain_replace']['replace']);
    }
    
    /**
     * Validate search and replace data before filtering
     * @param array $replaceables
     * @param array $ret
     * @return boolean
     */
    private function validateData($replaceables = [], $ret = []) 
    {        
        if (empty($ret['imported_package_footprint']['legacy_upload_information_url'])) {
            return false;
        }        
        if (empty($ret['imported_package_footprint']['scheme'])) {
            return false;
        }        
        if ('https://' !== $ret['imported_package_footprint']['scheme'] ) {
            return false;
        }        
        if (empty($replaceables['wpupload_url']['search']) || empty($replaceables['legacybase_url']['search'])) {
            return false;
        }        
        return true;
    }
    
    /**
     * Get raw HTTP version of URL
     * @param string $url
     * @return mixed
     */
    private function getRawHttpVersion($url = '')
    {
        return str_replace( 'https://', 'http://', $url);
    }
    
    /**
     * Adjust https URLs in search and replace for legacy multisites
     * @param array $replaceables
     * @param array $ret
     * @tested Codexonics\PrimeMoverFramework\Tests\TestPrimeMoverSearchReplaceUtilities::itDoesNotAdjustSearchParamsNonHttps()
     * @tested Codexonics\PrimeMoverFramework\Tests\TestPrimeMoverSearchReplaceUtilities::itAdjustLegacyHttpsUrls() 
     * @tested Codexonics\PrimeMoverFramework\Tests\TestPrimeMoverSearchReplaceUtilities::itDoesNotAdjustSearchParamsIfNotLegacy()
     */
    public function adjustLegacySSLReplace($replaceables = [], $ret = [])
    {
        if ( ! $this->validateData($replaceables, $ret) ) {
            return $replaceables;
        }
        
        $https_blogsdir = $replaceables['wpupload_url']['search'];
        $http_blogsdir = $this->getRawHttpVersion($https_blogsdir);
        
        $https_files = $replaceables['legacybase_url']['search'];
        $http_files = $this->getRawHttpVersion($https_files);
        
        $reference_index = array_search("legacybase_url",array_keys($replaceables));
        
        $offset = $reference_index + 1;
        
        $replaceables = array_slice($replaceables, 0, $offset, true) +
        [
            'httpblogsdir_url' => [ 
                'search' => $http_blogsdir, 
                'replace' => $replaceables['wpupload_url']['replace'] 
            ]            
        ] +
        [
            'httpfiles_url' => [
                'search' => $http_files,
                'replace' => $replaceables['legacybase_url']['replace']
            ]
        ] +
        
        array_slice($replaceables, $offset, NULL, true);        
        
        return $replaceables;
    }
}