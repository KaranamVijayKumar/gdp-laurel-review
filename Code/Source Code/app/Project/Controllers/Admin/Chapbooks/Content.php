<?php
/**
 * File: Content.php
 * Created: 25-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Chapbooks;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\Chapbook;
use Project\Models\ChapbookTocContent;
use Story\Error;
use Story\NotFoundException;

class Content extends AdminBaseController
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
    public $title;

    /**
     * @param $id
     */
    public function get($id)
    {

        $this->title = _('Chapbook Content');

        // get the chapbook and the related content and file
        try {

            $this->chapbook = Chapbook::findOrFail((int)$id);

            // get the toc for the chapbook and load the titles
            $this->chapbook->related('toc', array(), 0, 0, array('order' => 'asc'))->loadWithTitles();

            // gets the content
            $this->template = 'admin/chapbooks/contents';

            $this->addTocTitleLinks();
        } catch (NotFoundException $e) {
            redirect(action('\Project\Controllers\Admin\Chapbooks\Index'), array('error' => _('Not found.')));
        } catch (\Exception $e) {
            Error::exception($e);
        }
    }


    /**
     * @return bool
     */
    protected function addTocTitleLinks()
    {

        $toc_title_ids = array();

        // get the author and toc ids
        foreach ($this->chapbook->toc as $toc) {
            if ($toc->is_header || !count($toc->titles)) {
                continue;
            }
            foreach ($toc->titles as $title) {
                $toc_title_ids[] = $title->id;
            }
        }

        if (!count($toc_title_ids)) {
            return false;
        }
        // get the title contents
        return ChapbookTocContent::setTitleLinks($toc_title_ids, $this->chapbook, null);
    }
}
