<?php
/**
 * File: Show.php
 * Created: 25-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Chapbooks;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\Chapbook;
use Project\Models\ChapbookContent;
use Project\Models\ChapbookFile;
use Story\Error;
use Story\NotFoundException;

class Show extends AdminBaseController
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
    public $template = 'admin/chapbooks/show';

    /**
     * Page title
     *
     * @var string
     */
    public $title;

    /**
     * Shows the issue creation page
     *
     * @param int $id
     */
    public function get($id)
    {

        $this->title = _('Chapbook');

        // get the issue and the related content and file
        try {

            $this->chapbook = Chapbook::findOrFail((int)$id);

            // get the issue file
            $this->chapbook->cover_image = ChapbookFile::one(
                array(
                    'chapbookable_id'   => $this->chapbook->id,
                    'chapbookable_type' => get_class($this->chapbook)
                )
            );

            // get the issue content
            $name = current(ChapbookContent::$required_sections);
            $description = $this->chapbook->related(
                'contents',
                array(
                    'name' => $name,
                    1
                )
            );

            if ($description) {
                $description->load();
            }

            $this->chapbook->description = $description->first();
        } catch (NotFoundException $e) {
            redirect(action('\Project\Controllers\Admin\Chapbooks\Index'), array('error' => _('Not found.')));
        } catch (\Exception $e) {
            Error::exception($e);
        }
    }
}
