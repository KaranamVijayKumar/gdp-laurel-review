<?php
/**
 * File: Create.php
 * Created: 27-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Exporters;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\Export;
use Project\Services\Exporter\ExporterFactory;
use Project\Services\Exporter\Validator;
use Story\Dispatch;
use Story\Error;

/**
 * Class Create
 * @package Project\Controllers\Admin\Exporters
 */
class Create extends AdminBaseController
{
    /**
     * @var array
     */
    public $selected = array('preferences', 'exporters');

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $template = 'admin/exporters/create';

    /**
     * @var ExporterFactory
     */
    public $exporter;

    /**
     * @param          $route
     * @param Dispatch $dispatch
     */
    public function __construct($route, Dispatch $dispatch)
    {
        $this->exporter = app('container')->make('\Project\Services\Exporter\ExporterFactoryInterface');
        parent::__construct($route, $dispatch);
    }

    /**
     * Shows the create exporter page
     */
    public function get()
    {
        $this->title = _('Create Exporter');

    }

    /**
     * Saves the newly created export
     */
    public function post()
    {
        try {

            // validate the form
            $v = Validator::create($_POST, $this->exporter);

            if ($v->validate() && ($export = Export::createFromForm($v->data(), $this->exporter))) {
                redirect(
                    action('\Project\Controllers\Admin\Exporters\Index'),
                    array(
                        'notice' => _('Created.'),
                    )
                );
            }

            redirect(
                action('\Project\Controllers\Admin\Exporters\Create'),
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
