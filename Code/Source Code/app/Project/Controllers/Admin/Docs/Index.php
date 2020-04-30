<?php
/**
 * File: Index.php
 * Created: 18-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Docs;

use Project\Controllers\Admin\AdminBaseController;
use Project\Support\Docs\DocFactory;
use Project\Support\MenuFactory;

class Index extends AdminBaseController
{
    public $title;
    public $selected;

    public function run()
    {

        $this->title = 'Some docs';
        // detect if admin or not, render the appropiate doc
        $help_path = implode('/', func_get_args());

        $this->template = 'admin/docs/index';
        $factory = new DocFactory($help_path);

//        MenuFactory::addMenus(array('menu-docs' => $factory->buildMenu()));

        list($this->content, $this->title) = $factory->get();

        $this->selected = array('submisssions', 'submisssions_categories');
    }
}
