<?php
namespace Codexonics\PrimeMoverFramework\users;

/*
 * This file is part of the Codexonics.PrimeMoverFramework package.
 *
 * (c) Codexonics Ltd
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Codexonics\PrimeMoverFramework\cli\PrimeMoverCLIArchive;

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Prime Mover User Queries
 * Provides very basic user queries for user import and export processes.
 * Both single-site and multisite compatible
 */
class PrimeMoverUserQueries
{         
    private $cli_archiver;
    
    const PRIME_MOVER_USER_TAXONOMY_OPTION = 'prime_mover_user_taxonomy';
   
    /**
     * Constructor
     * @param PrimeMoverCLIArchive $cli_archiver
     */
    public function __construct(PrimeMoverCLIArchive $cli_archiver)
    {
        $this->cli_archiver = $cli_archiver;        
    }
    
    /**
     * Get user taxonomies
     * @param number $blog_id
     * @return []
     */
    public function getUserTaxonomies($blog_id = 0)
    {
        if (is_multisite()) {
            return get_option(self::PRIME_MOVER_USER_TAXONOMY_OPTION);
        } else {
            return get_object_taxonomies('user');
        }
    }
    
    /**
     * Get progress handlers
     * @return \Codexonics\PrimeMoverFramework\classes\PrimeMoverProgressHandlers
     */
    public function getProgressHandlers()
    {
        return $this->getCliArchiver()->getProgressHandlers();
    }
    
    /**
     * Update user taxonomy
     * @param array $ret
     * @param number $blog_id
     * @param number $start_time
     * @return array
     */
    public function updateUserTaxonomy($ret = [], $blog_id = 0, $start_time = 0)
    {
        if (! $this->getSystemAuthorization()->isUserAuthorized() || ! $blog_id ) {
            return $ret;
        }
        
        if (! isset($ret['user_equivalence'])) {
            return $ret;
        }
        $user_equivalence = $ret['user_equivalence'];    
        $this->getSystemFunctions()->switchToBlog($blog_id);                
        list($ret, $taxonomies) = $this->getUsersTaxonomy($ret, $blog_id);
        
        if ( ! is_array($taxonomies) || empty($taxonomies)) {
            do_action('prime_mover_log_processed_events', 'No user taxonomies, skipping..', $blog_id, 'import', __FUNCTION__, $this);
            return $ret;
        }
        
        do_action('prime_mover_log_processed_events', 'User taxonomies for import: ', $blog_id, 'import', __FUNCTION__, $this);
        do_action('prime_mover_log_processed_events', $taxonomies, $blog_id, 'import', __FUNCTION__, $this);
        
        list($ret, $updated_count_array, $updated_count) = $this->doUpdatedCount($ret);       
        $this->doProgressUserTaxonomyImport($updated_count);        
        foreach ($taxonomies as $k => $taxonomy) {         
            
            list($ret, $offset) = $this->getUserTaxMainOffset($ret);
            list($ret, $processed_object_ids) = $this->getProcessedObjectIds($ret);
            
            while ($term_taxonomy_id = $this->getTermTaxonomyId($taxonomy, $offset)) {
                if (empty($term_taxonomy_id)) {
                    break;
                } else {                        
                    list($ret, $object_offset) = $this->getObjectOffset($ret);
                    $term_taxonomy_id = reset($term_taxonomy_id);                    
                    while($object_ids = $this->getUsersInTerms($term_taxonomy_id, $object_offset)) {                        
                        if ( ! empty($object_ids)) {
                            foreach ($object_ids as $old_user_id) {                                
                                if ( ! isset($user_equivalence[$old_user_id] ) ) {
                                    continue;
                                }                                
                                list($processed_object_ids, $updated_count_array, $updated_count) = $this->processObjectIds($user_equivalence, $old_user_id, $term_taxonomy_id, $processed_object_ids, $taxonomy, 
                                    $updated_count_array, $updated_count);
                            }                        
                        }
                       
                        $object_offset = $object_offset + 5;                              
                        $this->maybeEnableUserImportExportTestMode(10, false);
                        $retry_timeout = apply_filters('prime_mover_retry_timeout_seconds', PRIME_MOVER_RETRY_TIMEOUT_SECONDS, __FUNCTION__);
                        if ( (microtime(true) - $start_time) > $retry_timeout) {                            
                            return $this->generateUserTaxRetryParameters($ret, $object_offset, $offset, $taxonomies, $processed_object_ids, $updated_count_array, $retry_timeout, $blog_id);
                        }
                    }
                }               
                $offset = $offset + 1;                   
            }            
            unset($taxonomies[$k]); 
        }        
        
        $ret = $this->cleanUpReturnVariable($ret);       
        $this->getSystemFunctions()->restoreCurrentBlog(); 
        
        return $ret;
    }

    /**
     * Get users taxonomy
     * @param array $ret
     * @param number $blog_id
     * @return array
     */
    protected function getUsersTaxonomy($ret = [], $blog_id = 0)
    {
        $taxonomies = $this->getUserTaxonomies($blog_id);        
        if (isset($ret['users_taxonomy'])) {
            $taxonomies = $ret['users_taxonomy'];
            unset($ret['users_taxonomy']);
        }               
        
        return [$ret, $taxonomies];
    }
    
    /**
     * Get user taxonomy main offset
     * @param array $ret
     * @return array
     */
    protected function getUserTaxMainOffset($ret = [])
    {
        $offset = 0;        
        if (isset($ret['users_tax_main_offset'])) {
            $offset = $ret['users_tax_main_offset'];
            unset($ret['users_tax_main_offset']);
        } 
        
        return [$ret, $offset];
    }
    
    /**
     * Get processed object ids
     * @param array $ret
     * @return array
     */
    protected function getProcessedObjectIds($ret = [])
    {
        $processed_object_ids = [];        
        if (isset($ret['users_processed_object_ids'])) {
            $processed_object_ids = $ret['users_processed_object_ids'];
            unset($ret['users_processed_object_ids']);
        }
        
        return [$ret, $processed_object_ids];
    }
    
    /**
     * Get object offset
     * @param array $ret
     * @return []
     */
    protected function getObjectOffset($ret = [])
    {
        $object_offset = 0;
        if (isset($ret['users_tax_object_offset'])) {
            $object_offset = (int)$ret['users_tax_object_offset'];
            unset($ret['users_tax_object_offset']);
        } 
        
        return [$ret, $object_offset];
    }
    
    /**
     * Process object ids
     * @param array $user_equivalence
     * @param number $old_user_id
     * @param number $term_taxonomy_id
     * @param array $processed_object_ids
     * @param string $taxonomy
     * @param array $updated_count_array
     * @param number $updated_count
     * @return array
     */
    protected function processObjectIds($user_equivalence = [], $old_user_id = 0, $term_taxonomy_id = 0, $processed_object_ids = [], $taxonomy = '', $updated_count_array = [], $updated_count = 0)
    {
        $new_user_id = $user_equivalence[$old_user_id];
        $this->deleteUserTermAssociation($old_user_id, $term_taxonomy_id, $processed_object_ids);
        $processed_object_ids = $this->insertUserTermAssociation($new_user_id, $term_taxonomy_id, $processed_object_ids);
        
        $updated_count_array[$taxonomy] = count($processed_object_ids);
        $updated_count = array_sum($updated_count_array);  
        
        return [$processed_object_ids, $updated_count_array, $updated_count];
    }
    
    /**
     * Do updated count
     * @param array $ret
     * @return array
     */
    protected function doUpdatedCount($ret = [])
    {
        $updated_count_array = [];
        $updated_count = 0;
        
        if (isset($ret['users_processed_user_tax_ids_done'])) {
            $updated_count_array = $ret['users_processed_user_tax_ids_done'];
            $updated_count = array_sum($ret['users_processed_user_tax_ids_done']);
            unset($ret['users_processed_user_tax_ids_done']);
        }   
        
        return [$ret, $updated_count_array, $updated_count];
    }
    
    /**
     * Do progress user taxonomy import
     * @param number $updated_count
     */
    protected function doProgressUserTaxonomyImport($updated_count = 0)
    {
        $update_usertax_progress = '';
        if ($updated_count) {
            $update_usertax_progress = sprintf(esc_html__('%d completed', 'prime-mover'), $updated_count);
        }
        $this->getProgressHandlers()->updateTrackerProgress(sprintf(esc_html__('Importing user taxonomies.. %s', 'prime-mover'), $update_usertax_progress), 'import' );
    }
    
    /**
     * Generate user taxonomy parameters
     * @param array $ret
     * @param number $object_offset
     * @param number $offset
     * @param array $taxonomies
     * @param array $processed_object_ids
     * @param array $updated_count_array
     * @param number $retry_timeout
     * @param number $blog_id
     * @return array
     */
    protected function generateUserTaxRetryParameters($ret = [], $object_offset = 0, $offset = 0, $taxonomies = [], $processed_object_ids = [], $updated_count_array = [], $retry_timeout = 0, $blog_id = 0)
    {
        $ret['users_tax_object_offset'] = $object_offset;
        $ret['users_tax_main_offset'] = $offset;
        $ret['users_taxonomy'] = $taxonomies;
        
        $ret['users_processed_object_ids'] = $processed_object_ids;
        $ret['users_processed_user_tax_ids_done'] = $updated_count_array;
        $this->getSystemFunctions()->restoreCurrentBlog();
        do_action('prime_mover_log_processed_events', "$retry_timeout seconds time out on updating user taxonomy" , $blog_id, 'import', __FUNCTION__, $this);
        
        return $ret;
    }
    
    /**
     * Clean up variable
     * @param array $ret
     * @return array
     */
    protected function cleanUpReturnVariable($ret = [])
    {
        if (isset($ret['users_tax_object_offset'])) {
            unset($ret['users_tax_object_offset']);
        }
        
        if (isset($ret['users_tax_main_offset'])) {
            unset($ret['users_tax_main_offset']);
        }
        
        if (isset($ret['users_taxonomy'])) {
            unset($ret['users_taxonomy']);
        }
        
        if (isset($ret['users_processed_object_ids'])) {
            unset($ret['users_processed_object_ids']);
        }
        
        if (isset($ret['users_processed_user_tax_ids_done'])) {
            unset($ret['users_processed_user_tax_ids_done']);
        }
        
        return $ret;
    }
    
    /**
     * Insert new term association and track results
     * @param number $object_id
     * @param number $term_taxonomy_id
     * @param array $processed_object_ids
     * @return array
     */
    protected function insertUserTermAssociation($object_id = 0, $term_taxonomy_id = 0, $processed_object_ids = [])
    {
        if (! $this->getSystemAuthorization()->isUserAuthorized()) {
            return $processed_object_ids;
        }        
        $object_id = (int)$object_id;
        $term_taxonomy_id = (int)$term_taxonomy_id;
        if ( ! $term_taxonomy_id || ! $object_id ) {
            return $processed_object_ids;
        }
        $result = false;
        global $wpdb;
        $data = ['object_id' => $object_id, 'term_taxonomy_id' => $term_taxonomy_id];
        $format = ['%d','%d'];
        
        $exist_query = "SELECT object_id FROM $wpdb->term_relationships WHERE object_id = %d AND term_taxonomy_id = %d";
        $exist_prepared = $wpdb->prepare($exist_query, $object_id, $term_taxonomy_id);
        $exist_call = $wpdb->get_var($exist_prepared);
        
        if ( $exist_call ) {            
            $processed_object_ids[$object_id] = $term_taxonomy_id;
            
        } else {
            $result = $wpdb->insert($wpdb->term_relationships, $data, $format);
        }
        
        if ($result) {
            $processed_object_ids[$object_id] = $term_taxonomy_id;
            
        }        
        return $processed_object_ids;
    }
    
    /**
     * Delete user term association
     * @param number $object_id
     * @param number $term_taxonomy_id
     * @param array $processed_object_ids
     * @return boolean|number|false
     */
    protected function deleteUserTermAssociation($object_id = 0, $term_taxonomy_id = 0, $processed_object_ids = [])
    {
        if (! $this->getSystemAuthorization()->isUserAuthorized()) {
            return false;
        }
        
        $term_taxonomy_id = (int)$term_taxonomy_id;
        $object_id = (int)$object_id;
        
        if ( ! $term_taxonomy_id || ! $object_id ) {
            return false;
        }
       
        if ( ! $this->maybeDeleteTermAssociation($processed_object_ids, $object_id, $term_taxonomy_id) ) {            
            return false;
        }        
        
        global $wpdb;        
        return $wpdb->delete($wpdb->term_relationships, ['object_id' => $object_id, 'term_taxonomy_id' => $term_taxonomy_id], ['%d', '%d']);
    }
    
    /**
     * Maybe delete term association
     * @param array $processed_object_ids
     * @param number $given_object_id
     * @param number $given_term_taxonomy_id
     * @return boolean
     */
    protected function maybeDeleteTermAssociation($processed_object_ids = [], $given_object_id = 0, $given_term_taxonomy_id = 0)
    {
        $associated_term_tax_id = 0;
        if (isset($processed_object_ids[$given_object_id]) && $given_term_taxonomy_id) {            
            $associated_term_tax_id = $processed_object_ids[$given_object_id];            
        }
        if ($associated_term_tax_id && $associated_term_tax_id === $given_term_taxonomy_id) {            
            return false;
        }
        return true;        
    }
    
    /**
     * Get terms wrapper, multisite global compatible
     * @param string $taxonomy
     * @param number $offset
     * @return []
     */
    protected function getTermTaxonomyId($taxonomy = '', $offset = 0)
    {        
        global $wpdb;
        /**
         * query ok
         */
        $query = "SELECT term_taxonomy_id FROM {$wpdb->term_taxonomy} WHERE taxonomy = %s ORDER BY term_taxonomy_id ASC LIMIT %d, 1";
        $prepared = $wpdb->prepare($query, $taxonomy, $offset);
        $results = $wpdb->get_results($prepared, ARRAY_A);        

        return wp_list_pluck($results, 'term_taxonomy_id');      
    }   
    
    /**
     * Get users in terms
     * @param number $term_taxonomy_id
     * @param number $offset
     * @return []
     */
    protected function getUsersInTerms($term_taxonomy_id = 0, $offset = 0)
    {
        global $wpdb;
       
        $query = "SELECT object_id FROM {$wpdb->term_relationships} WHERE term_taxonomy_id = %d ORDER BY object_id ASC LIMIT %d, 5";
        $prepared = $wpdb->prepare($query, $term_taxonomy_id, $offset);
        $results = $wpdb->get_results($prepared, ARRAY_A);        
        
        return wp_list_pluck($results, 'object_id'); 
    }
    
    /**
     * Get system authorization
     * @return \Codexonics\PrimeMoverFramework\classes\PrimeMoverSystemAuthorization
     */
    public function getSystemAuthorization()
    {
        return $this->getCliArchiver()->getSystemAuthorization();
    }    
    
    /**
     * Get system initialization
     * @return \Codexonics\PrimeMoverFramework\classes\PrimeMoverSystemInitialization
     */
    public function getSystemInitialization()
    {
        return $this->getCliArchiver()->getSystemInitialization();
    }
    
    /**
     * Get system functions
     * @return \Codexonics\PrimeMoverFramework\classes\PrimeMoverSystemFunctions
     */
    public function getSystemFunctions()
    {
        return $this->getCliArchiver()->getSystemFunctions();
    }
    
    /**
     * Get cli archiver
     * @return \Codexonics\PrimeMoverFramework\cli\PrimeMoverCLIArchive
     */
    public function getCliArchiver()
    {
        return $this->cli_archiver;
    } 
    
    /**
     * Maybe save user taxonomy if its implemented
     * @param boolean $single_site
     */
    public function maybeSaveUserTaxonomy($single_site = false)
    {
        if (! $this->getSystemAuthorization()->isUserAuthorized()) {
            return;
        }
        if ($this->maybeSkipUserTaxonomyInitialize($single_site)) {
            return;
        }        
        $multisite = false;
        $current_blog_id = 0;
        if (is_multisite()) {
            $multisite = true;
            $current_blog_id = get_current_blog_id();
        }        
        if ( ! $current_blog_id && $multisite) {
            return;
        }        
        if ($this->isHeadRequestInValid($single_site, $current_blog_id)) {
            return;
        }        
        $this->getSystemFunctions()->switchToBlog($current_blog_id);        
        $taxonomies = get_object_taxonomies('user');
        if ( ! is_array($taxonomies) || empty($taxonomies)) {
            do_action('prime_mover_log_processed_events', 'No user taxonomy detected', $current_blog_id, 'export', __FUNCTION__, $this);
            delete_option(self::PRIME_MOVER_USER_TAXONOMY_OPTION);
            return;
        }
        do_action('prime_mover_log_processed_events', 'User taxonomy detected, saving to dB: ', $current_blog_id, 'export', __FUNCTION__, $this);
        do_action('prime_mover_log_processed_events', $taxonomies, $current_blog_id, 'export', __FUNCTION__, $this);
        
        update_option(self::PRIME_MOVER_USER_TAXONOMY_OPTION, $taxonomies);
        $this->getSystemFunctions()->restoreCurrentBlog();
    }
    
    /**
     * Checks if head request is valid
     * @param boolean $single_site
     * @param number $current_blog_id
     * @return boolean
     */
    protected function isHeadRequestInValid($single_site = false, $current_blog_id = 0)
    {
        if ($single_site) {
            return false;
        }
        
        if ( ! is_multisite() || ! $current_blog_id) {
            return false;
        }
             
        $input_server = $this->getSystemInitialization()->getUserInput('server',
            [
                'HTTP_X_PRIME_MOVER_USERTAXONOMY' => FILTER_SANITIZE_STRING,
                'REQUEST_METHOD' => FILTER_SANITIZE_STRING
            ],
            'initialize_usertaxonomy_subsite', '', 0, true, true
            );       
        
        if ( ! $this->getSystemFunctions()->isHeadRequest($input_server) || empty($input_server['HTTP_X_PRIME_MOVER_USERTAXONOMY'] ) ) {
            return true;
        }
        
        $auth = $this->getSystemInitialization()->getAuthKey();
        $url = get_blogaddress_by_id($current_blog_id);
        $origin_signature = $auth . $url;
        $computed = hash('sha512', $origin_signature);
        
        return $input_server['HTTP_X_PRIME_MOVER_USERTAXONOMY'] !== $computed;        
    }
    
    /**
     * Maybe skip user taxonomy initialize
     * @param boolean $single_site
     * @return boolean
     */
    protected function maybeSkipUserTaxonomyInitialize($single_site = false)
    {
        $current_filter = current_filter();
        if ( ! is_multisite() && 'wp_loaded' === $current_filter) {
            return true;
        }
        
        if ( ! is_multisite() && ! $single_site ) {
            return true;
        }
        if (is_multisite() && is_main_site()) {
            return true;
        }
        
        return false;        
    }
    
    /**
     * Maybe initialize user taxonomies
     * @param number $blogid_to_export
     * @param array $ret
     * @mainsitesupport_affected
     */
    public function maybeInitializeUserTaxonomies($blogid_to_export = 0, $ret = [])
    {
        if ( ! $blogid_to_export) {
            return;
        }
        $blog_id = 0;
        if (isset($ret['prime_mover_export_targetid']) ) {
            $blog_id = $ret['prime_mover_export_targetid'];
        }        
        $blog_id = (int)$blog_id;                    
        if (!empty($ret['prime_mover_export_type']) && 'single-site' === $this->getSystemFunctions()->generalizeExportTypeBasedOnGiven($ret)) {
            return;
        } 
        
        $this->getProgressHandlers()->updateTrackerProgress(esc_html__('Initializing taxonomy..', 'prime-mover'), 'export' );
        if (is_multisite()) {
            $cookies = [];
            foreach ( $_COOKIE as $name => $value ) {
                $cookies[] = new \WP_Http_Cookie( ['name' => $name, 'value' => $value] );
            }
            
            $url = get_blogaddress_by_id($blogid_to_export );
            if ( ! $url ) {
                return;
            }
            do_action('prime_mover_log_processed_events', 'In multisite, calling head to initialize user taxonomies', $blogid_to_export, 'export', __FUNCTION__, $this);
            
            $params = $this->generateHeadParams($cookies, $url);
            wp_remote_head($url, $params); 
            
        } else {
            do_action('prime_mover_log_processed_events', 'In single site, initializing user taxonomies and saving to db', $blogid_to_export, 'export', __FUNCTION__, $this);
            $this->maybeSaveUserTaxonomy(true);            
        }               
    }
    
    /**
     * Generate head params
     * @param array $cookies
     * @param string $url
     * @return []
     */
    protected function generateHeadParams($cookies = [], $url = '')
    {
        $params = ['reject_unsafe_urls' => false, 'sslverify' => false, 'cookies' => $cookies];
        $auth = $this->getSystemInitialization()->getAuthKey();
        $origin_signature = $auth . $url;
        
        $params['headers'] = ['X-Prime-Mover-UserTaxonomy'=> hash('sha512', $origin_signature)];
        return $params;
    }
    
    /**
     * Maybe enable user import test mode
     * @param number $sleep
     * @param boolean $microsleep
     */
    public function maybeEnableUserImportExportTestMode($sleep = 0, $microsleep = false)
    {
        if ( ! defined('PRIME_MOVER_USERIMPORTEXPORT_TEST_MODE') ) {
            return;
        }
        if (  true === PRIME_MOVER_USERIMPORTEXPORT_TEST_MODE ) {
            $this->getSystemInitialization()->setProcessingDelay($sleep, $microsleep);
        }        
    }
}
