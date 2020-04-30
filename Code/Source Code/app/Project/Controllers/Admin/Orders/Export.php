<?php
/**
 * File: Export.php
 * Created: 28-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Orders;

use Project\Controllers\Admin\AdminBaseController;
use Project\Services\Exporter\ExporterFactory;
use Project\Services\Exporter\InvalidExporterException;
use Project\Support\Orders\Validator;
use Story\Dispatch;
use Story\Error;

class Export extends AdminBaseController
{
    /**
     * @var
     */
    public $title;

    /**
     * @var ExporterFactory
     */
    public $exporter;

    /**
     * Constructor
     *
     * @param          $route
     * @param Dispatch $dispatch
     */
    public function __construct($route, Dispatch $dispatch)
    {
        $this->exporter = app('container')->make('\Project\Services\Exporter\ExporterFactoryInterface');
        parent::__construct($route, $dispatch);
    }

    /**
     *
     */
    public function post()
    {
        try {

            $v = Validator::export($_POST, $this->exporter);

            if ($v->validate()) {
                $data = $v->data();

                // we generate the export and export the file
                $data = $this->exporter->generate($data);

                $this->exporter->download('csv', $data);
            }

            redirect(
                action('\Project\Controllers\Admin\Orders\Index'),
                array(
                    'error' => _('Export options were invalid.')
                )
            );
        } catch (InvalidExporterException $e) {
            redirect(
                action('\Project\Controllers\Admin\Orders\Index'),
                array(
                    'error' => $e->getMessage()
                )
            );
        } catch (\Exception $e) {
            Error::exception($e);
        }

//        dd($_POST);
    }
}
