<?php
/**
 * File: Delete.php
 * Created: 25-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Chapbooks;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\Chapbook;
use Project\Models\ChapbookFile;
use Story\Error;
use Story\NotFoundException;

/**
 * Class Delete
 * @package Project\Controllers\Admin\Chapbooks
 */
class Delete extends AdminBaseController
{
    /**
     * @var Chapbook
     */
    public $chapbook;

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
    public $template = 'admin/chapbooks/delete';

    /**
     * Page title
     *
     * @var string
     */
    public $title;

    /**
     * Shows the chapbook creation page
     *
     * @param int $id
     */
    public function get($id)
    {

        $this->title = _('Delete Chapbook');

        // get the chapbook and the related content and file
        try {

            $this->chapbook = Chapbook::findOrFail((int) $id);

            // get the chapbook file
            $this->chapbook->cover_image = ChapbookFile::one(
                array(
                    'chapbookable_id' => $this->chapbook->id,
                    'chapbookable_type' => get_class($this->chapbook)
                )
            );
        } catch (NotFoundException $e) {
            redirect(action('\Project\Controllers\Admin\Chapbooks\Index'), array('error' => _('Not found.')));
        } catch (\Exception $e) {
            Error::exception($e);
        }
    }

    /**
     * Deletes the chapbook
     *
     * @param $id
     */
    public function post($id)
    {

        // get the chapbook and the related content and file
        try {

            $this->chapbook = Chapbook::findOrFail((int) $id);

            // get the chapbook file
            $this->chapbook->cover_image = ChapbookFile::one(
                array(
                    'chapbookable_id' => $this->chapbook->id,
                    'chapbookable_type' => get_class($this->chapbook)
                )
            );

            if ($this->chapbook->deleteWithFiles()) {
                redirect(
                    action('\Project\Controllers\Admin\Chapbooks\Index'),
                    array(
                        'notice' => _('Deleted.'),
                    )
                );
            }
        } catch (NotFoundException $e) {
            redirect(action('\Project\Controllers\Admin\Chapbooks\Index'), array('error' => _('Not found.')));
        } catch (\Exception $e) {
            Error::exception($e);
        }
    }
}
