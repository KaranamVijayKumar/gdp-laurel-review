<?php
/**
 * File: Create.php
 * Created: 21-04-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Pages;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\Page;
use Project\Support\Pages\Validator;
use Story\Dispatch;
use Story\Error;

/**
 * Class Create
 * @package Project\Controllers\Admin\Pages
 */
class Create extends AdminBaseController
{

    /**
     * @var array
     */
    public $selected = array('pages');

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $template = 'admin/pages/create';

    /**
     * Required editable sections
     *
     * @var array
     */
    public $required_sections = array('content');

    /**
     * Optional editable sections
     *
     * @var array
     */
    public $optional_sections = array();

    /**
     * @param $route
     * @param Dispatch $dispatch
     */
    public function __construct($route, Dispatch $dispatch)
    {
        require_once SP . 'Project/Support/Pages/pages_helpers.php';

        parent::__construct($route, $dispatch);
    }

    /**
     * Shows the page creation form
     */
    public function get()
    {
        list($this->required_sections, $this->optional_sections) = array_values(get_pages_sections());

        $this->title = _('New Page');
    }

    /**
     * Creates the page
     */
    public function post()
    {
        try {

            // validate the form
            $v = Validator::create($_POST);

            if ($v->validate() && ($page = Page::createFromForm($v->data()))) {
                redirect(
                    action('\Project\Controllers\Admin\Pages\Edit', array($page->id)),
                    array(
                        'notice' => _('Created.'),
                    )
                );
            }

            redirect(
                action('\Project\Controllers\Admin\Pages\Create'),
                array(
                    'errorTitle' => _('Fix the following errors:'),
                    'error'      => $v->errorsToNotification(),
                )
            );

        } catch (\Exception $e) {
            Error::exception($e);
        }
    }
}
