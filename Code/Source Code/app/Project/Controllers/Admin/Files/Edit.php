<?php
/**
 * File: Edit.php
 * Created: 16-04-2015
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

class Edit extends AdminBaseController
{

    /**
     * @var PublicAsset
     */
    public $file;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $template = 'admin/files/edit';

    /**
     * @var array
     */
    public $selected = array('pages', 'files');

    /**
     * Shows the file management view
     *
     * @param $id
     */
    public function get($id)
    {
        try {

            $this->file = PublicAsset::findOrFail($id);

            $this->title = _('Manage file');

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

    /**
     * Saves the file
     * @param $id
     */
    public function post($id)
    {
        try {

            $this->file = PublicAsset::findOrFail($id);

            if ($this->file->setStatus((int)post('status'), true)) {
                redirect(
                    action('\Project\Controllers\Admin\Files\Edit', array($this->file->id)),
                    array(
                        'notice' => _('Saved.')
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
