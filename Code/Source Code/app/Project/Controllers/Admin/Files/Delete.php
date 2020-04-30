<?php
/**
 * File: Delete.php
 * Created: 20-04-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Files;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\PublicAsset;
use Story\Error;
use Story\NotFoundException;

class Delete extends AdminBaseController
{
    /**
     * @var PublicAsset
     */
    public $file;


    /**
     * Saves the file
     * @param $id
     */
    public function delete($id)
    {
        try {

            $this->file = PublicAsset::findOrFail($id);

            if ($this->file->delete()) {
                redirect(
                    action('\Project\Controllers\Admin\Files\Index'),
                    array(
                        'notice' => _('Deleted.')
                    )
                );
            }

        } catch (NotFoundException $e) {

            redirect(
                action('\Project\Controllers\Admin\Files\Index'),
                array(
                    'error' => _('File not found.')
                )
            );

        } catch (\Exception $e) {
            Error::exception($e);
        }
    }
}
