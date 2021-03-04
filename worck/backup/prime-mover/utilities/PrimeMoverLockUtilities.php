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

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Prime Mover Lock Utilities
 * Helper functionality for locking resources during export/import processes
 *
 */
class PrimeMoverLockUtilities
{        
    /**
     * Open lock file
     * @param string $lock_file
     * @return boolean|resource handle
     * @codeCoverageIgnore
     */
    public function openLockFile($lock_file = '', $render_absolute = true)
    {
        if ( ! $lock_file ) {
            return false;
        }
        global $wp_filesystem;
        if ($render_absolute) {
            $lock_file_path = $wp_filesystem->abspath() . $lock_file;
        } else {
            $lock_file_path = $lock_file;
        }
        
        return @fopen($lock_file_path, "wb");
    }
    
    /**
     * Create lock file using native PHP flock
     * @param $fp
     * @return boolean
     * @codeCoverageIgnore
     */
    public function createProcessLockFile($fp)
    {
        return flock($fp, LOCK_EX);
    }
    
    /**
     * Unlock file after processing
     * @codeCoverageIgnore
     */
    public function unLockFile($fp)
    {
        return flock($fp, LOCK_UN);
    }
    
    /**
     * Close dropbox lock
     * @param $fp
     * @codeCoverageIgnore
     */
    public function closeLock($fp)
    {
        @fclose($fp);
    }    
}