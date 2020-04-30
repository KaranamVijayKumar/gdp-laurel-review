<?php
/**
 * File: Delete.php
 * Created: 31-07-2014
 *
 * Handles backup deletion
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Backup;

use Project\Controllers\Admin\AdminBaseController;
use Story\Migration\Sqlite;

/**
 * Class Delete
 *
 * @package Project\Controllers\Admin\Backup
 */
class Delete extends AdminBaseController
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

            if (unlink($fileName)) {
                app('session')->flash('notice', _('Backup deleted.'));
                redirect(action('\Project\Controllers\Admin\Backup\Index'));
            } else {
                throw new \Exception('Could not delete the backup <br><q>' . $file . '</q>');
            }


        } catch (\Exception $e) {
            app('session')->flash('error', $e->getMessage());
            redirect(action('\Project\Controllers\Admin\Backup\Index'));
        }

    }
}
