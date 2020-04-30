<?php
/**
 * File: Restore.php
 * Created: 31-07-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Backup;

use Project\Controllers\Admin\AdminBaseController;
use Story\Migration\Sqlite;

/**
 * Class Restore
 *
 * @package Project\Controllers\Admin\Backup
 */
class Restore extends AdminBaseController
{
    /**
     * @param string $file
     */
    public function get($file = '')
    {

        try {

            $migration = new Sqlite();
            $fileName = $migration->getBackupPath() . $file . '.gz';
            // check if the file is supplied
            if (!$file || !file_exists($fileName)) {
                throw new \Exception(_('No such backup.'));
            }

            ini_set('memory_limit', '384M');
            ini_set('max_execution_time', 300);
            set_time_limit(300);

            // Start database connection
            $db = load_database();

            $db->connect();

            // Set database connection
            $migration->db = $db;

            // Load table configuration
            $migration->tables = require(SP . 'config/migrations.php');
            unset($migration->tables['sessions']); // prevents logging out after restore

            ob_start();
            $migration->restoreData($file);
            ob_end_clean();

            app('session')->flash('notice', _('Backup restored.'));
            redirect(action('\Project\Controllers\Admin\Backup\Index'));

        } catch (\Exception $e) {
            app('session')->flash('error', $e->getMessage());
            redirect(action('\Project\Controllers\Admin\Backup\Index'));
        }
    }
}
