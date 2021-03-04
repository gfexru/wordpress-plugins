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

use Codexonics\PrimeMoverFramework\classes\PrimeMoverImporter;

if (! defined('ABSPATH')) {
    exit;
}
/**
 *
/**
 * Extended Duplicator Search Replace Class
 * Standalone and removed reporting, logging methods.
 *
 * @package PrimeMoverFramework\utilities
 * @link https://github.com/lifeinthegrid/duplicator Duplicator GitHub Project
 * @link http://www.lifeinthegrid.com/duplicator/
 * @link http://www.snapcreek.com/duplicator/
 * @author Snap Creek
 * @author Codexonics
 * @copyright 2011-2017  SnapCreek LLC
 * @license GPLv2 or later

 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 * SOURCE CONTRIBUTORS:
 * David Coveney of Interconnect IT Ltd
 * https://github.com/interconnectit/Search-Replace-DB/
 */
final class PrimeMoverSearchReplace extends DupxUpdateEngine
{
    /**
     * Do search and replace
     * @param $dbh
     * @param array $list
     * @param array $tables
     * @param boolean $fullsearch
     * @param PrimeMoverImporter $importer
     * @param array $excluded_columns
     * @param number $start_time
     * @param array $ret
     * @return void|number|string|array
     * @codeCoverageIgnore
     */
    public static function load($dbh, $list = [], $tables = [], $fullsearch = false, PrimeMoverImporter $importer = null, $excluded_columns = [], $start_time = 0, $ret = [])
    {
        //ok
        if ( ! $importer->getSystemAuthorization()->isUserAuthorized() ) {
            return;
        }
        //ok      
        try {
            $walk_function = function () use (&$str) {
                $str = "`$str`";
            };
        } catch (\Exception $exc) {
        }        
        //ok
        if (isset($ret['srch_rplc_original_tables_count'])) {
            self::logSearchReplaceHeader($importer, $ret, $list, $tables, "Search replace doing another retry processing");            
        } else {            
            $ret['srch_rplc_original_tables_count'] = count($tables);
            self::logSearchReplaceHeader($importer, $ret, $list, $tables, "Search replace doing process the first time, ret: ");
        }
        //ok
        $table_with_excluded_column = '';
        $excluded_column = '';
        if (is_array($excluded_columns) && ! empty($excluded_columns) ) {
            $table_with_excluded_column = key($excluded_columns);
            $excluded_column = reset($excluded_columns);
        }
        
        /**
         * DEFINE TOTAL ROWS PROCESSED
         * THIS IS THE TOTAL ROWS PROCESSED FOR THE ENTIRE SEARCH REPLACE PROCESS
         */
        if (isset($ret['ongoing_srch_rplc_rows_processed'])) {
            $total_rows_processed = $ret['ongoing_srch_rplc_rows_processed'];
        } else {
            $total_rows_processed = 0;
        }        
        
        $is_already_timeout = false;
        if (is_array($tables) && !empty($tables)) {
            foreach ($tables as $key => $table) {   
                
                do_action('prime_mover_log_processed_events', "Doing search replace on $table" , $ret['blog_id'], 'import', 'load', 'PrimeMoverSearchReplace');
                $columns = [];               
                $fields = mysqli_query($dbh, 'DESCRIBE '.$table);
                while ($column	 = mysqli_fetch_array($fields)) {
                    $columns[$column['Field']] = $column['Key'] == 'PRI' ? true : false;
                }
                
                if ($table_with_excluded_column === $table && array_key_exists($excluded_column, $columns)) {
                    unset($columns[$excluded_column]);
                }

                $row_count = mysqli_query($dbh, "SELECT COUNT(*) FROM `{$table}`");
                $rows_result = mysqli_fetch_array($row_count);
                @mysqli_free_result($row_count);
                
                $row_count = $rows_result[0];
                if ($row_count == 0) {
                    unset($tables[$key]);                      
                    do_action('prime_mover_log_processed_events', "Table $table is skipped" , $ret['blog_id'], 'import', 'load', 'PrimeMoverSearchReplace');
                    continue;
                }

                $page_size = 25000;
                $offset = ($page_size + 1);
                $pages = ceil($row_count / $page_size);
                
                $colList = '*';                
                if (empty($colList)) {
                    unset($tables[$key]);                    
                    do_action('prime_mover_log_processed_events', "Table $table is skipped" , $ret['blog_id'], 'import', 'load', 'PrimeMoverSearchReplace');
                    continue;
                } 

                $init_page = 0;
                
                /**
                 * INITIALIZE rows processed for this table
                 */
                $current_row = 0;                
                
                if ( ! empty($ret['ongoing_srch_rplc_page_to_resume']) ) {
                    $init_page = (int)$ret['ongoing_srch_rplc_page_to_resume'];
                    
                    do_action('prime_mover_log_processed_events', "Resuming search replace on table $table at page $init_page" , $ret['blog_id'], 'import', 'load', 'PrimeMoverSearchReplace');
                    unset($ret['ongoing_srch_rplc_page_to_resume']);
                }     
                
                for ($page = $init_page; $page < $pages; $page++) {                  
                    do_action('prime_mover_log_processed_events', "Doing page transaction on table $table at page $page" , $ret['blog_id'], 'import', 'load', 'PrimeMoverSearchReplace');
                    
                    
                    $start = $page * $page_size;          
                    $resume_mode = false;
                    if ( ! empty($ret['ongoing_srch_rplc_row_to_resume']) ) {
                        $start = $ret['ongoing_srch_rplc_row_to_resume'];                         
                        unset($ret['ongoing_srch_rplc_row_to_resume']);
                        $resume_mode = true;
                    }
                    
                    if ($resume_mode && $start) {
                        $current_row = $start - 1;
                    }
                    
                    $sql = sprintf("SELECT {$colList} FROM `%s` LIMIT %d, %d", $table, $start, $offset);                    
                    
                    do_action('prime_mover_log_processed_events', "Running this select query for page transaction: " , $ret['blog_id'], 'import', 'load', 'PrimeMoverSearchReplace');
                    do_action('prime_mover_log_processed_events', $sql , $ret['blog_id'], 'import', 'load', 'PrimeMoverSearchReplace');
                    
                    $data = mysqli_query($dbh, $sql);                   
 
                    while ($row = mysqli_fetch_array($data)) {                         
                        $upd_sql = [];
                        $where_sql = [];
                        $upd = false;
                        $serial_err = 0;
                        $is_unkeyed = !in_array(true, $columns);

                        foreach ($columns as $column => $primary_key) {
                            $edited_data = $data_to_fix = $row[$column];
                            $base64converted = false;
                            $txt_found = false;

                            if ($is_unkeyed && ! empty($data_to_fix)) {
                                $where_sql[] = $column.' = "'.mysqli_real_escape_string($dbh, $data_to_fix).'"';
                            }
                            
                            if (!empty($row[$column]) && !is_numeric($row[$column]) && $primary_key != 1) {                                
                                if (base64_decode($row[$column], true)) {
                                    $decoded = base64_decode($row[$column], true);
                                    if (self::isSerialized($decoded)) {
                                        $edited_data = $decoded;
                                        $base64converted = true;
                                    }
                                }

                                foreach ($list as $item) {
                                    if (strpos($edited_data, $item['search']) !== false) {
                                        $txt_found = true;
                                        break;
                                    }
                                }
                                if (!$txt_found) {
                                    continue;
                                }

                                foreach ($list as $item) {
                                    $edited_data = self::recursiveUnserializeReplace($item['search'], $item['replace'], $edited_data);
                                }
                                
                                $serial_check = self::fixSerialString($edited_data);
                                if ($serial_check['fixed']) {
                                    $edited_data = $serial_check['data'];
                                } elseif ($serial_check['tried'] && !$serial_check['fixed']) {
                                    $serial_err++;
                                }
                            }
                            
                            if ($edited_data != $data_to_fix || $serial_err > 0) {                                
                                if ($base64converted) {
                                    $edited_data = base64_encode($edited_data);
                                }                                
                                $upd_sql[] = $column.' = "'.mysqli_real_escape_string($dbh, $edited_data).'"';
                                $upd = true;
                            }

                            if ($primary_key) {
                                $where_sql[] = $column.' = "'.mysqli_real_escape_string($dbh, $data_to_fix).'"';
                            }
                        }
                        
                        if ($upd && !empty($where_sql)) {
                            do_action('prime_mover_log_processed_events', "Running this UPDATE query for column $column transaction: " , $ret['blog_id'], 'import', 'load', 'PrimeMoverSearchReplace');
                            $sql = "UPDATE `{$table}` SET ".implode(', ', $upd_sql).' WHERE '.implode(' AND ', array_filter($where_sql));
                            do_action('prime_mover_log_processed_events', "DONE QUERY: $sql" , $ret['blog_id'], 'import', 'load', 'PrimeMoverSearchReplace');
                            
                            mysqli_query($dbh, $sql);                   
                            self::testSearchDelay();                           
                        }
                        
                        /**
                         * REVISION 2.0
                         * THIS CAN BE A LONG PROCESS
                         * LET'S CHECK THE ELAPSED TIME
                         */
                        
                        /**
                         * AT THIS TIME THE "ROW" IS COMPLETELY PROCESSED
                         * INCREMENT COUNTER
                         * $current_row is the total amount of rows processed IN THIS TABLE
                         * THIS IS RESETTED AFTER THE TABLE IS PROCESSED
                         */
                        $current_row++;
                        
                        /**
                         * ALSO INCREMENT THE TOTAL ROWS PROCESSED
                         */
                        $total_rows_processed++;
                        
                        $elapsed = microtime(true) - $start_time;
                        
                        /**
                         * WE NEED TO CHECK IF THIS IS ALREADY RUNNING FOR MORE THAN 30 SECONDS ALREADY
                         */
                        $retry_timeout = apply_filters('prime_mover_retry_timeout_seconds', PRIME_MOVER_RETRY_TIMEOUT_SECONDS, 'searchAndReplace');
                        if ($elapsed > $retry_timeout) {
                            /**
                             * YES MORE THAN 30 SECONDS HAS ELAPSED, BREAK THE LOOP AND REMEMBER THE DETAILS
                             */
                            $is_already_timeout = true;
                            break;
                        } 
                    }                    
                    
                    /**
                     * AT THIS POINT ALL ROWS OF THE PAGE BATCH IS COMPLETELY PROCESSED
                     * BUT THIS DOES NOT MEAN THE TABLE IS DONE PROCESSING
                     */
                    /**
                     * CLEAR DATA
                     */
                    @mysqli_free_result($data);                     
                    
                    if ($is_already_timeout) {
                        /**
                         * WE NEED TO HANDLE TIMEOUTS
                         */
                        return self::doRetrySearchReplace($page, $pages, $tables, $key, $importer, $ret, $total_rows_processed, $elapsed, $current_row);       
                    }              
                }
                
                if (isset($tables[$key])) {
                    unset($tables[$key]);                
                }    
            }            
        }        
                
        return self::markSearchReplaceComplete($ret, $importer);
    }
 
    /**
     * Do retry search replace helper
     * @param number $page
     * @param number $pages
     * @param array $tables
     * @param number $key
     * @param PrimeMoverImporter $importer
     * @param array $ret
     * @param number $total_rows_processed
     * @param number $elapsed
     * @param number $current_row
     * @return array
     * @codeCoverageIgnore
     */
    private static function doRetrySearchReplace($page = 0, $pages = 0, $tables = [], $key = 0, PrimeMoverImporter $importer, $ret = [], $total_rows_processed = 0, $elapsed = 0, $current_row = 0)
    {        
        //ok
        $ret['ongoing_srch_rplc_page_to_resume'] = $page;        
        
        //ok
        if ($current_row) {
            $ret['ongoing_srch_rplc_row_to_resume'] = $current_row;
        } 
        
        //ok
        $ret['ongoing_srch_rplc_remaining_tables'] = $tables;
        $percent_string = esc_html__('Starting...', 'prime-mover');
        
        //ok
        $total_rows_database = $ret['main_search_replace_total_rows_count'];        
        $percent = 0;
        
        //ok
        if ($total_rows_processed < $total_rows_database) {
            $percent = round(($total_rows_processed /$total_rows_database) * 100, 2);
        }
        //ok
        if ($total_rows_processed > $total_rows_database) {
            $percent = 99.5;
        }
        
        //ok
        if ($percent) {
            $percent_string = $percent . '%' . ' '. esc_html__('done', 'prime-mover');
        }
        
        //ok
        $ret['ongoing_srch_rplc_percent'] = $percent_string;
        $ret['ongoing_srch_rplc_rows_processed'] = $total_rows_processed;
        
        do_action('prime_mover_log_processed_events', "Retry search replace after $elapsed seconds elapsed time with the following parameters : " , $ret['blog_id'], 'import', 'doRetrySearchReplace', 'PrimeMoverSearchReplace');
        do_action('prime_mover_log_processed_events', $ret , $ret['blog_id'], 'import', 'doRetrySearchReplace', 'PrimeMoverSearchReplace');
        
        return $ret;
    }
    
    /**
     * Test search delay
     * @codeCoverageIgnore
     */
    private static function testSearchDelay()
    {
        if (defined('PRIME_MOVER_DELAY_SRCH_REPLACE') && PRIME_MOVER_DELAY_SRCH_REPLACE) {
            usleep(PRIME_MOVER_DELAY_SRCH_REPLACE);
        }
    }
    
    /**
     * Log First time search and replace
     * @param PrimeMoverImporter $importer
     * @param array $ret
     * @param array $list
     * @param array $tables
     * @param string $text
     * @codeCoverageIgnore
     */
    private static function logSearchReplaceHeader(PrimeMoverImporter $importer, $ret = [], $list = [], $tables = [], $text = '')
    {        
        do_action('prime_mover_log_processed_events', $text , $ret['blog_id'], 'import', 'logSearchReplaceHeader', 'PrimeMoverSearchReplace');
        do_action('prime_mover_log_processed_events', $ret , $ret['blog_id'], 'import', 'logSearchReplaceHeader', 'PrimeMoverSearchReplace');
        do_action('prime_mover_log_processed_events', "Search and replace master list : " , $ret['blog_id'], 'import', 'logSearchReplaceHeader', 'PrimeMoverSearchReplace');
        do_action('prime_mover_log_processed_events', $list , $ret['blog_id'], 'import', 'logSearchReplaceHeader', 'PrimeMoverSearchReplace');
        do_action('prime_mover_log_processed_events', "Search replace tables: " , $ret['blog_id'], 'import', 'logSearchReplaceHeader', 'PrimeMoverSearchReplace');
        do_action('prime_mover_log_processed_events',$tables, $ret['blog_id'], 'import', 'logSearchReplaceHeader', 'PrimeMoverSearchReplace');
    }
    
    /**
     * Marked search replace complete and cleanup
     * @param array $ret
     * @param PrimeMoverImporter $importer
     * @return array
     * @codeCoverageIgnore
     */
    protected static function markSearchReplaceComplete($ret = [], PrimeMoverImporter $importer) {
        $ret['srch_rplc_completed'] = true;        
        do_action('prime_mover_log_processed_events',"All search replace done !", $ret['blog_id'], 'import', 'markSearchReplaceComplete', 'PrimeMoverSearchReplace');
        
        if (isset($ret['srch_rplc_original_tables_count'])) {
            unset($ret['srch_rplc_original_tables_count']);
        }
        if (isset($ret['ongoing_srch_rplc_page_to_resume'])) {
            unset($ret['ongoing_srch_rplc_page_to_resume']);
        }
        if (isset($ret['ongoing_srch_rplc_remaining_tables'])) {
            unset($ret['ongoing_srch_rplc_remaining_tables']);   
        }
        if (isset($ret['ongoing_srch_rplc_percent'])) {
            unset($ret['ongoing_srch_rplc_percent']);
        }
        
        return $ret;
    }    
}
