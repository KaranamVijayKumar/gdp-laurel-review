<?php
/**
 * File: Logs.php
 * Created: 05-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin;

use Story\URL;

class Logs extends AdminBaseController
{

    public $selected;

    public $subtitle;

    public $template = 'admin/about/logs';

    public $logs = array();

    public $title;

    public function get($file = '')
    {
        $this->title = _('Log files');
        $this->selected = array('preferences', 'about');

        $file = implode('', func_get_args()) . '.log';
        // show the contents of the file when needed
        if ($file && is_file(SP.'storage/log/' . $file) && file_exists(SP.'storage/log/' . $file)) {
            $this->content = trim(file_get_contents(SP.'storage/log/' . $file));
            $this->template = 'admin/about/log';
            $this->subtitle = $file;
            return true;
        }


        $files = glob(SP.'storage/log/' . '*.log');

        // Sort them and convert to file info
        array_multisort(
            array_map('filemtime', $files),
            SORT_NUMERIC,
            SORT_ASC,
            $files
        );


        $this->logs = array_map(
            function ($file) {

                return new \SplFileInfo($file);
            },
            array_reverse($files)
        );

        foreach ($this->logs as $log) {
            /** @var \SplFileInfo $log */

            /** @noinspection PhpUndefinedFieldInspection */
            $log->displayName = $log->getBasename('.log');

            /** @noinspection PhpUndefinedFieldInspection */
            $log->downloadLink = URL::action('\Project\Controllers\Admin\Logs', array($log->displayName));
        }

        return true;
    }
}
