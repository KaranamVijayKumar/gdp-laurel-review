<?php
/**
 * File: Dashboard.php
 * Created: 30-07-2014
 *
 * Admin dashboard controller
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin;

/**
 * Class Dashboard
 *
 * @package Project\Controllers\Admin
 */
class Dashboard extends AdminBaseController
{

    public $title;

    public function run()
    {

        $this->content = 'To be implemented.';

        $this->title = _('Dashboard');
    }
}
