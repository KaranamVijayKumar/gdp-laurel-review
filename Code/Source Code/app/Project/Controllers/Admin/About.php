<?php
/**
 * File: About.php
 * Created: 05-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin;


use Project\Support\AboutFactory;

/**
 * Class About
 *
 * @package Project\Controllers\Admin
 */
class About extends AdminBaseController
{

    /**
     * @var
     */
    public $list;

    /**
     * @var
     */
    public $selected;

    /**
     * @var string
     */
    public $template = 'admin/about/index';

    /**
     * @var
     */
    public $title;

    /**
     *
     */
    public function get()
    {

        $this->title = _('About');
        $this->selected = array('preferences', 'about');
        $factory = new AboutFactory();



        // WYSIWYG html editor
        $factory->extend(
            _('WYSIWYG html editor'),
            function () {

                return array(
                    'name'        => "Imperavi Redactor",
                    'url'         => "http://imperavi.com/redactor/",
                    'description' => "Beautiful and easy-to-use WYSIWYG html editor",
                    'version'     => '~10.0.9',
                );

            }
        );

        $this->list = $factory->get();


    }
}
