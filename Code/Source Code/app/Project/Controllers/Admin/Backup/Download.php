<?php
/**
 * File: Download.php
 * Created: 31-07-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Backup;

use Project\Controllers\Admin\AdminBaseController;
use Story\Migration\Sqlite;

class Download extends AdminBaseController
{
    public function get($file = '')
    {
        try {

            $migration = new Sqlite();
            $fileName = $migration->getBackupPath() . $file . '.gz';
            // check if the file is supplied
            if (!$file || !file_exists($fileName)) {
                throw new \Exception(_('No such backup.'));
            }

            download($fileName);

        } catch (\Exception $e) {
            app('session')->flash('error', $e->getMessage());
            redirect(action('\Project\Controllers\Admin\Backup\Index'));
        }
    }
}
