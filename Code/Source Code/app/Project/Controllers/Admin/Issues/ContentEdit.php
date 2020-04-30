<?php
/**
 * File: ContentEdit.php
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
use Story\Dispatch;
use Story\Error;
use Story\NotFoundException;

/**
 * Class ContentEdit
 *
 * @package Project\Controllers\Admin\Issues
 */
class ContentEdit extends AdminBaseController
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
    public $template = 'admin/issues/edit_content';

    /**
     * @var string
     */
    public $title;

    /**
     * @var IssueToc
     */
    public $toc;

    /**
     * @var IssueTocContent
     */
    public $toc_content;

    /**
     * @var IssueTocTitle
     */
    public $toc_title;

    public function __construct($route, Dispatch $dispatch)
    {

        parent::__construct($route, $dispatch);
    }

    /**
     * Delete the toc content
     *
     * @param      $issue_id
     * @param null $content_id
     */
    public function delete($issue_id, $content_id = null)
    {

        $this->initIssueAndContent($issue_id, $content_id);

        if ($this->toc_content->delete()) {

            event('issue.edited', $this->issue);

            redirect(
                action('\Project\Controllers\Admin\Issues\Content', array($this->issue->id)),
                array('notice' => _('Content deleted.'), '__fields' => array())
            );
        }

        redirect(
            action('\Project\Controllers\Admin\Issues\Content', array($this->issue->id)),
            array('error' => _('There was an error deleting the content.'), '__fields' => array())
        );
    }

    /**
     * Initializes the issue and content id
     *
     * @param $issue_id
     * @param $content_id
     */
    protected function initIssueAndContent($issue_id, $content_id)
    {

        try {
            $issue_id = trim(html2text($issue_id));
            $content_id = trim(html2text($content_id));

            if (!$issue_id || !$content_id) {
                throw new NotFoundException('Issue content not found.');
            }

            $this->issue = Issue::findOrFail($issue_id);
            $this->toc_content = IssueTocContent::findOrFail($content_id);

            $this->toc_title = $this->toc_content->toc_title;
            $this->toc_title->load();

            $this->toc = $this->toc_title->toc;
            $this->toc->load();
        } catch (NotFoundException $e) {
            redirect(action('\Project\Controllers\Admin\Issues\Index'), array('error' => _('Not found.')));
        } catch (\Exception $e) {
            Error::exception($e);
        }
    }

    /**
     * Shows the content edit form
     *
     * @param      $issue_id
     * @param null $content_id
     */
    public function get($issue_id, $content_id = null)
    {

        $this->initIssueAndContent($issue_id, $content_id);

        $this->title = acronym($this->toc->content) . ': ' . $this->toc_title->content;
    }

    /**
     * Saves the toc content
     *
     * @param      $issue_id
     * @param null $content_id
     */
    public function post($issue_id, $content_id = null)
    {

        $this->initIssueAndContent($issue_id, $content_id);

        // validate the form
        // validate the user data
        $v = new IssueContentValidator($_POST);
        $v->addEditRules();
        // if valid, we update the issue, contents and file
        if ($v->validate() && ($this->toc_content->updateFromForm($v->data()))) {
            // redirect to the issue edit page
            event('issue.edited', $this->issue);

            redirect(
                action('\Project\Controllers\Admin\Issues\Content', array($this->issue->id)),
                array('notice' => _('Saved.'), '__fields' => array())
            );
        }
        // redirect to the prev page and display the errors
        redirect(
            action('\Project\Controllers\Admin\Issues\ContentEdit', array($this->issue->id, $this->toc_content->id)),
            array(
                'errorTitle' => _('Fix the following errors:'),
                'error'      => $v->errorsToNotification(),
            )
        );

    }
}
