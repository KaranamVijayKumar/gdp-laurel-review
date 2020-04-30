<?php
/**
 * File: ContentCreate.php
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
use Story\Error;
use Story\NotFoundException;

class ContentCreate extends AdminBaseController
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
    public $template = 'admin/chapbooks/create_content';

    /**
     * @var string
     */
    public $title;

    /**
     * @var ChapbookToc
     */
    public $toc;

    /**
     * @var ChapbookTocTitle
     */
    public $toc_title;

    /**
     * Shows the form
     *
     * @param        $chapbook_id
     * @param string $title_id
     */
    public function get($chapbook_id, $title_id = '')
    {

        $this->initChapbookAndTitle($chapbook_id, $title_id);

        $this->title = $this->toc->content . ' - ' . $this->toc_title->content;

    }

    /**
     * Initializes the chapbook and title
     *
     * @param $chapbook_id
     * @param $title_id
     */
    protected function initChapbookAndTitle($chapbook_id, $title_id)
    {

        try {
            $chapbook_id = trim(html2text($chapbook_id));
            $title_id = trim(html2text($title_id));

            if (!$chapbook_id || !$title_id) {
                throw new NotFoundException('Chapbook content not found.');
            }

            $this->chapbook = Chapbook::findOrFail($chapbook_id);
            $this->toc_title = ChapbookTocTitle::findOrFail($title_id);
            $this->toc_title->load();

            $this->toc = $this->toc_title->toc;
            $this->toc->load();

            if ($this->toc->is_header) {
                throw new NotFoundException('Chapbook content cannot be added since the TOC item is a header.');
            }
        } catch (NotFoundException $e) {
            redirect(action('\Project\Controllers\Admin\Chapbooks\Index'), array('error' => _('Not found.')));
        } catch (\Exception $e) {
            Error::exception($e);
        }
    }

    /**
     * Saves the toc content
     *
     * @param      $chapbook_id
     * @param null $title_id
     *
     */
    public function post($chapbook_id, $title_id = null)
    {

        $this->initChapbookAndTitle($chapbook_id, $title_id);

        // validate the form
        // validate the user data
        $v = new ChapbookContentValidator($_POST);
        $v->addCreateRules();
        // if valid, we update the chapbook, contents and file
        if ($v->validate() &&
            ($toc_title = ChapbookTocContent::createFromForm($v->data(), $this->toc, $this->toc_title))
        ) {
            // redirect to the chapbook edit page
            event('chapbook.edited', $this->chapbook);

            redirect(
                action('\Project\Controllers\Admin\Chapbooks\Content', array($this->chapbook->id)),
                array('notice' => _('Created.'), '__fields' => array())
            );
        }
        // redirect to the prev page and display the errors
        redirect(
            action('\Project\Controllers\Admin\Chapbooks\ContentCreate', array($this->chapbook->id, $title_id)),
            array(
                'errorTitle' => _('Fix the following errors:'),
                'error'      => $v->errorsToNotification(),
            )
        );

    }
}
