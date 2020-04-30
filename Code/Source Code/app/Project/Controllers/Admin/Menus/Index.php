<?php
/**
 * File: Index.php
 * Created: 02-04-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Menus;

use Project\Controllers\Admin\AdminBaseController;

/**
 * Class Index
 * @package Project\Controllers\Admin\Menus
 */
class Index extends AdminBaseController
{

    /**
     * @var array
     */
    public $selected = array('pages', 'menus');

    /**
     * @var string
     */
    public $title;

    /**
     * Shows the menu list
     */
    public function get()
    {
        $this->title = _('Menus');
    }
}
