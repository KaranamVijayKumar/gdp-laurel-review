<?php
/**
 * File: Content.php
 * Created: 20-01-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Issues;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\Issue;
use Project\Models\IssueTocContent;
use Story\Error;
use Story\NotFoundException;

/**
 * Class Content
 *
 * @package Project\Controllers\Admin\Issues
 */
class Content extends AdminBaseController
{

    /**
     * @var Issue
     */
    public $issue;

    /**
     * Selected menus
     *
     * @var array
     */
    public $selected = array('issues');

    /**
     * @var string
     */
    public $title;

    /**
     * @param $id
     */
    public function get($id)
    {

        $this->title = _('Issue Content');

        // get the issue and the related content and file
        try {

            $this->issue = Issue::findOrFail((int)$id);

            // get the toc for the issue and load the titles
            $this->issue->related('toc', array(), 0, 0, array('order' => 'asc'))->loadWithTitles();

            // gets the content
            $this->template = 'admin/issues/contents';

            $this->addTocTitleLinks();
        } catch (NotFoundException $e) {
            redirect(action('\Project\Controllers\Admin\Issues\Index'), array('error' => _('Not found.')));
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
        foreach ($this->issue->toc as $toc) {
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
        return IssueTocContent::setTitleLinks($toc_title_ids, $this->issue, null);
    }
}
