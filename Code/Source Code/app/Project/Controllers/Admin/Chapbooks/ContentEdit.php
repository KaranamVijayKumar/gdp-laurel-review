<?php
/**
 * File: ContentEdit.php
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
use Project\Models\ChapbookTocContent;
use Project\Models\ChapbookTocTitle;
use Project\Services\Chapbooks\ChapbookContentValidator;
use Story\Dispatch;
use Story\Error;
use Story\NotFoundException;

class ContentEdit extends AdminBaseController
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
     * @var string
     */
    public $template = 'admin/chapbooks/edit_content';

    /**
     * @var string
     */
    public $title;

    /**
     * @var ChapbookToc
     */
    public $toc;

    /**
     * @var ChapbookTocContent
     */
    public $toc_content;

    /**
     * @var ChapbookTocTitle
     */
    public $toc_title;

    /**
     * @param          $route
     * @param Dispatch $dispatch
     */
    public function __construct($route, Dispatch $dispatch)
    {

        parent::__construct($route, $dispatch);
    }

    /**
     * Delete the toc content
     *
     * @param      $chapbook_id
     * @param null $content_id
     */
    public function delete($chapbook_id, $content_id = null)
    {

        $this->initChapbookAndContent($chapbook_id, $content_id);

        if ($this->toc_content->delete()) {

            event('chapbook.edited', $this->chapbook);

            redirect(
                action('\Project\Controllers\Admin\Chapbooks\Content', array($this->chapbook->id)),
                array('notice' => _('Content deleted.'), '__fields' => array())
            );
        }

        redirect(
            action('\Project\Controllers\Admin\Chapbooks\Content', array($this->chapbook->id)),
            array('error' => _('There was an error deleting the content.'), '__fields' => array())
        );
    }

    /**
     * Initializes the chapbook and content id
     *
     * @param $chapbook_id
     * @param $content_id
     */
    protected function initChapbookAndContent($chapbook_id, $content_id)
    {

        try {
            $chapbook_id = trim(html2text($chapbook_id));
            $content_id = trim(html2text($content_id));

            if (!$chapbook_id || !$content_id) {
                throw new NotFoundException('Chapbook content not found.');
            }

            $this->chapbook = Chapbook::findOrFail($chapbook_id);
            $this->toc_content = ChapbookTocContent::findOrFail($content_id);

            $this->toc_title = $this->toc_content->toc_title;
            $this->toc_title->load();

            $this->toc = $this->toc_title->toc;
            $this->toc->load();
        } catch (NotFoundException $e) {
            redirect(action('\Project\Controllers\Admin\Chapbooks\Index'), array('error' => _('Not found.')));
        } catch (\Exception $e) {
            Error::exception($e);
        }
    }

    /**
     * Shows the content edit form
     *
     * @param      $chapbook_id
     * @param null $content_id
     */
    public function get($chapbook_id, $content_id = null)
    {

        $this->initChapbookAndContent($chapbook_id, $content_id);

        $this->title = acronym($this->toc->content) . ': ' . $this->toc_title->content;
    }

    /**
     * Saves the toc content
     *
     * @param      $chapbook_id
     * @param null $content_id
     */
    public function post($chapbook_id, $content_id = null)
    {

        $this->initChapbookAndContent($chapbook_id, $content_id);

        // validate the form
        // validate the user data
        $v = new ChapbookContentValidator($_POST);
        $v->addEditRules();
        // if valid, we update the chapbook, contents and file
        if ($v->validate() && ($this->toc_content->updateFromForm($v->data()))) {
            // redirect to the chapbook edit page
            event('chapbook.edited', $this->chapbook);

            redirect(
                action('\Project\Controllers\Admin\Chapbooks\Content', array($this->chapbook->id)),
                array('notice' => _('Saved.'), '__fields' => array())
            );
        }
        // redirect to the prev page and display the errors
        redirect(
            action('\Project\Controllers\Admin\Chapbooks\ContentEdit', array($this->chapbook->id, $this->toc_content->id)),
            array(
                'errorTitle' => _('Fix the following errors:'),
                'error'      => $v->errorsToNotification(),
            )
        );

    }
}
