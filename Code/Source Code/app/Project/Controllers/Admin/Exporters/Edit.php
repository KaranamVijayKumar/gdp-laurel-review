<?php
/**
 * File: Edit.php
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
use Story\NotFoundException;

/**
 * Class Edit
 * @package Project\Controllers\Admin\Exporters
 */
class Edit extends AdminBaseController
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
     * @var ExporterFactory
     */
    public $exporter;

    /**
     * @var Export
     */
    public $item;

    /**
     * @var string
     */
    public $template = 'admin/exporters/edit';

    /**
     * @var string
     */
    public $exporter_type;

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
     *
     * @param string $id
     */
    public function get($id)
    {

        try {

            $this->item = Export::findOrFail((int) $id);
            $exporter = $this->exporter->getByExporter($this->item->exporter);

            if ($exporter) {
                $this->exporter_type = $exporter->getCategoryName();
            }


        } catch (NotFoundException $e) {

            redirect(
                action('\Project\Controllers\Admin\Exporters\Index'),
                array(
                    'error' => _('Exporter not found.')
                )
            );

        } catch (\Exception $e) {
            Error::exception($e);
        }

        $this->title = _('Edit Exporter');

    }

    /**
     * Shows the create exporter page
     *
     * @param string $id
     */
    public function post($id)
    {

        try {

            $this->item = Export::findOrFail((int) $id);

            // validate and update
            // validate the form
            $v = Validator::edit($_POST, $this->exporter);

            if ($v->validate() && ($export = $this->item->updateFromForm($v->data(), $this->exporter))) {
                redirect(
                    action('\Project\Controllers\Admin\Exporters\Edit', array($this->item->id)),
                    array(
                        'notice' => _('Saved.'),
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


        } catch (NotFoundException $e) {

            redirect(
                action('\Project\Controllers\Admin\Exporters\Index'),
                array(
                    'error' => _('Exporter not found.')
                )
            );

        } catch (\Exception $e) {
            Error::exception($e);
        }

    }
}
