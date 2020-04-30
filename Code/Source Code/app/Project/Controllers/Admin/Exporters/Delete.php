<?php
/**
 * File: Delete.php
 * Created: 27-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Exporters;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\Export;
use Story\Error;
use Story\NotFoundException;

/**
 * Class Delete
 * @package Project\Controllers\Admin\Exporters
 */
class Delete extends AdminBaseController
{

    /**
     * Removes the exporter
     */
    public function delete($id)
    {
        try {

            $this->item = Export::findOrFail((int)$id);

            if ($this->item->delete()) {
                redirect(
                    action('\Project\Controllers\Admin\Exporters\Index'),
                    array(
                        'notice' => _('Deleted.')
                    )
                );
            } else {
                throw new \Exception('Could not delete the export #' . $this->item->id);
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
    }
}
