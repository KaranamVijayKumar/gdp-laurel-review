<?php
/**
 * File: ContentCreate.php
 * Created: 20-01-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Controllers\Admin\Issues;

use Project\Controllers\Admin\AdminBaseController;
use Project\Models\Issue;
use Project\Models\IssueToc;
use Project\Models\IssueTocContent;
use Project\Models\IssueTocTitle;
use Project\Services\Issues\IssueContentValidator;
use Story\Error;
use Story\NotFoundException;

class ContentCreate extends AdminBaseController
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
    public $template = 'admin/issues/create_content';

    /**
     * @var string
     */
    public $title;

    /**
     * @var IssueToc
     */
    public $toc;

    /**
     * @var IssueTocTitle
     */
    public $toc_title;

    /**
     * Shows the form
     *
     * @param        $issue_id
     * @param string $title_id
     */
    public function get($issue_id, $title_id = '')
    {

        $this->initIssueAndTitle($issue_id, $title_id);

        $this->title = $this->toc->content . ' - ' . $this->toc_title->content;

    }

    /**
     * Initializes the issue and title
     *
     * @param $issue_id
     * @param $title_id
     */
    protected function initIssueAndTitle($issue_id, $title_id)
    {

        try {
            $issue_id = trim(html2text($issue_id));
            $title_id = trim(html2text($title_id));

            if (!$issue_id || !$title_id) {
                throw new NotFoundException('Issue content not found.');
            }

            $this->issue = Issue::findOrFail($issue_id);
            $this->toc_title = IssueTocTitle::findOrFail($title_id);
            $this->toc_title->load();

            $this->toc = $this->toc_title->toc;
            $this->toc->load();

            if ($this->toc->is_header) {
                throw new NotFoundException('Issue content cannot be added since the TOC item is a header.');
            }
        } catch (NotFoundException $e) {
            redirect(action('\Project\Controllers\Admin\Issues\Index'), array('error' => _('Not found.')));
        } catch (\Exception $e) {
            Error::exception($e);
        }
    }

    /**
     * Saves the toc content
     *
     * @param      $issue_id
     * @param null $title_id
     *
     */
    public function post($issue_id, $title_id = null)
    {

        $this->initIssueAndTitle($issue_id, $title_id);

        // validate the form
        // validate the user data
        $v = new IssueContentValidator($_POST);
        $v->addCreateRules();
        // if valid, we update the issue, contents and file
        if ($v->validate() &&
            ($toc_title = IssueTocContent::createFromForm($v->data(), $this->toc, $this->toc_title))
        ) {
            // redirect to the issue edit page
            event('issue.edited', $this->issue);

            redirect(
                action('\Project\Controllers\Admin\Issues\Content', array($this->issue->id)),
                array('notice' => _('Created.'), '__fields' => array())
            );
        }
        // redirect to the prev page and display the errors
        redirect(
            action('\Project\Controllers\Admin\Issues\ContentCreate', array($this->issue->id, $title_id)),
            array(
                'errorTitle' => _('Fix the following errors:'),
                'error'      => $v->errorsToNotification(),
            )
        );

    }
}
