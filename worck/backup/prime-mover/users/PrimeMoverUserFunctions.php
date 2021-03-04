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

use SplFixedArray;

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Prime Mover User Functions
 * Provides very basic functions for user import and export processes.
 */
class PrimeMoverUserFunctions
{         
    private $user_queries;
    
    const DEFAULT_USER_FILE = 'users.json';
    const ENCRYPTED_USER_FILE = 'users.json.enc';
    const SPECIAL_USER_META_FILE = 'usersmeta.json';
    
    /**
     * Constructor
     * @param PrimeMoverUserQueries $user_queries
     */
    public function __construct(PrimeMoverUserQueries $user_queries)
    {  
        $this->user_queries = $user_queries;
    }
    
    /**
     * Get user queries
     * @return \Codexonics\PrimeMoverFramework\users\PrimeMoverUserQueries
     */
    public function getUserQueries()
    {
        return $this->user_queries;
    }
    
    /**
     * Get progress handlers
     * @return \Codexonics\PrimeMoverFramework\classes\PrimeMoverProgressHandlers
     */
    public function getProgressHandlers()
    {
        return $this->getUserQueries()->getCliArchiver()->getProgressHandlers();
    }
    
    /**
     * Update user taxonomy
     * @param array $ret
     * @param number $blog_id
     * @param number $start_time
     * @return array|number
     */
    public function updateUserTaxonomy($ret = [], $blog_id = 0, $start_time = 0)
    {
        return $this->getUserQueries()->updateUserTaxonomy($ret, $blog_id, $start_time);
    }
    
    /**
     * Get special user meta keys from json file
     * @param array $ret
     * @param number $blogid_to_import
     * @return array
     */
    public function getSpecialUserMetaKeysFromJsonFile($ret = [], $blogid_to_import = 0)
    {
        if ( ! $this->getSystemAuthorization()->isUserAuthorized()) {
            return $ret;
        }
        global $wp_filesystem;
        if (empty($ret['unzipped_directory'])) {
            return $ret;
        }
        $unzipped_dir = $ret['unzipped_directory'];
        $path = $this->getSpecialMetaKeysImportFile($unzipped_dir);
        if ( ! $path) {  
            do_action('prime_mover_log_processed_events', "Special meta keys path does not exist, skipping..", $blogid_to_import, 'import', __FUNCTION__, $this);
            return $ret;            
        }
        $this->getProgressHandlers()->updateTrackerProgress(esc_html__('Preparing user meta keys..', 'prime-mover'));
        $special_user_meta_keys = $wp_filesystem->get_contents($path);
        $special_user_meta_keys = trim($special_user_meta_keys);
        if ( ! $special_user_meta_keys ) {
            do_action('prime_mover_log_processed_events', "No special meta keys path found, skipping..", $blogid_to_import, 'import', __FUNCTION__, $this);
            return $ret;
        }
        $decoded = json_decode($special_user_meta_keys, true); 
        if ( ! is_array($decoded) ) {
            return $ret;
        }
        $ret['usermeta_keys_import_adjust'] = $decoded;
        return $ret;
    }
    
    /**
     * Restore pass
     * @param string $original_pass
     * @param array $ret
     */
    public function restoreOriginalPass($original_pass = '', $ret = [])
    {
        add_filter('wp_pre_insert_user_data', function($data = [], $update = false, $id) use ($original_pass, $ret) {
            if ( ! $this->getSystemAuthorization()->isUserAuthorized()) {
                return $data;
            }
            if ( ! $original_pass || empty($ret)) {
                return $data;
            }
            if (empty($data['user_email'])) {
                return $data;
            }
            if ($this->isUserCurrentlyLoggedIn($data['user_email'])) {
                return $data;
            }
            if ( ! isset($data['user_pass'] ) ) {
                return $data;
            }
            $data['user_pass'] = $original_pass;
            $import_blog_id = $this->getSystemInitialization()->getImportBlogID();
            
            do_action('prime_mover_log_processed_events', $data['user_email'] . " user pass restored", $import_blog_id, 'import', 'restoreOriginalPass', $this, false, true);
            return $data;
            
        }, 23, 3);
    }
    
    /**
     * Returns true if current logged-in user email matches with the given email
     * @param string $given_email
     * @return boolean
     */
    protected function isUserCurrentlyLoggedIn($given_email = '')
    {
        $current_user = wp_get_current_user();
        return $current_user->user_email === $given_email;        
    }
    
    /**
     * Remove original pass filter
     */
    public function removeOriginalPass()
    {
        remove_all_filters('wp_pre_insert_user_data', 23);
    }
    
    /**
     * Update post authors
     * @param SplFixedArray $user_equivalence
     * @param number $total_post_count
     * @param number $blog_id
     * @param number $start_time
     * @param array $ret
     * @return void|number|boolean
     */
    public function updatePostAuthors(SplFixedArray $user_equivalence, $total_post_count = 0, $blog_id = 0, $start_time = 0, $ret = [])
    {        
        if (! $this->getSystemAuthorization()->isUserAuthorized()) {
            return;
        }
        
        $this->getSystemFunctions()->switchToBlog($blog_id);
        global $wpdb;
        
        $wpdb->flush();
        $offset = 0;
        if (isset($ret['updating_authors_offset'])) {
            $offset = (int)$ret['updating_authors_offset'];
        }
        $query = "SELECT ID, post_author FROM {$wpdb->posts} ORDER BY ID ASC LIMIT %d, 30";
        $prepared = $wpdb->prepare($query, $offset);
        
        $posts_updated = 0;
        if (isset($ret['posts_updated'])) {
            $posts_updated = $ret['posts_updated'];
        }
        
        $update_authors_progress = '';
        if ($posts_updated) {            
            $update_authors_progress = sprintf(esc_html__('%d completed', 'prime-mover'), $posts_updated);
        }
        $this->getProgressHandlers()->updateTrackerProgress(sprintf(esc_html__('Updating authors.. %s', 'prime-mover'), $update_authors_progress), 'import' );               
        
        while ( $results = $wpdb->get_results($prepared, ARRAY_A) ) {            
            if (empty($results)) {
                break;                
            } else { 
                $ret = $this->updatePostAuthor($results, $user_equivalence, $ret);             
            }           
            $offset = $offset + 30;
            $query = "SELECT ID, post_author FROM {$wpdb->posts} ORDER BY ID ASC LIMIT %d, 30";
            $prepared = $wpdb->prepare($query, $offset);            
                           
            $retry_timeout = apply_filters('prime_mover_retry_timeout_seconds', PRIME_MOVER_RETRY_TIMEOUT_SECONDS, __FUNCTION__);
            if ( (microtime(true) - $start_time) > $retry_timeout) {
                $ret['updating_authors_offset'] = $offset;
                $this->getSystemFunctions()->restoreCurrentBlog();                
                do_action('prime_mover_log_processed_events', "$retry_timeout seconds time out on updating user authors" , $blog_id, 'import', __FUNCTION__, $this);
               
                return $ret;
            }
        }
        
        if (isset($ret['updating_authors_offset'])) {
            unset($ret['updating_authors_offset']);    
        }
        
        $this->getSystemFunctions()->restoreCurrentBlog();
        return $ret;
    }
    
    /**
     * Update post author
     * @param array $results
     * @param SplFixedArray $user_equivalence
     * @param array $ret
     * @return array
     */
    protected function updatePostAuthor($results = [], SplFixedArray $user_equivalence, $ret = [])
    {
        if (empty($results)) {
            return;
        }
        $posts_updated = 0;
        if (isset($ret['posts_updated'])) {
            $posts_updated = (int)$ret['posts_updated'];
        }        
        
        foreach ($results as $result) {
            if ( ! isset($result['ID']) || ! isset($result['post_author'] ) ) {
                continue;
            }
            $post_id = (int) $result['ID'];            
            $post_author = (int) $result['post_author'];
            
            if ( ! $post_id || ! $post_author ) {
                continue;
            }
            
            if ( ! isset($user_equivalence[$post_author])) {
                continue;
            }
            
            $new_author = (int)$user_equivalence[$post_author];
            if ( ! $new_author ) {
                continue;
            }
            if ($new_author === $post_author) {
                continue;
            }            
            $this->getUserQueries()->maybeEnableUserImportExportTestMode(100000, true);
            $arg = [
                'ID' => $post_id,
                'post_author' => $new_author,
            ];
            $post_id = wp_update_post( $arg );
            if ( ! is_wp_error($post_id) || ! $post_id ) {
                $posts_updated++;                
            }
        }   
        $ret['posts_updated'] = $posts_updated;
        
        return $ret;
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
        return $this->getUserQueries()->getCliArchiver();
    }   
    
    /**
     * Get user maximum author ID
     * @return string|NULL
     */
    public function countUserMaxId()
    {
        global $wpdb;
        return $wpdb->get_var("SELECT MAX(post_author) FROM {$wpdb->prefix}posts");
    }
    
    /**
     * Count total posts
     * @return string|NULL
     */
    public function countTotalPosts()
    {
        global $wpdb;
        return $wpdb->get_var("SELECT count(ID) FROM {$wpdb->prefix}posts");
    }
    
    /**
     * add New element to SPLFixedArray
     * @param SplFixedArray $array
     * @param number $index
     * @param number $data
     * @return SplFixedArray
     */
    public function addNewElement(SplFixedArray $array, $index = 0, $data = 0)
    {                
        return $this->getSystemFunctions()->addNewElement($array, $index, $data);
    }
    
    /**
     * Generate user export file name
     * @return string
     */
    public function generateUserExportFileName()
    {        
        if ($this->getSystemInitialization()->getMaybeEncryptExportData()) {
            return self::ENCRYPTED_USER_FILE;
        } else {
            return self::DEFAULT_USER_FILE;
        }
    }
    
    /**
     * Generate user meta export file name
     * @return string
     */
    public function generateUserMetaExportFileName()
    {
        return self::SPECIAL_USER_META_FILE;
    }
    
    /**
     * Get user export 
     * @param string $unzipped_dir
     * @return boolean|boolean[]|string[]
     */
    public function getUsersExportFilePath($unzipped_dir = '')
    {
        global $wp_filesystem;                
        if ( ! $unzipped_dir || ! $wp_filesystem->exists($unzipped_dir)) {
            return false;
        }
        
        $default_file = $unzipped_dir . self::DEFAULT_USER_FILE;
        $encrypted_file = $unzipped_dir . self::ENCRYPTED_USER_FILE;
        
        if ($wp_filesystem->exists($default_file)) {
            return [$default_file, false];
        }
        
        if ($wp_filesystem->exists($encrypted_file)) {
            return [$encrypted_file, true];
        }
        
        return false;
    }
    
    /**
     * Get special meta keys import file
     * @param string $unzipped_dir
     * @return boolean|string
     */
    public function getSpecialMetaKeysImportFile($unzipped_dir = '')
    {
        global $wp_filesystem;
        if ( ! $unzipped_dir || ! $wp_filesystem->exists($unzipped_dir)) {
            return false;
        }
        
        $usermeta_file = $unzipped_dir . self::SPECIAL_USER_META_FILE;        
        if ($this->getSystemFunctions()->nonCachedFileExists($usermeta_file)) {
            return $usermeta_file;
        }        
        return false;
    }
    
    /**
     * Gets affected user meta keys on a database table matching the "given_db_prefix"
     * @param string $given_db_prefix
     * @param number $blog_id
     * @return array
     * @mainsitesupport_affected
     */
    public function getAffectedUserMetaGivenPrefix($given_db_prefix = '', $blog_id = 0)
    {
        if (! $blog_id ) {
            return [];
        }

        if ( ! $given_db_prefix ) {
            $given_db_prefix = $this->getSystemFunctions()->getDbPrefixOfSite($blog_id);
        }
        
        global $wpdb;      
        $escaped_like = $wpdb->esc_like($given_db_prefix);
        $target_prefix = $escaped_like . '%';
        
        $main_site_id = 1;
        if (is_multisite()) {
            $main_site_id = $this->getSystemInitialization()->getMainSiteBlogId();
        }        
        $db_prefix = $wpdb->get_blog_prefix($main_site_id);      
        if ($this->getSystemFunctions()->isMultisiteMainSite($blog_id)) {
            $regex = $escaped_like . '[0-9]+';
            $db_search = "SELECT DISTINCT meta_key FROM {$db_prefix}usermeta where meta_key LIKE %s AND meta_key NOT REGEXP %s";
            $prepared = $wpdb->prepare($db_search, $target_prefix, $regex);
            $user_meta_keys = $wpdb->get_results($prepared, ARRAY_A);
            
        } else {           
            $db_search = "SELECT DISTINCT meta_key FROM {$db_prefix}usermeta where meta_key LIKE %s";
            $user_meta_keys = $wpdb->get_results($wpdb->prepare($db_search, $target_prefix), ARRAY_A);            
        }        
        
        if (empty($user_meta_keys)) {
            return [];
        }
        
        return wp_list_pluck($user_meta_keys, 'meta_key');
    }
    
    /**
     * Delete cap user metas
     * @param number $user_id
     * @param string $target_cap_prefix
     * @param string $target_level_prefix
     */
    public function deleteCapUserMetas($user_id = 0, $target_cap_prefix = '', $target_level_prefix = '')
    {
        if (! $this->getSystemAuthorization()->isUserAuthorized()) {
            return;
        }
        delete_user_meta($user_id, $target_cap_prefix);
        delete_user_meta($user_id, $target_level_prefix);    
    }
    
    /**
     * Update Cap user metas
     * @param number $user_id
     * @param string $current_cap_prefix
     * @param string $new_cap
     * @param string $current_level_prefix
     * @param string $new_user_level
     */
    public function updateCapUserMetas($user_id = 0, $current_cap_prefix = '', $new_cap = '', $current_level_prefix = '', $new_user_level = '')
    {
        if (! $this->getSystemAuthorization()->isUserAuthorized()) {
            return;
        }
        update_user_meta($user_id, $current_cap_prefix, $new_cap);
        update_user_meta($user_id, $current_level_prefix, $new_user_level);
    }
    
    /**
     * Generate user meta keys to adjust on export
     * @param array $ret
     * @param number $blog_id
     * @return array
     */
    public function generateUserMetaKeysToAdjustOnExport($ret = [], $blog_id = 0)
    {
        $user_meta_keys = $this->getAffectedUserMetaGivenPrefix('', $blog_id);
        if ( ! is_array($user_meta_keys) || empty($user_meta_keys) || ! isset($ret['randomizedbprefixstring'])) {
            return $ret;
        }
        
        $new_user_meta_keys = [];
        $current_db_prefix = $this->getSystemFunctions()->getDbPrefixOfSite($blog_id);
        $randomized_db_prefix = $ret['randomizedbprefixstring'];
        
        foreach ($user_meta_keys as $user_meta_key) {
            $prefix_free = $this->getSystemFunctions()->removePrefix($current_db_prefix, $user_meta_key);
            if ($prefix_free === $user_meta_key) {
                continue;
            }
            
            $new_meta_key = $randomized_db_prefix . $prefix_free;
            $new_user_meta_keys[$user_meta_key] = $new_meta_key;
        }
        
        return $new_user_meta_keys;
    }
    
    /**
     * Checks if zip package now includes user files
     * @param string $tmp_path
     * @param string $ret_mode
     * @return string|boolean|string
     */
    public function isZipPackageIncludeUsers($tmp_path = '', $ret_mode = 'bool')
    {
        $pos = true;
        $neg = false;
        if ('txt' === $ret_mode) {
            $pos = esc_html__('Yes', 'prime-mover');
            $neg = esc_html__('No', 'prime-mover');
        }
        if ( ! $tmp_path ) {
            return $neg;
        }
  
        $user_files = [self::DEFAULT_USER_FILE, self::ENCRYPTED_USER_FILE, self::SPECIAL_USER_META_FILE];       
        $za = $this->getSystemFunctions()->getZipArchiveInstance();
        
        $zip = $za->open($tmp_path);
        $opened = false;
        if (true === $zip) {
            $opened = true;
        }
        if ( ! $opened ) {
            return $neg;
        }
        foreach ($user_files as $user_file) {
            if (false !== $za->locateName($user_file, \ZIPARCHIVE::FL_NODIR)) {
                return $pos;
            }
        }
        return $neg;
    }
}
