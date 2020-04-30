<?php
/**
 * File: Index.php
 * Created: 31-07-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Backup;

use PDO;
use Project\Controllers\Admin\AdminBaseController;
use Story\Dispatch;
use Story\Error;
use Story\Migration\Sqlite;

class Index extends AdminBaseController
{

    public $selected = array('preferences', 'backup');

    public $template = 'admin/backup/index';

    public $backups = array();

    public $title;

    protected $session;

    public function __construct($route, Dispatch $dispatch)
    {
        $this->session = app('session');
        parent::__construct($route, $dispatch);
    }


    public function get()
    {

        $this->title = _('Backup/Restore');

        // get the list of backups
        $migration = new Sqlite();
        $files = glob($migration->getBackupPath() . '*.gz');

        // Sort them and convert to file info
        array_multisort(
            array_map('filemtime', $files),
            SORT_NUMERIC,
            SORT_ASC,
            $files
        );
        $this->backups = array_map(
            function ($file) {

                return new \SplFileInfo($file);
            },
            array_reverse($files)
        );
        // Add the download/restore/delete links, easing the view development
        foreach ($this->backups as $backup) {
            /** @var \SplFileInfo $backup */

            /** @noinspection PhpUndefinedFieldInspection */
            $backup->displayName = $backup->getBasename('.gz');

            /** @noinspection PhpUndefinedFieldInspection */
            $backup->downloadLink = action('\Project\Controllers\Admin\Backup\Download', array($backup->displayName));

            /** @noinspection PhpUndefinedFieldInspection */
            $backup->restoreLink = action('\Project\Controllers\Admin\Backup\Restore', array($backup->displayName));

            /** @noinspection PhpUndefinedFieldInspection */
            $backup->deleteLink = action('\Project\Controllers\Admin\Backup\Delete', array($backup->displayName));
        }

    }

    public function post()
    {
        try {
            // Perform a backup
            // Start database connection
            $db = load_database();

            $migrationName = '\Story\Migration\\' . studly($db->pdo->getAttribute(PDO::ATTR_DRIVER_NAME));
            /** @var \Story\Migration\Mysql $migration */
            $migration = new $migrationName;

            // Set database connection
            $migration->db = $db;

            // Load table configuration
            $migration->tables = require(SP . 'config/migrations.php');

            // turn off output
            ob_start();
            $migration->backupData();
            ob_end_clean();

            $this->session->flash('notice', _('Backup successful.'));
            $this->session->flash('notifSymbol', 'info-circle');
            redirect(action('\Project\Controllers\Admin\Backup\Index'));
        } catch (\Exception $e) {
            Error::exception($e);
            $this->session->flash('errorTitle', _('Backup failed'));
            $this->session->flash('error', $e->getMessage());
            redirect(action('\Project\Controllers\Admin\Backup\Index'));
        }
    }
}
