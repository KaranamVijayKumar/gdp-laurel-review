<?php
/**
 * File: Edit.php
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
use Project\Services\Chapbooks\ChapbookValidator;
use Story\Error;
use Story\NotFoundException;

/**
 * Class Edit
 * @package Project\Controllers\Admin\Chapbooks
 */
class Edit extends AdminBaseController
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
    public $template = 'admin/chapbooks/edit';

    /**
     * Page title
     *
     * @var string
     */
    public $title;

    /**
     * Shows the chapbook edit page
     *
     * @param int $id
     */
    public function get($id)
    {

        $this->title = _('Chapbook Properties');

        // get the chapbook and the related content and file
        try {

            $this->chapbook = Chapbook::findOrFail((int) $id);

            // load the chapbook contents
            $this->chapbook->contents->load();

            // get the chapbook file
            $this->chapbook->cover_image = ChapbookFile::one(
                array(
                    'chapbookable_id' => $this->chapbook->id,
                    'chapbookable_type' => get_class($this->chapbook)
                )
            );


            $this->chapbook->default_content = new ChapbookContent();
            $this->chapbook->default_content->set(array('content' => ''));
        } catch (NotFoundException $e) {
            redirect(action('\Project\Controllers\Admin\Chapbooks\Index'), array('error' => _('Not found.')));
        } catch (\Exception $e) {
            Error::exception($e);
        }
    }

    /**
     * @param $id
     */
    public function post($id)
    {
        try {
            $this->chapbook = Chapbook::findOrFail((int) $id);

            // load the chapbook contents
            $this->chapbook->contents->load();

            // chapbook cover image
            $this->chapbook->cover_image = ChapbookFile::one(
                array(
                    'chapbookable_id' => $this->chapbook->id,
                    'chapbookable_type' => get_class($this->chapbook)
                )
            );

            // validate the user data
            $v = new ChapbookValidator($_POST);
            $v->addEditRules($this->chapbook);

            // if valid, we update the chapbook, contents and file
            if ($v->validate() && ($chapbook = $this->chapbook->updateFromForm($v->data()))) {

                // redirect to the chapbook edit page
                event('chapbook.edited', $chapbook);

                redirect(
                    action('\Project\Controllers\Admin\Chapbooks\Edit', array($this->chapbook->id)),
                    array('notice' => _('Saved.'), '__fields' => array())
                );
            }

            // redirect to the prev page and display the errors
            redirect(
                action('\Project\Controllers\Admin\Chapbooks\Edit', array($this->chapbook->id)),
                array(
                    'errorTitle' => _('Fix the following errors:'),
                    'error'      => $v->errorsToNotification(),
                )
            );
        } catch (NotFoundException $e) {
            redirect(action('\Project\Controllers\Admin\Chapbooks\Index'), array('error' => _('Not found.')));
        } catch (\Exception $e) {
            Error::exception($e);
        }
    }
}
