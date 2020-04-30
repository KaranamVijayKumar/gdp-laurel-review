<?php
/**
 * File: Create.php
 * Created: 25-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Chapbooks;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\Chapbook;
use Project\Services\Chapbooks\ChapbookValidator;

class Create extends AdminBaseController
{
    /**
     * Selected menus
     *
     * @var array
     */
    public $selected = array('issues', 'chapbooks');

    /**
     * View template
     *
     * @var string
     */
    public $template = 'admin/chapbooks/create';

    /**
     * Page title
     *
     * @var string
     */
    public $title;

    /**
     * Shows the chapbook creation page
     */
    public function get()
    {

        $this->title = _('New Chapbook');
    }

    public function post()
    {

        $v = new ChapbookValidator($_POST);
        $v->addCreateRules();
        // validate the post data && create the chapbook
        /** @var Chapbook $chapbook */
        if ($v->validate() && ($chapbook = Chapbook::createFromForm($v->data()))) {


            event('chapbook.created', $chapbook);

            redirect(
                action('\Project\Controllers\Admin\Chapbooks\Show', array($chapbook->id)),
                array('notice' => _('Created.'))
            );
        }

        // redirect to the prev page and display the errors
        redirect(
            action('\Project\Controllers\Admin\Chapbooks\Create'),
            array(
                'errorTitle' => _('Fix the following errors:'),
                'error'      => $v->errorsToNotification(),
            )
        );
    }
}
