<?php
/**
 * File: Toc.php
 * Created: 25-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Chapbooks;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\Chapbook;
use Project\Models\ChapbookToc;
use Project\Services\Chapbooks\ChapbookTocValidator;
use Story\Error;
use Story\NotFoundException;

class Toc extends AdminBaseController
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
    public $template = 'admin/chapbooks/toc';

    /**
     * Page title
     *
     * @var string
     */
    public $title;

    /**
     * Shows the chapbook toc page
     *
     * @param int $id
     */
    public function get($id)
    {

        $this->title = _('Chapbook TOC');

        // get the chapbook and the related content and file
        try {

            $this->chapbook = Chapbook::findOrFail((int) $id);

            // get the toc for the chapbook and load the titles
            $this->chapbook->related('toc', array(), 0, 0, array('order' => 'asc'))->loadWithTitles();
        } catch (NotFoundException $e) {
            redirect(action('\Project\Controllers\Admin\Chapbooks\Index'), array('error' => _('Not found.')));
        } catch (\Exception $e) {
            Error::exception($e);
        }
    }

    /**
     * Saves the chapbook toc
     *
     * @param $id
     */
    public function post($id)
    {
        try {

            $this->chapbook = Chapbook::findOrFail((int) $id);

            // validate the user data
            $v = new ChapbookTocValidator($_POST);
            $v->addRules($this->chapbook);

            // if valid, we update the chapbook, contents and file
            if ($v->validate() && (ChapbookToc::updateTocForPublicationFromForm($this->chapbook, ($v->data())))) {

                // redirect to the chapbook edit page
                event('chapbook.toc.updated', $this->chapbook);

                redirect(
                    action('\Project\Controllers\Admin\Chapbooks\Toc', array($this->chapbook->id)),
                    array('notice' => _('Saved.'), '__fields' => array())
                );
            }

            // redirect to the prev page and display the errors
            redirect(
                action('\Project\Controllers\Admin\Chapbooks\Toc', array($this->chapbook->id)),
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
